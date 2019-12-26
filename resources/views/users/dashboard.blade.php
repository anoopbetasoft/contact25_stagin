@extends('layouts.customer')
@section('content')
@php
$friends = App\friend::where('friend_id_1',Auth::user()->id)->where('status','1')->orwhere('friend_id_2',Auth::user()->id)->with(['user2','user'])->get();
@endphp
<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
    }
    .gm-style-iw.gm-style-iw-c {
    max-width: 320px;
    max-height: 180px !important;
    }
    .gm-style-iw-d {
    overflow: hidden !important;
    max-width: 302px;
    max-height: 180px !important;
    padding: 10px;
    margin: 1px;
}

    @media (max-width:320px)
    {
        #map{
            margin-bottom:20px;

        }

        .gm-style-iw-d {
    padding: 3px;
    margin: -1px !important;
}
    }


    @media (max-width:678px)
    {
        #map{
            margin-bottom:20px;

        }
        .gm-style-iw-d {
    margin: 0px;
}
    }
    .gmnoprint
    {
        display: none;
    }
</style>
<!-- internal page dashboard customer -->
<div id="loaded_content"></div>
    <div class="row dashboard_1">
        <!-- ## IF FRIEND HAVE PRODUCTS OR USER HAVE FRIEND STARTS HERE -->
        @if(count($friendstuff)>'0' || count($countryproducts)>'0')
        <div class="col-lg-3 col-md-0">
            <div class="card bg-cyan text-white" style="height:378px;">
                <div class="card-body">
                    <div id="myCarouse2" class="carousel slide" data-ride="carousel">
                        <!-- Carousel items -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <h3 class="cmin-height"><span style="font-size:38px">Best Friends</span> <br><span class="font-medium">share </span> </h3>
                                <br>
                                <span class="m-l-10">
                                <a href="/add_product"><button class="btn btn-block  btn-rounded btn-warning">Start</button></a>
                                </span>
                            </div>
                            <div class="carousel-item">
                                <h3 class="cmin-height"><span style="font-size:38px">Sell</span> <br><span class="font-medium">Friends, Neighbours</span></h3>
                                <br>
                                <span class="m-l-10">
                                <a href="/add_product"><button class="btn btn-block  btn-rounded btn-warning">Start</button></a>
                                </span>
                            </div>
                            <div class="carousel-item">
                                <h3 class="cmin-height"><span style="font-size:38px">Your Stuff</span><br><span class="font-medium">organised</span></h3>
                                <br>
                                <span class="m-l-10">
                                <a href="/add_product"><button class="btn btn-block  btn-rounded btn-warning">Start</button></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3" style="display:none;">
            <div class="white-box" style="min-height:393px;">
                <h3 class="box-title" style="color:#00c292">
                    <i class="fa fa-tag barcode_scan"></i> Sell, Share &amp; Store
                </h3>
                <div class="steamline">
                    <div class="sl-item">
                        <div class="sl-left"> 
                            <button type="button" class="btn btn-success btn-circle btn-xl"><i class="fa fa-tag barcode_scan"></i> </button> 
                        </div>
                        <div class="sl-right">
                            <p>Sell to friends, neighbours, country wide - it's your choice</p>
                        </div>
                    </div>

                    <div class="sl-item">
                        <div class="sl-left"> 
                            <button type="button" class="btn btn-danger btn-circle btn-xl"><i class="icon-share"></i> </button> 
                        </div>
                        <div class="sl-right">
                            <p>Choose your closest friends and let them share your things</p>
                        </div>
                    </div>
                    <div class="sl-item">
                        <div class="sl-left"> <button type="button" class="btn btn-warning btn-circle btn-xl"><i class="ti-package show_boxes"></i> </button> </div>
                        <div class="sl-right">

                        <p>Lots of stuff? Organise it into numbered boxes with our help</p>
                        </div>
                    </div>
                </div>
                <span id="scan_result"></span>

                <span style="padding: 5px;">&nbsp;</span>
                <span id="barcode_results">

                    <div class="sttabs tabs-style-bar price_results" style="display: none;">
                        <nav>
                            <ul class="new_selected">
                            <li><a href="#section-bar-1" class="sticon btn-rounded"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>&nbsp;New</a></li>


                            </ul>
                            <ul class="good_selected">

                            <li class=""><a href="#section-bar-2" class="sticon btn-rounded"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-empty"></i>&nbsp;Good</a></li>


                            </ul>
                            <ul class="ok_selected">


                            <li><a href="#section-bar-3" class="sticon btn-rounded"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-empty"></i>&nbsp;OK</a></li>

                            </ul>
                        </nav>
                        <div class="content-wrap actual_price_results" style="text-align: center; display: none;">
                            <section id="section-bar-1" class="">
                                <p style="font-size: 10px;">
                                    <span style="font-size: 30px;  color: #00c292;  ">£</span>
                                    <span id="price_new" style="font-size: 46px; color: #00c292"></span>
                                    <span id="price_info" class="ti-info-alt" style="font-size: 14px; padding-bottom: 20px; color: black; cursor: pointer;"></span>
                                    <br>
                                    <span id="price_info_desc" style="display: none;">It's great to know how much your stuff is worth ... exactly!  
                                        <br><br>
                                        Pop it in a box and keep adding items until you've reached <span style="font-size: 12px; font-weight: bold;color: #00c292 ">£50</span> or more. Then <span style="font-size: 12px; font-weight: bold; color: #00c292  ">FREE POST</span> it to us to sell for you on Amazon, Ebay and Contact25.  We'll pack, post, deal with returns and then send money via <span style="font-size: 12px; font-weight: bold; color: #00c292  ">BACS</span>.
                                        <br><br>
                                        Or, if you prefer sell it yourself on Contact25 and handle the post yourself you can do that too! Remember, you'll need to send any orders within 
                                        <span style="font-size: 14px; font-weight: bold;color: #00c292">48hrs 
                                            <i class="fa fa-truck"></i>
                                        </span>. 
                                    </span>
                                </p>
                            </section>

                            <section id="section-bar-2" class="">
                                <p style="font-size: 10px;">
                                    <span style="font-size: 30px;  color: #00c292;  ">£</span>
                                    <span id="price_good" style="font-size: 46px; color: #00c292"></span>
                                    <span id="price_info_good" class="ti-info-alt" style="font-size: 14px; padding-bottom: 20px; color: black; cursor: pointer;"></span>
                                    <br>
                                    <span id="price_info_good_desc" style="display: none;font-size: 10px;">
                                        It's great to know how much your stuff is worth ... exactly!  
                                        <br><br>
                                        Pop it in a box and keep adding items until you've reached 
                                        <span style="font-size: 12px; font-weight: bold;color: #00c292 ">£50</span> 
                                        or more. Then 
                                        <span style="font-size: 12px; font-weight: bold; color: #00c292  ">FREE POST</span> it to us to sell for you on Amazon, Ebay and Contact25.  We'll pack, post, deal with returns and then send money via 
                                        <span style="font-size: 12px; font-weight: bold; color: #00c292  ">BACS</span>.
                                        <br><br>
                                        Or, if you prefer sell it yourself on Contact25 and handle the post yourself you can do that too! Remember, you'll need to send any orders within 
                                        <span style="font-size: 14px; font-weight: bold;color: #00c292">48hrs <i class="fa fa-truck"></i></span>.
                                    </span>
                                </p>
                            </section>
                            <section id="section-bar-3" class="">
                                <p style="font-size: 10px;">
                                    <span style="font-size: 30px;  color: #00c292;  ">£</span>
                                    <span id="price_ok" style="font-size: 46px; color: #00c292">2,383.45</span>
                                    <span id="price_info_ok" class="ti-info-alt" style="font-size: 14px; padding-bottom: 20px; color: black; cursor: pointer;"></span>
                                    <br>
                                    <span id="price_info_ok_desc" style="display: none;font-size: 10px;">

                                        It's great to know how much your stuff is worth ... exactly!  <br><br>
                                        Pop it in a box and keep adding items until you've reached <span style="font-size: 12px; font-weight: bold;color: #00c292 ">£50</span> or more. Then <span style="font-size: 12px; font-weight: bold; color: #00c292  ">FREE POST</span> it to us to sell for you on Amazon, Ebay and Contact25.  We'll pack, post, deal with returns and then send money via <span style="font-size: 12px; font-weight: bold; color: #00c292  ">BACS</span>.
                                        <br><br>
                                        Or, if you prefer sell it yourself on Contact25 and handle the post yourself you can do that too! Remember, you'll need to send any orders within <span style="font-size: 14px; font-weight: bold;color: #00c292">48hrs <i class="fa fa-truck"></i></span>. 
                                    </span>
                                </p>
                            </section>
                        </div><!-- /content -->
                    </div>
                </span>

                <div class="sttabs tabs-style-bar buy_or_sell_it" style="display: none; cursor: pointer;">
                    <nav>
                    <ul>
                    <li class=""><a class="sticon btn-rounded sell_it"><i class="fa fa-tag m-l-5"></i>&nbsp;Sell</a></li>
                    <li class=""><a class="sticon btn-rounded buy_it"><i class="fa fa-heart m-l-5"></i>&nbsp;Buy</a></li>
                    </ul>
                    </nav>
                </div>

                <h3 class="box-title" style="color:#00c292; font-size: 62px; text-align: center; margin-top: 50px;  display: none;"><i class="ti-package show_boxes" style="cursor: pointer;"></i><span class="ti-info-alt room_info" style="font-size: 12px; padding-bottom: 20px; color: black; cursor: pointer; margin-left: 3px;"></span>
                </h3>

                <div style="text-align: center; font-size: 10px;display: none; " id="room_info_desc">
                    Choose the 
                    <span style="font-size: 14px; font-weight: bold;color: #00c292">room <i class="ti-home"></i></span> 
                    and storage 
                    <span style="font-size: 14px; font-weight: bold;color: #00c292">box <i class="ti-package"></i></span> 
                    where your stuff will be stored. Label your box 
                    <span style="font-size: 14px; font-weight: bold;color: #00c292">1,2,3... <i class="ti-pencil-alt2"></i></span>
                </div>
                <!--<div style="margin-bottom:50px;"></div>-->
                <div class="display_box_options">
                    <div id="add_room" style="display: none; text-align: center;">

                        <select id="add_room_select" class="form-control">
                            <option value="0"> + Add Room</option>
                            <option value="Attic">Attic</option>
                            <option value="Office">Office</option>
                            <option value="Garage">Garage</option>
                            <option value="Shed">Shed</option>
                            <option value="Self Storage">Self Storage</option>
                            <option value="-1">Other ...</option>
                        </select>
                    </div>
                    <input type="text" class="form-control other_room" placeholder="Room Name" style="display: none;">

                    <div id="choose_room_box" style="display: none;"><div class="cssload-speeding-wheel"></div></div>
                </div>
                <span style="padding: 5px;">&nbsp;</span>

                <div class="sttabs tabs-style-bar sell_store_confirm" style="display: none; cursor: pointer;">
                    <nav>
                        <ul>
                        <li class=""><a class="sticon btn-rounded section-bar-5"><i class="fa fa-tag m-l-5"></i>&nbsp;Sell</a></li>
                        <li class=""><a class="sticon btn-rounded section-bar-4"><i class="fa fa-heart m-l-5"></i>&nbsp;Store</a></li>
                        </ul>
                    </nav>
                    <div class="content-wrap" style="text-align: center;">
                        <section id="section-bar-5" class="">
                            <p style="font-size: 9px;">
                                I own this item and if it sells will post within 
                                <span style="font-size: 14px; font-weight: bold;color: #00c292">48hrs <i class="fa fa-truck"></i></span>.  
                                I will accept no-quibble returns within 30 days of the sale (paid for by the end customer).  Should the item be defective or damaged I will pay for and returns costs and refund the item in full. 

                                <br>
                            </p>
                            <nav>
                                <ul>
                                    <span id="item_condition" style="display: none;"></span>
                                    <span id="box_location" style="display: none;"></span>
                                    <span id="room_location" style="display: none;"></span>
                                    <span id="barcode_number" style="display: none;"></span>
                                    <span id="s_id" style="display: none;"></span>
                                    <span id="s_ISBN10" style="display: none;"></span>
                                    <span id="s_ISBN13" style="display: none;"></span>
                                    <span id="s_weight" style="display: none;"></span>
                                    <span id="s_height" style="display: none;"></span>
                                    <span id="s_length" style="display: none;"></span>
                                    <span id="s_width" style="display: none;"></span>
                                    <span id="s_label" style="display: none;"></span>
                                    <span id="s_price" style="display: none;"></span>
                                    <span id="s_price_like_new" style="display: none;"></span>
                                    <span id="s_price_good" style="display: none;"></span>
                                    <span id="s_price_ok" style="display: none;"></span>
                                    <li class="tab-current" id="final_sell_it"><a class="sticon btn-rounded" style="background: #BB0003; color: white;"><i class="fa fa-tag m-l-5"></i>&nbsp;Sell It</a></li>
                                </ul>
                            </nav>
                        </section>
                        <section id="section-bar-4" class=""><p style="font-size: 14px;">
                            Store your item.  You can use this app just to store your stuff and find it again! <br>
                            <br>
                            We'll value it as you go, so if you do decide to sell, you'll know how much you can expect to get for it. 
                            </p>
                            <nav>
                                <ul>
                                <li class="tab-current"><a class="sticon btn-rounded" style="background:#8E06A0; color: white;"><i class="fa fa-heart m-l-5"></i>&nbsp;Store It</a></li>
                                </ul>
                            </nav>
                        </section>
                    </div><!-- /content -->
                </div>
            </div>
        </div>
        <!-- carousel-->
        <div class="col-xlg-3 col-lg-3 col-md-6  col-sm-12 home_carousel_friends"><div class="card bg-white">
                            <div class="card-body">
                                <div id="myCarousel4" class="carousel slide" data-ride="carousel">
                                    <!-- Carousel items -->
                                    <div class="carousel-inner">
                                        @if(count($friendstuff)>'0')
                                        @foreach($friendstuff as $key => $items)
                                        @php $p_slug =  "buy-".$items->p_slug; $country = Auth::user()->country; $id = $items->id; 
                                        $encoded = base64_encode($items->id);
                                        $p_img_arr = explode(',', $items->p_image);
                                        $items->p_selling_price = number_format($items->p_selling_price,$decimal_place[0]['decimal_places']);
                                        $items->p_selling_price = str_replace('.00','',$items->p_selling_price);
                                          $items->p_lend_price = number_format($items->p_lend_price,$decimal_place[0]['decimal_places']);
                                            $items->p_lend_price = str_replace('.00','',$items->p_lend_price);
                                        $count = 1;
                                        @endphp

                                            <div class="carousel-item flex-column  <?php if($key=='1') { echo 'active'; }?>">
                                            <h2 class="box-title text-info" style="font-size:14px;font-weight:bold;">FRIENDS' NEW STUFF<span style="float:right; font-size:18px;"><a href="<?php echo url('products/') ?>"><i class="ti-arrow-top-right"></i></a></span></h2>
                                             @if(empty($items->p_image))
                                            <a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><img class="buy-page" data-s_id="814443" src="{{asset('assets/images/logo-balls.png')}}" style="cursor:pointer;height:270px; padding: 5px 5px 0 0; margin-left: auto;margin-right:auto;display: block;" alt="Multiplying Sponge Balls Magic Trick"></a>
                                            @else
                                            <a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><img class="buy-page" data-s_id="814443" src='{{asset("uploads/products/$p_img_arr[0]")}}' style="cursor:pointer;height:270px; padding: 5px 5px 0 0; margin-left: auto;margin-right:auto;display: block;" alt="Multiplying Sponge Balls Magic Trick"></a>
                                            
                                            @endif

                                            <br>
                                            <div>
                                            <span style="float:right;">
                                                <span class="text-success" style="font-size:16px;"><a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><i class="fa fa-tag"></i>&nbsp; {{$items->currency->symbol}}{{$items->p_selling_price}}</a></span> &nbsp;&nbsp;<span class="text-warning" style="font-size:16px;"><a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><i class="fa fa-refresh m-l-5"></i>&nbsp; {{$items->currency->symbol}}{{$items->p_lend_price}}/wk</a></span>
