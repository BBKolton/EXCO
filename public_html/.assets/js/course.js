"use strict";

//TODO maybe refactor the two sets of functions together

(function(){

	var ADDRESS = window.location.href;

	window.addEventListener("load", function() {
		document.getElementById("editDesc").onclick = descriptionEdit;
		document.getElementById("sendAll").onclick = writeEmail;
		var elems = document.getElementsByClassName("sendSecs");
		for (var i = 0; i < elems.length; i++) { 
			elems[i].onclick = writeEmail;
		}
/*		document.getElementById("course-status-toggle").onclick = confirmCourseStatusChange 
*/
	});

/*	function confirmCourseStatusChange() {
		var r = confirm("Are you sure you want to toggle the course status?");
		if (r) {
			window.location.replace(window.location.href + "&course-status-toggle");
		}
	}*/

	//creates the textbox and submit and cancel buttons
	function descriptionEdit() {
		var desc = document.getElementById("description");
		var edit = document.getElementById("editDesc");
		var text = document.createElement("textarea");
		var area = edit.parentNode;
		var submit = document.createElement("button");
		var cancel = document.createElement("button");

		text.value = desc.innerHTML;
		submit.innerHTML = "Submit";
		submit.setAttribute("type", "button");
		submit.onclick = descriptionSubmit;
		cancel.innerHTML = "Cancel";
		cancel.setAttribute("type", "button");
		cancel.onclick = descriptionReset;
		text.id = "editDesc";
				
		area.replaceChild(text, edit);
		area.insertBefore(cancel, text.nextSibling);
		area.insertBefore(submit, text.nextSibling);
		
	}

	//submits the description to the server
	function descriptionSubmit() {
		var value = document.getElementById("editDesc").value;
		var ajax = new XMLHttpRequest();
		ajax.onload = descriptionApproved;
		ajax.open("POST", ADDRESS, true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("editDesc=" + value);
	}

	//gives the response from teh server
	function descriptionApproved() {
		if (this.status != 200) {
			elert("The edit failed to reach the server. Please try again or make sure you're connected to the internet");
		} else {
			document.getElementById("description").innerHTML = document.getElementById("editDesc").value;
			alert("Edit successful!");
			descriptionReset();
		}
	}

	//reset the description area
	function descriptionReset() {
		var editDesc = document.createElement("a");
		editDesc.innerHTML = "Edit Description";
		editDesc.onclick = descriptionEdit;
		editDesc.id = "editDesc";
		var text = document.getElementById("editDesc");
		var area = text.parentNode;
		while (area.firstChild) {
			area.removeChild(area.firstChild);
		}
		area.appendChild(editDesc);
	}



	//create sthe text areas and submit and cancel buttons
	function writeEmail() {
		var edit = this;
		var text = document.createElement("textarea");
		var subject = document.createElement("input");
		var description = document.createElement("p");
		var area = edit.parentNode;
		var submit = document.createElement("button");
		var cancel = document.createElement("button");

		description.innerHTML = "Send an email to the section or course. Your email will be displayed as the reply-to"
		subject.setAttribute("type", "text");
		subject.setAttribute("placeholder", "Subject");
		submit.innerHTML = "Send";
		submit.setAttribute("type", "button");
		submit.onclick = function() {sendEmail.call(submit); reset.call(submit);};
		cancel.innerHTML = "Cancel";
		cancel.setAttribute("type", "button");
		cancel.onclick = reset;
		text.setAttribute("placeholder", "Your message here");

		area.replaceChild(text, edit);
		area.insertBefore(description, text);
		area.insertBefore(subject, text);
		area.insertBefore(cancel, text.nextSibling);
		area.insertBefore(submit, text.nextSibling);
	}

	//sends the email
	function sendEmail() {
		var desc = this.parentNode.getElementsByTagName("input")[0].value;
		var text = this.parentNode.getElementsByTagName("textarea")[0].value;
		var ajax = new XMLHttpRequest();
		ajax.onload = requestResponse;
		ajax.open("POST", ADDRESS, true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("type=" + this.parentNode.id + "&subject=" + desc + "&text=" + text);
	}

	//gives the response to the email request
	function requestResponse() {
		if (this.status != 200) {
			elert("The email was not sent. Make sure you're connected to the internet");
		} else {
			alert(this.responseText);
		}
	}

	//resets teh areas to before the emails were sent
	function reset() {
		var sendSec = document.createElement("a");
		if (this.parentNode.className == "all") {
			sendSec.innerHTML = "Send an email to all students";
		} else {
			sendSec.innerHTML = "Email this section";
		}
		sendSec.onclick = writeEmail;
		var text = this;
		var area = this.parentNode;
		while (area.firstChild) {
			area.removeChild(area.firstChild);
		}
		area.appendChild(sendSec);
	}

})();