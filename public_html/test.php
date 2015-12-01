<?php
//The returning Instructor Form. Instructors fill this out to tell us what
//courses they'll be teaching with us next quarter

require("common.php");
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

head('<link href="/asuwecwb/.assets/css/rif.css" rel="stylesheet">' . 
'<script src="/asuwecwb/.assets/js/rif.js"></script>' .
'<script src="/asuwecwb/.assets/js/toastr.js"></script>' . 
'<link href="/asuwecwb/.assets/css/toastr.css" rel="stylesheet">', 1, 1);
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
				<li><a step="#step-5" class="step-5">
					<h4 class="list-group-item-heading">Facilities</h4>
					<p class="list-group-item-text">Submit your Proposal</p>
				</a></li>
			</ul>
		</div>
	</div>
	<div>
		<form id="updateProposal" data-toggle="formcache" name="proposalForm" method="post" action="/proposals/{proposal.id}" class="form-horizontal">
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
										<select id='underage' name='info-age' class='form-control'>
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
			<div id="step-3" class="row setup-content">
				<div class="col-xs-12">
					<div class="col-md-12 well">
						<div data-toggle="formcache" name="contactForm" class="form-horizontal">
							<h4 class="text-center">Items</h4><span class="help-block">Please list all items you will be purchasing to host your class</span>
							<div class="form-horizontal">
								<fieldset>
									<div class='col-md-4 hidden-xs hidden-sm'>
										<label>Name</label>
									</div>
									<div class='col-md-4 hidden-xs hidden-sm'>
										<label>Cost</label>
									</div>
									<div class='col-md-4 hidden-xs hidden-sm'>
										<label>Quantity</label>
									</div>
										
									<?php foreach ($i as $item) { ?>
										<div class='itemSection'>
											<div class='col-md-4 col-xs-12'>
												<div class="form-group">
													<label for="name" class="col-md-4 control-label hidden-md hidden-lg">Name</label>
													<div class="col-xs-12">
														<div class='input-group'>
															<span class='input-group-btn'>
																<button class='btn btn-danger'>
																	<span class='glyphicon glyphicon-remove'></span>
																</button>
															</span>
															<input id="name" name="name" type="text" placeholder="name" value="<?= $item["name"] ?>" class="form-control"/>
														</div>
													</div>
												</div>
											</div>

											<div class='col-md-4 col-xs-12'>
												<div class="form-group">
													<label for="cost" class="col-md-4 control-label hidden-md hidden-lg">Cost</label>
													<div class="col-xs-12">
														<input id="cost" name="cost" type="text" placeholder="cost" value="<?= $item["cost"] ?>" class="form-control"/>
													</div>
												</div>
											</div>

											<div class='col-md-4 col-xs-12'>
												<div class="form-group">
													<label for="quantity" class="col-md-4 control-label hidden-md hidden-lg">Quantity</label>
													<div class="col-xs-12">
														<input id="quantity" name="quantity" type="text" placeholder="quantity" value="<?= $item["quantity"] ?>" class="form-control"/>
													</div>
												</div>
											</div>
										</div>
									<?php } ?>

									<div class="form-group">
										<label for="newItem" class="col-md-4 control-label"></label><br/>
										<div class="col-md-4"></div>
										<div class="col-md-4">
											<button name="newItem" type="button" id='newItem' class="btn btn-default form-button">+ New Item</button>
										</div>
									</div>

								</fieldset>
							</div>
							<div class="form-horizontal">
								<fieldset>
									<h4 class="text-center">Facilities</h4>
									<div class='form-group'>
										<label for="text_facilities" class="col-md-4 control-label">Additional Facilities</label>
										<div class="col-md-8">
											<textarea id="text_facilities" name="text_facilities" placeholder="Provide any additional information that will be sent in the confirmation email" class="form-control"><?= $c['text_facilities'] ?></textarea><span class="help-block">Please let us know anything additional about the facilities you require</span>
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
		<form id="createItem" data-toggle="formcache" name="proposalForm" method="post" action="/items/new" class="form-horizontal">
			<div id="step-4" class="row setup-content">
				<div class="col-xs-12">
					<div class="col-md-9 well">
						<div class="form-horizontal">
							<h4 class="text-center">Add Item</h4>
							<fieldset>
								<item></item>
								<div class="form-group">
									<label for="ItemName" class="col-md-4 control-label">Name</label>
									<div class="col-md-4">
										<input id="ItemName" name="ItemName" type="text" placeholder="..." class="form-control input-md"/>
										<input id="ItemProposalId" name="ProposalId" type="hidden" value="{proposal.id}"/>
									</div>
								</div>
								<div class="form-group">
									<label for="Group" class="col-md-4 control-label">Group</label>
									<div class="col-md-4">
										<input id="Group" name="Group" type="text" placeholder="..." class="form-control input-md"/><span class="help-block">Group by priority of needed funds, or by components (e.g. Software, Hardware)</span>
									</div>
								</div>
								<div class="form-group">
									<label for="Quantity" class="col-md-4 control-label">Quantity</label>
									<div class="col-md-4">
										<input id="Quantity" name="Quantity" min="0" type="number" placeholder="1,2,3" class="form-control input-md"/><span class="help-block">Number of units desired</span>
									</div>
								</div>
								<div class="form-group">
									<label for="Price" class="col-md-4 control-label">Price</label>
									<div class="col-md-4">
										<input id="Price" name="Price" min="0" type="number" placeholder="$" class="form-control input-md"/><span class="help-block">Cost per unit (exclude dollar sign)</span>
									</div>
								</div>
								<div class="form-group">
									<label for="Description" class="col-md-4 control-label">Description</label>
									<div class="col-md-8">
										<textarea id="Description" name="Description" placeholder="Describe the item" class="form-control"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="Justification" class="col-md-4 control-label">Justification</label>
									<div class="col-md-8">
										<textarea id="Justification" name="Justification" placeholder="Justify why you need it" class="form-control"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="addItem" class="col-md-4 control-label"></label>
									<div class="col-md-4">
										<button id="addItem" name="addItem" type="submit" class="btn btn-primary btn-default">+Add Item</button>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="col-md-3">
						<div id="itemsList">
							<h3>All Items</h3>
							<p class="subtitle">Click to view/edit             </p>
							<ol>
								<li> <a href="/item/{item.id}">{item.ItemName}</a><a href="/item/{item.id}">(No Item Name)</a></li>
							</ol>
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
								<h2>Signatures</h2>
								<p><a href="/proposals/{proposal.id}">View Draft</a></p>
								<p>In order to finish the proposal and submit it to the committee, all involved persons listed under the contact tab must first virtually sign this page. Send this page's link to a contact, and have them log in with their listed NetID, then click 'Sign Proposal'.</p>
								<p>The signature process represents a depertment's pledge of general, physical, anmd financial support to the proposal. Signing the proposal also subjects the potentially funded proposal to our policies and procedures.</p>
								<h3>Status</h3>
								<div class="row">
									<div class="col-xs-12 col-md-4 text-center">
										<div class="sig-wrap {proposal.PrimarySignature == 0 ? &quot;disabled&quot; : &quot;&quot;}">
											<h4>Primary Author</h4>
											<p>
												<button type="button" class="btn btn-warning sign-btn">Sign Proposal</button><span class="unsigned">Unsiged</span>
											</p><span class="signed">Signed</span>
										</div>
									</div>
									<div class="col-xs-12 col-md-4 text-center">
										<div class="sig-wrap {proposal.DeanSignature == 0 ? &quot;disabled&quot; : &quot;&quot;}">
											<h4>Dean or Department Head</h4>
											<p>
												<button type="button" class="btn btn-warning sign-btn">Sign Proposal</button><span class="unsigned">Unsigned</span>
											</p><span class="signed">Signed</span>
										</div>
									</div>
									<div class="col-xs-12 col-md-4 text-center">
										<div class="sig-wrap {proposal.BudgetSignature == 0 ? &quot;disabled&quot; : &quot;&quot;}">
											<h4>Budget Director</h4>
											<p>
												<button type="button" class="btn btn-warning sign-btn">Sign Proposal</button><span class="unsigned">Unsigned</span>
											</p><span class="signed">Signed</span>
										</div>
									</div>
								</div>
								<p>Once all signatures have been collected, the Submit button will be available. Upon submitting your proposal, you will be unable to alter it. You will still be able to change specific items in case of price changes or slight quantity adjustments.</p>
								<div class="row">
									<div class="col-xs-4 col-xs-offset-8 col-md-2 col-md-offset-10"><a href="/proposal/submit/{proposal.id}">
											<button id="submitProposal" type="button" class="{proposal.BudgetSignature == 1 &amp;&amp; proposal.PrimarySignature == 1 &amp;&amp; proposal.DeanSignature == 1 ? &quot;&quot; : &quot;disabled&quot;} btn form-button">Submit Proposal</button></a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>