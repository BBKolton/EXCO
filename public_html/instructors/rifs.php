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
	                 u.last_name,
	                 r.late,
	                 r.paid,
	                 r.facilities
	          FROM rifs r
	          JOIN users u ON r.instructor_id = u.id';

} else {
	$query = 'SELECT r.id,
	                 r.name,
	                 u.first_name,
	                 u.last_name,
	                 r.late,
	                 r.paid,
	                 r.facilities
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
			<h1><?= $_SESSION['permissions'] > 2 ? 'All RIFS' : 'Your RIFS' ?></h1>
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
					<th>Late</th>
					<th>Paid</th>
					<th>Facilities</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				
			<?php foreach($rifs as $rif) { ?>
				<tr>
					<td><?= $rif['id'] ?></td>
					<td><a href='rif.php?id=<?= $rif["id"] ?>'><?= $rif['name'] ?></a></td>
					<td><?= $rif['first_name'] . " " . $rif['last_name'] ?></td>
					<?php if ($_SESSION['permissions'] > 2) { ?>
						<td><a href='rifsubmit.php?id=<?= $rif["id"] ?>&late=<?= ($rif["late"] == 0) ? "1" : "0" ?>'><?= $rif['late'] == 0 ? 'Mark Late' : 'Clear Late' ?></a></td>
						<td><a href='rifsubmit.php?id=<?= $rif["id"] ?>&paid=<?= $rif["paid"] == 0 ? "1" : "0" ?>'><?= $rif['paid'] == 0 ? 'Mark Paid' : 'Mark Unpaid' ?></a></td>
						<td><a href='rifsubmit.php?id=<?= $rif["id"] ?>&facilities=<?= $rif["facilities"] == 0 ? "1" : "0" ?>'><?= $rif['facilities'] == 0 ? 'Mark Complete' : 'Mark Incomplete' ?></a></td>
					<?php } else { ?>
						<td><?= $rif['late'] == 0 ? 'Not Late' : 'Late' ?></td>
						<td><?= $rif['paid'] == 0 ? 'Unpaid' : 'Paid' ?></td>
						<td><?= $rif['paid'] == 0 ? 'Incomplete' : 'Complete' ?></td>
					<?php } ?>
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