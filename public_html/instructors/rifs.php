<?php
require('../common.php');

session_start();
if ($_SESSION['permissions'] < 2) {
	error('Access Denied', 'You are not an administrator or instructor');
}

$db = new DB();
$query;

if ($_SESSION['permissions'] > 2) {
	$query = 'SELECT r.id,
	                 r.name,
	                 u.first_name,
	                 u.last_name
	          FROM rifs r
	          JOIN users u ON r.instructor_id = u.id';

} else {
	$query = 'SELECT r.id,
	                 r.name,
	                 u.first_name,
	                 u.last_name
	          FROM rifs r
	          JOIN users u ON r.instructor_id = u.id
	          WHERE u.id = ' . $db->quote($_SESSION['id']);

}

$rifs = $db->select($query);

head('<link href="/asuwecwb/.assets/css/rifs.css" rel="stylesheet" />', 0, 0, 1);
?>

<script>
	$(document).ready(function() {
		$('#dynatable').dynatable();
	});
</script>

<section class='title'>
	<div class='jumbotron'>
		<div class='container'>
			<h1>All Rifs</h1>
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
				
			<?php foreach($rifs as $rif) { ?>
				<tr>
					<td><?= $rif['id'] ?></td>
					<td><a href='rif.php?id=<?= $rif["id"] ?>'><?= $rif['name'] ?></a></td>
					<td><?= $rif['first_name'] . " " . $rif['last_name'] ?></td>
					<td><a href='rifsubmit.php?id=<?= $rif["id"] ?>&delete=delete'>Delete Rif</a></td>
				</tr>
			<?php } ?>
				
			</tbody>
		<table>
		<h2>New Rif</h2>
		<p><a href='rifsubmit.php?create=create'>Create New Rif</a></p>
	</div>
</section>



<?php
tail();
?>