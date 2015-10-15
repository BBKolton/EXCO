<?php
require('../common.php');

session_start();
if ($_SESSION['permissions'] < 3) {
	error('Access Denied', 'You are not an administrator');
}

$db = new DB();
$rifs = $db->select('SELECT r.id,
                            r.name,
                            u.first_name,
                            u.last_name
                     FROM rifs r
                     JOIN users u ON r.instructor_id = u.id');

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
				</tr>
			</thead>
			<tbody>
				
			<?php foreach($rifs as $rif) { ?>
				<tr>
					<td><?= $rif['id'] ?></td>
					<td><a href='rif.php?id=<?= $rif["id"] ?>'><?= $rif['name'] ?></a></td>
					<td><?= $rif['first_name'] . " " . $rif['last_name'] ?></td>
				</tr>
			<?php } ?>
				
			</tbody>
		<table>
	</div>
</section>



<?php
tail();
?>