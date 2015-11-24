<?php

require("../common.php");
head();

session_start();
if (!verifyAdminOrRifInstructor($_GET['id'])) {
	error('Access Denied', 'You are not cleared to edit this page');
}

if (isset($_GET['id'])) {
	$db = new DB();
	$c = $db->select("SELECT * FROM rifs WHERE id = " . $db->quote($_GET['id']));
	$c = $c[0];
	$s = $db->select("SELECT * FROM rifs_sections WHERE rif_id = " . $db->quote($c['id']));
	if (!$c) {
		error('No Rif Found', 'The ID specified does not correspond to an existing rif');
	}
}

head();
?>

<section class='content'>
	<div class='container'>
		<h2>Review your RIF</h2>
		<p>If you find something that's wrong, <a href='rif.php?id=<?=$_GET["id"] ?>'>head back to the edit page</a>.</p>
		<ul>
			<h4>General Information</h4>
			<li><b>Name</b>: <?= $c['name']?></li>
			<li><b>Category</b>: <?= $GENRES[$c['category']]?></li>
			<li><b>Size</b>: <?= $c['size']?></li>
			<li><b>General Location</b>: <?= $c['loc_gen']?></li>
			<li><b>Specific Location</b>: <?= $c['loc_spec']?></li>
			<li><b>Will you accept students after the first day?</b>: <?= $c['overload'] ? 'Yes' : 'No' ?></li>
			<li><b>Will you accept students under 18?</b>: <?= $c['underage'] > 0 ? $c['underage'] > 1 ? 'Yes, with adult' : 'Yes' : 'No' ?></li>
			<h4>Fee Information</h4>
			<li><b>General Fee</b>: <?= $c['fee_gen']?></li>
			<li><b>UW Reduced Fee</b>: <?= $c['fee_uw']?></li>
			<h4>Sections</h4>
			<?php for ($i = 0; $i < count($s); $i++) { 
				$sec = $s[$i]; ?>
				<li><b>Section <?= $i + 1 ?></b>
					<ul>
						<li><b>Dates</b>: <?= $sec['days'] ?></li>
						<li><b>Times</b>: <?= $sec['time_start'] . ' - ' . $sec['time_end'] ?></li>
					</ul>
				</li>
			<?php } ?>
		</ul>
		<p>If everything looks good from here, go ahead and hit the submit button. Your RIF will be marked as submitted. Afterwards, you will be taken to your auto-generated galley. Please check that all information is correct on your galley as well before finishing.</p>
		<form action='rifsubmit.php' action='get'>
			<input type='hidden' name='id' value='<?= $_GET["id"] ?>' />
			<button type='submit' class='btn btn-success' name='rif-submit' value='1'>Submit your RIF</button>
		</form>
	</div>
</section>		