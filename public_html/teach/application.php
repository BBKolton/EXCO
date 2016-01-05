<?php 
require('../common.php');
session_start();

if (!isset($_SESSION['id'])) {
	header('Location: /asuwxpcl/users/login.php');
	die();
}


$db = new DB();

$nia = $_SESSION['permissions'] < 2;

if ($nia) {
	$currentNia = $db->select('SELECT * FROM applications WHERE user_id = ' . $db->quote($_SESSION['id']))[0];
	if (!empty($currentNia)) {
		header('Location: applicationview.php?id=' . $currentNia['id']);
	}
}

$oldData;

$oldData = $db -> select('SELECT * FROM users_additional
                          WHERE user_id = ' . $db->quote($_SESSION['id']))[0];

head('<script src="/asuwxpcl/.assets/js/application.js"></script>' .
     '<link href="/asuwxpcl/.assets/css/application.css" rel="stylesheet" />');
?>

<style>
	h3 {
		border-top: 4px solid <?= ($nia ? '#c5395a' : '#297383') ?>;
	}
</style>

<section class='content'>
	<div class='container'>
		<h2><?= $nia ? 'New Instructor Application' : 'New Course Proposal' ?></h2>
		<?php if ($nia) { ?>
			<p>Thank you for your interest in teaching with the Experimental College. We're always looking for new, talented instructors to teach engaging, unique, and quality classes. Please fill out the form below to apply to teach a course with the college. Please estimate where you cannot give an exact answer; details can be dealt with later. You may only submit one New Instructor Application. If you have more than one course you wish to propose, use your strongest one for this form. If you're accepted as an instructor with EXCO, we can take a look at your other courses then</p>
		<?php } else { ?>
			<p>Thank you for your interest with teaching a new course with EXCO! Please fill out the form below to make your application of a new course. Please estimate values where they can't be specifically given at this time; details can be dealt with later</p>
		<?php } ?>
		<p>No fields are expressely required besides your file uploads, but we hope you fill out the complete form</p>
		<form method='post' action='applicationsubmit.php' enctype='multipart/form-data'>
			<div class='row'>
				<div class='col-xs-12 col-md-6 col-lg-4'>
					<h3>Personal Information</h3>
					<div class='form-group'>
					<p class='subtitle'>Disabled fields are tied to your account. You may change them <a href='/asuwxpcl/users/preferences.php'>here</a></p>
						<label>Name
							<input type='text' name='personal_name' class='form-control' value='<?= $_SESSION["name"] ?>' disabled/>
						</label>
					</div>
					<div class='form-group'>
						<label>Phone
							<input type='tel' name='personal_phone' class='form-control' value='<?= $_SESSION["phone"] ?>' disabled/>
						</label>
					</div>
					<div class='form-group'>
						<label>Email
							<input type='email' name='personal_zip' class='form-control' value='<?= $_SESSION["email"] ?>' disabled/>
						</label>
					</div>
					<div class='form-group'>
						<label>Address
							<input type='text' name='personal_address' class='form-control' <?php if($oldData) { ?> value='<?= $oldData["address"] ?>' disabled <?php } ?> />
						</label>
					</div>
					<div class='form-group'>
						<label>City
							<input type='text' name='personal_city' class='form-control' <?php if($oldData) { ?> value='<?= $oldData["city"] ?>' disabled <?php } ?> />
						</label>
					</div>
					<div class='form-group'>
						<label>State
							<input type='text' name='personal_state' class='form-control' <?php if($oldData) { ?> value='<?= $oldData["state"] ?>' disabled <?php } ?> />
						</label>
					</div>
					<div class='form-group'>
						<label>Zip
							<input type='number' name='personal_zip' class='form-control' value='<?= $_SESSION["zip"] ?>' disabled/>
						</label>
					</div>
				</div>

				<div class='col-xs-12 col-md-6 col-lg-4'>
					<h3>Course Information</h3>
					<div class='form-group'>
						<label>Proposed course name
							<input type='text' name='course_name' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>Summarize your course in less than 75 words
							<textarea id='summary' name='course_summary' rows="5" class='form-control'></textarea>
						</label>
						<p><span id='word-count'>0</span> / 75 words</p>
					</div>
					<div class='form-group'>
						<label>Which quarter would you like to start teaching?
							<input type='text' name='course_start' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>Estimated number of sections
							<input type='number' name='course_sections' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>Estimated number of hours per class day
							<input type='number' step='any' name='course_hours' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>Estimated number of days per section
							<input type='number' name='course_days' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>Max number of students per section
							<input type='number' name='course_max' class='form-control'/>
						</label>
					</div>
				</div>


				<div class='col-cs-12 col-md-12 col-lg-4' id='questions'>
					<div class='col-xs-12'><h3>Course Questions</h3></div>
					<div class='col-xs-12 col-md-6 col-lg-12'>
						<div class='form-group'>
							<label>How will you attract students from UW with this course?
								<textarea type='text' name='question_attract' class='form-control'></textarea>
							</label>
						</div>
					</div>
					<div class='col-xs-12 col-md-6 col-lg-12'>
						<div class='form-group'>
							<label>Why do you want to teach this course?
								<textarea type='text' name='question_why' class='form-control'></textarea>
							</label>
						</div>
					</div>
					<div class='col-xs-12 col-md-6 col-lg-12'>
						<div class='form-group'>
							<label>What skills/knowledge do you hope students will gain from this course?
								<textarea type='text' name='question_skills' class='form-control'></textarea>
							</label>
						</div>
					</div>
					<div class='col-xs-12 col-md-6 col-lg-12'>
						<div class='form-group'>
							<label>What supplies or equipment will students need to provide? What will you provide?
								<textarea type='number' name='question_supplies' class='form-control'></textarea>
							</label>
						</div>
					</div>
					<div class='col-xs-12 col-md-6 col-lg-12'>
						<div class='form-group'>
							<label>What excersises will students participate in?
								<textarea type='text' name='question_exercises' class='form-control'></textarea>
							</label>
						</div>
					</div>
					<div class='col-xs-12 col-md-6 col-lg-12'>
						<div class='form-group'>
							<label>Please describe your background in reference to the course, teaching, and training
								<textarea type='tel' name='question_background' class='form-control'></textarea>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class='row'>
				<div class='col-xs-12 col-md-6'>
					<h3>Resume and Course Outline</h3>
					<div class='form-group'>
						<p>Upload your resume and a course outline. We accept .doc, .docx, .pdf and .txt</p>
						<p>MAX FILE SIZE IS 2 MB</p>
						<p>Resume <input name="resume" type='file' value='Resume' /></p>
						<p>Course Outline <input name="outline" type="file" value="Outline"></p>
					</div>
				</div>

				<div class='col-xs-12 col-md-6'>
					<h3>Submit</h3>
					<p>Once you've completed all the information to the best of your ability, click submit to apply. You will not be able to edit any information submitted.</p>
					<p>Did you have issues filling out this form? Something you'd like to say about this process? Please <a target='_blank' href='/asuwxpcl/feedback.php'>give us feedback</a>!</p>
					<input type='submit' class='btn btn-success' />
				</div>
			</div>
		</form>
	</div>
</section>


<?php
tail();
?>