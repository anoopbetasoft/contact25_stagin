<?php 
 
include_once("../../include/config.php");

$sql = 'UPDATE spares SET s_format = "'.$_POST['format'].'" WHERE s_id = "'.$_POST['s_id'].'"';

#$test = 'UPDATE test SET value = "'.addslashes($sql).'"';
mysqli_query($db,$sql);

?>