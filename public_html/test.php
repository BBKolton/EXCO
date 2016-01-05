<?php
//The returning Instructor Form. Instructors fill this out to tell us what
//courses they'll be teaching with us next quarter

require("common.php");
session_start();

function sendConfirmationEmail($c, $DATABASE, $user, $newUser) {
	$body = <<<HTML
	<h1>ASUW Experimental College Email Confirmation</h1>
	<p>Thank you for registering with the Experimental College! We're excited 
	to have you!</p>
HTML;
	if ($newUser) {
		$body .= '<p><b>Our records indicate you are a new user. In order to access your new account, you  will need to click the link below</b><br />'
		$pass = randomString(40);
		$db -> query("UPDATE " . $DATABASE . ".users 
		              SET password_reset = " . $db->quote($pass) . 
		            " WHERE email = " . $db->quote($user[0]["email"]));

		$body .= "https://depts.washington.edu/asuwxpcl/users/forgot.php?reset=" . $pass . "&email=" . $user[0]["email"];
		$body .= "</p>"

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
}


?>