<?php 

include_once("../../include/config.php");



$sql = '
		DELETE
		FROM
			stock_room
		WHERE
			stock_room.sr_u_id = "'.$_SESSION['u_id'].'"
		AND
			sr_id = "'.$_POST['selected_room'].'"
		
		
';
mysqli_query($db,$sql);

$sql = '
		DELETE
		FROM
			stock_box
		WHERE
			stock_box.sb_sr_id = "'.$_POST['selected_room'].'"
		
		
';
mysqli_query($db,$sql);



?>