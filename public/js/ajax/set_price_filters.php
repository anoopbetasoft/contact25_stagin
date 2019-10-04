<?php 
/*
include_once('../../config.php');
/* remove basket item*//*
$sql = 'DELETE FROM cart WHERE cart.ct_id = "'.$_POST['cart_id'].'"';
mysql_query($sql);
echo $_POST['cart_id'];
*/
session_start();

$values = explode('£', $_POST['amount'] );


$_SESSION['PRICE_FILTER_LOW'] = preg_replace("/[^0-9,.]/", "", $values[1]);
$_SESSION['PRICE_FILTER_HIGH'] = preg_replace("/[^0-9,.]/", "", $values[2]);

?>