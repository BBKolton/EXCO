<?php 
	//if a user is logged in, adds the section to the users cart and brings them there,
	//otherwise, prompts the user for a login.
	require("common.php");
	session_start();
	
	//redirect user with no login
	if (empty($_SESSION["name"])) {
		header("Location: login.php");
		die();
	}

	//bad data sent? send them to the bad person zone! also die.
	if (empty($_GET["id"]) || empty($_GET["section"])) {
		error("Unknown Signup", "You did not specify a section or class to signup for!");
	}

	//get info from the class and section requested
	$db = new DB();
	$section = $db -> select("SELECT * FROM " . $DATABASE . ".sections
	                         WHERE course_id = " . $db->quote($_GET["id"]) . 
	                       " AND section = " . $db->quote($_GET["section"]));

	if (empty($section[0]) || $section[0]['status'] !== "1") {
		error("Invalid Signup", "Your specified class or section does not exist or is not taking registrations!");
	}

	//If first item in cart, make a new array and put the class in there
	if (empty($_SESSION["cart"])) {
		$cart = [$_GET["id"], $_GET["section"]];
		$_SESSION["cart"] = $cart;
	} else {
		array_push($_SESSION["cart"], $_GET["id"], $_GET["section"]);
	}
	header("Location: cart.php");
?>