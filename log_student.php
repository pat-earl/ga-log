<?php
if(!session_id()) session_start();

$students_in_office_table = [];
$students_in_office = [];
if(isset($_SESSION['stud_in_office']) && isset($_SESSION['stud_in_office_table'])) {
	$students_in_office_table = $_SESSION['stud_in_office_table'];
	$students_in_office = $_SESSION['stud_in_office'];
}

if(!isset($_POST['student_dropdown'])){
	echo("Yoohoo! Who are you helping?! Try again.");
	exit;
}

if(!isset($_POST['ga_select'])) {
	echo("what, no GA?");
	exit;
}


$id = intval($_POST['student_dropdown']);
$class = '';
if(isset($_POST['class_dropdown']))
	$class = $_POST['class_dropdown'];
if($class=='0') {
	$class=$_POST['other_class'];
}
$prof = $_POST['prof_dropdown'];
$reason = $_POST['reason'];
$visit_date = $_POST['today'];
$in_time = $_POST['time_log'];

$GAs = "";
if(isset($_POST['ga_select'])) {
	$i = 0;
	foreach ($_POST['ga_select'] as $ga) {
		$GAs = $GAs . $ga;
		$i++;
		if($i < count($_POST['ga_select'])) {
			$GAs = $GAs . ",";
		}
	}
}

$dt = $visit_date . ' ' . $in_time . ':00';

if(in_array($id, $students_in_office)){
	echo("This student is already signed in.");
} else {
	$db = new PDO('sqlite:./GA_LOG.db');
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
		if($student_query = $db->prepare("SELECT * FROM signed_in LEFT JOIN students on signed_in.student_id = students.rowid WHERE sign_in_time >= sign_out_time")) {
			$student_query->execute();
			while($row = $student_query->fetchObject()) {
				$students_in_office_table[] = $row;
				$students_in_office[] = $row->student_id;
			}
		}else{
		//error !! don't go further
		var_dump($db->errorInfo());
		}
		$_SESSION['stud_in_office_table'] = $students_in_office_table;
		$_SESSION['stud_in_office'] = $students_in_office;

		echo("<div class='success'>Student successfully logged.</div><br>");

	}else{
	   //error !! don't go further
	   /*var_dump($db->errorInfo());*/
	   echo("Something went haywire in the SQL INSERT.");
	}
}

?>