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
$u_email = $row['u_email'];
$u_mob= $row['u_mob'];

if ($num_rows>0){
 
			do{
				#Add an || for postcode and second address# #change code to show address or not depending if there is user information#
				if ((strlen($row['u_address_1'])>0)||(strlen($row['u_postcode'])>0)){
						$display_user_details = '
						<small class="text-muted p-t-30 db">Email address</small>
                                <h6><div>'.$u_email.'</div></h6>

							<button type="button" class="btn btn-danger update_user_address" style="float:right">Edit</button>	
						<small class="text-muted p-t-30 db">Phone Number</small>
                                <h6><div>'.$u_mob.'</div></h6>
								
						<small class="text-muted">Address (Main):</small>
                                <h6>'.$address1.'<br>'.$address2.'<br>'.$address3.'<br>'.$address4.', '.$postcode.'</h6>
							<div class="map-box">
								<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d470029.1604841957!2d72.29955005258641!3d23.019996818380896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C+Gujarat!5e0!3m2!1sen!2sin!4v1493204785508" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
							</div>	
					';
					}else{
						$display_user_details = '<center>
                                					<button type="button" class="btn btn-success card-body add_new_address">
														<i class="fa fa-plus-circle"></i>  Add Address 
													</button>
												</center>';
					}
					
				
				print $display_user_details

								
				;
				 
			}while($row = mysqli_fetch_assoc($query));


		}



?>
