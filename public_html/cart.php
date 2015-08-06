<?php
	//TODO make sure the userknows that they can ad dmore classes
	//Remove a class from the cart
	if ($_GET["remove"] == 1) {
		session_start();
		$cart = $_SESSION["cart"];
		unset($cart[$_GET["cart"]]); //the get values are teh course and section to remove
		unset($cart[$_GET["cart"] + 1]);
		$_SESSION["cart"] = array_values($cart);
		header("Location: cart.php");
		die();
	}

	//general page things
	require("common.php");
	head();
	?>
	<section class="cart">
		<div class="container">
			<H1>Your Cart</H1>

			<?php if (empty($_SESSION["cart"])) { ?>
				<p>You have no items in your cart right now</p>
			<?php } else { ?>
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
								<td><a href="cart.php?remove=1&cart=<?= $i ?>"><img src="http://placehold.it/20x20" /></td>
								<td><?= $courses[0]["name"] ?></td>
								<td><?= $section ?></td>
								<td><?= $courses[0]["days"] ?></td>
								<td><?= $courses[0]["times"] ?></td>
								<td>$<?= $costClass ?></td>
								<td>$<?= $costExCo ?></td>
							</tr>
						<?php } ?>
				</table>
			<?php } ?>
		</div>
	</section>
	<?php if (!empty($_SESSION["cart"])) { ?>
		<section class="total">
			<div class="container">
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
				<h2>Credit Card Information</h2>
				<form action="cartsubmit.php" method="post">
					<input type="text" name="card" placeholder="Credit card number" /><br />
					<input type="text" name="exp" placeholder="Expiration MMYY" /><br />
					<input type="text" name="cvc" placeholder="Security code" /><br />
					<input type="text" name="phone" placeholder="Phone Number" /><br />
					<p>This information is used only for this transaction. It is not
						saved by the Experimental College</p>
					<button action="submit">Register Now!</button>
				</form>
			</div>
		</section>
	<?php } ?>
	
	<?php 
	tail();
?>