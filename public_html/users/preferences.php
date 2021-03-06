<?php
//preferences for user accounts, such as changing email, changing password,
//etc. 
require("../common.php");
session_start();

if (empty($_SESSION)) {
	header("location: /asuwxpcl/users/login.php");
	die();
}

$db = new DB();

//we be changin data
if (!empty($_POST)) {

	//verify password is correct, die if it isnt
	$pass = $db->select("SELECT password FROM " . $DATABASE . ".users 
	                     WHERE users.id = " . $db->quote($_SESSION["id"]));
	if (!password_verify($_POST["password"], $pass[0]["password"])) {
		error("Incorrect Password", "Your supplied old password does not match our records");
	}


	//change a users email
	//TODO this is copied from register user
	//TODO html entitiy escape everything going into the database
	if (!empty($_POST["email-new"])) {
		if ($_POST["email-new"] !== $_POST["email-new-confirm"]) {
			error("Emails do not Match", "You typed in two different emails");
		}

		if (preg_match("/((@uw\.edu)|(@u\.washington\.edu))/i", $exists[0]["email"])) {
			$netid = substr($exists[0]["email"], 0, strpos($exists[0]["email"], "@"));
			$db -> query("UPDATE " . $DATABASE . ".users
			              SET activation = '1', netid = " . $db->quote($netid) .
			            " WHERE email = " . $db->quote($email));
		} else {
			$db -> query("UPDATE " . $DATABASE . ".users
			              SET activation = '1', netid = NULL
			              WHERE email = " . $db->quote($email));
		}
		changeComplete("Email", "NOTE: If you have changed from an @uw.edu or @u.washington.edu address 
		to a non university	address or vice-versa, your prices have changed. Please contact the 
		Exerpimental College for further assistance.");
	}


	//change a users password
	if (!empty($_POST["password-new"])) {
		if ($_POST["password-new"] !== $_POST["password-new-confirm"]) {
			error("New Passwords do not Match", "Your passwords did not match");
		}
		if (strlen($_POST["password-new"]) < 8) {
			error("Password Too Short", "Please choose a password longer than 8 characters");
		}
		$db->query("UPDATE " . $DATABASE . ".users SET password = " . 
		            $db->quote(password_hash($_POST["password-new"], PASSWORD_BCRYPT)) . 
		          " WHERE id = " . $db->quote($_SESSION["id"]));
		changeComplete("Password");
	}


	//change a users basic information
	if (!empty($_POST["first"])) {
		if (empty($_POST["last"]) || empty($_POST["phone"]) ||
		    empty($_POST["phone"]) || empty($_POST["zip"]) || 
		    empty($_POST['mailing']) || ($_POST["mailing"] != 1 && $_POST["mailing"] != 0) ||

		    !empty($_POST['additional']) && (
		    empty($_POST['address']) || empty($_POST['city']) || 
		    empty($_POST['state']) ) ) {

			error("A Required Field was Empty", "You must include all information during this
			       request. Please do not leave a field blank under \"Change Personal Information.\"
			       Fields under \"Change Address\" and \"Change Password\" may be left blank.");
		}

		//all data is good past here
		foreach($_POST as &$val) {
			$val = $db->quote($val);
		}

		$db->query("UPDATE " . $DATABASE . ".users 
		            SET first_name = " . $_POST["first"] .
		              ", last_name = " . $_POST["last"] .
		              ", phone = " . $_POST["phone"] .
		              ", zip = " . $_POST["zip"] . 
		              ", mailing = " . $_POST["mailing"] .
		          " WHERE id = " . $db->quote($_SESSION["id"]));

		if (!empty($_POST['additional'])) {
			$db->query("UPDATE users_additional 
			            SET address = " . $_POST['address'] . ", 
			                city = " . $_POST['city'] . ",
			                state = " . $_POST['state'] . ",
			                about = " . $_POST['about'] . "
			            WHERE user_id = " . $db->quote($_SESSION['id']));
		}

		changeComplete("Information");
	}

}


function changeComplete($type = "Information", $note = "") {
	session_destroy(); 
	head(); ?>
	<section><div class="container">
		<h1><?= $type ?> Changed!</h1>
		<p>Success! Please re-login to continue.</p>
		<p><?= $note ?></p>
	</div></section>
	<?php tail();
	die();
}






//create the page with the current information
$user = $db->select("SELECT first_name,
                            last_name,
                            phone,
                            mailing,
                            email,
                            zip
                     FROM " . $DATABASE . ".users 
                     WHERE users.id = " . $db->quote($_SESSION["id"]));

$additional = $db->select('SELECT * FROM users_additional WHERE user_id = ' . $db->quote($_SESSION['id']))[0];

head(); ?>

<section class="content">
	<div class="container">
		<h1>Preferences</h1>
		<p>Update your password, email, or basic information. Please note that after updating, you will have to log back in</p>
		<form action="/asuwxpcl/users/preferences.php" method="post">
			<h2>Change Email</h2>
			<div class='form-group'>
				<p>New Email</p>
				<input class='form-control' name="email-new" type="text" />
			</div>
			<div class='form-group'>
				<p>Confirm New Email</p>
				<input class='form-control' name="email-new-confirm" type="text" />
			</div>
			<div class='form-group'>
				<p>Password</p>
				<input class='form-control' name="password" type="password" /><br />
			</div>
			<input type="reset" value="Reset" class='btn btn-info'/> <input class='btn btn-success' type="submit" value="Change Email" />
		</form><form action="/asuwxpcl/users/preferences.php" method="post">
			<h2>Change Password</h2>
			<div class='form-group'>
				<p>Current Password</p>
				<input class='form-control' name="password" type="password" />
			</div>
			<div class='form-group'>
				<p>New Password</p>
				<input class='form-control' name="password-new" type="password" />
			</div>
			<div class='form-group'>
				<p>Confirm New Password</p>
				<input class='form-control' name="password-new-confirm" type="password" /><br />
			</div>
			<input type="reset" value="Reset" class='btn btn-info'/> <input class='btn btn-success' type="submit" value="Change Password" />
		</form><form action="/asuwxpcl/users/preferences.php" method="post">
			<h2>Change Personal Information</h2>
			<div class='form-group'>
				<p>First Name</p>
				<input class='form-control' name="first" type="text" value="<?= htmlspecialchars($user[0]['first_name']) ?>" />
			</div>
			<div class='form-group'>
				<p>Last Name</p>
				<input class='form-control' name="last" type="text" value="<?= htmlspecialchars($user[0]['last_name']) ?>" />
			</div>
			<div class='form-group'>
				<p>Phone Number</p>
				<input class='form-control' name="phone" type="text" value="<?= htmlspecialchars($user[0]['phone']) ?>" />
			</div>
			<div class='form-group'>
				<p>Zip Code</p>
				<input class='form-control' name="zip" type="text" value="<?= htmlspecialchars($user[0]['zip']) ?>" />
			</div>

			<?php if (isset($additional)) { ?>
				<input type='hidden' name='additional' value='true' />
				<div class='form-group'>
					<p>Address</p>
					<input class='form-control' type='text' name='address' value='<?= $additional["address"] ?>' />
				</div>
				<div class='form-group'>
					<p>City</p>
					<input class='form-control' type='text' name='city' value='<?= $additional["city"] ?>' />
				</div>
				<div class='form-group'>
					<p>State</p>
					<input class='form-control' type='text' name='state' value='<?= $additional["state"] ?>' />
				</div>
				<div class='form-group'>
					<p>About You</p>
					<textarea class='form-control' name='about'><?= $additional['about'] ?></textarea>
				</div>
			<?php } ?>

			<div class='form-group'>
				<p>Subscribe to Mailing List</p> 
				<label><input type="radio" name="mailing" value="1" <?= $user[0]['mailing'] == 1 ? 'checked' : '' ?>/> Yes </label>
				<label><input type="radio" name="mailing" value="0" <?= $user[0]['mailing'] == 1 ? '' : 'checked' ?>/> No </label>
			</div>
			<div class='form-group'>
				<p>Password</p> 
				<input class='form-control' name="password" type="password" /><br />
			</div>
			<input type="reset" value="Reset" class='btn btn-info'/> <input class='btn btn-success' type="submit" value="Change Info" />
		</form>
	</div>
</section>



<?php tail();


?>