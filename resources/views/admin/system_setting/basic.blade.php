<form id="update_setting" method="post">
    <div class="row">


        <div class="col-lg-12">
            <form action="" method="post">
                @csrf
                @if ( Session::has('message') )
                <div class="alert alert-success" role="alert">
                    {{ Session::get('message') }}
                </div>
                @endif
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="name">Inpost Delivery Amount</label>
                    <input class="c-input" name="inpost_amount" type="text" id="inpost_amount" placeholder="Inpost Amount" value="{{$systemsettting[0]['inpost_amount']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Credit Clearing Period (In Days)</label>
                    <input class="c-input" name="clear_credit_period" id="clear_credit_period" type="text" placeholder="Credit Clearing Period" value="{{$systemsettting[0]['clear_credit_period']}}">
                </div>
            <div class="c-field u-mb-small">
                <label class="c-field__label" for="email">Credit Discount (In Percentage)</label>
                <input class="c-input" name="credit_discount" id="credit_discount" type="text" placeholder="Credit Discount" value="{{$systemsettting[0]['credit_discount']}}">
            </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Credit Clearing Period For Service (In Days)</label>
                    <input class="c-input" name="clear_credit_period_service" id="clear_credit_period_service" type="text" placeholder="Credit Clearing Period For Service" value="{{$systemsettting[0]['clear_credit_period_service']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Reminder Time ( In Minutes )</label>
                    <input class="c-input" name="remind_time" id="remind_time" type="text" placeholder="Reminder Time" value="{{$systemsettting[0]['remind_time']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Product Not Delivered Limit % (Buyer)</label>
                    <input class="c-input" name="product_not_delivered_limit" id="product_not_delivered_limit" type="text" placeholder="Product Not Delivered Limit" value="{{$systemsettting[0]['product_not_delivered_limit']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Product Cancel Limit % (Seller)</label>
                    <input class="c-input" name="product_cancel_limit_seller" id="product_cancel_limit_seller" type="text" placeholder="Product Cancel Limit " value="{{$systemsettting[0]['product_cancel_limit_seller']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">No Of Day For Claim (Buyer)</label>
                    <input class="c-input" name="no_of_day_for_claim" id="no_of_day_for_claim" type="text" placeholder="No Of Day For Claim" value="{{$systemsettting[0]['no_of_day_for_claim']}}">
                </div>

                <div class="c-field u-mb-small">
                    <input type="submit" class="c-btn c-btn--info c-btn--fullwidth" value="update">
                </div>
            </form>
            @if(count($oldpolicy)>'0')
            <h1>Old Policies</h1>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">INPOST AMOUNT</th>
                    <th scope="col">Clear Credit Period (For Item & Subscription)</th>
                    <th scope="col">Clear Credit Period (For Service)</th>
                    <th scope="col">Credit Discount</th>
                    <th scope="col">Product Not Delivered Limit (Buyer)</th>
                    <th scope="col">Product Cancel Limit (Seller)</th>
                    <th scope="col">No Of Day For Claiming(Buyer)</th>
                    <th scope="col">Added On</th>
                </tr>
                </thead>
                <tbody>
                @foreach($oldpolicy as $key => $policy)
                <tr>
                    <td scope="row">{{$key+1}}</td>
                    <td>{{$policy['inpost_amount']}}</td>
                    <td>{{$policy['clear_credit_period']}}</td>
                    <td>{{$policy['clear_credit_period_service']}}</td>
                    <td>{{$policy['credit_discount']}}</td>
                    <td>{{$policy['product_not_delivered_limit']}}</td>
                    <td>{{$policy['product_cancel_limit_seller']}}</td>
                    <td>{{$policy['no_of_day_for_claim']}}</td>
                    <td>{{$policy['created_at']}}</td>
                </tr>
                @endforeach

                </tbody>
            </table>
            @endif
        </div>
         

        <!-- <div class="col-lg-5">
            <div class="c-field u-mb-small">
                <label class="c-field__label" for="companyName">Company Name</label>
                <input class="c-input" id="companyName" type="text" placeholder="Dashboard Ltd.">
            </div>

            <div class="c-field u-mb-small">
                <label class="c-field__label" for="website">Website</label>
                <input class="c-input" id="website" type="text" placeholder="zawiastudio.com">
            </div>  

            <label class="c-field__label" for="socialProfile">Social Profiles</label>

            <div class="c-field has-addon-left u-mb-small">
                <span class="c-field__addon u-bg-twitter">
                    <i class="fa fa-twitter u-color-white"></i>
                </span>
                <input class="c-input" id="socialProfile" type="text" placeholder="Clark">
            </div>

            <div class="c-field has-addon-left">
                <span class="c-field__addon u-bg-facebook">
                    <i class="fa fa-facebook u-color-white"></i>
                </span>
                <input class="c-input" type="text" placeholder="Clark">
            </div>
        </div> -->
        
    </div>
</form>