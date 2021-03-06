<?php

	//LOOKING FOR GLOBAL VARIABLES???
	//head to config.php
	require("config.php");

	//require https on all pages
	if ($_SERVER["SERVER_PORT"] !== 443 && (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == "off")) {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		die();
	}

	//creates, maintains, and contains several functions for connections to the database
	class DB {

		//the connection to MySQL
		protected static $connection;

		//initiates the connection (if one has not been made yet) and returns it or the old one
		public function connect() {
			require("config.php"); //to get the $DATABASE
			if(!isset(self::$connection)) {
				self::$connection = new MySQLi($DBLOCATION, $DBUSER, $DBPASS, $DATABASE, 64124);
			}

			if (self::$connection === false) {
				return false;
			}

			return self::$connection;
		}

		//query the database with the search provided. returns a msqli mixed result, or false
		public function query($search) {
			$connection = $this -> connect();
			$result = $connection -> query($search);
			if ($result === false) {
				echo "result was false! <br>" . $search . " <br> ";
			}
			return $result;
		}

		//get rows from the databse (like a SELECT). creashes on failure, array on success
		public function select($search) {
			$rows = array();
			$result = $this -> query($search);
			if($result === false) {
				echo "A database error has ocurred. Please contact the web admin."; //mercy be upon your soul
				die();
			}
			while ($row = $result -> fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}

		//get the last error from database, returns string
		public function error() {
			$connection = $this -> connect();
			return $connection -> error;
		}

		//quotes and escapes values for use in a database query. returns a sanitized string
		public function quote($value) {
			$connection = $this->connect();
			return "'" . $connection->real_escape_string($value) . "'";
		}

		//like above but does not surround with quotes
		public function escape($value) {
			$connection = $this->connect();
			return $connection->real_escape_string($value);
		}

		public function getInsertedId() {
			$connection = $this->connect();
			return $connection->insert_id;
		}

	}

	function verifyAdminOrClassInstructor($courseID) {
		if ($_SESSION["permissions"] >= 3) {
			return true;
		} 
		if ($_SESSION["permissions"] == 2) {
			$db = new DB();
			$user = $db -> select("SELECT users.id 
			                       FROM " . $DATABASE . ".users 
			                       JOIN " . $DATABASE . ".courses ON users.id = courses.instructor_id 
			                       WHERE courses.id = " . $db->quote($courseID));
			if ($user[0]["id"] == $_SESSION["id"]) {
				return true;
			}
		}
		return false;
	}

	function verifyAdminOrRifInstructor($courseID) {
		if ($_SESSION['permissions'] >= 3) {
			return true;
		}
		if ($_SESSION['permissions'] == 2) {
			$db = new DB();
			$user = $db -> select("SELECT instructor_id
			                       FROM rifs
			                       WHERE id = " . $db->quote($courseID));
			if ($user[0]['instructor_id'] == $_SESSION['id']) {
				return true;
			}
		}
		return false;
	}

	//creates a random string
	function randomString($length = 80) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-';
		$charsLeng = strlen($chars);
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			$result = $result . $chars[rand(0, $charsLeng - 1)];
		}
		return $result;
	}

	//An easy way to throw errors with logins. 
	function error($type = "Unknown", $message = "Please contact an administrator") {
		head(); ?>
		
		<section class="content">
			<div class="container">
				<h1><?= $type ?></h1>
				<p><?= $message ?></p>
				<img style="" src="/asuwxpcl/.assets/img/errorzebra.png" alt="error zebra" />
			</div>
		</section>

		<?php tail();
		die();
	}


//  _    _ _______ __  __ _      
// | |  | |__   __|  \/  | |     
// | |__| |  | |  | \  / | |     
// |  __  |  | |  | |\/| | |     
// | |  | |  | |  | |  | | |____ 
// |_|  |_|  |_|  |_|  |_|______|
//                               

	//the begining of all documents. Includes head and relevant links. allows for any extra pages to be linked
	//throught the use of $extra, and aditional commonly used resources are listed afterwards.
	function head($extra = " ", $ckEditor = 0, $datePicker = 0, $dynatable = 0) { 
		require("config.php"); //to get $MINDATE and $MAXDATE
		session_start(); 
		?>

		<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8" />
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
				
				<title>Experimental College</title>
				<link href="/asuwxpcl/.assets/img/logo.ico" type="image/icon" rel="shortcut icon" />
				<link href="/asuwxpcl/.assets/css/bootstrap.css" type="text/css" rel="stylesheet" />
<!-- 				<link href="/asuwxpcl/.assets/css/bootstrap-theme.css" type="text/css" rel="stylesheet" />
 -->				<link href="/asuwxpcl/.assets/css/EXCO.css" type="text/css" rel="stylesheet" />
				<script type="text/javascript" src="/asuwxpcl/.assets/js/jquery-1.11.3.min.js"></script>
				<script type="text/javascript" src="/asuwxpcl/.assets/js/bootstrap.min.js"></script>
				<?= $extra ?>
				
				<?php //these provide the WYSIWYG editor and datepicker for things that need them
					if ($ckEditor) { ?>
						<link rel="stylesheet" href="/asuwxpcl/.assets/plugins/jquery-ui/jquery-ui.min.css">
						<script src="/asuwxpcl/.assets/plugins/jquery-ui/jquery-ui.min.js"></script>
						<script src="/asuwxpcl/.assets/plugins/ckeditor/ckeditor.js"></script>
						<script src="/asuwxpcl/.assets/plugins/ckeditor/ckeditorReplace.js"></script>
					<?php } if ($datePicker) { 
						//labelled as js cause thats all the file has 
						include($branch . '.assets/js/rif.php'); ?>
						<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
						<script src='/asuwxpcl/.assets/plugins/daterangepicker/daterangepicker.js'></script>
						<link rel='stylesheet' href='/asuwxpcl/.assets/plugins/daterangepicker/daterangepicker.css' />
						<?php datePickerConfig($MINDATE, $MAXDATE); //this function lives in the above include branch
					 } if ($dynatable) { ?>
					 	<link rel='stylesheet' href='/asuwxpcl/.assets/plugins/dynatable/dynatable.css' />
					 	<script src='/asuwxpcl/.assets/plugins/dynatable/dynatable.js'></script>
					 <?php }
				?>

			</head>
			<!--BEGIN NAVBAR-->
			<body>
				<div class='full-wrapper'>
					<nav class="navbar navbar-inverse navbar-fixed-top">
						<div class="container">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="/asuwxpcl/index.php"><img height="150%" src="/asuwxpcl/.assets/img/logo.png" /></a>
							</div>
							<div id="navbar" class="navbar-collapse collapse">
								<ul class="nav navbar-nav">
									<li><a href="/asuwxpcl/courses/courses.php">Courses</a></li>
									<li><a href="/asuwxpcl/teach/">Teach</a></li>
									<li><a href="/asuwxpcl/help.php">Help</a></li>
									<li><a href="/asuwxpcl/about.php">About</a></li>
									<!-- <li><a href="/asuwxpcl/feedback.php">Feedback</a></li> -->
								</ul>
								<ul class="nav navbar-nav navbar-right">
									
									<?php if(isset($_SESSION["name"])) { ?>
										<?php if ($_SESSION["permissions"] > 1) { ?>
											<li class='common-instructor'><a href="/asuwxpcl/instructors">Instructors</a></li>
										<?php } ?>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= htmlspecialchars($_SESSION["name"]) ?><span class="caret"></span></a>
											<ul class="dropdown-menu">
												<li><a href="/asuwxpcl/users/mycourses.php">My Courses</a></li>
												<li><a href="/asuwxpcl/users/cart.php">Cart</a></li>
												<?php if ($_SESSION["permissions"] > 1) { ?>
													<li role="separator" class="divider"></li>
												<?php } if ($_SESSION["permissions"] > 2 ) { ?>
													<li class="dropdown-header">Administration</li>
													<li><a href="/asuwxpcl/admin/admin.php">Admin Panel</a></li>
												<?php } if ($_SESSION["permissions"] > 3 ) { ?>
													<li><a href="/asuwxpcl/admin/superadmin.php">Super Admin</a></li>
												<?php } ?>
												<li role="separator" class="divider"></li>
												<li class="dropdown-header">Account</li>
												<li><a href="/asuwxpcl/users/preferences.php">Preferences</a></li>
												<li><a href="/asuwxpcl/users/logout.php">Logout</a></li>
											</ul>
										</li>
									<?php } else { ?>
										<li><a id="login-link" href="/asuwxpcl/users/login.php">Login / Register</a></li>
									<?php } ?>

								</ul>
							</div><!--/.nav-collapse -->
						</div>
					</nav>
					<div class='alert'>
						<div class='container'>		
							<p><spam class='glyphicon glyphicon-alert'></spam> Credit card registration is disabled for scheduled maintenance. We expect this to take several hours. No new registrations may be taken at this time. All other site functions will remain online.</p>
						</div>
					</div>
					<div class='body'>

	<?php }


	//The end of documents
	function tail() { ?>
					</div>
					<div class="credits">
						<p class='text-center'>&copy; Experimental College | 
						<!-- <a href='/asuwxpcl/about.php'>About</a> |  -->
						<a href='/asuwxpcl/feedback.php'>Feedback</a>
						</p>
					</div>
					<script>
					  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

					  ga('create', 'UA-10905115-1', 'auto');
					  ga('send', 'pageview');

					</script>
				</div> <!-- close full-wrapper -->
			</body>
		</html>
	<?php }
?>