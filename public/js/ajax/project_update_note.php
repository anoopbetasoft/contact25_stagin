<?php 
include ("../../config.php");

$sql = 'UPDATE stages SET s_notes = "'.$_POST['new_note'].'" WHERE s_id = "'.$_POST['stage_id'].'"
					
';


mysql_query($sql);
#echo $sql;