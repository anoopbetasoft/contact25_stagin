<?php 

include('../../config.php');  
include('include/includes.php'); 
	

	
$sql = 'INSERT INTO 
			spares
				(
					s_u_id,
					s_cheapest_amazon,
					s_min_price,
					s_cheapest_check,
					s_label,
					s_price,
					s_qty,
					s_album
				)
			VALUES
				(
					-1,
					"'.$_POST['price'].'",
					"'.$_POST['min_selling_price'].'",
					"'.date("Y-m-d H:i:s").'",
					"'.$_POST['title'].'",
					"'.$_POST['price'].'",
					"'.$_POST['me_too_qty'].'",
					"22"
					
				)
';	
#echo $sql;
mysql_query($sql);


$sql = 'SELECT 
			* 
		FROM 
			spares
		ORDER BY 
			spares.s_id DESC
		LIMIT 1
		';
		#die($sql);
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		
		$result .= ' 
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:green;color:WHITE;text-align:center;">NEW SKU: SKU-CONTACT25-'.$row['s_id'].' (qty: '.$row['s_qty'].') (price: '.$row['s_price'].')</div>
			</td>
		</tr>
		';
	}while($row	= mysql_fetch_assoc($query));
}else{
	$result .= ' 
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:red;color:black;text-align:center;">ERROR - nothing added</div>
			</td>
		</tr>
		';
}
?>


	
	
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
	
	
    
    
		<?php echo $result?>

	
	
    
                   
</table>