<?php 

include("../../include/config.php");

echo json_encode(array(
	$_SESSION['c25_id'].'_1',
	$_SESSION['c25_id'].'_2',
	$_SESSION['c25_id'].'_3',
	$_SESSION['c25_id'].'_4',
	$_SESSION['c25_id'].'_5',
	$_SESSION['c25_id'].'_6',
	$_SESSION['c25_id'].'_7',
	$_SESSION['c25_id'].'_8',
	$_SESSION['c25_id'].'_9',
	$_SESSION['c25_id'].'_10',
	$_SERVER['HTTP_HOST']
)); 


?>