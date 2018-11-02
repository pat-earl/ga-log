<div class="divider"></div>
<div class='section'>
    <div class="row">
        <div class="col s3">
            <a href='./comments.php'><h6>Have a suggestion?</h6></a>
        </div>

        <div class="col s6"></div>

        <div class="col s3">
            <select name='theme' id='theme'>
                <option value='light' selected>Light (default)</option>
                <option value='dark'>Dark</option>
            </select>
        </div>
    </div>
</div>
<script	src="https://code.jquery.com/jquery-3.3.1.min.js"
	  	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  	crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
$( "#theme" ).change(function() {
  alert( "Handler for .change() called." );
});
</script>