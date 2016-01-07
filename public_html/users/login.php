<?php
	require("../common.php");
	session_start();


	//if we're already logged in, redirect away
	if (!empty($_SESSION["id"])) {
		header("location: /asuwxpcl/index.php");
	}

	//If theres no email or password supplied, display thr login
	if (empty($_POST["email"]) || empty($_POST["password"])) {
		page();
		die();
	} if (isset($_SESSION["name"])) {
		header("Location /asuwxpcl/index.php");
	}

	$email = $_POST["email"];
	$pass = $_POST["password"];

	//Ask the database for the user
	$db = new DB();
	$user = $db -> select("SELECT id, email, password, first_name, last_name, activation, permissions, phone, zip, netid
	                       FROM " . $DATABASE . ".users 
	                       WHERE email = " . $db -> quote($email));

	
	if (empty($user[0])) { //if no user was found, throw an error
		page("Unknown Email", "The email " . $email . " is not registered");
		die();
	} else if (!password_verify($pass, $user[0]["password"])){ //If the pass is wrong, throw and error
		page("Wrong Password", "The password you supplied is incorrect");
		die();
	} else if ($user[0]["activation"] !== "1") { //if the user isnt authenticated, throw and error
		page("Unactivated Account", "You have not yet verified your email address. Please check your email to click the verification link. The email my be in your junk ");
		die();
	} else { //this is a real user with ability to sign in, do so!
		$_SESSION["email"] = $user[0]["email"];
		$_SESSION["first_name"] = $user[0]["first_name"];
		$_SESSION["last_name"] = $user[0]["last_name"];
		$_SESSION["name"] = $user[0]["first_name"] . " " . $user[0]["last_name"];
		$_SESSION["phone"] = $user[0]["phone"];
		$_SESSION["permissions"] = $user[0]["permissions"];
		$_SESSION["netId"] = $user[0]["netid"];
		$_SESSION["id"] = $user[0]["id"];
		$_SESSION["phone"] = $user[0]["phone"];
		$_SESSION["zip"] = $user[0]["zip"];
		header("Location: /asuwxpcl/index.php");
	}

	function page($error = '', $message = '') { 
		head('<link href="/asuwxpcl/.assets/css/login.css" rel="stylesheet" />'); 
		?>

		<section class="content">
			<div class="container">
				<div class='row'>
					<div class='col-xs-12 col-md-6'>
						<?php if ($error == '') { ?> 
							<h1>A New Site!</h1>
							<p>The Experimental College has gotten a facelift! With it, we've archived and cleaned our user database. 
							If you had an account on our old site and have not made one here yet, <b>you will need to create a new 
							account to the right</b></p>
						<?php } else { ?> 
							<div class='error'>
								<h1><?= $error ?></h1>
								<p><?= $message ?></p>
							</div>
						<?php } ?>
						<form action="/asuwxpcl/users/login.php" method="post">
							<h3>Login</h3>
							<input class='form-control' type="text" name="email" autofocus placeholder="Email" /><br />
							<input class='form-control' type="password" placeholder='Password' name="password" /><br />
							<p>
								<input type="submit" value="login" class='btn btn-info' />
								<a href="/asuwxpcl/users/forgot.php">Forgot Password?</a>
							</p>
						</form>
					</div>
					<div class='col-xs-12 col-md-6'>
						<form action="/asuwxpcl/users/registeruser.php" method="post">
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
							<input class='form-control' type='text' name="zip" placeholder="Zip code" /><br />
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