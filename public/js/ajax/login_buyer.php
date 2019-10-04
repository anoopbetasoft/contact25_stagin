<?php
include('../../config.php');  
include('include/includes.php'); 
session_start();	
$sql = 'SELECT 
			*  
		FROM 
			buyers 
		WHERE 
			
				b_email = "'.$_POST['buyer_email'].'"
			AND
				b_pass = "'.$_POST['buyer_password'].'"
			
			
			
		';

$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		$_SESSION['logged_in'] = 1;
		$_SESSION['buyer_username'] = $row['b_name'];
		$_SESSION['buyer_id'] = $row['b_id'];
		
	}while($row	= mysql_fetch_assoc($query));
}
?>


	