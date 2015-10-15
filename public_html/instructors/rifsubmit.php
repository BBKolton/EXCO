<?php
	require("../common.php");
	session_start();
	if (!verifyAdminOrClassInstructor($_GET['id'])) {
		error('Access Denied', 'You\'re not allowed to edit this RIF!');
	}

	$db = new DB();

	$data = [];
	foreach ($_POST as $key => $val) {
		$data[$key] = $db->quote($val);
	}

	if ($data['info-overload'] == '') {
		$data['info-overload'] = 0;
	}

	$db -> query("UPDATE rifs
	              SET name = " . $data["name"] . ",
	                  room_rate = " . $data["room-rate"] . ",
	                  room_hours = " . $data["room-hours"] . ",
	                  fee_gen = " . $data["fee-gen"] . ",
	                  fee_uw = " . $data["fee-uw"] . ",
	                  category = " . $data["info-cat"] . ",
	                  loc_gen = " . $data["loc-gen"] . ",
	                  loc_spec = " . $data["loc-spec"] . ",
	                  firstday = " . $data["info-firstday"] . ",
	                  overload = " . $data["info-overload"] . ",
	                  underage = " . $data["info-age"] . ",
	                  text_email = " . $data["info-email"] . ",
	                  text_short = " . $data["info-short"] . ",
	                  text_long = " . $data["info-long"] . "
	              WHERE id = " . $db->quote($_GET['id']));

	header('Location: rif.php?id=' . $_GET['id']);

?>