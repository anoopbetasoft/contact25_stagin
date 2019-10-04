<?php 

include("../../include/config.php");

global $db;
	
	$sql = 'SELECT
				u_id,
				u_sell_f,
				u_sell_ff,
				u_sell_n,
				u_sell_c,
				u_lend_f,
				u_lend_ff,
				u_lend_n,
				u_lend_c
			FROM
				users
			WHERE
				users.u_id = "'.$_SESSION['c25_id'].'"				
			';
  
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	if ($num_rows>0){
			
			$output = '';
		
			do{
					
					if ($row['u_sell_f']>0){

					$check_f = 'checked';
						
					}else{

					$check_f = '';
					}
					
				
					if ($row['u_sell_ff']>0){

					$check_ff = 'checked';
						
					}elseif ($row['u_sell_ff']<0){

					$check_ff = '';
					}
					
				
					if ($row['u_sell_n']>0){

					$check = 'checked';
						
					}elseif ($row['u_sell_n']<0){

					$check = '';
					}
					//die($check_f);
					$output .=
						'<ul>
				<li><b><span style="color: #ec038d">Sell to </span> <span style="font-size:20px; color:green">*</span></b></li>
              <li>
                <div class="checkbox checkbox-danger">  
                  <input type="checkbox" class="fxhdr" id="sell_to_friends" '.$check_f.'>
                  <label for="checkbox1" class="text-danger"> Friends  </label>
                </div>
				
				<div class="checkbox checkbox-info">
                  <input type="checkbox" class="fxhdr" '.$check_ff.' id="sell_to_friends_f_friends">
                  <label for="checkbox1" class="text-info"> Friends of Friends </label>
                </div>
           
                <div class="checkbox checkbox-primary">
                  <input type="checkbox" class="fxhdr" '.$check.' id="sell_to_neighbours">
                  <label for="checkbox2" class="text-primary"> Neighbours </label>
                </div>
             
                <div class="checkbox checkbox-success">
                  <input type="checkbox" class="fxhdr" id="sell_to_uk">
                  <label for="checkbox3"  class="text-success"> UK </label>
                </div>
              </li>
            </ul>
			   <ul>
				   <li><b><span style="color: #ec038d">Lend to </span><span style="font-size:20px; color:green">*</span></b></li>
              <li>
                <div class="checkbox checkbox-danger">
                  <input type="checkbox" class="fxhdr" id="lend_to_friends">
                  <label for="checkbox4" class="text-danger"> Friends </label>
                </div>
				
				<div class="checkbox checkbox-info">
                  <input type="checkbox" class="fxhdr" id="lend_to_friends_f_friends">
                  <label for="checkbox4" class="text-info"> Friends of Friends </label>
                </div>
           
                <div class="checkbox checkbox-primary">
                  <input type="checkbox" class="fxhdr" id="lend_to_neighbours">
                  <label for="checkbox5" class="text-primary"> Neighbours </label>
                </div>
             
                <div class="checkbox checkbox-success">
                  <input type="checkbox" class="fxhdr" id="lend_to_uk">
                  <label for="checkbox6" class="text-success"> UK </label>
                </div>
              </li>
            </ul>

						';
			}while($row = mysqli_fetch_assoc($query));
			
			
		}

?>

               <?=$output?>


	



 
            
