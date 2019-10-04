<?php 

include_once('../../config.php');

/* remove it (if added twice by accident)*/
$sql = 'DELETE FROM wishlist WHERE wl_session_id = "'.session_id().'" AND wl_s_id = "'.$_POST['id_wishlist_remove'].'"';
mysql_query($sql);
echo $_POST['id_wishlist_remove'];

?>