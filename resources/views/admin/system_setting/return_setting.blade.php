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
                    <label class="c-field__label" for="name">No of days allowed for refund</label>
                    <input class="c-input" name="days_allowed_for_refund" type="text" id="inpost_amount" placeholder="Inpost Amount" value="{{$returnsetting[0]['days_allowed_for_refund']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Credit limit used for generating label(don't use - [minus]) </label>
                    <input class="c-input" name="credit_limit_refund" id="clear_credit_period" type="text" placeholder="Credit Clearing Period" value="{{$returnsetting[0]['credit_limit_refund']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Inpost Return Amount</label>
                    <input class="c-input" name="inpost_return_amount" id="credit_discount" type="text" placeholder="Credit Discount" value="{{$returnsetting[0]['inpost_return_amount']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">No of days allowed for seller to create label after return request</label>
                    <input class="c-input" name="days_allowed_for_return_label" id="clear_credit_period_service" type="text" placeholder="Credit Clearing Period For Service" value="{{$returnsetting[0]['days_allowed_for_return_label']}}">
                </div>
                <div class="c-field u-mb-small">
                    <label class="c-field__label" for="email">Product Returning Limit % (Dont use %)</label>
                    <input class="c-input" name="product_returning_limit" id="product_returning_limit" type="text" placeholder="Product returning limit" value="{{$returnsetting[0]['product_returning_limit']}}">
                </div>

                <div class="c-field u-mb-small">
                    <input type="submit" class="c-btn c-btn--info c-btn--fullwidth" value="update">
                </div>
            </form>
            @if(count($oldreturnpolicy)>'0')
                <h1>Old Return Policies</h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">days_allowed_for_refund</th>
                        <th scope="col">credit_limit_refund</th>
                        <th scope="col">inpost_return_amount</th>
                        <th scope="col">days_allowed_for_return_label</th>
                        <th scope="col">Added On</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($oldreturnpolicy as $key => $policy)
                        <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$policy['days_allowed_for_refund']}}</td>
                            <td>{{$policy['credit_limit_refund']}}</td>
                            <td>{{$policy['inpost_return_amount']}}</td>
                            <td>{{$policy['days_allowed_for_return_label']}}</td>
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