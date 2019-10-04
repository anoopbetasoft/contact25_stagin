<?php 
include ("../../config.php");
/* CHECK FOR EXISTING PRODUCT */

if ($_POST['s_id'] == 735603){
	/*subscription*/	
	
	?>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">

<button type="submit"><i class="fa fa-shopping-cart"></i>SUBSCRIBE (VIA PAYPAL)</button>
</form>
<br><br>
<br>
<br>

<br>

	<?php
	die();
}



if ((($_POST['s_album']<1) || ($_POST['s_album']==7))&&($_POST['s_album']!=23)){
			
		
					#include_once('/home/vhosts/contact25.com/httpdocs/classes/class.price.php');
					#$price 			= new price();
					#$price_updated 	=  $price->selling_price($_POST['s_id']);
	
					// if the price hasn't been found through 3rd party sellers, check Amazon //
					if ($price_updated == 0){
						
						
					
	
					include_once('/home/vhosts/contact25.com/httpdocs/classes/class.amazon.php');
					$amazon 		= new amazon();
					$amazon->amazon_aws_definitions_uk();
					//
					


					$s_id					= $_POST['s_id']; // set the ID
					
					
					$sql = 'SELECT 
								s_ISBN10,
								s_cat
							FROM 
								spares_'.$_SESSION['spares'].'
							WHERE 
								spares_'.$_SESSION['spares'].'.s_id = "'.$s_id.'"
								
							';
							
					
					#die($sql);		
					$query		= mysql_query($sql);
					$row		= mysql_fetch_assoc($query);
					$num_rows	= mysql_num_rows($query);
					if ($num_rows>0){
						do{
							$asin					= $row['s_ISBN10']; // set the ASIN
							$cat					= $row['s_cat']; // set the CAT
						}while($row	= mysql_fetch_assoc($query));
					}
					#die("test".$sql);

							
					
					$serviceUrl 			= "mws-eu.amazonservices.com";
					
					if ($s_id > 0){

					
					
					#$current_height			= $amazon->current_height_is_set($s_id);
					#
					if ($current_height	 == 0){
						/* set the height/weight/legth etc*/
						
						/*REMOVED FOR SPEED OF PAGE LOADING*/
						
						//$amazon->amazon_aws_update_sizes($asin, $serviceUrl, $s_id);
						
					}
					
					/* check the buy price from Amazon Prime & update*/
					$amazon->amazon_aws_update_buy_price($asin, $serviceUrl, $s_id, $cat);
					#die($s_id);
					
					}
		
		}

	
	
	}

$sql = 'REPALCE INTO spares_'.$_SESSION['spares'].' SELECT * FROM spares WHERE spares.s_id = "'.$_POST['s_id'].'"';
mysql_query($sql);	
	
if (($_POST['s_album']<22) || ($_POST['s_album']==23) || ($_POST['s_album']==23)){
	$check_for_qty = ' AND s_qty > 0';
}else{
	$check_for_qty = '';
}
 $sql = 'SELECT 
			s_label,
			s_desc,
			s_price,
			s_price_RRP,
			s_price_like_new,
			s_price_good,
			s_price_ok,
			s_buy_price,
			s_id,
			s_album,
			s_ISBN10,
			s_cat,
			s_num
		FROM 
			spares_'.$_SESSION['spares'].'
		WHERE 
			spares_'.$_SESSION['spares'].'.s_id = "'.$_POST['s_id'].'"
		
			
		';
#die($sql);		
	
		
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);

