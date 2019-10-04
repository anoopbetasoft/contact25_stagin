<script type="text/javascript">
$( document ).ready(function() {
  

	/* if it's not local administrator - forward to homepage*/
	
	if (localStorage.getItem("global_admin") === null) {
		  window.location.replace("http://contact25.com");
	}


	
	$('#store_only').click(function() {
			store_it();
	});
	
	function store_it(){	
	
			//$('#amazon_results').html("Connecting to Amazon/Ebay - please wait ...");
			var user = $('#user').val();
			var box = $('#box').val();
			var room = $('#room').val();
			
			var item_title = $('#item_title').val();
			var item_desc = $('#item_desc').val();
			var qty = $('#qty').val();
			var price = $('#price').val();
			var barcode = $('#barcode').val();
			
			var large_image = $('#large_image').val();
			var condition_type = $('#condition_type').val();
			
			$.ajax({
                    url: "js/ajax/store_it.php",
                    data: {
						user:user,
						box:box,
						room:room,
						item_title:item_title,
						item_desc:item_desc,
						qty:qty,
						price:price,
						barcode:barcode,
						large_image:large_image,
						condition_type:condition_type
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("You have no internet connection - please try again");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
						$('#amazon_results').html(data);
						//$('#amazon_results').html(data);
						
                    }

        });
	}
	
	
	
	
	

	
	
	
	
	
	
			
		
	
	
});

</script><?php 
include ("../../config.php");
/* CHECK FOR EXISTING PRODUCT */
$sql = 'SELECT * FROM spares WHERE spares.s_ISBN13 = "'.$_POST['barcode'].'" AND (spares.s_amazon_URL IS NULL)';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
if ($num_rows>0){
	do{
		echo "SKU-CONTACT-".$row['s_id'].'<br>';
		echo "BOX:".$row['s_box'].'<br>';
		echo "ROOM:".$row['s_room'].'<br>';
		echo "CONDITION: ";
		if ($row['s_condition'] == 11){
			echo "New";
		}
		if ($row['s_condition'] == 1){
			echo "New";
		}
		if ($row['s_condition'] == 2){
			echo "Used - Very Good";
		}
		if ($row['s_condition'] == 3){
			echo "Used - Good";
		}
		if ($row['s_condition'] == 4){
			echo "Used - Acceptable";
		}
		echo '<br>';
		$sql = 'UPDATE spares SET spares.s_u_id = -2 WHERE s_id = "'.$row['s_id'].'"';
		mysql_query($sql);
		echo "<span style='color:green;font-size:22px; font-weight:bold'>ITEM UPDATED TO PREDATOR PRICING MODE AMAZON</span>";
		die();
	}while($row = mysql_fetch_assoc($query));
}



/*
	PAGE FUNCTIONS
	1 - Search Amazon based on the barcode posted
	2 - Parse the page and grab the image
	3 - Parse the page and grab the current selling price
	4 - Establish the cheapest price and match it

*/




//////////////////////////////////////////
/*					1					*/
//////////////////////////////////////////

	$html = file_get_contents('http://www.amazon.co.uk/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords='.$_POST['barcode'].'');
#die('http://www.amazon.co.uk/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords='.$_POST['barcode'].'');
//////////////////////////////////////////
/*					2					*/
//////////////////////////////////////////

	$expStr=explode('a-link-normal a-text-normal',$html);
	$resultString=explode('>',$expStr[1]);
	$resultString=explode('href=',$resultString[0]);
	$resultString = str_replace('"', '', $resultString[1]);
	$resultString = str_replace(' "', '', $resultString);
	
	/*	the below works on DVDs / listings with multi-images */
	$html = file_get_contents($resultString);
	$expStr=explode('imgTagWrapperId',$html);
	$resultString=$expStr[1];
	$result=explode('<li',$resultString);
	$resultString=$result[0];
	$result=explode('<img',$resultString);
	$resultString='<img'.$result[1];
	$result=explode('http://',$resultString);
	
	/*
		LARGEST IMAGE FROM AMAZON
	*/
	$large_image = 'http://'.substr($result[2], 0, strpos($result[1], ".jpg")).'.jpg'; ## remove everything after .jpg
	
	if (strlen($result[2])<1){
		
		/* if there's no image for books, try a different format - look for gallery image - found on books during testing*/
		$expStr=explode('mainUrl":',$html);
		
		$resultString=explode(',',$expStr[1]);
		$large_image=str_replace('"','',$resultString[0]);
		
		
	}
	
	
	echo '<img src="'.$large_image.'" style="height:500px; display: block;
    margin-left: auto;
    margin-right: auto; ">'; ## image preview


	/*
		COPY THE FILE FROM AMAZON TO STICKER-SWAP.COM FOR LISTING ON EBAY - this works - just need to change the IDS to the one you've just added.
	*/
