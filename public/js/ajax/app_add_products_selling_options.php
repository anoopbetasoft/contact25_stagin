<?php 

include("../../include/config.php");
include_once ("../../include/class.mystuff.php");

$mystuff = new mystuff();
echo $mystuff->selling_lending_options_display($_SESSION['c25_id']); 
 

?>