<?php 

include("../../include/config.php");

global $db;

if ($_POST['u_country_post']>0){

$sql = '
		UPDATE
			users
		SET
			users.u_email 		= "'.$_POST['email'].'",
			users.u_address_1 	= "'.$_POST['address1'].'",
			users.u_address_2	= "'.$_POST['address2'].'",
			users.u_address_3 	= "'.$_POST['address3'].'",
			users.u_address_4 	= "'.$_POST['address4'].'",
			users.u_postcode 	= "'.$_POST['postcode'].'",
			users.u_country 	= "'.$_POST['u_country_post'].'",
			users.u_mob 		= "'.$_POST['u_mob'].'"
		WHERE
			users.u_id 			= "'.$_SESSION['c25_id'].'"
			';		
	
		mysqli_query($db,$sql);
}


$sql = 'SELECT * FROM users WHERE users.u_id = "'.$_SESSION['c25_id'].'"';
	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

		$email		= 	$row['u_email'];	
		$address1 	= 	$row['u_address_1'];
		$address2 	= 	$row['u_address_2'];
		$address3 	= 	$row['u_address_3'];
		$address4 	= 	$row['u_address_4'];
		$postcode 	= 	$row['u_postcode'];
		$u_country	= 	$row['u_country'];
		$u_mob		= 	$row['u_mob'];
	
$sql = '
		SELECT 
			c_id,c_name 
		FROM 
			countries 
		ORDER BY 
			c_name ASC';

		$query = mysqli_query($db,$sql);
		$row = mysqli_fetch_assoc($query);
		$num_rows = mysqli_num_rows($query);
	
	if($num_rows > 0){
			
			$country_dropdown = '<select id="add_u_country_post" class="form-control form-control-line">
                        ';
			
		do{
			if ($row['c_id'] == $u_country){
				$selected = 'selected';
			}else{
				$selected = '';
			}
			$country_dropdown .= '
                        <option value="'.$row['c_id'].'" '.$selected.'>'.$row['c_name'].'</option>
                      ';
			
		} while ($row = mysqli_fetch_assoc($query));
		
		$country_dropdown .= '
                      </select>';
	}



?>
<div class="form-material">
				<div class="form-group">
                    <div class="col-md-12">
                      <input type="text" id="add_email" class="form-control form-control-line" value="<?=$email?>">
                    </div>
                  </div>


				  <div class="form-group">
                    <div class="col-md-12">
						<label class="text_dd" for="fname" style="display:inline;"></label>
                      <input type="text" id="add_address1"  class="form-control form-control-line" value="<?=$address1?>"> 
                    </div>
                  </div>


                  <div class="form-group">
                    <div class="col-md-12">
                      <input type="text"  id="add_address2" placeholder="Address line 2" class="form-control form-control-line" value="<?=$address2?>">
                    </div>
                  </div>


                  <div class="form-group">
                    <div class="col-md-12">
                      <input type="text"  id="add_address3" placeholder="City" class="form-control form-control-line" value="<?=$address3?>">
                    </div>
                  </div>
                  

					<div class="form-group">
                    <div class="col-md-12">
                      <input type="text" id="add_address4" placeholder="County" class="form-control form-control-line" value="<?=$address4?>">
                    </div>
                  </div> 
					

                  <div class="form-group">
                    <div class="col-md-12">
						<label class="text_ddd" for="fname" style="display:inline;"></label>
                      <input type="text" id="add_postcode" placeholder="Postcode" class="form-control form-control-line" value="<?=$postcode?>">
                    </div>
                  </div>
                  

                  <div class="form-group">
                    <div class="col-md-12">
                      <input type="number" id="add_u_mob" placeholder="Mobile Number" class="form-control form-control-line" value="<?=$u_mob?>" required>
                    </div>
                  </div>
                 

                  <div class="form-group">
                    <div class="col-sm-12">
                    <?=$country_dropdown?>
                      
                    </div>
                  </div>


				<center>
                    <button type="button" class="btn btn-success card-body add_customer_details">Submit</button>
				</center>
</div>
		