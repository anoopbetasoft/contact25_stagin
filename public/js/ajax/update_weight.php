<?php
include('../../config.php');  
include('include/includes.php'); 


$sql = 'UPDATE spares SET s_weight = "'.$_POST['weight'].'" WHERE s_id = "'.$_POST['s_id'].'"';	
mysql_query($sql);

$sql = 'UPDATE stock_c25 SET s_weight = "'.$_POST['weight'].'" WHERE s_s_id = "'.$_POST['s_id'].'"';	
mysql_query($sql);

?>