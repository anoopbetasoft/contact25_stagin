<?php 

include_once("include/config.php");
global $db;

session_start();

/*
	if (preg_match("/-/", $_POST['var_search'])) {
    	$check_amazon_or_order_id = '
				orders.o_amazon_order_id like "%'.$_POST['var_search'].'%"';
	} else {
		$check_amazon_or_order_id = '
				orders.o_id = "'.$_POST['var_search'].'"';
	}*/
	
//die("123");
		  include_once ("../../include/class.mystuff.php");
		  $mystuff = new mystuff();die("123".$_SESSION['u_id']);
		  echo $mystuff->list_rooms($_SESSION['u_id']);
		  
		  ?>
     


