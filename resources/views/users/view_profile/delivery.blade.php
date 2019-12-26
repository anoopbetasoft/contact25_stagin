
    @csrf
    <input type="hidden" name="otp_val" value="">
    <input type="hidden" name="contact_no_hidden" value="">
    <div class="alert alert-danger auth_danger" style="display: none"></div>
    <div class="alert alert-success auth_success" style="display: none"></div>
    <div class="form-body">
       <!--  <h4>Delivery</h4> -->

        <!-- name -->
        <div class="card-body"> 
                
                                        <div class="display_delivery">
                                          <!-- UK ONLY -->
                                          @if(Auth::user()->country=='United Kingdom' && Auth::user()->currency_code=='GBP')
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
                                            <label class="control-label text-success" style="font-size:24px">Delivery Providers</label>

          <!-- UK ONLY -->
      
      <div class="form-horizontal form-material" style="padding-top: 12px; padding-left: 5px;">
{{--<label class="control-label text-success" style="font-size:24px; padding-top:12px;">
                  <i class="fas fa-truck"></i> 
                  Add Delivery Options
                </label>--}}
        <!-- <div class="row" style="padding-left: 8px; padding-top: 12px"> -->
          @if(count($delivery)>0)
          <div style="display: block;">
          @else
          <div style="display: none;">
          @endif
          <!-- Column -->
          <div class="row">
		  <div class="table-responsive">
		     <table width="100%" class="table ">
			   <thead>
			    <tr>
			     <th class="text-purple">Name</th>
			     <th class="text-purple">Link</th>
			     <th class="text-purple">Price</th>
			     <th class="text-purple">Description</th>
                    <th class="text-purple">Status</th>
			     <th class="text-purple" >Edit</th>
			    {{-- <th class="text-purple">Remove</th>--}}
				 </tr>
			   </thead>
			    <tbody id="deliveries">
				@foreach($delivery as $deliveries)
				  <tr id="delivery{{$deliveries->id}}">
				    <td id="tbl_delivery_provider{{$deliveries->id}}">{{$deliveries->delivery_provider}}</td>
				    <td id="tbl_tracking_url{{$deliveries->id}}">{{$deliveries->tracking_url}}</td>
				    <td> <p class="text-muted font-medium price" id="tbl_price{{$deliveries->id}}">
                                                  @if($deliveries->price=='')
                                                  {{'Free'}}
                                                  @else
                                                         @php
                                                         $price = $deliveries->price;
                                                            $price = floatval($price);
                                                             $price = number_format($price,$decimal_place[0]['decimal_places']);
                                                            $price = str_replace('.00','',$price);
                                                         @endphp
                                                  {{$price}}
                                                  @endif
                                                </p></td>
				    <td><p class="text-muted font-medium description" id="tbl_description{{$deliveries->id}}">
                                                  {{$deliveries->description}}
                                                </p>
                    </td>
                      <td><p class="text-muted font-medium description" id="tbl_status{{$deliveries->id}}" @if($deliveries->status=='1') style="color:#00c292!important;" @else style="color:#D3D3D3!important;" @endif>@if($deliveries->status=='1')Live @else Archive @endif</p></td>
				    <td>   <button type="button" class="btn btn-light remove_courier" data-toggle="modal" data-target="#deliverymodal{{$deliveries->id}}">
                                        <i class="ti-pencil" aria-hidden="true"></i>
                                      </button></td>
				   {{-- <td> <button type="button" id="deletedelivery{{$deliveries->id}}" class="btn btn-light remove_courier" data-courier_id="11" onclick="deletedelivery({{$deliveries->id}});">
                                        <i class="ti-close" aria-hidden="true"></i>
                                      </button></td>--}}
				  </tr>
				  @endforeach
				</tbody>
			 </table>
			 </div>
		  </div>
		  
		  
		  
		  
		  
                                <!--    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>                
                                                 <p class="text-purple font-medium">Name</p>
                  </div>
                  
                  <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-2" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>                
                                                 <p class="text-primary font-medium">Link</p>
                                      </div> 
                  <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-2" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>                
                                                 <p class="text-purple font-medium">Price</p>
                  </div>
                  <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-2" style="width: auto; min-width: 0;">
                                        <div class="card" style="margin-bottom: 2px;"></div>                
                                                 <p class="text-purple font-medium">Delivery Description</p>
                  </div>
                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-2 col-xs-2">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                        <p class="text-purple font-medium">Edit</p></div>
                                    </div>
                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-2 col-xs-2">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                        <p class="text-purple font-medium">Remove</p></div>
                                    </div>
                                  </div>
                        -->
        <!--
           @foreach($delivery as $deliveries)
           <div id="delivery{{$deliveries->id}}">
            <div class="row">
        <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6">
                                        <div class="card" style="margin-bottom: 2px;"></div>              
                                                 <p class="text-muted font-medium delivery_provider" id="tbl_delivery_provider{{$deliveries->id}}">{{$deliveries->delivery_provider}}</p>
                    </div>
                    
                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6">
                                        <div class="card" style="margin-bottom: 2px;"></div>              
                                                 <p class="text-muted font-medium tracking_url" id="tbl_tracking_url{{$deliveries->tracking_url}}">{{$deliveries->tracking_url}}</p>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6">
                                        <div class="card" style="margin-bottom: 2px;"></div>              
                                                 <p class="text-muted font-medium price" id="tbl_price{{$deliveries->id}}">
                                                  @if($deliveries->price=='')
                                                  {{'Free'}}
                                                  @else
                                                         @php
                                                              //$deliveries->price = floatval($deliveries->price);
                                                             // $deliveries->price = number_format($deliveries->price,$decimal_place[0]['decimal_places']);
                                                             // $deliveries->price = str_replace('.00','',$deliveries->price);
                                                         @endphp
                                                  {{$deliveries->price}}
                                                  @endif
                                                </p>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-3 col-xs-6">
                                        <div class="card" style="margin-bottom: 2px;"></div>              
                                                 <p class="text-muted font-medium description" id="tbl_description{{$deliveries->id}}">
                                                  {{$deliveries->description}}
                                                </p>
                    </div>
                    
                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-4 col-xs-2">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                            <button type="button" class="btn btn-light remove_courier" data-toggle="modal" data-target="#deliverymodal{{$deliveries->id}}">
                                        <i class="ti-pencil" aria-hidden="true"></i>
                                      </button>
                                        </div>
                                    </div>

                    <div class="col-md-2 col-lg-2 col-xlg-2 col-sm-4 col-xs-2">
                                      <div class="card padding-top:0px; margin-bottom: 12px;">
                                            <button type="button" id="deletedelivery{{$deliveries->id}}" class="btn btn-light remove_courier" data-courier_id="11" onclick="deletedelivery({{$deliveries->id}});">
                                        <i class="ti-close" aria-hidden="true"></i>
                                      </button>
                                        </div>
                                    </div>
                                  </div>
                                </div>
          @endforeach
		  -->
        </div>
