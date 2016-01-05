<?php
//deals with all data sent from rifs and rifsadmin3

require("../common.php");
session_start();
$db = new DB();


//Create a new blank rif
if (isset($_GET['create']) && $_SESSION['permissions'] > 1) {
	$db -> query('INSERT INTO rifs (instructor_id, name)
	               VALUES (' . $db->quote($_SESSION['id']) . ", 'New Course')");
	header('Location: rifs.php');
	die();
}


if (!verifyAdminOrRifInstructor($_GET['id'])) {
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
	header('Location: /asuwxpcl/instructors/rifs.php');
	die();
}

//set a rifs submitted
if (isset($_GET['submitted'])) {
	if ($_SESSION['permissions'] > 2) {
		$db -> query('UPDATE rifs
		              SET submitted = ' . $db->quote($_GET['submitted']) . '
		              WHERE id = ' . $db->quote($_GET['id']));
	} else {
		error('Access Denied', 'You are not an administrator');
	}
	header('Location: rifs.php');
	die();
}

if (isset($_GET['rif-submit'])) {
	$text = '';

	$c = $db->select("SELECT u.id as instructor_id,
	                         r.id as id,
	                         r.name,
	                         u.first_name, 
	                         u.last_name, 
	                         r.fee_gen,
	                         r.fee_uw,
	                         r.loc_gen,
	                         r.loc_spec,
	                         r.text_short
	                         FROM rifs r JOIN users u ON r.instructor_id = u.id 
	                         WHERE r.id = " . $db->quote($_GET['id']));
	$c = $c[0];  ////////
	//var_dump($c);
	$u = $db->select("SELECT first_name, last_name FROM users WHERE id = " . $db->quote($c['instructor_id']));
	$s = $db->select("SELECT * FROM rifs_sections WHERE rif_id = " . $db->quote($c['id']));

	$text.= $c['name'] . "\n";
	$text.= $c['first_name'] . ' ' . $c['last_name'] . "\n";
	for ($i = 0; $i < count($s); $i++) { 
		$sec = $s[$i];
		$text.= 'Sec ' . ($i + 1) . ': ' . $sec['days'] . "\n";
		$text.= 'Sec ' . ($i + 1) . ': ' . $sec['time_start'] . ' - ' . $sec['time_end'] . "\n";
	}
	$text.= $c['text_short'] . "\n";
	$text.= 'General Public / ' . $c['fee_gen'] . "\n";
	$text.= 'UW Students / ' . $c['fee_uw'] . "\n";
	$text.= 'Max Enrollment / ' . $s[0]['size'] . "\n";
	$text.= 'Location / ' . $c['loc_gen'] . "\n";

	$db -> query('UPDATE rifs
	              SET submitted = 1
	              WHERE id = ' . $db->quote($_GET['id']));
	$db -> query('INSERT INTO galleys (id, text) VALUES (' . $db->quote($_GET['id']) . ',' . $db->quote($text) . ')
	              ON DUPLICATE KEY UPDATE text = '. $db->quote($text));
	

	header('Location: galley.php?id=' . $_GET['id']);	
	die();
}

//set a rifs lateness
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

//set a rifs payment
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

//set a rifs facilities
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

if (isset($_POST['review'])) {
	header('Location: rifreview.php?id=' . $_GET['id']);
	die();
}

header('Location: rif.php?id=' . $_GET['id']);

?>