<?php 

include("../../include/config.php");

global $db;

	$sql = 'SELECT 
				u_id, u_timezone 
			FROM 
				users 
			WHERE
				users.u_id = "'.$_SESSION['c25_id'].'"';
			
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
	if($num_rows > 0){
			
			
			
		do{
			$u_timezone = $row['u_timezone'];
			
		} while ($row = mysqli_fetch_assoc($query));
		
		
	}

$u_timezone = $u_timezone+$_POST['add_time'];


$sql = "UPDATE
	users
SET
	users.u_timezone = '".$u_timezone."'
WHERE
	users.u_id = '".$_SESSION['c25_id']."'";

mysqli_query ($db,$sql);

?>