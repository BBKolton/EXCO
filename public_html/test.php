<?php
	session_start();
	$charge = 5;
	if ($user[0]["type"] == "general") {
		$charge = 12;
	}

	$charge = $charge * (count($_SESSION["cart"]) / 2);
	echo count($_SESSION["cart"]) . "<br>";
	echo "<pre>" . print_r($_SESSION['cart'], true) . "</pre>";
	if (isset($_SESSION)) {
		echo "fucking php says its here fuck php";
	} else {
		echo "session is missing?????????";
	}
	echo "<br>";
	echo $charge;

?>