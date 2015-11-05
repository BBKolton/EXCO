<?php
session_start();
require('../common.php');

if ($_SESSION['permissions'] < 3) {
	error('Unauthorized Access', 'You are not an admin');
}

$db = new DB();

$application = $db -> select('SELECT *
                      FROM applications
                      JOIN users ON applications.user_id = users.id
                      JOIN users_additional ua ON users.id = ua.user_id
                      WHERE applications.id = '. $db->quote($_GET['id']))[0];

head('<link href="/asuwecwb/.assets/css/application.css" rel="stylesheet" />');
?>

<section class='content'>
	<div class='container'>
		<h2>New Instructor Application</h2>
		<div class='row'>
			<div class='col-xs-12 col-md-6 col-lg-4'>
				<h3>Personal Information</h3>
					<p><b>Name</b><br />
					<?= $application['first_name'] . ' ' . $application['last_name'] ?></p>
					
					<p><b>Phone</b><br />
					<?= $application['phone'] ?></p>
					
					<p><b>Email</b><br />
					<?= $application['email'] ?></p>
					
					<p><b>Address</b><br />
					<?= $application['address'] ?></p>
					
					<p><b>City</b><br />
					<?= $application['city'] ?></p>
					
					<p><b>State</b><br />
					<?= $application['state'] ?></p>
					
					<p><b>Zip</b><br />
					<?= $application['zip'] ?></p>
					
			</div>

			<div class='col-xs-12 col-md-6 col-lg-4'>
				<h3>Course Information</h3>
					<p><b>Proposed course name</b><br />
					<?= $application['course_name'] ?></p>
				
					<p><b>Summarize your course in less than 75 words</b><br />
					<?= $application['course_summary'] ?></p>
				
					<p><b>When would you start?</b><br />
					<?= $application['course_start'] ?></p>
				
					<p><b>Estimated number of sections</b><br />
					<?= $application['course_sections'] ?></p>
				
					<p><b>Estimated number of hours per class day</b><br />
					<?= $application['course_hours'] ?></p>
				
					<p><b>Estimated number of days per section</b><br />
					<?= $application['course_days'] ?></p>
				
					<p><b>Max number of students per section</b><br />
					<?= $application['course_max'] ?></p>
				
			</div>


			<div class='col-cs-12 col-md-12 col-lg-4' id='questions'>
				<div class='col-xs-12'><h3>Course Questions</h3></div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>How will you attract students from UW with this course?</b><br />
							<?= $application['question_attract'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>Why do you want to teach this course?</b><br />
							<?= $application['question_why'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>What skills/knowledge do you hope students will gain from this course?</b><br />
							<?= $application['question_skills'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>What supplies or equipment will students need to provide? What will you provide?</b><br />
							<?= $application['question_supplies'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>What excersises will students participate in?</b><br />
							<?= $application['question_exercises'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>Please describe your background in reference to the course, teaching, and training</b><br />
							<?= $application['quetion_background'] ?></p>
				</div>
			</div>
		</div>

		<h3>Resume and Course Outline</h3>
		<?php $dir = 'docs/' . $application['user_id'] . '/'; 
		$files = scandir($dir);
		$outline = $dir . $files[2];
		$resume = $dir . $files[3]; ?>
		<p><a href='<?= $resume ?>'>Resume</a></p>
		<p><a href='<?= $outline ?>'>Outline</a></p>
	</div>
</section>


<?php
tail();
?>