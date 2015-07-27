<?php
	require("common.php");

	//redirect anyone who got here by accident without a course ID
	if (!isset($_GET['id'])) {
		header('Location: index.php');
		die();
	} 

	$courseID = $_GET['id'];
	head("<link href='assets/css/course.css' type='text/css' rel='stylesheet'>");
	$db = new DB();
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
			sec.location,
			sec.section,
			sec.status,
			users.first_name,
			users.last_name
			FROM " . $DATABASE . ".courses courses
			JOIN " . $DATABASE . ".sections sec ON sec.course_id = courses.id
			JOIN " . $DATABASE . ".users users ON courses.instructor_id = users.id
			WHERE courses.id = " . $db -> quote($courseID));
	?>

	<section class="title" style="background: url('assets/img/classes/<?= $courseID ?>.jpg'), black;">
		<div class="container">
			<!--<p><?= print_r($sections) ?></p>-->
			<h1><?= $sections[0]["name"]?></h1>
			<p><?= $sections[0]["first_name"] . " " . $sections[0]["last_name"] ?></p>
		</div>
	</section>
	<section class="description">
		<div class="container">
			<h2>About the Class</h2>
			<p><?= $sections[0]["description"] ?></p>
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
						<p>Time: <?= $sections[$i]["times"] ?></p>
						<p>Dates: <?= $sections[$i]["days"] ?></p>
						<p>Size: <?= $sections[$i]["size"] ?></p>
						<p>General Fee: $<?= $sections[$i]["fee_gen"] ?></p>
						<p>UW Fee: $<?= $sections[$i]["fee_uw"] ?></p>
						<p>Location: <?= $sections[$i]["location"] ?></p>
						
						<?php if($sections[$i]["status"] === "1") { ?>
							<form action="signupsubmit.php">
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
	if ($sections[0]["instructor_id"] == $_SESSION["id"] || $_SESSION["permissions"] == 3 ) { ?>
		<section class="administration">
			<div class="container">
				<h1>Admin Panel</h1>
				<p>This area shows all students signed up for your course, by section. It is invisible to others.</p>
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
?>