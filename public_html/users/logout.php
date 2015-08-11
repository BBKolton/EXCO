<?php
	session_start();
	session_destroy();
	header("Location: /asuwecwb/index.php");
	die(); //  :(
?>