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
    <style>
        button.btn.btn-danger {
            margin-bottom: 10px;
        }




        @media (max-width:360px)
        {
                .btn-danger {
            color: #fff;
            background-color: #e46a76;
            border-color: #e46a76;
            font-size: 10px;
                padding: 5px;
           }


           span.font-normal {
           FONT-SIZE: 11PX;
           }
        }



    </style>
    <div class="row">
            @if(count($orderdetails) > 0)
                @foreach($orderdetails as $order)
                    @php
                    $return_history = App\return_history::where('order_id',$order['id'])->get();
                    @endphp
            @if(count($return_history)>'0')
                <div class="col-lg-6">
                    <div class="card">
                        <div class="white-box-pd buy-page">
                            <div class="row">
                            <div class="col-lg-4">
                                <div class="product-img">
                                    @if(!empty($order['product_details']['p_image']))
                                    @php $p_img_arr = explode(',', $order['product_details']['p_image']); @endphp
                                    <img class="d-block w-100" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="Second slide" style="width: 100%;cursor: pointer;">
                                    @else
                                    <img src="{{asset('assets/images/logo-balls.png')}}" alt="product image" style="width: 100%;cursor: pointer;">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body">
                                    <h3>{{$order['product_details']['p_title']}}</h3>
                                    <hr>
                                    <p>
                                        <span class="font-normal">Placed on : </span>
                                        <span class="text-success">{{date_format($order['created_at'] , 'M d, Y H:i a')}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Order # : </span><span class="text-success">{{$order['id']}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Quantity : </span><span class="text-success">{{$order['o_quantity']}}</span>
                                    </p>
                                    <p>
                                        @php
                                            $order['o_total'] = number_format($order['o_total'],$decimal_place[0]['decimal_places']);
                                            $order['o_total'] = str_replace('.00','',$order['o_total']);
                                        @endphp
                                         <span class="font-normal">Total : </span><span class="text-success">{{$order->currency->symbol}}{{$order['o_total']}}</span>
                                        
                                    </p>
                                    <p>
                                         <span class="font-normal">Delivery Charge : </span><span class="text-success">{{$order->currency->symbol}}{{$order['o_delivery_charge']}}</span>
                                        
                                    </p>
                                    <p>
                                         <span class="font-normal">Delivery Provider : </span>
                                         @if($order['o_delivery_provider']=='inpost')
                                         <span class="text-success">Inpost Next Day</span>
                                         @elseif($order['o_delivery_provider']=='0')
                                         <span class="text-success">None</span>
                                         @elseif($order['o_delivery_provider']!='0' && $order['o_delivery_provider']!='inpost')
                                         <span class="text-success">{{$order->deliveryprovider->delivery_provider}}</span>
                                         @endif
                                        
                                    </p>
                                    @if($order['o_collection_time']!='' && $order['o_product_type']=='2')
                                    <p>
                                        <span class="font-normal">Service Time  : </span><span class="text-success">{{$order['o_collection_time']}}</span>
                                    </p>
                                    @elseif($order['o_collection_time']!='' && $order['o_product_type']!='2')
                                    <p>
                                        <span class="font-normal">Collection Time  : </span><span class="text-success">{{$order['o_collection_time']}}</span>
                                    </p>
                                   @elseif($order['o_tracking_no']!='')
                                    <p>
                                        <span class="font-normal">Tracking Link : </span><span class="text-success">{{$order->tracking_link->tracking_url}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Tracking No : </span><span class="text-success">{{$order['o_tracking_no']}}</span>
                                    </p>
                                    @endif
                                    <p>
                                        <span class="font-normal">Return Type :</span>
                                            @if($return_history[0]['return_type']=='1')
                                            <span class="text-success">Damaged Product</span>
                                        @else
                                            <span class="text-success">Other Reason</span> @endif

                                    </p>
                                    <p>
                                        <span class="font-normal">Return Reason :</span>

                                            <span class="text-success">{{$return_history[0]['return_reason']}}</span>

                                    </p>
                                    <p>
                                        <span class="font-normal">Actions :</span>
                                        <div id="returnrequest{{$return_history[0]['id']}}">
                                        @if($return_history[0]['return_status']=='2')
                                        @if(Auth::user()->country=='United Kingdom' && Auth::user()->inpost_return=='1' && Auth::user()->return_label_status=='1')
                                        <button class="btn btn-success" onclick="acceptrequestinpost('{{$return_history[0]['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                                            Accept & Generate Label
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @else
                                            <button class="btn btn-success" onclick="acceptrequest('{{$return_history[0]['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                                                Accept & Provide Address
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger" onclick="declinerequest('{{$return_history[0]['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                                            Decline
                                            <i class="fas fa-window-close"></i>
                                        </button>
                                        @elseif($return_history[0]['return_status']=='0')
                                            Return Request Rejected
                                        @elseif($return_history[0]['return_status']=='1')
                                            <button class="btn btn-success" onclick="refunditem('{{$return_history[0]['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                                               Click if you received item
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @elseif($return_history[0]['return_status']=='3')
                                            @php
                                                $refundamount = App\refund_history::where('order_id',$order['id'])->get();
                                                 $refundeddate = date('d F Y hA',strtotime($refundamount[0]['created_at']));
                                                 $refundmessage = 'Return Request successful '.$refundamount[0]['currency_symbol'].$refundamount[0]['amount'].' Refunded '.$refundeddate;
                                            @endphp
                                            {{$refundmessage}}
                                        @endif
                                    </div>
                                    </p>
                                    @if($order['o_returned']=='0' && $order['o_cancelled']=='0' && $order['o_collect_status']=='0' && $order['o_completed']=='0' && $order['o_not_delivered']=='0' && $order['o_delivered']=='0')
                                        <div id="action{{$order['id']}}">
                                    @if($order['o_dispatched']=='0' && $order['o_collect_status']=='0' && $order['o_completed']=='0' &&  $order['o_cancelled']=='0')
                                    <div id="cancelorder{{$order['id']}}"><span class="font-normal">Cancel Order : </span><button class="btn btn-danger" onclick="buyercancel('{{$order['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                            Cancel
                            <i class="fas fa-window-close"></i>
                        </button></div>
                                    @endif
                                    @if($order['o_dispatched']!='0' && $order['o_returned']=='0')

                        <div id="productdelivered{{$order['id']}}"><span class="font-normal">Mark It As Delivered : </span><button class="btn btn-danger" onclick="productdelivered('{{$order['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                            Mark It As Delivered
                            <i class="fas fa-window-close"></i>
                        </button></div>
                          @php
                          $orderdate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                          $claimingdate = date('Y-m-d H:i:s',strtotime($orderdate. ' + '.$system_setting[0]['no_of_day_for_claim']. 'days'));
                          $claimingdateformat = $order['created_at'];
                          $addaystoclaimingdate = date_add($claimingdateformat,date_interval_create_from_date_string($system_setting[0]['no_of_day_for_claim']." days"));
                          $day_diff = strtotime(date('Y-m-d H:i:s')) - strtotime($orderdate);
                          $day_diff = abs(round($day_diff/86400));
                          @endphp
                         @if(strtotime(date('Y-m-d H:i:s')) > strtotime($claimingdate))
                        <div id="claimitnotdelivered{{$order['id']}}"><span class="font-normal">Claim It As Not Delivered : </span><button class="btn btn-danger" onclick="claimitnotdelivered('{{$order['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                            Not Delivered ?
                            <i class="fas fa-window-close"></i>
                        </button></div>
                          @else
                             <input type="hidden" id="ordered_on{{$order['id']}}" value="{{date_format($order['created_at'] , 'dS M Y')}}">
                             <input type="hidden" id="delivered_by{{$order['id']}}" value="{{date_format($addaystoclaimingdate , 'dS M Y')}}">
                             <input type="hidden" id="days{{$order['id']}}" value="{{$system_setting[0]['no_of_day_for_claim']}}">
                             <input type="hidden" id="day_diff{{$order['id']}}" value="{{$day_diff}}">
                           <div id="notreceived{{$order['id']}}"><span class="font-normal">Not Received ? </span><button class="btn btn-danger" onclick="notreceived('{{$order['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                                                        Not Received ?
                                                        <i class="fas fa-window-close"></i>
                                                    </button></div>
                                                <div id="claimitnotdelivered{{$order['id']}}"><span class="font-normal">Claim It As Not Delivered : </span><button class="btn btn-danger" onclick="claimitnotdelivered('{{$order['id']}}');"> <!-- If the user is seller and item is not collected or dispatched -->
                                                        Not Delivered ?
                                                        <i class="fas fa-window-close"></i>
                                                    </button></div>
                             @endif
                                    @endif
                                        </div>
                                        @endif

                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @endforeach
            @else
                <p>No orders found</p>
            @endif     
        </div>
   
   
@endsection