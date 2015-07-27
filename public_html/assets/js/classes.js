"use strict";
(function(){
	//Makes the sidebar scroll
	window.addEventListener("load", function() {
		$(function() {
			$('.sidebar').affix();
		});
	});

	//make anchors show up a bit above where they're supposed to
	window.addEventListener("hashchange", hashScroll);
	window.addEventListener("load", hashScroll);

	function hashScroll() {
		window.scrollTo(window.scrollX, window.scrollY - 60);
	}
})();