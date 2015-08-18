<?php 

require("common.php");

$db = new DB();

echo "SELECT courses.name,
			courses.description,
			courses.num_sections,
			courses.instructor_id,
			courses.id,
			sec.times,
			sec.days,
			sec.size,
			sec.fee_gen,
			sec.fee_uw,
			sec.location_gen,
			sec.section,
			sec.status,
			users.first_name,
			users.last_name
			FROM " . $DATABASE . ".courses courses
			JOIN " . $DATABASE . ".sections sec ON sec.course_id = courses.id
			JOIN " . $DATABASE . ".users users ON courses.instructor_id = users.id
			WHERE courses.id = " . $db -> quote($_GET["id"]);

	echo generate_salted_hash("temppass");

?>