<?php 

include_once('../../config.php');

/* remove it (if added twice by accident)*/
include_once('../../libs/classes/class.display.php');
$display = new display();
echo $display->saved_addresses_show($_POST['address_selected']);

?>