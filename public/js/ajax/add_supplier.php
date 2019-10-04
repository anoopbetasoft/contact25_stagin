<?php 
include('../../config.php');  
include('include/includes.php'); 

/*select the user email*/
$sql = 'SELECT * FROM users WHERE users.u_amazon_supplier_name = "'.$_POST['new_supplier_name'].'"';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	echo -1;
}else{
	$sql = 'INSERT INTO 
				users 
					(
						u_name,
						u_amazon_supplier_name
					)
			VALUES
					(
						"'.$_POST['new_supplier_name'].'",
						"'.$_POST['new_supplier_name'].'"
					)
	';

	mysql_query($sql);
	
	/* generate supplier dropdown*/
	$sql 		= 'SELECT * FROM users WHERE (LENGTH(users.u_amazon_supplier_name)>0) ORDER BY u_name';
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	if ($num_rows >0){
		$suppliers = '<option>Chosen Supplier >></option>';
		$suppliers .= '<option value="-1" style="color:red;font-weight:bold;"><< ADD NEW >></option>';
		do{
			$style = 'style="color:grey;"';
			if ($row['u_no_delivery_price_confirmed'] == -1){
				$style = 'style="color:green;"';
			}
			if ($row['u_no_delivery_price_confirmed'] == -2){
				$style = 'style="color:red;"';
			}
			$suppliers .= '<option value="'.$row['u_id'].'" '.$style.'>'.$row['u_amazon_supplier_name'].'</option>';
			
		}while($row		= mysql_fetch_assoc($query));
	}
	echo $suppliers;
}







?>