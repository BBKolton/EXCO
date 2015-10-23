<?php 
require('../common.php');
session_start();

if (!isset($_SESSION['id'])) {
	header('Location: /asuwecwb/login.php');
	die();
}

head('', 1);
?>

<section class='content'>
	<div class='container'>
		<h2>Apply to Instruct with EXCO</h2>
		<p>Thank you for your interest in teaching with the Experimental College. We're always looking for new, talented instructors to teach engaging, unique, and quality classes. Please fill out the form below to apply to the college.</p>
		<form>

		</form>
	</div>
</section>


<?php
tail();
?>