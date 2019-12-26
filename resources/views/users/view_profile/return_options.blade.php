@csrf
<input type="hidden" name="otp_val" value="">
<input type="hidden" name="contact_no_hidden" value="">
<div class="alert alert-danger auth_danger" style="display: none"></div>
<div class="alert alert-success auth_success" style="display: none"></div>
<div class="form-body">
    <!-- <h4>Communication</h4> -->
    <label class="control-label text-success" style="font-size:24px">Return Options</label>
    <hr>
    <!-- name -->
    <div class="card-body">
        <div class="checkbox checkbox-info">
            <input id="refundrequest" type="checkbox"
                   class="fxhdr" @if(Auth::user()->refundrequest_status=='1') {{"checked"}} @endif>
            <label for="refundrequest" style="color: #03a9f3;cursor: pointer"> Automatically refund return request for items valued under {{$currency_symbol[0]->symbol}} </label>&nbsp;<input type="text" class="form-control" name="return_item_limit" id="refundrequestvalue" style="width:10%;" value="{{Auth::user()->refundrequest_value}}">
        </div>
        <br>
        <div class="checkbox checkbox-info">
            <input id="refundrequestdamage" type="checkbox"
                   class="fxhdr" @if(Auth::user()->refundrequestdamage_status=='1') {{"checked"}} @endif>
            <label for="refundrequestdamage" style="color: #03a9f3;cursor: pointer"> Automatically refund return request for <span class="color-danger">damaged</span> items valued under {{$currency_symbol[0]->symbol}}</label>&nbsp;<input type="text" class="form-control" name="return_item_limit" id="refundrequestdamagevalue" style="width: 10%;" value="{{Auth::user()->refundrequestdamage_value}}">
        </div>
        <br>
        <input type="button" class="btn btn-info" value="Update" onclick="updatepolicy();">



    </div>

</div>