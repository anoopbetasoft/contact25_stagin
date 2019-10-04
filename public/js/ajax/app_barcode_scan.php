<?php 

include('../../config.php');  
include('include/includes.php'); 

/*

insterprest the following posted vars


*/



$sql = 'SELECT 
			* 
		FROM 
			spares
		WHERE 
			spares.s_ISBN13 = "'.$_POST['barcode'].'"
		
			
		';
		#die($sql);
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		
		if ($row['s_buy_price']>0){
			$price = 	$row['s_buy_price'];
		}else{
			$price = 	$row['s_price'];
		}
		
		$result .= '<img src="http://contact25.com/uploads/7_'.$row['s_id'].'.jpg" alt="'.$row['s_label'].'" style="width:100%;">
					<div style="color:#fcb040; font-size:24px; font-weight:bold; height: 40px;">
			 		'.$row['s_label'].'</div>
					<div style="clear:both;"</div>
					<div style="color:#ec008c; font-size:34px; height: 40px; margin-top: 10px; ">
			 		£'.$price.'</div>
					<div style="clear:both;"</div>
					
					

					
					<div id="buy_now"><button style="margin-top:10px; width:100%; min-height: 80px; background-color:#ed1c24; font-size:24px;" class="button button-red" >Buy Now (£'.$price.')</button></div>
					
					
					<div id="buy_now"><button style="margin-top:10px; width:100%; min-height: 80px; background-color:#056839; font-size:24px;" class="button button-green">Sell Now (£'.$price.')</button></div>';
	}while($row	= mysql_fetch_assoc($query));
}else{
	
	/* scan 3rd party sites for it*/
	include('../../classes/class.scanning.php'); 
	$scan = new scanning();
	$result .= $scan->barcode_search($_POST['barcode']);
	
}



?><div><img onClick="runecho();" src="images/contact25_logo_new.png" style="margin-left: auto; margin-right: auto; width:90%;"  alt="Contact25 Logo"/></div>

    
    

<?php echo $result?>



	
