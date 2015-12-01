<?php
require('../common.php');

session_start();
if ($_SESSION['permissions'] < 2) {
	error('Access Denied', 'You are not an instructor');
}

head('<link href="/asuwecwb/.assets/css/instructors.css" rel="stylesheet" />');

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
				<h2>Links</h2>
				<p><a href='dates.php'>Dates</a></p>
				<p><a href='rifs.php'>Rifs</a></p>
				<p><a href='rif.php'>Rif</a></p>
				<p><a href='galleys.php'>Galleys</a></p>
				<p><a href='galley.php'>Galley</a></p>
			</div>
			<div class='col-md-4'>
				<div class='dates'>
					<h2>Instructor Dates</h2>
					<p>Instructors, for all important dates and dealines, please check 
					<a href='dates.php'> here</a>.</p>
				</div>
			</div>
		</div>
	</div>
</section>


<?php
tail();