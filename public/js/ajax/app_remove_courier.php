<?php 

include_once("../../include/config.php");

global $db;

$sql = '
		DELETE
			FROM
				users_delivery_provider
			WHERE
				users_delivery_provider.udp_id = "'.$_POST['courier_id'].'"			
		';
//die($sql);
mysqli_query($db,$sql);

?>