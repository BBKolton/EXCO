"use strict";

//(function() {
	var itemCount = 0;
	var timeCount = 0;

	window.addEventListener("load", function() {
		//items
		document.getElementById("item-add").onclick = ItemAddNew;
		var itemFields = document.getElementById("item").childNodes;
		for (var i = 5; i < itemFields.length - 2; i+= 2) {
			itemFields[i].firstChild.onkeyup = itemUpdateCost;
		}
		document.getElementById("item-clear-fields").onclick = ItemClearFields;

		//room
		var roomFields = document.getElementById("rooms").lastChild.childNodes;
		for (var i = 0; i < roomFields.length - 4; i+= 2) {
			roomFields[i].childNodes[3].firstChild.onkeyup = roomUpdateCostAndTotal;
		}

		//sections
		document.getElementById("section-add").onclick = sectionAddNew;
		document.getElementById("section-clear-fields").onclick = sectionClearFields;

		//clear buttons for already added items
		var clears = document.getElementsByClassName('clear-original');
		console.log(clears)
		for (var i = 0; i < clears.length; i++) {
			clears[i].onclick = function() {
				console.log('herp');
				this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
				itemUpdateTotal();
			}
		}
		itemUpdateTotal();

	});

	//Item Info
	//adds a finished item to the list below TODO combine with sectionAddNew
	function ItemAddNew() {
		//make the row
		var item = document.getElementById("item");
		var row = document.createElement("tr");
		document.getElementById("items").appendChild(row);
		
		//add the image that gets rid of the row
		var x = document.createElement("td");
		var img = document.createElement("img");
		img.src = "https://placehold.it/20x20";
		x.appendChild(img);
		row.appendChild(x);
		x.onclick = function() {
			x.parentNode.parentNode.removeChild(row);
			itemUpdateTotal()
		}
		
		//get values from fields, make new td's and add them
		for (var i = 3; i < item.childNodes.length - 2; i+= 2) {
			var def = document.createElement("td");
			var inp = document.createElement("input");
			var inp2 = document.createElement("input");
			inp.value = item.childNodes[i].firstChild.value;
			inp2.value = item.childNodes[i].firstChild.value;
			inp.setAttribute("disabled", "disabled");
			inp2.setAttribute("hidden", "hidden");
			inp.setAttribute("type", "text");
			inp2.setAttribute("type", "text");
			inp2.setAttribute("name", "item-" + itemCount + "-" + ((i - 3)/ 2));
			item.childNodes[i].firstChild.value = "";
			row.appendChild(def);
			def.appendChild(inp);
			def.appendChild(inp2);
		}
		itemCount++;
		
		//add total to end
		var total = document.createElement("td");
		total.className = "total";
		total.innerHTML = document.getElementById("item-row-cost").innerHTML;
		row.appendChild(total);
		document.getElementById("item-row-cost").innerHTML = "$0";
		
		//make the total correct
		itemUpdateTotal();
	}

	//the clear button for the fields
	function ItemClearFields() {
		var item = document.getElementById("item");
		for (var i = 3; i < item.childNodes.length - 3; i+= 2) {
			item.childNodes[i].firstChild.value = "";
		}
		itemUpdateCost();
	}

	//updates the cost at the end of supplies fees
	function itemUpdateCost() {
		var cost = document.getElementById("item-quan").value * document.getElementById("item-cost").value;
		document.getElementById("item-row-cost").innerHTML = "$" + cost;
	}

	//udpates the total at the bottom of the fsupplies area
	function itemUpdateTotal() {
		var totals = document.getElementsByClassName("total");
		var total = 0;
		for (var i = 0; i < totals.length; i++) {
			total+= parseInt(totals[i].innerHTML.substring(1));
		}
		document.getElementById("item-total").innerHTML = "Total Fee: $" + total;
		roomUpdateCostAndTotal();	
	}


	//Room Info
	//updates room costs and fees
	function roomUpdateCostAndTotal() {
		var rate = document.getElementById("room-rate").value;
		var hour = document.getElementById("room-hours").value;
		var cost = parseInt(rate) * parseInt(hour);
		document.getElementById("room-total").innerHTML = "$" + cost;
		var item = document.getElementById("item-total").innerHTML;
		document.getElementById("room-item-total").innerHTML = "$" + (cost + parseInt(item.substring(12)));
		feesUpdate();
	}


	//fees Info
	//udpate the fee costs for students
	function feesUpdate() {
		var gen = document.getElementById("fee-gen");
		var stu = document.getElementById("fee-uw");
		var hrs = document.getElementById("room-hours").value;
		gen.innerHTML = "$" + parseInt(hrs) * 10;
		stu.innerHTML = "$" + parseInt(hrs) * 7;
	}


	//Section Info
	//adds a new section to the list of sections TODO combine with itemAddNew!!!
	function sectionAddNew() {
		//make the new row
		var section = document.getElementById("section");
		var row = document.createElement("tr");
		document.getElementById("sections").appendChild(row);
		
		//add the image that gets rid of the row
		var x = document.createElement("td");
		var img = document.createElement("img");
		img.src = "https://placehold.it/20x20";
		x.appendChild(img);
		row.appendChild(x);
		x.onclick = function() {
			x.parentNode.parentNode.removeChild(row);
		}
		
		//get values from fields, make new td's and add them
		for (var i = 3; i < section.childNodes.length; i+= 2) {
			var def = document.createElement("td");
			var inp = document.createElement("input");
			var inp2 = document.createElement("input");
			inp.value = section.childNodes[i].firstChild.value;
			inp2.value = section.childNodes[i].firstChild.value;
			inp.setAttribute("disabled", "disabled");
			inp2.setAttribute("hidden", "hidden");
			inp.setAttribute("type", "text");
			inp2.setAttribute("type", "text");
			inp2.setAttribute("name", "section-" + timeCount + "-" + ((i - 3)/ 2));
			console.log("section-" + timeCount + "-" + ((i - 3)/ 2));
			section.childNodes[i].firstChild.value = "";
			row.appendChild(def);
			def.appendChild(inp);
			def.appendChild(inp2);
		}
		timeCount++;
		sectionClearFields();
	}


	//clear the fields of sections
	function sectionClearFields() {
		var section = document.getElementById("section");
		for (var i = 3; i < section.childNodes.length - 2; i+= 2) {
			section.childNodes[i].firstChild.value = "";
		}
	}


//})();