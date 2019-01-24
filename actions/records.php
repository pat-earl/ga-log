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
$student_query = $db->prepare("SELECT rowid, fname, lname FROM 'students'");
$student_query->execute();

$student_list = []; // Used in populating student select dropdown as human-readable info
$student_ids  = []; // Used in populating student select dropdown as value sent to query
while($row = $student_query->fetchObject()) {
	$student_ids[] = $row->rowid;

	$fullname = $row->fname . " " . $row->lname;
	$student_list[] = $fullname;
}

// Store both in the session (both used in "create_student.php")
$_SESSION['stud_ids'] = $student_ids;
$_SESSION['stud_list'] = $student_list;


/* GET LIST OF STUDENTS CURRENTLY SIGNED IN */

$students_in_office_table = []; // Holds all query objects (all data about signed-in students)
$students_in_office 	  = []; // Holds just the id of the students currently signed in
if($student_query = $db->prepare("SELECT signed_in.rowid, * FROM signed_in INNER JOIN students on signed_in.student_id = students.rowid WHERE sign_in_time >= sign_out_time")) {
	$student_query->execute();

	while($row = $student_query->fetchObject()) {
		$students_in_office_table[] = $row;
		$students_in_office[] 		= $row->student_id;
	}

} else {
   //error !! don't go further
   var_dump($db->errorInfo());
   exit();
}

// Store both in the session (both used in "log_student.php", "signout.php", "table.php")
$_SESSION['stud_in_office_table'] = $students_in_office_table;
$_SESSION['stud_in_office'] = $students_in_office;

if(isset($_SESSION['stud_in_office_table']) and 
   isset($_SESSION['stud_in_office'])	and
   isset($_SESSION['stud_ids'])			and
   isset($_SESSION['stud_list'])) {
		echo("success");
		
		exit;
   }
echo("error");
if(!isset($_POST['ajax']))
	header("Location: ../index.php");
?>

