<?php 
session_start();

require('../common.php');

if ($_SESSION['id'] < 3) {
	error('Unauthorized User', 'You are not an admin');
}

$db = new DB();

if (isset($_GET['deleteId'])) {
	$userId = $db -> select('SELECT user_id FROM applications
                             WHERE id = ' . $db->quote($_GET['deleteId']))[0]['user_id'];

	$db -> query('DELETE users_additional FROM users_additional
                  JOIN users ON users_additional.user_id = users.id
                  JOIN applications ON applications.user_id = users.id
                  WHERE applications.id = '. $db->quote($_GET['deleteId']));

	$db -> query('DELETE FROM applications 
                  WHERE id = ' . $db->quote($_GET['deleteId']));

	$dir = 'docs/' . $userId;
	$files = scandir($dir);
	for($i = 2; $i < count($files); $i++) {
		unlink($dir . '/' . $files[$i]);
	}
	rmdir($dir);

	header('Location: applicationadmin.php');	
}


$applications = $db -> select('SELECT applications.course_name name,
                              applications.id as application_id,
                              users.id as users_id 
                       FROM applications
                       JOIN users ON applications.user_id = users.id');


head('', 0, 0, 1);
?>

<script>
	$(document).ready(function() {
		$('#dynatable').dynatable();
	});
</script>

<section class='content'>
	<div class='container'>
		<h2>Applications</h2>
		<table id='dynatable' class='table table-striped'>	
			<thead>
				<tr>
					<th>Id</th>
					<th>Course Name</th>
					<th>Instructor</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($applications as $application) { ?>
					<tr>
						<td><?= $application['application_id'] ?></td>
						<td><a href='applicationview.php?id=<?= $application["application_id"] ?>'><?= $application['name'] == '' ? '(No Name Given)' : $application['name'] ?></a></td>
						<td><?= $application['users_id'] ?></td>
						<td><a href='applicationadmin.php?deleteId=<?= $application["application_id"] ?>'>Delete</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</section>

<?php tail(); ?>