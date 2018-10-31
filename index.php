<?php
if(!session_id()) session_start();

date_default_timezone_set('America/New_York');
$time = date('H:i');
$date = date('Y-m-d');
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
	<title>GA Support Log</title>
	<style>
	::placeholder { 
		color:#333;
		opacity:1;
	}
	</style>
</head>

<?php 
/* if(isset($_SESSION['stud_in_office_table']) and 
isset($_SESSION['stud_in_office'])	and
isset($_SESSION['stud_ids'])			and
isset($_SESSION['stud_list'])) { */
?>
<!-- <body onload="init();"> -->
<body>

<div class="container center-align">
	<h2>Gradbusters' Student Time Log</h2>
</div>

<div class='container' id='tableDiv'>
<!-- Table will be populated here -->
</div>

<div class="container">
	<div class="row center-align">
		<button class="waves-effect waves-light btn" id='show_signin' onclick="showDiv('show_signin', 'sign_in');">Sign In</button>
		<button class="waves-effect waves-light btn" id='show_add' onclick="showDiv('show_add', 'add_new_student');">Add Student</button>
	</div>

	<!-- Primary Sign in/out form -->
	<div class='section' id='sign_in' style="display:none;">


		<h3>Student Sign-In</h3>


		<form method="POST">

			<!-- Student:  -->
			<div class="input-field col s12">
			<select id='student_dropdown' name='student_dropdown' required>
				<option value='' selected disabled>(SELECT STUDENT)</option>
				<?php 
				if(isset($_SESSION['stud_ids']))
					for($i = 0; $i < count($_SESSION['stud_ids']); $i++) {
					    echo("<option value='".$_SESSION['stud_ids'][$i]."'>".$_SESSION['stud_list'][$i]."</option>");
					}
				?>
			</select>
			<label>Student</label>
			</div><br>

			<!-- Class: -->
			<div class="input-field col s12">
				<select id='class_dropdown' class="no-autoinit" name='class_dropdown' onchange="populateProf();" required>Choose your opt
					<option value='' selected disabled>(SELECT CLASS)</option>
				</select>
				<label>Class</label>
			</div>

			<div class="input-field col s12" id='other_class' name='other_class' style='display:none;'>
				<input type='text' placeholder="What class are they here for, then?"/><br>
			</div>
			<div id='none'></div>

			<!-- Professor: --> 
			<div class="input-field col s12">
				<select id='prof_dropdown' name='prof_dropdown' disabled>
					<option value='' selected disabled>(SELECT PROFESSOR)</option>
				</select>
				<label>Professor</label>
			</div><br>

			<!-- Reason for visit: -->
			<input type='text' name='reason' placeholder="Reason for visit"/><br>

			<br>
			<div class="input-field col s12">
				<select multiple id='ga_select' class="no-autoinit" name='ga_select[]'>
				</select>
				<label>GA(s) Select</label>
			</div><br>

			<br>
			<label>Date</label>
			<input type="text" class="datepicker" name="today" value=<?php echo "$date"; ?>>
			
			<label>Time In</label>
			<input type="text" class="timepicker no-autoinit" name='time_log' value=<?php echo "$time"; ?> min="8:00" max="18:00">

			<br>

			<button class="signinstud waves-effect waves-light btn" type="button">Submit
				<!-- <i class="material-icons right">send</i> -->
			</button>
		</form>
	</div>

	<!-- Add new student to the database -->
	<div class='section' id='add_new_student' style="display:none;">
		<h3>Add new student</h3>
		<form method="POST">
			<input type="text" name='fname_create' placeholder="First Name">
			<input type="text" name='lname_create' placeholder="Last Name">
			<br>
			<button class="addstud waves-effect waves-light btn" type='button'>Submit</button>
		</form>
	</div>
	<br>
	<div class='section'>
		<a href='./comments.php'><h6>Have a suggestion?</h6></a>
	</div>
</div>

<?php //} ?>

<script	src="https://code.jquery.com/jquery-3.3.1.min.js"
	  	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  	crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="./scripts/scripts.js"></script>

</body>



</html>
