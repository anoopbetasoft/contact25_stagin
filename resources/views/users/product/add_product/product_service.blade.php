@php
$service_opening_hrs = App\service_opening_hr::where('user_id',Auth::user()->id)->orderBy('user_day','ASC')->get();
@endphp
@if(isset($product))
    <input type="hidden" name="p_type" value="{{$product[0]['p_type']}}">
@endif
<div class="col-md-3">
    <div class="form-group">
        <label class="control-label text-info " style="font-size:24px;"><i class="fa fa-tag"></i> Sell to</label>
        @include('users.product.add_product.product_sell_to')
    </div>
</div>
<input type="hidden" name="deliveryvalues" value="{{count($delivery)}}">
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
        @php
        if(isset($product) && $product[0]['p_service_option']!='')
        {
            $serviceoptions = explode(',',$product[0]['p_service_option']);
        }
        if(isset($product) && $product[0]['p_service_option']=='')
        {
            $serviceoptions = [];
        }
        @endphp
            <div class="checkbox checkbox-{{$class_service}} checkbox-circle sell-wrapper checkbox_service_list" >
                <input id="checkbox_service_{{$otpns['id']}}"  data-value="{{$otpns['id']}}" type="checkbox" style="cursor:pointer;" onclick="service_type_selected(this);" data-service_type = "{{$otpns['type']}}_service" data-selected_option="{{$otpns['type']}}" class="" @if($otpns['id']==1) name="location_select" @endif @if(isset($product) && $product[0]['p_service_option']!='' && (($otpns['id']=='1' && in_array('1',$serviceoptions)) || ($otpns['id']=='2' && in_array('2',$serviceoptions)) || ($otpns['id']=='3' && in_array('3',$serviceoptions)))) checked @endif>
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
        <input type="number"  step="any" min="0" class="form-control" placeholder="Price" name="p_sell_price" @if(isset($product)) value="{{$product[0]['p_selling_price']}}" @endif>
    </div>
    </div>
    <div class="col-md-6">
    <label class="control-label selling_product_price text-success"><i class="fa fa-tag"></i> Service Time</label>
    <div class="input-group mb-3">
        <input type="number" name="service_time" class="form-control" placeholder="Service Time" @if(isset($product)) value="{{$product[0]['service_time']}}" @endif>
        @if(isset($product))
            <select class="form-control" name="service_time_type">
                <option value="min" @if($product[0]['service_time_type'] == 'min') {{'Selected'}} @endif>Minute(s)</option>
                <option value="hour" @if($product[0]['service_time_type'] == 'hr' || $product[0]['service_time_type'] == 'hrs') {{'Selected'}} @endif>Hour(s)</option>
                <option value="day" @if($product[0]['service_time_type'] == 'day') {{'Selected'}} @endif>Day(s)</option>
            </select>
        @else
           <select class="form-control" name="service_time_type">
               <option value="min">Minute(s)</option>
               <option value="hour">Hour(s)</option>
               <option value="day">Day(s)</option>
           </select>
        @endif
    </div>
</div>
</div>
   @if(isset($product) && in_array('3',$serviceoptions))
    <div class="form-group group_service" style="display: block">
   @else
    <div class="form-group group_service" style="display: none">
   @endif
        <label class="control-label text-success"><i class="fas fa-users"></i> Group Session</label>
        <input type="number"  step="any" min="0"  class="form-control input_types" placeholder="Max spaces available" name="p_service_group_price" @if(isset($product)) value="{{$product[0]['p_group']}}" @endif>
    </div>
</div>

<!-- location -->
@if(isset($product) && in_array('1',$serviceoptions))
    <div class="col-md-6 location_service" style="display: block;">
        @php
        $location = explode(',',$product[0]['p_location']);
        $lat = $location[0];
        $lng = $location[1];
        @endphp
        <input type="hidden" id="lat" value="{{$lat}}">
        <input type="hidden" id="lng" value="{{$lng}}">
@else
    <div class="col-md-6 location_service" style="display: none;">
@endif
    <div class="form-group">
        <label class="control-label text-success" style="font-size:24px"> <i class="ti-location-pin" style="color:#00c292"></i> Location</label>
       <!-- <input type="text" class="form-control input_types" placeholder="Location" name="p_service_location">-->
        <input type="hidden" id="location_coord" name="p_service_location">
        <input id="pac-input" class="form-control input_types" placeholder="Location" type="text" >
        <div id="map"></div>
    </div>
</div>
@if(isset($product) && in_array('1',$serviceoptions))
    <div class="col-md-6 location_service" style="display:block">
@else
    <div class="col-md-6 location_service" style="display:none">
