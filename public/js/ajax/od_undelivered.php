<?php 
 
include_once("../../include/config.php");




/* report underliered item */
$sql = "
	SELECT 
		order_details.od_o_id,
		order_details.od_qty,
		order_details.od_po,
		stock_c25.s_price_buy,
		stock_c25.s_SKU,
		stock_c25.s_label,
		users.u_name,
		users.u_email
	FROM 
		order_details,
		stock_c25,
		users
	WHERE 
		order_details.od_o_id='".$_POST['order_id']."' 
	AND 
		od_s_id = '".$_POST['s_id']."'
	AND 
		stock_c25.s_s_id = order_details.od_s_id
	AND 
		order_details.od_purchased_via = users.u_id
	";

$query = mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($query);
$num_rows = mysqli_num_rows($query);
if ($num_rows>0){
	do{
		
		include("/var/www/vhosts/contact25.com/httpdocs/classes/class.mail.php"); 
		$send_cron = new email();

		$to_email 			= "antony@contact25.com";
		$to_name			= "Antony Vila";
		$from_email       	= "info@contact25.com";
		#$from_email   		= "contact25.com";
		$from_name			= "Contact25";
		$title    			= "Product not arrived (PO ".$_POST['order_id'].")";
		$message    		= "Hi ".$row['u_name']."
		
The following product has not arrived yet:
<span style='font-weight:bold;font-size:16px;color:#FF9933;'>(SKU: ".$row['s_SKU'].")
".$row['s_label']." x ".$row['od_qty']."</span>

The purchase order was <span style='font-weight:bold;font-size:20px;color:#FF9933;'>".$row['od_po']."</span>

Please could you look into when this will be delivered?

Thanks
"; // optional, comment out and test
		$to_email 			= "delight@contact25.com";
		$email_sent			= $send_cron->cron_mail($to_email, $from_email, $to_name, $from_name, $message, $title);
		
	}while($row = mysqli_fetch_assoc($query));
}





echo "<div style='color:green; padding:20px;'><i data-icon=')' class='linea-icon linea-basic fa-fw'></i>Reported as missing <br>
Requested update</div>";


?>