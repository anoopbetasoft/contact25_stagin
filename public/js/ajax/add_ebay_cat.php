<?php 

/*
	PAGE FUNCTIONS
	1 - Search Ebay based on the barcode posted
	2 - Parse the sub page and grabs the category link required to enter your own product

*/




//////////////////////////////////////////
/*					1					*/
//////////////////////////////////////////

	$html = file_get_contents('http://www.ebay.co.uk/sch/i.html?_nkw='.str_replace(" ", "+", $_POST['barcode']).'');
	$expStr=explode('lvpicinner full-width picW s225',$html);
	$resultString=explode('>',$expStr[1]);
	$resultString=explode('href=',$resultString[1]);
	$resultString=explode('"',$resultString[1]);
	$prod_page_html = $resultString[1];
	
//////////////////////////////////////////
/*					2					*/
//////////////////////////////////////////	
	
	$html = file_get_contents($prod_page_html);
	$expStr=explode('bc-w',$html);
	$resultString=explode('li',end($expStr)); ## end means get the last element in the exploded string ##
	$resultString=explode('href=',$resultString[0]);
	$resultString = explode('"',$resultString[1]);
	$title =  $resultString[4];
	$title =  preg_replace("/[^A-Za-z0-9_ -]/","",$title);
	
	$title = substr($title, 0, -1);
	
	echo $title;
	$category_string = preg_replace("/[^0-9]/","",$resultString[1]);
	
	echo "<br>";
	echo $category_string;