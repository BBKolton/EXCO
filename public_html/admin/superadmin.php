<?php
	require("../common.php");
	session_start();
	if (empty($_SESSION)) {
		header("location: /asuwecwb/users/login.php");
		die();
	}

	if ($_SESSION["permissions"] < 4) {
		error("Drastically Insufficient Privledges!", "You are totally not in any way shape or form allowed to view this page!!!");
		die();
	}


	$maintenanceMode;
	if ($_POST["maintenance"] == "toggle") {
		$maintenanceMode = toggleMaintenanceMode();	
	} else {
		$maintenanceMode = false;
		if (strpos(file_get_contents("../.htaccess"), "#TOGGLE") === false) {
			$maintenanceMode = true;
		}
	}




	head("<link href='/asuwecwb/.assets/css/superadmin.css' type='text/css' rel='stylesheet'>"); ?>

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
		</div>
	</section>



	
	<?php tail();

	function toggleMaintenanceMode() {
		$maintenanceMode = false;
		if (strpos(file_get_contents("../.htaccess"), "#TOGGLE") === false) {
			$maintenanceMode = true;
		}
		$data = null;
		if ($maintenanceMode) {
			$data = <<<DATA
RewriteEngine On

RewriteRule ^((?!(maintenancemode|login|logout|superadmin|\.assets)).)*$ /asuwecwb/maintenancemode.php [NE,R,L]

#TOGGLE
DATA;
		}
		$htaccess = file_put_contents("../.htaccess", $data);
		return !$maintenanceMode;
	}


?>set user passwords,