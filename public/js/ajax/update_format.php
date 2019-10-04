<?php
include('../../config.php');  
include('include/includes.php'); 


$sql = 'UPDATE spares SET s_format = "'.strtoupper($_POST['format']).'" WHERE s_id = "'.$_POST['s_id'].'"';	
mysql_query($sql);
?>