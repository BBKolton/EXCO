<?php
	require("../common.php");
	session_start();
	if (empty($_SESSION)) {
		header("location: /asuwxpcl/users/login.php");
		die();
	}

	if ($_SESSION["permissions"] < 3) {
		error("Insufficient Privledges!", "You are not allowed to view this page!");
		die();
	}

	$db = new DB();

	//Change account type
	if (!empty($_POST["account-type"]) && !empty($_POST["email"]) && 
			$_POST["account-type"] > 0 && $_POST["account-type"] < 3) {
		$db->query("UPDATE " . $DATABASE . ".users 
		            SET permissions = " . $db->quote($_POST["account-type"]) . " 
		            WHERE email = " . $db->quote($_POST["email"]));
	}

	//activate user account
	if (!empty($_POST["activate-email"])) {
		$db->query("UPDATE " . $DATABASE . ".users 
		            SET activation = 1 
		            WHERE email = " . $db->quote($_POST["activate-email"]));
	}

	head("<link href='/asuwxpcl/.assets/css/admin.css' type='text/css' rel='stylesheet'>"); ?>

	<section role="main">
		<div class="jumbotron">
			<div class="container">
				<h1>Admin Panel</h1>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container">
			<div>
				<h3>WIELD THE HAMMER WITH GRACE AND VIRTUE</h3>
				<p>This is the admin panel. You may elevate or lower user priveledges, set user activations, close sections, cancel classes, and more. All these actions area powerful site-affecting tools, and should be used with care and diligence. Double check your action before hitting any buttons.<span style="float: right; margin-right: 35px;"><i><b>-Bryce</b></i></span></p> 
			</div>
<!-- 			<div>
				<h2>Email Group</h2>

			</div> -->
			<div>
				<h2>Change User Type</h2>
				<p>Change a user's account type. The user in question will have to relog to view changes. </p>
				<form action="admin.php" method="post">
					<select name="account-type">
						<option value="1">General/UW Student</option>
						<option value="2">Instructor</option>
					</select>
					<input type="text" name="email" placeholder="email" />
					<button type="submit">Change Account Type</button>
				</form>
			</div>
			<div>
				<h2>Activate User</h2>
				<p>Activate a user's account. You should generally not use this, and instead rely on users activating on their own. </p>
				<form action="admin.php" method="post">
					<input type="text" name="activate-email" placeholder="email" />
					<button type="submit">Activate Account</button>
				</form>
			</div>
		</div>
	</section>



	
	<?php tail();
?>