/*
	$newfile = '/home/vhosts/contact25.com/httpdocs/uploads/7_4604.jpg';
	
	if(!@copy($large_image,$newfile))
		{
   		 	$errors= error_get_last();
    		echo "COPY ERROR: ".$errors['type'];
   		 	echo "<br />\n".$errors['message'];
	} else {
   			echo "File copied from remote!";
	}	
*/	

//////////////////////////////////////////
/*					3					*/
//////////////////////////////////////////

	$expStr=explode('<span id="productTitle" class="a-size-large">',$html);
	$expStr=explode('<span>',$expStr[1]);
	$expStr=explode('<style>',$expStr[0]);
	
	$product_title = $expStr[0];
	$product_title = str_replace('</span>', '', $product_title);
	$expStr=explode('"',$product_title);
	$product_title = $expStr[0].' ('.$expStr[2].') '.$expStr[4];
	$product_title = $expStr[0];
	
	
	
	#die($product_title);
	#$expStr=explode('</h1>',$product_title);
	#$product_title = $product_title[0];
	#$product_title = str_replace('</div>', '', $product_title);
	#$product_title = str_replace('</h1>', '', $product_title);
	$product_title = strip_tags($product_title);
	$product_title = trim($product_title);
	
	if (preg_match('/Hardcover/', $expStr[2])){
		$product_title = $product_title.' [Hardback]';
	}
	if (preg_match('/Paperback/', $expStr[2])){

		$product_title = $product_title.' [Paperback]';
	}
	
	
	
//////////////////////////////////////////
/*					4					*/
//////////////////////////////////////////

	
	#echo $expStr[1];
	$expStr=explode('a-color-price',$html);
	$expStr=explode('£',$expStr[2]);
	$expStr=explode('.',$expStr[1]);
	
	$new_price = ($expStr[0]-0.01);
	#echo $expStr[0];

	/*
		PRICE SUGGESTION - PRICE MATCH AMAZON (THEN WORK DOWN FROM THERE IF IT DOESN'T SELL)
	*/


	$expStr=explode('outer_postBodyPS',$html);
	$expStr=explode('noscript',$expStr[0]);
	$how_many_in_array = count($expStr);
	$prod_desc = strip_tags($expStr[$how_many_in_array-2]);
	$prod_desc = str_replace(">", "", $prod_desc);
	$prod_desc = str_replace("<", "", $prod_desc);
	$prod_desc = trim($prod_desc);
	
	
	/* malformed - try again*/
	if (preg_match("/jsOffDisplayBlock/",$prod_desc)){
		$prod_desc = $title; ## reset it on general principle
		if (preg_match('productDescriptionWrapper',$html)){
			$expStr=explode('productDescriptionWrapper',$html);
			$expStr=explode('emptyClear',$expStr[1]);
			die("##".$expStr[0]);
			$expStr=explode('emptyClear',$expStr[0]);
		}
		
		
	}
	
	
	if (preg_match("/26id/",$prod_desc)){
		$prod_desc = $product_title; ## reset it on general principle
	}
	
	
	
	
	
	
	

	
	


/*
	PAGE FUNCTIONS
	1 - Search Ebay based on the barcode posted
	2 - Parse the sub page and grabs the category link required to enter your own product

*/




//////////////////////////////////////////
/*					1					*/
//////////////////////////////////////////
/*
	$html = file_get_contents('http://www.ebay.co.uk/sch/i.html?_nkw='.str_replace(" ", "+", $_POST['barcode']).'');
	$expStr=explode('lvpicinner full-width picW s225',$html);
	$resultString=explode('>',$expStr[1]);
	$resultString=explode('href=',$resultString[1]);
	$resultString=explode('"',$resultString[1]);
	$prod_page_html = $resultString[1];
	/*
//////////////////////////////////////////
/*					2					*/
//////////////////////////////////////////	
/*	
	$html = file_get_contents($prod_page_html);
	$expStr=explode('bc-w',$html);
	$resultString=explode('li',end($expStr)); ## end means get the last element in the exploded string ##
	$resultString=explode('href=',$resultString[0]);
	$resultString = explode('"',$resultString[1]);
	$category_string = preg_replace("/[^0-9]/","",$resultString[1]);
	$catID = $category_string;
	$title =  $resultString[4];
	$title =  preg_replace("/[^A-Za-z0-9_ -]/","",$title);
	$title = substr($title, 0, -1);
	$category_label =  $title;
*/
	
