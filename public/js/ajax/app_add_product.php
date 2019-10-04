<?php 

include("../../include/config.php");
global $db;

if ($_SESSION['c25_id']>0){
	
	// http://contact25.com/uploads/7_'.$_POST['s_id'].'.jpg

	// work out which price to save based on the type //
	if ($_POST['item_type'] == 1){
		
		// set lending price if it exists
		if ($_POST['selling_price']>0){
			$price = $_POST['selling_price'];
		}else{
			$price 			= 0;
		}
		
		if ($_POST['lending_price']>0){
			$price_lend = $_POST['lending_price'];
		}else{
			$price_lend 			= 0;
		}
		
		
		if ($_POST['lending_duraction_dwmy']>0){
			$lending_duraction_dwmy			= $_POST['lending_duraction_dwmy'];
		}else{
			$lending_duraction_dwmy			= 0;
		}
		
		
		// only record the lending options on an item //
		$s_lend_f 							= $_POST['checkbox_lend_f'];
		$s_lend_n 							= $_POST['checkbox_lend_n'];
		$s_lend_c 							= $_POST['checkbox_lend_c'];
	
		
	}elseif ($_POST['item_type'] == 2){
		// service
		$price = $_POST['selling_price_service'];
	}elseif ($_POST['item_type'] == 3){
		// subscription
		$price = $_POST['subscription_price'];
	}
	
	// record selling options on all items
	if (($_POST['checkbox_sell_f'] == 'undefined')||(strlen($_POST['checkbox_sell_f'])==0)){
		$s_sell_f 							= "0"; 
	}else{
		$s_sell_f 							= addslashes($_POST['checkbox_sell_f']);
	}
	
	if (strlen($_POST['checkbox_sell_n'])==0){
		$s_sell_n 							= "0";
	}else{
		$s_sell_n 							= $_POST['checkbox_sell_n'];
	}
	
	
	if (strlen($_POST['checkbox_sell_c'])==0){
		$s_sell_c 							= "0";
	}else{
		$s_sell_c 							= addslashes($_POST['checkbox_sell_c']);
	}
	
	if (($_POST['checkbox_lend_f'] == 'undefined')||(strlen($_POST['checkbox_lend_f'])==0)){
		$s_lend_f 							= "0"; 
	}else{
		$s_lend_f 							= addslashes($_POST['checkbox_lend_f']);
	}
	
	if (strlen($_POST['checkbox_lend_n'])==0){
		$s_lend_n 							= "0";
	}else{
		$s_lend_n 							= $_POST['checkbox_lend_n'];
	}
	
	
	if (strlen($_POST['checkbox_lend_c'])==0){
		$s_lend_c 							= "0";
	}else{
		$s_lend_c 							= addslashes($_POST['checkbox_lend_c']);
	}
	
	// validation //
	if ($_POST['s_id']>0){
		
	}else{
		$_POST['s_id'] = "NULL";
	}
	
	if ($_POST['s_qty']>0){
		
	}else{
		
	}
	
	if ($_POST['barcode']>0){
		
	}else{
		$_POST['barcode'] = '';
	}
	
$sql = '
		INSERT INTO 
			stock_c25
		(
			s_u_id,
			s_session_id,
			s_s_id,
			s_label,
			s_desc,
			s_qty,
			s_condition,
			s_price_buy_inc_vat,
			s_price_lend_inc_vat,
			s_lend_dwmy,
			s_barcode_type,
			s_ISBN13,
			s_price_updated,
			s_sell_f,
			s_sell_n,
			s_sell_c,
			s_lend_f,
			s_lend_n,
			s_lend_c,
			s_box,
			s_room
		)
			VALUES 
		(
			"'.$_SESSION['c25_id'].'",
			"'.session_id().'",
			'.$_POST['s_id'].',
			"'.html_entity_decode ($_POST['s_label']).'",
			"'.html_entity_decode ($_POST['s_description']).'",
			"'.$_POST['s_qty'].'",
			"'.$_POST['quality_val'].'",
			"'.$price.'",
			"'.$price_lend.'",
			"'.$lending_duraction_dwmy.'",
			"4",
			"'.$_POST['barcode'].'",
			"'.date("Y-m-d H:i:s").'",
			"'.$s_sell_f.'",
			"'.$s_sell_n.'",
			"'.$s_sell_c.'",
			"'.$s_lend_f.'",
			"'.$s_lend_n.'",
			"'.$s_lend_c.'",
			"'.$_POST['current_box'].'",
			"'.$_POST['current_room'].'"
		)
';
	 
	mysqli_query				($sql, $db);
	// save the pictures against the stock_c25 table id //
	
	$last_id 			= 		last_id_for_this_user();
	$picture_link 		= 		savepictures($last_id);
	$sql 				=	 	'UPDATE stock_c25 SET s_pic_link =  '.$picture_link.' WHERE s_id = '.$last_id;
	//mysqli_query				($sql, $db);
	
	
/*"s_label="+s_label+"&s_description="+s_description+"&s_qty="+s_qty+"&item_type="+item_type+"&quality_val="+quality_val+"&selling_price="+selling_price+"&lending_price="+lending_price+"&selling_price_service="+selling_price_service+"&subscription_price="+subscription_price+"&lending_duraction_to_send="+lending_duraction_to_send+"&subscription_duration="+subscription_duration+"&checkbox_sell_f="+checkbox_sell_f+"&checkbox_sell_ff="+checkbox_sell_ff+"&checkbox_sell_n="+checkbox_sell_n+"&checkbox_sell_c="+checkbox_sell_c+"&checkbox_lend_f="+checkbox_lend_f+"&checkbox_lend_ff="+checkbox_lend_ff+"&checkbox_lend_n="+checkbox_lend_n+"&checkbox_lend_c="+checkbox_lend_c+"&current_box="+current_box+"&current_room="+current_room*/
 
}else{
	// no session ID for user - must have timed out - fail & prompt for login.
	die("timeout");
}


die();
include_once ("../../include/class.app.php");
$app = new app();
echo $app->add_product();

function savepictures($last_id){
	global $db;
	
	$max_images = 	11;
	$i			=	1;
	
	do{
		$filename = 'https://contact25.com/uploads_customer/'.$_SESSION['c25_id'].'_'.$i.'.jpg';
		if (file_exists($filename)) {
			if(!@copy($filename,'https://contact25.com/uploads_customers/'.$last_id.'_'.$_SESSION['c25_id'].'_'.$i.'.jpg'))
			{
				$errors= error_get_last();
				echo "COPY ERROR: ".$errors['type'];
				echo "<br />\n".$errors['message'];
			} else {
				echo "File copied from remote!";
			}
		} 
		$i ++;
	}while($i<$max_images);	
		
	
	
	
}

function last_id_for_this_user(){
	
	global $db;
	
			$sql = 'SELECT
						stock_c25.s_id
					FROM
						stock_c25
					WHERE
						stock_c25.s_u_id = "'.$_SESSION['c25_id'].'"
					ORDER BY
						s_id
					DESC
					LIMIT 1';
			$query = mysqli_query($db,$sql);
			$row = mysqli_fetch_assoc($query);
			$num_rows = mysqli_num_rows($query);

			if ($num_rows>0){
				do{


					return $row['s_id'];


				}while($row = mysqli_fetch_assoc($query));
			}
}

?>