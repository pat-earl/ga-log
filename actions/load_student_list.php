<?php 
/**
 * Returns the list of students as html option tags
 * 
 * Author: Tyler Stoney
 * Created: 01 Nov 2018
 */
if(!session_id()) session_start();

if(!isset($_POST['ajax'])){
	header("Location: ../index.php");
	exit;
}

echo("<option value='' selected disabled>(SELECT STUDENT)</option>");
if(isset($_SESSION['stud_ids']))
    for($i = 0; $i < count($_SESSION['stud_ids']); $i++) {
        echo("<option value='".$_SESSION['stud_ids'][$i]."'>".$_SESSION['stud_list'][$i]."</option>");
    }
?>