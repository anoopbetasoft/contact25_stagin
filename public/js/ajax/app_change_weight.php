<?php 
 
include_once("../../include/config.php");

$sql = 'UPDATE spares SET s_weight = "'.$_POST['weight'].'" WHERE s_id = "'.$_POST['s_id'].'"';

#$test = 'UPDATE test SET value = "'.addslashes($sql).'"';
mysqli_query($db,$sql);

$sql = 'UPDATE stock_c25 SET s_weight = "'.$_POST['weight'].'" WHERE s_s_id = "'.$_POST['s_id'].'" AND (s_u_id = "'.$_SESSION['u_id'].'" OR s_u_id = "22212")';

#$test = 'UPDATE test SET value = "'.addslashes($sql).'"';
mysqli_query($db,$sql);


?>