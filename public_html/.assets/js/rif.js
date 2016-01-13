$(document).ready(function() {

	toastr.options = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": false,
		"progressBar": false,
		"positionClass": "toast-bottom-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}

	document.getElementById('toggleDatepicker').onclick = function() {
		$('.datepicker').data('daterangepicker').show();
	}


	var navListItems = $('ul.setup-panel li a');
	var allWells = $('.setup-content');

	allWells.hide();

	changeSection($('ul.setup-panel li a.step-1'));

	navListItems.click(function(){changeSection(this)});

	function changeSection(section) {
		var $target = $($(section).attr('step')),
				$item = $(section).closest('li');
		window.location.hash = $(section).attr('step').substring(6, 7);

		if (!$item.hasClass('disabled')) {
				navListItems.closest('li').removeClass('active');
				$item.addClass('active');
				allWells.hide();
				$target.show();
		}
	}


	$('#updateRif').submit(function(e) {
		e.preventDefault();
		for (i in CKEDITOR.instances) {
			CKEDITOR.instances[i].updateElement();
		}

		$.ajax({
			data: $('#updateRif').serialize(),
			method: 'POST',
			url: $('#updateRif').attr('action'),
			success: function(data) {
				toastr["success"]("Saved");
			}
		});
	})

	function bindSections() {
		$('.removeSection').click(function(e) {
			var section = $(this).attr('section');
			e.preventDefault();
			$.ajax({
				data: {section: section, removeSection: true},
				method: 'POST',
				url: $('#sections').attr('action'),
				success: function(data) {
					$('#itemsList').html(data);
					toastr["warning"]("Section Removed" );
					bindSections();
				}
			})
		})		
	}

	bindSections();

	$('#sections').submit(function(e){
		e.preventDefault();
		$.ajax({
			data: $('#sections').serialize(),
			method: 'POST',
			url: $('#sections').attr('action'),
			success: function(data) {
				$('#itemsList').html(data);
				toastr["success"]("Section Added" );
				$('#sections').find('input[type=text], textarea').val('');
				bindSections();
			}
		});
	});


	function sendItemData(callback) {
		var data = [];
		var items = $('.itemSection');
		for (var i = 0; i < items.length; i++) {
			var dat = {};
			dat.id = ($(items[i]).children('input').attr('value'));
			dat.name = $('.' + dat.id + '.name').val();
			dat.cost = $('.' + dat.id + '.cost').val();
			dat.quantity = $('.' + dat.id + '.quantity').val();
			data.push(dat);
		}


		$.ajax({
			data: {serialized: $('#facilities').serialize(), items: data},
			method: 'POST',
			url: $('#updateItems').attr('action'),
			success: function(data) {
				callback();
			}
		});
	}


	//?dsa
	$('#updateItems').submit(function(e){
		e.preventDefault();
		sendItemData(function() {
			toastr["success"]("Saved" );
		});
	});

	function bindRemoveButtons() {
		$('.removeItem').click(function(e) {
			$(this).closest('.itemSection').remove();
			$.ajax({
				method: 'POST',
				data: {
					id: $(this).closest('.itemSection').children('input').attr('value'),
					deleteItem: true
				},
				url: $('#updateItems').attr('action'),
				success: function(data) {
				}
			})
		})
	}

	bindRemoveButtons();

	$('#newItem').click(function(e) {
		sendItemData(function() {
			$.ajax({
				method: 'POST',
				data: {newItem: true},
				url: $('#updateItems').attr('action'),
				success: function(data) {							
					$('#itemArea').html(data);
					bindRemoveButtons();
				}
			})
		});
	})



	$('#step-5-select').on('click', function() {
		$('#rifReview').fadeOut(0, function() {
			$('#loadingReview').fadeIn(0, function() {
				$.ajax({
					method: 'GET',
					url: $('#updateRif').attr('action').replace('rifsubmit', 'rifreview'),
					success: function(data) {
						$('#loadingReview').delay(1000).fadeOut(500, function() {
							$('#rifReview').empty().append(data).fadeIn(500);
						});
					}
				})
			});
		});
	});

	bindCostUpdate();

	function bindCostUpdate() {
		$('.cost,.quantity,#room_rate,#room_hours,#expected').keyup(function() {
			computeFees();
		});
	}

	computeFees();

	function computeFees() {
		var total = 0;
		$('.itemSection').each(function() {
			var id = $(this).children('input').attr('value');
			total += $('.' + id + '.cost').val() * $('.' + id + '.quantity').val();
		})
		var hrs = $('#room_hours').val();
		var room = $('#room_rate').val() / $('#expected').val();
		$('#fee_uw_max').html(hrs * 7 + total + hrs * room);
		$('#fee_gen_max').html(hrs * 10 + total + hrs * room);

	}

	$('#text_short').keyup(function() {
		if ($(this).val().length > 600) {
			$('#shortAlert').css('color', 'red')
				.css('font-weight', 'bold');	
		} else {
			$('#shortAlert').css('color', 'black')
				.css('font-weight', 'normal')
		}

		$('#shortLength').html($('#text_short').val().length);
	})

});
