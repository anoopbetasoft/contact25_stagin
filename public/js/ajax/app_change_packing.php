<?php 
 
include_once("../../include/config.php");

$sql = 'UPDATE spares SET s_packing = "'.$_POST['format'].'" WHERE s_id = "'.$_POST['s_id'].'"';

#$test = 'UPDATE test SET value = "'.addslashes($sql).'"';
mysqli_query($db,$sql);

if ($_POST['format'] == 0){
	echo '';
}

if ($_POST['format'] == 1){
	echo 'A/000'; 	
}
if ($_POST['format'] == 2){
	echo 'B/00'; 	
}
if ($_POST['format'] == 3){
	echo 'C/0'; 	
}
if ($_POST['format'] == 4){
	echo 'D/1'; 	
}
if ($_POST['format'] == 5){
	echo 'E/2'; 	
}
if ($_POST['format'] == 6){
	echo 'F/3'; 	
}
if ($_POST['format'] == 7){
	echo 'G/4'; 	
}
if ($_POST['format'] == 8){
	echo 'H/5'; 	
}
if ($_POST['format'] == 9){
	echo 'J/6'; 	
}
if ($_POST['format'] == 10){
	echo 'K/7'; 	
}
if ($_POST['format'] == 11){
	echo 'BOX'; 	
}


?>