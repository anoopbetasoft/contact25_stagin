<?php 
/* You might want some authentication here */
/* check authentication */
/* Authentication ended. */
$url = 'http://www.gardners.com/gardners/accountholders/ordertools/Search/SearchKeyword.aspx'; //Edit your target here


rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, 'uiSearchBox='.$_POST['barcode']);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);




die($_POST['barcode']);
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