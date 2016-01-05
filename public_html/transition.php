<?php
/*
require('common.php');


$db = new DB();
$rows = $db->select("SELECT * FROM lightbulb2_fall2015.lb_rifs_galleys g
				JOIN lightbulb2_fall2015.lb_rifs_course_form f ON f.id = g.id
				JOIN lightbulb2_fall2015.lb_rifs_processed rs on rs.id = g.id
				WHERE paid = 1 ");


foreach ($rows as $row) {

	echo "<p><h1>ROW</h1>";
	var_dump($row);
	echo"</p>";

	$galley = unserialize($row["galley"]);
	$rif = unserialize($row["form"]);
	$galley['title'] = preg_replace("/'/", "''", $galley['title']);
	$galley['summary'] = preg_replace("/'/", "''", $galley['summary']);
	$galley['additionalSectionInfo'] = preg_replace("/'/", "/''/", $galley['additionalSectionInfo']);


	var_dump($galley);

	echo "<p>" . "INSERT INTO exco_2016_winter.courses (status, name, instructor_id, description, email) VALUES(1, ". $db->quote($galley["title"]) . ", 0, " . $db->quote($galley['summary']) . "" . $db->quote($rif['confirmationEmail']) . '</p>';

		$cat;

		switch ($rif['category']) {
			case "10":
				$cat = 0;
				break;
			case "14":
				$cat = 1;
				break;
			case "15":
				$cat = 2;
				break;
			case "16":
				$cat = 3;
				break;
			case "17":
				$cat = 4;
				break;
			case "19":
				$cat = 5;
				break;
			case "20":
				$cat = 6;
				break;
			case "29":
				$cat = 7;
				break;
			case "24":
				$cat = 8;
				break;
			case "25":
				$cat = 9;
				break;
			case "30":
				$cat = 10;
				break;
			case "31":
				$cat = 11;
				break;
			case "26":
				$cat = 12;
				break;
			case "27":
				$cat = 13;
				break;
			case "13":
				$cat = 14;
				break;
			case "28":
				$cat = 15;
				break;
		}


	$db->query("INSERT INTO exco_2016_winter.courses (status, type, name, instructor_id, description, email) 
	            VALUES(1, " . $cat . ", ". $db->quote($galley["title"]) . ",
	            0, 
	            " . $db->quote($galley['summary']) . ", 
	            " . $db->quote($rif['confirmationEmail']) . ")");
	$id = $db->getInsertedId();

	//END COURSE INSERT SECTION


	//SECTION INSERT SECTION
	preg_match_all("(\S{1,2}:.{2}(am|pm|AM|PM)\s{0,1}-\s{0,1}.{1,2}:.{2}(am|pm|AM|PM))", $galley['times'], $times);
	preg_match_all("/((Sun\.|Mon\.|Tues\.|Weds\.|Thurs\.|Fri\.|Sat\.) ){0,1}\w{1,2}\/\w{1,2}( {0,1}\- {0,1}\w{1,2}\/\w{1,2}){0,1},{0,1}/i",
		$galley['dates'], $dates);

	$times = $times[0];
	$dates = $dates[0];

	echo "<h4>TIMES</h4><p>";
	var_dump($times);
	var_dump($dates);
	echo "</p>";

	
	for ($i = 0; $i < count($times); $i++) { 

		$firstdatetime = 0;

		//MATCH FIRST DATES AND TIMES
		preg_match_all("/\w{1,2}\/\w{1,2}( {0,1})/", $dates[$i], $firstdates);
		preg_match_all("/(\w{1,2}:.{2}(am|pm|AM|PM)\s{0,1})/", $times[0][$i], $firsttimes);


		$firstDateTime = $firstdates[0][0];
		echo "<h3>DATETIMETHING FIRST DAY</h3>";
		var_dump($dates);
		echo "</p>";





		$db->query("INSERT INTO sections (course_id,
		                                  section,
		                                  status,
		                                  start_day,
		                                  days,
		                                  times,
		                                  size,
		                                  fee_gen,
		                                  fee_uw,
		                                  facility_id,
		                                  location_gen,
		                                  location_spec)
		            VALUES (" . $id . ",
		                    " . $db->quote($i + 1) . ",
		                    1,
		                    " . $db->quote("2016/" . $firstdates[0][0]) . ",
		                    " . $db->quote($dates[$i]) . ",
		                    " . $db->quote($times[$i]) . ",
		                    " . $db->quote($galley['enrollment']) . ",
		                    " . $db->quote(preg_replace('.\$.', "",$galley['general'])) . ",
		                    " . $db->quote(preg_replace('.\$.',"",$galley['student'])) . ",
		                    0,
		                    " . $db->quote($rif['general-location']) . ",
		                    " . $db->quote($rif["address"]) . ")");

	}


} 
*/