?>


	
	<div id='ebay_cat_id' style='display:none;'><?php echo $category_string?></div>
	<div id='ebay_cat_label' style='display:none;'><?php echo $category_label?></div>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
	<input type="hidden" id="large_image" value="<?php echo $large_image?>">
	<tr>
    	<td><strong>Item<strong></td> 
		<td><span style="color:green;font-weight:bold; font-size: 22px; text-align: left;"><input type="text" id="item_title" style="width:100%; border: 1px solid grey;" value="<?php echo $product_title?>"></span></td>
	</tr>
     
    <tr>
    	<td><strong>Description<strong></td> 
		<td><span style="color:green;font-weight:bold; font-size: 22px; text-align: left;"><textarea id="item_desc" style="width:100%; height: 300px; border: 1px solid grey;"><?php echo $prod_desc?></textarea></span></td>
	</tr>
	
	<tr>
		<td><span style="font-weight:bold; font-size: 22px; text-align: left;">Qty</span></td>
		<td><input type="text" id="qty" style="width:100px; border: 1px solid grey;" value="1"></td>
	</tr>
	
	<tr>
		<td><span style="font-weight:bold; font-size: 22px; text-align: left;">Price</span></td>
		<td><input type="text" id="price" style="width:100px; border: 1px solid grey;" value="<?php echo $new_price?>"> 
		<?php 
		// price warning //
		if ($new_price<0.6){
			?>
			<span style="color:orange; text-align:left;">CAUTION! - You are about to list your product for less than the price of a large letter<br>
it had better fit into a standard letter otherwise you'll lose money at this price.
</span>

			<?
		}elseif ($new_price<2.5){
			?>
			<span style="color:red; text-align:left;">WARNING! - You are about to list your product for less than the price of a small parcel<br>
it had better fit into a large letter otherwise you'll lose money at this price.</span>
			<?
		}
		
		?>
        
        <?php 
		
		
		?>
        
        </td>
	</tr>
	
	
	
	<tr>
		<td><span style="font-weight:bold; font-size: 22px; text-align: left;">Quality</span></td>
		<td>
		
		<select id="condition_type"  style="color:green;font-weight:bold; font-size: 22px; float:left;">
			<option value="">- Select -</option>
			
			<option value="11"> New</option>
			<option value="1" selected> Used - Like New</option>
			<option value="2"> Used - Very Good</option>
            <option value="3"> Used - Good</option>
			<option value="4"> Used - Acceptable</option>
			
			<option value="5"> Collectible - Like New</option>
			<option value="6"> Collectible - Very Good</option>
            <option value="7"> Collectible - Good</option>
			<option value="8"> Collectible - Acceptable</option>
            
            <option value="9"> Not used</option>
            <option value="10">Refurbished (for computers, kitchen & housewares, electronics, and camera  & photo only)</option>
		</select>
		
		
		</td>

	
	</tr>
    
   
    
    
    
    <tr>
		<td colspan="2">
		
		<div id="store_only" style="padding:20px;  font-size:28px; cursor:pointer; background-color:#A8A8A8; color:white; text-align:center;">Store</div>
		
		
		</td>

	
	</tr>
    
    
    <tr>
		<td colspan="2">
		
		<div id="add_to_amazon" style="padding:20px;  font-size:28px; cursor:pointer; background-color:purple;color:white;text-align:center;">Sell Amazon</div>
		
		
		</td>

	
	</tr>
    
    <tr>
		
		<td colspan="2">
		
		<div id="add_to_ebay" style="padding:20px;  font-size:28px; cursor:pointer; background-color:red;color:white;text-align:center;">Sell Ebay (<?php echo $category_label?>)</div>
		
		
		</td>

	
	</tr>
    
    <tr>
		<td colspan="2">
		
		<div id="add_to_amazon_and_ebay" style="padding:20px;  font-size:28px; cursor:pointer; background-color:green;color:white;text-align:center;">Sell Amazon & Ebay</div>
		
		
		</td>

	
	</tr>
    
                   
