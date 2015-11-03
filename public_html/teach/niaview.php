<?php
session_start();
require('../common.php');

if ($_SESSION['permissions'] < 3) {
	error('Unauthorized Access', 'You are not an admin');
}

$db = new DB();

$nia = $db -> select('SELECT *
                      FROM nias
                      JOIN users ON nias.user_id = users.id
                      JOIN users_additional ua ON users.id = ua.user_id
                      WHERE nias.id = '. $db->quote($_GET['id']))[0];

head('<link href="/asuwecwb/.assets/css/nia.css" rel="stylesheet" />');
?>

<section class='content'>
	<div class='container'>
		<h2>New Instructor Application</h2>
		<div class='row'>
			<div class='col-xs-12 col-md-6 col-lg-4'>
				<h3>Personal Information</h3>
					<p><b>Name</b><br />
					<?= $nia['first_name'] . ' ' . $nia['last_name'] ?></p>
					
					<p><b>Phone</b><br />
					<?= $nia['phone'] ?></p>
					
					<p><b>Email</b><br />
					<?= $nia['email'] ?></p>
					
					<p><b>Address</b><br />
					<?= $nia['address'] ?></p>
					
					<p><b>City</b><br />
					<?= $nia['city'] ?></p>
					
					<p><b>State</b><br />
					<?= $nia['state'] ?></p>
					
					<p><b>Zip</b><br />
					<?= $nia['zip'] ?></p>
					
			</div>

			<div class='col-xs-12 col-md-6 col-lg-4'>
				<h3>Course Information</h3>
					<p><b>Proposed course name</b><br />
					<?= $nia['course_name'] ?></p>
				
					<p><b>Summarize your course in less than 75 words</b><br />
					<?= $nia['course_summary'] ?></p>
				
					<p><b>When would you start?</b><br />
					<?= $nia['course_start'] ?></p>
				
					<p><b>Estimated number of sections</b><br />
					<?= $nia['course_sections'] ?></p>
				
					<p><b>Estimated number of hours per class day</b><br />
					<?= $nia['course_hours'] ?></p>
				
					<p><b>Estimated number of days per section</b><br />
					<?= $nia['course_days'] ?></p>
				
					<p><b>Max number of students per section</b><br />
					<?= $nia['course_max'] ?></p>
				
			</div>


			<div class='col-cs-12 col-md-12 col-lg-4' id='questions'>
				<div class='col-xs-12'><h3>Course Questions</h3></div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>How will you attract students from UW with this course?</b><br />
							<?= $nia['question_attract'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>Why do you want to teach this course?</b><br />
							<?= $nia['question_why'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>What skills/knowledge do you hope students will gain from this course?</b><br />
							<?= $nia['question_skills'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>What supplies or equipment will students need to provide? What will you provide?</b><br />
							<?= $nia['question_supplies'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>What excersises will students participate in?</b><br />
							<?= $nia['question_exercises'] ?></p>
				</div>
				<div class='col-xs-12 col-md-6 col-lg-12'>
							<p><b>Please describe your background in reference to the course, teaching, and training</b><br />
							<?= $nia['quetion_background'] ?></p>
				</div>
			</div>
		</div>

		<h3>Resume and Course Outline</h3>
		<?php $dir = 'docs/' . $nia['user_id'] . '/'; 
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