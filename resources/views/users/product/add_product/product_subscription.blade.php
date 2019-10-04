<div class="col-md-3">
    <div class="form-group">
        <label class="control-label text-info " style="font-size:24px;"><i class="fa fa-tag"></i> Sell to</label>
        @include('users.product.add_product.product_sell_to')
    </div>
</div>

<div class="col-md-3">
    <div class="form-group subs_member_div">
        <label class="control-label text-purple" style="font-size:24px"> <i class="fa fa-repeat"></i> Subscription</label><Br/>

        @foreach($p_subscription_option  as $series=> $otpns)
            @if($otpns['id'] ==1)
                @php $class_subs = 'danger';@endphp
            @elseif($otpns['id'] ==2)
                @php $class_subs = 'info';@endphp
            @elseif($otpns['id'] ==3)
                @php $class_subs = 'primary';@endphp
            @else
                @php $class_subs = 'success';@endphp
            @endif
            <div class="checkbox checkbox-{{$class_subs}} checkbox-circle sell-wrapper checkbox_subslist" >
                <input id="checkbox_subs_{{$otpns['id']}}"  data-value="{{$otpns['id']}}" type="checkbox" style="cursor:pointer;" onclick="subscription_type_selected(this);" data-subs_type = "{{$otpns['type']}}_subs" data-selected_option="{{$otpns['type']}}" class="" @if($otpns['id']==1) name="location_select" @endif>
                <label for="checkbox_subs_{{$otpns['id']}}" class="text-{{$class_subs}}" style="cursor:pointer;">{{ucwords($otpns['display_text'])}}</label>
            </div>
        @endforeach
        <input type="hidden" name="p_subscription_option" value="">
    </div>
</div>

<!-- group -->
<div class="col-md-6">
    <label class="control-label selling_product_price text-purple"><i class="fa fa-repeat"></i> Subscription Price</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <button class="btn btn-purple" type="button">{{$currency_symbol[0]->symbol}}</button>
        </div>
        <input type="number" min="0" step="any" class="form-control" placeholder="Price" name="p_sell_price">
        <div class="input-group-prepend custom_dropdown_width">
             <select class="form-control" data-style="form-control btn-secondary"  name="p_sub_per_optn" style="cursor: pointer;">
                <option value="1">Per Day</option>
                <option value="2">Per Week</option>
                <option value="3">Per Month</option>
                <option value="4">Per Year</option>
            </select>
        </div>
    </div>
    <div class="form-group group_subs" style="display: none">
        <label class="control-label text-purple"><i class="fas fa-users"></i> Group Session</label>
        <input type="number" min="0" class="form-control input_types" placeholder="Lending Price / Time"  name="p_subs_group">
    </div>
</div>

<!-- location -->



<div class="col-md-6 location_subs" style="display: none">
    <div class="form-group">
        <label class="control-label text-purple" style="font-size:24px"> <i class="ti-location-pin"></i> Location</label>
        <!--<input type="text" class="form-control input_types" placeholder="Location" name='p_subs_location'>-->
        <input type="hidden" id="location_coord" name="p_subs_location" value="">
        <input id="pac-input" class="form-control input_types" placeholder="Location" type="text" >
        <div id="map"></div>
    </div>
</div>

<div class="col-md-6 location_subs" style="display:none">
    <label class="control-label text-purple" style="font-size:24px"> <i class="fas fa-taxi"></i> Radius</label>
    <div class="input-group mb-3">
        <input type="number" min="0" step="any" class="form-control input_types" placeholder="miles / km you will travel to carry out this service"   name="p_subs_radius">
        <div class="input-group-prepend custom_dropdown_width">
            
            <select class="form-control" data-style="form-control btn-secondary" name="p_subs_radius_options" style="cursor: pointer;">
                <option value="0" selected="selected">No Radius</option>
                <option value="1">Distance (Km)</option>
                <option value="2">Distance (Miles)</option>
            </select>
        </div>
    </div>
</div>

<!-- time -->
<div class="col-md-6 time_subs" style="display: none">
    <div class="form-group" >
        <label class="control-label text-purple" style="font-size:24px"> <i class="fa fa-user"></i> Start Time</label>
       <!--  <input type="text" class="form-control input_types" placeholder="select Time" name="p_subs_time" id="p_subs_time" onclick="initialize_datepicker(this)"> -->
         <input type="text" class="form-control input_types" placeholder="select Time" name="p_subs_time" id="p_subs_time">
    </div>
</div>


<div class="col-md-6 time_subs" style="display:none">
    <label class="control-label text-purple" style="font-size:24px"> <i class="fa fa-repeat"></i> Repeat</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <select class="form-control" data-style="form-control btn-secondary" name="p_subs_repeat_option" style="cursor: pointer;">
                <option value="1">Daily</option>
                <option value="2">Weekly</option>
                <option value="3">Monthly</option>
                <option value="4">Anually</option>
            </select>
        </div>
        <input type="number" min="0" class="form-control input_types" name="p_subs_repeat" placeholder="x times">
        <div class="input-group-append">
            <span class="input-group-text" style="cursor: pointer;">
                <div class="custom-control custom-checkbox" style="cursor: pointer;">
                    <input type="checkbox" class="custom-control-input" id="subs_time_forever" placeholder="" name="p_subs_forever">
                    <label class="custom-control-label" for="subs_time_forever" style="cursor: pointer;">Forever</label>
                </div>
            </span>
           
        </div>
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
        $("#p_subs_time").bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
    </script>
