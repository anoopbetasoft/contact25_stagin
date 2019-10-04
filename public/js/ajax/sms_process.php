<?php 
include('../../config.php');  
include('include/includes.php'); 

$sql = 'SELECT * FROM users WHERE users.u_email = "avila@jukeboxmarketing.com" limit 1
		';
		#die($sql);
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		
		echo $row['u_mob'];
		
	}while($row	= mysql_fetch_assoc($query));
}
?>
