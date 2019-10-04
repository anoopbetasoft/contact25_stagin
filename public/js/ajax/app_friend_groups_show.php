<?php 

include("../../include/config.php");

global $db;

	$sql = '
		SELECT 
			u_sell_f,
			u_lend_f
		FROM
			users
		WHERE 
			u_id =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);
	if ($num_rows>0){
		do{
			
			$sell_friends_ticked = $row['u_sell_f'];
			$lend_friends_ticked = $row['u_lend_f'];
			
		}while($row = mysqli_fetch_assoc($query));
	}

	$sell_friends_ticked = explode(",", $sell_friends_ticked);
	$lend_friends_ticked = explode(",", $lend_friends_ticked);

	$sql = 'SELECT 
			* 
		FROM
			users_friend_groups
		WHERE 
			ufg_u_id =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$friend_group_sell = '';
	$friend_group_lend = '';
	if ($num_rows>0){
		do{
			if (in_array($row['ufg_id'],$sell_friends_ticked)){
				$ticked = 'checked=checked';
			}else{
				$ticked = '';
			}
			$friend_group_sell .= '  <div class="btn-group" data-toggle="buttons">
                                            
						<label class="btn sell_to_friends_'.$row['ufg_id'].' focus active" style="padding:0px;">
							<div class="custom-control custom-checkbox mr-sm-2">
								<input type="checkbox" '.$ticked.'  class="custom-control-input checkbox-info sell_to_friends_default" data-group-id="'.$row['ufg_id'].'" id="sell_to_friends_'.$row['ufg_id'].'">
								<label class="custom-control-label text-info" for="checkbox1">'.$row['ufg_name'].'</label>
							</div>
						</label>


					</div>
				   ';
			if (in_array($row['ufg_id'],$lend_friends_ticked)){
				$ticked = 'checked=checked';
			}else{
				$ticked = '';
			}
			$friend_group_lend .= '<div class="btn-group" data-toggle="buttons">
                                            
						<label class="btn lend_to_friends_'.$row['ufg_id'].' focus active" style="padding:0px;">
							<div class="custom-control custom-checkbox mr-sm-2">
								<input type="checkbox" '.$ticked.' class="custom-control-input checkbox-info lend_to_friends_default" id="lend_to_friends_'.$row['ufg_id'].'">
								<label class="custom-control-label text-info" for="checkbox1">'.$row['ufg_name'].'</label>
							</div>
						</label>


					</div>';	 
		}while($row = mysqli_fetch_assoc($query));
	}

echo json_encode(array($friend_group_sell, $friend_group_lend));
die();
?>