$(document).ready(function() {

	document.getElementById('toggleDatepicker').onclick = function() {
		$('.datepicker').data('daterangepicker').show();
	}




})


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
			console.log($('#updateRif').serialize())
			for (i in CKEDITOR.instances) {
				CKEDITOR.instances[i].updateElement();
			}

			$.ajax({
				data: $('#updateRif').serialize(),
				method: 'POST',
				url: $('#updateRif').attr('action'),
				success: function(data) {
					console.log(data)
					toastr["success"](data);
				}
			});
		})

		function bindSections() {
			$('.removeSection').click(function(e) {
				var section = $(this).attr('section');
				console.log(section)
				e.preventDefault();
				$.ajax({
					data: {section: section, removeSection: true},
					method: 'POST',
					url: $('#sections').attr('action'),
					success: function(data) {
						console.log(data);
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
			console.log($('#sections').serialize())
			$.ajax({
				data: $('#sections').serialize(),
				method: 'POST',
				url: $('#sections').attr('action'),
				success: function(data) {
					console.log(data)
					$('#itemsList').html(data);
					toastr["success"]("Section Added" );
					$('#sections').find('input[type=text], textarea').val('');
					bindSections();
				}
			});
		});


		function sendItemData(callback) {
			var data = [];
			console.log('herp);')
			var items = $('.itemSection');
			for (var i = 0; i < items.length; i++) {
				var dat = {};
				console.log('derp')
				dat.id = ($(items[i]).children('input').attr('value'));
				console.log('.' + dat.id + '.name')
				dat.name = $('.' + dat.id + '.name').val();
				dat.cost = $('.' + dat.id + '.cost').val();
				dat.quantity = $('.' + dat.id + '.quantity').val();
				data.push(dat);
			}

			console.log(data);

			$.ajax({
				data: {serialized: $('#facilities').serialize(), items: data},
				method: 'POST',
				url: $('#updateItems').attr('action'),
				success: function(data) {
					console.log(data)
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
				console.log($(this).closest('.itemSection').children('input').attr('value'))
				$.ajax({
					method: 'POST',
					data: {
						id: $(this).closest('.itemSection').children('input').attr('value'),
						deleteItem: true
					},
					url: $('#updateItems').attr('action'),
					success: function(data) {
						console.log(data);
					}
				})
			})
		}

		bindRemoveButtons();

		$('#newItem').click(function(e) {
			sendItemData(function() {
				console.log('herp');
				console.log($('#updateItems').attr('action')); ///loldsaodlsa
				$.ajax({
					method: 'POST',
					data: {newItem: true},
					url: $('#updateItems').attr('action'),
					success: function(data) {							
						console.log(data)
						$('#itemArea').html(data);
						bindRemoveButtons();
					}
				})
			});
		})



		$('#step-5-select').on('click', function() {
			console.log('herp')
			$('#rifReview').fadeOut(0, function() {
				$('#loadingReview').fadeIn(0, function() {
					$.ajax({
						method: 'GET',
						url: $('#updateRif').attr('action').replace('rifsubmit', 'rifreview'),
						success: function(data) {
							console.log(data)
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
				console.log('derp');
			$('.cost,.quantity,#room_fee,#room_hours').keyup(function() {
				console.log('lol')
				computeFees();
			});
		}


		function computeFees() {
			var total = 0;
			$('.itemSection').each(function() {
				var id = $(this).children('input').attr('value');
				console.log(id);
				total += $('.' + id + '.cost').val() * $('.' + id + '.quantity').val();
			})
			console.log(total);
		}

});
