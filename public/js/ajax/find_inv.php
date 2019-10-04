




<script type="text/javascript">
$( document ).ready(function() {
  


	$('#set_weight').keypress(function (e) {
		
		if (e.which == 13) {
			
			var s_id = $('#set_weight').data( "s_id" );
			var weight = $( this ).val();
			update_weight(weight,s_id);
		}
	});
	
	
	$('#set_format').keypress(function (e) {
		
		if (e.which == 13) {
			
			var s_id = $('#set_format').data( "s_id" );
			var format = $( this ).val();
			update_format(format,s_id);
		}
	});
	
	
	
	
	
	
	
	function update_weight(weight,s_id){	
		
	
			//$('#amazon_results').html("Searching - please wait ...");
			
			$.ajax({
                    url: "js/ajax/update_weight.php",
                    data: {
						
						weight:weight,
						s_id:s_id
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						
					
						
                    }

        });
	}
	
	function update_format(format,s_id){	
		
	
			//$('#amazon_results').html("Searching - please wait ...");

			$.ajax({
                    url: "js/ajax/update_format.php",
                    data: {
						
						format:format,
						s_id:s_id
								
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
	}
	
	
	
	
		
	
	
});

</script><?php 

include('../../config.php');  
include('include/includes.php'); 
	
	if (preg_match("/-/", $_POST['var_search'])) {
    	$check_amazon_or_order_id = '
				orders.o_amazon_order_id like "%'.$_POST['var_search'].'%"';
	} else {
		$check_amazon_or_order_id = '
				orders.o_id = "'.$_POST['var_search'].'"';
	}
	
	
$sql = 'SELECT 
			orders.o_id  
		FROM 
			orders
		WHERE 

			(
				
				'.$check_amazon_or_order_id.'
			)
			
			
		';
		#die($sql);
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		
		$result .= any_unweighted_items($row['o_id']);
		
		$result .= '
		<tr>
			<td colspan="2">
				<a href="invoices/'.$row['o_id'].'.pdf" target="_blank"><div id="add_to_amazon_and_ebay" style="padding:20px;  font-size:28px; cursor:pointer; background-color:green;color:white;text-align:center;">'.$row['o_id'].'.pdf</div></a>
			</td>
		</tr>
		';
		
		$result .= '
		<tr>
			<td colspan="2">
				<a href="tcpdf/examples/invoice.php?special_req='.$row['o_id'].'" target="_blank"><div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">GENERATE INVOICE COPY</div></a>
			</td>
		</tr>
		';
		
		$result .= '
		<tr>
			<td colspan="2">
				<a href="tcpdf/examples/invoice.php?printit=1&special_req_vat=1&special_req='.$row['o_id'].'" target="_blank"><div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">GENERATE VAT RECEIPT</div></a>
			</td>
		</tr>
		';
		
		$result .= '
		<tr>
			<td colspan="2">
				<a href="dmo/create.php?special_req='.$row['o_id'].'" target="_blank"><div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">GENERATE DMO LABEL</div></a>
			</td>
		</tr>
		';
		
		$result .= '
		<tr>
			<td colspan="2">
				<a href="dmo/create.php?special_req='.$row['o_id'].'&format=LL" target="_blank"><div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">GENERATE DMO LABEL (LARGE LETTER)</div></a>
			</td>
		</tr>
		';
		
		$result .= '
		<tr>
			<td colspan="2">
				<a href="dmo/create.php?special_req='.$row['o_id'].'&format=P" target="_blank"><div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">GENERATE DMO LABEL (PARCEL)</div></a>
			</td>
		</tr>
		';
		
		$result .= '
		<tr>
			<td colspan="2">
				<a href="dmo/create_lock.php" target="_blank"><div style="padding:20px;  font-size:28px; background-color:white;color:black;text-align:center;">UNLOCK PRINT</div></a>
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

function any_unweighted_items($o_id){
	
	$sql = 'SELECT 
			s_id,
			s_weight,
			s_format
		FROM 
			spares, order_details 
		WHERE 
			order_details.od_s_id = spares.s_id
		AND
			order_details.od_o_id = '.$o_id.'
		AND
		(
			(s_weight = 0)
			||
			(s_format IS NULL)
		)	
			
		';
		
	$query		= mysql_query($sql);
	$row		= mysql_fetch_assoc($query);
	$num_rows	= mysql_num_rows($query);
	if ($num_rows >0){
		$output = '';
		do{
			if ($row['s_weight'] == 0){
			$output .= '<tr>
			<td><input type="text" id="set_weight" data-s_id="'.$row['s_id'].'" placeholder="Set Weight" style="width:100%; border: 1px solid grey; font-size: 62px; text-align:center; "></td>
		</tr>';
			}
			
			if (!strlen($row['s_format'] >0)){
			$output .= '<tr>
			<td><input type="text" id="set_format" data-s_id="'.$row['s_id'].'" placeholder="Format (P/LL/L)" style="width:100%; border: 1px solid grey; font-size: 62px; text-align:center; "></td>
		</tr>';
			}
			
		}while($row	= mysql_fetch_assoc($query));
	}
	
	return $output;
}

?>


	
	
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
	
	
    
    
		<?php echo $result?>

	
	
    
                   
</table>