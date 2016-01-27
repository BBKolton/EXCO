<?php
require("../common.php");
session_start();

$db = new DB();

verifyInputData();
$user = getUserData($db);
markReferral($db);
if ($_POST['type'] == 'credit') {
	require("../modules/cccharge.php");
	$amount = createAmount($user);
	$invoice = createInvoice();
	$result = chargeCreditCard($amount, $_POST['first-name'], $_POST['last-name'],
	                           $_POST['email'], $_POST['card'], $_POST['exp'],
	                           $_POST['cvc'], $invoice);
	if ($result) {
		registerCourses($db, $user);
		displayConfirmation($db, $amount, $invoice);
		sendConfirmationEmail($db, $user, $amount, $invoice);
	} else { 
		error("Unforseen Error", "Something failed in an unexpected way. Please contact the Experimental College and ask to speak with the webmaster");
	}
} else {
	registerCourses($db, $user);
	displayConfirmation($db, $amount, $invoice);
	sendConfirmationEmail($db, $user, $amount, $invoice);
}
$_SESSION["cart"] = null;


//verifies user data, throws errors if they arent signed in, if there is no data,
//if there are no classes, or if there's missing credit data
function verifyInputData() {
	if (empty($_SESSION["id"])) {
		error("No Logon Info", "Please login to continue");
	} if (count($_SESSION["cart"]) < 2 && !$_POST['outside']) {
		error("No classes", "You don't have any classes to sign up for!");
	} if (empty($_POST['referred']) || $_POST['referred'] == "Select...") {
		error("Missing Referral", "Please select a referral location. This information is anonymous");
	} if ($_SESSION['permissions'] < 3 && $_POST['type'] != 'credit') {
		error('Insufficient Credentials', 'You are not allowed to make a charge other than by credit');
	}
}


