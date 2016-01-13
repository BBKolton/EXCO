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
	$c = $c[0];
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


if ($_POST['addSection']) {
	$db -> query("INSERT INTO rifs_sections
                (rif_id, days, time_start, time_end)
                VALUES ( " .
                    $db->quote($_GET['id']) . "," .
                    $db->quote($_POST['dates']) . "," . 
                    $db->quote($_POST['startTime']) . "," .
                    $db->quote($_POST['endTime']) .
                ")");

	printSections();
	die();
}

if ($_POST['removeSection']) {
	$db -> query('DELETE FROM rifs_sections WHERE id = ' . $db->quote($_POST['section']));
	printSections();
}

function printSections() {
	$db = new DB();
	$s = $db->select("SELECT * FROM rifs_sections WHERE rif_id = " . $db->quote($_GET['id']));	
	?>

	<h3>All Sections</h3>
	<?php if (empty($s)) { ?>
		<p>You have no saved sections yet</p>
	<?php } else { ?>
		<ul>
		<?php foreach ($s as $sec) { ?>
			<li><a class='removeSection' section='<?= $sec["id"] ?>'><button class='btn btn-danger' type='button'><span class='glyphicon glyphicon-remove'></span></button></a> <?= $sec['time_start'] . ' - ' . $sec['time_end'] . ', ' . $sec['days'] ?></li>
		<?php } ?>
		</ul>
	<?php } ?>
<?php 
die();
}


function printItems() {
	$db = new DB();
	$i = $db->select("SELECT * FROM rifs_items WHERE rif_id = " . $db->quote($_GET['id']));
	if (!empty($i)) { ?>
		<div class='col-md-4 hidden-xs hidden-sm'>
			<label>Name</label>
		</div>
		<div class='col-md-4 hidden-xs hidden-sm'>
			<label>Cost</label>
		</div>
		<div class='col-md-4 hidden-xs hidden-sm'>
			<label>Quantity</label>
		</div>
	<?php } ?>
			
		<?php foreach ($i as $item) { ?>
			<div class='itemSection'>
				<input type='hidden' name='id' value='<?= $item['id'] ?>' />
				<div class='row margin-fix'>
					<div class='col-md-4 col-xs-12'>
						<div class="form-group">
							<label for="name" class="col-md-4 control-label hidden-md hidden-lg">Name</label>
							<div class="col-xs-12">
								<div class='input-group'>
									<span class='input-group-btn'>
										<button class='btn btn-danger removeItem' type='button'>
											<span class='glyphicon glyphicon-remove'></span>
										</button>
									</span>
									<input id="name" name="name" type="text" placeholder="name" value="<?= $item["name"] ?>" class="name <?= $item['id'] ?> form-control"/>
								</div>
							</div>
						</div>
					</div>

					<div class='col-md-4 col-xs-12'>
						<div class="form-group">
							<label for="cost" class="col-md-4 control-label hidden-md hidden-lg">Cost</label>
							<div class="col-xs-12">
								<input id="cost" name="cost" type="text" placeholder="cost" value="<?= $item["cost"] ?>" class="cost <?= $item['id'] ?> form-control"/>
							</div>
						</div>
					</div>

					<div class='col-md-4 col-xs-12'>
						<div class="form-group">
							<label for="quantity" class="col-md-4 control-label hidden-md hidden-lg">Quantity</label>
							<div class="col-xs-12">
								<input id="quantity" name="quantity" type="text" placeholder="quantity" value="<?= $item["quantity"] ?>" class="quantity <?= $item['id'] ?> form-control"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

<?php
}


//update rif items
if (isset($_POST['items'])) {
	$parsed = array();
	parse_str($_POST['serialized'], $parsed);
	$_POST = array_merge($parsed, $_POST);
	unset($_POST['serialized']);
	saveItems();
	die();
}


function saveItems() {
	$db = new DB();
	$items = $_POST['items'];
	foreach ($items as $item) {
		$rif = $db->select("SELECT rif_id FROM rifs_items WHERE id = " . $db->quote($item['id']))[0];
		if (verifyAdminOrRifInstructor($rif)) {

			$db->query("UPDATE rifs_items
			            SET name = " . $db->quote($item['name']) . " ,
			            cost = " . $db->quote($item['cost']) . " ,
			            quantity = " . $db->quote($item['quantity']) . " 
			            WHERE id = " . $db->quote($item['id']));
		}
	}

	$db->query("UPDATE rifs 
	            SET room_rate = " . $db->quote($_POST['room_rate']) . ", 
	            room_hours = " . $db->quote($_POST['room_hours']) . ", 
	            text_facilities = " . $db->quote($_POST['text_facilities']) . ", 
	            fee_uw = " . $db->quote($_POST['fee_uw']) . ", 
	            expected = " . $db->quote($_POST['expected']) . ",
	            location_gen = " . $db->quote($_POST['location_gen']) . ", 
	            location_spec = " . $db->quote($_POST['location_spec']) . ",
	            fee_gen = " . $db->quote($_POST['fee_gen']) . " 
	            WHERE id = " . $db->quote($_GET['id']));

	var_dump($_POST);
	die();
}


if ($_POST['deleteItem']) {
	echo $_POST['id'];
	if(verifyAdminOrRifInstructor($_GET['id'])) {
		$db->query("DELETE FROM rifs_items WHERE id = " . $db->quote($_POST['id']));
		echo 'lolzers';
	}
	echo 'lel';
	die();
}


if ($_POST['newItem']) {
	$db->query("INSERT INTO rifs_items (rif_id) VALUES (" . $db->quote($_GET['id']) . ")");
	printItems();
	die();
}


//Update the rif
if ($_POST['update']) {
	var_dump($_POST);

	if ($_POST['info-overload'] == '') {
		$_POST['info-overload'] = 0;
	}

	echo "name is ";
	$data = ($_POST['update']);
	var_dump($data);
	
	$db -> query("UPDATE rifs
	              SET name = " . $db->quote($_POST['name']) . ",
	                  category = " . $db->quote($_POST['category']) . ",
	                  size = " . $db->quote($_POST['size']) . ",
	                  loc_gen = " . $db->quote($_POST['loc-gen'])           . ",
	                  loc_spec = " . $db->quote($_POST['loc-spec'])             . ",
	                  firstday = " . $db->quote($_POST['firstday']) . ",
	                  overload = " . $db->quote($_POST['overload']) . ",
	                  underage = " . $db->quote($_POST['underage']) . ",
	                  text_email = " . $db->quote($_POST['text_email']) . ",
	                  text_short = " . $db->quote($_POST['text_short']) . ",
	                  text_long = " . $db->quote($_POST['text_long']) . "
	              WHERE id = " . $db->quote($_GET['id']));

}
?>
