<?php 
include ("../../config.php");
 
$sql = 'UPDATE stage_uploads SET s_deleted = "1" WHERE s_u_id = "'.$_POST['upload_remove'].'"
					
';


mysql_query($sql);
echo $sql;