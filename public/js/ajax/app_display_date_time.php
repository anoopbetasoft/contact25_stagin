<?php 

include("../../include/config.php");

global $db;

	$sql = 'SELECT 
				* 
			FROM 
				users 
			WHERE 
				users.u_id = "'.$_SESSION['c25_id'].'"
			';

		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);



	$sql = 'SELECT
				cd_timestamp
			FROM
				credits_debits
			WHERE
				credits_debits.cd_u_id = "'.$_SESSION['c25_id'].'"
			';

	 
	  
		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);
	

		$trans_info = date("D, d F Y", strtotime($row['cd_timestamp']));

	

?>
                   
                <?=$trans_info?>
           
