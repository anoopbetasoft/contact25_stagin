<?php 
/*
include_once('../../config.php');
/* remove basket item*//*
$sql = 'DELETE FROM cart WHERE cart.ct_id = "'.$_POST['cart_id'].'"';
mysql_query($sql);
echo $_POST['cart_id'];
*/
session_start();
$_SESSION['list_grid_view']=2;

?>