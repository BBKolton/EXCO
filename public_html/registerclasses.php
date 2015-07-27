<?php
	//take the session data we gots and register a user for the classes
	require("common.php");

	session_start();

	if(empty($_SESSION["cart"])) {
		error("No Classes", "No classes were found in your cart!");
	}

	$db = new DB();
	for ($i = 0; $i < count($_SESSION["cart"]); $i+=2) {
		$id = $db->quote($_SESSION["cart"][$i]);
		$section = $db->quote($_SESSION["cart"][$i + 1]); 
		$db -> query("INSERT INTO " . $DATABASE . ".registrations
		             (course_id, course_section, user_id) VALUES
		             (" . $id . ", " . $section . ", " . $db->quote($_SESSION["id"]) . ")");
	}
	$_SESSION["cart"] = null;

	head(); ?>
		<section class="confirmation">
			<div class="container">
				<h1>Registration Sucessful!</h1>
				<p>Head over to <a href="myclasses.php">My Classes!</a>
			</div>
		</section>
	<?php tail();
?>