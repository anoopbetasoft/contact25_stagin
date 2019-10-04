<?php 

include("../../include/config.php");


global $db;


$sql = 'SELECT * FROM users WHERE users.u_id = "'.$_SESSION['c25_id'].'"';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	
$address1 = $row['u_address_1'];
$address2 = $row['u_address_2'];
$address3 = $row['u_address_3'];
$address4 = $row['u_address_4'];
$postcode = $row['u_postcode'];
$u_country = $row['u_country'];

		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);
		
		
$sql = "
		UPDATE
	users
		SET
	users.u_address_1 = '".$address1."',
	users.u_address_2 = '".$address2."',
	users.u_address_3 = '".$address3."',
	users.u_address_4 = '".$address4."',
	users.u_postcode = '".$postcode."',
	users.u_country = '".$u_country."'
WHERE
	users.u_id = '".$_SESSION['c25_id']."'";

mysqli_query ($db,$sql);
	
	
$sql = 'SELECT * FROM countries WHERE countries.c_id =  "'.$u_country.'"';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$u_country = $row['c_name'];	
		
		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);



?>