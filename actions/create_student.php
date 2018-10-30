<?php
if(!session_id()) session_start();

$student_list = [];
$student_ids = [];
if(isset($_SESSION['stud_ids']) && isset($_SESSION['stud_list'])) {
	$student_ids = $_SESSION['stud_ids'];
	$student_list = $_SESSION['stud_list'];
}
if(isset($_POST["fname_create"]) && isset($_POST["lname_create"])) {
	if($_POST["fname_create"]=="" && $_POST["lname_create"]=="") {
		echo("First and last names can't be left blank.");
		exit;
	} else {
		$db = new PDO('sqlite:../data/GA_LOG.db');
		$fullname = $_POST["fname_create"] . " " . $_POST["lname_create"];
		$exists = false;
		foreach($student_list as $student) {
			if($student == $fullname) {
				echo("student already exists.");
				$exists = true;
				exit;
			}
		}
		if(!$exists) {
			$insert_stmt = $db->prepare("INSERT INTO students VALUES(:fname, :lname)");
			$insert_stmt->bindParam(":fname", $_POST["fname_create"]);
			$insert_stmt->bindParam(":lname", $_POST["lname_create"]);
			$insert_stmt->execute();
			echo("success");
		}

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