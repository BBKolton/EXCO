<?php
//register a new user. Makes plenty of checks against duplicate users and common emails
require("../common.php");

if (!empty($_GET["code"])) {
	verifyUser($_GET['email'], $_GET["code"], $DATABASE);
	die();
}

//TODO: Refactor this section
//if any of these are not set, the page will die
if (empty($_POST["email"]) || 
    empty($_POST["password"]) || 
    empty($_POST["password2"]) ||
    empty($_POST["first-name"]) || 
    empty($_POST["last-name"]) || 
    empty($_POST["zip"]) ||
    empty($_POST["phone"])) {
	error("Missing Field", "You tried to register without completing a required field");
}

if ($_POST["password"] !== $_POST["password2"]) {
	error("Passwords Don't Match", "Your passwords did not match");
} if (!validEmail($_POST["email"])) {
	error("Invalid Email", "Your email " . $_POST["email"] . " is invalid");
} if (!validPassword($_POST["password"])) {
	error("Invalid Password", "Your password must be longer than 8 characters in length");
}

//evaluate mailing preferences
$mailing = 1;
if (!isset($_POST["mailing"])) {
	$mailing = 0;
}

//cryptify the password
$password = $_POST["password"];
$hash = password_hash($password, PASSWORD_BCRYPT);
$veriRaw = randomString();

//repackaged for easier imploding later, when creating a new user
$options = [$_POST["email"], $hash, $_POST["first-name"], $_POST["last-name"],
            $_POST["zip"], $_POST["phone"], $mailing, $veriRaw];

//escape all the data in the array
$db = new DB();
for ($i = 0; $i < count($options); $i++) {
	$options[$i] = $db -> quote($options[$i]);
}

//assign all variables nicer names
list($email, $hash, $firstName, $lastName, $zip, $phone, $mailing, $verification) = $options;

//$exists will be FALSE if no people are found. Will be a mysqli with rows if they do
//ha ha looks like sexists
$rows = $db -> select("SELECT * 
                       FROM " . $DATABASE . ".users 
                       WHERE email = " . $email);

if (empty($rows[0])) { //no user with same email, insert user
	$db -> query("INSERT INTO " . $DATABASE . ".users 
	              (email, password, first_name, last_name, zip, phone, mailing, activation)
	              VALUES(" . implode(", ", $options) . ")");
	head();
	?>

	<ection class="content">
		<div class="container">
			<h1>Registration Successful!</h1>
			<p>Please check your email for a verification message. You are not able to sign up
			for classes until you have verified your email</p>
		</div>
	</ection>

	<?php
	tail();
	mailUser($_POST['email'], $veriRaw);
} else { //A user was found, stop the registration
	error("Account Already Exists", "The email " . $email . "is aready in use");
}


//verifies a user's mailing code against the database
function verifyUser($email, $code, $DATABASE) {
	$db = new DB();
	$exists = $db -> select("SELECT * 
	                         FROM " . $DATABASE . ".users 
	                         WHERE email = " . $db->quote($email) . 
	                       " AND activation = " . $db->quote($code));
	if (empty($exists[0])) {
		error("Invalid Verification", "Your verification is invalid. You might already be verified.");
	} else {
		//TODO this is copied into preferences for updateing email
		if (preg_match("/((@uw\.edu)|(@u\.washington\.edu))/i", $exists[0]["email"])) {
			$netid = substr($exists[0]["email"], 0, strpos($exists[0]["email"], "@"));
			$db -> query("UPDATE " . $DATABASE . ".users
			              SET activation = '1', netid = " . $db->quote($netid) .
			            " WHERE email = " . $db->quote($email));
		} else {
			$db -> query("UPDATE " . $DATABASE . ".users
			              SET activation = '1', netid = NULL
			              WHERE email = " . $db->quote($email));
		}
		
		head();
		?>
		<section class="content">
			<div class="container">
				<h1>Success!</h1>
				<p>You are now verified with the Experimental College! 
				Sign up for some classes!</p>
			</div>
		</section>
		<?php
		tail();
	}
}


//mails the user with a verification email
function mailUser($email, $verification) {
	require("../modules/PHPMailer/PHPMailerAutoload.php");

	$mail = new PHPMailer(true);
	$mail->AddAddress($email);
	$mail->SetFrom("No-Reply@exco.org");
	$mail->Subject = "Verify Email";
	$mail->AddReplyTo("noreply@exco.org", "ASUW Experimental College");
	$mail->SetFrom("noreply@exco.org", "ASUW Experimental College");
	$mail->Body = "Thank you for registering with the Experimental College!" .
			"Please click this link to verify your account " . 
			"http://depts.washington.edu/asuwxpcl/users/registeruser.php?email=" . $email .
			"&code=" . $verification . " . If you did not request this email, please " . 
			"ignore it.";
	try {
		$mail->Send();
	} catch (Exception $e) {
		error("Email Failure", $mail->ErrorInfo);
	}
}


//this very weak function makes sure an email has the right pattern
function validEmail($email) {
	$match = preg_match('/.+@.+\..{2,}/i', $email);
	if ($match === 1) {
		return true;
	} else if ($match === 0) {
		return false;
	}
	error('Unexpected Error', 
		'While attempting to validate your email, a crucial error occurred. Please notify the webadmin.');
}


//makes sure a password has the correct requirements
function validPassword($password) {
	if (strlen($password) < 8) {
		return false;
	}
	return true;
}
?>