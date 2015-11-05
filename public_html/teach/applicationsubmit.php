<?php
require('../common.php');
session_start();

//check all prerequisites
if (!isset($_SESSION['id'])) {
	error('Not Logged In', 'You are not logged in and cannot make an application');
} if (!isset($_POST)) {
	error('No form data received', 'No data from your form was received');
} if (!isset($_FILES['resume']) || !isset($_FILES['outline'])) {
	error('Missing Document', 'You did not upload a resume or outline');
} if ($_FILES['resume']['error'] == 1 || $_FILES['outline']['error'] == 1) {
	error('File Upload Error', 'One of your files is larger than the required 2MB');
} if ($_FILES['resume']['error'] > 1 || $_FILES['outline']['error'] > 1) {
	error('File Upload Error', 'The file upload failed. Please make sure your internet connection is stable and try again');
}

$acceptedTypes = ["docx", "doc", "txt", "pdf"];

$resumeType = substr($_FILES['resume']['name'], strpos($_FILES['resume']['name'], '.') + 1);
$outlineType = substr($_FILES['outline']['name'], strpos($_FILES['outline']['name'], '.') + 1);

if (!in_array($resumeType, $acceptedTypes)) {
	error('Wrong Resume Format', 'The uploaded resume was not in .docx, .doc, .txt, or .pdf format');
} if (!in_array($outlineType, $acceptedTypes)) {
	error('Wrong Outline Format', 'The uploaded outline was not in .docx, .doc, .txt, or .pdf format');
}

foreach ($_POST as $key => $value) {
	$_POST[$key] = htmlspecialchars($value);
}

$db = new DB();
$db -> query('INSERT INTO applications (
		user_id,
		course_name,
		course_summary,
		course_start,
		course_sections,
		course_hours,
		course_days,
		course_max,
		question_attract,
		question_why,
		question_skills,
		question_supplies,
		question_exercises,
		question_background
		) VALUES (' . 
		$_SESSION['id'] . ',' . 
		$db->quote($_POST['course_name']) . ',' .
		$db->quote($_POST['course_summary']) . ',' .
		$db->quote($_POST['course_start']) . ',' .
		$db->quote($_POST['course_sections']) . ',' .
		$db->quote($_POST['course_hours']) . ',' .
		$db->quote($_POST['course_days']) . ',' .
		$db->quote($_POST['course_max']) . ',' .
		$db->quote($_POST['question_attract']) . ',' .
		$db->quote($_POST['question_why']) . ',' .
		$db->quote($_POST['question_skills']) . ',' .
		$db->quote($_POST['question_supplies']) . ',' .
		$db->quote($_POST['question_excersises']) . ',' .
		$db->quote($_POST['question_background']) . '
		)');

$db -> query('INSERT INTO users_additional (
		user_id,
		address,
		city,
		state
		) VALUES (' .
		$_SESSION['id'] . ',' .
		$db->quote($_POST['personal_address']) . ',' .
		$db->quote($_POST['personal_city']) . ',' .
		$db->quote($_POST['personal_state']) . '
		)');

$dir = 'docs/' . $_SESSION['id'];
if (!is_dir($dir)) {
	mkdir($dir);
}

move_uploaded_file($_FILES['resume']['tmp_name'], $dir . '/resume.' . $resumeType);
move_uploaded_file($_FILES['outline']['tmp_name'], $dir . '/outline.' . $outlineType);

header('Location: /asuwecwb/teach/applicationcomplete.php');