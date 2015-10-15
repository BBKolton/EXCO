<?php
	//A nice, pretty, easy way of browsing all the classes ExCo has to offer
	
	require("../common.php");
	$db = new DB();
	$types = $db -> select("SELECT DISTINCT type from " . $DATABASE . ".courses ORDER BY type");
	head("<link href='/asuwecwb/.assets/css/courses.css' type='text/css' rel='stylesheet' />" . 
	     "<script type='text/javascript' src='/asuwecwb/.assets/js/courses.js'></script>"); ?>



	<section class="title">
		<div class="jumbotron">
			<div class="container">
				<h1><?= $QUARTER ?> Classes</h1>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-md-10">
					<?php for ($j = 0; $j < count($types); $j++) { 
						$type = $GENRES[$types[$j]["type"]]; ?>
						<h2><a name="<?= $type ?>"><?= $type ?></h2>
						<div class="row">
							<?php 
							$classes;
							if (empty($_SESSION["permissions"]) || $_SESSION["permissions"] === '1') {
								$classes = $db -> select("SELECT id, name, type, status 
								                          FROM " . $DATABASE . ".courses
								                          WHERE type = " . $db->quote($types[$j]['type']) . " 
								                          AND status = '1' ");
							} else {
								$classes = $db -> select("SELECT id, name, type, status
								                          FROM " . $DATABASE . ".courses
								                          WHERE type = " . $db->quote($types[$j]['type']));
							}
							for ($i = 0; $i < count($classes); $i++) { 
								?>
								<a href="/asuwecwb/courses/course.php?id=<?= $classes[$i]['id'] ?>">
									<div class="class-wrap col-lg-4 col-sm-6 col-xs-12">
										<div class="class" style="background-image: url('/asuwecwb/.assets/img/classes/<?= $classes[$i]['id'] ?>.jpg'), url('/asuwecwb/.assets/img/classes/fallback.jpg'); background-size: cover;">
											<h3 class="status-<?= $classes[$i]['status']?>"><?= htmlspecialchars($classes[$i]["name"]) ?></h3>
										</div>
									</div>
								</a>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<div class="col-md-2">
					<div class="sidebar-wrap">
						<nav class="sidebar hidden-print hidden-sm hidden-xs ">
							<h2>Genres</h2>
							<ul>
								<?php for ($i = 0; $i < count($types); $i++) { 
									$type = $GENRES[$types[$i]["type"]] ?>
									<li><a class="sidebar-links" href="#<?= htmlspecialchars($type) ?>"><?= $type ?></a></li>
								<?php } ?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php tail();
?>