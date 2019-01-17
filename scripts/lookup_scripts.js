/**
 * Handles the Materialize style, dynamic generation of elements,
 * and running php scripts via AJAX.
 * 
 * Author: Tyler Stoney
 * Created: 28 Oct 2018
 */

var classes;
var ga_sel = document.getElementById('ga_select');
var class_sel = document.getElementById('class_dropdown');
var profEnabled = false;
var timePickers = null;

function showDiv(btnName, divName) {
	var showButt = document.getElementById(btnName);
	var addDiv = document.getElementById(divName);
	showButt.setAttribute('hidden', 'true');
	showButt.setAttribute("style", "display:none;");
    addDiv.removeAttribute('style');
	addDiv.removeAttribute('disabled');
}

// Helper, enables an element via ID
function enableElt(eltID) {
	var elt = document.getElementById(eltID);
	elt.removeAttribute('disabled');
}

/* Loads JSON file passed in in first arg */
function loadJSON(filename, callback) {   

    var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
    // Replace 'my_data' with the path to your file
    xobj.open('GET', filename, true); 
    xobj.onreadystatechange = function () {
          if (xobj.readyState == 4 && xobj.status == "200") {
            // Required use of an anonymous callback as .open will NOT
            //   return a value but simply returns undefined in asynchronous mode
            callback(xobj.responseText);
          }
    };
    xobj.send(null);  
 }

 /* Loads GA schedules from
  * a JSON file, selects the 
  * GA(s) currently occupying 
  * the office.
  */
function loadGaSchedules() {
	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var today_date = new Date();
	var today_now = Date.now();
	var curr_day = days[today_date.getDay()];	
	loadJSON('./data/ga_schedule.json', function(response) {
		// Parse JSON string into object
		var ga_sched = JSON.parse(response);
		ga_sel = document.getElementById('ga_select');
		if(ga_sel !== null) {
			ga_sched.forEach(ga => {
				var sel = document.createElement("option");
				sel.setAttribute('value', (ga.first_name + " " + ga.last_name));
				sel.innerHTML = (ga.first_name + " " +  ga.last_name);
				if(ga['schedule'][curr_day]['in'] != 'NO'){

					var in_time = ga['schedule'][curr_day]['in'].split(':');
					var out_time = ga['schedule'][curr_day]['out'].split(':');

					var ga_in = new Date();
					ga_in.setHours(in_time[0]);
					ga_in.setMinutes(in_time[1]);

					var ga_out = new Date();
					ga_out.setHours(out_time[0]);
					ga_out.setMinutes(out_time[1]);

					if(today_now >= ga_in && today_now <= ga_out){
						sel.setAttribute('selected', 'true');
					}
				}
				ga_sel.appendChild(sel);
			});
		}
	});

}

// Reloads the student records, effectively
//   refreshing the students in the session
function reloadRecords() {
	var ajaxurl = './actions/records.php',
	data = "&ajax=" + true;
	$.post(ajaxurl, data, function (response) {
		if(response.indexOf("success")>=0){
			reloadDropdowns();
		}
		else
			M.toast({html: "Error: Couldn't reload the dropdowns."});

	});
}

// load_student_list.php returns html option
//   tags containing the students in the session.
//   The form's select element is then refreshed.
function reloadDropdowns() {
	var ajaxurl = './actions/load_student_list.php',
	data = "&ajax=" + true;
	$.post(ajaxurl, data, function (response) {
		if(response.indexOf("option")>=0){
			$('#student_dropdown').append(response);
			//$('select').formSelect();
			//$('#test option').filter(function () { return $(this).html() == "B"; }).val();
			var studList={};
			$("#student_dropdown > option").each(function() {
				//alert(this.text + ' ' + this.value);
				studList[this.text] = null;
			});
			$('input.autocomplete').autocomplete({
				data: studList,
			});
		}
		else
			M.toast({html: "Error: Couldn't populate the dropdowns."});

	});
}

function changeSelectDom() {
	$("#student_dropdown option").filter(function() {
		if( this.text == $("#student-auto").val()){
			console.log(this.value + this.text);
			return this.text == $("#student-auto").val();
		}
	}).attr('selected', true);
}

// Creates the clock app with a 24h time picker.
// * NOTE: The clock didn't want to be created manually 
//   if this was in a  function, so here it is. *
document.addEventListener('DOMContentLoaded', function() {
	var elems = document.querySelectorAll('.datepicker');
	timePickers = M.Datepicker.init(elems, {format: 'yyyy-mm-dd'});
});

$(document).ready(function() {
	loadGaSchedules();
	//reloadTable();

	M.AutoInit();

	var elems = document.querySelectorAll('.datepicker');
	timePickers = M.Datepicker.init(elems, {format: 'yyyy-mm-dd'});

	// Populates the class select dropdown from a JSON file
	loadJSON('./data/classlist.json', function(response) {
		// Parse JSON string into object
		classes = JSON.parse(response);
		classes.forEach(cscClass => {
			
			var sel = document.createElement("option");
			sel.setAttribute('value', cscClass.id);
			if(cscClass.id != "0"){
				sel.innerHTML = ("CSC" + cscClass.id + " - " + cscClass.name);
			} 
			class_sel.appendChild(sel);
		});
		$('select').formSelect();
	});

	/* Making the submit buttons functional */
	$('.submit').click(function(){
        var clickBtnValue = $(this).val();
        var ajaxurl = './actions/lookup.php',
        data =  $(this).parent().closest('form').serialize() + "&ajax=" + true;
        $.post(ajaxurl, data, function (response) {
            if(response.indexOf("error")>=0){
        		M.toast({html: ("Error: " + response)});
			} else {
				$('#tableDiv').html(response);
				M.toast({displayLength: 1000, html: 'Find what you\'re looking for, chief?'/*, completeCallback: function(){location.reload(); }*/});
        	}
        });
	});

	
	reloadDropdowns();

});

