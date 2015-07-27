<?php
	//register a new user. Makes plenty of checks against duplicate users and common emails

	require("common.php");

	//if any of these are not set, the page will die
	if (empty($_POST["email"]) || 
	    empty($_POST["password"]) || 
	    empty($_POST["first-name"]) || 
	    empty($_POST["last-name"]) || 
	    empty($_POST["phone"])) {
		error("Missing Field", "You tried to register without completing a required field");
	}

	//evaluate mailing preferences
	$mailing = 1;
	if (!isset($_POST["mailing"])) {
		$mailing = 0;
	}

	//cryptify the password
	$password = $_POST["password"];
	$hash = password_hash($password, PASSWORD_BCRYPT);

	//repackaged for easier imploding later, when creating a new user
	$options = [$_POST["email"], $hash, $_POST["first-name"], $_POST["last-name"],
	            $_POST["phone"], $mailing];
	
	//escape all the data in the array
	$db = new DB();
	for ($i = 0; $i < count($options); $i++) {
		$options[$i] = $db -> quote($options[$i]);
	}

	//assign all variables nicer names
	list($email, $hash, $firstName, $lastName, $phone, $mailing) = $options;

	//$exists will be FALSE if no people are found. Will be a mysqli with rows if they do
	//ha ha looks like sexists
	$exists = $db -> query("SELECT * 
	                        FROM " . $DATABASE . ".users 
	                        WHERE email = " . $email);

	//execute duplicate account checks
	if (!$exists) { //malformed request (this shouldnt happen ever). page dies in here
		error("Bad Database Request");
	} else { //create an array from the mysqli result and see if anyone lives inside
		$rows = array();
		while ($row = mysqli_fetch_assoc($exists)) {
			$rows[] = $row;
		}
	} if (empty($rows[0])) { //no user with same email, insert user
		$db -> query("INSERT INTO " . $DATABASE . ".users 
		              (email, password, first_name, last_name, phone, mailing)
		              VALUES(" . implode(", ", $options) . ")");
		header("Location: index.php");
		die();
	} else { //A user was found, stop the registration
		error("Account Already Exists", "The email " . $email . "is aready in use");
	}
?>