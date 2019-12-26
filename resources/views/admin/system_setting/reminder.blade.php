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
                    <label class="c-field__label" for="name">Time For Reminder (In Minutes)</label>
                    <input class="c-input" name="days_allowed_for_refund" type="text" id="remind_time" placeholder="Reminder Time" value="{{$returnsetting[0]['remind_time']}}">
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