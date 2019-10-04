<?php 
 
include("../../include/config.php");
include_once ("../../include/class.mystuff.php");

$mystuff = new mystuff();
echo $mystuff->add_friendship_group($_SESSION['c25_id'], $_POST['friendship_group_to_add'], $_POST['sell_lend']); 
 

?>