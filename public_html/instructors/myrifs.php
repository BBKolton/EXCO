<?php
//creates a page for viewing your RIFs if your an instructor, and
//all RIFS if youre an admin.
require("../common.php");

session_start();

//If the user isn't supposed to be here
if (empty($_SESSION["id"])) {
	error("Access Denied", "You must be logged in and an instructor to view this page");
} if ($_SESSION["permissions"] < 2) {
	error("Instructors Only", "You must be an instructor ar administrator to view this page");
}

//user is supposed to be here, let's find out who they are and query the table for either
//all data for admins or just instructor's rifs if not.
$db = new DB();
if ($_SESSION["permissions"] == 2) { //I'm an instructor!
	$id = $_SESSION0["id"];
	$db -> select("SELECT ");
}
?>