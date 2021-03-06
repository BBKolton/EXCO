<?php
//SuperAdmin control panel. Change user account passwords, bring the site down, and other super things

require("../common.php");
session_start();

//check for superadmin account
if (empty($_SESSION)) {
	header("location: /asuwxpcl/users/login.php");
	die();
} if ($_SESSION["permissions"] < 4) {
	error("Drastically Insufficient Privledges!", "You are totally not in any way shape or form allowed to view this page!!!");
	die();
}

//check for if the site is in maintenance mode
$maintenanceMode;
if ($_POST["maintenance"] == "toggle") {
	$maintenanceMode = toggleMaintenanceMode();	
} else {
	$maintenanceMode = false;
	if (strpos(file_get_contents("../.htaccess"), "#TOGGLE") === false) {
		$maintenanceMode = true;
	}
}

$db = new DB();

//change password for user if requested
if (!empty($_POST["email"]) && !empty($_POST["pass"])) {
	$db->query("UPDATE " . $DATABASE . ".users 
	            SET password = " . $db->quote(password_hash($_POST["pass"], PASSWORD_BCRYPT)) . " 
	            WHERE email = " . $db->quote($_POST["email"]));
}

//change account type for user if reqested
if (!empty($_POST["account-type"]) && !empty($_POST["email"])) {
	$db->query("UPDATE " . $DATABASE . ".users 
	            SET permissions = " . $db->quote($_POST["account-type"]) . " 
	            WHERE email = " . $db->quote($_POST["email"]));
}


head("<link href='/asuwxpcl/.assets/css/superadmin.css' type='text/css' rel='stylesheet'>"); ?>

<section role="main">
	<div class="jumbotron">
		<div class="container">
			<h1>Superadmin Panel</h1>
		</div>
	</div>
</section>

<section class="content" />
	<div class="container">
		<div>
			<h3>Warning</h3>
			<p>I really hope I shouldn't have to tell you to be careful at this point...<span style="float: right; margin-right: 35px;"><i><b>-Bryce</b></i></span></p> 
		</div>
		<div>
			<h2>Take Site Down</h2>
			<p>Take the site into maintenance mode. All requests to the site will be filtered to a "site is down page," besides requests to this page and the login portal.</p>
			<p id="maintenance-wrapper"><b >Status: 

			<?php if ($maintenanceMode) { ?>
				<span id="maintenance-indicator" style="background-color: green">Site Running</span>
			<?php } else { ?>
				<span id="maintenance-indicator" style="background-color: red">Shut Down</span>
			<?php }	?>
			</b></p>

			<form action="superadmin.php" method="post">
				<button type="submit" name="maintenance" value="toggle">Toggle Maintenance Mode</button>
			</form>
		</div>
		<div>
			<h2>Change User Password</h2>
			<p>Manually change a user's password. You can change any account from here. Use this option only when "forgot a password" does not work for a user (which means their email system is blocking our recovery email). Good practice is setting their email to a generic password, and encouraging them to change it once they login.</p>
			<form action="superadmin.php" method="post">
				<input type="text" name="email" placeholder="email" />
				<input type="text" name="pass" placeholder="password" />
				<button type="submit">Change Account Password</button>
			</form>
		</div>
		<div>
			<h2>Change User Type</h2>
			<p>Change a user's account type. The user in question will have to relog to view changes. ONLY THE WEBMASTER, DIRECTOR AND CO-DIRECTOR SHOULD BE SUPERADMINS</p>
			<form action="superadmin.php" method="post">
				<select name="account-type">
					<option value="1">General/UW Student</option>
					<option value="2">Instructor</option>
					<option value="3">Administrator</option>
					<option value="4">Super Administrator</option>
				</select>
				<input type="text" name="email" placeholder="email" />
				<button type="submit">Change Account Type</button>
			</form>
		</div>
	</div>
</section>




<?php tail();

//brings teh site down if it is up, and vice versa.
//only the superadmin page, login page are available when site is down
function toggleMaintenanceMode() {
	$maintenanceMode = false;
	if (strpos(file_get_contents("../.htaccess"), "#TOGGLE") === false) {
		$maintenanceMode = true;
	}
	$data = null;
	if ($maintenanceMode) {
		$data = <<<DATA
RewriteEngine On

RewriteRule ^((?!(maintenancemode|login|logout|superadmin|\.assets)).)*$ /asuwxpcl/maintenancemode.php [NE,R,L]

#TOGGLE
#Created by superadmin.php
DATA;
	}
	$htaccess = file_put_contents("../.htaccess", $data);
	return !$maintenanceMode;
}


?>