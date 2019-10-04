<?php 
include ("../../config.php");

$sql = 'UPDATE stages SET s_cat = "'.$_POST['new_cat'].'" WHERE s_id = "'.$_POST['stage_id'].'"
					
';


mysql_query($sql);
#echo $sql;