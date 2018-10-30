<?php
if(!session_id()) session_start();

date_default_timezone_set('America/New_York');
$time = date('H:i');
$date = date('Y-m-d');

$dt = $date . ' ' . $time . ':00';
$db = new PDO('sqlite:../data/GA_LOG.db');
if($student_query = $db->prepare("UPDATE signed_in set sign_out_time=:dt WHERE student_id = :studId")){
	$student_query->bindParam(":studId", intval($_POST['studId']));
	$student_query->bindParam(":dt", $dt);
	$student_query->execute();
	$stud_key = 0;
	foreach($_SESSION['stud_in_office_table'] as $key => $stud) {
		if(intval($stud->student_id) == intval($_POST['studId'])){
			$stud_key = $key;
			break;
		}
	}
	unset($_SESSION['stud_in_office_table'], $stud_key);
	echo("<div class='success'>Successfully signed out!</div>");
	exit;
} else{
	//error !! don't go further
	echo("<div class='error'>Failed to sign student out.</div>");
	exit;
}
?>