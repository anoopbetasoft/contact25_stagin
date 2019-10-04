
    @csrf
    <input type="hidden" name="otp_val" value="">
    <input type="hidden" name="contact_no_hidden" value="">
    <div class="alert alert-danger auth_danger" style="display: none"></div>
    <div class="alert alert-success auth_success" style="display: none"></div>
    <div class="form-body">
        <!-- <h4>Communication</h4> -->
        <label class="control-label text-success" style="font-size:24px">Communication</label>
        <hr>
        <!-- name -->
        <div class="card-body">
                                        
                                                     
                <div class="checkbox checkbox-info">
                  <input id="checkbox1" type="checkbox" class="fxhdr" @if(Auth::user()->order_status=='1') {{"checked"}} @endif>
                  <label for="checkbox1" style="color: #03a9f3" onclick="updateorder();"> Receive an order </label>
                </div>
                  
           
                <div class="checkbox checkbox-warning">
                  <input id="checkbox2" type="checkbox" class="fxsdr" @if(Auth::user()->message_status=='1') {{"checked"}} @endif>
                  <label for="checkbox2" style="color:#fec107" onclick="updatemessage()"> Receive a message </label>
                </div>
             
                <div class="checkbox checkbox-success">
                  <input id="checkbox3" type="checkbox" class="open-close" @if(Auth::user()->collect_status=='1') {{"checked"}} @endif>
                  <label for="checkbox3" style="color:#00c292" onclick="updatecollectreminder()"> Reminder (before setting off to collect something) </label>
                </div>
             
                  <div class="checkbox checkbox-info">
                  <input id="checkbox4" type="checkbox" class="fxhdr" @if(Auth::user()->collection_status=='1') {{"checked"}} @endif>
                  <label for="checkbox4" style="color: #03a9f3" onclick="updatecollectionreminder()"> Reminder (before a collection)</label>
                </div>  
                <div class="checkbox checkbox-info">
                  <input id="checkbox5" type="checkbox" class="fxhdr" @if(Auth::user()->friend_status=='1') {{"checked"}} @endif>
                  <label for="checkbox5" style="color: #03a9f3" onclick="updatefriendstatus()"> Friend Request</label>
                </div>    

                                    
                                    </div>
     
    </div>