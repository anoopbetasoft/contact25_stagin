<?php 
include('../../config.php');  

if (strlen($_POST['voucher_code'])>0){
	include_once('../../libs/classes/class.basket.php');
	$basket = new basket();
	$basket->apply_voucher($_POST['voucher_code']);
}