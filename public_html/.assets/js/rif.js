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



		//?
		$('#updateItems').submit(function(e){
				e.preventDefault();
				var items = [{test: 'test', test2: 'test2'}, {herp: 'derp'}];
				$.ajax({
						data: {serialized: $('#facilities').serialize(), items: items},
						method: 'POST',
						url: $('#updateItems').attr('action'),
						success: function(data) {
							console.log(data);
							$('#itemArea').html(data);
							toastr["success"]("Saved" );
						}
				});
		});

		$('.removeItem').click(function(e) {
			$(this).closest('.itemSection').remove();
			console.log($(this).closest('.itemSection'))
		})

		$('#newItem').click(function(e) {

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
		})

});
