<?
include('../../config.php');  
include('include/includes.php'); 

$sql = 'DELETE FROM
			order_details 
		WHERE 
			od_id = "'.$_POST['order_item_id'].'"
		';
//echo $sql;	
mysql_query($sql);


?>


	