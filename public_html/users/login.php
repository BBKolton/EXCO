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
	$user = $db -> select("SELECT id, email, password, first_name, last_name, activation, permissions, type, phone, zip
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
		$_SESSION["phone"] = $user[0]["phone"];
		$_SESSION["zip"] = $user[0]["zip"];
		header("Location: /asuwecwb/index.php");
	}

	function page() { 
		head('<link href="/asuwecwb/.assets/css/login.css" rel="stylesheet" />'); 
		?>

		<section class="content">
			<div class="container">
				<div class='row'>
					<div class='col-xs-12 col-md-6'>
						<form action="/asuwecwb/users/login.php" method="post">
							<h3>Login</h3>
							<input class='form-control' type="text" name="email" autofocus placeholder="Email" /><br />
							<input class='form-control' type="password" placeholder='Password' name="password" /><br />
							<p>
								<input type="submit" value="login" class='btn btn-info' />
								<a href="/asuwecwb/users/forgot.php">Forgot Password?</a>
							</p>
						</form>
					</div>
					<div class='col-xs-12 col-md-6'>
						<form action="/asuwecwb/users/registeruser.php" method="post">
							<h3>Register New User</h3>
							<p><i><b>NOTICE:</b> If you are a member of the University of Washington and
								have a valid NetID email address, use it here to receive student
								pricing.</i></p>
							<input class='form-control' type="text" name="email" placeholder="Email" /><br />
							<div class='form-group'>
								<input class='form-control' type="password" name="password" placeholder="Password"/> Must be longer than 8 characters<br />
							</div>
							<input class='form-control' type="password" name="password2" placeholder="Verify Password"/><br />
							<input class='form-control' type="text" name="first-name" placeholder="First Name" /><br />
							<input class='form-control' type="text" name="last-name" placeholder="Last Name" /><br />
							<input class='form-control' type="text" name="phone" placeholder="Phone Number" /><br />
							<label><input type="checkbox" name="mailing" value="true" checked />Sign me up for newsletters</label><br />
							<input type="submit" value="register" class="btn btn-success"/>
						</form>
					</div>
				</div>
			</div>
		</section>

		<?php tail(); 
	}

?>