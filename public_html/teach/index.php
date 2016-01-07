<?php
	require("../common.php");
	head('<link href="/asuwxpcl/.assets/css/teach.css" type="text/css" rel="stylesheet" />');
	?>

	<section class="title">
		<div class="jumbotron">
			<div class="container">
				<h1>Teach With Us!</h1>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container">
			<?php if ($_SESSION['permissions'] > 2) { ?>
				<div class='row'>
					<div class='col-xs-12 col-sm-3 col-md-2 pull-right'>
						<h3>NIA Admin</h3>
						<p><a href='applicationsadmin.php'>Click here for the nia admin</a></p>
					</div>
					<div class='col-xs-12 col-sm-9 col-md-10'>
			<?php } ?>
			<h1>Teach at EXCO</h1>
			<p>The Experimental College relies on our hard working instructors to teach interesting, engaging, and extracurricular classes. We're always interested to hear a course idea, and are always on the lookout for new, exciting talent. </p>
			<h2>New Instructors</h2>
			<p>The relationship between the instructor and the Experimental College is that of an independent contractor and an organization. The instructor provides a service for the Experimental College in the form of the class he or she teaches. This course is taught in accordance with the description in our quarterly catalog, which serves as the contract between the instructor and the Experimental College. When students register for a course, they pay a registration fee to the Experimental College and the course fee directly to the instructor on the first day of the course.</p>
			<p>Please read our <a href="/asuwxpcl/.assets/docs/instructorinfo.pdf"> prospective instructor information sheet</a>. If you want to work with the Experimental College, you can <b>apply online <a href="application.php">here</a>.</b> If you have any further questions, please <a href="/asuwxpcl/about.php">contact us</a>.</p>
			<h2>Returning Instructors</h2>
			<p>If you're already an instructor with EXCO and want to propose a new class, please download the <a href="/asuwxpcl/.assets/docs/newcourseproposal.pdf">new course proposal form for existing instructors</a> and submit it to our office, or apply online <a href="application.php">here</a>.</p>
			<?php if ($_SESSION['id'] > 2) { ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>


	<?php tail();
?>