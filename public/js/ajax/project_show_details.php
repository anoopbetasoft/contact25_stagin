<script type="text/javascript">
$( document ).ready(function() {
 
  $('.remove_upload').click(function() {
	  var upload_remove = ($(this).data("remove_upload_id"));
	  var stage_id = ($(this).data("stage-id"));
	  
	  $.ajax({
                    url: "js/ajax/project_delete_upload.php",
                    data: {
						
						stage_id:stage_id,
						upload_remove:upload_remove
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						$("#hide-upload-"+upload_remove).hide();
						//alert(data);

						
                    }

        	});
		
	  
	});
 
 
 $('.s_days').change(function() {
	 
  		var s_days = ($(this).val());
		
		 
		$.ajax({
                    url: "js/ajax/project_update_days.php",
                    data: {
						
						stage_id:stage_id,
						s_days:s_days
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						//alert(data);

						
                    }

        	});
		
	
 });
 
 
 
 
  $('.s_startdate').change(function() {
	 
  		var s_startdate = ($(this).val());
		var stage_id = ($(this).data("stage-id"));
		 
		$.ajax({
                    url: "js/ajax/project_update_startdate.php",
                    data: {
						
						stage_id:stage_id,
						s_startdate:s_startdate
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						//alert(data);

						
                    }

        	});
		
	
 });
 
 $('.s_supplier').change(function() {
  		var new_supplier = ($(this).val());
		var stage_id = ($(this).data("stage-id"));
		
		$.ajax({
                    url: "js/ajax/project_update_supplier.php",
                    data: {
						
						stage_id:stage_id,
						new_supplier:new_supplier
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						
						
                    }

        	});
		
	
 });
 
 
 $( ".sage_notes" ).keyup(function() {
  		var new_note = ($(this).val());
		var stage_id = ($(this).data("stage-id"));
		$("#sage_notes").css("color", "#999");
		$.ajax({
                    url: "js/ajax/project_update_note.php",
                    data: {
						
						stage_id:stage_id,
						new_note:new_note
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						$("#sage_notes").css("color", "#222");
						
                    }

        	});
});
  
  
 $('#s_cat').change(function() {
  		var new_cat = ($(this).val());
		var stage_id = $('#stage_id').val();
		$.ajax({
                    url: "js/ajax/project_update_cat.php",
                    data: {
						
						stage_id:stage_id,
						new_cat:new_cat
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						
						
                    }

        	});
		
	
 });
 
 var stage_id = $('#stage_id').val();
  show_items(stage_id);
  
  
  $(function(){
 	$(".add_item_wrapper input").keypress(function (e) {
    if (e.keyCode == 13) {
		var stage_id = ($(this).data("message-id"));
		var add_item = $('.add_item_'+stage_id).val();
		
        $.ajax({
                    url: "js/ajax/project_add_element.php",
                    data: {
						
						stage_id:stage_id,
						add_item:add_item
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						//alert("aaded");
						$('.add_item_'+stage_id).val("");
						
						show_items(stage_id);
						
                    }

        	});
    }
 	});
	});




	
	function show_items(stage_id){
		
		$('#stage_items_'+stage_id).html('<img src="http://contact25.com/images/SpinningWheel.gif" alt="Spinnner" style="width:30px;">');
		
		$.ajax({
                    url: "js/ajax/project_show_elements.php",
                    data: {
						
						stage_id:stage_id
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						
						$('#stage_items_'+stage_id).html(data);
						
                    }

        	});
			
	}
	
	
	
	
	
});

</script><?php 
include ("../../config.php");
/* CHECK FOR EXISTING PRODUCT */



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