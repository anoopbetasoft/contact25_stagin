<style>
    label {
        display: inline-block;
        margin-bottom: -0.5rem;
    }
</style>
<div class="form-body">

    <label class="control-label text-success" style="font-size:24px"> Profile</label>
    <hr>
    <!-- name -->
    <form id="update_profile" enctype="multipart/form-data">
        <div class="alert alert-danger  profile_danger" style="display:none"></div>
        <div class="alert alert-success profile_success" style="display:none"></div>
    @csrf
    <!-- email and name-->
        <div class="row">
            <div class="col-md-4">
                <form>
              <div class="form-group">
                  <label class="control-label">Name</label>
                    <input class="form-control" name="name" type="text" placeholder="Jason"
                           value="{{$uDetails[0]['name']}}" required="required">
                    <span id="name"></span>
             </div>
            </div>
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input class="form-control" name="email" type="email" placeholder="jason@clark.com"
                           value="{{$uDetails[0]['email']}}">
                    <span id="email"></span>
                </div>
            </div>

            <!--/span-->
        <!-- <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">
                        Contact Number
                        @if($uDetails[0]['contact_verify_status'] == 0)
            <i class="fa fa-exclamation-triangle no_verified" style="color:red" title="Verification Pending"></i>
@else
            <i class="fa fa-check verified" style="color:green" title="Contact Verified"></i>
