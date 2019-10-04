<?php 
include('../../config.php');  

/* REGISTER CUSTOMER*/

$sql = "
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
				'".$_POST['u_name']."',
				'".$_POST['u_email']."',
				'".$_POST['u_mob']."',
				'".$_POST['u_company']."',
				'".$_POST['u_address_1']."',
				'".$_POST['u_address_2']."',
				'".$_POST['u_address_3']."',
				'".$_POST['u_address_4']."',
				'".$_POST['u_postcode']."',
				'".$_POST['u_country']."'
			)
			


";
echo $sql;
mysql_query($sql);


$sql = 'SELECT u_id, u_name FROM users WHERE users.u_email = "'.$_POST['u_email'].'" LIMIT 1';
	$query = mysql_query($sql);
	$row = mysql_fetch_assoc($query);
	$num_rows = mysql_num_rows($query);
	$basket_list = '';

	if ($num_rows>0){
		do{
			
			$_SESSION['u_id'] = $row['u_id'];
			$_SESSION['u_name'] = $row['u_name'];
			unset($_SESSION['login_attempt']);
			echo  $_SESSION['u_id'];
		}while($row = mysql_fetch_assoc($query));
	}else{
		if (empty($_SESSION['login_attempt'])){
			$_SESSION['login_attempt'] = 1;	
		}else{
			$_SESSION['login_attempt'] ++;
		}
		echo 'FAIL:'.$_SESSION['login_attempt'];
	}

/*
if (strlen($_POST['a_address_1'])>0){
	/* SEPARATE DELIVERY ADDRESS POSTED*/	
	/*if ($_POST['choose_address'] == 1){
		
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
						'".$_POST['a_country']."',
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
				addresses.a_country = '".$_POST['a_country']."'

			WHERE 
				a_id = '".$_POST['choose_address']."'
		
		";
		mysql_query($sql);
	}
	
}
*/


#echo $sql;




#

		
	

?>


	
	
	