$dropdown = '';
$all_prices = array();
$selected = 1; ## to be assigned to the newest offer
if ($num_rows >0){
	do{
		
		$title = $row['s_label'];
		
		if (strlen($title)>0){
			/*new format*/
			include_once('/home/vhosts/contact25.com/httpdocs/libs/classes/class.display.php');
			$display = new display();
			$title .= ' > '.$display->show_album_title($row['s_album']);
				
		}else{
			/*old format*/
			if (strlen($row['s_num'])>0){
				if (($row['s_album']>0) && ($row['s_album']!=7)){
					include_once('/home/vhosts/contact25.com/httpdocs/libs/classes/class.display.php');
					$display = new display();
					$title .= $display->show_album_title($row['s_album']);
					$title .= ' Num ';
				}
				
					
				
				$title .= $row['s_num'];
			}
		}
		
		
		if (strlen($row['s_desc'])>0){
			$desc = $row['s_desc'];	
		}else{
			$desc = $row['s_label'];	
		}
		if ($row['s_price'] > 0){
			$s_album = $row['s_album'];
			$price = $row['s_price'];
			$price_new = $row['s_price'];
			if ($selected == 1){
				$selected = 'selected';
			}
			$dropdown .= '<option '.$selected.' value="1">NEW (£'.$price_new.')</option>';
			$selected = '';
			array_push($all_prices, $price_new);
		}else{
			$price = 0;
		}
		
		if (($row['s_price_RRP'] > 0)&&($row['s_price_RRP']>$row['s_price'])){
			$price_RRP = $row['s_price_RRP'];
		}else{
			$price_RRP = 0;
		}
		#die("#".$row['s_price_like_new']);
		if ($row['s_price_like_new'] > 0){
			$price_like_new = $row['s_price_like_new'];
			if ($selected == 1){
				$selected = 'selected';
			}
			$dropdown .= '<option '.$selected.' value="2">LIKE NEW (£'.$price_like_new.')</option>';
			$selected = '';
			array_push($all_prices, $price_like_new);
		}else{
			$price_like_new = 0;
		}
		
		if ($row['s_price_good'] > 0){
			$price_GOOD = $row['s_price_good'];
			if ($selected == 1){
				$selected = 'selected';
			}
			$dropdown .= '<option '.$selected.' value="3">GOOD (£'.$price_GOOD.')</option>';
			$selected = '';
			array_push($all_prices, $price_GOOD);
		}else{
			$price_GOOD = 0;
		}
		
		if ($row['s_price_ok'] > 0){
			$price_OK = $row['s_price_ok'];
			if ($selected == 1){
				$selected = 'selected';
			}
			$dropdown .= '<option '.$selected.' value="3">OK (£'.$price_OK.')</option>';
			$selected = '';
			array_push($all_prices, $price_OK);
		}else{
			$price_OK = 0;
		}
		
		if (($row['s_album'] < 1)||($row['s_album'] == 7)){
			$s_album = 7;	
		}else{
			$s_album = $row['s_album'];
		}
		
		if ($price == 0){
			$price = $row['s_price'];
		}
		
		
					
		

	}while($row	= mysql_fetch_assoc($query));
	
};
$sql = 'SELECT 
			* 
		FROM 
			category, category_links
		WHERE 
			category_links.cl_s_id = "'.$_POST['s_id'].'"
		AND
			category_links.cl_c_id = category.c_id
			
		';
		
		
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	$i = 1;
		$category_description = $title.' appears in: ';
	do{
		$category_description .= $row['c_name_long'];
		if ($i != $num_rows){
			$category_description .= ', ';
		}
		
		$i ++;
	}while($row	= mysql_fetch_assoc($query));
	
};

#
#
define ("DROPDOWN_PRICES", $dropdown);


$price_lowest = min($all_prices);
define ("FROM_PRICE", $price_lowest);

