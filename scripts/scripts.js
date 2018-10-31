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

function populateProf() {
	
	var e = document.getElementById("class_dropdown");
	var selDiv = document.getElementById("prof_dropdown");
	var classID = e.options[e.selectedIndex].value;

	if(!profEnabled){
		enableElt('prof_dropdown');
        profEnabled = true;
    }

    // If the user selected "(other)" as the class,
    //   show the field for manual entry of class
	if(classID=="0") {
		showDiv('none', 'other_class');
	} else { // Otherwise, hide and disable it.
        var oth_class = document.getElementById('other_class');
		oth_class.setAttribute("style", "display:none;");
        oth_class.setAttribute("disabled", "true;");
    }

    // Deletes all child nodes of the professor select 
    //   dropdown (removes all professor options)
	while (selDiv.firstChild) {
    	selDiv.removeChild(selDiv.firstChild);
	}

    // Get the list of professors associated  
    //   with the selected class's ID.
    // I use a for rather than forEach so I 
    //   can break out of it. Thanks, JS >:(
	var profList;
    for(var i = 0; i < classes.length; i++) {
        if(classes[i].id === classID) {
            profList = classes[i].profs;
            break;
        }
    }

    // Create option elements with each professor in list
	profList.forEach(prof => {
		var sel = document.createElement("option");
		sel.setAttribute('value', prof);
		sel.innerHTML = (prof);
		selDiv.appendChild(sel);
	});

    // Reinitialize the Materialize options to update the buttons
	$('select').formSelect();
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

 /* Loads GA schedules */
function loadGaSchedules() {
	var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var today_date = new Date();
	var today_now = Date.now();
	var curr_day = days[today_date.getDay()];	
	loadJSON('./data/ga_schedule.json', function(response) {
		// Parse JSON string into object
		var ga_sched = JSON.parse(response);
		ga_sel = document.getElementById('ga_select');
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
	});

}

// Loads the table of currently-signed-in students.
//  Function is run on page load/reload, and on successfully
//  logging a student as present.
function reloadTable() {
	var ajaxurl = './actions/table.php',
	data = "&ajax=" + true;
	$.post(ajaxurl, data, function (response) {
		if(response.indexOf("table")>=0){
			$('#tableDiv').html(response);
			initButtons();
		} else if(response.indexOf("Welcome")>=0) {
			M.toast({html: response});
			initButtons();
		}
		else
			M.toast({html: "Error: Table couldn't be auto-updated. Just refresh the page."});

	});
}

function getRecords() {
	var ajaxurl = './actions/records.php',
	data = "&ajax=" + true;
	$.post(ajaxurl, data, function (response) {
		if(response.indexOf("success")>=0){
			reloadTable();
		} else {
			M.toast({html: "Error: Session couldn't be set. Try refreshing the page."});
		}
	});
}

// Dynamically-created Materialize buttons must be manually initialized,
//  so this function takes care of that.  The only buttons thus far in the
//  app that need this are the signout buttons put in the table.
function initButtons() {
	$('.signout').click(function(){
        var clickBtnValue = $(this).val();
        var rowId = '#row'+clickBtnValue;
        var ajaxurl = './actions/signout.php',
        data =  {'studId': clickBtnValue, 'ajax': true};
        $.post(ajaxurl, data, function (response) {
            if(response.indexOf("success")>=0){
            	M.toast({html: 'Student successfully signed out!'});
        		$(rowId).slideUp(1000, function() { $(rowId).remove(); } );
        		var len = $("#signin_table tr").length;
        		if(len < 3)
					$("#signin_table").remove();
        	} else {
        		M.toast({html: "Error: Student couldn't be removed."});
        	}
        });
	});
}

// Creates the clock app with a 24h time picker.
// * NOTE: The clock didn't want to be created manually 
//   if this was in a  function, so here it is. *
document.addEventListener('DOMContentLoaded', function() {
	var elems = document.querySelectorAll('.timepicker');
	var instances = M.Timepicker.init(elems, {twelveHour: false});
});

$(document).ready(function() {
	loadGaSchedules();
	getRecords();
	//reloadTable();

	M.AutoInit();

	loadJSON('./data/classlist.json', function(response) {
		// Parse JSON string into object
		classes = JSON.parse(response);
		classes.forEach(cscClass => {
			
			var sel = document.createElement("option");
			sel.setAttribute('value', cscClass.id);
			if(cscClass.id != "0"){
				sel.innerHTML = ("CSC" + cscClass.id + " - " + cscClass.name);
			} else {
				sel.innerHTML = ("(Other)");
			}
			class_sel.appendChild(sel);
		});
		$('select').formSelect();
	});

	initButtons();
	
	/* Add student */
	$('.addstud').click(function(){
        var clickBtnValue = $(this).val();
        var ajaxurl = './actions/create_student.php',
        data =  $('form').serialize() + "&ajax=" + true;
        $.post(ajaxurl, data, function (response) {
            if(response.indexOf("success")>=0){
            	M.toast({displayLength: 1000, html: 'Student successfully added!', completeCallback: function(){location.reload();}});
        	} else {
        		M.toast({html: ("Error: " + response)});
        	}
        });
	});
	
	/* Sign in student */
	$('.signinstud').click(function(){
        var clickBtnValue = $(this).val();
        var ajaxurl = './actions/log_student.php',
        data =  $('form').serialize() + "&ajax=" + true;
        $.post(ajaxurl, data, function (response) {
            if(response.indexOf("success")>=0){
				M.toast({displayLength: 1000, html: 'Student successfully logged!'/*, completeCallback: function(){location.reload(); }*/});
				getRecords();
				//reloadTable();
			} else {
        		M.toast({html: ("Error: " + response)});
        	}
        });
    });

});



$("#class_dropdown").on('change', function() {
    populateProf();
});