</span>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                         @foreach($countryproducts as $key => $items)
                                        @php $p_slug =  "buy-".$items->p_slug; $country = Auth::user()->country; $id = $items->id; 
                                        $encoded = base64_encode($items->id);
                                        $p_img_arr = explode(',', $items->p_image);
                                        $items->p_selling_price = number_format($items->p_selling_price,$decimal_place[0]['decimal_places']);
                                        $items->p_selling_price = str_replace('.00','',$items->p_selling_price);
                                        $items->p_lending_price = number_format($items->p_lending_price,$decimal_place[0]['decimal_places']);
                                        $items->p_lending_price = str_replace('.00','',$items->p_lending_price);
                                        $count = 1;
                                        @endphp
                                            <div class="carousel-item flex-column  <?php if($key=='1') { echo 'active'; }?>">
                                            <h2 class="box-title text-info" style="font-size:14px;font-weight:bold;">NEW CLOSE TO YOU<span style="float:right; font-size:18px;"><a href="<?php echo url('products/') ?>"><i class="ti-arrow-top-right"></i></a></span></h2>
                                             @if(empty($items->p_image))
                                            <a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><img class="buy-page" data-s_id="814443" src="{{asset('assets/images/logo-balls.png')}}" style="cursor:pointer;height:270px; padding: 5px 5px 0 0; margin-left: auto;margin-right:auto;display: block;" alt="Multiplying Sponge Balls Magic Trick"></a>
                                            @else
                                            <a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><img class="buy-page" data-s_id="814443" src='{{asset("uploads/products/$p_img_arr[0]")}}' style="cursor:pointer;height:270px; padding: 5px 5px 0 0; margin-left: auto;margin-right:auto;display: block;" alt="Multiplying Sponge Balls Magic Trick"></a>
                                            
                                            @endif

                                            <br>
                                            <div>
                                            <span style="float:right;">
                                                <span class="text-success" style="font-size:16px;"><a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><i class="fa fa-tag"></i>&nbsp; {{$items->currency->symbol}}{{$items->p_selling_price}}</a></span> &nbsp;&nbsp;<span class="text-warning" style="font-size:16px;"><a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>"><i class="fa fa-refresh m-l-5"></i>&nbsp; {{$items->currency->symbol}}{{$items->p_lend_price}}/wk</a></span> &nbsp;&nbsp;
                                            
