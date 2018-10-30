<?php
/**
 * Adds a student to the student table and updates  
 * the session variable to reflect the changes.
 * 
 * Author: Tyler Stoney
 * Created: 28 Oct 2018
 */
if(!session_id()) session_start();

$student_list = []; // Initialize as empty, only fill if the session var is set for each
$student_ids  = [];
if(isset($_SESSION['stud_ids']) && isset($_SESSION['stud_list'])) {
	$student_ids = $_SESSION['stud_ids'];
	$student_list = $_SESSION['stud_list'];
}
// Only create a student if required post data is set, should 
//   prevent anything from accidentally firing if accessed by hand.
if(isset($_POST["fname_create"]) && isset($_POST["lname_create"])) {

	// Initial validation
	if($_POST["fname_create"]=="" || $_POST["lname_create"]=="") {
		echo("First and last names can't be left blank.");
		exit;
	} else {
		$db = new PDO('sqlite:../data/GA_LOG.db');
		$fullname = $_POST["fname_create"] . " " . $_POST["lname_create"];

		// Search current records for existence of this student
		$exists = false;
		foreach($student_list as $student) {
			if($student == $fullname) {
				echo("student already exists.");
				$exists = true;
				exit;
			}
		}

		// If not found, clear to proceed.
		if(!$exists) {
			$insert_stmt = $db->prepare("INSERT INTO students VALUES(:fname, :lname)");
			$insert_stmt->bindParam(":fname", $_POST["fname_create"]);
			$insert_stmt->bindParam(":lname", $_POST["lname_create"]);
			$insert_stmt->execute();
			echo("success");
		}

		// Re-query the db to update the current list of students
		$student_query = $db->prepare("SELECT rowid, fname, lname FROM 'students'");
		$student_query->execute();

		$student_list = [];
		$student_ids = [];
		while($row = $student_query->fetchObject()) {
			$fullname = $row->fname . " " . $row->lname;
			$student_ids[] = $row->rowid;
			$student_list[] = $fullname;
		}
	}
} else {
	echo("couldn't do anything.");
	exit;
}

?>