<?php
require('../common.php');

session_start();
if ($_SESSION['permissions'] < 2) {
	error('Access Denied', 'You are not an administrator or instructor');
}

$db = new DB();
$query;

if ($_SESSION['permissions'] > 2) {
	$query = 'SELECT g.id,
	                 u.first_name,
	                 u.last_name,
	                 r.name
	          FROM galleys g
	          JOIN rifs r ON g.id = r.id 
	          JOIN users u ON r.instructor_id = u.id';

} else {
	$query = 'SELECT g.id,
	                 u.first_name,
	                 u.last_name,
	                 r.name
	          FROM galleys g
	          JOIN rifs r ON g.id = r.id 
	          JOIN users u ON r.instructor_id = u.id
	          WHERE u.id = ' . $db->quote($_SESSION['id']);

}

$galleys = $db->select($query);

head('<link href="/asuwxpcl/.assets/css/rifsgalleys.css" rel="stylesheet" />', 0, 0, 1); ?>

<script>
	$(document).ready(function() {
		$('#dynatable').dynatable();
	});
</script>

<section class='title'>
	<div class='jumbotron'>
		<div class='container'>
			<h1><?= $_SESSION['permissions'] > 2 ? 'All Galleys' : 'Your Galleys' ?></h1>
		</div>
	</div>
</section>

<section class='content'>
	<div class='container'>
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
				
			<?php foreach($galleys as $galley) { ?>
				<tr>
					<td><?= $galley['id'] ?></td>
					<td><a href='galley.php?id=<?= $galley["id"] ?>'><?= $galley['name'] ?></a></td>
					<td><?= $galley['first_name'] . " " . $galley['last_name'] ?></td>
					<td><a href='galleysubmit.php?id=<?= $galley["id"] ?>&delete=delete'>Delete galley</a></td>
				</tr>
			<?php } ?>
				
			</tbody>
		<table>
		<?php if ($_SESSION['permissions'] > 2) { ?>
			<h2>Copy Page</h2>
			<p><a href='galleysubmit.php?allgalleys=true'>View all rifs in text format on one page</a></p>
		<?php } ?>
	</div>
</section>



<?php
tail();
?>