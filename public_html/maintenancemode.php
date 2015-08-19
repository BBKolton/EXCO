<?php
	require("common.php");

	if (strpos(file_get_contents(".htaccess"), "#TOGGLE") === false) {
		header("location: index.php");
		die();
	}

?>

<head>	
	<style>

		body {
			background-image: url(".assets/img/index-2.jpg");
			background-position: 50% 50%;
			background-size: cover;
		}

		div > * {
			color: white;
			font-family: "calibri" sans-serif;
			text-align: center;
		}

		h1 {
			font-size: 30pt;
			margin-top: 40px;
		}

		p {
			font-size: 20pt;
		}

		a {
			color: white;
		}

	</style>
</head>
<body>
	<div>
		<h1>ASUW Experimental College</h1>
		<p>Down for maintenance. Please check back soon! <br />
		SuperAdmins, use SU Panel <a href="/asuwecwb/admin/superadmin.php">here</a>.
		Logout <a href="/asuwecwb/users/logout.php">here</a>. </p>
	</div>
</body>