(function() {

	window.addEventListener("load", function() {
		var elems = document.getElementsByClassName("show");
		for (var i = 0; i < elems.length; i++) {
			elems[i].onclick = showEmail;
		}
	});

	function showEmail() {
		this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
		this.innerHTML = "Hide";
		this.onclick = hideEmail;
	}

	function hideEmail() {
		this.parentNode.getElementsByTagName("span")[0].style.display = "none";
		this.innerHTML = "Show Email";
		this.onclick = showEmail;
	}

})();