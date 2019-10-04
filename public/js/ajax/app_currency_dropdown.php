<?php 

include("../../include/config.php");
include_once ("../../include/class.friendsstuff.php");
$friends_stuff = new friendsmystuff();
echo $friends_stuff->currency_dropdown($_SESSION['c25_id']);
?> 