@endif
                </label>
                <input class="form-control" name="contact" type="number" name="contact_no" id="phone"  placeholder="telephone number" value="{{$uDetails[0]['contact_no']}}">
                    
                </div>
            </div> -->
        </div>
        <!-- address fields-->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Street Address</label>
                    <input class="form-control" name="st_address1" type="text" placeholder="Street Address 1"
                           value="{{$uDetails[0]['street_address1']}}" required="required">
                </div>
                <div class="form-group">
                    <label class="control-label">Street Address 2</label>
                    <input class="form-control" name="st_address2" type="text" placeholder="Street Address 2"
                           value="{{$uDetails[0]['street_address2']}}">
                    <!-- <span id="name"></span> -->
                </div>
            </div>
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">City</label>
                    <input class="form-control" name="city" type="text" placeholder="City"
                           value="{{$uDetails[0]['city']}}">
                    <span id="city"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">County/State</label>
                    <input class="form-control" name="state" type="text" placeholder="County/State"
                           value="{{$uDetails[0]['state']}}" required="required">
                    <span id="state"></span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Postcode/Zip</label>
                    <input class="form-control" name="pincode" type="text" placeholder="Postcode/Zip"
                           value="{{$uDetails[0]['pincode']}}" required="required">
                    <span id="pincode"></span>
                </div>
            </div>


            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Country</label><Br/>
                    @if($pendingordercount>0 || $pendingpurchasecount > 0)
                        <input type="hidden" name="country2" value="{{$uDetails[0]['country']}}">
                    @else
                        <input type="hidden" name="country2" value="">
                    @endif
                    <select name="country" class="selectpicker" data-style="form-control btn-secondary"
                            required="required" <?php if ($pendingordercount > 0 || $pendingpurchasecount > 0) {
                        echo "disabled";
                    } ?> id="countrylist">
                        @foreach($country as $c)
                            <option value="{{$c['c_name']}}"
                                    @if($c["c_name"] == $uDetails[0]['country']) selected="selected" @endif>{{$c['c_name']}}</option>
                        @endforeach
                    </select>
                    <span id="country"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Currency:</label><Br/>
                    @if($pendingordercount>0 || $walletcheck[0]['wallet']!='0' || $creditcheckcount>0)
                        <input type="hidden" name="currency_code" value="{{$uDetails[0]['currency_code']}}">
                    @endif
                    <select name="currency_code" class="selectpicker" data-style="form-control btn-secondary"
                            required="required" <?php if ($pendingordercount > 0 || $walletcheck[0]['wallet']!='0' || $creditcheckcount>0) {
                        echo "disabled";
                    } ?>>
                        @foreach($currencies as $c)
                            <option value="{{$c['code']}}"
                                    @if($c["code"] == $uDetails[0]['currency_code']) selected="selected" @endif>{{$c['code']}}
                                [{{$c['symbol']}}]
                            </option>
                        @endforeach
                    </select>
                    <span id="country"></span>
                </div>
            </div>
            <!-- location -->
            <div class="col-lg-12">
                <?php
                $regions = array(
                    'Africa' => DateTimeZone::AFRICA,
                    'America' => DateTimeZone::AMERICA,
                    'Antarctica' => DateTimeZone::ANTARCTICA,
                    'Asia' => DateTimeZone::ASIA,
                    'Atlantic' => DateTimeZone::ATLANTIC,
                    'Europe' => DateTimeZone::EUROPE,
                    'Indian' => DateTimeZone::INDIAN,
                    'Pacific' => DateTimeZone::PACIFIC
                );
                $timezones = array();
                foreach ($regions as $name => $mask) {
                    $zones = DateTimeZone::listIdentifiers($mask);
                    foreach ($zones as $timezone) {
                        // Lets sample the time there right now
                        $time = new DateTime(NULL, new DateTimeZone($timezone));
                        // Us dumb Americans can't handle millitary time
                        $ampm = $time->format('H') > 12 ? ' (' . $time->format('g:i a') . ')' : '';
                        // Remove region name and add a sample time
                        $timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
                    }
                }
                // View
                echo '<label class="control-label text-success" style="font-size:24px"> <i class="ti-time" style="color:#00c292"></i> Timezone</label><br><p>Select your timezone. Knowing what time it is where you are is very important when buying and selling products and services.</p>';
                echo '<select id="timezone" name="timezone" class="form-control">';
                foreach ($timezones as $region => $list) {
                    echo '<optgroup label="' . $region . '">' . "\n";
                    foreach ($list as $timezone => $name) {
                        if ($uDetails[0]['timezone'] == $timezone) {
                            echo '<option name="' . $timezone . '" value="' . $timezone . '" selected>' . $name . '</option>' . "\n";
                        } else {
                            if ($timezone == 'Europe/London' && $uDetails[0]['timezone'] == '') {
                                echo '<option name="' . $timezone . '" value="' . $timezone . '" selected value="DEFAULT">' . $name . '</option>' . "\n";
                            } else {
                                if ($timezone == $uDetails[0]['timezone'])   // If this timezone is in database
                                {
                                    echo '<option name="' . $timezone . '" value="' . $timezone . '" selected>' . $name . '</option>' . "\n";
                                } else {
                                    echo '<option name="' . $timezone . '" value="' . $timezone . '">' . $name . '</option>' . "\n";
                                }
                            }

                        }
                    }
                    echo '<optgroup>' . "\n";
                }
                echo '</select>';
                ?>
            </div>
            <div class="col-md-12 location_service">
                <div class="form-group">
                    <br>
                    <label class="control-label text-success" style="font-size:24px"> <i class="ti-location-pin"
                                                                                         style="color:#00c292"></i>
                        Location</label><br>
                    <p>Search for your home location (usually using your postal address). We need this so that we can
                        send buyers directions to your home location to collect things you sell.</p>
                    <!-- <input type="text" class="form-control input_types" placeholder="Location" name="p_service_location">-->
                    @if($uDetails[0]['lat']!='')
                        <input type="hidden" id="lat" value="{{$uDetails[0]['lat']}}">
                        <input type="hidden" id="lng" value="{{$uDetails[0]['lng']}}">
                    @endif
                    <input type="hidden" name="latlng" id="latlng"
                           value="({{$uDetails[0]['lat']}},{{$uDetails[0]['lng']}})">
                    <input type="hidden" id="location_coord" name="p_service_location" value="">
                    <input id="pac-input" class="form-control input_types" name="location" placeholder="Location"
                           type="text" value="{{$uDetails[0]['location']}}">
                    <div id="map"></div>
                </div>
            </div>
        </div>


        <!--profile picture-->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="input-file-now-custom-1">Your Avatar</label>
                    <div class="dropify-errors-container">
                        @if(empty($uDetails[0]['avatar']))
                            <input type="file" name="avatar" id="input-file-now-custom-1" class="dropify"
                                   data-default-file=""/>
                        @else
                            @php $img_name = $uDetails[0]['avatar']; @endphp
                            <input type="hidden" name="avatar_type" value="{{$uDetails[0]['avatar_type']}}">

                            @if($uDetails[0]['avatar_type'] == 1)
                                <input type="file" name="avatar" id="input-file-now-custom-1" class="dropify"
                                       data-default-file='{{asset("uploads/avatar/$img_name")}}' width="100%">
                            @elseif($uDetails[0]['avatar_type'] == 2)
                                <input type="file" name="avatar" id="input-file-now-custom-1" class="dropify"
                                       data-default-file='{{$img_name}}' width="100%">
                                <!-- http://www.tompetty.com/sites/g/files/g2000007521/f/sample001.jpg -->
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
        <!-- Google Map Location -->
        <!-- <div class="row">
      <div class="col-md-6 location_service">
    <div class="form-group">
        <label class="control-label text-success" style="font-size:24px"> <i class="ti-location-pin" style="color:#00c292"></i> Location</label>
       
        <input type="hidden" id="location" name="p_service_location" value="">
        <input id="location_name" class="form-control input_types" placeholder="Location" type="text" >
        <div id="map"></div>
    </div>
</div>
</div> -->
        <div class="form-actions">
            <button type="submit" class="btn btn-info">Update</button>
        </div>

    </form>
</div>

<script src="{{asset('assets/js/customer/google_location.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ&libraries=places&callback=initAutocomplete"></script>


  