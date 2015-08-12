<?php
	require("../common.php");
	session_start();


	//if we're already logged in, redirect away
	if (!empty($_SESSION["id"])) {
		header("location: /asuwecwb/index.php");
	}

	//If theres no email or password supplied, display thr login
	if (empty($_POST["email"]) || empty($_POST["password"])) {
		page();
		die();
	} if (isset($_SESSION["name"])) {
		header("Location /asuwecwb/index.php");
	}

	$email = $_POST["email"];
	$pass = $_POST["password"];

	//Ask the database for the user
	$db = new DB();
	$user = $db -> select("SELECT id, email, password, first_name, last_name, activation, permissions, type
	                       FROM " . $DATABASE . ".users 
	                       WHERE email = " . $db -> quote($email));

	
	if (empty($user[0])) { //if no user was found, throw an error
		page();
		error("Unknown Email", "The email " . $email . " does not exist!");
	} else if (!password_verify($pass, $user[0]["password"])){ //If the pass is wrong, throw and error
		page();
		error("Wrong Password", "The pass you supplied is incorrect!");
	} else if ($user[0]["activation"] !== "1") { //if the user isnt authenticated, throw and error
		page();
		error("Unactivated Account", "You have not yet verified your email address.");
	} else { //this is a real user with ability to sign in, do so!
		$_SESSION["email"] = $user[0]["email"];
		$_SESSION["first_name"] = $user[0]["first_name"];
		$_SESSION["last_name"] = $user[0]["last_name"];
		$_SESSION["name"] = $user[0]["first_name"] . " " . $user[0]["last_name"];
		$_SESSION["phone"] = $user[0]["phone"];
		$_SESSION["permissions"] = $user[0]["permissions"];
		$_SESSION["type"] = $user[0]["type"];
		$_SESSION["id"] = $user[0]["id"];
		header("Location: /asuwecwb/index.php");
	}

	function page() { 
		head(); 
		?>

		<section class="login">
			<div class="container">
				<form action="/asuwecwb/users/login.php" method="post">
					<h3>Login</h3>
					<input type="text" name="email" autofocus placeholder="email" />
					<input type="password" name="password" />
					<input type="submit" value="login" />
					<p><a>Forgot Password?</a></p>
				</form>
				<form action="/asuwecwb/users/registeruser.php" method="post">
					<h3>Register User</h3>
					<p>NOTICE: If you are a member of the University of Washington and
						have a valid NetID email address, use it here to receive student
						pricing.</p>
					<input type="text" name="email" placeholder="email" /><br />
					<input type="password" name="password" placeholder="password"/> Must be longer than 8 characters<br />
					<input type="password" name="password2" placeholder="verify password"/><br />
					<input type="text" name="first-name" placeholder="first name" /><br />
					<input type="text" name="last-name" placeholder="last name" /><br />
					<input type="text" name="phone" placeholder="phone" /><br />
					<label><input type="checkbox" name="mailing" value="true" checked />Sign me up for newsletters</label><br />
					<input type="submit" value="register" />
				</form>
			</div>
		</section>

		<?php tail();
	}

?>