if (1>2){
	
	/*
		recently viewed got to 5million records - too big to query regularly - removed this
	*/
	
	/* record that the page has been viewed*/
	/*
	if (strlen($title)>0){
	$sql = 'DELETE FROM recently_viewed WHERE rv_item_id = "'.$_POST['s_id'].'" AND rv_session = "'.session_id().'"';
	mysql_query($sql);
	#die($sql);
	$sql = 'INSERT INTO recently_viewed 
				(
					rv_item_name,
					rv_item_id,
					rv_url,
					rv_price_from,
					rv_session,
					rv_date
				)
				VALUES
				(
					"'.$title.'",
					"'.$_POST['s_id'].'",
					"'.$_POST['page_url'].'",
					"'.FROM_PRICE.'",
					"'.session_id().'",
					"'.date("Y-m-d H:i:s").'"
				)


					';

	mysql_query($sql);
	
	}	
}*/

?>
<div class="price-box">
								<p>
                                 <?php
								if (FROM_PRICE>0){
									?>
									<span class="special-price">
										<span style="font-size:10px;">FROM</span> £<?php echo FROM_PRICE?> <span style="font-size:14px;">(INC UK DELIVERY)</span>
									</span>
									<?php }?>
                           		
									
                                    <?php if ($price_RRP>0){?>
									<span class="old-price">
										£<?php echo $price_RRP?> 
									</span>&nbsp;<span style="font-size:10px;">(RRP)</span>
                                    <?php }?>
								</p>
							</div>
                            <?php 
								if (FROM_PRICE>0){
									?>
									
									
							<form method="post">
                            
								<label>QUALITY</label>
								<select  id="quality" name="quality">
									<option>Choose an option</option>
                                    <?php echo DROPDOWN_PRICES?>
									
								</select>  <input type="hidden" id="s_album" name="s_album" value="<?php echo $s_album?>">
                                <?php echo  if (1>2){?>
								<a href="#" class="clear-caption">Clear selection</a>
                                
  
    
                                
                                
								<div class="price-box">
									<p class="price">
										<span class="old-price">
											£105.00
										</span>
										<span class="special-price">
											£<?php echo $price?>
										</span>
									</p>
								</div>
                                
                                <?php }?>
                                <div style="clear:both; padding:15px;"></div>
								<div class="qnt-addcart">
									<input type="number" value="1" id="qty" name="qty">
                                    <input type="hidden" value="<?php echo $_POST['s_id']?>" id="id" name="id">
                                    <input type="hidden" value="1" id="add_basket" name="add_basket">
									<button type="submit"><i class="fa fa-shopping-cart"></i>Add to <?php echo BASKET_LABEL?></button>
								</div>
							</form>
                            <?php }?>
<?php	
		
		
die();




$sql = 'SELECT * FROM stages WHERE s_id = "'.$_POST['stage_id'].'"';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	$stages_dropdown = '';
	do{
		
		/* stage dropdown list*/
		$stages_dropdown .= '<option value="1"';
		if ($row['s_cat'] == 1){
			$stages_dropdown .= ' selected';	
		}
		$stages_dropdown .= '>Building</option><option value="2"';
		if ($row['s_cat'] == 2){
			$stages_dropdown .= ' selected';	
		}
		$stages_dropdown .= '>Joinery</option><option value="3"';
		if ($row['s_cat'] == 3){
			$stages_dropdown .= ' selected';	
		}
		$stages_dropdown .= '>Roofing</option><option value="4"';
		if ($row['s_cat'] == 4){
			$stages_dropdown .= ' selected';	
		}
		$stages_dropdown .= '>Plumbing</option><option value="5"';
		if ($row['s_cat'] == 5){
			$stages_dropdown .= ' selected';	
		}
		$stages_dropdown .= '>Plastering</option><option value="6"';
		if ($row['s_cat'] == 6){
			$stages_dropdown .= ' selected';	
		}
		$stages_dropdown .= '>Plumbing</option><option value="7"';
		if ($row['s_cat'] == 7){
			$stages_dropdown .= ' selected';	
		}
		$stages_dropdown .= '>Electrics</option>';

		$s_notes = $row['s_notes'];
		$s_startdate = $row['s_startdate'];
		$s_days = $row['s_days'];
		
		$supplier_dropdown = supplier_dropdown($row['s_supplier']);
		
	}while($row = mysql_fetch_assoc($query));
}


function supplier_dropdown($supplier_id){
	$sql = 'SELECT * FROM stages_staff WHERE ss_active = "1"';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	if ($num_rows>0){
		$supplier_dropdown = '';
		do{
			
			/* stage dropdown list*/
			$supplier_dropdown .= '<option value="'.$row['ss_id'].'"';
			if ($row['ss_id'] == $supplier_id){
				$supplier_dropdown .= ' selected';	
			}
			$supplier_dropdown .= '>'.$row['ss_name'].' ('.$row['ss_trade'].')</option>';
			
			
		}while($row = mysql_fetch_assoc($query));
		
		return $supplier_dropdown;
	}

}


$timeframe = '<option value="0">Days >></option>';
$startdate = '<option value="0">Start Date >></option>';
		$i = 1;
		do{
			$date_est = date("D j M", strtotime("+".$i." day")); 
			$date_est_full = date("Y-m-d", strtotime("+".$i." day")); 
			if ($s_startdate == $date_est_full){
				$selected = " selected ";
			}else{
				$selected = "";		
			}
			
			if ($i == $s_days){
				$selected_days = " selected ";
			}else{
				$selected_days = "";		
			}
			$timeframe .= '<option value="'.$i.'" '.$selected_days.'>'.$i.'</option>';
			$startdate .= '<option value="'.$date_est_full.'" '.$selected.'>('.$date_est.')</option>';
			$i ++;
		}while($i < 122);


if (($s_days > 0)&&($s_startdate > 0)){
	$est_finish_date = '<span style="color:green; font-weight:normal; font-size:16px">(Finish: <strong>'.date("j F Y", strtotime($s_startdate."+".$s_days." weekdays")).'</strong>)<span>'; 
}


#echo $_POST['stage_id'];
?>
<input type="hidden" id="stage_id" name="stage_id" value="<?php echo $_POST['stage_id']?>">
<div class="col-lg-6 col-md-6">
						<div class="checkbox-form">		
                        	<h3>Items List</h3>
							<div class="row">


