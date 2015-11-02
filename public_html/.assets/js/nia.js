window.addEventListener('load', function() {
	var text = document.getElementById('summary');
	text.onkeyup = countWords;
	console.log(text)

	function countWords() {
		console.log('herro')
		var count = document.getElementById('word-count');
		var spaces = this.value.match(/\b/g);
		console.log(spaces);
		spaces = spaces ? spaces.length / 2 : 0;
		if (spaces > 75) {
			count.style.color = "#d53118";
		} else {
			count.style.color = "black";
		}
		count.innerHTML = spaces;
	}
}); 