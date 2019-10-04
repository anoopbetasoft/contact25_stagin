<?php 
include('../../config.php');  
include('include/includes.php'); 

$sql = 'UPDATE order_details SET order_details.od_purchased_via = '.$_POST['supplier_id'].' WHERE od_id = '.$_POST['od_id'].'';
#die($sql);
#$sql = 'UPDATE test SET value='.$sql.'';
#die($sql);
mysql_query($sql);
#mysql_query($sql);

#$query		= mysql_query($sql);

		
	

?>


	
	
	