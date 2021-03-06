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
					                           WHERE reg.status = 1 AND
					                           co.id = " . $db->quote($_GET["id"]));	
				} else { //send an email to a section
					$students = $db -> select("SELECT DISTINCT users.email FROM " . $DATABASE . ".users 
					                           JOIN " . $DATABASE . ".registrations reg ON reg.user_id = users.id
					                           JOIN " . $DATABASE . ".courses co ON reg.course_id = co.id
					                           JOIN " . $DATABASE . ".sections sec ON reg.course_section
					                           WHERE co.id = " . $db->quote($_GET["id"]) . " AND 
					                           reg.status = 1 AND
					                           sec.id = " . $db->quote($_POST["type"]));	
				}
				emailUsers($students, $_POST["subject"], $_POST["text"]);	
			}
		} 

		//disable course
		if (!empty($_POST["course-toggle"])) {
			$active = $db->select("SELECT courses.status
			                       FROM " . $DATABASE . ".courses
			                       WHERE id = " . $db->quote($_GET["id"]))[0]["status"];
			if ($active === "1") {
				$active = 2;
			} else {
				$active = 1;
			}

			$db -> query("UPDATE " . $DATABASE . ".courses
			              SET status = " . $db->quote($active) . " 
			              WHERE id = " . $db->quote($_GET["id"]));

			header("Refresh:0");
		}


		die();
	}


	$courseID = $_GET['id'];
	head("<link href='/asuwxpcl/.assets/css/course.css' type='text/css' rel='stylesheet'>" . 
	     "<script type='text/javascript' src='/asuwxpcl/.assets/js/course.js'></script>", 0, 0, 1);

	$sections = $db -> select("SELECT courses.name,
			courses.description,
			courses.instructor_id,
			courses.id,
			courses.status as course_status,
			sec.times,
			sec.days,
			sec.size,
			sec.fee_gen,
			sec.fee_uw,
			sec.location_gen,
			sec.location_spec,
			sec.section,
			sec.status,
			sec.start_day,
			users.first_name,
			users.last_name,
			users.email,
			users.phone,
			ua.about
			FROM " . $DATABASE . ".courses courses
			JOIN " . $DATABASE . ".sections sec ON sec.course_id = courses.id
			LEFT OUTER JOIN " . $DATABASE . ".users users ON courses.instructor_id = users.id
			LEFT OUTER JOIN " . $DATABASE . ".users_additional ua ON users.id = ua.user_id
			WHERE courses.id = " . $db -> quote($courseID)); //rdsadsa


	if (empty($sections[0])) {
		error("Specified Class Not Found", "The course you're looking for was not found");
	}


	?>
	<script>
		// $(document).ready(function() {
		// 	$('.dynatable').dynatable();
		// });
	</script>

	<section class="title" >
		<div class="jumbotron" style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('/asuwxpcl/.assets/img/classes/<?= $courseID ?>.jpg');">
			<div class="container">
				<h1><?= htmlspecialchars($sections[0]["name"]) ?></h1>
				<p><?= htmlspecialchars($sections[0]["first_name"]) . " " . htmlspecialchars($sections[0]["last_name"]) ?></p>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container">

			<?php if ($sections[0]["course_status"] == 2) { ?>
				<div id="cancelled-course">
					<h1>Course Cancelled</h1>
					<p>This course has been cancelled. No sections will be held. 
					If you were enrolled, you may request a refund from the college.
					Please <a href="/asuwxpcl/about.php">contact us</a> for more 
					information. </p>
				</div>
			<?php } ?>

			<div class="row">
				<div class="col-md-9 col-xs-12">
					<h2>About the Class</h2>
					<p id="description"><?= $sections[0]["description"] ?></p>

					<?php
						for ($i = 0; $i < count($sections); $i++) {
							if ($sections[0]['course_status'] != 1) $sections[$i]['status'] = 2;
							$size = $db->select("SELECT count(*) AS count FROM registrations
							                     WHERE course_id = " . $db->quote($_GET['id']) . " 
							                     AND status = 1
							                     AND course_section = " . ($i + 1))[0]['count'];
							//$size = "SELECT count(*) FROM registrations WHERE course_id = " . $db->quote($_GET['id']) . " AND course_section = " . $i;
							if ($size >= $sections[$i]['size']) $sections[$i]['status'] = 4; 
							if ($sections[$i]['start_day'] < date("Y-m-d")) $sections[$i]['status'] = 3;
					?>
					<!-- <?= var_dump($sections[$i]['status']); ?> -->
					<div class="section col-md-4 col-sm-6 col-xs-12 ">
						<div class="wrapper status-<?= $sections[$i]["status"] ?>">
							<h3>Section <?= $i + 1 ?></h3>
							<ul class="no-style">
								<li>Time: <?= htmlspecialchars($sections[$i]["times"]) ?></li>
								<li>Dates: <?= htmlspecialchars($sections[$i]["days"]) ?></li>
								<li>Size: <?= ($_SESSION['permissions'] > 2 ? $size . '/' : '')?><?= htmlspecialchars($sections[$i]["size"]) ?></li>
								<li>General Fee: $<?= htmlspecialchars($sections[$i]["fee_gen"]) ?></li>
								<li>UW Fee: $<?= htmlspecialchars($sections[$i]["fee_uw"]) ?></li>
								<li>Location: <?= htmlspecialchars($sections[$i]["location_gen"]) ?></li>
							</ul>
							
							<?php if($sections[$i]["status"] === "1") { ?>
								<form action="/asuwxpcl/courses/signupsubmit.php">
									<input type="hidden" name="id" value="<?= $courseID ?>" />
									<input type="hidden" name="section" value="<?= $sections[$i]['section'] ?>" />
									<button type="submit" class='btn btn-success'>Sign Up</button>
								</form>
							<?php } else if ($sections[$i]['status'] == 2) { ?>
								<h3>Cancelled</h3>
							<?php } else if ($sections[$i]['status'] == 3) { ?>
								<h3>Started</h3>
							<?php } else { ?> 
								<h3>Full</h3>
							<?php }
							if ($_SESSION['permissions'] > 2) { ?>
								<form action='/asuwxpcl/courses/coursesubmit.php' method='get'>
									<input type='hidden' name='id' value='<?= $courseID ?>' />
									<input type='hidden' name='outsideSignup' value='true' />
									<input type='hidden' name='section' value='<?= $sections[$i]['section'] ?>' />
									<button type='submit' class='btn btn-info other-register'>Register Student</button>
								</form>
							<?php } ?>

						</div>
					</div>
					<?php 
						}
					?>
				</div>


				<div class="col-md-3 col-xs-12">
					<h3>About the Instructor</h3>
					<p>Email: <?= $sections[0]['email'] ?></p>
					<p><?= $sections[0]['about'] ?></p>
					
					<?php if ($_SESSION['permissions'] > 2) { ?>
						<p>Phone: <?= $sections[0]['phone'] ?></p>
					<?php } ?>
				</div>
					

			</div>
		</div>
	</section>


	<?php 
	//The administration and user view section. Accessible only by the instructor of the class
	//and any admins who are singed in
	if (verifyAdminOrClassInstructor($_GET["id"])) { ?>
		<section class="content">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-xs-12">
						<h2>Instructor Panel</h2>
						<p>Edit information, send emails, and view registrants for your course and section</p>
						<div><a id="editDesc">Edit Description</a></div>
						<div class="all" id="true"><a id="sendAll">Send an email to all students</a></div> 

						<?php for ($i = 0; $i < count($sections); $i++) { ?>
							<h2>Section <?= $i + 1 ?></h2>
							<?php
							$section;
							if ($_SESSION['permissions'] > 2) {
								$section = $db -> select("SELECT users.first_name,
								                                 users.last_name,
								                                 users.netid,
								                                 users.phone,
								                                 users.email,
								                                 reg.status,
								                                 reg.id
								                          FROM " . $DATABASE . ".users users
								                          JOIN " . $DATABASE . ".registrations reg
								                          ON reg.user_id = users.id 
								                          WHERE reg.course_id = " . $db->quote($sections[0]["id"]) . "
								                          AND reg.course_section = " . $db->quote($i + 1));
							} else {
								$section = $db -> select("SELECT users.first_name,
								                                 users.last_name,
								                                 users.netid,
								                                 users.phone,
								                                 users.email,
								                                 reg.status,
								                                 reg.id
								                          FROM " . $DATABASE . ".users users
								                          JOIN " . $DATABASE . ".registrations reg
								                          ON reg.user_id = users.id 
								                          WHERE reg.course_id = " . $db->quote($sections[0]["id"]) . "
								                          AND reg.status = 1
								                          AND reg.course_section = " . $db->quote($i + 1));
							}
							if (empty($section)) { ?>
								<p>There is no one yet signed up for this section</p>
							<?php } else { ?>

							<div class="sec" id="<?= $i + 1 ?>"><a class="sendSecs">Email this section</a></div>
							<table class='dynatable table table-striped'>
								<thead>
									<tr>
										<?php if ($_SESSION['permissions'] > 2) { ?> 
											<th>Cancel Registration</th> 
											<th>Move Registration</th>
										<?php } ?>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Type</th>
									</tr>
								</thead>
								<tbody>
									<?php for ($j = 0; $j < count($section); $j++) { ?>
										<tr>
											<?php if ($_SESSION['permissions'] > 2) { ?> 
												<?php if ($section[$j]['status'] == 1) { ?>
													<td><a href='coursesubmit.php?cancel=true&id=<?= $section[$j]['id'] ?>&course=<?= $_GET['id']?>'>Cancel</a></td>
												<?php } else { ?>
													<td><a href='coursesubmit.php?uncancel=true&id=<?= $section[$j]['id'] ?>&course=<?= $_GET['id']?>'>Uncancel</a></td>
												<?php } ?>

												<td><a href='coursesubmit.php?id=<?= $section[$j]['id'] ?>&moveselect=true'>Move</a></td>
												
											<?php } ?>
											<td><?= htmlspecialchars($section[$j]["first_name"]) ?></td>
											<td><?= htmlspecialchars($section[$j]["last_name"]) ?></td>
											<td><?= htmlspecialchars($section[$j]["email"]) ?></td>
											<td><?= htmlspecialchars($section[$j]["phone"]) ?></td>
											<td>
												<?php if ($section[$j]["netid"]) { ?>
													Student
												<?php } else { ?>
													General
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						<?php }
						if ($_SESSION['permissions'] > 2) { ?>
							<form action='coursesubmit.php?' method="get">
								<p>Specific Location: <?= $sections[$i]["location_spec"] ?></p>
								<div class='col-xs-4'>
									<p>Change Location</p>
								</div>
								<div class='col-xs-8'>
									<input type='hidden' name='id' value="<?= $_GET["id"] ?>" />
									<input type='hidden' name='changeLocation' value='true' />
									<input type='hidden' name='section' value='<?= $sections[$i]['section']?>' />
									<input type='text' name='loc_spec' class='form-control' />
									<button type='submit' class='btn btn-success'>Update Location</button>
								</div>
							</form>
						<?php }
						} ?>
					</div>
					<div class="col-md-4 col-xs-12">
						<h2>Course Controls</h2>
						<form action="course.php?id=<?= $_GET["id"] ?>" method="post">
							<?php if ($sections[0]["course_status"] == 1) { ?>
								<button type="submit" name="course-toggle" value="toggle" class='btn btn-warning'>Cancel Course</button>
							<?php } else { ?>
								<button type="submit" name="course-toggle" value="toggle" class='btn btn-info'>Reinstate Course</button>
							<?php } ?>
						</form>
					</div>
				</div>
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
		require("../modules/PHPMailer/PHPMailerAutoload.php");

		$mail = new PHPMailer(true);
		$mail->AddAddress(htmlspecialchars($_SESSION["email"]));
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