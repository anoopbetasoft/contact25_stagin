
    @csrf
    <input type="hidden" name="otp_val" value="">
    <input type="hidden" name="contact_no_hidden" value="">
    <div class="alert alert-danger auth_danger" style="display: none"></div>
    <div class="alert alert-success auth_success" style="display: none"></div>
    <div class="form-body">
       <!--  <h4>Delivery</h4> -->
       <label class="control-label text-success" style="font-size:24px">Delivery</label>
        <hr>
        <!-- name -->
        <div class="card-body"> 
                
                                        <div class="display_delivery">
                                          <!-- UK ONLY -->
                                          @if(Auth::user()->country=='United Kingdom')
                                          <div class="col-md-12 col-lg-12 col-xlg-12 col-sm-12 col-xs-12" style="padding-bottom: 12px;">
            <label class="control-label text-warning " style="font-size:24px; padding-bottom: 12px;"> 
              <i class="fas fa-shipping-fast"></i> 
              Inpost Next Day 
            </label>
            <p>Send your parcels (up to 20kg) tracked to UK destinations for only £2.99. Simply print a label from 1 click, pop it on your parcel and drop it off at a locker (<a href="https://inpost.co.uk/en/where-is-your-locker" target="blank">click here to find your nearest locker</a>). Your buyer will then be able to track the item. Your delivery cost will be added to the selling cost, so your buyer pays for delivery.</p>
                        
            <select class="form-control form-control-line update_inpost_del" id="inpost_status" onchange="updateinpost()">
              <option value="0" @if(Auth::user()->inpost_status=='0') {{"Selected"}} @endif>Off</option>
              <option value="1" @if(Auth::user()->inpost_status=='1') {{"Selected"}} @endif>On</option>                        
                        </select>
          </div>
          <hr>
          @endif
          <!-- UK ONLY -->
      
      <div class="form-horizontal form-material" style="padding-top: 12px; padding-left: 5px;">
<label class="control-label text-success" style="font-size:24px; padding-top:12px;"> 
                  <i class="fas fa-truck"></i> 
                  Add Delivery Options
                </label>
        <!-- <div class="row" style="padding-left: 8px; padding-top: 12px"> -->
          @if(count($delivery)>0)
          <div id="deliveries" style="display: block;">
          @else
          <div id="deliveries" style="display: none;">
          @endif
          <!-- Column -->
          <div class="row">
                                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>                
                                                 <p class="text-purple font-medium">Name</p>
                  </div>
                  
                  <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-12" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>                
                                                 <p class="text-primary font-medium">Link</p>
                                      </div> 
                  <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>                
                                                 <p class="text-purple font-medium">Price</p>
                  </div>
                    
                    <div class="col-md-12 col-lg-2 col-xlg-2 col-sm-12 col-xs-12">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                        </div>
                                    </div>
                                  </div>
                        
        
           @foreach($delivery as $deliveries)
           <div id="delivery{{$deliveries->id}}">
            <div class="row">
        <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6">
                                        <div class="card" style="margin-bottom: 2px;"></div>              
                                                 <p class="text-muted font-medium">{{$deliveries->delivery_provider}}</p>
                    </div>
                    
                    <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-12">
                                        <div class="card" style="margin-bottom: 2px;"></div>              
                                                 <p class="text-muted font-medium">{{$deliveries->tracking_url}}</p>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6">
                                        <div class="card" style="margin-bottom: 2px;"></div>              
                                                 <p class="text-muted font-medium">
                                                  @if($deliveries->price=='')
                                                  {{'Free'}}
                                                  @else
                                                  {{$deliveries->price}}
                                                  @endif
                                                </p>
                    </div>
                    
                    
                    <div class="col-md-3 col-lg-3 col-xlg-3 col-sm-6 col-xs-3">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                            <button type="button" id="deletedelivery{{$deliveries->id}}" class="btn btn-light remove_courier" data-courier_id="11" onclick="deletedelivery({{$deliveries->id}});">
                                        <i class="ti-close" aria-hidden="true"></i>
                                      </button>
                                        </div>
                                    </div>
                                  </div>
                                </div>
          @endforeach
        </div>
<!-- </div> -->
                                    
    
                  <form id="delivery" name="delivery" enctype="multipart/form-data">
                    @csrf
                  <div class="form-group">
                    <label class="col-md-12">Courier / Delivery Provider</label>
                    <div class="col-md-12">
                      <input type="text" id="delivery_provider" name="delivery_provider" placeholder="DHL / TNT / Yodel / Royal Mail" class="form-control form-control-line">
                    </div>
                  </div>
        
                  <div class="form-group">
                    <label for="example-email" class="col-md-12">Tracking URL</label>
                    <div class="col-md-12">
                      <input type="text" id="tracking_url" name="tracking_url" placeholder="https://courier-tracking-location" class="form-control form-control-line" name="example-email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="example-email" class="col-md-12">Price</label>
                    <div class="col-md-12">
                      <input type="text" id="price" name="price" placeholder="price" class="form-control form-control-line">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button id="deliveryclick" class="btn btn-danger add_courier" type="button"><i class="fas fa-truck"></i>  Add Courier</button>
                    </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>