@php
$service_opening_hrs = App\service_opening_hr::where('user_id',Auth::user()->id)->get();
@endphp
<div class="col-md-3">
    <div class="form-group">
        <label class="control-label text-info " style="font-size:24px;"><i class="fa fa-tag"></i> Sell to</label>
        @include('users.product.add_product.product_sell_to')
    </div>
</div>

<div class="col-md-3">
    <div class="form-group service_div">
      
        <label class="control-label text-success" style="font-size:24px"> <i class="fa fa-user"></i> Service</label>
        @foreach($p_service_option  as $series=> $otpns)
            @if($otpns['id'] ==1)
                @php $class_service = 'danger';@endphp
            @elseif($otpns['id'] ==2)
                @php $class_service = 'primary';@endphp
            @else
                @php $class_service = 'success';@endphp
            @endif
            <div class="checkbox checkbox-{{$class_service}} checkbox-circle sell-wrapper checkbox_service_list" >
                <input id="checkbox_service_{{$otpns['id']}}"  data-value="{{$otpns['id']}}" type="checkbox" style="cursor:pointer;" onclick="service_type_selected(this);" data-service_type = "{{$otpns['type']}}_service" data-selected_option="{{$otpns['type']}}" class="" @if($otpns['id']==1) name="location_select" @endif>
                <label for="checkbox_service_{{$otpns['id']}}" class="text-{{$class_service}}" style="cursor:pointer;">{{$otpns['display_text']}}</label>
            </div>
        @endforeach

        <input type="hidden" name="serviceoption" value="">
    </div>
</div>

<div class="col-md-6">
    <div class="row">
    <div class="col-md-6">
    <label class="control-label selling_product_price text-success"><i class="fa fa-tag"></i> Selling Price</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <button class="btn btn-success" type="button">{{$currency_symbol[0]->symbol}}</button>
        </div>
        <input type="number"  step="any" min="0" class="form-control" placeholder="Price" name="p_sell_price">
    </div>
    </div>
    <div class="col-md-6">
    <label class="control-label selling_product_price text-success"><i class="fa fa-tag"></i> Service Time</label>
    <div class="input-group mb-3">
        <input type="number" name="service_time" class="form-control" placeholder="Service Time">
       <select class="form-control" name="service_time_type">
           <option value="min">Minute(s)</option>
           <option value="hour">Hour(s)</option>
           <option value="day">Day(s)</option>
       </select>
    </div>
</div>
</div>
    <div class="form-group group_service" style="display: none">
        <label class="control-label text-success"><i class="fas fa-users"></i> Group Session</label>
        <input type="number"  step="any" min="0"  class="form-control input_types" placeholder="Max spaces available" name="p_service_group_price">
    </div>
</div>

<!-- location -->

<div class="col-md-6 location_service" style="display: none;">
    <div class="form-group">
        <label class="control-label text-success" style="font-size:24px"> <i class="ti-location-pin" style="color:#00c292"></i> Location</label>
       <!-- <input type="text" class="form-control input_types" placeholder="Location" name="p_service_location">-->
        <input type="hidden" id="location_coord" name="p_service_location">
        <input id="pac-input" class="form-control input_types" placeholder="Location" type="text" >
        <div id="map"></div>
    </div>
</div>

<div class="col-md-6 location_service" style="display:none">
    <label class="control-label text-success" style="font-size:24px"> <i class="fas fa-taxi"></i> Radius</label>
    <div class="input-group mb-3">
        <input type="number"  min="0" class="form-control input_types" placeholder="miles / km you will travel to carry out this service" name="p_service_radius">
        <div class="input-group-prepend custom_dropdown_width">
            
            <select class="form-control" data-style="form-control btn-secondary" name="p_service_radius_option" style="cursor: pointer;">
                <option value="0" selected="selected" style="cursor: pointer;">No Radius</option>
                <option value="1" style="cursor: pointer;">Distance (Km)</option>
                <option value="2" style="cursor: pointer;">Distance (Miles)</option>
            </select>
        </div>
    </div>
</div>
<!-- time -->

<div class="col-md-6 time_service" style="display:none">
    <div class="form-group">
        <label class="control-label text-success" style="font-size:24px"> <i class="fa fa-user"></i> Start Time</label>
        <input type="text" class="form-control input_types" placeholder="Select Time" name="p_service_time" id="p_service_time">
    </div>
</div>
<div class="col-md-6 time_service" style="display:none">
    <label class="control-label text-success" style="font-size:24px"> <i class="fa fa-repeat"></i> Repeat</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend custom_dropdown_width">
            <select class="form-control" data-style="form-control btn-secondary"  name="p_service_repeat_option" style="cursor: pointer;">
                <option value="1" style="cursor: pointer;">Daily</option>
                <option value="2" style="cursor: pointer;">Weekly</option>
                <option value="3" style="cursor: pointer;">Monthly</option>
                <option value="4" style="cursor: pointer;">Anually</option>
            </select>
        </div>
        <input type="number"  min="0" class="form-control input_types" name="p_service_repeat" placeholder="x times">
        <div class="input-group-append">
            <span class="input-group-text" style="cursor: pointer;">
                <div class="custom-control custom-checkbox" style="cursor: pointer;">
                    <input type="checkbox" class="custom-control-input" id="service_time_forever" placeholder="" name="p_service_forever">
                    <label class="custom-control-label" for="service_time_forever" style="cursor: pointer;">Forever</label>
                </div>
            </span>
           
        </div>
    </div>
