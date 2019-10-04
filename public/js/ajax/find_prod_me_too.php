<script type="text/javascript">
$( document ).ready(function() {
  /*

	/* if it's not local administrator - forward to homepage*/
	
	if (localStorage.getItem("global_admin") === null) {
		  window.location.replace("http://contact25.com");
	}


	
	
});

$('.add_me_too_prod').click(function() {
		
		var me_too_base   = ( $(this).attr('data-me-too') );
		var title 		  = ( $('#me_too_title_'+me_too_base).val() );
		var price 		  = ( $('#me_too_price_'+me_too_base).val() );
		var original_qty  = ( $(this).attr('data-qty-original') );
		var me_too_qty    = ( $(this).attr('data-qty-me-too') );
		var s_album    	  = ( $(this).attr('data-s-album') );
		
		
			
			
			$.ajax({
                    url: "js/ajax/find_prod_me_add.php",
                    data: {
						
						me_too_base:me_too_base,
						title:title,
						price:price,
						original_qty:original_qty,
						me_too_qty:me_too_qty,
						s_album:s_album
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						//alert(data);
						$('#amazon_results').html(data);
						
                    }

        });
			
	});

</script><?php 

include('../../config.php');  
include('include/includes.php'); 
	
$sql = 'SELECT 
			* 
		FROM 
			spares
		WHERE 
			spares.s_label like "%'.$_POST['var_search'].'%"
		AND
			spares.s_SKU IS NULL	
		AND
			spares.s_album = "'.$_POST['s_album'].'"
			
		';
		#die($sql);
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		
		$result .= '
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; cursor:pointer; background-color:green;color:white;text-align:center;">(#'.$row['s_num'].') '.$row['s_label'].' (&pound;'.$row['s_price'].') Stock: '.$row['s_qty'].'</div>';
				if ($row['s_qty']>1){
					$result .= '
				<input type="text" id="me_too_title_'.$row['s_id'].'" placeholder="Me too Title" style="width:100%; border: 1px solid grey; font-size: 62px; text-align:center; ">
				<input type="text" id="me_too_price_'.$row['s_id'].'" placeholder="Me too Price (£)" style="width:100%; border: 1px solid grey; font-size: 62px; text-align:center; ">
				<div class="add_me_too_prod" data-s-album="'.$row['s_album'].'" data-me-too="'.$row['s_id'].'" data-qty-original="'.($row['s_qty']-(floor($row['s_qty']/2))).'" data-qty-me-too="'.floor($row['s_qty']/2).'" style="padding:20px;  font-size:28px; cursor:pointer; background-color:red;color:white;text-align:center;">Me Too ('.floor($row['s_qty']/2).')</div>
				';
				}else{
					$result .= '
				<div style="padding:20px;  font-size:28px; text-align:center;">not enough stock to add a me too product</div>';
				}
					
				$result .= '
				
				
			</td>
		</tr>
		';
	}while($row	= mysql_fetch_assoc($query));
}else{
	$result .= ' 
		<tr>
			<td colspan="2">
				<div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">No Results</div>
			</td>
		</tr>
		';
}
?>


	
	
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
	
	
    
    
		<?php echo $result?>

	
	
    
                   
</table>