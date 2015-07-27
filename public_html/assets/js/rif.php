<?php 
	//Turns all classes of datepicker on a page into the datepicker. 
	
	function datePickerConfig($minDate, $maxDate) { ?>
	<script>
		"use strict";
		window.addEventListener("load", function() {
			createDatePicker();
		});

		function createDatePicker() {
			$(".datepicker").multiDatesPicker({
				maxPicks: 999,
				showButtonPanel: true,
				dateFormat: 'mm/dd',
				numberOfMonths: 3,
				altField: '#dates',
				minDate: new Date(<?= $minDate ?>),
				maxDate: new Date(<?= $maxDate ?>)
			});
		}
	</script>
	<?php }
?>