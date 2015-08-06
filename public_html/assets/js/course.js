"use strict";

//TODO refator EVERYTHING to make it simpler
//too much copy pasta
/*
(function(){
*/
	var ADDRESS = window.location.href;

	window.addEventListener("load", function() {
		document.getElementById("editDesc").onclick = descriptionEdit;
		document.getElementById("sendAll").onclick = courseWriteEmail;
		var elems = document.getElementsByClassName("sendSecs");
		for (var i = 0; i < elems.length; i++) { 
			elems[i].onclick = sectionWriteEmail(elems[i].id);
		}
	});

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
		cancel.onclick = descriptionReturnStart;
		text.id = "editDesc";
				
		area.replaceChild(text, edit);
		area.insertBefore(cancel, text.nextSibling);
		area.insertBefore(submit, text.nextSibling);
		
	}

	function descriptionSubmit() {
		var value = document.getElementById("editDesc").value;
		var ajax = new XMLHttpRequest();
		ajax.onload = descriptionApproved;
		ajax.open("POST", ADDRESS, true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("editDesc=" + value);
	}

	function descriptionApproved() {
		if (this.status != 200) {
			elert("The edit failed to reach the server. Please try again or make sure you're connected to the internet");
		} else {
			document.getElementById("description").innerHTML = document.getElementById("editDesc").value;
			alert("Edit successful!");
			descriptionReturnStart();
		}
	}

	function descriptionReturnStart() {
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

	function courseWriteEmail() {
		var edit = document.getElementById("sendAll");
		var text = document.createElement("textarea");
		var subject = document.createElement("input");
		var description = document.createElement("p");
		var area = edit.parentNode;
		var submit = document.createElement("button");
		var cancel = document.createElement("button");

		description.innerHTML = "Send an email to the entire course. Your email will be displayed as the reply-to"
		subject.setAttribute("type", "text");
		subject.setAttribute("placeholder", "Subject");
		subject.id = "sendAllSubject"
		submit.innerHTML = "Submit";
		submit.setAttribute("type", "button");
		submit.onclick = courseEmail;
		cancel.innerHTML = "Cancel";
		cancel.setAttribute("type", "button");
		cancel.onclick = courseReturnStart;
		text.id = "sendAll";
		text.setAttribute("placeholder", "Your message here");


		area.replaceChild(text, edit);
		area.insertBefore(description, text);
		area.insertBefore(subject, text);
		area.insertBefore(cancel, text.nextSibling);
		area.insertBefore(submit, text.nextSibling);
	}

	function courseEmail() {
		var desc = document.getElementById("sendAllSubject").value;
		var text = document.getElementById("sendAll").value
		var ajax = new XMLHttpRequest();
		ajax.onload = courseApproved;
		ajax.open("POST", ADDRESS, true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("sendAll=true&subject=" + desc + "&text=" + text);
	}

	function courseApproved() {
		if (this.status != 200) {
			elert("The email was not sent. Make sure you're connected to the internet");
		} else {
			alert(this.responseText);
			alert(this.status);
			courseReturnStart();
		}
	}

	function courseReturnStart() {
		var sendAll = document.createElement("a");
		sendAll.innerHTML = "Send Email to All Sections";
		sendAll.onclick = courseWriteEmail;
		sendAll.id = "sendAll";
		var text = document.getElementById("sendAll");
		var area = text.parentNode;
		while (area.firstChild) {
			area.removeChild(area.firstChild);
		}
		area.appendChild(sendAll);
	}

	function sectionWriteEmail(sectionNum) {
		var edit = this;
		alert(this);
		var text = document.createElement("textarea");
		var subject = document.createElement("input");
		var description = document.createElement("p");
		var area = edit.parentNode;
		var submit = document.createElement("button");
		var cancel = document.createElement("button");

		description.innerHTML = "Send an email to this section only. Your email will be displayed as the reply-to"
		subject.setAttribute("type", "text");
		subject.setAttribute("placeholder", "Subject");
		subject.id = "sendSectionSubject"
		submit.innerHTML = "Submit";
		submit.setAttribute("type", "button");
		submit.onclick = sectionEmail(sectionNum);
		cancel.innerHTML = "Cancel";
		cancel.setAttribute("type", "button");
		cancel.onclick = sectionReturnStart(sectionNum);
		text.id = "sec" + sectionNum;
		text.setAttribute("placeholder", "Your message here");


		area.replaceChild(text, edit);
		area.insertBefore(description, text);
		area.insertBefore(subject, text);
		area.insertBefore(cancel, text.nextSibling);
		area.insertBefore(submit, text.nextSibling);
	}

	function sectionEmail() {
		var desc = document.getElementById("sendAllSubject").value;
		var text = document.getElementById("sendAll").value
		var ajax = new XMLHttpRequest();
		ajax.onload = sectionApproved(sectionNum);
		ajax.open("POST", ADDRESS, true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("sendAll=true&subject=" + desc + "&text=" + text);
	}

	function sectionApproved(sectionNum) {
		if (this.status != 200) {
			elert("The email was not sent. Make sure you're connected to the internet");
		} else {

			alert(this.responseText);
			alert(this.status);
			sectionReturnStart(sectionNum);
		}
	}

	function sectionReturnStart() {
		var sendSec = document.createElement("a");
		sendSec.innerHTML = "Email this section";
		sendSec.onclick = sectionWriteEmail;
		sendAll.id = "sec" + sectionNum;
		var text = document.getElementById("sendAll");
		var area = text.parentNode;
		while (area.firstChild) {
			area.removeChild(area.firstChild);
		}
		area.appendChild(sendSec);
	}
/*
})();*/