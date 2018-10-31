<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
<div class="container">
	<h3>the peanut gallery.</h3>
	<p><a style='cursor: pointer;' onclick="history.go(-1);">< back</a></p>
	<div class='section'>
		<div id='comments_go_here'>
		</div>
	</div>
	<div class='section'>
		<form method="POST">
			<div class="input-field col s12">
			<textarea type="textarea" maxlength="128" id='comment-data' class="materialize-textarea" name='comment' placeholder="Boy howdy is this log neato" required data-length="128"></textarea>
			<label for='comment-data'>Comment</label>
			</div>

			<div class="input-field col s12">
			<label>Title</label>
			<input type="text" maxlength="64" id='short-data' name='submitter' placeholder="Your name, a title, I don't care. Just keep it brief." required data-length="64"></input>
			</div>

			<button id='submit-comment' class="waves-effect waves-light btn" type="button">Submit
				<!-- <i class="material-icons right">send</i> -->
			</button>
		</form>
	</div>
</div>

<script	src="https://code.jquery.com/jquery-3.3.1.min.js"
	  	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  	crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src='./scripts/comments.js'></script>
</body>
</html>