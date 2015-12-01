<?php
	//view all the classes a user is part of. 
	//also shows any classes a user is instructing.

	require("../common.php");
	
	head('<script type="text/javascript" src="/asuwecwb/.assets/js/mycourses.js"></script>' . 
		'<link href="/asuwecwb/.assets/css/mycourses.css" type="text/css" rel="stylesheet" />');

	if (empty($_SESSION["name"])) {
		header("Location: /asuwecwb/index.php");
	} ?>

	<section class="content">
		<div class="container">

		<?php 
			$db = new DB();
			

			//for instructors, posts all the classes they are teaching.
			if ($_SESSION["permissions"] == 2) {
				$taught = $db -> select("SELECT courses.name,
				                                courses.id
				                        FROM " . $DATABASE . ".courses
				                        WHERE courses.instructor_id = " . $db ->quote($_SESSION["id"]));
				if (empty($taught)) { ?>
					<h1>No Taught Classes</h1>
					<p>You are not yet teaching with the Experimental College.</p>
				<?php } else { ?>
					<h1>Your Classes</h1>
					<p>These are the classes you are teaching. You may visit the links for more information.</p>
					<table>
						<?php for ($i = 0; $i < count($taught); $i++) { ?>
							<tr>
								<td><a href="/asuwecwb/courses/course.php?id=<?= $taught[$i]['id'] ?>"><?= $taught[$i]['name'] ?></a></td>
							</tr>
						<?php } ?>
					</table>
				<?php }
			}




			//display classes for users. If a user is an instructor, they will not see an error for
			//no found classes. However, if they're enrolled in a class, they will see it.
			$courses = $db -> select("SELECT courses.name,
			                                 courses.id,
			                                 courses.email,
			                                 sec.fee_gen,
			                                 sec.fee_uw,
			                                 sec.times,
			                                 sec.days,
			                                 sec.location_spec,
			                                 sec.section
			                          FROM " . $DATABASE . ".courses
			                          JOIN " . $DATABASE . ".sections sec
			                          ON courses.id = sec.course_id
			                          JOIN " . $DATABASE . ".registrations reg
			                          ON reg.course_id = sec.course_id
			                          JOIN " . $DATABASE . ".users 
			                          ON users.id = reg.user_id
			                          WHERE users.id = " . $db->quote($_SESSION["id"]) . 
			                         "AND sec.section = reg.course_section"); 

			if (empty($courses) && $_SESSION["permissions"] != 2) { ?>

				<h1>Nothing Here Yet!</h1>
				<p>You have not yet signed up for a class with the Experimetal College. <a href="/asuwecwb/courses/courses.php">Find something awesome!</a></p>
				
			<?php } else if (!empty($courses)) { ?>
				
				<h1>Your Enrollments</h1>
				<table class='table table-striped'>
					<tr>
						<th>Class</th>
						<th>Section</th>
						<th>Days</th>
						<th>Times</th>
						<th>Location</th>
						<th>Fee</th>
						<th>Registration Email</th>
					</tr>
					<?php 
					for ($i = 0; $i < count($courses); $i++) { 
						$fee = $courses[$i]["fee_gen"];
						if ($_SESSION["type"] === "student") {
							$fee = $courses[$i]["fee_uw"];
						} ?>
						<tr>
							<td><a href="/asuwecwb/courses/course.php?id=<?= $courses[$i]['id'] ?>"><?= $courses[$i]["name"] ?></a></td>
							<td><?= $courses[$i]["section"] ?></td>
							<td><?= $courses[$i]["days"] ?></td>
							<td><?= $courses[$i]["times"] ?></td>
							<td><?= $courses[$i]["location_spec"] ?></td>
							<td>$<?= $fee ?></td>
							<td><a class="show">Show Email</a><span class="email"><?= $courses[$i]["email"] ?></span></td>
						</tr>
					<?php } ?>
					
				</table>

			<?php } ?>

		</div>
	</section>

	<?php tail();

?>