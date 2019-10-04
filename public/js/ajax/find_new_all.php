<?php 
include('../../config.php');  
include('include/includes.php'); 
/*
	PAGE FUNCTIONS
	1 - Search Amazon based url
	2 - Parse the page and grab the basic product details
	3 - Add to the database if they don't exist already

*/

	
	


	$html = file_get_contents($_POST['url']);
	
	/* save the number of pages in this section */
	$num_pages_here=explode('pagnDisabled',$html);
	$num_pages_here=explode('<',$num_pages_here[1]);
	$num_pages_here=explode('>',$num_pages_here[0]);
	$num_pages_here = $num_pages_here[1];
	$_SESSION['num_pages_to_loop'] = $num_pages_here;
	/*
	$expStr=explode('resultsCol',$html);
	$html = $expStr[1].$expStr[2];
	*/
	
	$expStr=explode('/dp/',$html);
	
	$how_many = count($expStr);
	
	$i = 0;
	$how_many_added = 0;
	#die(var_dump($expStr));
	do{
		
		$after_string = explode('/',$expStr[$i+1]);
		
		$before_string = explode('/',$expStr[$i]);
		$title_product = end($before_string);
		if (preg_match("/header/i", $title_product)) {
			$create_string = '';
		}elseif (preg_match("/amazon/i", $title_product)) {
			$create_string = '';
		}else{
			$create_string = 'http://www.amazon.co.uk/'.$title_product.'/dp/'.$after_string[0].'/';
		}
		
		
		
		
		$i ++;
		
		
		$sql 		= 'SELECT * FROM spares WHERE spares.s_ISBN10 = "'.$after_string[0].'"';
		
		$query		= mysql_query($sql);
		$row		= mysql_fetch_assoc($query);
		$num_rows	= mysql_num_rows($query);
		
		if($_POST['user']){
			$user_id = $_POST['user'];
		}else{
			$user_id = -1;
		}
		
		
		if ($num_rows>0){
			## product already exists - do nothing ##
		}else{
			if (strlen($after_string[0])>0){
				 #if (!preg_match_all("~\b(ebook|unabridged)\b~i//",$after_string[0])){
					 
					 if (
					 	((strlen($create_string)>0)
						&&
						(!preg_match(';', $create_string))
						))
						 {
						$sql 		= 'INSERT INTO spares (s_u_id, s_ISBN10,  s_amazon_URL, s_album) VALUES ( "'.$user_id.'", "'.$after_string[0].'",  "'.$create_string.'", -1)';
						
						 mysql_query($sql);
						 $how_many_added ++;
						 $sql_all .= $sql.'<br>';
					 }
					
					
					
				 #}
				
				
			}
		}
		
		
		
		
		
	}while($how_many>($i));
	
	/* UPDATE THE URL LOOP RECORD*/
	$sql = 'UPDATE content_urls SET cu_last_update = "'.date("Y-m-d H:i:s").'" WHERE cu_url = "'.$_POST['url'].'"';
	mysql_query($sql);
	
	
//////////////////////////////////////////
/*					2					*/
//////////////////////////////////////////

	
?>
    
   
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
    
    <tr>
		<td colspan="2">
		
		<div id="store_only" style="padding:20px;  font-size:28px; cursor:pointer; background-color:#A8A8A8; color:white; text-align:center;"><?php echo $how_many_added?> added (page will refresh in 10 sec)</div>
		<?php echo $sql_all?> 
		
		</td>

	
	</tr>
    </table>
   