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
			//Use this for and ONLY FOR debugging problems. It's awful and will shit out FUCKING FATAL ERRORS
			//FOR THE MOST INANE BULLSHIT. It's the equivalent of installing a water monitor on your faucet 
			//to check for leaks, but everytime you use your sink for more than 3 seconds, it calls 911, reports 
			//a fire, stops the water, and explodes your house... for your own safety, of course. Fuck PHP

			//mysqli_report(MYSQLI_REPORT_ALL);

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

		//get teh alst error from database, returns string
		public function error() {
			$connection = $this -> connect();
			return $connection -> error;
		}

		//SUPER IMPORTANT
		//quotesand escapes values for use in a database query. returns a sanitized string
		public function quote($value) {
			$connection = $this->connect();
			return "'" . $connection->real_escape_string($value) . "'";
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

	//An easy way to throw errors with logins. 
	function error($type = "Unknown", $message = "Please contact an administrator") {
		head(); ?>
		
		<section class="error">
			<div class="container">
				<h1><?= $type ?></h1>
				<p><?= $message ?></p>
				<img style="" src="/asuwecwb/assets/img/errorzebra.png" alt="error zebra" />
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
	function head($extra = " ", $ckEditor = 0, $datePicker = 0) { 
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
				<link href="LINKHEREBOZO" type="image/SOMETHING" rel="shortcut icon" />
				<link href="/asuwecwb/assets/css/bootstrap.css" type="text/css" rel="stylesheet" />
				<link href="/asuwecwb/assets/css/bootstrap-theme.css" type="text/css" rel="stylesheet" />
				<link href="/asuwecwb/assets/css/EXCO.css" type="text/css" rel="stylesheet" />
				<script type="text/javascript" src="/asuwecwb/assets/js/jquery-1.11.3.min.js"></script>
				<script type="text/javascript" src="/asuwecwb/assets/js/bootstrap.min.js"></script>
				<?= $extra ?>
				
				<?php //these provide the WYSIWYG editor and datepicker for things that need them
					if ($ckEditor) { ?>
						<link rel="stylesheet" href="/asuwecwb/assets/plugins/jquery-ui/jquery-ui.min.css">
						<script src="/asuwecwb/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
						<script src="/asuwecwb/assets/plugins/ckeditor/ckeditor.js"></script>
						<script src="/asuwecwb/assets/plugins/ckeditor/ckeditorReplace.js"></script>
					<?php } if ($datePicker) { 
						//labelled as js cause thats all the file has 
						include($branch . 'assets/js/rif.php'); ?>
						<link rel="stylesheet" href="/asuwecwb/assets/plugins/jquery-ui/jquery-ui.min.css">
						<link rel="stylesheet" href="/asuwecwb/assets/css/datepicker.css">
						<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script><!--multidatespicker dependency -->
						<script src="/asuwecwb/assets/plugins/multidatepicker/multidatespicker.js"></script>
						<?php datePickerConfig($MINDATE, $MAXDATE); //this function lives in the above include branch
					 } 
				?>

			</head>
			<!--BEGIN NAVBAR-->
			<body>
				<nav class="navbar navbar-inverse navbar-fixed-top">
					<div class="container">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="/asuwecwb/index.php"><img height="150%" src="/asuwecwb/assets/img/logo.png" /></a>
						</div>
						<div id="navbar" class="navbar-collapse collapse">
							<ul class="nav navbar-nav">
								<li><a href="/asuwecwb/courses.php">Courses</a></li>
								<li><a href="/asuwecwb/assets/docs/Catalog.pdf">Catalog</a></li>
								<li><a href="!!">Teach</a></li>
								<li><a href="!!">Help</a></li>
								<li><a href="/asuwecwb/about.php">About</a></li>
								<li><a href="/asuwecwb/feedback.php">Feedback</a></li>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								
								<?php if(isset($_SESSION["name"])) { ?>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $_SESSION["name"] ?><span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="/asuwecwb/mycourses.php">My Courses</a></li>
											<li><a href="/asuwecwb/cart.php">Cart</a></li>
											<?php if($_SESSION["permissions"] > 1) { ?>
												<li><a href="/asuwecwb/instructors/rif.php">Rifs</a></li>
											<?php } ?>
											<li role="separator" class="divider"></li>
											<li class="dropdown-header">Account</li>
											<li><a href="#">Preferences</a></li>
											<li><a href="/asuwecwb/logout.php">Logout</a></li>
										</ul>
									</li>
								<?php } else { ?>
									<li><a id="login-link" href="/asuwecwb/login.php">Login / Register</a></li>
								<?php } ?>

							</ul>
						</div><!--/.nav-collapse -->
					</div>
				</nav>

	<?php }


	//The end of documents
	function tail() { ?>
			<div class="credits">
			
			</div>
			</body>
		</html>
	<?php }
?>