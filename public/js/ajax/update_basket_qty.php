<?php 
include_once('../../config.php'); 
/* remove basket item*/
$sql = 'UPDATE cart SET cart.ct_qty = "'.$_POST['new_qty'].'" WHERE ct_id = "'.$_POST['cart_id'].'"';
mysql_query($sql);
#echo  $sql;


 
?>