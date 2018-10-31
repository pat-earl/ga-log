<?php
if(!session_id()) session_start();

date_default_timezone_set('America/New_York');

$time = date('H:i');
$date = date('Y-m-d');
$dt = $date . ' ' . $time . ':00';

if(!isset($_POST['comment']) || !isset($_POST['submitter'])){
    echo("how'd you submit an empty field? Try again.");
    exit;
}


$db = new PDO('sqlite:../data/GA_LOG.db');
if($insert_stmt = $db->prepare("INSERT INTO comments VALUES(:comment, :submitter, :subTime)")) {
    $insert_stmt->bindParam(":comment", $_POST['comment']);
    $insert_stmt->bindParam(":submitter", $_POST['submitter']);
    $insert_stmt->bindParam(":subTime", $dt);
    $insert_stmt->execute();
} else {
    echo("(sub)Mission failed, we'll get 'em next time.");
    exit;
}
echo("success!");
?>