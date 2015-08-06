<?php
session_start();
require("common.php");
$db = new DB();
		$students = $db -> select("SELECT DISTINCT users.email FROM " . $DATABASE . ".users 
		                           JOIN " . $DATABASE . ".registrations reg ON reg.user_id = users.id
		                           JOIN " . $DATABASE . ".courses co ON reg.course_id = co.id
		                           WHERE co.id = " . $db->quote($_GET["id"]));	

		foreach ($students as $user) {
		echo "<br>";
			print($user["email"]);
		}

mailUsers($students, "test subject", "test message");



	function mailUsers($users, $subject, $text) {
		require("modules/PHPMailer/PHPMailerAutoload.php");

		$mail = new PHPMailer(true);
		$mail->AddAddress($_SESSION["email"]);
		foreach ($users as $user) {
			$mail->AddBCC($user["email"]);
		}
		$mail->SetFrom($_SESSION["email"]);
		$mail->Subject = $subject;
		$mail->AddReplyTo($_SESSION["email"], $_SESSION["name"]);
		$mail->SetFrom("noreply@exco.org", "ASUW Experimental College");
		$mail->Body = $text;
		try {
			$mail->Send();
			echo "success";
		} catch (Exception $e) {
			//error("Email Failure", $mail->ErrorInfo);
			echo "didnt work <br>";
			echo $mail->ErrorInfo;
		}
	}

?>