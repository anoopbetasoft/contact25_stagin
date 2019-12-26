@extends('layouts.customer')


@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <div class="row page-titles">
        <div class="col-md-12 align-self-center text-right">
                        <span style="float: left;" class="text-themecolor">
                            <nav class="breadcrumb filter1" style="font-size: 15px; padding-top: 5px;">
                                    <a class="breadcrumb-item text-danger ordersfilter pending_orders active"
                                       href="javascript:void(0)" onclick="sorting('pending');" value="pending">Pending</a>
                                 <a class="breadcrumb-item text-danger ordersfilter dispatched"
                                    href="javascript:void(0)" onclick="sorting('dispatched');" value="dispatched">Dispatched</a>
                                    <a class="breadcrumb-item text-danger ordersfilter cancelled_orders"
                                       href="javascript:void(0)" onclick="sorting('cancelled'); " value="cancelled">Cancelled</a>
                                    <a class="breadcrumb-item text-danger ordersfilter completed_orders"
                                       href="javascript:void(0)" onclick="sorting('completed');" value="completed">Completed</a>
                            </nav>
                        </span>
            <div class="btn-group" style="float: right;">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <i class="fa fa-sort-alpha-asc"></i>
                </button>
                <div class="dropdown-menu" x-placement="bottom-start"
                     style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item sort" href="javascript:void(0)" data-sort_by="s_s_id"
                       onclick="sorting('all');">All</a>
                    <a class="dropdown-item sort" href="javascript:void(0)" data-sort_by="s_s_id"
                       onclick="sorting('item');">Item</a>
                    <a class="dropdown-item sort" href="javascript:void(0)" data-sort_by="s_s_id DESC"
                       onclick="sorting('service');">Service</a>
                    <a class="dropdown-item sort" href="javascript:void(0)"
                       data-sort_by="s_price_buy_inc_vat ASC" onclick="sorting('subscription');">Subscription</a>
                    <a class="dropdown-item sort" href="javascript:void(0)" data-sort_by="s_price_buy_inc_vat DESC"
                       onclick="sorting('collection');">Collection</a>
                </div>
            </div>


        </div>
    </div>
    <div class="row display_my_sales">
        @if(count($soldItems) > 0)
            @php
                $itemcounter = 0;
                $itemdispatchcounter = 0;
                $serviceitemcounter = 0;
                $subscriptioncounter = 0;
            @endphp
            @foreach($soldItems as $key => $sales)
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <?php
                        //$datearray = explode('-',$sales['o_collection_time']);
                        //print_r($datearray);
                        //$month = $datearray[1];
                        //$day = explode(' ',$datearray[2]);
                        //$dayname = $day[0];
                        //$starttime = $day[1];
                        //$endtime = explode(' ',$datearray[5]);
                        //$endtime = $endtime[0];
                        //$orderlog = orders_log::where('order_id',$sales['id'])->get();

                        ?>
                        <div class="itemtime">
                            <input type="hidden" class="itemcollectiontime" value="123">
                        </div>
                    <div class="white-box ribbon-wrapper card">
                        @if($sales['o_product_type']=='1') <!-- Count down timer for item -->
                        @if($sales['o_collection_time']!='' && $sales['o_collect_status']=='0' && $sales['o_cancelled']=='0' && $sales['o_completed']=='0')
                           @php
                            $collect_time = explode(' ',$sales['o_collection_time']);
                            //dd($collect_time);
                            $start = date('d M y ga',strtotime($collect_time[0].''.$collect_time[1]));
                            $end = date('ga',strtotime($collect_time[4]));
                            //$message = 'Collection Overdue (Due '.$start.'-'.$end.')';
                            $message = 'Collection Overdue (Due '.$start.'-'.$end.')';
                            $message2 = 'Collection '.$start.'-'.$end;
                           //$collect_time[2] = str_replace(' ','',$collect_time[2]);
                            @endphp
                            <p id="collectiontime-{{$itemcounter}}">{{$message2}}</p>
                            <div id="itemcounter-{{$itemcounter}}" class="itemcounter-class" style="margin-left: 2px;"></div>
                            <input type="hidden" id="itemcounter-class-{{$itemcounter}}" value="{{$collect_time[0].' '.$collect_time[1].':00'}}">
                            <input type="hidden" id="collectitemmessage-{{$itemcounter}}" value="{{$message}}">

                            @php
                            $itemcounter++;
                            @endphp
                           @elseif($sales['o_collection_time']=='' && $sales['o_dispatched']=='0' && $sales['o_delivered']=='0' && $sales['o_completed']=='0')
                            @php
                                $time = date('Y-m-d H:i:s',strtotime($sales['created_at'].'+1 Days'));

                               //$collect_time[2] = str_replace(' ','',$collect_time[2]);
                            @endphp
                                <div id="itemdispatchcounter-{{$itemdispatchcounter}}" class="itemdispatchcounter-class" style="margin-left: 2px;"></div>
                                <input type="hidden" id="itemdispatchcounter-class-{{$itemdispatchcounter}}" value="{{$time}}">
                            {{--<div class="counter-class" data-date="{{$collect_time[0].' '.$collect_time[1].':00'}}">--}}
                            {{--<div class="itemdispatchcounter-class itemdispatchcounter-{{$itemdispatchcounter}}" data-date="{{$time}}" style="display: inline-block;">
							<i class="fa fa-truck" style="color:#9675ce"></i>
                                <div style="display: inline-block;"><span class="counter-days"></span> Days</div>
                                <div style="display: inline-block;"><span class="counter-hours"></span> Hrs</div>
                                <div style="display: inline-block;"><span class="counter-minutes"></span> Min</div>
                                <div style="display: inline-block;"><span class="counter-seconds"></span> Sec</div>
                            </div>--}}
                            @php
                                $itemdispatchcounter++;
                            //$itemcounter++;
                            @endphp
                        @endif
                        @elseif($sales['o_product_type']=='2' && $sales['o_cancelled']=='0' && $sales['o_completed']=='0')  <!-- Countdown timer for service -->
                        @php
                            $time = '';
                            $splitslot = explode(',',$sales['o_collection_time']);
                            for($i=0;$i<count($splitslot);$i++)
                            {
                                $splitstartend = explode(' ',$splitslot[$i]);
                               if(strtotime(date('Y-m-d H:i:s')) < strtotime(date('Y-m-d H:i:s',strtotime($splitstartend[0].' '.$splitstartend[1].':00'))))
                                {
                                    $time = $splitstartend[0].' '.$splitstartend[1].':00';
                                    break;
                                }
                            }
                        @endphp
                            <div id="serviceitemcounter-{{$serviceitemcounter}}" class="serviceitemcounter-class" style="margin-left: 2px;"></div>
                            <input type="hidden" id="demotimeserviceitemcounter-{{$serviceitemcounter}}" value="{{$time}}">
                           {{-- <div class="serviceitemcounter-class serviceitemcounter-{{$serviceitemcounter}}" data-date="{{$time}}" style="display: inline-block;">
                                <i class="fa fa-user" style="color:#00c292"></i>
                                <div style="display: inline-block;"><span class="counter-days"></span> Days</div>
                                <div style="display: inline-block;"><span class="counter-hours"></span> Hrs</div>
                                <div style="display: inline-block;"><span class="counter-minutes"></span> Min</div>
                                <div style="display: inline-block;"><span class="counter-seconds"></span> Sec</div>
                            </div>--}}
                            @php
                                $serviceitemcounter++;
                            //$itemcounter++;
                            @endphp
                        @elseif($sales['o_product_type']=='3') <!-- Countdown timer for subscription -->
                            @if($sales['o_collection_time']!='' && $sales['o_collect_status']=='0' && $sales['o_cancelled']=='0' && $sales['o_completed']=='0')
                                @php
                                    $collect_time = explode(' ',$sales['o_collection_time']);
                                   //$collect_time[2] = str_replace(' ','',$collect_time[2]);
                                @endphp
                               <div id="subscriptioncounter-class-{{$subscriptioncounter}}" class="subscriptioncounter-class" style="margin-left: 2px;"></div>
                                <input type="hidden" id="subscriptioncounter-{{$subscriptioncounter}}" value="{{$collect_time[0].' '.$collect_time[1].':00'}}">
                                {{--<div class="counter-class" data-date="{{$collect_time[0].' '.$collect_time[1].':00'}}">--}}
                               {{-- <div class="subscriptioncounter-class subscriptioncounter-{{$subscriptioncounter}}" data-date="{{$collect_time[0].' '.$collect_time[1].':00'}}" style="display: inline-block;">
                                    <i class="fa fa-map-marker text-success" style="color:#00c292"></i>
                                    <div style="display: inline-block;"><span class="counter-days"></span> Days</div>
                                    <div style="display: inline-block;"><span class="counter-hours"></span> Hrs</div>
                                    <div style="display: inline-block;"><span class="counter-minutes"></span> Min</div>
                                    <div style="display: inline-block;"><span class="counter-seconds"></span> Sec</div>
                                </div>--}}
                                @php
                                    $subscriptioncounter++;
                                //$itemcounter++;
                                @endphp
                            @elseif($sales['o_collection_time']=='' && $sales['o_dispatched']=='0' && $sales['o_cancelled']=='0' && $sales['o_completed']=='0')
                                @php
                                    $time = date('Y-m-d H:i:s',strtotime($sales['created_at'].'+1 Days'));
                                   //$collect_time[2] = str_replace(' ','',$collect_time[2]);
                                @endphp
                                {{--<div id="demo-{{$itemcounter}}"></div>--}}
                                <div id="subscriptioncounter-class-{{$subscriptioncounter}}" class="subscriptioncounter-class" style="margin-left: 2px;"></div>
                                <input type="hidden" id="subscriptioncounter-{{$subscriptioncounter}}" value="{{$time}}">
                                {{--<div class="counter-class" data-date="{{$collect_time[0].' '.$collect_time[1].':00'}}">--}}
                               {{-- <div class="subscriptioncounter-class subscriptioncounter-{{$subscriptioncounter}}" data-date="{{$time}}" style="display: inline-block;">
                                    <i class="fa fa-truck" style="color:#9675ce"></i>
                                    <div style="display: inline-block;"><span class="counter-days"></span> Days</div>
                                    <div style="display: inline-block;"><span class="counter-hours"></span> Hrs</div>
                                    <div style="display: inline-block;"><span class="counter-minutes"></span> Min</div>
                                    <div style="display: inline-block;"><span class="counter-seconds"></span> Sec</div>
                                </div>--}}
                                @php
                                    $subscriptioncounter++;
                                //$itemcounter++;
                                @endphp
                                @endif
                        @endif
                        <div class="ribbon ribbon-corner ribbon-right ribbon-info"
                             style="font-size:10px; padding-top:24px; padding-right:17px;">
                            <i class="fas fa-times"> <span style="font-size:20px;">{{$sales['o_quantity']}}</span></i>
                        </div>
                        <div style="padding:10px"></div>
                        <div style="text-align:center;">
                            <!-- <span class="text-purple" style="font-size:25px; color:grey;"><i class="fa fa fa-truck"></i> <span class="text-purple" style="font-size:12px;">Post in</span>
                                <div class="text-danger deadline_date_time" data-deadline="2019-04-18 23:59:59" style="font-size:18px; font-weight:bold;">
                                    Overdue
                                </div>
                                <span class="text-purple" style="font-size:35px; color:grey;  display:none;"><i class="fa fa fa-truck"></i> 
                                    <span class="text-danger" style="font-size:25px;">5h 7m 5s</span>
                                        <div class="product-text" style="border-top:0px;">
                                    </div>
                                    <div style="padding:15px"></div>
                                </span>
                            </span> -->
                        </div>
                        <div class="product-img">
                            @if(!empty($order['product_details']['p_image']))
                                @php $p_img_arr = explode(',', $order['product_details']['p_image']); @endphp
                                <img class="d-block w-100" src='{{asset("uploads/products/$p_img_arr[0]")}}'
                                     alt="{{$sales['product_details']['p_title']}}" min-height="200px;">
                            @else
                                <img src="{{asset('assets/images/logo-balls.png')}}"
                                     alt="{{$sales['product_details']['p_title']}}" min-height="200px;">
                            @endif

                        </div>
                        @php
                            $sales['o_purchased_for'] =  (float) ($sales['o_purchased_for']);
                                $sales['o_purchased_for'] = number_format($sales['o_purchased_for'],$decimal_place[0]['decimal_places']);
                            $sales['o_purchased_for'] = str_replace('.00','',$sales['o_purchased_for']);
                        @endphp
                        <h1>{{$sales->currency->symbol}}{{$sales['o_purchased_for']}}
                        <!--isbn code-->
                            <!--  <span style="font-size: 12px">
                                 <small class="text-muted db"> 8717868117642 </small>
                             </span> -->
                        </h1>
                    <!--  <h3>{{$sales->o_collection_time}}</h3> -->
                        <div style="height:55px;">
                            <h3 class="box-title m-b-0">
                                {{$sales['product_details']['p_title']}}
                            </h3>
                        </div>
                        <div style="padding:5px"></div>
                        @if($sales['o_dispatched']=='1' && $sales['o_parcel_id']=='0')
                            <div id="trackstatus{{$sales['id']}}">
                                <p>

                                    <span class="font-normal">Tracking Link : </span><span class="text-success"
                                                                                           id="tracking_url{{$sales['id']}}">{{$sales['tracking_link']['tracking_url']}}</span>
                                </p>
                                <p>
                                    <span class="font-normal">Tracking No : </span><span class="text-success"
                                                                                         id="tracking_no['id']}}">{{$sales['o_tracking_no']}}</span>
                                </p>
                            </div>
                        @endif
                        @if(Auth::user()->inpost_status=='1' && $sales['o_product_type']=='1' && $sales['o_collection_time']=='' && $sales['o_cancelled']=='0' && $sales['o_completed']=='0' && $sales['o_dispatched']=='0')
                            <div id="inpostoption{{$sales['id']}}">
                                <a class="btn btn-warning btn-block waves-effect waves-light"
                                   style="height: 60px;font-size:30px;"
                                   href="{{route('inpost-label',['order_id'=>$sales['id']])}}">INPOST
                                    <i class="fas fa-shipping-fast"></i>
                                </a>
                                <a href="" class="btn btn-dark  btn-block waves-effect waves-light ">
                                    Where is my inpost locker?
                                </a>
                            </div>
                        @endif
                        @if(count($delivery)>'0' && $sales['o_product_type']=='1' && $sales['o_collection_time']=='' && $sales['o_cancelled']=='0' && $sales['o_completed']=='0' && $sales['o_dispatched']=='0')
                            <a class="btn btn-danger btn-block waves-effect waves-light"
                               style="height: 60px;font-size:30px;background-color: #9675ce;color: #fff;border-color: #9675ce;" id="royalmail{{$sales['id']}}" data-toggle="modal"
                               data-target="#exampleModal{{$sales['id']}}">
                                DISPATCH
                                <i class="fas fa-shipping-fast"></i>
                            </a>
                        @endif
                        @if($sales['o_product_type']=='1' && $sales['o_collection_time']!='' && $sales['o_cancelled']=='0')
                            <a class="btn btn-success btn-block waves-effect waves-light"
                               style="height: 60px;font-size:30px;" id="collected{{$sales['id']}}"
                               @if($sales['o_collect_status']!='1') onclick="collected('{{$sales['userDetails']['name']}}','{{$sales['id']}}');" @endif>
                                Collected
                                <i class="fas fa-check-circle"></i>
                            </a>
                        @endif

                        @if($sales['o_product_type']!='1' && $sales['o_collection_time']!='' && $sales['o_cancelled']=='0')
                            <a class="btn btn-success btn-block waves-effect waves-light"
                               style="height: 60px;font-size:30px;" id="completed{{$sales['id']}}"
                               @if($sales['o_completed']!='1')onclick="completed('{{$sales['userDetails']['name']}}','{{$sales['id']}}');" @endif>
                                Completed
                                <i class="fas fa-check-circle"></i>
                            </a>
                        @endif
                        @if($sales['seller_id']==Auth::user()->id && $sales['o_collect_status']=='0' && $sales['o_dispatched']=='0' && $sales['o_completed']=='0')
                            @if($sales['o_cancelled']=='0')
                                <a class="btn btn-warning btn-block waves-effect waves-light"
                                   style="height: 60px;font-size:30px;" id="cancel{{$sales['id']}}"
                                   onclick="sellercancel('{{$sales['id']}}');">
                                    <!-- If the user is seller and item is not collected or dispatched -->
                                    Cancel
                                    <i class="fas fa-window-close"></i>
                                </a>
                            @elseif($sales['o_cancelled']!='0')
                                <a class="btn btn-danger btn-block waves-effect waves-light"
                                   style="height: 60px;font-size:30px;" id="cancel{{$sales['id']}}">
                                    <!-- If the user is seller and item is not collected or dispatched -->
                                    Cancelled
                                </a>
                            @endif
                        @endif

                    </div>
                </div>
            @endforeach
        @else
            <p>You haven't made any sales yet.</p>
        @endif
    </div>
    <!-- Modal -->
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    @foreach($soldItems as $sale)
        <div class="modal fade" id="exampleModal{{$sale['id']}}" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tracking Link</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form name="tracking_link" id="tracking_link{{$sale['id']}}">
                        @csrf
                        <div class="modal-body">
                            <label> Tracking Link </label>
                            <select class="form-control" name="o_tracking_link" id="o_tracking_link{{$sale['id']}}">
                                <option value="">Select Tracking Link</option>
                                @foreach($delivery as $deliveryname)
                                    <option value="{{$deliveryname->id}}" @if($sale->o_tracking_no==$deliveryname->id)
                                        "selected" @endif>{{$deliveryname->tracking_url}}</option>
                                @endforeach
                            </select>
                            <label> Tracking No </label>
                            <input type="text" name="o_tracking_no" class="form-control"
                                   id="o_tracking_no{{$sale['id']}}" placeholder="Tracking Number"
                                   value="{{$sale->o_tracking_no}}">
                            <input type="hidden" name="id" value="{{$sale['id']}}">
                            <label>UPLOAD IMAGE OF TRACKING NO</label>
                            <input type="file" name="image" id="image">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            $('#tracking_link{{$sale['id']}}').submit(function (event) {
                $('#exampleModal{{$sale['id']}}').modal('toggle');
                event.preventDefault();
                //event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '{{ url('/update_tracking_link') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {
                        if (data.success == 0) { //error
                            Swal.fire("Error", data.message, "warning");
                        }
                        if (data.success == 1) { //settings updated
                            $('#inpostoption{{$sale['id']}}').css('display', 'none');
                            $('#royalmail{{$sale['id']}}').css('display', 'none');
                            $('#cancel{{$sale['id']}}').css('display', 'none');
                            $('#tracking_url{{$sale['id']}}').css('display', 'none');
                            $('#tracking_no{{$sale['id']}}').html(data.tracking_no);
                            Swal.fire('Success', 'Tracking Number Added Successfully');
                            location.reload();
                        }
                    },
                    error: function (data) {
                        Swal.fire('Error occured while inserting data');
                    }
                });

            });
            $(".itemtime").each(function (index) {
                console.log(index + ": " + $(this).children('.itemcollectiontime').val());
            });

        </script>

    @endforeach


@endsection