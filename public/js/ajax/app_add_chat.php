<?php 

include("../../include/config.php");

 
include_once ("../../include/class.chat.php");
$chat = new chat();
echo $chat->add_chat($_POST['chat_message'],$_SESSION['c25_id'], $_POST['u_id_2']);


?>