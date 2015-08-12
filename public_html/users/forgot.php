<?php
	require("../common.php");

	$db = new DB();

	if (!empty($_POST["password"])) {
		if (empty($_POST["email"])) {
			error("Empty Email", "You must submit an email address");
		}
		$db -> query("UPDATE " . $DATABASE . ".users 
		              SET password_reset");
	}

	head();



	tail();
?>