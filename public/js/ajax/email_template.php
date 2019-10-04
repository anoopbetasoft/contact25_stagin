<?php 
include('../../config.php');  

include_once('../../libs/classes/class.mail.php');
$email = new email();
echo $email->email_template_load($_POST['template'],$_POST['order_item_id']);

?>

	
	
	