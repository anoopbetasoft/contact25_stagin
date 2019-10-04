<?php 
include ("../../config.php");

$sql = 'UPDATE stages SET s_supplier = "'.$_POST['new_supplier'].'" WHERE s_id = "'.$_POST['stage_id'].'"
					
';


mysql_query($sql);
#echo $sql;