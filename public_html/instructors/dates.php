<?php
require('../common.php');

session_start();
if ($_SESSION['permissions'] < 2) {
	error('Access Denied', 'You are not an instructor');
}

head();

?>

<section class='content'>
	<div class='container'>
		<h2>Important Instructor Dates</h2>
		<h3>Fall 2015 </h3>
		<p>August 23 // Online registration begins </p>
		<p>September 23 // All other registration begins </p>
		<p>October 2 // Summer facilities invoices due / New Course Proposals due </p>
		<p>October 12 – December 4 // Fall Classes </p>
		<p>October 16 // Winter Quarter RIFs due October 28-30 // Winter Galleys </p>
		<p>January 14 // Fall facilities invoices due There are no classes on: November 11 (Veterans Day), November 26-27 (Thanksgiving Break) </p>

		<h3>Winter 2016 </h3>
		<p>December 6 // Online registration begins </p>
		<p>January 3 // All other registration begins </p>
		<p>January 8 // Fall facilities invoices due / New Course Proposals due </p>
		<p>January 18 - March 21 // Winter Classes </p>
		<p>January 21 // Spring Quarter RIFs due February 2-4 // Spring Galleys </p>
		<p>April 1 // Winter facilities invoices due There are no classes on: January 18 (Martin Luther King Day), February 15 (President's Day) </p>

		<h3>Spring 2016 </h3>
		<p>March 13 // Online registration begins </p>
		<p>March 28 // All other registration begins </p>
		<p>April 5 // Winter facilities invoices due / New Course Proposals due </p>
		<p>April 15 - June 8 // Spring Classes </p>
		<p>April 12 // Summer Quarter RIFs due </p>
		<p>April 24 - 26 // Summer Galleys </p>
		<p>June 24 // Spring facilities invoices due There are no classes on: May 30 (Memorial Day) </p>

		<h3>Summer 2016 </h3>
		<p>June 5 // Online registration begins </p>
		<p>June 20 // All other registration begins </p>
		<p>June 24 // Spring facilities invoices due / New Course Proposals due </p>
		<p>June 20 - August 23 // Summer Classes </p>
		<p>July 8 // Fall Quarter RIFs due </p>
		<p>July 20-22 // Fall Galleys </p>
		<p>October 4 // Summer facilities invoices due </p>
		<p>There are no classes on: July 3-4 (Independence Day Weekend)</p>

	</div>
</section>


<?php
tail();