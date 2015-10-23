<?php

	
	require("../common.php");
	session_start();
	$db = new DB();
	

	//print_r($_POST);
	
	//Create a new blank rif
	if (isset($_GET['create']) && $_SESSION['permissions'] > 1) {
		$db -> query('INSERT INTO rifs (instructor_id, name)
		               VALUES (' . $db->quote($_SESSION['id']) . ", 'New Course')");
		header('Location: rifs.php');
		die();
	}


	if (!verifyAdminOrClassInstructor($_GET['id'])) {
		error('Access Denied', 'You\'re not allowed to edit this RIF!');
	}


	//Delete a rif
	if (isset($_GET['delete'])) {
		$db -> query('DELETE FROM rifs
		              WHERE id = ' . $db->quote($_GET['id']));
		$db -> query('DELETE FROM rifs_items
		              WHERE rif_id = ' . $db->quote($_GET['id']));
		$db -> query('DELETE FROM rifs_sections
		              WHERE rif_id = ' . $db->quote($_GET['id']));
		header('Location: /asuwecwb/instructors/rifs.php');
		die();
	}


	if (isset($_GET['late'])) {
		if ($_SESSION['permissions'] > 2) {
			$db -> query('UPDATE rifs
			              SET late = ' . $db->quote($_GET['late']) . '
			              WHERE id = ' . $db->quote($_GET['id']));
		} else {
			error('Access Denied', 'You are not an administrator');
		}
		header('Location: rifs.php');
		die();
	}

	if (isset($_GET['paid'])) {
		if ($_SESSION['permissions'] > 2) {
			$db -> query('UPDATE rifs
			              SET paid = ' . $db->quote($_GET['paid']) . '
			              WHERE id = ' . $db->quote($_GET['id']));
		} else {
			error('Access Denied', 'You are not an administrator');
		}
		header('Location: rifs.php');
		die();
	}

	if (isset($_GET['facilities'])) {
		if ($_SESSION['permissions'] > 2) {
			$db -> query('UPDATE rifs
			              SET facilities = ' . $db->quote($_GET['facilities']) . '
			              WHERE id = ' . $db->quote($_GET['id']));
		} else {
			error('Access Denied', 'You are not an administrator');
		}
		header('Location: rifs.php');
		die();
	}

	//Update the rif

	$data = [];
	$items = [];
	$sections = [];
	foreach ($_POST as $key => $val) {
		$data[$key] = $db->quote($val);
		if (strpos($key, 'item') !== false) {
			array_push($items, $db->quote($val));
		} else if (strpos($key, 'section') !== false) {
			array_push($sections, $val);
		}
	}


	$db -> query('DELETE FROM rifs_items
	              WHERE rif_id = ' . $db->quote($_GET['id']));
	$db -> query('DELETE FROM rifs_sections
	              WHERE rif_id = ' . $db->quote($_GET['id']));


	for ($i = 0; $i < sizeof($items); $i+= 3) {
		$db -> query("INSERT INTO rifs_items
		              (rif_id, name, cost, quantity)
		              VALUES ( " .
		                  $db->quote($_GET['id']) . "," .
		                  $items[$i] . "," . 
		                  $items[$i + 1] . "," .
		                  $items[$i + 2] .
		              ")");
	}

	for ($i = 0; $i < sizeof($sections); $i+= 3) {
		echo 'herp';
		$startDay = $db->quote($sections[$i] . ' ' . substr($sections[$i + 2], 0, strpos($sections[$i + 2], ',')));
		$db -> query("INSERT INTO rifs_sections 
		              SET rif_id = " . $db->quote($_GET['id']) . ",
		                  section = " . $db->quote($i / 3 + 1) . ",
		                  start_day = " . $startDay . ",
		                  days = " . $db->quote($sections[$i + 2]) . ",
		                  time_start = " . $db->quote($sections[$i]) . ",
		                  time_end = " . $db->quote($sections[$i + 1]) . ",
		                  size = " . $data['size'] . ",
		                  fee_gen = " . $data['fee-gen'] . ",
		                  fee_uw = " . $data['fee-uw'] . ",
		                  location_gen = " . $data['loc-gen'] . ",
		                  location_spec = " . $data['loc-spec']);
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

	//header('Location: rif.php?id=' . $_GET['id']);

?>