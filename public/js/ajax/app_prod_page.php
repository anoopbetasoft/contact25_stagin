<?php 

	include("../../include/config.php");
	include_once ("../../include/class.friendsstuff.php");
	$friends_stuff = new friendsmystuff();
	echo $friends_stuff->product_page($_SESSION['s_id']);

?>