<?php 
session_start();
include_once('../../config.php');
/* login*/
if (!filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)) {
		  	echo  -1;
		}else{
	$sql = 'SELECT u_id FROM users WHERE users.u_email = "'.$_POST['u_email'].'" LIMIT 1';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	$basket_list = '';

	if ($num_rows>0){
		echo 1;
	}else{
		echo 0;
	}

}
?>