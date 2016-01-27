<?php 
	require("common.php");

	head("<link href='/asuwxpcl/.assets/css/index.css' type='text/css' rel='stylesheet'>"); ?>

	<!--CAUROSEL AREA-->
	<section role="main">
		<div class="jumbotron">
			<div class="container">
				<h1>ASUW Experimental College</h1>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-xs-12">
					<h1>Browse Courses</h1>
					<p>We offer dozens of classes a quarter. Choose a category to find what's right for you!</p>
					<div class='categories-wrap'>
					
					<?php foreach ($GENRES as $key => $val) { ?>
						<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
							<a href='courses/courses.php#<?= $val ?>'>
								<div class='cat-wrap'>
									<img class='icon' src="/asuwxpcl/.assets/img/icons/<?= $key ?>.png" />
									<h3 class='text-center'><?= $val ?></h3>
								</div>
							</a>
						</div>
					<?php } ?>
					
					</div>
				</div>
				<div class="col-md-4 col-xs-12 sidebar-wrap">
					<div class="sidebar">
						<h2>Courses Starting Soon</h2>
						
						<h2>What is EXCO?</h2>
						<p>The Experimental College is a student-run organization partnered with professional instructors that teaches classes a bit 'out there.' We go beyond the 
						traditional classes one would expect, to fill in the gaps that others left behind. Check out our
						<a href="/asuwxpcl/about.php"> About </a>page for more info.</p>
						<h2>A New Site!</h2>
						<p>Welcome to the new Experimental College Website! We've put a lot of work into revamping the website for ease and usability. If you encounter any issues during your visit, please use the feedback link at the top of the webpage to let us know!</p>
					</div>
				</div>
			</div>
		</div>
	</section>


	<?php tail(); 
?>
