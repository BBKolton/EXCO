<?php
require('../common.php');

session_start();
if ($_SESSION['permissions'] < 2) {
	error('Access Denied', 'You are not an instructor');
}

head('<link href="/asuwxpcl/.assets/css/instructors.css" rel="stylesheet" />');

?>

<section class='title'>
	<div class='jumbotron'>
		<div class='container'>
			<h1>Instructors Page</h1>
		</div>
	</div>
</section>

<section class='content'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-8'>
				<div class='links-wrap'>
					<ul class='nav nav-pills'>
						
						<li role='presentation'>
							<h4>Links</h4>
						</li>

						<li role='presentation'>
							<a href='dates.php'>
								<span class='glyphicon glyphicon-calendar'></span> Dates
							</a>
						</li>

						<li role='presentation'>
							<a href='/asuwxpcl/teach/application.php'>
								<span class='glyphicon glyphicon-asterisk'></span> NCPs
							</a>
						</li>

						<li role='presentation'>
							<a href='rifs.php'>
								<span class='glyphicon glyphicon-repeat'></span> RIFs
							</a>
						</li>

						<li role='presentation'>
							<a href='galleys.php'>
								<span class='glyphicon glyphicon-tasks'></span> Galleys
							</a>
						</li>

					</ul>
				</div>
				<p>Welcome to the instructor portal! All of the important administrative tasks you may need to take for new courses, galleys, and returning instructor forms are available here. If you're looking for a way to access your class rosters, please use the drop down menu at the top right and select "my courses"</p>
				<p><a href='/asuwxpcl/.assets/docs/spotRegistrationForm.doc'>On the spot registration form</a></p>
			</div>
			<div class='col-md-4'>
				<div class='dates'>
					<h2>News &amp; Alerts</h2>
					<h3>Important Deadlines</h3>
					<p>Instructors, for all important dates and deadlines, please check <a href='dates.php'> here</a>.</p>
					<h3>RIFs Now Open</h3>
					<p>Please submit your rifs for the coming quarter now!</p>
				</div>
			</div>
		</div>
	</div>
</section>


<?php
tail();