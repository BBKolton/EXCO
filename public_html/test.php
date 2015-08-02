<?php
	session_start();
	require("common.php");
	echo count($_SESSION["cart"]) . "<br>";

	$invoice = "EXCO Class";
	if (count($_SESSION["cart"]) > 2) {
		$invoice . "es";
	}
	for ($i = 0; $i < count($_SESSION["cart"]); $i+= 2) {
		$invoice = $invoice . " " . $_SESSION["cart"][$i] . ":" . $_SESSION["cart"][$i + 1];
	}
	echo $invoice . "<br>";
	

	$db = new DB();
	$user = $db -> select("SELECT email, first_name, last_name, phone, type 
	                       FROM " . $DATABASE . ".users 
	                       WHERE id = " . $db -> quote($_SESSION["id"]));
	$charge = 5;
	if ($user[0]["type"] == "general") {
		$charge = 12;
	}
	$charge = $charge * (count($_SESSION["cart"]) / 2);
	echo $charge;
?>