@endif
    <label class="control-label text-success" style="font-size:24px"> <i class="fas fa-taxi"></i> Radius</label>
    <div class="input-group mb-3">
        <input type="number"  min="0" class="form-control input_types" placeholder="miles / km you will travel to carry out this service" name="p_service_radius" @if(isset($product)) value="{{$product[0]['p_radius']}}" @endif>
        <div class="input-group-prepend custom_dropdown_width">
            @if(isset($product))
            <select class="form-control" data-style="form-control btn-secondary" name="p_service_radius_option" style="cursor: pointer;">
                <option value="0" selected="selected" style="cursor: pointer;" @if($product[0]['p_radius_option'] == '0') {{'Selected'}} @endif>No Radius</option>
                <option value="1" style="cursor: pointer;" @if($product[0]['p_radius_option'] == '1') {{'Selected'}} @endif>Distance (Km)</option>
                <option value="2" style="cursor: pointer;" @if($product[0]['p_radius_option'] == '2') {{'Selected'}} @endif>Distance (Miles)</option>
            </select>
            @else

            <select class="form-control" data-style="form-control btn-secondary" name="p_service_radius_option" style="cursor: pointer;">
                <option value="0" selected="selected" style="cursor: pointer;">No Radius</option>
                <option value="1" style="cursor: pointer;">Distance (Km)</option>
                <option value="2" style="cursor: pointer;">Distance (Miles)</option>
            </select>
            @endif
        </div>
    </div>
</div>
<!-- time -->
@if(isset($product) && in_array('2',$serviceoptions))
<div class="col-md-6 time_service" style="display:block">
@else
<div class="col-md-6 time_service" style="display:none">
@endif
    <div class="form-group">
        <label class="control-label text-success" style="font-size:24px"> <i class="fa fa-user"></i> Start Time</label>
        <input type="text" class="form-control input_types" placeholder="Select Time" name="p_service_time" id="p_service_time" @if(isset($product)) value="{{$product[0]['p_time']}}" @endif>
    </div>
