<?php 
include('../../config.php');  
include('include/includes.php'); 

$sql = 'UPDATE 
			stock
		SET
			s_s_id = -1
		WHERE
			s_id = "'.$_POST['item_id'].'"
				
			
			';
mysql_query($sql);
echo $_POST['item_id'];




	
