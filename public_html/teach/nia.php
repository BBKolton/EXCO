<?php 
require('../common.php');
session_start();

if (!isset($_SESSION['id'])) {
	header('Location: /asuwecwb/users/login.php');
	die();
}

head('<script src="/asuwecwb/.assets/js/nia.js"></script>' .
     '<link href="/asuwecwb/.assets/css/nia.css" rel="stylesheet" />');
?>

<section class='content'>
	<div class='container'>
		<h2>Apply to Instruct a Course with EXCO</h2>
		<p>Thank you for your interest in teaching with the Experimental College. We're always looking for new, talented instructors to teach engaging, unique, and quality classes. Please fill out the form below to apply to a course with the college. Please estimate where you cannot give an exact answer; details can be dealt with later</p>
		<form method='post' action='niasubmit.php' enctype='multipart/form-data'>
			<div class='row'>
				<div class='col-xs-12 col-md-6 col-lg-4'>
					<h3>Personal Information</h3>
					<div class='form-group'>
					<p class='subtitle'>Disabled fields are tied to your account. You may change them <a href='/asuwecwb/users/preferences.php'>here</a></p>
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
							<input type='text' name='personal_address' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>City
							<input type='text' name='personal_city' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>State
							<input type='text' name='personal_state' class='form-control'/>
						</label>
					</div>
					<div class='form-group'>
						<label>Zip
							<input type='number' name='personal_zip' class='form-control' disabled/>
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
						<label>When would you start?
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
					<p>Did you have issues filling out this form? Something you'd like to say about this process? Please <a target='_blank' href='/asuwecwb/feedback.php'>give us feedback</a>!</p>
					<input type='submit' class='btn btn-success' />
				</div>
			</div>
		</form>
	</div>
</section>


<?php
tail();
?>