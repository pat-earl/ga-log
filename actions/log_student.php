<?php
/**
 * Signs a student in to the log table, updates the 
 * session with current students in the office
 * 
 * Author: Tyler Stoney
 * Created: 28 Oct 2018
 */
if(!session_id()) session_start();

$students_in_office_table = [];
$students_in_office = [];
if(isset($_SESSION['stud_in_office']) && isset($_SESSION['stud_in_office_table'])) {
	$students_in_office_table = $_SESSION['stud_in_office_table'];
	$students_in_office = $_SESSION['stud_in_office'];
}

/* Initial form validation messages, will be displayed in toasts */
if(!isset($_POST['student_dropdown'])){
	echo("Yoohoo! Who are you helping?! Try again.");
	exit;
}

if(!isset($_POST['ga_select'])) {
	echo("what, no GA?");
	exit;
}


// Get/format our variables from the POST data
$id 		= intval($_POST['student_dropdown']);
$reason 	= $_POST['reason'];
$visit_date = $_POST['today'];
$in_time 	= $_POST['time_log'];
$class 		= ''; // Class is optional
$prof 		= ''; // Prof is optional
$GAs 		= ''; // GA(s) is mandatory, but will be populated later

// Form a datetime string that will be parsed properly by the sqlite db
$dt = $visit_date . ' ' . $in_time . ':00';

// If a class was chosen
if(isset($_POST['class_dropdown'])) 
	$class = $_POST['class_dropdown'];
if($class=='0') // If the user selected 'other' for the class
	$class=$_POST['other_class'];

// If a professor was chosen (this case will only arise)
//   if the user selected 'other' for the class and did 
//   not choose a professor.
if(isset($_POST['prof_dropdown'])) 
	$prof = $_POST['prof_dropdown'];

// Populate the GA field based on the multi-select in the form
if(isset($_POST['ga_select'])) {
	$i = 0;
	$ga_count = count($_POST['ga_select']);

	foreach ($_POST['ga_select'] as $ga) {
		$GAs = $GAs . $ga;
		$i++;
		if($i < $ga_count) {
			$GAs = $GAs . ",";
		}
	}
}

// $students_in_office holds just names
if(in_array($id, $students_in_office)) { 
	echo("This student is already signed in.");
} else {
	$db = new PDO('sqlite:../data/GA_LOG.db');
	if($insert_stmt = $db->prepare("INSERT INTO signed_in VALUES(:student_id, :csClass, :prof, :reason, :assistants, :in_time, :out_time)")) {
		$insert_stmt->bindParam(":student_id", $id);
		$insert_stmt->bindParam(":csClass", $class);
		$insert_stmt->bindParam(":prof", $prof);
		$insert_stmt->bindParam(":reason", $reason);
		$insert_stmt->bindParam(":assistants", $GAs);
		$insert_stmt->bindParam(":in_time", $dt);
		$insert_stmt->bindParam(":out_time", $dt);
		$insert_stmt->execute();
		
	
		$students_in_office_table = [];
		$students_in_office = [];
		// I requery the db because it's easier than adding an entry to the 
		//   student_in_office_table variable, since that contains query results.
		//   a student was removed.  In the javascript file, AJAX queries for
		//   and redraws the table immediately after this script ends.
		if($student_query = $db->prepare("SELECT * FROM signed_in LEFT JOIN students on signed_in.student_id = students.rowid WHERE sign_in_time >= sign_out_time")) {
			$student_query->execute();
			while($row = $student_query->fetchObject()) {
				$students_in_office_table[] = $row;
				$students_in_office[] = $row->student_id;
			}
		}else{
			//error !! don't go further
			var_dump($db->errorInfo());
			exit;
		}
		$_SESSION['stud_in_office_table'] = $students_in_office_table;
		$_SESSION['stud_in_office'] = $students_in_office;

		echo("<div class='success'>Student successfully logged.</div><br>");

	}else{
	   echo("Something went haywire in the SQL INSERT.");
	}
}

?>