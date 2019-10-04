<?php 

include_once('../../config.php');

/* remove it (if added twice by accident)*/
$sql = 'DELETE FROM wishlist WHERE wl_session_id = "'.session_id().'" AND wl_s_id = "'.$_POST['id_for_wishlist'].'"';
mysql_query($sql);

/* add to wishlist */
$sql = 'INSERT INTO wishlist 
			(
				wl_session_id,
				wl_s_id,
				wl_date_added
			)
		VALUES
			(
				"'.session_id().'",
				"'.$_POST['id_for_wishlist'].'",
				"'.date("Y-m-d H:i:s").'"
			)

 ';
mysql_query($sql);

?>