<span id="stage_items_<?php echo $_POST['stage_id']?>"></span>

								
								
								
								
								<div class="col-md-12">
									<div class="checkout-form-list">
                                    	<span class="add_item_wrapper" data-message-id="<?php echo $_POST['stage_id']?>">
										<input type="text" name="name" class="add_item_<?php echo $_POST['stage_id']?>" data-message-id="<?php echo $_POST['stage_id']?>" placeholder="Add Item" value="">
                                        </span>
									</div>
								</div>
							
								
								
								<div class="col-md-12">
									<div class="country-select">
										<label>Job Category <span class="required">*</span></label>
										<select name="s_cat" id="s_cat">
                                        <?php echo $stages_dropdown?>
										</select> 										
									</div>
								</div>
								
								

															
							</div>
									
								<div class="order-notes">
									<div class="checkout-form-list">
										<label>Job Notes (General)</label>
										<textarea class="sage_notes" data-stage-id="<?php echo $_POST['stage_id']?>" cols="30" rows="10" placeholder="Notes about your job, e.g. special notes not included in jobs list."><?php echo $s_notes?></textarea>
									</div>									
								</div>
							</div>													
						</div>
					</div>
											
											
											
											
											
											
											
											
											
										
				
				
				<div class="col-md-6">
									<div class="country-select">
										<label>Supplier <span class="required">*</span></label>
										<select name="s_supplier" class="s_supplier" data-stage-id="<?php echo $_POST['stage_id']?>">
                                        <option value="0">Select</option>
										<?php echo $supplier_dropdown?>
										</select> 										
									</div>
								</div>
				
				
								<div class="col-md-6">
									<div class="country-select">
										<label>Agreed Start Date</label>
										<select name="s_startdate" class="s_startdate" data-stage-id="<?php echo $_POST['stage_id']?>">
										 <?php echo $startdate?> 
										
										</select>									
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="country-select">
										<label>Days Work (Mon-Fri) <?php echo $est_finish_date?></label>
										<select name="s_days" class="s_days" data-stage-id="<?php echo $_POST['stage_id']?>">
										<?php echo $timeframe?>
										
										</select>									
									</div>
								</div>
										
								
                                 
                                 <?php 
												$sql 		= 'SELECT * FROM stage_uploads WHERE s_id = "'.$_POST['stage_id'].'" AND s_deleted = 0';
								
												$query		= mysql_query($sql);
												$row		= mysql_fetch_assoc($query);
												$num_rows	= mysql_num_rows($query);
												if ($num_rows >0){
													
													?><div class="col-md-6">
														<div style="padding: 20px 10px 20px 0; font-size:18px;"><?
													do{
														echo '<span id="hide-upload-'.$row['s_u_id'].'">';
														
														if (preg_match("/.pdf/",$row['s_file'])){
															echo '<span class="remove_upload"  data-remove_upload_id="'.$row['s_u_id'].'" data-stage-id="'.$_POST['stage_id'].'">x</span> 
															
															<img src="http://contact25.com/img/pdf.png" alt="Special" class="primary-image" style="width:30px;">';	
														}else{
															echo '<span class="remove_upload"  data-remove_upload_id="'.$row['s_u_id'].'" data-stage-id="'.$_POST['stage_id'].'">x</span>  <img src="http://contact25.com/img/img.png" alt="Special" class="primary-image" style="width:30px;">';
														}
														echo '<a href="/project_uploads/'.$row['s_file'].'" target="_blank" style="padding-top:50px;"> '.$row['s_u_name'].'</a><br>';
														echo '</span>';
													}while($row		= mysql_fetch_assoc($query));
													
													?>
														</div>
													</div>		
													<?
				
													
												}
												
												?>		
                                 
                                 
                                
										

																
									
                                
                                		
								<div class="col-md-6">
									<div>
										<label>Upload File</label>
										<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="text" name="upload_title" id="upload_title" placeholder="Upload Title" value="">
    <input type="hidden" name="stage_id" id="stage_id" value="<?php echo $_POST['stage_id']?>">
    <input type="submit" value="Upload" name="submit">
</form>						



		
									</div>
								</div>
                                
                                
                               
				
				
				

									
				<div class="order-button-payment">
									<input type="submit" id="place_order" value="Mark as Complete">
                                   
                                    
								</div>					
									
				
				
				<a class="mark_as_purchased" id="mark_as_purchased_'.$row['od_id'].'" data-puchased-id="'.$row['od_id'].'" style=" display:none;  cursor:pointer; color:RED;">SIGN OFF JOB</a>
									

</div>