<?php
include('../../config.php');  
include('include/includes.php'); 

if ($_SESSION['u_id'] = 22212){
	
	/*
		SPECIAL PRICE UPDATES (FROM OFFICE DEPOT SUP
	*/
	$sql = 'UPDATE
					order_details 
				SET  
					 od_purchased_via = "22806"
				WHERE 
					od_id = "'.$_POST['order_item_id'].'"
				';
	mysql_query($sql);
	
}