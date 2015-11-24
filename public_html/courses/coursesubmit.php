<?php
require('../common.php');
session_start();

if (empty($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
	error('Not logged in', 'Youre not logged in or do not have sufficient priveleges to change this data');
}

$db = new DB();

if (isset($_GET['id']) && $_GET['cancel']) {
	$db->query('UPDATE registrations SET status = 0 WHERE id = ' . $db->quote($_GET['id']));
	header('Location: course.php?id=' . $_GET['course']);
	die();
}