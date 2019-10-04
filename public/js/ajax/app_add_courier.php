<?php 

include_once("../../include/config.php");



$sql = 'INSERT INTO 
			users_delivery_provider
				(
				udp_u_id,
				udp_name, 
				udp_url
				) 
		VALUES
				(
				"'.$_SESSION['c25_id'].'", 
				"'.$_POST['deliver_name'].'", 
				"'.$_POST['deliver_link'].'"
				)
		';

	//die($sql);

mysqli_query($db,$sql);




?>