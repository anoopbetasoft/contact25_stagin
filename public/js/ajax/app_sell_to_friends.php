<?php 

include("../../include/config.php");

global $db;

	if ($_POST['sell_to_friends'] > 0){
		$extra_sql = 'users.u_sell_f = 1';		
	}

	if ($_POST['sell_to_friends'] < 0){
		$extra_sql = 'users.u_sell_f = 0';		
	}


	if (strlen($_POST['sell_to_friends_subgroup_change']) > 0){
		$extra_sql = 'users.u_sell_f = "'.$_POST['sell_to_friends_subgroup_change'].'"';		
	}

	if ($_POST['sell_to_neighbours'] > 0){
		$extra_sql = 'users.u_sell_n = 1';		
	}

	if ($_POST['sell_to_neighbours'] < 0){
		$extra_sql = 'users.u_sell_n = 0';		
	}

	if ($_POST['sell_to_neighbours'] > 0){
		$extra_sql = 'users.u_sell_n = 1';		
	}

	if ($_POST['sell_to_neighbours'] < 0){
		$extra_sql = 'users.u_sell_n = 0';		
	}

	if ($_POST['sell_to_uk'] > 0){
		$extra_sql = 'users.u_sell_c = 1';		
	}

	if ($_POST['sell_to_uk'] < 0){
		$extra_sql = 'users.u_sell_c = 0';		
	}

	if ($_POST['lend_to_friends'] > 0){
		$extra_sql = 'users.u_lend_f = 1';		
	}

	if ($_POST['lend_to_friends'] < 0){
		$extra_sql = 'users.u_lend_f = 0';		
	}

	if ($_POST['lend_to_of_friends'] > 0){
		$extra_sql = 'users.u_lend_ff = 1';		
	}

	if ($_POST['lend_to_of_friends'] < 0){
		$extra_sql = 'users.u_lend_ff = 0';		
	}

	if ($_POST['lend_to_neighbours'] > 0){
		$extra_sql = 'users.u_lend_n = 1';		
	}

	if ($_POST['lend_to_neighbours'] < 0){
		$extra_sql = 'users.u_lend_n = 0';		
	}

	if ($_POST['lend_to_uk'] > 0){
		$extra_sql = 'users.u_lend_c = 1';		
	}

	if ($_POST['lend_to_uk'] < 0){
		$extra_sql = 'users.u_lend_c = 0';		
	}




	$sql = "
			UPDATE
				users
			SET
				".$extra_sql."
				
			WHERE
				users.u_id = ".$_SESSION['c25_id']."
			";

	echo $sql;
	//die($sql);".$_SESSION['u_id']"
	mysqli_query ($db,$sql);

?>