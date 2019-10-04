<?
include('../../config.php');  


$sql = 'UPDATE
			order_details 
		SET 
			od_suggestion = "'.$_POST['suggestion'].'"
		WHERE
			od_id = "'.$_POST['order_item_id'].'"
		';
#echo $sql;	
mysql_query($sql);

if ($_POST['suggestion'] == 1){
	echo '<i class="fa fa-history"></i> Waiting (Int Delivery?)';
}

if ($_POST['suggestion'] == 2){
	echo '<i class="fa fa-history"></i> Waiting (Cancellation?)';
}




?>


	