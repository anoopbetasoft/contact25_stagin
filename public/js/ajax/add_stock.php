<?php 

include ("../../config.php");

$sql = 'SELECT * FROM spares where spares.s_id = "'.$_POST['id'].'"';
$query = mysql_query ($sql);
$row = mysql_fetch_assoc ($query);
$num_rows = mysql_num_rows ($query);

if ($num_rows > 0){
	
	do{
		$current_stock = $row['s_qty'];
	}while($row = mysql_fetch_assoc ($query));
	
}
/*
if ($current_stock == 0){
	$extra = ', spares.`s_change` = "Y"';
}else{
	$extra = '';
}*/
	#$extra = ', spares.s_change = "Y"';

$new_stock = $current_stock + $_POST['stock_level'];



$sql = 'UPDATE spares SET s_qty = "'.$new_stock.'", spares.s_change = "Y" WHERE s_id = "'.$_POST['id'].'"';
mysql_query($sql);
#echo $sql;
echo $new_stock;

?>