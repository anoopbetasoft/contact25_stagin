<?php 

include("../../include/config.php");
include_once ("../../include/class.chat.php");
$chat = new chat();
echo $chat->load_chat($_SESSION['c25_id']);

?>
