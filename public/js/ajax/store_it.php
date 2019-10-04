<?php 

include ("../../config.php");

if (preg_match("[Paperback]", $_POST['item_title'])){
	$extra_field 	= 	"s_cat,";
	$extra_insert 	= 	"'BOOK',";
}
if (preg_match("[Hardback]", $_POST['item_title'])){
	$extra_field 	= 	"s_cat,";
	$extra_insert 	= 	"'BOOK',";
}
if (preg_match("[DVD]", $_POST['item_title'])){
	$extra_field 	= 	"s_cat,";
	$extra_insert 	= 	"'DVD',";
}

						
$sql = "INSERT INTO spares 
						(
							s_u_id,
							s_box,
							s_room,
							s_label,
							s_desc,
							s_qty,
							s_price,
							s_ISBN13,
							".$extra_field."
							s_album,
							s_condition
							
						)
					VALUES
						(
							'".$_POST['user']."',
							'".$_POST['box']."',
							'".$_POST['room']."',
							'".addslashes($_POST['item_title'])."',
							'".addslashes($_POST['item_desc'])."',
							'".$_POST['qty']."',
							'".$_POST['price']."',
							'".$_POST['barcode']."',
							".$extra_insert."
							'7',
							'".$_POST['condition_type']."'
						)

";
#die($sql);
mysql_query ($sql);
$sql = 'SELECT s_id FROM spares ORDER BY s_id DESC LIMIT 1';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	do{
		session_start();
		$_SESSION['last_saved_s_id'] = $row['s_id'];
	}while($row = mysql_fetch_assoc($query));
}
echo 'Stored it.<br>
<br>
SKU-CONTACT25-'.$_SESSION['last_saved_s_id'];
$large_image = $_POST['large_image'];
$newfile = '/home/vhosts/contact25.com/httpdocs/uploads/7_'.$_SESSION['last_saved_s_id'].'.jpg';

	
	if(!@copy($large_image,$newfile))
		{
   		 	$errors= error_get_last();
    		echo "COPY ERROR: ".$errors['type'];
   		 	echo "<br />\n".$errors['message'];
	} else {
   			#echo "File copied from remote!";

	}	

/*
$query = mysql_query ($sql);
$row = mysql_fetch_assoc ($query);
$num_rows = mysql_num_rows ($query);
*/


?>