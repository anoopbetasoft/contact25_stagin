<?php 
include ("../../config.php");
/*
$sql = 'DELETE FROM stage_elements WHERE s_e_id = "'.$_POST['element_id'].'"
					
';
/* move element up*/

/*1 is the top position of an element*/

	/* update the displaced order*/
	$sql = 'UPDATE stage_elements SET s_order = "'.($_POST['current_order']-1).'" WHERE s_id = "'.$_POST['stage_id'].'" AND s_order = "'.($_POST['current_order']+1).'"';
	mysql_query($sql);
	
	$sql = 'UPDATE stage_elements SET s_order = "'.($_POST['current_order']+1).'" WHERE s_e_id = "'.$_POST['element_id'].'"';
	mysql_query($sql);	
	
	
	

	
	





