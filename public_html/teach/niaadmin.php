<?php 
session_start();

require('../common.php');

if ($_SESSION['id'] < 3) {
	error('Unauthorized User', 'You are not an admin');
}

$db = new DB();

if (isset($_GET['deleteId'])) {
	$userId = $db -> select('SELECT user_id FROM nias
                             WHERE id = ' . $db->quote($_GET['deleteId']))[0]['user_id'];

	$db -> query('DELETE users_additional FROM users_additional
                  JOIN users ON users_additional.user_id = users.id
                  JOIN nias ON nias.user_id = users.id
                  WHERE nias.id = '. $db->quote($_GET['deleteId']));

	$db -> query('DELETE FROM nias 
                  WHERE id = ' . $db->quote($_GET['deleteId']));

	$dir = 'docs/' . $userId;
	$files = scandir($dir);
	for($i = 2; $i < count($files); $i++) {
		unlink($dir . '/' . $files[$i]);
	}
	rmdir($dir);

	header('Location: niaadmin.php');	
}


$nias = $db -> select('SELECT nias.course_name name,
                              nias.id as nia_id,
                              users.id as users_id 
                       FROM nias
                       JOIN users ON nias.user_id = users.id');


head('', 0, 0, 1);
?>

<script>
	$(document).ready(function() {
		$('#dynatable').dynatable();
	});
</script>

<section class='content'>
	<div class='container'>
		<h2>NIA Applications</h2>
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
				<?php foreach ($nias as $nia) { ?>
					<tr>
						<td><?= $nia['nia_id'] ?></td>
						<td><a href='niaview.php?id=<?= $nia["nia_id"] ?>'><?= $nia['name'] ?></a></td>
						<td><?= $nia['users_id'] ?></td>
						<td><a href='niaadmin.php?deleteId=<?= $nia["nia_id"] ?>'>Delete</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</section>

<?php tail(); ?>