<?php
require('../common.php');
session_start();

if (empty($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
	error('Not logged in', 'Youre not logged in or do not have sufficient priveleges to change this data');
}

$db = new DB();

if (isset($_GET['id']) && ($_GET['cancel'] || $_GET['uncancel'])) {
	$status = ( $_GET['cancel'] ? 1 : 0 );
	$db->query('UPDATE registrations SET status = '. $status . ' WHERE id = ' . $db->quote($_GET['id']));
	header('Location: course.php?id=' . $_GET['course']);
	die();
}

if (isset($_GET['id']) && $_GET['moveselect']) { 
	head();
	?>
	<section class='content'>
		<div class='container'>
			<form class='form' action='coursesubmit.php' method='get'>
				<h2>Move Registration</h2>
				<input type='hidden' name='id' value='<?= $_GET["id"] ?>' />
				<input type='hidden' name='move' value='true' />
				<div class='form-group'>
					<p>Course ID Number</p>
					<input type='text' class='form-control' name='course' placeholder='i.e. 23' />
				</div>
				<div class='form-group'>
					<p>Section Number</p>
					<input type='text' class='form-control' name='section' placeholder='i.e. 1' />
				</div>
				<button type='submit' class='btn btn-primary'>Move Registration</button>
			</form>
		</div>
	</section>
	<?php
	tail();
	die();
} 

if (isset($_GET['id']) && isset($_GET['course']) && isset($_GET['section']) && $_GET['move']) {
	$new = $db->select('SELECT c.id FROM courses c JOIN sections s ON s.course_id = c.id
	                    WHERE c.id = ' . $db->quote($_GET["course"]) . '
	                    AND s.section = ' . $db->quote($_GET["section"]))[0];
	if (empty($new)) {
		error('Unknown New Course', 'The section or course id entered does not exist');
	}

	$db->query('UPDATE registrations SET course_id = ' . $db->quote($_GET["course"]) . 
	           ', course_section = ' . $db->quote($_GET["section"]) . '
	            WHERE id = ' . $db->quote($_GET['id']));
	header('Location: /asuwecwb/courses/course.php?id=' . $new['id']);
	die();
}