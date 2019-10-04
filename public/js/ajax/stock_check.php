<?php 








include('../../config.php');  
include('include/includes.php'); 

$sql = 'SELECT 
			*  
		FROM 
			spares, album
		WHERE 
			spares.s_label like "%'.$_POST['stock_check'].'%"
		AND
			spares.s_album = album.a_id
			
			
			
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
				<div id="add_to_amazon_and_ebay" style="padding:20px;  font-size:28px; cursor:pointer; color:black;text-align:left;">'.$row['a_name'].' >> '.$row['s_label'].' ('.$row['s_qty'].')</div>
			</td>
		</tr>
		';
	}while($row	= mysql_fetch_assoc($query));
}else{
	
}
?>


	
	
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
	
	
    
    
		<?php echo $result?>

	
	
    
                   
</table>