<?php
	require("../common.php");
	session_start();
	if ($_SESSION["permissions"] < 3) {
		error("Insufficient Privledges!", "You are not allowed to view this page! (are you logged in?)");
		die();
	}

	head("<link href='/asuwecwb/.assets/css/admin.css' type='text/css' rel='stylesheet'>"); ?>

	<section role="main">
		<div class="jumbotron">
			<div class="container">
				<h1>Admin Panel</h1>
			</div>
		</div>
	</section>

	<section class="content" />
		<div class="container">
			<div>
				<h3>WEILD THE HAMMER WITH GRACE AND VIRTUE</h3>
				<p>This is the admin panel. You may elevate or lower user priveledges, set user activations, close sections, cancel classes, and more. All these actions area powerful site-affecting tools, and should be sued with care and diligence. Double check your action before hitting any buttons.<span style="float: right; margin-right: 35px;"><i><b>-Bryce</b></i></span></p> 
			</div>
			<div>
				<h2>Change User Privileges</h2>




		</div>
	</section>



	
	<?php tail();
?>