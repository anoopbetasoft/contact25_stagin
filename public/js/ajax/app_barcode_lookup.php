<?php 
include("include/config.php");

global $db;

if(strpos( $_SERVER['HTTP_HOST'], 'https://contact25.com') !== false){
    $root_loaction = '';
}else{
	$root_loaction = '/home/vhosts/contact25.com/httpdocs/';
}

session_start();
/* login*/

	#$barcode = 9780263255683; ## test barcode when there is nothing to scan - best selling diet book ##
	$barcode = $_POST['barcode'];
	//$barcode = '5010305051008';
	#$barcode = '5051429101842';


	$result = barcode_lookup_add($barcode, $root_loaction);
	

	echo json_encode(array($result[0], $result[1]));


function barcode_lookup_add($barcode, $root_loaction){
	
	include_once("../../include/config.php");

	$sql = 'SELECT * FROM spares WHERE spares.s_ISBN13 = "'.$barcode.'" AND s_ISBN10 != "-1" LIMIT 1';
	$query = mysqli_query($db,$sql);
	
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	//return array("#22".$num_rows, array(1,2,3,4,4,5,6,7,8));
	$basket_list = '';
	$output = '';
	$i=1;
	//return array("#22".$db, array(1,2,3,4,4,5,6,7,8));
do{
	$options .= '<option>'.$i.'</option>';
	$i++;
}while($i<51);
	


	if ($num_rows>0){
		do{
			
			if (false === file_get_contents('https://contact25.com/uploads/7_'.$row['s_id'].'.jpg',0,null,0,1)) {
				
				$pic_link = 'https://contact25.com/assets/images/logo-balls.png';
				$pic_link = '<img src="'.$pic_link.'" alt="'.$row['s_label'].'" style="max-width:100%; display: block; margin: 0 auto; " align="middle">';
				include_once($root_loaction.'classes/class.amazon.php');
				$amazon 				= new amazon();
				$amazon->amazon_aws_definitions_uk();
				$pic_link = $amazon->amazon_aws_barcode_search($row['s_ISBN13'], $serviceUrl);
			}else{
				//return array("#image", array(1,2,3,4,4,5,6,7,8));
				$pic_link = 'http://contact25.com/uploads/7_'.$row['s_id'].'.jpg';
				$pic_link = '<img src="'.$pic_link.'" alt="'.$row['s_label'].'" style="max-width:100%; display: block; margin: 0 auto; " align="middle">';
			}
			
			
			/*
			if (file_exists('http://contact25.com/uploads/7_'.$row['s_id'].'.jpg')) {
				$pic_link = 'http://contact25.com/uploads/7_'.$row['s_id'].'.jpg';
				$pic_link = '<img src="'.$pic_link.'" alt="'.$row['s_label'].'" style="max-width:100%; display: block; margin: 0 auto; " align="middle">';
			}else{
				include_once($root_loaction.'classes/class.amazon.php');
				$amazon 				= new amazon();
				$amazon->amazon_aws_definitions_uk();
				$pic_link = $amazon->amazon_aws_barcode_search($row['s_ISBN13'], $serviceUrl);
			}
			*/
				//$output =  '<div style="font-size: 58px; text-align: center; padding-bottom:10px; margin-left:20%; margin-right:20%"><select class="form-control s_qty">'.$options.'</select></div>';
			 
			$output =  '';
			//$output .=  '<div style="text-align:center;font-size: 18px;">'.$row['s_label'].'</div>';
			$output .= '<br>'.$pic_link;
			$output .= '';
			$prod_info = array($row['s_id'], $row['s_ISBN10'], $row['s_ISBN13'], $row['s_weight'], $row['s_height'], $row['s_length'], $row['s_width'], $row['s_label'], $row['s_price'], $row['s_price_like_new'], $row['s_price_good'], $row['s_price_ok']);
			
			#$sql = 'UPDATE test SET value = "123'.($output).'"';
			#mysqli_query($db,$sql);
			
			//$output = "test output - so silly business";
			
			return (array($output, $prod_info));
			if (file_exists('http://contact25.com/uploads/7_'.$row['s_id'].'.jpg')) {
				
			}else{
				include_once($root_loaction.'classes/class.amazon.php');
				$amazon 				= new amazon();
				$amazon->amazon_aws_definitions_uk();
				$amazon->amazon_aws_barcode_search($row['s_ISBN13'], $serviceUrl);
			}
			
			
				 
			//$output .=  '<div style="font-size: 58px; text-align: center; padding-bottom:10px; margin-left:20%; margin-right:20%"><select class="form-control s_qty">'.$options.'</select></div>';
			//$output .=  '<div style="text-align:center;font-size: 18px;">'.$row['s_label'].'</div>';
			//$output .= '<br>';
			$output .= '<img src="http://contact25.com/uploads/7_'.$row['s_id'].'.jpg" alt="'.$row['s_label'].'" style="max-width:100%; display: block; margin: 0 auto; " align="middle">';
			$prod_info = array($row['s_id'], $row['s_ISBN10'], $row['s_ISBN13'], $row['s_weight'], $row['s_height'], $row['s_length'], $row['s_width'], $row['s_label'], $row['s_price'], $row['s_price_like_new'], $row['s_price_good'], $row['s_price_ok']);
			
			#$sql = 'UPDATE test SET value = "123'.($output).'"';
			#mysqli_query($db,$sql);
			
			//$output = "test output - so silly business";
			
			return (array($output, $prod_info));
		}while($row = mysqli_fetch_assoc($query));
	}else{
		
		##include_once('/home/vhosts/contact25.com/httpdocs/classes/class.amazon.php');
		include_once($root_loaction.'classes/class.amazon.php');
		$amazon 				= new amazon();
		$amazon->amazon_aws_definitions_uk();
		$serviceUrl 			= "mws-eu.amazonservices.com";
		$check = $amazon->amazon_aws_barcode_search($barcode, $serviceUrl);
		if ($check == 'Sorry - we couldn\'t find this item'){
			$not_found = 'Sorry - we couldn\'t find this one - could you add it please?';
			return (array('-', array('', '', '', '', '', '', '', $not_found)));
		}else{
			// item found - show it.
			$output = barcode_lookup_add($barcode);
		}
		
		
	}
}







?>