<!-- </div> -->
                                    
    
                  <form id="delivery" name="delivery" enctype="multipart/form-data">
                    @csrf
                      <label class="control-label text-success" style="font-size:24px; padding-top:12px;">
                          <i class="fas fa-truck"></i>
                          Add Courier
                      </label>
                      <br>
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
                    <label for="example-email" class="col-md-12">Description</label>{{--<p style="margin-left: 9px;">Send your parcels (sizes up to 38cm h x 38cm w x 64cm d) tracked to UK destinations for only £2.99. Simply print a label from 1 click, pop it on your parcel and drop it off at a locker (click here to find your nearest locker). Your buyer will then be able to track the item. Your delivery cost will be added to the selling cost, so your buyer pays for delivery.</p>--}}<br>
                    <div class="col-md-12">
                      <textarea id="description" name="description" placeholder="Description" class="form-control form-control-line deliverydescription"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button id="deliveryclick" class="btn btn-danger add_courier" type="button"><i class="fas fa-truck"></i>  Add Courier</button>
                    </div>
                  </div>
                </form>
                @if(Auth::user()->country=='United Kingdom' && count($delivery)>'0')
                <div class="form-group">
                   <div class="checkbox checkbox-info">
                  <input id="delivery_option" type="checkbox" class="fxhdr" @if(Auth::user()->delivery_option=='1') checked="" @endif>
                  <label for="delivery_option" style="color: #03a9f3;cursor: pointer;" onclick="updatedeliveryoption();"> Allow buyer to choose any of the above delivery options.</label>
                </div>
                  </div>
                @elseif(Auth::user()->country!='United Kingdom' && count($delivery)>'1')
                <div class="form-group">
                   <div class="checkbox checkbox-info">
                  <input id="delivery_option" type="checkbox" class="fxhdr" @if(Auth::user()->delivery_option=='1') checked="" @endif>
                  <label for="delivery_option" style="color: #03a9f3;cursor: pointer;" onclick="updatedeliveryoption();"> Allow buyer to choose any of the above delivery options.</label>
                </div>
                  </div>
                @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div id="popupdetails">
      @foreach($delivery as $deliverys)
<div class="modal fade" id="deliverymodal{{$deliverys->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         {{-- <form name="editdelivery">--}}
            @csrf
              <div class="form-group">
                    <label class="col-md-12" for="delivery_provider{{$deliverys->id}}">Courier / Delivery Provider</label>
                    <div class="col-md-12">
                      <input type="text" id="delivery_provider{{$deliverys->id}}" name="delivery_provider" placeholder="DHL / TNT / Yodel / Royal Mail" class="form-control" value="{{$deliverys->delivery_provider}}">
                    </div>
                  </div>
        
                  <div class="form-group">
                    <label for="tracking_url{{$deliverys->id}}" class="col-md-12">Tracking URL</label>
                    <div class="col-md-12">
                      <input type="text" id="tracking_url{{$deliverys->id}}" name="tracking_url" placeholder="https://courier-tracking-location" class="form-control" name="example-email" value="{{$deliverys->tracking_url}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="price{{$deliverys->id}}" class="col-md-12">Price</label>
                    <div class="col-md-12">
                      <input type="text" id="price{{$deliverys->id}}" name="price" placeholder="price" class="form-control" value="{{$deliverys->price}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="description{{$deliverys->id}}" class="col-md-12">Description</label><p>
                    <div class="col-md-12">
                      <textarea rows="10" cols="200" id="description{{$deliverys->id}}" name="description" placeholder="Description" class="form-control form-control-line deliverydescription" value="{{$deliverys->description}}">{{$deliverys->description}}</textarea>
                    </div>
                  </div>
          <div class="form-group">
              <label for="status{{$deliverys->id}}" class="col-md-12">Status:</label><p>
              <div class="col-md-12">
                  <select class="form-control" name="status" id="status{{$deliverys->id}}">
                      <option value="0" @if($deliverys->status=='0') selected @endif>Archive</option>
                      <option value="1" @if($deliverys->status=='1') selected @endif>Live</option>
                  </select>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updatedelivery({{$deliverys->id}});">Save changes</button>
      </div>
    {{--</form>--}}
    </div>
  </div>
</div>
@endforeach
</div>