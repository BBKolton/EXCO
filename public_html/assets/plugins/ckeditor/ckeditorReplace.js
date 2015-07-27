//A simple script to grab ALL text areas in any document and replace them with the CKeditor

"use strict";

(function() {
	window.addEventListener("load", function() {
		var textareas = document.getElementsByTagName("textarea");
		for (var i = 0; i < textareas.length; i++) {
			CKEDITOR.replace(textareas[i]);
			console.log("replaced " + textareas[i]);
		}
	});
})();