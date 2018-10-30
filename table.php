<?php
if(!session_id()) session_start();

date_default_timezone_set('America/New_York');

if(!isset($_SESSION['stud_in_office_table'])) {
	echo("couldn't update table. Maybe try refreshing the page?");
	exit;
} else{

	$amt = count($_SESSION['stud_in_office_table']);

	if($amt>0){
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
			$id = $stud->student_id;
			echo("<tr id='row$id'>");
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