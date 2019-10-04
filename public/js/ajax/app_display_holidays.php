<?php 

include("../../include/config.php");

global $db;

$sql = 'SELECT
			* 
		FROM 
			users_holidays
		WHERE 
			users_holidays.uh_u_id = '.$_SESSION['c25_id'].'
		AND
			users_holidays.uoh_end_time > NOW()
		ORDER BY
			users_holidays.uoh_end_time ASC
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);


if ($num_rows>0){
 			print '<div class="col-md-12 col-lg-12 col-xlg-12 col-sm-12 col-xs-12">
						<label class="control-label text-success" style="font-size:24px"> 
							<i class="fas fa-calendar-alt"></i> 
							Holidays
						</label>
					</div>';
			do{
				#Add an || for postcode and second address# #change code to show address or not depending if there is user information#
				
						print '<div class="row m-t-40">
						
                                    <!-- Column -->
                                    <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto;  min-width: 0;">
                                        <div class="card">
                                           <div class="ribbon-vwrapper" style="padding-right:0px; padding-bottom:0px;">
                                    <div class="ribbon ribbon-bookmark ribbon-vertical-l ribbon-info" style="height: 115px;">
										<i class="mdi mdi-airplane-takeoff"></i>
											</div>
                            				<div>
											   <h3 class="text-purple">Start</h3>
																										
                                                <p class="text-muted" style="font-size: 13px;">'.date("D d M Y", strtotime($row['uoh_start_time'])).'</p>
                                                <b style="font-size: 11px;">('.date("H:ia", strtotime($row['uoh_start_time'])).')</b> 
									  		</div>
                               	 			</div>
                                        </div>	
                                    </div>
									
                                    <!-- Column -->
									
                                    <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto;  min-width: 0;">
                                        <div class="card">
                                            <div class="ribbon-vwrapper" style="padding-right:0px; padding-bottom:0px;">
                                    <div class="ribbon ribbon-bookmark ribbon-vertical-l ribbon-danger" style="height: 115px;">
										<i class="mdi mdi-airplane-landing"></i>
									</div>
                                   				<div>
                                                <h3 class="text-primary">End</h3>
                                                <p class="text-muted" style="font-size: 13px;">'.date("D d M Y", strtotime($row['uoh_end_time'])).'</p>
                                                <b style="font-size: 11px;">('.date("H:ia", strtotime($row['uoh_end_time'])).')</b> 
												</div>
                               	 			</div>
                                        </div>
                                    </div>
                                    <!-- Column -->
									
                                    <div class="col-md-12 col-lg-2 col-xlg-2 col-sm-12 col-xs-12">
                                      <div class="card padding-top:0px;">
                                            <button type="button" class="btn btn-light remove_holiday" style="margin-top:25px;" data-holiday_id='.$row['uh_id'].'>
                                        <i class="ti-close" aria-hidden="true"></i>
                                    	</button>
                                        </div>
                                    </div>
                                    
                                </div>
								
									<hr>	
					';
		
	
			}while($row = mysqli_fetch_assoc($query));


		}



?>

	
