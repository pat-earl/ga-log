<?php
/**
 * Fetches and stores the 10 most recent comments 
 * (from the last 7 days) in the session.
 * 
 * Author: Tyler Stoney
 * Created: 29 Oct 2018
 */
if(!session_id()) session_start();

if(!isset($_POST['ajax'])){
	header("Location: ../index.php");
	exit;
}

$db = new PDO('sqlite:../data/GA_LOG.db');
$comment_query = $db->prepare("SELECT * FROM 'comments' WHERE time BETWEEN datetime('now', '-7 days') AND datetime('now', 'localtime') ORDER BY time LIMIT 10");
$comment_query->execute();

$comments = [];
while($row = $comment_query->fetchObject()) {
	$comments[] = $row;
}

$_SESSION['comments'] = $comments;
echo("success");
?>