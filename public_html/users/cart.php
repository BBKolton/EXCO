<?php
	//TODO make sure the userknows that they can ad dmore classes
	//Remove a class from the cart
	if ($_GET["remove"] == 1) {
		session_start();
		$cart = $_SESSION["cart"];
		unset($cart[$_GET["cart"]]); //the get values are teh course and section to remove
		unset($cart[$_GET["cart"] + 1]);
		$_SESSION["cart"] = array_values($cart);
		header("Location: /asuwecwb/users/cart.php");
		die();
	}

	//general page things
	require("../common.php");
	head("<link href='/asuwecwb/.assets/css/cart.css' type='text/css' rel='stylesheet'>"); ?>

	<section class="title">
		<div class="jumbotron">
			<div class="container">
				<h1>Your Cart</h1>
			</div>
		</div>
	</section>


	<section class="content">
		<div class="container">

			<?php if (empty($_SESSION["cart"])) { ?>
				<p>You have no items in your cart right now</p>
			<?php } else { ?>
				<div class="row">
					<div class="col-md-9 col-xs-12">
						<h1>Courses</h1>
						<table>
							<tr>
								<th></th>
								<th>Class</th>
								<th>Section</th>
								<th>Days</th>
								<th>Times</th>
								<th>Class Fee</th>
								<th>ExCo Fee</th>
							</tr>
							<?php 
								//the various fees we guun be keepin track of today
								$totalClassFee = 0;
								$totalExCoFee = 0;
								$total = 0;

								$db = new DB();
								for ($i = 0; $i < count($_SESSION["cart"]); $i+=2) { 
									$id = $_SESSION["cart"][$i];
									$section = $_SESSION["cart"][$i + 1]; 
									$courses = $db -> select("SELECT courses.name,
									                                 sec.fee_gen,
									                                 sec.fee_uw,
									                                 sec.times,
									                                 sec.days
									                          FROM " . $DATABASE . ".courses
									                          JOIN " . $DATABASE . ".sections sec
									                          ON courses.id = sec.course_id
									                          WHERE courses.id = " . $db->quote($id)); 
									$type = $_SESSION["type"];
									$costExCo;
									$costClass;
									if ($type === "student") {
										$costExCo = 5;
										$costClass = $courses[0]["fee_uw"];
									} else {
										$costExCo = 12;
										$costClass = $courses[0]["fee_gen"];
									} 
									$totalExCoFee+= $costExCo;
									$totalClassFee+= $costClass;
									?>
									<tr>
										<td><a href="/asuwecwb/users/cart.php?remove=1&cart=<?= $i ?>"><img src="http://placehold.it/20x20" /></td>
										<td><?= htmlspecialchars($courses[0]["name"]) ?></td>
										<td><?= $section ?></td>
										<td><?= htmlspecialchars($courses[0]["days"]) ?></td>
										<td><?= htmlspecialchars($courses[0]["times"]) ?></td>
										<td>$<?= $costClass ?></td>
										<td>$<?= $costExCo ?></td>
									</tr>
								<?php } ?>
						</table>
						<p><a href="/asuwecwb/courses/courses.php">Add another class</a></p> 
						<h1>Total Due</h1>
						<p>The Experimental College collects a fee per class. Other fees noted above <strong>are due to instructors on the first day of class</strong>, and are noted here for your convenience. You will only pay the total Experimental College fee when you click continue</p>
						<table>
							<tr>
								<td>Total ExCo Fee (due right now)</td>
								<td>$<?= $totalExCoFee ?></td>
							</tr>
							<tr>
								<td>Total Class Fees (due on the first day of your class(es))</td>
								<td>$<?= $totalClassFee ?></td>
							</tr>				
						</table>
					</div>
					<div class="col-md-3 col-xs-12">
						<h2>Credit Card Information</h2>
						<form action="/asuwecwb/users/cartsubmit.php" method="post">
							<p>First Name</p><input type="text" name="first-name" value="<?= htmlspecialchars($_SESSION['first_name']) ?>">
							<p>Last Name</p><input type="text" name="last-name" value="<?= htmlspecialchars($_SESSION['last_name']) ?>">
							<p>Email Address</p><input type="text" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>">
							<p>Card Number (no dashes or spaces)</p><input type="text" name="card" >
							<p>Expiration Date (in form MMYY)</p><input type="text" name="exp" >
							<p>CVC/CVV code (three digits on back of card)</p><input type="text" name="cvc" >
							<p>Phone Number</p><input type="text" name="phone" value="<?= htmlspecialchars($_SESSION['phone']) ?>">
							<p>This information is used only for this transaction. It is not
								saved by the Experimental College</p>
							<button action="submit">Register Now!</button>
						</form>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
	
	<?php 
	tail();
?>