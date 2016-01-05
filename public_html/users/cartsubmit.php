<?php
require("../common.php");
session_start();

//grab the user data from teh database for freshest bariables
$db = new DB();

$db->query("UPDATE referrals
            SET count = count + 1
            WHERE name = " . $db->quote($_POST['referred']));

$user = $db -> select("SELECT id, email, first_name, last_name, phone, netid  
                       FROM " . $DATABASE . ".users 
                       WHERE id = " . $db -> quote($_SESSION["id"]));


$newUser = false;
if ($_POST['outside']) {
	$_SESSION['cart'][0] = $_POST['id'];
	$_SESSION['cart'][1] = $_POST['section'];

	$user = $db->select("SELECT id, email, first_name, last_name, phone, netid
	                     FROM users 
	                     WHERE email = " . $db->quote($_POST['email']));
	if (empty($user)) {
		$db -> query("INSERT INTO " . $DATABASE . ".users 
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
	}
	$user = $db->select("SELECT id, email, first_name, last_name, phone, netid
	                     FROM users 
	                     WHERE email = " . $db->quote($_POST['email']));
}

verifyUserData();
$fieldsString = createCurlData($DATABASE, $MERCHANTID, $MERCHANTUSER, $MERCHANTPIN, $user);
$curlData = curlRequest($MERCHANTURL, $fieldsString);
if ($curlData["result"] === "0") {
	registerCourses($DATABASE, $user);
	displayConfirmation($curlData, $DATABASE, $db);
	sendConfirmationEmail($curlData, $DATABASE, $user, $newUser);
	$_SESSION["cart"] = null;
} else if ($curlData["Code"]) { 
	error($curlData["Name"], $curlData["Message"] . " This error was autogenerated. Contact EXCO if you need assistance");
}


//verifies user data, throws errors if they arent signed in, if there is no data,
//if there are no classes, or if there's missing credit data
function verifyUserData() {
	if (empty($_SESSION["id"])) {
		error("No Logon Info", "Please login to continue");
	} if (count($_SESSION["cart"]) < 2 ) {
		error("No classes", "You don't have any classes to sign up for!");
	} if (empty($_POST["card"]) || empty($_POST["exp"]) ||
			empty($_POST["cvc"]) || empty($_POST["phone"])) {
		error("Missing Information", "You did not complete a required field");
	}
}


//Create all the data for the Curl request. returns a bare string.
function createCurlData($DATABASE, $MERCHANTID, $MERCHANTUSER, $MERCHANTPIN, $user) {
	//decide just how much we're charging the user right now.
	$charge = 5;
	if ($user[0]["netid"] === null) {
		$charge = 12;
	}
	$charge = $charge * (count($_SESSION["cart"]) / 2);

	//create the invoice name
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

	//standardize the data we're about to stringify
	$fields = array(
		"ssl_test_mode"=>"FALSE", //set to false to not send to credit companies, but return approved
		"ssl_card_number"=>urlencode($_POST["card"]),
		"ssl_exp_date"=>urlencode($_POST["exp"]),
		"ssl_cvv2cvc2"=>urlencode($_POST["cvc"]),
		"ssl_merchant_id"=> $MERCHANTID,
		"ssl_user_id"=> $MERCHANTUSER,
		"ssl_pin"=> $MERCHANTPIN,
		"ssl_account_data" => "",
		"ssl_transaction_type"=>"ccsale",
		"ssl_show_form"=>"false",
		"ssl_cvv2cvc2_indicator"=>"1",
		"ssl_amount"=>urlencode($charge),
		"ssl_salestax"=>"0",
		"ssl_invoice_number"=>urlencode($invoice),
		"ssl_first_name"=>urlencode($_POST["first-name"]),
		"ssl_last_name"=>urlencode($_POST["last-name"]),
		"ssl_email"=>urlencode($_POST["email"]),
		'ssl_avs_address'=>'NONE',
		'ssl_avs_zip'=>'NONE',
		"ssl_result_format"=>"ASCII"
	);

	$fieldsString = "";
	foreach ($fields as $key => $value) {
		$fieldsString .= $key . "=" . $value . "&";
	}

	return $fieldsString;
}


//requests the data from the merchant. returns the parsed data.
function curlRequest($MERCHANTURL, $fieldsString) {
	//Create the request and populate the data
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $MERCHANTURL);
	curl_setopt($ch, CURLOPT_POST, 1);
	//set post data string
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
	//these two options are frequently necessary to avoid SSL errors with PHP
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//execute the curl
	$curlResult = curl_exec($ch);

	//close the curl session
	curl_close($ch);
	
	$curlArray = preg_split("/(ssl_)|(error)/", $curlResult);
	$values = array();
	foreach ($curlArray as $part) {
		$equal = strpos($part, "=");
		$key = trim(substr($part, 0, $equal));
		$value = trim(substr($part, $equal + 1));
		$values[$key] = $value;
	}

	return $values;
}


//gives the user a recpeit that their request was successful
function displayConfirmation($c, $DATABASE, $db) {
	head(); ?>

	<section class="content">
		<div class="container">
			<h1>Order Successful!</h1>
			<p>Thank you for taking classes with the Experimental College!
				You should receive an email shortly detailing the classes you applied for.
				Your order information is below.</p>
			<table>
				<tr>
					<td>Card Ending in: </td>
					<td><?= substr($c["card_number"], 12) ?></td>
				</tr>
				<tr>
					<td>Amount: </td>
					<td>$<?= $c["amount"] ?></td>
				</tr>
				<tr>
					<td>Invoice: </td>
					<td><?= $c["invoice_number"] ?></td>
				</tr>
				<tr>
					<td>Classes: </td>
					<td></td>
				</tr>
			
				<?php for ($i = 0; $i < count($_SESSION["cart"]); $i+= 2) { 
					$course = $db -> select("SELECT name FROM " . $DATABASE . ".courses 
					                         WHERE id = " . $db->quote($_SESSION["cart"][$i])); ?>
					<tr>
						<td></td>
						<td><?= $course[0]["name"] ?></td>
					</tr>

				<?php } ?>

			</table>
			<p>Head over to <a href="mycourses.php">my courses</a> at any time to view your
				currently enrolled classes</p>
		</div>
	</section>

	<?php tail();
}


function sendConfirmationEmail($c, $DATABASE, $user, $newUser) {
	$body = <<<HTML
	<h1>ASUW Experimental College Email Confirmation</h1>
	<p>Thank you for registering with the Experimental College! We're excited 
	to have you!</p>
HTML;

	if ($newUser) {
		$body .= '<p><b>Our records indicate you are a new user. In order to access your new account, you  will need to click the link below</b><br />';
		$pass = randomString(40);
		$db = new DB();
		$db -> query("UPDATE " . $DATABASE . ".users 
		              SET password_reset = " . $db->quote($pass) . 
		            " WHERE email = " . $db->quote($user[0]["email"]));

		$body .= "https://depts.washington.edu/asuwxpcl/users/forgot.php?reset=" . $pass . "&email=" . $user[0]["email"];
		$body .= "</p>";
	}

	$body .= <<<HTML
	<p><b>REMINDER:</b> Bring cash or check, and your ID to your class(es) on the 
	first day</p>
	<p>Below, you may find your order confirmation and any additional instructor
	written comments about your new class(es)</p>
	<table><tr><td>Card Ending in: </td><td>
HTML;
	$body .= substr($c["card_number"], 12) ;
	$body .= "</td></tr><tr><td>Amount: </td><td>$";
	$body .= $c["amount"];
	$body .= "</td></tr><tr><td>Invoice: </td><td>";
	$body .= $c["invoice_number"];
	$body .= "</td></tr><tr><td>Classes: </td><td></td></tr>";
	for ($i = 0; $i < count($_SESSION["cart"]); $i+= 2) { 
		$db = new DB();
		$course = $db -> select("SELECT name FROM " . $DATABASE . ".courses 
		                         WHERE id = " . $db->quote($_SESSION["cart"][$i])); 
		$body .= <<<HTML
		<tr>
			<td></td>
			<td> {$course[0]["name"]} </td>
		</tr>
HTML;

	}
	$body .= "</table>";
	//TODO do we give instructor emails to students?
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
				FROM " . $DATABASE . ".courses c
				JOIN " . $DATABASE . ".sections s ON s.course_id = c.id
				JOIN " . $DATABASE . ".users u ON c.instructor_id = u.id
				WHERE c.id = " . $db->quote($_SESSION["cart"][$i]) . " 
				AND s.section = " . $db->quote($_SESSION["cart"][$i + 1]));
		$body .= "<h2><a href='depts.washington.edu/asuwxpcl/course?id=" . $_SESSION["cart"][$i];
		$body .= "'>" . $a[0]["name"] . "</a></h2>";
		$body .= "<p>Instructor " . $a[0]["first_name"] . " " . $a[0]["last_name"];
		$body .= "<br>" . $a[0]["email"] . "</p>";
		$body .= "<p>" . $a[0]["location_spec"] . " on " . $a[0]["days"] . " at " . $a[0]["times"] . "<br>";
		$body .= "$" . $a[0]["fee_gen"] . ", or $" . $a[0]["fee_uw"] . " for UW students with ID"; 
		$body .= "<p>" . $a[0]["message"] . "</p>";
	}
	$body .= "<h2>Thank you for choosing the Experimental College!</h2>";
	$body .= "<p>Please do not hesitate to contact your instructor or the ";
	$body .= "Experimental College if you have any questions.</p>";


	require("../modules/PHPMailer/PHPMailerAutoload.php");

	$mail = new PHPMailer(true);
	$mail->AddAddress($user[0]["email"]);
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


//Add all the classes from the user's session into the database. 
function registerCourses($DATABASE, $user) {
	$db = new DB();
	for ($i = 0; $i < count($_SESSION["cart"]); $i+=2) {
		$id = $db->quote($_SESSION["cart"][$i]);
		$section = $db->quote($_SESSION["cart"][$i + 1]); 
		$db -> query("INSERT INTO " . $DATABASE . ".registrations
		             (course_id, course_section, user_id) VALUES
		             (" . $id . ", " . $section . ", " . $db->quote($user[0]["id"]) . ")");
	}
}


?>