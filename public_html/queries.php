<?php

	require("common.php");

	$db = new DB();

	if (!empty($_GET["classlist"])) {
		echo json_encode($db->select("SELECT id, name FROM " . $DATABASE . ".courses"));
	}

	if (!empty($_GET["classsearch"])) {
		echo json_encode($db->select("SELECT id, name FROM " . $DATABASE . ".courses
		                              WHERE name LIKE '%" . $db->escape($_GET["classsearch"]) . "%'"));
	}

?>