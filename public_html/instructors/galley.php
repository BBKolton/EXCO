<?php 

require("../common.php");

session_start();

if (!verifyAdminOrRifInstructor($_GET['id'])) {
	error('Access Denied', 'You are not cleared to edit this page');
}

if (!isset($_GET['id'])) {
	error('Undeclared Galley', 'You did not specify a galley to view');
}

$g;
if (isset($_GET['id'])) {
	$db = new DB();
	$g = $db->select("SELECT * FROM galleys WHERE id = " . $db->quote($_GET['id']))[0];
	if (!$g) {
		error('Unknown Galley', 'The galley requested could not be found');
	}
}

head('<link href="/asuwecwb/.assets/css/galley.css" type="text/css" rel="stylesheet" />');

?>

<section class='content'>
	<div class='container'>
		<form action='galleysubmit.php?id=<?= $_GET["id"] ?>' method='post'>
			<h1>Galley Overview</h1>
			<p>Please review your galley to make sure no errors have occurred. The rif is <a href='rif.php?id=<?= $_GET["id"] ?>'>here</a>.</p>
			<textarea name='text'><?= $g['text'] ?></textarea>
			<input type='submit' value='Save Galley' class='btn btn-warning' /> <button type='submit' name='continue' value='true' class='btn btn-success'>Save and Finish</button>
		</form>
	</div>
</section>

<?php tail() ?>