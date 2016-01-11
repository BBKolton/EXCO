<?php
//The returning Instructor Form. Instructors fill this out to tell us what
//courses they'll be teaching with us next quarter

require("../common.php");
session_start();
if (!verifyAdminOrRifInstructor($_GET['id'])) {
	error('Access Denied', 'You are not cleared to edit this page');
}

$c;
$i;
$s;
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

if (!isset($_GET['id'])) {
	error("Unspecified Rif Id", "You have to specify a rif id.");
}

head('<link href="/asuwxpcl/.assets/css/rif.css" rel="stylesheet">' . 
'<script src="/asuwxpcl/.assets/js/rif.js"></script>' .
'<script src="/asuwxpcl/.assets/js/toastr.js"></script>' . 
'<link href="/asuwxpcl/.assets/css/toastr.css" rel="stylesheet">', 1, 1);
?>	
<div class="container">
	<h2>Returning Instructor Form</h2>
	<div class="row form-group">
		<div class="col-xs-12">
			<ul class="nav nav-pills nav-justified thumbnail setup-panel form-ul">
				<li class="active"><a step="#step-1" class="step-1">
					<h4 class="list-group-item-heading">Course Information</h4>
					<p class="list-group-item-text">Name and Category</p>
				</a></li>
				<li><a step="#step-2" class="step-2">
					<h4 class="list-group-item-heading">Descriptions</h4>
					<p class="list-group-item-text">About your Course</p>
				</a></li>
				<li><a step="#step-4" class="step-4">
					<h4 class="list-group-item-heading">Sections</h4>
					<p class="list-group-item-text">Course Dates and Times</p>
				</a></li>
				<li><a step="#step-3" class="step-3">
					<h4 class="list-group-item-heading">Fees</h4>
					<p class="list-group-item-text">Items, Costs and Fees</p>
				</a></li>
				<li id='step-5-select'><a step="#step-5" class="step-5">
					<h4 class="list-group-item-heading">Review</h4>
					<p class="list-group-item-text">Submit your RIF</p>
				</a></li>
			</ul>
		</div>
	</div>
	<div>
		<form id="updateRif" data-toggle="formcache" name="proposalForm" method="post" action="/asuwxpcl/instructors/rifsubmit.php?id=<?= $_GET["id"] ?>" class="form-horizontal">
			<input type='hidden' name='farts' value='true' />
			<input type='hidden' name='update' value='true' />
			<div id="step-1" class="setup-content">
				<div class="col-xs-12">
					<div class="col-md-12 well">
						<div data-toggle="formcache" name="proposalForm" class="form-horizontal">
							<fieldset>
								<div class="form-group">
									<label for="name" class="col-md-4 control-label">Name</label>
									<div class="col-md-5">
										<input id="name" name="name" type="text" placeholder="name" value="<?= $c["name"] ?>" class="form-control input-md"/>
									</div>
								</div>
								<div class="form-group">
									<label for="category" class="col-md-4 control-label">Category</label>
									<div class="col-md-4">
										<select id="category" name="category" class="form-control">
										<?php for($j = 0; $j < count($GENRES); $j++) { ?>
											<option value="<?= $j ?>" <?= $c["category"] == $j ? "selected" : "" ?>><?= $GENRES[$j] ?></option>
										<?php }	?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="size" class="col-md-4 control-label">Max Enrollment</label>
									<div class="col-md-4">
										<input id="size" name="size" type="text" placeholder="size" value="<?= $c["size"] ?>" class="form-control"/>
									</div>
								</div>

								<div class="form-group">
									<label for="overload" class="col-md-4 control-label">Underage Students</label>
									<div class="col-md-4">
										<select id='underage' name='underage' class='form-control'>
											<option value='1' <?= $c['underage'] == 1 ? 'selected' : '' ?>>Yes</option>
											<option value='0' <?= $c['underage'] == 0 ? 'selected' : '' ?>>No</option>
											<option value='2' <?= $c['underage'] == 2 ? 'selected' : '' ?>>Yes, if accompanied by adult</option>
										</select>
										<span class="help-block">Will you accept students under the age of 18?</span>
									</div>
								</div>

								<div class="form-group">
									<label for="overload" class="col-md-4 control-label">Late Students</label>
									<div class="col-md-4">
										<select id="firstday" name="firstday" class="form-control">
											<option value="1" <?= $c['firstday'] == 1 ? 'selected="selected"' : '' ?>>Yes</option>
											<option value="0" <?= $c['firstday'] == 1 ? '' : 'selected="selected"' ?>>No</option>
										</select>
										<span class="help-block">Will you accept students after the first day of class?</span>
									</div>
								</div>

								<div class="form-group">
									<label for="overload" class="col-md-4 control-label">Overload</label>
									<div class="col-md-4">
										<input id="overload" name="overload" class="form-control" value="<?= $c["overload"] ?>" selected="selected" />
										<span class="help-block">How many students are you willing to overload? Leave blank if you cannot overload your course</span>
									</div>
								</div>

								<div class="form-group">
									<label for="save" class="col-md-4 control-label"></label><br/>
									<div class="col-md-4"></div>
									<div class="col-md-4">
										<button type="submit" name="save btn-primary" class="btn btn-primary form-button">Save</button>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
			<div id="step-2" class="row setup-content">
				<div data-toggle="formcache" name="description" class="form-horizontal">
					<div class="col-xs-12">
						<div class="col-md-12 well">
							<div class="form-horizontal">
								<fieldset>
									<div class="form-group">
										<label for="text_short" class="col-md-4 control-label">Short Description</label>
										<div class="col-md-8">
											<textarea id="text_short" name="text_short" placeholder="Please write a quick description of your course" class="form-control"><?= $c['text_short'] ?></textarea><span class="help-block">Please keep your description under 600 characters. This description will be used for the galley and any other size-restricted content. Do not use formatting for this description</span>
										</div>
									</div>
									<div class="form-group">
										<label for="text_long" class="col-md-4 control-label">Long Description</label>
										<div class="col-md-8">
											<textarea id="text_long" name="text_long" placeholder="" class="form-control ckEditor"><?= $c['text_long'] ?></textarea><span class="help-block">Write as much as you want about your class here. There is no length limit for your long description. You may use formatting of any kind in this description</span>
										</div>
									</div>
									<div class="form-group">
										<label for="text_email" class="col-md-4 control-label">Email Messsage</label>
										<div class="col-md-8">
											<textarea id="text_email" name="text_email" placeholder="Provide any additional information that will be sent in the confirmation email" class="form-control ckEditor"><?= $c['text_email'] ?></textarea><span class="help-block">When students register for an Experimental College class, they receive an email confirming their registration. If there is any information for this class that should be included in this email (e.g. supplies that they will need, clothing they should wear, special directions to your class location, etc.) put it here and it will be included in their confirmation email</span>
										</div>
									</div>
									<div class="form-group">
										<label for="save" class="col-md-4 control-label"></label>
										<div class="col-md-4">
											<button name="save" type="submit" class="btn btn-primary btn-default form-button">Save</button>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div>
		<form id="updateItems" data-toggle="formcache" name="proposalForm" method="post" action="/asuwxpcl/instructors/rifsubmit.php?id=<?= $_GET["id"] ?>" class="form-horizontal">
			<div id="step-3" class="row setup-content">
				<div class="col-xs-12">
					<div class="col-md-12 well">
						<div data-toggle="formcache" name="contactForm" class="form-horizontal">
							<h3 class="text-center">Items</h3><span class="help-block">Please list all items you will purchase to teach your course </span>
							<div class="form-horizontal">
								<fieldset id='itemArea'>
								<?php if (!empty($i)) { ?>
									<div class='col-md-4 hidden-xs hidden-sm'>
										<label>Name</label>
									</div>
									<div class='col-md-4 hidden-xs hidden-sm'>
										<label>Cost</label>
									</div>
									<div class='col-md-4 hidden-xs hidden-sm'>
										<label>Quantity</label>
									</div>
								<?php } ?>
										
									<?php foreach ($i as $item) { ?>
										<div class='itemSection'>
											<input type='hidden' name='id' value='<?= $item['id'] ?>' />
											<div class='row margin-fix'>
												<div class='col-md-4 col-xs-12'>
													<div class="form-group">
														<label for="name" class="col-md-4 control-label hidden-md hidden-lg">Name</label>
														<div class="col-xs-12">
															<div class='input-group'>
																<span class='input-group-btn'>
																	<button class='btn btn-danger removeItem' type='button'>
																		<span class='glyphicon glyphicon-remove'></span>
																	</button>
																</span>
																<input id="name" name="name" type="text" placeholder="name" value="<?= $item["name"] ?>" class="name <?= $item['id'] ?> form-control"/>
															</div>
														</div>
													</div>
												</div>

												<div class='col-md-4 col-xs-12'>
													<div class="form-group">
														<label for="cost" class="col-md-4 control-label hidden-md hidden-lg">Cost</label>
														<div class="col-xs-12">
															<input id="cost" name="cost" type="text" placeholder="cost" value="<?= $item["cost"] ?>" class="cost <?= $item['id'] ?> form-control"/>
														</div>
													</div>
												</div>

												<div class='col-md-4 col-xs-12'>
													<div class="form-group">
														<label for="quantity" class="col-md-4 control-label hidden-md hidden-lg">Quantity</label>
														<div class="col-xs-12">
															<input id="quantity" name="quantity" type="text" placeholder="quantity" value="<?= $item["quantity"] ?>" class="quantity <?= $item['id'] ?> form-control"/>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php } ?>

								</fieldset>

								<div class="form-group">
									<div class="col-md-8 hidden-xs hidden-sm"></div>
									<div class="col-md-4">
										<button name="newItem" type="button" id='newItem' class="btn btn-primary form-button">+ New Item</button>
									</div>
								</div>

							</div>
							<div class="form-horizontal">
								<fieldset id='facilities'>
									<h3 class="text-center">Facilities</h3>
									<div class="form-group">
										<label for="room_rate" class="col-md-4 control-label">Room Rate</label>
										<div class="col-md-4 col-xs-12">
											<div class='input-group'>
												<span class='input-group-addon'>$</span>
												<input id="room_rate" name="room_rate" type="text" placeholder="Hourly rate in full dollars" value="<?= $c["room_rate"] ?>" class="form-control"/>
											</div>
											<span class="help-block">View the <a href='/asuwxpcl/.assets/docs/FacilitiesCosts.pdf'>rental rates</a></span>
										</div>
									</div>
									<div class="form-group">
										<label for="room_hours" class="col-md-4 control-label">Instruction Hours</label>
										<div class="col-md-4 col-xs-12">
											<input id="room_hours" name="room_hours" type="text" placeholder="Number of instructional hours per course" value="<?= $c["room_hours"] ?>" class="form-control"/>
											<span class="help-block"></span>
										</div>
									</div>
									<div class='form-group'>
										<label for="text_facilities" class="col-md-4 control-label">Facilities Information</label>
										<div class="col-md-8">
											<textarea id="text_facilities" name="text_facilities" placeholder="What do you require for your room facilities?" class="form-control"><?= $c['text_facilities'] ?></textarea><span class="help-block">Let us know if the room will need any special accomodations, such as whiteboards, computers, projectors, or more</span>
										</div>
									</div>


									<h3 class="text-center">Fees</h3>
									<div class="form-group">
										<label for="fee_uw" class="col-md-4 control-label">UW Student Fee</label>
										<div class="col-md-4 col-xs-12">
											<div class='input-group'>
												<span class='input-group-addon'>$</span>
												<input id="fee_uw" name="fee_uw" type="text" placeholder="Hourly rate in full dollars" value="<?= $c["fee_uw"] ?>" class="form-control"/>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="fee_gen" class="col-md-4 control-label">General Fee</label>
										<div class="col-md-4 col-xs-12">
											<div class='input-group'>
												<span class='input-group-addon'>$</span>
												<input id="fee_gen" name="fee_gen" type="text" placeholder="Hourly rate in full dollars" value="<?= $c["fee_gen"] ?>" class="form-control"/>
											</div>
										</div>
									</div>








									<div class="form-group">
										<label for="save" class="col-md-4 control-label"></label><br/>
										<div class="col-md-4"></div>
										<div class="col-md-4">
											<button name="save" type="submit" class="btn btn-primary btn-default form-button">Save</button>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div>
		<form id="sections" data-toggle="formcache" name="proposalForm" method="post" action="/asuwxpcl/instructors/rifsubmit.php?id=<?= $_GET["id"] ?>" class="form-horizontal">
			<input type='hidden' name='addSection' value='true' />
			<div id="step-4" class="row setup-content">
				<div class="col-xs-12">
					<div class="col-md-8 well">
						<div class="form-horizontal">
							<h4 class="text-center">Add Section</h4>
							<fieldset>
								<item></item>
								<div class="form-group">
									<label for="startTime" class="col-md-2 control-label">Start Time</label>
									<div class="col-md-8">
										<input id="startTime" name="startTime" type="text" class="form-control input-md"/><span class="help-block">In form 9:00pm or similar</span>
									</div>
								</div>
								<div class="form-group">
									<label for="endTime" class="col-md-2 control-label">End Time</label>
									<div class="col-md-8">
										<input id="endTime" name="endTime" type="text" class="form-control input-md"/><span class="help-block">In form 9:00pm or similar</span>
									</div>
								</div>
								<div class="form-group">
									<div class='row margin-fix'>
										<label for="dates" class="col-md-2 control-label">Class Dates</label>
										<div class="col-md-8">

											<div class='input-group'>
												<input id="dates" name="dates" min="0" type="text" placeholder="Tues. 1/12 - 1/26" class="form-control datepicker input-md"/>
												<span class='input-group-btn'><button class='btn btn-primary' type='button' id='toggleDatepicker'><span class='glyphicon glyphicon-calendar'></span> Open Calendar</button></span>
											</div>

										</div>
									</div>
									<div class='row margin-fix'>
										<div class='col-md-2'>&nbsp;</div>
										<div class="col-md-8">	
											<span class="help-block"><b>Use one of the following formats:</b><br>
											&nbsp; Tues 1/12<br>
											&nbsp; Tues 1/12 - 1/26<br>
											&nbsp; Tues, Thurs 1/12 - 1/28<br>
											&nbsp; Tues 1/26, Weds 1/27, Thurs 1/28<br>
											<b>You may need to manually edit your dates to match a format</b></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="addItem" class="col-md-2 control-label"></label>
									<div class="col-md-4">
										<button id="addItem" name="addItem" type="submit" class="btn btn-primary btn-default">+ Save Section</button>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="col-md-4">
						<div id="itemsList">
							<h3>All Sections</h3>
							<?php if (empty($s)) { ?>
								<p>You have no saved sections yet</p>
							<?php } else { ?>
								<ul>
								<?php foreach ($s as $sec) { ?>
									
									<li><a class='removeSection' section='<?= $sec["id"] ?>'><button class='btn btn-danger' type='button'><span class='glyphicon glyphicon-remove'></span></button></a> <?= $sec['time_start'] . ' - ' . $sec['time_end'] . ', ' . $sec['days'] ?></li>

								<?php } ?>
								</ul>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div>
		<form data-toggle="formcache" name="proposalForm" method="get" action="/proposal/submit/{proposal.id}" class="form-horizontal">
			<div id="step-5" class="row setup-content">
				<div class="col-xs-12">
					<div class="col-md-12">
						<div class="well">
							<div class="form-horizontal">
								<h2>Review</h2>

								<div id='loadingReview'>
									<img id='loadingTriangle' src='/asuwxpcl/.assets/img/loadingTriangle.gif'>
								</div>

								<div id='rifReview'>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
