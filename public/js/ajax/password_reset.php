<?php 
include_once('../../config.php');
/* remove basket item*/
/*

$sql = 'DELETE FROM cart WHERE cart.ct_id = "'.$_POST['cart_id'].'"';
mysql_query($sql);
echo $_POST['cart_id'];
*/


	$sql = 'SELECT u_id, u_pass FROM users WHERE users.u_email = "'.$_POST['forgotton_email'].'" LIMIT 1';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	$basket_list = '';
	
	if ($num_rows>0){
		do{
			
			include("../../libs/classes/class.mail.php"); 
			$send_mail = new email();
			$to_email 			= $_POST['forgotton_email'];
			$to_name			= $_POST['forgotton_email'];
			$from_email       	= "ebay@contact25.com";
			$from_email   		= "contact25.com";
			$title    			= "contact25.com";
			$new_pass			= generatePassword();
			$message    		= "Thank you for requesting a new password on contact25.com.
			
Your password has been reset to: ".$new_pass; // optional, comment out and test
			$sql				= 'UPDATE users SET u_pass = "'.$new_pass.'" WHERE u_id = "'.$row['u_id'].'"';
			mysql_query($sql);
			$send_mail->sendemail($to_email, $from_email, $to_name, $from_name, $message, $title);
			echo 1; #success
		}while($row = mysql_fetch_assoc($query));
	}else{
		echo 0;
	}


function generatePassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}
?>