<?php 
 
include_once("../../include/config.php");

$sql = 'UPDATE spares SET s_next_format = "'.$_POST['nextformat'].'" WHERE s_id = "'.$_POST['s_id'].'"';

#$test = 'UPDATE test SET value = "'.addslashes($sql).'"';
mysqli_query($db,$sql);


?>