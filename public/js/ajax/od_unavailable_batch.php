<?php
include('../../config.php');  
include('include/includes.php'); 

if ($_SESSION['u_id'] = 22212){
	
	/*
		SPECIAL PRICE UPDATES (FROM OFFICE DEPOT SUP
	*/
	$sql = 'UPDATE
					stock_c25 
				SET  
					 s_price = 0
				WHERE 
					s_s_id = "'.$_POST['order_item_id'].'"
				';
		
	mysql_query($sql);
	
	
	/* remove it from ebay */
	
	$sql = 'UPDATE
					spares 
				SET  
					 s_price = 0,
					 spares.s_album = -2
				WHERE 
					s_id = "'.$_POST['order_item_id'].'"
				';
		
	mysql_query($sql);
	
	
	
	
	
}