<?php 

include("../../include/config.php");

global $db;

$sql = 'SELECT 
			COUNT(users_friends.uf_u_id) AS "user_count"
		FROM  
			users_friends
		WHERE 
			users_friends.uf_u_id =  "'.$_SESSION['c25_id'].'" 
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$friends = $row["user_count"];


$sql = 'SELECT 
			count(stock_c25.s_room) AS "box_count" 
		FROM
				stock_c25 
		WHERE 
				stock_c25.s_u_id =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$box_friend = $row["box_count"];

$sql = 'SELECT 
			users.u_name, users.u_amazon_supplier_name  
		FROM
			users
		WHERE 
			users.u_id =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);


	$user_name = $row["u_name"];
	$company_name = $row["u_amazon_supplier_name"];


$sql = 'SELECT 
			count(order_details.od_purchased_via) AS "sales_count"  
		FROM
			order_details
		WHERE 
			order_details.od_purchased_via =  "'.$_SESSION['c25_id'].'"
		';

	$query = mysqli_query($db,$sql);
	$row = mysqli_fetch_assoc($query);
	$num_rows = mysqli_num_rows($query);

	$number_of_sales = $row["sales_count"];

	
			do{
				#Add an || for postcode and second address# #change code to show address or not depending if there is user information#
				if (1<0){
						$user_detail_display = '<center class="m-t-30"> 
												<img src="assets/images/users/5.jpg" class="img-circle" width="150" />
												<div class="row text-center justify-content-md-center">
                                        		<div class="col-4">
												<a href="javascript:void(0)" class="link">
												<i class="fa fa-upload"></i></a>
												</div>
												
													

                                        		<div class="col-4">
												<a href="javascript:void(0)" class="link"><i class="fa fa-camera"></i></a>
												</div>
                                    			</div>
												
												<h4 class="card-title m-t-10">'.$user_name.'</h4>
												<h6 class="card-subtitle">'.$company_name.'</h6>
												
												<div class="row text-center justify-content-md-center">
													<div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">'.$friends.'</font></a></div>
													<div class="col-4"><a href="javascript:void(0)" class="link"><i class="ti-bar-chart"></i> <font class="font-medium">'.$number_of_sales.'</font></a></div>
													<div class="col-4"><a href="javascript:void(0)" class="link"><i class="ti-package"></i> <font class="font-medium">'.$box_friend.'</font></a></div>
												</div>
												</center>
					';
					}else{
						$user_detail_display = '';
					}
					
				
				print $user_detail_display

								
				;
				 
			}while($row = mysqli_fetch_assoc($query));



?>



<div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Communication</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Delivery/Tracking</a> </li>
								<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#holiday" role="tab">Holiday Period</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <div class="card-body">
                                        
                                            		 
                <div class="checkbox checkbox-info">
                  <input id="checkbox1" type="checkbox" class="fxhdr">
                  <label for="checkbox1" style="color: #03a9f3"> Receive an order </label>
                </div>
				  
           
                <div class="checkbox checkbox-warning">
                  <input id="checkbox2" type="checkbox" class="fxsdr">
                  <label for="checkbox2" style="color:#fec107"> Receive a message </label>
                </div>
             
                <div class="checkbox checkbox-success">
                  <input id="checkbox4" type="checkbox" class="open-close">
                  <label for="checkbox4" style="color:#00c292"> Reminder (before setting off to collect something) </label>
                </div>
             
                  <div class="checkbox checkbox-info">
                  <input id="checkbox1" type="checkbox" class="fxhdr">
                  <label for="checkbox1" style="color: #03a9f3"> Reminder (before a collection)</label>
                </div>    

                                    
                                    </div>
                                </div>
                                <!--second tab-->
                                
                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <div class="card-body">
                                        <form class="form-horizontal form-material">
                  <div class="form-group">
                    <label class="col-md-12">Courier / Delivery Provider</label>
                    <div class="col-md-12">
                      <input type="text" placeholder="DHL / TNT / Yodel / Royal Mail" class="form-control form-control-line">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="example-email" class="col-md-12">Tracking URL</label>
                    <div class="col-md-12">
                      <input type="text" placeholder="https://courier-tracking-location" class="form-control form-control-line" name="example-email" id="example-email">
                    </div>
                  </div>
                  
					
                 
                  <div class="form-group">
                    <label class="col-sm-12">Inpost Next Day (UK ONLY)</label>
                    <div class="col-sm-12">
                      <select class="form-control form-control-line">
                        <option>Off</option>
                        <option>On</option>
                        
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button class="btn btn-success">Add</button>
                    </div>
                  </div>
                </form>
                                    </div>
                                </div>
								
								
				<!--second tab-->
                                
                                <div class="tab-pane" id="holiday" role="tabpanel">
                                    <div class="card-body">
                                        
               
                
										<p>Explain how it works</p>
										
									
									
									 <div class="col-md-6">
										 <label class="control-label text-success" style="font-size:24px"> <i class="fas fa-calendar-alt"></i> Start Time</label>
										 <div class="input-group mb-3">
									
										
										 
									 <input type="text" id="date-format" class="form-control" placeholder="Saturday 24 June 2017 - 21:44" data-dtp="dtp_vFMNY">		
										 </div>
									 </div>
									 
									  
										
											
											
											
											
									 
									 <div class="col-md-6">
										 <label class="control-label text-success" style="font-size:24px"> <i class="fas fa-calendar"></i> End Time</label>
										 <div class="input-group mb-3">
									<div id="date-format2-hidden" style="display: none;"></div>
										
										 
									 <input type="text" id="date-format2" class="form-control" data-dtp="dtp_vFMNY">
										 </div>
									 </div>
									 
									  
										
                 
              
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button class="btn btn-success add_holiday_time">Add Holiday</button>
						
                    </div>
                  </div>
               
                                    </div>
                                </div>	
								
								
                            </div>
                        </div>


  <script>
    // MAterial Date picker    
    $('#mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
    $('#timepicker').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
    $('#date-format').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
	$('#date-format2').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
		
    $('#min-date').bootstrapMaterialDatePicker({ format: 'DD/MM/YYYY HH:mm', minDate: new Date() });
    // Clock pickers
    $('#single-input').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $('.clockpicker').clockpicker({
        donetext: 'Done',
    }).find('input').change(function() {
        console.log(this.value);
    });
    $('#check-minutes').click(function(e) {
        // Have to stop propagation here
        e.stopPropagation();
        input.clockpicker('show').clockpicker('toggleView', 'minutes');
    });
    if (/mobile/i.test(navigator.userAgent)) {
        $('input').prop('readOnly', true);
    }
    // Colorpicker
    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker({
        mode: 'gradient'
    });
    // Date Picker
    jQuery('.mydatepicker, #datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    jQuery('#date-range').datepicker({
        toggleActive: true
    });
    jQuery('#datepicker-inline').datepicker({
        todayHighlight: true
    });
    // Daterange picker
    $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-daterange-timepicker').daterangepicker({
        timePicker: true,
        format: 'MM/DD/YYYY h:mm A',
        timePickerIncrement: 30,
        timePicker12Hour: true,
        timePickerSeconds: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-limit-datepicker').daterangepicker({
        format: 'MM/DD/YYYY',
        minDate: '06/01/2015',
        maxDate: '06/30/2015',
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        dateLimit: {
            days: 6
        }
    });
    </script>
