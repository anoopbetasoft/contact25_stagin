<?php 

include("../../include/config.php");
include_once ("../../include/class.friendsstuff.php");
$friends_stuff = new friendsmystuff();
echo $friends_stuff->business_essentials_carousel($_SESSION['c25_id']);
?>