<?php
if(!session_id()) session_start();

$db = new PDO('sqlite:../data/GA_LOG.db');
$comment_query = $db->prepare("SELECT * FROM 'comments' WHERE time BETWEEN datetime('now', '-14 days') AND datetime('now', 'localtime')");
$comment_query->execute();

$comments = [];
while($row = $comment_query->fetchObject()) {
	$comments[] = $row;
}

$_SESSION['comments'] = $comments;
echo("success");
?>