<?php
/**
 * Generates the table of currently-logged-in students.
 * 
 * Author: Tyler Stoney
 * Created: 29 Oct 2018
 */

if(!session_id()) session_start();

if(!isset($_POST['ajax'])){
	header("Location: ../index.php");
	exit;
}

date_default_timezone_set('America/New_York');

if(!isset($_SESSION['stud_in_office_table'])) {
	// My JQuery looks for "table" in the response to draw the table
	//   otherwise, it will create a toast with whatever the response
	//   is, for example, this:
	echo("couldn't update table. Maybe try refreshing the page?");
	exit;
} else {

	$amt = count($_SESSION['stud_in_office_table']);

	if($amt>0) { 
		echo("<table class='highlight' id='signin_table'>
				<tr>
					<th>Student</th>
					<th>Class</th>
					<th>Professor</th>
					<th>Reason</th>
					<th>Time In</th>
					<th> </th>
				</tr>");

		foreach($_SESSION['stud_in_office_table'] as $stud) {
			$t = date_create_from_format('Y-m-d H:i:s', $stud->sign_in_time);
			$t = date('H:i', $t->getTimestamp());
			$id = $stud->rowid;
			echo("<tr id='row".$stud->rowid."'>");
			echo("<td>$stud->fname $stud->lname</td>");
			echo("<td>$stud->class</td>");
			echo("<td>$stud->professor</td>");
			echo("<td>$stud->reason_for_visit</td>");
			echo("<td>$t</td>");
			echo("<td><button type='button'  name='studId' value='$id' class='signout waves-effect waves-red btn-flat'>Sign Out?</button></td>");
			echo("</tr>");
		}

		echo("</table>
			<br>");
	} elseif($amt==0) {
		echo("Welcome, friendo! No one in the office right now :-)");
	}
}
?>