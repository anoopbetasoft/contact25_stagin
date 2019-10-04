<?php 
include('../../config.php');  
include('include/includes.php'); 

/*select the user email*/
$sql = 'SELECT u_email,u_name  FROM users WHERE u_id = "'.$_POST['user_id'].'"';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	do{
		$user_email = $row['u_email'];
		$u_name = $row['u_name'];
	}while($row = mysql_fetch_assoc($query));
}

/*email template*/
$sql = 'SELECT * FROM email_templates WHERE et_id = "'.$_POST['delivery'].'"';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	do{
		$e_title = $row['et_title'];
		$e_body = $row['et_body'];
	}while($row = mysql_fetch_assoc($query));
}

/*order details*/
$sql = 'SELECT od_num FROM order_details WHERE od_o_id = "'.$_POST['order_id'].'"';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	$order_details = '';
	do{
		$order_details .= $row['od_num'].'
		';
	
	}while($row = mysql_fetch_assoc($query));
}




/* reformat email body */
$e_body = str_replace('<#NAME>',$u_name,$e_body);
$e_body = str_replace('<#ORDER_LIST>',$order_details,$e_body);
/* delivery dates */
if ($_POST['timeframe'] == 0){
	/*standard - +9 working days*/	
	$days_to_add = 9;
	$i = 1;
	
	do{
		$day_of_the_week = date("N", strtotime("+".$i." day"));
		
		/* if it's not a weekend*/
		if (
			($day_of_the_week == 6)
			||
			($day_of_the_week == 7)
			) 
		{}else{
			$est_delivery_date = date("jS F Y", strtotime("+".$i." day"));
			$est_delivery_date_raw = date("Y-m-d H:i:s", strtotime("+".$i." day"));
			$days_to_add = $days_to_add-1;
		}
		$i ++;
		
		
	}while($days_to_add>0);
	

	/*standard - +9 working days*/	
	$days_to_add = 15;
	
	do{
		$day_of_the_week = date("N", strtotime("+".$i." day"));
		
		/* if it's not a weekend*/
		if (
			($day_of_the_week == 6)
			) 
		{}else{
			$complain_date = date("jS F Y", strtotime("+".$i." day"));
			$days_to_add = $days_to_add-1;
		}
		$i ++;
		
		
	}while($days_to_add>0);
	

	
}

if ($_POST['timeframe'] > 0){

	$est_delivery_date = date("jS F Y", strtotime("+".$_POST['timeframe']." day"));
	$est_delivery_date_raw = date("Y-m-d H:i:s", strtotime("+".$_POST['timeframe']." day"));

}

if (strlen($complain_date)>0){
	## you've got a complain date	
}else{
	$complain_date = date("jS F Y", strtotime($est_delivery_date_raw."+10 days"));
}


/* reformat email body */
$e_body = str_replace('<#DELIVERYDATE>',$est_delivery_date,$e_body);
$e_body = str_replace('<#COMPLAIN_DATE>',$complain_date,$e_body);
$e_title = str_replace('<#ORDER_ID>',$_POST['order_id'],$e_title);



$sql = 'INSERT INTO 
		emails 
			(
				e_to,
				e_from,
				e_o_id,
				e_title,
				e_message,
				e_delivery_date,
				e_sendafter
			)
	VALUES
			(
				"'.$user_email.'",
				"info@contact25.com",
				"'.$_POST['order_id'].'",
				"'.$e_title.'",
				"'.$e_body.'",
				"'.$est_delivery_date_raw.'",
				"'.date('Y-m-d H:i:s').'"
			)
			

';

mysql_query($sql);

/*email template - follow up emails */
if ($_POST['delivery'] == 1){
	
	/**/
	$sql = 'SELECT * FROM email_templates WHERE et_id = 5 OR et_id = 6';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	if ($num_rows>0){
		do{
			$e_title = $row['et_title'];
			$e_body = $row['et_body'];
			/* reformat email body */
			$e_body = str_replace('<#NAME>',$u_name,$e_body);
			$e_body = str_replace('<#ORDER_LIST>',$order_details,$e_body);
			$e_body = str_replace('<#DELIVERYDATE>',$est_delivery_date,$e_body);
			$e_body = str_replace('<#COMPLAIN_DATE>',$complain_date,$e_body);
			$e_title = str_replace('<#ORDER_ID>',$_POST['order_id'],$e_title);
			
			/* dispatched message */
			if ($row['et_id'] == 5){
				$send_date = date("Y-m-d H:i:s", strtotime("+2 day"));
			}
			/* delivered message */
			if ($row['et_id'] == 6){
				$send_date = date("Y-m-d H:i:s", strtotime($est_delivery_date_raw));
			}
			
			
			$sql = 'INSERT INTO 
					emails 
						(
							e_to,
							e_from,
							e_o_id,
							e_title,
							e_message,
							e_sendafter
						)
				VALUES
						(
							"'.$user_email.'",
							"info@contact25.com",
							"'.$_POST['order_id'].'",
							"'.$e_title.'",
							"'.$e_body.'",
							"'.$send_date.'"
						)
						
			
			';
			
			mysql_query($sql);
			
		}while($row = mysql_fetch_assoc($query));
	}
}




?>EMAIL SENT