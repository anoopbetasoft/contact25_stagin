<?php 
include ("../../config.php");

$sql = 'INSERT INTO stage_elements (
						s_id,
						s_e_name
						)
					VALUES
						(
							"'.$_POST['stage_id'].'",
							"'.$_POST['add_item'].'"
						)
';


mysql_query($sql);
echo $sql;