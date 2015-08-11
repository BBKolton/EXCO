<?php
	//A nice, pretty, easy way of browsing all the classes ExCo has to offer
	
	require("../common.php");
	$db = new DB();
	$types = $db -> select("SELECT DISTINCT type from " . $DATABASE . ".courses ORDER BY type");
	head("<link href='/asuwecwb/.assets/css/classes.css' type='text/css' rel='stylesheet' />" . 
	     "<script type='text/javascript' src='/asuwecwb/.assets/js/classes.js'></script>"); ?>

	<section class="classes">
		<div class="container">
			<div class="row">
				<div class="col-md-10">
					<h1><?= $QUARTER ?> Classes</h1>
					<?php for ($j = 0; $j < count($types); $j++) { 
						$type = $GENRES[$types[$j]["type"]]; ?>
						<h2><a name="<?= $type ?>"><?= $type ?></h2>
						<div class="row">
							<?php 
							$classes = $db -> select("SELECT id, name, type 
							                          FROM " . $DATABASE . ".courses
							                          WHERE type = " . $db->quote($types[$j]['type']));
							for ($i = 0; $i < count($classes); $i++) { 
								?>
								<a href="/asuwecwb/courses/course.php?id=<?= $classes[$i]['id'] ?>">
									<div class="class-wrap col-lg-4 col-sm-6 col-xs-12">
										<div class="class" style="background-image: url('/asuwecwb/.assets/img/classes/<?= $classes[$i]['id'] ?>.jpg'), url('/asuwecwb/.assets/img/classes/fallback.jpg'); background-size: cover;">
											<h3><?= $classes[$i]["name"] ?></h3>
										</div>
									</div>
								</a>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<div class="col-md-2">
					<nav class="sidebar hidden-print hidden-sm hidden-xs ">
						<h1>Categories</h1>
						<ul>
							<?php for ($i = 0; $i < count($types); $i++) { 
								$type = $GENRES[$types[$i]["type"]] ?>
								<li><a class="sidebar-links" href="#<?= $type ?>"><?= $type ?></a></li>
							<?php } ?>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<section style="height: 600px">
	</section>

	<?php tail();
?>