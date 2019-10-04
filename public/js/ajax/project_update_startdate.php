<?php 
include ("../../config.php");
 
$sql = 'UPDATE stages SET s_startdate = "'.date("Y-m-d", strtotime($_POST['s_startdate'])).'" WHERE s_id = "'.$_POST['stage_id'].'"
					
';


mysql_query($sql);
echo $sql;