//Gets user's data from data base. Creates a user if user doe snot already exist
function getUserData($db) {
	$newUser = false;
	if ($_POST['outside']) {
		$_SESSION['cart'] = null;
		$_SESSION['cart'][0] = $_POST['id'];
		$_SESSION['cart'][1] = $_POST['section'];

		$user = $db->select("SELECT id, email, first_name, last_name, phone, netid
		                     FROM users 
		                     WHERE email = " . $db->quote($_POST['email']))[0];
		if (empty($user)) {
			$db -> query("INSERT INTO users 
			              (email, password, first_name, last_name, zip, phone, mailing, activation)
			              VALUES(" . implode(", ", [$db->quote($_POST['email']),
			                                        $db->quote(randomString()), 
			                                        $db->quote($_POST['first-name']), 
			                                        $db->quote($_POST['last-name']), 
			                                        $db->quote($_POST['zip']), 
			                                        $db->quote($_POST['phone']), 
			                                        $db->quote(1), 
			                                        $db->quote(1)]) . ")");		
			$newUser = true;
			$user = $db->select("SELECT id, email, first_name, last_name, phone, netid
			                     FROM users 
			                     WHERE email = " . $db->quote($_POST['email']))[0];
		}
	} else {
		$user = $db -> select("SELECT id, email, first_name, last_name, phone, netid  
		                       FROM users 
		                       WHERE id = " . $db -> quote($_SESSION["id"]))[0];
	}
	$user['newUser'] = $newUser;
	return $user;
}


//increments referral location
function markReferral($db) {
	$db->query("UPDATE referrals
	            SET count = count + 1
	            WHERE name = " . $db->quote($_POST['referred']));
}


//create the invoice name
function createInvoice() {

	$invoice = "Class";
	if (count($_SESSION["cart"]) > 2) {
		$invoice . "es";
	}
	for ($i = 0; $i < count($_SESSION["cart"]); $i+= 2) {
		$invoice = $invoice . " " . $_SESSION["cart"][$i] . ":" . $_SESSION["cart"][$i + 1];
	} 
	if (strlen($invoice) > 25) { //maximum length set by virtual merchant
		$invoice = "EXCO Classes";
	}
	return $invoice;
}


//decide just how much we're charging the user right now.
function createAmount($user) {
	$charge = 5;
	if ($user["netid"] === null) {
		$charge = 12;
	}
	$charge = $charge * (count($_SESSION["cart"]) / 2);

	return $charge;
}


//Add all the classes from the user's session into the database. 
function registerCourses($db, $user) {
	for ($i = 0; $i < count($_SESSION["cart"]); $i+=2) {
		$id = $db->quote($_SESSION["cart"][$i]);
		$section = $db->quote($_SESSION["cart"][$i + 1]); 
		$type = $db->quote($_POST['type']);
		$db -> query("INSERT INTO registrations
		             (course_id, course_section, user_id, type) VALUES
		             (" . $id . ", " . $section . ", " . $db->quote($user["id"]) . ", " . $type . ")");
	}
}


//gives the user a recpeit that their request was successful
function displayConfirmation($db, $amount, $invoice) {
	head(); ?>

	<section class="content">
		<div class="container">
			<h1>Order Successful!</h1>
			<p>Thank you for taking classes with the Experimental College!
				You should receive an email shortly detailing the classes you applied for.
				Your order information is below.</p>
			<table>

				<?php if ($_POST['type'] == 'credit') { ?> 
					<tr>
						<td>Card Ending in: </td> 
						<td><?= substr($_POST['card'], 12) ?></td>
					</tr>
					<tr>
						<td>Amount: </td>
						<td>$<?= $amount ?></td>
					</tr>
					<tr>
						<td>Invoice: </td>
						<td><?= $invoice ?></td>
					</tr>
				<?php } ?>

				<tr>
					<td>Classes: </td>
					<td></td>
				</tr>

				<?php for ($j = 0; $j < count($_SESSION["cart"]); $j+= 2) { 
					$course = $db->select("SELECT name FROM courses 
					                       WHERE id = " . $db->quote($_SESSION["cart"][$j]))[0]; ?>
					<tr>
						<td></td>
						<td><?= $course["name"] ?></td>
					</tr>
				<?php } ?>

			</table>
			<p>Head over to <a href="mycourses.php">my courses</a> at any time to view your
				currently enrolled sections</p>
		</div>
	</section>

	<?php tail();
}


function sendConfirmationEmail($db, $user, $amount, $invoice) {
	$body = <<<HTML
	<h1>ASUW Experimental College Email Confirmation</h1>
	<p>Thank you for registering with the Experimental College! We're excited 
	to have you!</p>
HTML;

	if ($user['newUser']) {
		$body .= '<p><b>Our records indicate you are a new user. In order to access your new account, you will need to click the link below</b><br />';
		$pass = randomString(40);
		$db -> query("UPDATE users 
		              SET password_reset = " . $db->quote($pass) . 
		            " WHERE email = " . $db->quote($user["email"]));

		$body .= "https://depts.washington.edu/asuwxpcl/users/forgot.php?reset=" . $pass . "&email=" . $user["email"];
		$body .= "</p>";
	}

	$body .= <<<HTML
	<p><b>REMINDER:</b> Bring cash or check and your ID to your class(es) on the 
	first day</p>
	<p>Below, you may find your order confirmation and any additional instructor
	written comments about your new class(es)</p><table>
HTML;
	if ($_POST['type'] == 'credit') {	
		$body .= "<tr><td>Card Ending in: </td><td>";
		$body .= substr($_POST["card"], 12) ;
		$body .= "</td></tr><tr><td>Amount: </td><td>$";
		$body .= $amount;
		$body .= "</td></tr><tr><td>Invoice: </td><td>";
		$body .= $invoice;
		$body .= "</td></tr><tr><td>Classes: </td><td></td></tr>";
	}
	for ($i = 0; $i < count($_SESSION["cart"]); $i+= 2) { 
		$db = new DB();
		$course = $db->select("SELECT name FROM courses 
		                       WHERE id = " . $db->quote($_SESSION["cart"][$i]))[0]; 
		$body .= <<<HTML
		<tr>
			<td></td>
			<td> {$course["name"]} </td>
		</tr>
HTML;

	}
	$body .= "</table>";

	for ($i = 0; $i < count($_SESSION["cart"]); $i+= 2) {
		$a = $db -> select("SELECT c.name,
				c.email as message,
				c.instructor_id, 
				u.email,
				u.first_name,
				u.last_name,
				s.days, 
				s.times,
				s.location_spec,
				s.fee_gen,
				s.fee_uw
				FROM courses c
				JOIN sections s ON s.course_id = c.id
				JOIN users u ON c.instructor_id = u.id
				WHERE c.id = " . $db->quote($_SESSION["cart"][$i]) . " 
				AND s.section = " . $db->quote($_SESSION["cart"][$i + 1]))[0];
		$body .= "<h2><a href='depts.washington.edu/asuwxpcl/course?id=" . $_SESSION["cart"][$i];
		$body .= "'>" . $a["name"] . "</a></h2>";
		$body .= "<p>Instructor " . $a["first_name"] . " " . $a["last_name"];
		$body .= "<br>" . $a["email"] . "</p>";
		$body .= "<p>" . $a["location_spec"] . " on " . $a["days"] . " at " . $a["times"] . "<br>";
		$body .= "$" . $a["fee_gen"] . ", or $" . $a["fee_uw"] . " for UW students with ID"; 
		$body .= "<p>" . $a["message"] . "</p>";
	}
	$body .= "<h2>Thank you for choosing the Experimental College!</h2>";
	$body .= "<p>Please do not hesitate to contact your instructor or the ";
	$body .= "Experimental College if you have any questions.</p>";

	require("../modules/PHPMailer/PHPMailerAutoload.php");

	$mail = new PHPMailer(true);
	$mail->AddAddress($user["email"]);
	$mail->SetFrom("No-Reply@exco.org");
	$mail->Subject = "Class Information";
	$mail->AddReplyTo("noreply@exco.org", "ASUW Experimental College");
	$mail->SetFrom("noreply@exco.org", "ASUW Experimental College");
	$mail->Body = $body;
	$mail->IsHTML(true);
	try {
		$mail->Send();
	} catch (Exception $e) {
		error("Email Failure", $mail->ErrorInfo);
	}		
}


?>