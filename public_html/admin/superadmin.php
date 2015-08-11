<?php
	require("../common.php");
	session_start();
	if ($_SESSION["permissions"] < 3) {
		error("Insufficient Privledges!", "You are not allowed to view this page! (are you logged in?)");
		die();
	}

	

?>