</span>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                        
                                       
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
            
            <!-- END carousel-->
            </div>

        <div class="col-lg-6 col-md-6">
        <div class="white-box">
            <input type="hidden" name="lat" id="lat" value="{{Auth::user()->lat}}">
            <input type="hidden" name="lng" id="lng" value="{{Auth::user()->lng}}">
            <h3 class="box-title" style="color:#fb9678">NEW CLOSE TO YOU 
               {{-- <span style="float:right; font-size:18px;cursor: pointer;">
                    <i class="ti-reload" style="color:#ee0ecd" onclick="initMap();"></i> 
                    <i class="ti-location-pin" style="color:#00c292" onclick="locate();"></i> 
                    <i class="ti-home" style="color:#03a9f3"></i> 
                    <a href="map.html"><i class="ti-arrow-top-right"></i></a>
                </span>--}}

            </h3>
            <!-- <object data="https://contact25.com/_old_version_Mar_19/map" width="100%" height="100%" style="min-height:295px;">
            <embed src="https://contact25.com/_old_version_Mar_19/map" width="100%" height="100%" style="min-height:295px;"> 
            </object> -->

        {{--    <div id="homemap"></div>--}}

        </div>
            <div id="map" style="width: 100%;height:303px;margin-top:-18px;"></div>
        
        </div>
         @endif
            <!-- ##  IF FRIEND HAVE PRODUCTS OR USER HAVE FRIEND ENDS HERE## -->
    </div>
    <div id="office_depot_output"></div>   
    <div style="clear:both;"></div>






    <!-- ============================================================== -->
    <!-- Comment - table -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- ============================================================== -->
        <!-- Comment widgets -->
        <!-- ============================================================== -->
         @if(count($friendrequest)>0)
        <div class="col-lg-6 home_friend_requests"> <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Friend Requests</h5>
                            </div>
                            
                            <!-- ============================================================== -->
                            <!-- Comment widgets -->
                            <!-- ============================================================== -->
                            <div class="comment-widgets">
                                <!-- Comment Row -->
                               @foreach($friendrequest as $requests)
                                <div id="request{{$requests->id}}">
                                <div class="d-flex no-block comment-row hide_request_4">
                                    <div class="p-2">  <span class="round">
                                        @if($requests->user->avatar!='')
                                                    @if(strpos($requests->user->avatar, "https://") !== false)
                                                        <img src="{{$requests->user->avatar}}" alt="user" width="50">
                                                    @else
                                                        <img src="{{asset('uploads/avatar/'.$requests->user->avatar)}}" alt="user" width="50">
                                                    @endif
                                        @else
                                            <img src="{{asset('admin-login/images/logo-icon.png')}}" alt="user" width="50">
                                        @endif
                                    </span></div>
                                    <div class="comment-text w-100">
                                        <h5 class="font-medium">{{$requests->user->name}}</h5>
                                        <p class="m-b-10 text-muted">... wants to be <span class="text-cyan" style="font-size:16px;font-weight:bold;"><i class="fa fa-heart"></i> friends</span>.  Accept to  <span class="text-success" style="font-size:16px;font-weight:bold;"><i class="fa fa-tag"></i> buy/sell</span> or <span class="text-warning" style="font-size:16px;font-weight:bold;"><i class="fa fa-refresh m-l-5"></i>lend</span> each other's stuff!</p>
                                        <div class="comment-footer" style="float:right;">
                                            <button type="button" class="btn btn-danger btn-circle btn-lg decline_friend_request" data-friend_request_id="4" onclick="deletefriendrequest({{$requests->id}},{{$requests->friend_id_1}})"><i class="fa fa-times"></i> </button>
                                             <button type="button" class="btn btn-info btn-circle btn-lg accept_friend_request" data-friend_request_id="4" onclick="acceptfriendrequest({{$requests->id}})"><i class="fa fa-check"></i> </button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endforeach
                               </div>
                                </div>
            
            <!-- END carousel-->
            </div>
            @endif
        <!-- ============================================================== -->
        <!-- Table -->
        <!-- ============================================================== -->
        @if(Auth::user()->reject_count<'5')
          @if(count($friendrequest)>0)
          <div class="col-lg-6">
          @else
          <div class="col-lg-12">
          @endif  
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                    <div>
                    <h5 class="card-title">Find Friends</h5>
                    <h6 class="card-subtitle">{{--(If no app friends listed for this user) --}}Download the app, and we'll look for your friends for you. </h6>
                    </div>

                    </div><span style="float:right;cursor: pointer;">

                    <img src="{{ asset('assets/images/store_app.png') }}" alt="">  <img src="{{ asset('assets/images/store_play.png') }}" alt="">                                 </span>  
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-6">
                            <h3>Find your friends</h3>
                            <h5 class="font-light m-t-0">Link up, and borrow/buy each other's stuff


                            </h5>
                        </div>
                        {{--<div class="col-6 align-self-center display-6 text-right">
                            <h2 class="text-success">
                                <button type="button" class="btn btn-outline-info btn-rounded"><i class="fas fa-heart"></i> Scan</button>
                            </h2>
                        </div>--}}
                        <div class="col-12 align-self-center display-12 text-right">
                            <h2 class="text-success">
                                <input type="text" name="email" id="email" class="form-control" placeholder="Find Email / Mobile">
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover no-wrap">
                        <thead>
                            <tr>
                                <th>NAME / STUFF</th>
                                <th>INVITE / IGNORE</th>
                            </tr>
                            </thead>
                        <tbody id="frienddata">
                       <!--  <tr>

                            <td class="txt-oflo">Geoff Smith 
                                <span class="badge badge-warning badge-pill" style="cursor: pointer;"><i class="fas fa-user-plus"></i> 24</span> 
                            </td>


                            <td>
                                <span class="text-success"><button type="button" class="btn btn-secondary btn-rounded"> <i class="far fa-heart"></i> Invite</button> / <button type="button" class="btn btn-secondary btn-rounded"> <i class="fa fa-times"></i> Ignore</button></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="txt-oflo">
                                Delight Mukaro <span class="badge badge-info badge-pill" style="cursor: pointer;"><i class="fas fa-user-plus"></i> 324</span>
                            </td>
                            <td>
                                <span class="text-info"><span class="text-success"><button type="button" class="btn btn-secondary btn-rounded"> <i class="far fa-heart"></i> Pending</button></span></span>
                            </td>
                        </tr> -->
                        
                        </tbody>
                    </table>
                </div>

                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-6">
                            <h3>Scan</h3>
                            <h5 class="font-light m-t-0">Get your friends to scan this QR code, and they'll be able to see your stuff. If they register, you'll automatically be linked.
                            </h5>
                        </div>
                        <div class="col-6 align-self-center display-6 text-right">
                            <h2 class="text-success" style="cursor: pointer;">
                                <img src="https://chart.googleapis.com/chart?chs=100x100&amp;cht=qr&amp;chl=http%3A%2F%2Fwww.google.com%2F&amp;choe=UTF-8" title="Link to Google.com">
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- =======================Create Friend Group Section =============== -->
        
        @if(count($friendcount)>'0')
        <div class="col-lg-12">
         <div class="card">
            <div class="card-body">
        <h3>Create Friends Group</h3>
         <form action="/create-group" method="post">
            @csrf
        <label>Friends Group Name</label>
        <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Group Name" required>
        <br>
        <label>Choose Friends:</label><br>
        <input type="hidden" name="group_id" value="">
        <div class="btn-group sell_to_friends" data-toggle="buttons" {{--onclick="friendscheckall()"--}}>
                            <label class="btn sell_to_friends" style="padding:0px;">
        <div class="custom-control custom-checkbox mr-sm-2 ">
        <input type="checkbox" class="custom-control-input checkbox-danger friendinput" style="cursor: pointer;" id="friendscheckall">
        <label class="custom-control-label text-danger" for="friendscheckall" style="cursor: pointer;" >Check All</label></div>
        </label>
        </div>
        @foreach($friends as $friend)
        @if($friend->friend_id_1==Auth::user()->id)

        <div class="btn-group sell_to_friends" data-toggle="buttons">
                            <label class="btn sell_to_friends" style="padding:0px;">
        <div class="custom-control custom-checkbox mr-sm-2 " {{--onclick="friendscheckall2({{$friend['user2']['id']}})"--}}>
        <input type="checkbox" name="friend[]" class="custom-control-input checkbox-danger friendcheck" id="friend{{$friend['user2']['id']}}" value="{{$friend['user2']['id']}}" style="cursor: pointer;">
        <label class="custom-control-label text-danger" for="friend{{$friend['user2']['id']}}" >{{$friend['user2']['name']}}</label></div>
        </label>
        </div>
        @elseif($friend->friend_id_2==Auth::user()->id)

        <div class="btn-group sell_to_friends" data-toggle="buttons">
                            <label class="btn sell_to_friends" style="padding:0px;">
        <div class="custom-control custom-checkbox mr-sm-2 ">
        <input type="checkbox" name="friend[]" class="custom-control-input checkbox-danger friendcheck" id="friend{{$friend['user']['id']}}" value="{{$friend['user']['id']}}" style="cursor: pointer;">
        <label class="custom-control-label text-danger" for="friend{{$friend['user']['id']}}">{{$friend['user']['name']}}</label></div>
        </label>
        </div>
        @endif
        @endforeach
        <br>
        <input type="submit" name="Create Group" value="Create Group" class="btn btn-primary">
        <br>
        </form>
        <br>
         @php
        $groups = App\friend_group::where('user_id',Auth::user()->id)->get();
        @endphp
        @if(count($groups)>0)
        <div class="table-responsive">
                    <table class="table table-hover no-wrap">
                        <thead>
                            <tr>
                                <th>Group Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @foreach($groups as $group)
                        <tbody id="group{{$group->id}}">
                            <tr>
                                <td>{{$group->group_name}}</td>
                                <td onclick="deletegroup({{$group->id}});" style="cursor: pointer;"><i class="fa fa-trash"></i>&nbsp;Delete</td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
        </div>
        @endif
    </div>
