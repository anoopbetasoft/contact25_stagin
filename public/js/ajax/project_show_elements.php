<script type="text/javascript">

	$('.fa-arrow-up').click(function() {
		
		var element_id = ($(this).data("message-id"));
		var current_order = ($(this).data("current_order"));
		var stage_id = $('#stage_id').val();
		
		$.ajax({
                    url: "js/ajax/project_move_element_up.php",
                    data: {
						
						element_id:element_id,
						current_order:current_order,
						stage_id:stage_id
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						//$('.col-md-9').html(data);
						//location.reload();
						show_items();
						
                    }

        	});
	});	
	
	$('.fa-arrow-down').click(function() {
		
		var element_id = ($(this).data("message-id"));
		var current_order = ($(this).data("current_order"));
		var stage_id = $('#stage_id').val();
		
		$.ajax({
                    url: "js/ajax/project_move_element_down.php",
                    data: {
						
						element_id:element_id,
						current_order:current_order,
						stage_id:stage_id
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						//$('.col-md-9').html(data);
						//location.reload();
						show_items();
						
                    }

        	});
	});	
		
			
	$('.remove_element').click(function() {
		
		var element_id = ($(this).data("message-id"));
		
		$.ajax({
                    url: "js/ajax/project_remove_element.php",
                    data: {
						
						element_id:element_id
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
						//$('.col-md-9').html(data);
						//location.reload();
						show_items();
						
                    }

        	});
		
		
	});
	
	
	function show_items(){
		var stage_id = $('#stage_id').val();
		$('#stage_items_'+stage_id).html('<img src="http://contact25.com/images/SpinningWheel.gif" alt="Spinnner" style="width:30px;">');
		var stage_id = $('#stage_id').val();
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
		
	
		

</script>

<?php 

include ("../../config.php");

/* check if existing customer */

$sql = 'SELECT 	
			*
		FROM
			stage_elements
		WHERE
			s_id = "'.$_POST['stage_id'].'"
		ORDER BY
			s_order ASC	
		';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);

?><?
$i = 1;
if ($num_rows>0){
	do{
		if ($i != $row['s_order']){
			$sql = 'UPDATE stage_elements SET s_order = "'.$i.'" WHERE s_e_id = "'.$row['s_e_id'].'"';
			mysql_query($sql);	
		}
		
		
		
		?>
		<div class="col-md-9" >
									<div class="checkout-form-list">
                                    <?php echo $row['s_e_name']?>
										
									</div>
								</div>
								<div class="col-md-3"  style="height:40px;">
									<div class="checkout-form-list">
                                    <a><i class="fa fa-trash-o pro-del-basket remove_element" data-cartid="62" data-message-id="<?php echo $row['s_e_id']?>"></i></a>
										<?php if ($i>1){?><a><i class="fa fa-arrow-up" data-message-id="<?php echo $row['s_e_id']?>" data-current_order="<?php echo $i?>"></i></a><?php }?>
				<?php if ($i<$num_rows){?><a><i class="fa fa-arrow-down"  data-message-id="<?php echo $row['s_e_id']?>" data-current_order="<?php echo $i?>"></i></a><?php }?>
									</div>
								</div>
                  
            
         
                                
		<?
		$i ++;
			
	}while($row = mysql_fetch_assoc($query));
	
	?><div class="col-md-9" style="height:20px;"></div>
<?
}else{
	/*	no elements */



}


