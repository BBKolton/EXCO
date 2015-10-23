<?php
	require("../common.php");
	head('<script type="text/javascript" src="../.assets/js/rif.js"></script>' . 
		 '<link rel="stylesheet" href="../.assets/css/rif.css">', 1, 1);

	session_start();
	if (!verifyAdminOrClassInstructor($_GET['id'])) {
		error('Access Denied', 'You are not cleared to edit this page');
	}

	$c;
	if (isset($_GET['id'])) {
		$db = new DB();
		$c = $db->select("SELECT * FROM rifs WHERE id = " . $db->quote($_GET['id']));
		$c = $c[0];

		$i = $db->select("SELECT * FROM rifs_items WHERE rif_id = " . $db->quote($c['id']));

		$s = $db->select("SELECT * FROM rifs_sections WHERE rif_id = " . $db->quote($c['id']));

		if (!$c) {
			error('No Rif Found', 'The ID specified does not correspond to an existing rif');
		}
	}

?>	
<form action="/asuwecwb/instructors/rifsubmit.php?id=<?= $c['id'] ?>" method="POST">
	<section class="title">
		<div class="container">
			<h1>Returning Instructor Form</h1>
			<p>Use this registration form to register for a returning class.</p>
			<p><a href='rifs.php'>Return to Rifs</a></p>
		</div>
	</section>



	<section class="content"> <!-- This section holds all the fee entrywork an instructor must do. It mostly does
	                       math all by itself with javascript.  -->
		<div class="container">
			<fieldset>
				<h2>Fees</h2>

				<h3>Supplies</h3>
				<p>List supplies you will be ordering from the Experimental College. Enter the item
				name, quantity and cost (without dollar signs) and click "Add Item" to add it to your list.</p>
				<table id="items">
					<tr>
						<th>Clear</th>
						<th>Supply Item</th>
						<th>Cost per Item</th>
						<th>Total Class Quantity</th>
						<th>Line Cost</th>
					</tr>
					<tr id="item">
						<td><img id="item-clear-fields" src="//placehold.it/20x20" /></td>
						<td><input type="text" placeholder="Paper"/></td>
						<td><input type="text" placeholder="12" id="item-cost"/></td>
						<td><input type="text" placeholder="20" id="item-quan"/></td>
						<td id="item-row-cost">$0</td>
					</tr>
					<?php 
						$total = 0;
						$num = 0;
						foreach ($i as $item) { 
							$total+= $item['cost'] * $item['quantity']; ?>
							<tr>
								<td><img class='clear-original-item' src='//placehold.it/20x20' /></td>
								<td>
									<input disabled='disabled' type='text' value='<?= $item["name"] ?>' />
									<input hidden='hidden' type='text' name='item-<?= $num ?>-0' value='<?= $item["name"] ?>' />
								</td>
								<td>
									<input disabled='disabled' type='text' value='<?= $item["cost"] ?>' />
									<input hidden='hidden' type='text' name='item-<?= $num ?>-1' value='<?= $item["cost"] ?>' />
								</td>
								<td>
									<input disabled='disabled' type='text' value='<?= $item["quantity"] ?>' />
									<input hidden='hidden' type='text' name='item-<?= $num ?>-2' value='<?= $item["quantity"] ?>' />
								</td>
								<td class='total'>$<?= $item['quantity'] * $item['cost'] ?></td>
							</tr>
							<?php 
							$num++;
						}


					?>


				</table>
				<button type="button" id="item-add">Add Item</button>
				<p id="item-total">Total fee: $<?= $total ?></p>

				<h2>Room Rental</h2>
				<p>Please view the room rental fees <a href="">here</a></p>
				<table id="rooms">
					<tr>
						<td>Room Rental Hourly Rate</td>
						<td><input type="text" id="room-rate" name="room-rate" size="6" value='<?= $c["room_rate"] ?>'/></td>
					</tr>
					<tr>
						<td>Instruction Hours</td>
						<td><input type="text" id="room-hours" name="room-hours" size="6" value='<?= $c["room_hours"] ?>'/></td>
					</tr>
					<tr>
						<td>Total Room Rental Fee</td>
						<td name="room-total" id="room-total">$0</td>
					</tr>
					<tr>
						<td>Total Supply and Room Rental Fees</td>
						<td name="room-item-total" id="room-item-total">$0</td>
					</tr>

				</table>

				<h2>Student Fees</h2>
				<p>Recommended Maximum Fees</p>
				<table>
					<tr>
						<td>General Public: </td>
						<td id="fee-gen">$0</td>
					</tr>
					<tr>
						<td>UW Student: </td>
						<td id="fee-uw">$0</td>
					</tr>
				</table>
				<p>Input your fee per student below. For affordability's sake, we urge you do not exceed the maximums set above. Fees that exceed the maximum may be rejected by the Experimental College</p>
				<table>
					<tr>
						<td>General Public: </td>
						<td><input name="fee-uw" type="text" size="6" value='<?= $c["fee_uw"] ?>' /></td>
					</tr>
					<tr>
						<td>UW Student: </td>
						<td><input name="fee-gen" type="text" size="6" value='<?= $c["fee_gen"] ?>' /></td>
					</tr>
				</table>			
			</fieldset>
		</div>
	</section>

	<section class="content"><!-- times section -->
		<div class="container">
			<fieldset>
				<h2>Dates and Times</h2>
				<div class="datepicker"></div>
				<table id="sections">
					<tr>
						<th>Clear</th>
						<th>Start Time</th>
						<th>End Time</th>
						<th>Meeting Dates</th>
					</tr>
					<tr id="section">
						<td><img id="section-clear-fields" src="//placehold.it/20x20" /></td>
						<td><input type="text"  id="section-start" placeholder="6:00pm"/></td>
						<td><input type="text"  id="section-end" placeholder="9:00pm"/></td>
						<td><input id="dates" type="text" /><button type="button" id="section-add">Add Section</button></td>
					</tr>
					<?php 
					$num = 0;
					foreach ($s as $sec) { 
						$num++;
						?>
						<tr>
							<td><img class='clear-original' src='//placehold.it/20x20' /></td>
							<td>
								<input disabled='disabled' type='text' value='<?= $sec["time_start"] ?>' />
								<input hidden='hidden' type='text' name='section-<?= $num ?>-0' value='<?= $sec["time_start"] ?>' />
							</td>
							<td>
								<input disabled='disabled' type='text' value='<?= $sec["time_end"] ?>' />
								<input hidden='hidden' type='text' name='section-<?= $num ?>-1' value='<?= $sec["time_end"] ?>' />
							</td>
							<td>
								<input disabled='disabled' type='text' value='<?= $sec["days"] ?>' />
								<input hidden='hidden' type='text' name='section-<?= $num ?>-2' value='<?= $sec["days"] ?>' />
							</td>
						</tr>
						<?php 
					}

					?>
				</table>
			</fieldset>
		</div>
	</section>

	<section class="content"> <!-- Information This section holds all the nice descriptions and email messages -->
		<div class="container">
			<fieldset>
				<h2>Course Information</h2>
				<h3>Basic Things</h3>
				<table>
					<tr>
						<td>Name</td>
						<td><input name="name" type="text" size="20" value='<?= $c["name"] ?>' /></td>
					</tr>
					<tr>
						<td>Category</td>
						<td><select name="info-cat">

						<?php for($i = 0; $i < count($GENRES); $i++) { ?>
							<option value="<?= $i ?>" <?= $c["category"] == $i ? "selected" : "" ?>><?= $GENRES[$i] ?></option>
						<?php }	?>

						</select></td>
					</tr>
					<tr>
						<td>Max Enrollment</td>
						<td><input type='text' name='size' size='12' value='<?= $c["size"] ?>'/>
					<tr>
						<td>General Location</td>
						<td><input name="loc-gen" type="text" size="12" value='<?= $c["loc_gen"] ?>' /></td>
					</tr>
					<tr>
						<td>Specific Location</td>
						<td><input name="loc-spec" type="text" size="12" value='<?= $c["loc_spec"] ?>' /></td>
					</tr>
					<tr>
						<td>Are you willing to accept students after the first day of class?</td>
						<td><select name='info-firstday'>
							<option value='1' <?= $c['firstday'] ? 'selected' : '' ?>>Yes</option>
							<option value='0' <?= !$c['firstday'] ? 'selected' : '' ?>>No</option>
						</select></td>
					</tr>
					<tr>
						<td>How many students are you willing to overload? Leave blank if you cannot</td>
						<td><input name="info-overload" type="text" size="4" value='<?= $c["overload"] ?>' /></td>
					</tr>
					<tr>
						<td>Will you accept students under 18?</td>
						<td><select name='info-age'>
							<option value='1' <?= $c['underage'] == 1 ? 'selected' : '' ?>>Yes</option>
							<option value='0' <?= $c['underage'] == 0 ? 'selected' : '' ?>>No</option>
							<option value='2' <?= $c['underage'] == 2 ? 'selected' : '' ?>>Yes, if accompanied by adult</option>
						</select></td>
					</tr>
				</table>

				<h3>Confirmation Email</h3>
				<p>When students register for an Experimental College class, they receive an email 
				confirming their registration. If there is any information for this class that should 
				be included in this email (e.g. supplies that they will need, clothing they should wear, 
				special directions to your class location, etc.) put it here and it will be included in 
				their confirmation email</p>
				<textarea name="info-email" rows="7" cols="60"><?= $c["text_email"] ?></textarea>
				<h3>Course Descriptions</h3>
				<h4>Short</h4>
				<p>Enter a short course description here. This description will be used for the catalog, 
				small blurbs, and any other length sensitive areas</p>
				<textarea name="info-short" rows="7" cols="60"><?= $c["text_short"] ?></textarea>
				<h4>Long</h4>
				<p>Enter a full description for the course here. There is no limit to length. 
				This description will be used primarily on digital devices, like your course description page, where length is not an issue.</p>
				<p>If you leave this area blank, we will use the description above</p>
				<textarea name="info-long" rows="7" cols="60"><?= $c["text_long"] ?></textarea>


			</fieldset>
		</div>
	</section>

	<section>
		<div class="content">
			<div class='container'>
				<fieldset>
					<p id='submit-area'><input type="submit" value="Save" /><input type="submit" value="Review and Submit" /></p>
				</fieldset>
			</div>
		</div>
	</section>
</form>

<?php
	$db = new DB();
	//$db -> query("");




	tail()
?>