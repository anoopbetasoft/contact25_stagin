<?php 
include ("../../config.php");
 
$sql = 'UPDATE stages SET s_days = "'.$_POST['s_days'].'" WHERE s_id = "'.$_POST['stage_id'].'"
					
';


mysql_query($sql);
echo $sql;