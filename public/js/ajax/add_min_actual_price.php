<?php 
include('../../config.php');  
include('include/includes.php'); 

$sql = 'UPDATE 
			spares
		SET
			s_min_price = "'.$_POST['min_selling_price'].'",
			s_price = "'.$_POST['starting_price'].'",
			s_album = 17
		WHERE
			s_id = "'.$_POST['item_id'].'"
				
			
			';
mysql_query($sql);
$sql = 'UPDATE 
			stock
		SET
			s_amazon_listed = 1
		WHERE
			s_s_id = "'.$_POST['item_id'].'"
		
				
			
			';
mysql_query($sql);



echo $_POST['item_id'];




	
