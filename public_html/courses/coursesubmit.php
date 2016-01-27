<?php
require('../common.php');
session_start();

if (empty($_SESSION['permissions']) || $_SESSION['permissions'] < 3) {
	error('Not logged in', 'You\'re not logged in or do not have sufficient priveleges to change this data');
}

$db = new DB();

if (isset($_GET['id']) && $_GET['moveselect']) { 
	head();
	?>
	<section class='content'>
		<div class='container'>
			<form class='form' action='coursesubmit.php' method='get'>
				<h2>Move Registration</h2>
				<input type='hidden' name='id' value='<?= $_GET["id"] ?>' />
				<input type='hidden' name='move' value='true' />
				<div class='form-group'>
					<p>Course ID Number</p>
					<input type='text' class='form-control' name='course' placeholder='i.e. 23' />
				</div>
				<div class='form-group'>
					<p>Section Number</p>
					<input type='text' class='form-control' name='section' placeholder='i.e. 1' />
				</div>

				<button type='submit' class='btn btn-primary'>Move Registration</button>
			</form>
		</div>
	</section>
	<?php
	tail();
	die();
} 

if (isset($_GET['id']) && $_GET['outsideSignup'] && isset($_GET['section'])) {
	head('<script src="/asuwxpcl/.assets/js/coursesubmit.js"></script>');
	?>
	<section class='content'>
		<div class='container'>
			<form class='form' action='/asuwxpcl/users/cartsubmit.php' method='post'>
				<h2>Outside Registration</h2>
				<p>Register a user over the phone with credit card. If the user does not have an account, it will be created. Otherwise, the class will be associated with the email entered.</p>
				<input type='hidden' name='id' value='<?= $_GET["id"] ?>' />
				<input type='hidden' name='section' value='<?= $_GET["section"] ?>' />
				<input type='hidden' name='outside' value='true' />
				<div class='form-group'>Type<select id='type' class='form-control' name='type'>
					<optgroup label='General'>
						<option value='credit'>Credit or Debit</option>
						<option value='cash'>Cash</option>
						<option value='check'>Check</option>
					</optgroup>
					<optgroup label='No Charge'>
						<option value='audit'>Audit</option>
						<option value='other'>Other</option>
					</optgroup>
				</select></div>
				<div class='form-group'>First Name<input class='form-control' type="text" name="first-name"></div>
				<div class='form-group'>Last Name<input class='form-control' type="text" name="last-name"></div>
				<div class='form-group'>Email Address<input class='form-control' type="text" name="email"></div>
				<div id='card-form'>
					<div class='form-group'>Card Number (no dashes or spaces)<input class='form-control' type="text" name="card" ></div>
					<div class='form-group'>Expiration Date (in form MMYY)<input class='form-control' type="text" name="exp" ></div>
					<div class='form-group'>CVC/CVV code (three digits on back of card)<input class='form-control' type="text" name="cvc" ></div>
				</div>
				<div class='form-group'>Phone Number<input class='form-control' type="text" name="phone"></div>
				<div class='form-group'>Zip Code<input class='form-control' type='text' name='zip'></div>
				<div class='form-group'>Where did you hear about EXCO?
					<select name='referred' class='form-control'>
						<option value='Select...' selected>Select...</option> 
						<?php $options = $db ->select("SELECT name FROM referrals ORDER BY count DESC"); 
						foreach ($options as $option) { 
							if ($option['name'] != "Select...") { ?>
							<option value='<?= $option['name'] ?>'><?= $option['name'] ?></option>
						<?php }
						} ?>
					</select>
				</div>   
				<p>When you submit, the user will be charged, and an account created. If the account already exists, the class will be added to the account. Charge is automatically computed based on email address. If the address ends in @uw.edu or @u.washington.edu, the charge will be $5. Otherwise, the charge will be $12.</p>
				<!-- <p><b>Credit card registration is on hold due to scheduled maintenance. Do not charge to credit card!</b></p> -->
				<button action="submit" class='btn btn-success'>Register User</button>
			</form>
		</div>
	</section>

	<?php
	tail();
}



if (isset($_GET['id']) && ($_GET['cancel'] || $_GET['uncancel'])) {
	$status = ( !$_GET['cancel'] ? 1 : 0 );
	$db->query('UPDATE registrations SET status = '. $status . ' WHERE id = ' . $db->quote($_GET['id']));
	header('Location: course.php?id=' . $_GET['course']);
	die();
}


if (isset($_GET['id']) && isset($_GET['course']) && isset($_GET['section']) && $_GET['move']) {
	$new = $db->select('SELECT c.id FROM courses c JOIN sections s ON s.course_id = c.id
	                    WHERE c.id = ' . $db->quote($_GET["course"]) . '
	                    AND s.section = ' . $db->quote($_GET["section"]))[0];
	if (empty($new)) {
		error('Unknown New Course', 'The section or course id entered does not exist');
	}

	$db->query('UPDATE registrations SET course_id = ' . $db->quote($_GET["course"]) . 
	           ', course_section = ' . $db->quote($_GET["section"]) . '
	            WHERE id = ' . $db->quote($_GET['id']));
	header('Location: /asuwxpcl/courses/course.php?id=' . $new['id']);
	die();
}

if (isset($_GET['id']) && $_GET['changeLocation'] && isset($_GET['loc_spec'])) {
	$db->query("UPDATE sections SET location_spec = " . $db->quote($_GET['loc_spec']) . "
	            WHERE course_id = " . $db->quote($_GET['id']) . "
	            AND section = " . $db->quote($_GET['section']));
	header('Location: /asuwxpcl/courses/course.php?id=' . $_GET['id']);
}