</div>
@if(isset($product) && in_array('2',$serviceoptions))
<div class="col-md-6 time_service" style="display:block">
@else
<div class="col-md-6 time_service" style="display:none">
@endif
    <label class="control-label text-success" style="font-size:24px"> <i class="fa fa-repeat"></i> Repeat</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend custom_dropdown_width">
            @if(isset($product))
            <select class="form-control" data-style="form-control btn-secondary"  name="p_service_repeat_option" style="cursor: pointer;">
                <option value="1" style="cursor: pointer;" @if($product[0]['p_repeat_per_option'] == '1') {{'Selected'}} @endif>Daily</option>
                <option value="2" style="cursor: pointer;" @if($product[0]['p_repeat_per_option'] == '2') {{'Selected'}} @endif>Weekly</option>
                <option value="3" style="cursor: pointer;" @if($product[0]['p_repeat_per_option'] == '3') {{'Selected'}} @endif>Monthly</option>
                <option value="4" style="cursor: pointer;" @if($product[0]['p_repeat_per_option'] == '4') {{'Selected'}} @endif>Anually</option>
            </select>
            @else
            <select class="form-control" data-style="form-control btn-secondary"  name="p_service_repeat_option" style="cursor: pointer;">
                <option value="1" style="cursor: pointer;">Daily</option>
                <option value="2" style="cursor: pointer;">Weekly</option>
                <option value="3" style="cursor: pointer;">Monthly</option>
                <option value="4" style="cursor: pointer;">Anually</option>
            </select>
            @endif
        </div>
        <input type="number"  min="0" class="form-control input_types" name="p_service_repeat" placeholder="x times" @if(isset($product)) value="{{$product[0]['p_repeat']}}" @endif>
        <div class="input-group-append">
            <span class="input-group-text" style="cursor: pointer;">
                <div class="custom-control custom-checkbox" style="cursor: pointer;">
                    <input type="checkbox" class="custom-control-input" id="service_time_forever" placeholder="" name="p_service_forever" @if(isset($product) && $product[0]['p_repeat_forever']) {{"checked"}} @endif>
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
                    <ul class="list_service_hours">
                    @if(count($service_opening_hrs) > 0)

                        
                        @foreach($service_opening_hrs as $hrs)
                                @php
                                $filtered = array();
                                    if($hrs['break_time']>'0')
                                    {
                                        $filterstarttime = explode(',',$hrs['user_start_time']);
                                        $filterendtime = explode(',',$hrs['user_end_time']);
                                        for($j=0;$j<count($filterstarttime);$j++)
                                        {
                                        $filterstarttime[$j] = date("g:i a", strtotime($filterstarttime[$j]));
                                        $filterendtime[$j] = date("g:i a", strtotime($filterendtime[$j]));
                                        $filtered[$j] = $filterstarttime[$j].' - '.$filterendtime[$j];
                                        }
                                        $filteredfinal = implode(',',$filtered);
                                    }
                                    else
                                    {
                                        $filteredfinal = date("g:i a", strtotime($hrs['user_start_time'])).' - '.date("g:i a", strtotime($hrs['user_end_time']));
                                    }
                                @endphp
                            <p id="service_hour_id_{{$hrs['id']}}" style="font-size:15px">{{ucwords($hrs['user_day_name'])}} ({{$filteredfinal}})    <span class="text-danger" data-user_hr_id="{{$hrs['id']}}" onclick="removeServiceHour(this);"> <i class="fa fa-trash" aria-hidden="true" style="cursor: pointer;"></i> </span>  </p>
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
                        <select class="serviceweekday text-info form-control">
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
                        <select class="servicehours_start text-info form-control">
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
                        <select class="servicehours_end text-info form-control">
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
            <option value="0" @if(isset($product) && $product[0]['service_lead_time']=='') {{"selected"}} @endif>Select Service Lead Time</option>
            <option value="1" @if(isset($product) && $product[0]['service_lead_time']=='1') {{"selected"}} @endif>1 Day</option>
            <option value="2" @if(isset($product) && $product[0]['service_lead_time']=='2') {{"selected"}} @endif>2 Days</option>
            <option value="3" @if(isset($product) && $product[0]['service_lead_time']=='3') {{"selected"}} @endif>3 Days</option>
            <option value="4" @if(isset($product) && $product[0]['service_lead_time']=='4') {{"selected"}} @endif>4 Days</option>
            <option value="5" @if(isset($product) && $product[0]['service_lead_time']=='5') {{"selected"}} @endif>5 Days</option>
            <option value="6" @if(isset($product) && $product[0]['service_lead_time']=='6') {{"selected"}} @endif>6 Days</option>
            <option value="7" @if(isset($product) && $product[0]['service_lead_time']=='7') {{"selected"}} @endif>7 Days</option>
            <option value="8" @if(isset($product) && $product[0]['service_lead_time']=='8') {{"selected"}} @endif>8 Days</option>
            <option value="9" @if(isset($product) && $product[0]['service_lead_time']=='9') {{"selected"}} @endif>9 Days</option>
            <option value="10" @if(isset($product) && $product[0]['service_lead_time']=='10') {{"selected"}} @endif>10 Days</option>
            <option value="11" @if(isset($product) && $product[0]['service_lead_time']=='11') {{"selected"}} @endif>11 Days</option>
            <option value="12" @if(isset($product) && $product[0]['service_lead_time']=='12') {{"selected"}} @endif>12 Days</option>
            <option value="13" @if(isset($product) && $product[0]['service_lead_time']=='13') {{"selected"}} @endif>13 Days</option>
            <option value="14" @if(isset($product) && $product[0]['service_lead_time']=='14') {{"selected"}} @endif>14 Days</option>
            <option value="15" @if(isset($product) && $product[0]['service_lead_time']=='15') {{"selected"}} @endif>15 Days</option>
            <option value="16" @if(isset($product) && $product[0]['service_lead_time']=='16') {{"selected"}} @endif>16 Days</option>
            <option value="17" @if(isset($product) && $product[0]['service_lead_time']=='17') {{"selected"}} @endif>17 Days</option>
            <option value="18" @if(isset($product) && $product[0]['service_lead_time']=='18') {{"selected"}} @endif>18 Days</option>
            <option value="19" @if(isset($product) && $product[0]['service_lead_time']=='19') {{"selected"}} @endif>19 Days</option>
            <option value="20" @if(isset($product) && $product[0]['service_lead_time']=='20') {{"selected"}} @endif>20 Days</option>
            <option value="21" @if(isset($product) && $product[0]['service_lead_time']=='21') {{"selected"}} @endif>21 Days</option>
            <option value="22" @if(isset($product) && $product[0]['service_lead_time']=='22') {{"selected"}} @endif>22 Days</option>
            <option value="23" @if(isset($product) && $product[0]['service_lead_time']=='23') {{"selected"}} @endif>23 Days</option>
            <option value="24" @if(isset($product) && $product[0]['service_lead_time']=='24') {{"selected"}} @endif>24 Days</option>
            <option value="25" @if(isset($product) && $product[0]['service_lead_time']=='25') {{"selected"}} @endif>25 Days</option>
            <option value="26" @if(isset($product) && $product[0]['service_lead_time']=='26') {{"selected"}} @endif>26 Days</option>
            <option value="27" @if(isset($product) && $product[0]['service_lead_time']=='27') {{"selected"}} @endif>27 Days</option>
            <option value="28" @if(isset($product) && $product[0]['service_lead_time']=='28') {{"selected"}} @endif>28 Days</option>
            <option value="29" @if(isset($product) && $product[0]['service_lead_time']=='29') {{"selected"}} @endif>29 Days</option>
            <option value="30" @if(isset($product) && $product[0]['service_lead_time']=='30') {{"selected"}} @endif>30 Days</option>
        </select>
    </div>
</div>
    <style type="text/css">
        #map{
            height:300px;
        }
    </style>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ&libraries=places&callback=initAutocomplete"></script>
        <script src="{{asset('assets/js/customer/location_map.js')}}"></script>
    <script type="text/javascript">
        $("#p_service_time").bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
    </script>
