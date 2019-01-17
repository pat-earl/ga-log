<?php require './pages/header.php' ?>

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

<body>

<div class="container center-align">
	<h2>Gradbusters' Student Time Log</h2>
</div>

<div class='container' id='tableDiv'>
<!-- Table will be populated here -->
</div>

<div class="container">
	<!-- Primary Sign in/out form -->
	<div class='section' id='sign_in'>


		<h3>Records Lookup</h3>


		<!-- Student:  -->
		<form method="POST" id="name-form">
			<div class="input-field col s12">
			<input type="text" id="student-auto" class="autocomplete" onchange='changeSelectDom();'>
  			<label for="student-auto">Student</label>
			</div>
			<div class="input-field col s12" style='display:none;'>
			<select id='student_dropdown' name='student_dropdown' style='display:none;'>
			</select>
			</div>
			<button class="submit waves-effect waves-light btn" type="button">Submit
			</button>
		</form>

		<br>

		<!-- Class: -->
		<form method="POST" id="class-form">
			<div class="input-field col s12">
				<select id='class_dropdown' class="no-autoinit" name='class_dropdown' required>Choose your opt
					<option value='' selected disabled>(SELECT CLASS)</option>
				</select>
				<label>Class</label>
			</div>

			<button class="submit waves-effect waves-light btn" type="button">Submit
			</button>
		</form>

		<br>

		<!-- Date: -->
		<form method="POST" id="date-form">
			<label>Date</label>
			<input type="text" class="datepicker" name="today" value=<?php echo "$date"; ?>>
			<button class="submit waves-effect waves-light btn" type="button">Submit
			</button>
		</form>

			
	</div>
	<br>
	<?php require './pages/footer.php' ?>
</div>

<script src="./scripts/lookup_scripts.js"></script>

</body>



</html>