</div>
<div class="service_hours col-md-6">
    <div class="form-group">
        <label class="control-label text-success" style="font-size:24px"> <i class="far fa-clock" style="color:#00c292"></i> Service Hours</label><br><p style="color: #808080">(times you can do services)</p>
        <div class="row" style="margin-top: -17px;">
        <ul id="themecolors" class="m-t-20 service_hours">
                
                <li>
                    <ul class="list_open_hours"> 
                    @if(count($service_opening_hrs) > 0)
                        
                        
                        @foreach($service_opening_hrs as $hrs)
                            <p id="hour_id_{{$hrs['id']}}" style="font-size:15px">{{ucwords($hrs['user_day_name'])}} ({{date("g:i a", strtotime($hrs['user_start_time']))}} - {{date("g:i a", strtotime($hrs['user_end_time']))}})    <span class="text-danger" data-user_hr_id="{{$hrs['id']}}" onclick="removeServiceHour(this);"> <i class="fa fa-trash" aria-hidden="true" style="cursor: pointer;"></i> </span>  </p>
                        @endforeach
                        
                    @endif
                    </ul>
                    <?php //echo "<pre>";print_r($user_hrs); echo "</pre>"; ?>
                </li>

                <li class="open_times" style=" font-size:11px"></li>

                 <ul class="hours-select clearfix inline-lasyout up-4" style="font-size:10px;margin-top: -17px;">
                   <!-- <li> -->
                    <div class="row">
                    <div class="col-lg-3">
                        <select class="weekday text-info form-control">
                            <option value="1" data-user_day_name = "monday">Mon</option>
                            <option value="2" data-user_day_name = "tuesday">Tue</option>
                            <option value="3" data-user_day_name = "wednesday">Wed</option>
                            <option value="4" data-user_day_name = "thursday">Thur</option>
                            <option value="5" data-user_day_name = "friday">Fri</option>
                            <option value="6" data-user_day_name = "saturday">Sat</option>
                            <option value="7" data-user_day_name = "sunday">Sun</option>
                        </select>
                    </div>
                        <?php
                            $begin = new DateTime("00:00");
                            $end   = new DateTime("24:00");
                            $interval = DateInterval::createFromDateString('30 min');
                            $times    = new DatePeriod($begin, $interval, $end); 
                        ?>
                        <div class="col-lg-4">
                        <select class="hours_start text-info form-control">
                            <?php 
                            foreach ($times as $time) {

                                if($time->format('H:i:s') == '09:00:00'){
                                    $select_start = 'selected';
                                }else{
                                     $select_start = '';
                                }

                                echo '<option value="'.$time->format('H:i:s').'" '.$select_start.' >'.$time->format('h:i  a').'</option>';
                            }
                            ?>
                        </select>
                        </div>
                        <div class="col-lg-4">
                        <select class="hours_end text-info form-control">
                            <?php
                            foreach ($times as $time) {
                                if($time->format('H:i:s') == '17:00:00'){
                                    $select_end = 'selected';
                                }else{
                                     $select_end = '';
                                }
                                
                                echo '<option value="'.$time->format('H:i:s').'" '.$select_end.' >'.$time->format('h:i  a').'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                    <!-- </li> -->
                    <li>
                        <button class="btn btn-info  add_opening_times" onclick="add_service_times(this);" type="button">Add Service Hours</button>
                    </li>
                    <br>
                </ul>
            </ul>
    </div>
</div>
</div>
<div class="service_lead_time col-md-6">
    <div class="form-group">
        <label class="control-label text-success" style="font-size:24px"> <i class="far fa-clock" style="color:#00c292"></i> Service Lead Time</label>
       <i class="far fa-question-circle" onclick="servicelead()" style="cursor: pointer;"></i>
        <select name="service_lead_time" class="form-control">
            <option value="0">Select Service Lead Time</option>
            <option value="1">1 Day</option>
            <option value="2">2 Days</option>
            <option value="3">3 Days</option>
            <option value="4">4 Days</option>
            <option value="5">5 Days</option>
            <option value="6">6 Days</option>
            <option value="7">7 Days</option>
            <option value="8">8 Days</option>
            <option value="9">9 Days</option>
            <option value="10">10 Days</option>
            <option value="11">11 Days</option>
            <option value="12">12 Days</option>
            <option value="13">13 Days</option>
            <option value="14">14 Days</option>
            <option value="15">15 Days</option>
            <option value="16">16 Days</option>
            <option value="17">17 Days</option>
            <option value="18">18 Days</option>
            <option value="19">19 Days</option>
            <option value="20">20 Days</option>
            <option value="21">21 Days</option>
            <option value="22">22 Days</option>
            <option value="23">23 Days</option>
            <option value="24">24 Days</option>
            <option value="25">25 Days</option>
            <option value="26">26 Days</option>
            <option value="27">27 Days</option>
            <option value="28">28 Days</option>
            <option value="29">29 Days</option>
            <option value="30">30 Days</option>
        </select>
    </div>
</div>
    <style type="text/css">
        #map{
            height:300px;
        }
    </style>
    <script src="{{asset('assets/js/customer/location_map.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ&libraries=places&callback=initAutocomplete"></script>
    <script type="text/javascript">
        $("#p_service_time").bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
    </script>
