<?php
	//TODO make sure the userknows that they can ad dmore classes
	//Remove a class from the cart
	if ($_GET["remove"] == 1) {
		session_start();
		$cart = $_SESSION["cart"];
		unset($cart[$_GET["cart"]]); //the get values are teh course and section to remove
		unset($cart[$_GET["cart"] + 1]);
		$_SESSION["cart"] = array_values($cart);
		header("Location: /asuwxpcl/users/cart.php");
		die();
	}

	//general page things
	require("../common.php");
	head("<link href='/asuwxpcl/.assets/css/cart.css' type='text/css' rel='stylesheet'>"); ?>

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
						<table class='table table-striped table-condensed'>
							<thead>
								<tr>
									<th>Remove</th>
									<th>Class</th>
									<th>Section</th>
									<th>Days</th>
									<th>Times</th>
									<th>Class Fee</th>
									<th>ExCo Fee</th>
								</tr>
							</thead>
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
									                          WHERE courses.id = " . $db->quote($id) . " 
									                          AND sec.section = " . $db->quote($section)); 
									$type = $_SESSION["type"];
									$costExCo;
									$costClass;
									if (isset($_SESSION['netId'])) {
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
										<td><a href="/asuwxpcl/users/cart.php?remove=1&cart=<?= $i ?>"><span class='glyphicon glyphicon-remove'></span></a></td>
										<td><?= htmlspecialchars($courses[0]["name"]) ?></td>
										<td><?= $section ?></td>
										<td><?= htmlspecialchars($courses[0]["days"]) ?></td>
										<td><?= htmlspecialchars($courses[0]["times"]) ?></td>
										<td>$<?= $costClass ?></td>
										<td>$<?= $costExCo ?></td>
									</tr>
								<?php } ?>
						</table>
						<p><a href="/asuwxpcl/courses/courses.php">Add another class</a></p> 
						<h1>Total Due</h1>
						<p>The Experimental College collects a fee per class. Other fees noted above <strong>are due to instructors on the first day of class</strong>, and are noted here for your convenience. You will only pay the total Experimental College fee when you click continue</p>
						<table>
							<tr>
								<td>Total ExCo Fee (due right now): </td>
								<td>&nbsp;<b>$<?= $totalExCoFee ?></b></td>
							</tr>
							<tr>
								<td>Total Class Fees (due on the first day of your class(es)): </td>
								<td>&nbsp;<b>$<?= $totalClassFee ?></b></td>
							</tr>				
						</table>
						<h1>Important Policies You Should Know</h1>
						<p>The Experimental College <b>does not offer refunds</b> for the course registration fee if a user requests cancellation. We may waive fees if an unforseeable event occurs that is out of the student's control. You may change your registration from any one class to another within the same quarter by contacting our office. We will not transfer a registration accross quarters. With this purchase, you will only pay the registration fee to the Experimental College, and <b>you are personally responsible for paying instructors the class' fee on the first day of the class</b></p>
					</div>
					<div class="col-md-3 col-xs-12">
						<h2>Credit Card Information</h2>
						<form action="/asuwxpcl/users/cartsubmit.php" method="post">
							<input type='hidden' name='type' value='credit' />
							<div class='form-group'>First Name<input class='form-control' type="text" name="first-name" value="<?= htmlspecialchars($_SESSION['first_name']) ?>"></div>
							<div class='form-group'>Last Name<input class='form-control' type="text" name="last-name" value="<?= htmlspecialchars($_SESSION['last_name']) ?>"></div>
							<div class='form-group'>Email Address<input class='form-control' type="text" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>"></div>
							<div class='form-group'>Card Number (no dashes or spaces)<input class='form-control' type="text" name="card" ></div>
							<div class='form-group'>Expiration Date (in form MMYY)<input class='form-control' type="text" name="exp" ></div>
							<div class='form-group'>CVC/CVV code (three digits on back of card)<input class='form-control' type="text" name="cvc" ></div>
							<div class='form-group'>Phone Number<input class='form-control' type="text" name="phone" value="<?= htmlspecialchars($_SESSION['phone']) ?>"></div>
							<div class='form-group'>Where did you hear about EXCO?
								<select name='referred' class='form-control'>
									<option value='Select...' selected>Select...</option> 
									<?php $options = $db ->select("SELECT name FROM referrals ORDER BY count DESC"); 
									foreach ($options as $option) { 
										if ($option['name'] != "Select...") { ?>
										<option value='<?= $option['name'] ?>'><?= $option['name'] ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class='form-group'><i>This information is used only for this transaction. It is not saved by the Experimental College</i></div>
<!-- 							<p><b>Registration is on hold due to technical difficulties, please come back in an hour</b></p>-->
							<button action="submit" class='btn btn-success'>Register Now!</button>
						</form>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
	
	<?php 
	tail();
?>