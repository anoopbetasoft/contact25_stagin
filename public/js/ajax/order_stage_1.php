<?php 
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

session_start(); 

/* new with paypal express checkout - check if the email address has been used before- if so, don't add a new user - has to be unique and if they've logged in using that email address to paypal, they own it*/
$sql = 'SELECT 	
			*
		FROM
			users
		WHERE
			u_email = "'.$_POST['u_email'].'"';
$query = mysql_query($sql);
$row = mysql_fetch_assoc($query);
$num_rows = mysql_num_rows($query);
do{
	/* save the ID */
	$c_id = $row['u_id'];	
	$_SESSION['u_id'] = $row['u_id'];
}while($row = mysql_fetch_assoc($query));


$country_id = $_POST['u_country'];
/* STAGE 1: UPDATE ORDER & DELIVERY ADDRESS DETAILS*/
if ($_SESSION['u_id']>0){
/* CUSTOMER'S USER DETAILS*/
$sql = "
	UPDATE 
		users
	SET 
		users.u_name = '".$_POST['u_name']."',
		users.u_email = '".$_POST['u_email']."',
		users.u_mob = '".$_POST['u_mob']."',
		users.u_company = '".$_POST['u_company']."',
		users.u_address_1 = '".$_POST['u_address_1']."',
		users.u_address_2 = '".$_POST['u_address_2']."',
		users.u_address_3 = '".$_POST['u_address_3']."',
		users.u_address_4 = '".$_POST['u_address_4']."',
		users.u_postcode = '".$_POST['u_postcode']."',
		users.u_country = '".$country_id."'
		
	
	WHERE 
		u_id = '".$_SESSION['u_id']."'

";
echo $sql;
mysql_query($sql);
}else{
	$sql = '
			INSERT INTO 
				users
					(
						u_name,
						u_email,
						u_mob,
						u_company,
						u_address_1,
						u_address_2,
						u_address_3,
						u_address_4,
						u_postcode,
						u_country
					)
				VALUES
					(
						"'.ucwords($_POST['u_name']).'",
						"'.$_POST['u_email'].'",
						"'.$_POST['u_mob'].'",
						"'.ucwords($_POST['u_company']).'",
						"'.ucwords($_POST['u_address_1']).'",
						"'.ucwords($_POST['u_address_2']).'",
						"'.ucwords($_POST['u_address_3']).'",
						"'.ucwords($_POST['u_address_4']).'",
						"'.strtoupper ($_POST['u_postcode']).'",
						"'.$country_id.'"
						
					)
					
		';
		mysql_query($sql);
		
		/*select the customer you've just inserted */
			$sql = 'SELECT 	
						*
					FROM
						users
					WHERE
						u_email = "'.$_POST['u_email'].'"';
			$query = mysql_query($sql);
			$row = mysql_fetch_assoc($query);
			$num_rows = mysql_num_rows($query);
			do{
				/* save the ID */
				$c_id = $row['u_id'];	
				$_SESSION['u_id'] = $row['u_id'];
			}while($row = mysql_fetch_assoc($query));



}




if (strlen($_POST['a_address_1'])>0){
	/* SEPARATE DELIVERY ADDRESS POSTED*/	
	if ($_POST['choose_address'] == 1){
		
		$sql = "
			INSERT INTO 
				addresses
					(
						a_name,
						a_address_1,
						a_address_2,
						a_address_3,
						a_address_4,
						a_postcode,
						a_country,
						a_u_id
					) 
				VALUES
					(
						'".$_POST['a_name']."',
						'".$_POST['a_address_1']."',
						'".$_POST['a_address_2']."',
						'".$_POST['a_address_3']."',
						'".$_POST['a_address_4']."',
						'".$_POST['a_postcode']."',
						'".$country_id."',
						'".$_SESSION['u_id']."'
						
						
					)
				
		";
		mysql_query($sql);
		
	}
	
	if ($_POST['choose_address'] > 1){
		
		$sql = "
			UPDATE 
				addresses
			SET 
				addresses.a_name = '".$_POST['a_name']."',
				addresses.a_address_1 = '".$_POST['a_address_1']."',
				addresses.a_address_2 = '".$_POST['a_address_2']."',
				addresses.a_address_3 = '".$_POST['a_address_3']."',
				addresses.a_address_4 = '".$_POST['a_address_4']."',
				addresses.a_postcode = '".$_POST['a_postcode']."',
				addresses.a_country = '".$country_id."'

			WHERE 
				a_id = '".$_POST['choose_address']."'
		
		";
		mysql_query($sql);
	}
	
}



#echo $sql;




#

		
	

?>


	
	
	