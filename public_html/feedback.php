<?php

	require("common.php");
	
	if ($_POST) {
		setData($DATABASE);
	}

	head();

	?>
	<section class="content">
		<div class="container">
			<h1>Feedback</h1>
			<p>Have something you want to say about the website, a class, or the Experimental
			College in general? This is the place! Share your thoughts with us to help ExCo grow.</p>
			<p><em>Please note that unless you choose, your feedback is completely anonymous</em></p>
			<form action="/asuwecwb/feedback.php" method="post">
				<div class='form-group'>
					<p>What are you giving feedback about?</p>
					<select class='form-control' name="type">
						<option value="website">The Website</option>
						<option value="personel">An ExCo Employee</option>
						<option value="instructor">An Instructor</option>
						<option value="general">ExCo in General</option>
						<option value="other">Something Else</option>
					</select>
				</div>
				<div class='form-group'>
					<p>What would you like to say?</p>
					<textarea class='form-control' name="text" cols="70" rows="7" name="comment" placeholder="Comment here..."></textarea>
				</div>
				<div class='form-group'>
					<p>Provide your email if you wish</p>
					<input class='form-control' name="email" type="text" placeholder="Email (optional)" />
				</div>
				<button class='btn btn-success' type="submit" value="Submit">Submit</button>
				<button class='btn btn-warning' type="reset" value="Reset">Reset</button>
			</form>
		</div>
	</section>

	<?php
	tail();

	function setData($DATABASE) {
		$db = new DB();
		$values = [];
		foreach ($_POST as $value) {
			array_push($values, $db->quote($value));
		}
		$valuesString = "(" . implode(", ", $values) . ")";
		$db -> query("INSERT INTO " . $DATABASE . ".feedback 
		              (type, text, email) 
		              VALUES " . $valuesString);

		head(); ?>

		<div class="container">
			<h1>Thank you for your feedback!</h1>
			<p>Your feedback has been successfuly saved.</p>
		</div>

		<?php tail();
		die();
	}

?>