<?php
if(!session_id()) session_start();

date_default_timezone_set('America/New_York');
$time = date('H:i');
$date = date('Y-m-d');
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* 
 * Some initialization stuff for use in populating
 * dropdowns, form checking, etc.
 */

/* GET FULL LIST OF STUDENTS */

// Connect to db, select every student
$db = new PDO('sqlite:../data/GA_LOG.db');

$students_in_office_table = []; // Holds all query objects (all data about signed-in students)
$students_in_office 	  = []; // Holds just the id of the students currently signed in

$clause = "";
$where = false;

if( isset($_POST['today']) and $_POST['today'] != "") {
	if(!$where) {
		$clause = " WHERE ";
		$where = true;
	} else {
		$clause = $clause . " AND ";
	}
	
	// Form a datetime string that will be parsed properly by the sqlite db
	$dt = $_POST['today'] . ' 00:00:00';

	$clause = $clause . "sign_in_time BETWEEN '" . $dt . "' AND '" . $_POST['today'] . " 23:59:59'";
}

if(isset($_POST['student_dropdown']) and $_POST['student_dropdown'] != "") {
	if(!$where) {
		$clause = " WHERE ";
		$where = true;
	} else {
		$clause = $clause . " AND ";
	}
	
	$clause = $clause . "student_id = " . $_POST['student_dropdown'];
}


if($student_query = $db->prepare("SELECT * FROM signed_in LEFT JOIN students on signed_in.student_id = students.rowid" . $clause . " ORDER BY sign_in_time")) {
	$student_query->execute();

	while($row = $student_query->fetchObject()) {
		$students_in_office_table[] = $row;
		$students_in_office[] 		= $row->student_id;
	}

} else {
   //error !! don't go further
   //var_dump($db->errorInfo());
	echo("SELECT * FROM signed_in LEFT JOIN students on signed_in.student_id = students.rowid" . $clause);
	// echo ("<div class='error'></div>");
	echo("idk brainiac, look it up yourself");
	exit();
}

$amt = count($students_in_office_table);

if($amt>0) { 
	echo("<table class='highlight' id='signin_table'>
			<tr>
				<th>Student</th>
				<th>Class</th>
				<th>Professor</th>
				<th>Reason</th>
				<th>Date</th>
				<th>Time In</th>
				<th>Time Out</th>
			</tr>");

	foreach($students_in_office_table as $stud) {
		$time_in = date_create_from_format('Y-m-d H:i:s', $stud->sign_in_time);
		$date_in = date('d-m-Y', $time_in->getTimestamp());
		$time_in = date('H:i', $time_in->getTimestamp());
		$time_out = date_create_from_format('Y-m-d H:i:s', $stud->sign_out_time);
		$time_out = date('H:i', $time_out->getTimestamp());
		$id = $stud->student_id;
		echo("<tr id='row$id'>");
		echo("<td>$stud->fname $stud->lname</td>");
		echo("<td>$stud->class</td>");
		echo("<td>$stud->professor</td>");
		echo("<td>$stud->reason_for_visit</td>");
		echo("<td>$date_in</td>");
		echo("<td>$time_in</td>");
		echo("<td>$time_out</td>");
		echo("</tr>");
	}

	echo("</table>
		<br>");
} else {
	echo("SELECT * FROM signed_in LEFT JOIN students on signed_in.student_id = students.rowid" . $clause);
	//echo ("<div class='error'></div>");
	echo ("Nobody here but us potatoes!");
}

if(!isset($_POST['ajax']))
	header("Location: ../lookup.php");
?>

