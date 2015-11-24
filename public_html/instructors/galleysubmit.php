<?php 

require('../common.php');

session_start();
if (!verifyAdminOrRifInstructor($_GET['id'])) {
	error('Access Denied', 'You are not cleared to edit or view this page');
}

$db = new DB();

if (isset($_POST['text'])) {
	$db -> query('INSERT INTO galleys (id, text) VALUES (' . $db->quote($_GET['id']) . ',' . $db->quote($_POST['text']) . ')
	              ON DUPLICATE KEY UPDATE text = '. $db->quote($_POST['text']));
	if ($_POST['continue']) {
		header('Location: galleys.php');
		die();
	}
	header('Location: galley.php?id=' . $_GET['id']);
	die();
}

if (isset($_GET['allgalleys'])) { 
	head(); ?>
	<section class='content'><div class='container'><h2>All Galleys</h2>
	<?php $galleys = $db -> select('SELECT galleys.text FROM galleys 
	                                JOIN rifs ON galleys.id = rifs.id
	                                ORDER BY rifs.category');
		foreach ($galleys as $galley) { ?>
			<p style='white-space: pre-wrap'><?= htmlspecialchars($galley['text']) ?></p>
		<?php }
	?> </div></section> <?php
	tail(); 
}
