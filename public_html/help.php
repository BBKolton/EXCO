<?php 
	require("common.php");
	head('<link href=".assets/css/help.css" type="text/css" rel="stylesheet" />' . 
	     "<script type='text/javascript' src='/asuwecwb/.assets/js/help.js'></script>"); 

	$faq = ["How can I register for a class?" => 
				"To register online, you can create a user account or register as a guest. User accounts save your registration information quarter by quarter, making the registration process simpler. If you have registered for a course using your account, you can also access location information about the class through our website. You can search our online course catalog and click on the “Register” button to register for a class. </p><p>
				To register over the phone, call (206) 68-LEARN (206.685.3276) to speak to a staff member. The Experimental College office is open 10am to 5pm, Mondays through Fridays, except on public holidays. </p><p>
				To register by mail, please fill out the registration form in the back of the Experimental College Catalog, and mail it, along with the Registration Fee payment, to Box 352238 SAO 21, University of Washington, Seattle, WA 98195- 2238. Registration fee checks should be made out to the University of Washington. </p><p>
				To register in person, please come into our office. We are located in the ASUW Suite, Room 131J in the Husky Union Building on the University of Washington campus: 4001 Northeast Stevens Way Seattle, WA 98195. </p><p>
				After you register you will receive a confirmation e-mail from the Experimental College, which acts as a receipt and includes important course information, included the class location.",
	        "What are the fees that I pay?" => 
	        	"As an Experimental College student you pay either a $5 for UW or $12 for General Public registration fee directly to the Experimental College at the time of registration. This money goes straight to the Experimental College and is a primary source of revenue. Then, on the first day of class, you pay by cash or check to the instructor for the course fee, which goes entirely to the instructor. The online registration system can only charge for the $5 or $12 amount and will at no point process both the registration and course fee. </p><p>
	        	Registration fees are non-refundable unless a class is cancelled by the Experimental College. Course fee refunds after a class has begun must be negotiated with the Instructor of the course.",
	        "Can I get a refund?" => 
	        	"Registration fees are non-refundable unless an Experimental College course is cancelled. If a course is cancelled we will call you to ask if you'd like a refund or would like to be transferred into another Experimental College class happening in the current quarter. If you ask for a refund, the registration fee amount will be returned to your card within approximately three weeks. </p><p>
	        	You may transfer your registration fee from one class to another up until the day before both classes start. Please call our office if you'd like to transfer your registration. </p><p> 
	        	Instructors decided independently how they would like to structure their refund policy. Course fee refunds after the start of a course must be negotiated with the instructor.",
	        "What are the classes offered?" => 
	        	"Experimental College classes are offered on the University of Washington campus and in neighborhoods around the greater Seattle area. The general location of the class is listed on the course page and in the Experimental College catalog (for example: “University District”, “Wallingford”, or “Capitol Hill”). </p><p>
				The exact location of the course is disclosed only after registration, to ensure that students arriving at class have already registered. You can view the exact location of the class in the confirmation e-mail you receive after registration.", 
			"Who are the instructors?" => 
				"Experimental College courses are taught by UW Students and community members selected by the Experimental College staff. Our instructors are experienced in their respective subjects and passionate about education. Some have been teaching with the Experimental College for over a decade. Learn more about individual Experimental College instructors by clicking on “Catalog” and “by Instructor” at the top of this page.", 
			"Can I register for a class after it starts or if it is full?" => 
				"If you wish to register for a class that has already started or is full, you can call the Experimental College office. A staff member will check to see if the instructor accepts students after the first day of class or if the instructor can increase the enrollment of the class.", 
			"What happens with the personal information given during registration?" => 
				"Any personal information given during registration is obliterated from one quarter to the next. Within the quarter, your phone number and e-mail address will show up on the roster of the instructor who teaches the class you’ve registered for. Instructors will use these to contact you and your classmates regarding specific class information. Your phone number, e- mail address, and mailing address are visible to Experimental College staff only, which we may use to contact you in the case of a class being cancelled, or if we have questions about your registration.", 
			"When does the Experimental College catalog go online?/ When does the Experimental College catalog come out in print?" => 
				"The new Experimental College catalog goes online and comes out in print at the end of each University of Washington quarter usually in early September for Fall, mid December for Winter, mid to late March for Spring, and early June for Summer.", 
			"Why is only the general location of my class displayed?" => 
				"The Experimental College requires students to register for all of its classes as registration fees are a primary source of revenue. To prevent students from showing up without registering, the specific location of each class is withheld until after registration.", 
			"What qualifies someone for the student discount?" => 
				"The student discount is for tuition paying University of Washington students only, because the ASUW Experimental College is subsidized by the Student Activities Fees paid with quarterly tuition. Unfortunately, we can no longer offer discounts to UW Alumni as we no longer receive funding from that source.", 
			"Who do I make checks payable to?" => 
				"Checks for the registration fee should be made out to the University of Washington, NOT the Experimental College. Course fees checks should be made payable to the instructor.", 
			"What methods can I use to pay my course fee on the first day of class?" => 
				"Course fees can be paid by cash or check unless otherwise instructed by the instructor.", 
			"What if I don’t receive my confirmation e-mail?" => 
				"If you don’t receive a confirmation e-mail from the Experimental College shortly after you register for a class, check to make sure it hasn’t landed in your junk or spam mail folder accidentally. If you still can’t find it, please call our office immediately so that we can pass on the pertinent information that you’ll need to know about the class.", 
			"What if my confirmation e-mail does not have the exact location?" => 
				"If your confirmation e-mail doesn’t have an exact location, please call our office immediately. A staff member will be able to provide you with the exact location of the class.", 
			"How do I find out about the supplies/materials/clothing I need for a class?" =>
				"Some classes do indeed require specific supplies or materials. In most cases these materials will be mentioned either in the course description of the class in the Experimental College catalog, or the instructor will send an e-mail about the materials required. However, if you don’t receive an e-mail or have questions about clothing, please call our office. A staff member will either be able to answer any questions you have, or direct you to the instructor of the course.", 
			"What are the parking rates on campus?" => 
				"For up to date information, please visit <a href='http://www.washington.edu/facilities/transportation/park'>Transportation Services</a>. ", 
			"What are the parking rates nearby campus?" => 
				"There is street parking nearby campus. The parking fee is $4 per hour but you may only pay up to 2 hours max. Most street parking is free after 6PM M-Sat and free all day on Sundays. There is street parking on University Way, Brooklyn Ave, 41st St., and 12th Ave. However, please note that as there is currently a lot of construction projects going on in that vicinity, street parking has become even more limited.", 
			"Where do I park for evening classes/weekends classes?" => 
				"Parking on campus is free from 9PM-6AM on weekdays in unrestricted lots. Street parking near campus is free after 6PM from M-Sat and free on Sundays.", 
			"Where is the UW campus?" => 
				"The campus is located north east of downtown seattle, just east of the Wallingford neighborhood, north of the shipping canal. <a href = 'https://www.google.com/maps/place/47%C2%B039'21.9%22N+122%C2%B018'33.3%22W/@47.65609,-122.309238,17z/data=!3m1!4b1!4m2!3m1!1s0x0:0x0' >Google Maps Link</a>. </p><p>
				Exit at 169 on I-5. From North: Turn left onto 45th St. at the traffic light after exiting. Keep going straight on 45th St. The main entrance to the UW campus will be on your right. </p><p>
				Exit at 169 on I-5. From South, turn right onto 45th St. at the traffic light after exiting. Keep going straight on 45th St. The main entrance to the UW campus will be on your right.", 
			"How can I find the building my class is in on campus?" => 
				"The <a href='http://www.washington.edu/maps/'>UW MAPS</a> page has great information on specific building locations ", 
			"If I missed my class will it be offered next quarter?" => 
				"Many Experimental College classes return quarter after quarter. However, it is up to the instructor whether he/she wishes to offer the class again.", 
			"It turns out I can’t make it to the class I signed up for. What do I do?" => 
				"If you can’t make it to the class you registered for, please call our office to let us know so that we can take you off the class roster. The registration fee you already paid is non-refundable, so if you cancel your registration you will forfeit this amount. However, you do have the option to transfer your registration to another Experimental College class during the current quarter that has not begun yet. If you choose to do so, a staff member will transfer you from the original class to the new class, and you will receive an e-mail confirming the transfer. Please keep in mind that registrations cannot be “saved” for other quarters.", 
			"Where can I get updates about classes?" => 
				"We have an e-mail newsletter that you can sign up for to receive e-mails twice per quarter letting you know that registration is open. We'll also update you on which classes are still open for registration about halfway through the quarter.", 
			"I have a complaint about a class. What can I do?" => 
				"Student satisifaction and safety are our top priorities. If you have a complaint regarding your class please feel free to e-mail office@exco.org or call 206-543-4375 and the staff will be sure to process the complaint. </p><p>
				You can also use the anonymous <a href='/asuwecwb/feedback.php'>feedback page</a> for anything related to your experience with EXCO. ", 
			"Who can work for the ASUW Experimental College?" => 
				"Any current student of the University of Washington (both graduate and undergrated students) can apply to work at the Experimental College in one of eight staff positions: Director, Assistant Director, Accountant, Catalog Editor, Facilities Coordinator, Office Assistant, Public Relations Coordinator, and Webmaster. To check for current job listings you can visit jobs.asuw.org to see if we are currently hiring.", 
			];

	?>

	<section class="title">
		<div class="jumbotron">
			<div class="container">
				<h1>Frequently Asked Questions</h1>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-md-8 faq">
					<?php foreach ($faq as $q => $a) { ?>
						<a name="<?= $q ?>"><h2><?= $q ?></h2></a>
						<p><?= $a ?></p>
					<?php } ?>
				</div>
				<div class="col-md-4 hidden-xs hidden-sm" id="sidenavbar">
					<h3>Navigation</h3>
					<?php foreach ($faq as $q => $a) { ?>
						<a href="#<?= $q ?>"><h4><?= $q ?></h4></a>
					<?php } ?>

				</div>
			</div>
		</div>
	</section>

	<?php tail();

?>