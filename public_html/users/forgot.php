<?php
	require("../common.php");

	$db = new DB();


	//User has already received email, give password change prompt
	if (!empty($_GET["email"]) && !empty($_GET["reset"])) {
		changePasswordPage();
	//User has submitted new passwords
	} else if (!empty($_POST["email"]) && !empty($_POST["reset"])) {

		//make sure all data is not trash
		if (empty($_POST["pass"]) || empty($_POST["pass-verify"]) ||
				$_POST["pass"] !== $_POST["pass-verify"]) {
			changePasswordPage();
			error("Invalid Password", "Your passwords did not match");
		} if (strlen($_POST["pass"]) < 8) { //make sure the pass is long enough
			changePasswordPage();
			error("Password Too Short", "Please choose a password >= 8 characters.");
		}

		//Make sure the user exists
		$user = $db -> select("SELECT id FROM " . $DATABASE . ".users 
		                       WHERE email = " . $db->quote($_POST["email"]) . " 
		                       AND password_reset = " . $db->quote($_POST["reset"]));
		if (empty($user)) {
			error("Unknown User", "We were unable to find or complete your password reset request");
		}

		//at this point we can change the password. A user is verified.
		$pass = password_hash($_POST["pass"], PASSWORD_BCRYPT);
		$db -> query("UPDATE " . $DATABASE . ".users 
		              SET activation = '1',
		                  password_reset = NULL,
		                  password = " . $db->quote($pass) . " 
		              WHERE email = " . $db->quote($_POST["email"]));
		resetCompletePage();

	//user has submitted an email to change their password
	} else if (!empty($_POST["email"])) {
		$email = $db -> select("SELECT users.email 
		                        FROM " . $DATABASE . ".users 
		                        WHERE email = " . $db->quote($_POST["email"]));
		if (!empty($email)) {
			$pass = randomString(40);
			$db -> query("UPDATE " . $DATABASE . ".users 
			              SET password_reset = " . $db->quote($pass) . 
			            " WHERE email = " . $db->quote($email[0]["email"]));
			sendEmail($email, $pass);
		}
		showVerify($_POST["email"]);
	} else {
		page();
	}

	//The regular page, displays the button to reset a password
	function page() {
		head(); ?>

		<section class="content">
			<div class="container">
				<h1>Reset Password</h1>
				<form action="forgot.php" method="post">
					<p>Enter your email address to reset your password. 
					You will receive an email with a reset link.</p>
					<input type="text" name="email" placeholder="Email Address" />
					<input type="submit" value="Submit" />
				</form>
			</div>
		</section>

		<?php tail();
	}

	//sends an email to a user with the password reset link
	function sendEmail($email, $pass) {
		require("../modules/PHPMailer/PHPMailerAutoload.php");
		$mail = new PHPMailer(true);
		$mail->AddAddress($email[0]["email"]);
		$mail->SetFrom("No-Reply@exco.org");
		$mail->Subject = "Reset Password";
		$mail->AddReplyTo("noreply@exco.org", "ASUW Expewrimental College");
		$mail->SetFrom("noreply@exco.org", "ASUW Experimetnal College");
		$mail->Body = "We've received a request to change your password.
			Please visit this link to reset: 
			https://depts.washington.edu/asuwxpcl/users/forgot.php?reset=" . $pass . "&email=" . $email[0]["email"] ." 
			If you did not request this email, ignore it.";
		try {
			$mail->Send();
		} catch (Exception $e) {
			error("Email Failure", $mail->ErrorInfo);
		}
	}

	//Shows a verification message after sending an email
	//works even if the email fails (so that no one can query our email database)
	function showVerify($email) {
		head(); ?>

		<section class="content">
			<div class="container">
				<h1>Email Success!</h1>
				<p>An email was sent to <?= $email ?></p>
			</div>
		</section>

		<?php tail();
	}

	//Shows the page with the form to actually change the password.
	//user has already verified email at this point
	function changePasswordPage() {
		$email = $_GET["email"];
		if ($_POST["email"]) {
			$email = $_POST["email"];
		}
		$reset = $_GET["reset"];
		if ($_POST["reset"]) {
			$reset = $_POST["reset"];
		}
		head(); ?>

		<section class="content">
			<div class="container">
				<h1>Reset Password</h1>
				<form action="forgot.php" method="post">
					<p>Enter a new password twice to complete the reset. 
					Please choose a password longer than or equal to 8 characters.</p>
					<input type="password" name="pass" />
					<input type="password" name="pass-verify" />
					<input type="hidden" name="email" value="<?= $email ?>" />
					<input type="hidden" name="reset" value="<?= $reset ?>" />
					<input type="submit" value="submit" />
				</form>
			</div>
		</section>

		<?php tail();
	}

	//finally changes a user's password
	function resetCompletePage() {
		session_start();
		session_destroy(); //in case a user was logged in when they reset password
		head(); ?>

		<section class="content">
			<div class="container">
				<h1>Password Reset!</h1>
				<p>Your password has been reset. Please 
				<a href="/asuwxpcl/users/login.php">login</a> to continue</p>
			</div>
		</section>

		<?php tail();
	}

?>