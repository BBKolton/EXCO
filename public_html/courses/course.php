<?php
	require("../common.php");

	//redirect anyone who got here by accident without a course ID
	if (empty($_GET['id'])) {
		header('Location: courses.php');
		die();
	} 


	session_start();
	$db = new DB();

	//if We're changing data or sending emails
	if (!empty($_POST) && verifyAdminOrClassInstructor($_GET["id"])) {
		//update the database with the  course's new description
		if (!empty($_POST["editDesc"])) {
			$db -> query("UPDATE " . $DATABASE . ".courses 
			              SET description = " . $db->quote($_POST["editDesc"]) . 
			            " WHERE id = " . $db->quote($_GET["id"]));
		} 

		//send an email to the course or section
		if (!empty($_POST["type"])) {
			if (empty($_POST["subject"]) || empty($_POST["text"])) {
				echo "You forgot to specify a subject or message";
			} else {
				$students;
				//send an email to the class
				if ($_POST["type"] == 0) {
					$students = $db -> select("SELECT DISTINCT users.email FROM " . $DATABASE . ".users 
					                           JOIN " . $DATABASE . ".registrations reg ON reg.user_id = users.id
					                           JOIN " . $DATABASE . ".courses co ON reg.course_id = co.id
					                           WHERE co.id = " . $db->quote($_GET["id"]));	
				} else { //send an email to a section
					$students = $db -> select("SELECT DISTINCT users.email FROM " . $DATABASE . ".users 
					                           JOIN " . $DATABASE . ".registrations reg ON reg.user_id = users.id
					                           JOIN " . $DATABASE . ".courses co ON reg.course_id = co.id
					                           JOIN " . $DATABASE . ".sections sec ON reg.course_section
					                           WHERE co.id = " . $db->quote($_GET["id"]) . " AND 
					                           sec.id = " . $db->quote($_POST["type"]));	
				}
				emailUsers($students, $_POST["subject"], $_POST["text"]);	
			}
		} 
		die();
	}


	$courseID = $_GET['id'];
	head("<link href='/asuwecwb/.assets/css/course.css' type='text/css' rel='stylesheet'>" . 
	     "<script type='text/javascript' src='/asuwecwb/.assets/js/course.js'></script>");

	if (isset($_GET["email"]) && !empty($_GET["section"]) &&
			verifyAdminOrClassInstructor($_GET["id"])) {
		//sendSectionEmail
	}

	$sections = $db -> select("SELECT courses.name,
			courses.description,
			courses.num_sections,
			courses.instructor_id,
			courses.id,
			sec.times,
			sec.days,
			sec.size,
			sec.fee_gen,
			sec.fee_uw,
			sec.location_gen,
			sec.section,
			sec.status,
			users.first_name,
			users.last_name
			FROM " . $DATABASE . ".courses courses
			JOIN " . $DATABASE . ".sections sec ON sec.course_id = courses.id
			JOIN " . $DATABASE . ".users users ON courses.instructor_id = users.id
			WHERE courses.id = " . $db -> quote($courseID));

	if (empty($sections[0])) {
		error("Specified Class Not Found", "The course you're looking for was not found");
	}

	?>

	<section class="title" style="background-image: url('/asuwecwb/.assets/img/classes/<?= $courseID ?>.jpg');">
		<div class="container">
			<!--<p><?= print_r($sections) ?></p>-->
			<h1><?= $sections[0]["name"]?></h1>
			<p><?= $sections[0]["first_name"] . " " . $sections[0]["last_name"] ?></p>
		</div>
	</section>
	<section class="description">
		<div class="container">
			<h2>About the Class</h2>
			<p id="description"><?= $sections[0]["description"] ?></p>
		</div>
	</section>
	<section class="times">
		<div class="container">
			<div class="row col-md-10">

				<?php
					for ($i = 0; $i < $sections[0]['num_sections']; $i++) {
				?>
				<div class="section col-md-4 col-sm-6 col-xs-12 ">
					<div class="wrapper status-<?= $sections[$i]["status"] ?>">
						<h3>Section <?= $i + 1 ?></h3>
						<ul class="no-style">
							<li>Time: <?= $sections[$i]["times"] ?></li>
							<li>Dates: <?= $sections[$i]["days"] ?></li>
							<li>Size: <?= $sections[$i]["size"] ?></li>
							<li>General Fee: $<?= $sections[$i]["fee_gen"] ?></li>
							<li>UW Fee: $<?= $sections[$i]["fee_uw"] ?></li>
							<li>Location: <?= $sections[$i]["location_gen"] ?></li>
						</ul>
						
						<?php if($sections[$i]["status"] === "1") { ?>
							<form action="/asuwecwb/courses/signupsubmit.php">
								<input type="hidden" name="id" value="<?= $courseID ?>" />
								<input type="hidden" name="section" value="<?= $sections[$i]['section'] ?>" />
								<button type="submit">Sign Up</button>
							</form>
						<?php } ?>

					</div>
				</div>
				<?php 
					}
				?>
			</div>

			<div class="col-md-2 col-xs-12">
				<ul>
					<li>something</li>
					<li>Soemthing else</li>
					<li>Maybe some cool info about the teacher</li>
				</ul>

			</div>
		</div>
	</section>


	<?php 
	//The administration and user view section. Accessible only by the instructor of the class
	//and any admins who are singed in
	if (verifyAdminOrClassInstructor($_GET["id"])) { ?>
		<section class="administration">
			<div class="container">
				<h1>Admin Panel</h1>
				<p>Edit information, send emails, and view registrants for your course and section</p>
				<div><a id="editDesc">Edit Description</a></div>
				<div class="all" id="true"><a id="sendAll">Send an email to all students</a></div> 


				<?php for ($i = 0; $i < $sections[0]["num_sections"]; $i++) { ?>
					<h2>Section <?= $i + 1 ?></h2>
					<?php
					$section = $db -> select("SELECT users.first_name,
					                                 users.last_name
					                          FROM " . $DATABASE . ".users users
					                          JOIN " . $DATABASE . ".registrations reg
					                          ON reg.user_id = users.id 
					                          WHERE reg.course_id = " . $db->quote($sections[0]["id"]) . "
					                          AND reg.course_section = " . $db->quote($i + 1));
					if (empty($section)) { ?>
						<p>There is no one yet signed up for this section</p>
					<?php } else { ?>

					<div class="sec" id="<?= $i + 1 ?>"><a class="sendSecs">Email this section</a></div>
					<table>
						<tr>
							<th></th>
							<th>First Name</th>
							<th>Last Name</th>
						</tr>
						<tr>
							<?php for ($j = 0; $j < count($section); $j++) { ?>
								<tr>
									<td></td>
									<td><?= $section[$j]["first_name"] ?></td>
									<td><?= $section[$j]["last_name"] ?></td>
								</tr>
							<?php } ?>
						</tr>
					</table>
				<?php }
				} ?>
			</div>
		</section>


	<?php }

	tail();

	//email all users in the users list as BCC's the subject and message
	function emailUsers($users, $subject, $text) {
		if (empty($users)) {
			echo "Failed to find any students!";
			die();
		}
		require("modules/PHPMailer/PHPMailerAutoload.php");

		$mail = new PHPMailer(true);
		$mail->AddAddress($_SESSION["email"]);
		foreach ($users as $user) {
			$mail->AddBCC($user["email"]);
		}
		$mail->SetFrom($_SESSION["email"]);
		$mail->Subject = $subject;
		$mail->AddReplyTo($_SESSION["email"], $_SESSION["name"]);
		$mail->SetFrom("noreply@exco.org", "ASUW Experimental College");
		$mail->Body = $text;
		try {
			$mail->Send();
			echo "Success!";
		} catch (Exception $e) {
			echo "Error Sending Email";
			file_put_contents("EMAILERROR.txt", $mail->ErrorInfo);
		}
	}
?>