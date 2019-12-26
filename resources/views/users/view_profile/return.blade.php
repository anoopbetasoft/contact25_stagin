<style type="text/css">
  .return_address
  {
    resize: vertical;
  }
</style>
    @csrf
    <input type="hidden" name="otp_val" value="">
    <input type="hidden" name="contact_no_hidden" value="">
    <div class="alert alert-danger auth_danger" style="display: none"></div>
    <div class="alert alert-success auth_success" style="display: none"></div>
    <div class="form-body">
       <!--  <h4>Delivery</h4> -->
       <label class="control-label text-success" style="font-size:24px">Returns</label>
        <hr>
        <!-- name -->
        <div class="card-body"> 
                
                                        <div class="display_delivery">
                                          <!-- UK ONLY -->
                                          @if(Auth::user()->country=='United Kingdom' && Auth::user()->currency_code=='GBP')
                                          <div class="col-md-12 col-lg-12 col-xlg-12 col-sm-12 col-xs-12" style="padding-bottom: 12px;">
            <label class="control-label text-warning " style="font-size:24px; padding-bottom: 12px;"> 
              <i class="fas fa-shipping-fast"></i> 
              Inpost Returns 
            </label>
            <p>Return your parcels (up to 20kg) tracked to UK destinations for only £2.99. Simply print a label from 1 click, pop it on your parcel and drop it off at a locker (<a href="https://inpost.co.uk/en/where-is-your-locker" target="blank">click here to find your nearest locker</a>). Your buyer will then be able to track the returns item. Your delivery cost will be added to your costs so you pay for the return.</p>
            
                        
            <select class="form-control form-control-line update_inpost_del" id="inpost_return" onchange="updateinpostreturn()">
              <option value="0" @if(Auth::user()->inpost_return=='0') {{"Selected"}} @endif>Off</option>
              <option value="1" @if(Auth::user()->inpost_return=='1') {{"Selected"}} @endif>On</option>                        
                        </select>
          </div>
          <hr>
          @endif
          <!-- UK ONLY -->
      
      <div class="form-horizontal form-material" style="padding-top: 12px; padding-left: 5px;">
<label class="control-label text-success" style="font-size:24px; padding-top:12px;"> 
                  <i class="fas fa-truck"></i> 
                  Return Options
                </label>
        <!-- <div class="row" style="padding-left: 8px; padding-top: 12px"> -->
          
<!-- </div> -->
                                    
    
                  <form id="return" name="return" enctype="multipart/form-data">
                    @csrf
                  <div class="form-group">
                    <label for="example-email" class="col-md-12">Return Address(If Different To Your Home Address)</label>
                    <div class="col-md-12">
                      <textarea id="return_address" name="return_address" placeholder="Return Address" class="form-control form-control-line return_address">{{Auth::user()->return_address}}</textarea>
                    </div>
                  </div>
        
                  <div class="form-group">
                   <div class="checkbox checkbox-info">
                  <input id="checkbox1" type="checkbox" class="fxhdr" @if(Auth::user()->return_label_status=='1') checked="" @endif>
                  <label for="checkbox1" style="color: #03a9f3"> I Will Provide Return Labels</label>
                </div>
                  </div>
                 
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button id="deliveryclick" class="btn btn-danger add_courier" type="button" onclick="returnstatus()"><i class="fas fa-truck"></i>  Save</button>
                    </div>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>