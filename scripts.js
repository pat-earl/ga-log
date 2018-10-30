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
}

function enableProf() {
	var profDiv = document.getElementById('prof_dropdown');
	profDiv.removeAttribute('disabled');
	profEnabled = true;
}

function populateProf() {
	
	var e = document.getElementById("class_dropdown");
	var selDiv = document.getElementById("prof_dropdown");
	var classID = e.options[e.selectedIndex].value;

	if(!profEnabled)
		enableProf();

	if(classID=="0") {
		showDiv('none', 'other_class');
	} else {
		document.getElementById('other_class').setAttribute("style", "display:none;");
	}

	while (selDiv.firstChild) {
    	selDiv.removeChild(selDiv.firstChild);
	}

	var profList;
	classes.forEach(cs => {
		if(cs.id === classID) {
			profList = cs.profs;
		}
	});

	profList.forEach(prof => {
		var sel = document.createElement("option");
		sel.setAttribute('value', prof);
		sel.innerHTML = (prof);
		selDiv.appendChild(sel);
	});

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
	loadJSON('./ga_schedule.json', function(response) {
		// Parse JSON string into object
		var ga_sched = JSON.parse(response);
		ga_sel = document.getElementById('ga_select');
		ga_sched.forEach(ga => {
			var sel = document.createElement("option");
			sel.setAttribute('value', (ga.first_name + " " + ga.last_name));
			sel.innerHTML = (ga.first_name + " " +  ga.last_name);
			if(ga['schedule'][curr_day]['in'] != 'NO'){
				var ga_in = new Date();
				var ga_out = new Date();
				
				var in_time = ga['schedule'][curr_day]['in'].split(':');
				var out_time = ga['schedule'][curr_day]['out'].split(':');

				ga_in.setHours(in_time[0]);
				ga_in.setMinutes(in_time[1]);

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

function reloadTable() {
	var ajaxurl = './table.php',
	data = '';
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

function initButtons() {
	$('.signout').click(function(){
        var clickBtnValue = $(this).val();
        var rowId = '#row'+clickBtnValue;
        var ajaxurl = './signout.php',
        data =  {'studId': clickBtnValue};
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

//function clockInit() {
	document.addEventListener('DOMContentLoaded', function() {
		var elems = document.querySelectorAll('.timepicker');
		var instances = M.Timepicker.init(elems, {twelveHour: false});
	});
//}

$(document).ready(function() {
	loadGaSchedules();
	reloadTable();

	M.AutoInit();

	//clockInit();
	
	

	loadJSON('./classlist.json', function(response) {
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

	/* Sign out student */
	/* $('.signout').click(function(){
        var clickBtnValue = $(this).val();
        var rowId = '#row'+clickBtnValue;
        var ajaxurl = './signout.php',
        data =  {'studId': clickBtnValue};
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
	}); */
	
	/* Add student */
	$('.addstud').click(function(){
        var clickBtnValue = $(this).val();
        var ajaxurl = './create_student.php',
        data =  $('form').serialize();
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
        var ajaxurl = './log_student.php',
        data =  $('form').serialize();
        $.post(ajaxurl, data, function (response) {
            if(response.indexOf("success")>=0){
				M.toast({displayLength: 1000, html: 'Student successfully logged!'/*, completeCallback: function(){location.reload(); }*/});
				reloadTable();
			} else {
        		M.toast({html: ("Error: " + response)});
        	}
        });
    });

});



$("#class_dropdown").on('change', function() {
    populateProf();
});
