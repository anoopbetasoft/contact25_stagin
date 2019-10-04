<?php 

include("../../include/config.php");
/* login*/ 

	global $db;

	$sql = 'SELECT * FROM users WHERE users.u_email = "'.$_POST['username'].'" AND users.u_pass = "'.$_POST['password'].'" LIMIT 1';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
		do{
			$_SESSION['c25_id']	= $row['u_id'];
			echo $row['u_id'];
		}while($row = mysqli_fetch_assoc($query));
	}else{
		echo -1;
		
	}




	
	
	
	

?>