</div>

        <!-- =======================Create Friend Group Section Ends Here ====== -->



    </div>
    @endif

  {{--<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ&callback=initMap"></script>--}}
              <script async defer
                      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ"></script>
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
              <script>
                  $(document).ready(function() {
                      if (localStorage.getItem("latitude") === null || localStorage.getItem("latitude") === "") {
                          initGeolocation();
                      }
                      function initGeolocation() {
                          if (navigator && navigator.geolocation) {
                              navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
                          } else {
                              console.log('Geolocation is not supported');
                          }
                      }

                      function errorCallback() {
                          localStorage.setItem("latitude","");
                          localStorage.setItem("longitude","");

                      }

                      function successCallback(position) {
                          localStorage.setItem("latitude",position.coords.latitude);
                          localStorage.setItem("longitude",position.coords.longitude);

                      }
                  });
              </script>
        <script type="text/javascript">

                var map;
                var infoWindow;
                var markersData = [];
                var mainUrl = '';
                var bounds = '';
                var userid = [];
                var lat = [];
                var lng = [];
                //console.log(localStorage.getItem("latitude"));
                //console.log(localStorage.getItem("longitude"));
                if (localStorage.getItem("latitude") === null || localStorage.getItem("latitude") === "")
                {
                    var lat = $('#lat').val();
                    var lng = $('#lng').val();
                }
                else
                {
                    var lat = localStorage.getItem("latitude");
                    var lng = localStorage.getItem("longitude");
                }

                var icons = {
                    shop: {
                        icon:  'assets/googlemap/orange.png'
                    },
                    home: {
                        icon: 'assets/googlemap/purple.png'
                    },
                    hotspot: {
                        icon: 'assets/googlemap/red.png'
                    }
                };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl+'/fetchlocationproduct',
            dataType : 'json',
            success: function(data) {
                if(data.error=='1')
                {
                  alert(data.message);
                }
                else
                {

                        markersData = data;
                        initialize(lat, lng, markersData);
                }

            },
            error: function(data) {
                alert("Some error occured"); //location.reload(); return false;
                //console.log();
            }
        });
            function initGeolocation() {
                if (navigator && navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
                } else {
                    console.log('Geolocation is not supported');
                }
            }
            function errorCallback() {
                localStorage.setItem("latitude","");
                localStorage.setItem("longitude","");

            }

            function successCallback(position) {
                localStorage.setItem("latitude",position.coords.latitude);
                localStorage.setItem("longitude",position.coords.longitude);

            }

            function initialize(latitude,longitude,markersData)
        {
            var mapOptions = {
                center: new google.maps.LatLng(latitude,longitude),
                zoom: 10,
                mapTypeId: 'roadmap',
                gestureHandling: 'greedy',
                styles: [{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#d6e2e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#cfd4d5"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#7492a8"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":25}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#cfd4d5"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"color":"#7492a8"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#dde2e3"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#588ca4"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"saturation":-100}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a9de83"}]},{"featureType":"poi.park","elementType":"geometry.stroke","stylers":[{"color":"#bae6a1"}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"color":"#c6e8b3"}]},{"featureType":"poi.sports_complex","elementType":"geometry.stroke","stylers":[{"color":"#bae6a1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#41626b"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":-45},{"lightness":10},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#c1d1d6"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#a6b5bb"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.fill","stylers":[{"color":"#9fb6bd"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"transit","elementType":"labels.icon","stylers":[{"saturation":-70}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#b4cbd4"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#588ca4"}]},{"featureType":"transit.station","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#008cb5"},{"visibility":"on"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"saturation":-100},{"lightness":-5}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#a6cbe3"}]}]
            };

            map = new google.maps.Map(document.getElementById('map'), mapOptions);

            // a new Info Window is created
            infoWindow = new google.maps.InfoWindow();

            // Event that closes the Info Window with a click on the map
            google.maps.event.addListener(map, 'click', function() {
                infoWindow.close();
            });

            // Finally displayMarkers() function is called to begin the markers creation
            displayMarkers(latitude,longitude,markersData);
            setTimeout(function(){
               // map.setCenter(new google.maps.LatLng(latitude,longitude));
                //map.setZoom(10);
            }, 300);
        }
        function displayMarkers(latitude,longitude,markersData)
        {
            var userid = [];
            var lat = [];
            var lng = [];
            // this variable sets the map bounds according to markers position
            var bounds = new google.maps.LatLngBounds();
            //console.log(markersData.length);
            $.each(markersData,function(i)
            {
                console.log(markersData);
                if(markersData[i].status=='1')
                {
                    if(!userid.includes(markersData[i].user_id))
                    {
                        userid.push(markersData[i].user_id);
                        var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
                        var name = markersData[i].title;
                        var image = '<img src="' + markersData[i].image + '" alt="" style="width:50px; height:auto; padding: 10px;">';
                        var link = markersData[i].link;
                        var p_type = markersData[i].p_type;
                        var price = markersData[i].price;
                        var lend_price = markersData[i].lendprice;
                        var shoplink = markersData[i].shop_link;
                        if (p_type == '3') {
                            console.log('Price:' + price);
                        }
                        createMarker(latlng, name, image, link, p_type, price, lend_price, shoplink);
                        bounds.extend(latlng);
                    }
                }
                else {
                    if(!lat.includes(markersData[i].lat) && !lng.includes(markersData[i].lng))
                    {
                        lat.push(markersData[i].lat);
                        lng.push(markersData[i].lng);
                        var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
                        var name = markersData[i].title;
                        var image = '<img src="' + markersData[i].image + '" alt="" style="width:50px; height:auto; padding: 10px;">';
                        var link = markersData[i].link;
                        var p_type = markersData[i].p_type;
                        var price = markersData[i].price;
                        var lend_price = markersData[i].lendprice;
                        var shoplink = markersData[i].shop_link;
                        if (p_type == '3') {
                            console.log('Price:' + price);
                        }
                        createMarker(latlng, name, image, link, p_type, price, lend_price, shoplink);
                        bounds.extend(latlng);
                    }
                }
            });
            //console.log(markersData.length);
            // for loop traverses markersData array calling createMarker function for each marker
            /*for (var i = 0; i < markersData.length; i++){
                var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
                var name = markersData[i].title;
                var image = '<img src="'+markersData[i].image+'" alt="" style="width:50px;">';
                var link = markersData[i].link;
                createMarker(latlng, name, image,link);
                bounds.extend(latlng);
               }*/
            map.fitBounds(bounds);

            // Finally the bounds variable is used to set the map bounds
            // with fitBounds() function

        }
                function createMarker(latlng, name, image, link, p_type, price, lend_price, shoplink) {
                    var marker = new google.maps.Marker({
                        map: map,
                        position: latlng,
                        title: name,
                        icon: icons['home'].icon
                });

                    // This event expects a click on a marker
                    // When this event is fired the Info Window content is created
                    // and the Info Window is opened.
                    google.maps.event.addListener(marker, 'click', function () {

                        // Creating the content to be inserted in the infowindow
                        if(p_type=='3')
                        {
                            var iwContent =  '<div id="content" style="width:100%;text-align:center;">'+
                                '<div id="siteNotice">'+
                                '</div>'+
                                '<h1 id="firstHeading" class="firstHeading" style="color:#ab8ce4;font-size:13px;"><i class="fa fa-repeat" style="color:#ab8ce4"></i>  '+ name +'</h1>'+
                                '<div id="bodyContent">'+
                                '<p style="color:#ab8ce4;"></p>' +
                                ''+
                                '<p><a href=' + link +'>'+
                                image +'</a> '+
                                '.</p>'+
                                    '<p style="color:#ab8ce4;">'+price+''+lend_price+'</p>'+
                                '<p style="text-align:right;font-size:16px;margin-bottom: 0rem !important;"><a href='+ shoplink +' style="text-align: right !important;"><i class="ti-arrow-top-right"></i></a></p>'+
                                '</div>'+
                                '</div>';
                        }
                        else
                        {
                            var iwContent =  '<div id="content" style="width:100%;text-align: center;">'+
                                '<div id="siteNotice">'+
                                '</div>'+
                                '<h1 id="firstHeading" class="firstHeading" style="color:#d01c76;font-size:13px;"><i class="ti-location-pin" style="color:#d01c76"></i>  '+ name +'</h1>'+
                                '<div id="bodyContent">'+
                                '<p style="color:#d01c76;"></p>' +
                                ''+
                                '<p><a href=' + link +'>'+
                                image +'</a> '+
                                '.</p>'+
                                '<p style="color:#d01c76">'+price+''+lend_price+'</p>'+
                               '<p style="text-align:right;font-size:16px;margin-bottom:0rem !important;"><a href='+ shoplink +' style="text-align: right !important;"><i class="ti-arrow-top-right"></i></a></p>'+
                                '</div>'+
                                '</div>';
                        }


                        // including content to the Info Window.
                        infoWindow.setContent(iwContent);

                        // opening the Info Window in the current map and at the current marker location.
                        infoWindow.open(map, marker);
                    });

                }

        </script>

@endsection
