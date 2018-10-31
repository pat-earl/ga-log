<?php

if(!session_id()) session_start();

date_default_timezone_set('America/New_York');

$comments = [];
if(isset($_SESSION['comments']))
    $comments = $_SESSION['comments'];
    
if(count($comments) == 0){
    echo("<h5>It's lonely out here :( Add a comment, suggestion, fan mail, hate mail!</h5>");
    exit;
} else {

    $i = 0;
    foreach($comments as $row){
        $i++;
        if($i%2==1)
            echo("<div class='row'>");

        echo("<div class='col s12 m5'>
                <div class='card blue-grey darken-1'>
                    <div class='card-content white-text'>
                        <span class='card-title'>$row->submitter</span>
                        <p>$row->comment</p>
                    </div>
                </div>
            </div>");
        if($i%2==0)
            echo("</div><br>");
    }
    exit;
}
?>