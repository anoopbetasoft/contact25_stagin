<?php 
include('../../config.php');  
include('include/includes.php'); 

$sql = 'INSERT INTO 
			spares
				(
					s_u_id,
					s_ISBN10,
					s_amazon_URL,
					s_album
				)
			VALUES
				(
					-1,
					"'.$_POST['isbn10'].'",
					"'.$_POST['url'].'",
					-1
					
				)
			
			';
mysql_query($sql);

$sql 		= 'SELECT s_id FROM spares ORDER BY s_id DESC LIMIT 1';
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows>0){
	## GRAB IT ##
	do{
		
		$sql = 'UPDATE stock SET  s_s_id = "'.$row['s_id'].'" WHERE s_id = "'.$_POST['item_id'].'"';
		mysql_query($sql);
		
		
	}while($row	= mysql_fetch_assoc($query));
	
}


	
