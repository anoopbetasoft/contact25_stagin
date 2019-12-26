@php
    $friendproductcheck = App\Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency','friend'])->whereHas('userDet', function($q) {
                       $q->where('country','=',Auth::user()->country);
                })->whereHas('friend', function($q){
                    $q->where('friend_id_2',Auth::user()->id)->where('status','1');
                })->OrwhereHas('friend2', function($q){
                    $q->where('friend_id_1',Auth::user()->id)->where('status','1');
                })->where('user_id','!=',Auth::user()->id)
                ->where('p_slug','!=','')
                ->orderBy('created_at','desc')->get();
    $totalsale = App\Credit_detail::where('user_id',Auth::user()->id)->sum('value');
    $pendingsale = App\Credit_detail::where('user_id',Auth::user()->id)->where('status','0')->sum('value');
    $systemsetting = App\system_setting::where('status','1')->get();
    $decimal_place = App\currencies::where('code', Auth::user()->currency_code)->get();
@endphp
@if(count($friendproductcheck)=='0')
    @php
        $countryproductcheck = App\Product::with(['userDet'])->whereHas('userDet', function($q) {
                           $q->where('country','=',Auth::user()->country);
                    })->get();
    @endphp
@endif
<input type="hidden" id="clear_credit_period_service" value="{{$systemsetting[0]['clear_credit_period_service']}}">
<input type="hidden" id="clear_credit_period" value="{{$systemsetting[0]['clear_credit_period']}}">
<input type="hidden" id="credit_discount" value="{{$systemsetting[0]['credit_discount']}}">
<!-- left sidebar -->

 <div class="user-pro-body">
                    <div>
                        @if(empty($avataricon[0]['avatar']))
                            <img src="{{asset('admin-login/images/logo-icon.png')}}" alt="homepage" class="imgcircle"/>
                        @else
                            @foreach($avataricon as $avatar)
                                @if(strpos($avatar->avatar, "https://") !== false)
                                    <img src="{{$avatar->avatar}}" alt="homepage" class="imgcircle"/>
                                @else
                                    <img src="{{asset("uploads/avatar/$avatar->avatar")}}" alt="homepage"
                                         class="imgcircle"/>
                                @endif
                            @endforeach
                        @endif
                        <a href="{{url('/view_profile')}}" class=""
                       style="padding: 0px;">{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}
                        <span class="caret"></span>
                    </a>
            
                    </div>
                                </div>
<div class="scroll-sidebar">
    <!-- Sidebar navigation-->
    
               
                

    <nav class="sidebar-nav">
        <ul id="sidebarnav">
            <!-- <li class="user-pro">
                <div class="user-pro-body">
                    <div>
                        @if(empty($avataricon[0]['avatar']))
                            <img src="{{asset('admin-login/images/logo-icon.png')}}" alt="homepage" class="imgcircle"/>
                        @else
                            @foreach($avataricon as $avatar)
                                @if(strpos($avatar->avatar, "https://") !== false)
                                    <img src="{{$avatar->avatar}}" alt="homepage" class="imgcircle"/>
                                @else
                                    <img src="{{asset("uploads/avatar/$avatar->avatar")}}" alt="homepage"
                                         class="imgcircle"/>
                                @endif
                            @endforeach
                        @endif

                    </div>
                    <a href="{{url('/view_profile')}}" class=""
                       style="padding: 0px;">{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}
                        <span class="caret"></span>
                    </a>
                </div>
            </li> -->
            <li class="user-pro hide-menu t-earn" style="padding: 0px;">
                @php
                $totalsale = number_format($totalsale,$decimal_place[0]['decimal_places']);
                $totalsale = str_replace('.00','',$totalsale);
                $pendingsale = number_format($pendingsale,$decimal_place[0]['decimal_places']);
                $pendingsale = str_replace('.00','',$pendingsale);
                @endphp
                <a class="waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"
                   style="padding: 0px;"><small class="db text-success ">SALES (LAST 30 DAYS)</small>
                    <h3 class="m-t-0 m-b-0 total_earnings_30_days text-success">{{$currency_symbol[0]->symbol}}{{$totalsale}}</h3>@if($pendingsale!='0')
                        <p onclick="pendingmessage();">({{$currency_symbol[0]->symbol}}{{$pendingsale}}
                            Pending)</p>@endif</a>
            </li>
            <li>
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><em
                            class="far fa-clock"></em> <span class="hide-menu"> Buy &amp; Sell</span></a>
                <ul aria-expanded="false" class="collapse buy_sell_dropdown">
                    <li><a href="{{url('/subscribed_users')}}">Subscribed Users</a></li>
                    <li><a href="{{url('/my_sales')}}">My Orders</a></li>
                    <li><a href="{{url('/return_request')}}">Return Requests</a></li>
                    <li><a href="{{url('/myproducts')}}">My Stuff</a></li>
                    @if(count($friendproductcheck)>'0')
                        <li><a href="{{url('/products')}}">My Friend's Stuff</a></li>
                    @elseif(count($countryproductcheck)>'0')
                        <li><a href="{{url('/products')}}">My Neighbours Stuff</a></li>
                    @endif
                    <li><a href="{{url('/my_order')}}">My Purchases</a></li>
                    <li><a href="{{url('/my_subscription')}}">My Subscriptions</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i
                            class="ti-email"></i><span class="hide-menu">Messages</span></a>
                <ul aria-expanded="false" class="collapse">
                    <li><a href="javascript:void(0)">Chat</a></li>
                    <li><a href="javascript:void(0)">Support</a></li>
                </ul>
            </li>
            <li><a href="{{url('/my_money')}}"><i class="ti-wallet"></i><span class="hide-menu">My Money</span></a></li>

            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   aria-expanded="false"><i class="fas fa-sign-out-alt"></i><span class="hide-menu">Logout</span></a>
            </li>

        </ul>
    </nav>
    <!-- End Sidebar navigation -->
</div>
<script>
    function pendingmessage() {
        var productdays = $('#clear_credit_period').val();
        var servicedays = $('#clear_credit_period_service').val();
        var creditdiscount = $('#credit_discount').val();
        Swal.fire({
            title: "<h1>Pending Money</h1>",
            html: "When we take money for sales you make, we hold onto it for " + productdays + " days for product sales and " + servicedays + " days for services.<br>If your buyer returns the goods or claims a refund for services we can easily return they money to them.<br>After your money is cleared, you can spend it on Contact25 (and get " + creditdiscount + "% off your purchases) or we can transfer the money into your bank account.",
            //confirmButtonText: "V <u>redu</u>",
        });


    }
</script>