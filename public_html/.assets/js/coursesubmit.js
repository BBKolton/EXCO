window.addEventListener('load', function() {
	console.log('herp');
	var type = document.getElementById('type');
	type.onchange = function() {
		var selected = type.options[type.selectedIndex].value;
		console.log(selected)
		var form = document.getElementById('card-form');
		if (selected != 'credit') {
			form.style.display = 'none';
			console.log(form)
			console.log('glerp')
		} else {
			form.style.display = 'block';
			console.log('flerpderp')
		}
	}
})