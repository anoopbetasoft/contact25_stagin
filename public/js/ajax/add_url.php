<?php 
include('../../config.php');  
include('include/includes.php'); 
/*
	PAGE FUNCTIONS
	1 - Add URL for search

*/

		$sql 		= 'SELECT * FROM content_urls WHERE cu_url = "'.$_POST['url'].'"';
		$query		= mysql_query($sql);
		$row		= mysql_fetch_assoc($query);
		$num_rows	= mysql_num_rows($query);
		if ($num_rows>0){
			## product already exists - do nothing ##
			$output = 'URL Already Exists';
		}else{
			$sql = 'INSERT INTO 
				content_urls 
					(
						cu_url, 
						cu_vat, 
						cu_last_update
					)
			VALUES
					(
						"'.$_POST['url'].'", 
						"'.$_POST['vat'].'", 
						"'.date('2010-m-d H:i:s').'"
					)	
					
				';
			mysql_query($sql);
			$output = 'URL Added';
		}


	

/*

	$html = file_get_contents($_POST['url']);
	$expStr=explode('/dp/',$html);
	$how_many = count($expStr);
	
	$i = 0;
	$how_many_added = 0;
	do{
		$after_string = explode('/',$expStr[$i+1]);
		$before_string = explode('/',$expStr[$i]);
		$create_string = 'http://www.amazon.co.uk/'.end($before_string).'/dp/'.$after_string[0].'/'.$after_string[1];
		$i ++;
		
		
		$sql 		= 'SELECT * FROM spares WHERE spares.s_ISBN10 = "'.$after_string[0].'"';
		$query		= mysql_query($sql);
		$row		= mysql_fetch_assoc($query);
		$num_rows	= mysql_num_rows($query);
		if ($num_rows>0){
			## product already exists - do nothing ##
		}else{
			if (strlen($after_string[0])>0){
				 if (!preg_match_all("~\b(ebook|unabridged)\b~i//",$after_string[0])){
					 $sql 		= 'INSERT INTO spares (s_u_id, s_ISBN10, s_amazon_URL, s_album) VALUES ( "'.$_POST['user'].'", "'.$after_string[0].'", "'.$create_string.'", -1)';
					mysql_query($sql);
					#echo $sql.'<br>';
					$how_many_added ++;
				 }
				
				
			}
		}
		
		
		
		
		
	}while($how_many>($i));
	
	*/
//////////////////////////////////////////
/*					2					*/
//////////////////////////////////////////

	
?>
    
   
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:green; font-size: 22px; text-align: left;">
    
    <tr>
		<td colspan="2">
		
		<div id="store_only" style="padding:20px;  font-size:28px; cursor:pointer; background-color:#A8A8A8; color:white; text-align:center;"><?php echo $output;?></div>
		
		
		</td>

	
	</tr>
    </table>
   