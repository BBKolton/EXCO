<?php 
	require("common.php");

	head("<link href='/asuwecwb/.assets/css/index.css' type='text/css' rel='stylesheet'>"); ?>

	<!--CAUROSEL AREA-->
	<section class="carousel" role="main">
		<div class="container">
			<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
				<!-- Wrapper for slides -->
				<div class="carousel-inner">

					<?php 
						$firstImg = true;
						foreach (glob(".assets/img/carousel/*.*") as $file) { 
					?>

					<div class="item 
						<?php if ($firstImg) { ?> 
							active 
							<?php $firstImg = false; } 
						?>">
						<img src=" <?= ($file) ?> " alt="...">
					</div>
					<?php } ?>

					<!-- Controls DELETE-->
					<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</a>
					<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>
				</div> <!-- Carousel -->
				<div class="main-text text-center" >
					<h1>Experimental College</h1>
					<p>A really cool slogan/motto here</p>
				</div>
			</div>
		</div>
	</section>
	<section class="explanation">
		<div class="container">
			<h1>Phew, It's Dusty in Here!</h1>
			<p>Welcome to the new Experimental College webpage. There's a lot of construction going on, so sit tight as things can change day by day. If you're looking for the live site, head <a href="exco.org">here</a>. Questions? Email webmaster at exco dot org</p>
		</div>
	</section>
	<section class="courses-list">
		<div class="container">
		<h1>Classes</h1>
			<ul>

				<?php 
					//get a database connection
					$db = new DB();
					$courses = $db -> select("SELECT id, name FROM " . $DATABASE . ".courses");
					for ($i = 0; $i < count($courses); $i++) { ?>

						<li><a href="/asuwecwb/courses/course.php?id=<?= $courses[$i]['id'] ?>"><?= $courses[$i]["name"]?></a></li>

					<?php }
				?> 

			</ul>
		</div>
	</secton>

	<section class="banner-two">
		<div class="container">
			<div  style="height: 2000px;">ahhh </div>
		</div>
	</section>

	<?php tail(); 
?>
