<?php 
 
include("../../include/config.php");

global $db;

$sql = 'SELECT
			*
		FROM
			users_delivery_provider
		WHERE
			users_delivery_provider.udp_u_id = '.$_SESSION['c25_id'].'
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);




if ($num_rows>0){
	
print '<hr>
						<div class="col-md-12 col-lg-12 col-xlg-12 col-sm-12 col-xs-12">
								<label class="control-label text-success" style="font-size:24px; padding-top:12px;"> 
									<i class="fas fa-truck"></i> 
									Add Delivery Options
								</label>
								
							</div>
							<div class="row" style="padding-left: 8px; padding-top: 12px">
						
                                    <!-- Column -->
                                    <div class="col-md-4 col-lg-4 col-xlg-4 col-sm-6 col-xs-2" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>								
                                                 <p class="text-purple font-medium">Name</p>
									</div>
									
									<div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>								
                                                 <p class="text-primary font-medium">Link</p>
                                      </div> 
									  
									  <div class="col-md-12 col-lg-2 col-xlg-2 col-sm-12 col-xs-12">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                        </div>
                                    </div>
									  		';
				
			do{
				#Add an || for postcode and second address# #change code to show address or not depending if there is user information#
				
						print'
				
				<div class="col-md-4 col-lg-4 col-xlg-4 col-sm-6 col-xs-2" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>							
                                                 <p class="text-muted font-medium">'.$row['udp_name'].'</p>
										</div>
										
										<div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>							
                                                 <p class="text-muted font-medium">'.$row['udp_url'].'</p>
										</div>
										
										
										<div class="col-md-12 col-lg-2 col-xlg-2 col-sm-12 col-xs-12">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                            <button type="button" class="btn btn-light remove_courier" data-courier_id='.$row['udp_id'].'>
                                        <i class="ti-close" aria-hidden="true"></i>
                                    	</button>
                                        </div>
                                    </div>
                                    
		';
				
			}while($row = mysqli_fetch_assoc($query));
	
		}



print'</div>
     	<hr>

			<div class="form-horizontal form-material" style="padding-top: 12px; padding-left: 5px;">
                  <div class="form-group">
                    <label class="col-md-12">Courier / Delivery Provider</label>
                    <div class="col-md-12">
                      <input type="text" id="deliver_name" placeholder="DHL / TNT / Yodel / Royal Mail" class="form-control form-control-line">
                    </div>
                  </div>
				
                  <div class="form-group">
                    <label for="example-email" class="col-md-12">Tracking URL</label>
                    <div class="col-md-12">
                      <input type="text" id="deliver_link" placeholder="https://courier-tracking-location" class="form-control form-control-line" name="example-email" id="example-email">
                    </div>
                  </div>
                  
					
                 
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button class="btn btn-danger add_courier"><i class="fas fa-truck"></i>  Add Courier</button>
                    </div>
                  </div>
           	</div> ';

?>
                             
									
                                  

                   