<?php
	require("common.php");
	session_start();
	
	$ch = curl_init();

	//verify user data
	if (empty($_POST["card"]) || empty($_POST["exp"]) ||
			empty($_POST["cvc"]) || empty($_POST["phone"])) {
		error("Missing Information", "You did not complete a required field");
	}
	$db = new DB();
	$user = $db -> select("SELECT email, first_name, last_name, phone, type 
	                       FROM " . $DATABASE . ".users 
	                       WHERE id = " . $db -> quote($_SESSION["id"]));
	$charge = 5;
	if ($user[0]["type"] == "general") {
		$charge = 12;
	}

	$charge = $charge * (count($_SESSION["cart"]) / 2);

	$fields = array(
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
		"ssl_amount"=>urlencode(1),
		"ssl_salestax"=>"0",
		"ssl_invoice_number"=>urlencode($_POST["ssl_invoice_number"]),
		"ssl_first_name"=>urlencode($user[0]["first_name"]),
		"ssl_last_name"=>urlencode($user[0]["last_name"]),
		"ssl_email"=>urlencode($user[0]["email"]),
		'ssl_avs_address'=>'NONE',
		'ssl_avs_zip'=>'NONE',
		"ssl_result_format"=>"HTML",
		"ssl_receipt_decl_method"=>"POST",
		"ssl_receipt_decl_get_url"=> urlencode("http://depts.washington.edu/asuwecwb/cartresponse.php"),
		"ssl_receipt_apprvl_method"=>"POST",
		"ssl_receipt_apprvl_get_url"=> urlencode("http://depts.washington.edu/asuwecwb/cartresponse.php")
	);

	$fieldsString = "";

	foreach ($fields as $key => $value) {
		$fieldsString .= $key . "=" . $value . "&";
	}

/*	echo $fieldsString;*/

	curl_setopt($ch, CURLOPT_URL, $MERCHANTURL); //set method
	curl_setopt($ch, CURLOPT_POST, 1);
	//set post data string
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
	//these two options are frequently necessary to avoid SSL errors with PHP
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	//perform the curl post and store the result
	$result = curl_exec($ch);
	//close the curl session
	curl_close($ch);

?>