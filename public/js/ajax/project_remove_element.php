<?php 
include ("../../config.php");

$sql = 'DELETE FROM stage_elements WHERE s_e_id = "'.$_POST['element_id'].'"
					
';


mysql_query($sql);
echo $sql;