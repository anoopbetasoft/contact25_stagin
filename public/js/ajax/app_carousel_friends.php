<?php 

include("../../include/config.php");

include_once ("../../include/class.friendsstuff.php");
$friends_stuff = new friendsmystuff();
echo json_encode(array($friends_stuff->home_carousel($_SESSION['c25_id']), $friends_stuff->home_friend_requests($_SESSION['c25_id'])));
?>