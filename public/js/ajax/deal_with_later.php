<?php 








include('../../config.php');  
include('include/includes.php'); 

$sql = 'UPDATE orders SET orders.o_dealwithlater = 1 WHERE o_id = "'.$_POST['item_message'].'"';
#die($sql);
mysql_query($sql);

#$query		= mysql_query($sql);

		
	

?>


	
	
	