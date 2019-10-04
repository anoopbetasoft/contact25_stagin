<?php 

include_once("../../include/config.php");



$sql = '
		SELECT 
			stock_room.sr_id
		FROM
			stock_room
		WHERE
			stock_room.sr_u_id = "'.$_SESSION['u_id'].'"
		AND
			sr_name like "%'.$_POST['selected_room'].'%"
		
		
';
$query = mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($query);
$num_rows = mysqli_num_rows($query);

if ($num_rows > 0){
	$extra_room = ' '.($num_rows+1);
}else{
	$extra_room = '';
}


/* add room */
$sql = 'INSERT INTO stock_room (sr_name, sr_u_id, sr_session_id) VALUES ("'.$_POST['selected_room'].$extra_room.'", "'.$_SESSION['u_id'].'", "'.session_id().'")';
mysqli_query($db,$sql);
/* add box */
$sql = '
		SELECT 
			stock_room.sr_id
		FROM
			stock_room
		WHERE
			stock_room.sr_u_id = "'.$_SESSION['u_id'].'"
		ORDER BY
			stock_room.sr_id DESC
		LIMIT 1
';
$query = mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($query);
$num_rows = mysqli_num_rows($query);

if ($num_rows>0){
	do{
		$sql = 'INSERT INTO stock_box (sb_sr_id, sb_num) VALUES ("'.$row['sr_id'].'", "1")';
		mysqli_query($db,$sql);
	}while($row = mysqli_fetch_assoc($query));
}





?>