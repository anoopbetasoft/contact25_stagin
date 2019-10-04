<?php 
include('config.php');  
#include('include/includes.php'); 

$sql = 'UPDATE spares SET spares.s_checked = "1999-01-01 00:00:00" WHERE spares.s_id = "'.$_POST['id_to_remove'].'"';
mysql_query($sql);
die($sql);
/*
	PAGE FUNCTIONS
	1 - Search Amazon based url
	2 - Parse the page and grab the basic product details
	3 - Add to the database if they don't exist already

*/


		
		$sql 		= 'SELECT * FROM spares WHERE spares.s_ISBN10 = "'.$after_string[0].'"';
		$query		= mysql_query($sql);
		$row		= mysql_fetch_assoc($query);
		$num_rows	= mysql_num_rows($query);
		