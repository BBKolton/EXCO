<?php
	session_start();
	session_destroy();
	header("Location: /asuwxpcl/index.php");
	die(); //  :(
?>