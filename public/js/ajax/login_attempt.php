<?php 
$hostname_tigers = "88.208.249.28";
$database_tigers = "contact25";
$username_tigers = "contact25-un";
$password_tigers = "mrW09n~8";
$tigers = mysql_pconnect($hostname_tigers, $username_tigers, $password_tigers) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_tigers, $tigers) or die("Please refresh browser");

session_start();
/* login*/


	$sql = 'SELECT u_id, u_name FROM users WHERE users.u_email = "'.$_POST['username'].'" AND users.u_pass = "'.$_POST['password'].'" LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	$basket_list = '';

	if ($num_rows>0){
		do{
			
			$_SESSION['u_id'] = $row['u_id'];
			$_SESSION['u_name'] = $row['u_name'];
			unset($_SESSION['login_attempt']);
			echo  $_SESSION['u_id'];
		}while($row = mysqli_fetch_assoc($query));
	}else{
		if (empty($_SESSION['login_attempt'])){
			$_SESSION['login_attempt'] = 1;	
		}else{
			$_SESSION['login_attempt'] ++;
		}
		echo 'FAIL:'.$_SESSION['login_attempt'];
	}


?>