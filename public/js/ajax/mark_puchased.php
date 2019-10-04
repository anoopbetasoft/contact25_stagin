<?php 








include('../../config.php');  
include('include/includes.php'); 

$sql = 'SELECT 	
				od_purchased_via
			FROM
				order_details
			WHERE
				od_id = "'.$_POST['item_purchased'].'"';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	do{
		/* who did we purchase from */
		$od_purchased_via = $row['od_purchased_via'];	
	}while($row = mysql_fetch_assoc($query));

/* the purchaser is Amazon - quick dispatch ok*/
if (($od_purchased_via == '2') || ($od_purchased_via == '01482') ){
	$dispatch_date = date("Y-m-d H:i:s");
}else{
	$days_to_add = 2; // 2 working days for dispatch notification to be triggered on ebay //
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
			$dispatch_date = date("Y-m-d H:i:s", strtotime("+".$i." day"));
			$days_to_add = $days_to_add-1;
		}
		$i ++;
		
		
	}while($days_to_add>0);
}


$sql = 'UPDATE order_details SET order_details.od_purchased = 1, od_mark_dispatched_date = "'.$dispatch_date.'" WHERE od_id = "'.$_POST['item_purchased'].'"';
mysql_query($sql);

#$query		= mysql_query($sql);

		$result .= '
		<tr>
			<td colspan="2">
				<div id="add_to_amazon_and_ebay" style="padding:20px;  font-size:28px; cursor:pointer; color:black;text-align:left;">Order Purchased ('.$sql.')</div>
			</td>
		</tr>
		';
	

?>


	
	
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
	
	
    
    
		<?php echo $result?>

	
	
    
                   
</table>