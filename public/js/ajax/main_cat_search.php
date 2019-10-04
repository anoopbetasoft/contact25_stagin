<script type="text/javascript">// JavaScript Document

$(document).ready(function(){

$('.display_product').click(function(){
		
		var s_id = ($(this).data("id"));
	
		$.ajax({
                    url: "http://contact25.com/js/ajax/display_product.php",
                    data: {
							s_id:s_id
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("...we tried to add the side menu, but you have no internet connection");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {

						$('.content').html(data);
						
						// nothing - it's just saving a session var in the ebay_processing file //
                    }

        });
	});
	

		


	
});</script><?php 

include('../../config.php');  
include('include/includes.php'); 

/*

insterprest the following posted vars


*/



$sql = 'SELECT 
			* 
		FROM 
			spares,
			category_links
		WHERE 
			spares.s_id = category_links.cl_s_id
		AND
			category_links.cl_c_id = "'.$_POST['cat_id'].'"
		AND 
			s_price >0
		
		
			
		';
		#die($sql);
		
$query		= mysql_query($sql);
$row		= mysql_fetch_assoc($query);
$num_rows	= mysql_num_rows($query);
if ($num_rows >0){
	do{
		if (file_exists('/home/vhosts/contact25.com/httpdocs/uploads/small/7_'.$row['s_id'].'.jpg')) {
			$price = 	$row['s_price'];
			$result .= '<div style="float:left; margin:auto; padding: auto; cursor:pointer;" class="display_product" data-id="'.$row['s_id'].'">
						<img class="load_product" src="http://contact25.com/uploads/small/7_'.$row['s_id'].'.jpg" alt="'.$row['s_label'].'" style="height:200px; padding-right: 5px;">
						<div style="color:red; font-size:16px; font-weight:bold; float:right;padding-right: 20px; ">£'.$price.'</div>
						</div>
			';
		}
	}while($row	= mysql_fetch_assoc($query));
}else{
	
	$result .= '<div style="float:left; margin:auto; padding: auto; cursor:pointer;" class="display_product" data-id="'.$row['s_id'].'">
						no results
						</div>
					
			';
	
}


##<div><img src="images/contact25_logo_new.png" style="margin-left: auto; margin-right: auto; width:90%; max-width:400px;"  alt="Contact25 Logo"/></div>

?>
    <div style="padding:10px;">&nbsp;</div>
    

<?php echo $result?>



	
