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
    @php
    $count = 0;
    @endphp
    <div class="row">
            @if(count($orderdetails) > 0)
                @foreach($orderdetails as $order)
                    @php
                if($order['p_price_per_optn']=='1') // if subscription is for day basis
                {
                $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$order['o_subs_period'].' days'));
                }
                else if($order['p_price_per_optn']=='2') // if subscription is for day basis
                {
                $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $period = $order['o_subs_period'] * 7;
                $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$period.' days'));
                }
                else if($order['p_price_per_optn']=='3') // if subscription is for day basis
                {
                $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$order['o_subs_period'].' months'));
                }
                else if($order['p_price_per_optn']=='4') // if subscription is for day basis
                {
                $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$order['o_subs_period'].' year'));
                }
                @endphp
                @if(strtotime(date('Y-m-d H:i:s')) < strtotime($renewdate))
                    @php
                    $count++;
                    @endphp
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
                                        <span class="font-normal">Subscribed User : </span>
                                        <span class="text-success">{{$order['userDetails']['name']}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Subscription Start Date : </span>
                                        <span class="text-success">{{date('d-m-Y H:i:s',strtotime($order['created_at']))}}</span>
                                    </p>
                                    @php
                                    if($order['p_price_per_optn']=='1') // if subscription is for day basis
                                    {
                                         $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                                        // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                                        $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$order['o_subs_period'].' days'));
                                    }
                                    else if($order['p_price_per_optn']=='2') // if subscription is for day basis
                                    {
                                         $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                                        // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                                        $period = $order['o_subs_period'] * 7;
                                        $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$period.' days'));
                                    }
                                    else if($order['p_price_per_optn']=='3') // if subscription is for day basis
                                    {
                                        $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                                       // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                                        $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$order['o_subs_period'].' months'));
                                    }
                                    else if($order['p_price_per_optn']=='4') // if subscription is for day basis
                                    {
                                         $subscriptiondate = date('Y-m-d H:i:s',strtotime($order['created_at']));
                                        // $renewdate = date('Y-m-d',strtotime("+'".$subscriptionvalue['o_subs_period']."' months", strtotime($subscriptionvalue['created_at'])));
                                        $renewdate = date('Y-m-d H:i:s', strtotime($subscriptiondate. ' + '.$order['o_subs_period'].' year'));
                                    }
                                    $nextrenewdate = date('d-m-Y H:i:s', strtotime($renewdate. ' + 1 days'));
                                    @endphp
                                    <input type="hidden" id="nextrenew{{$order['id']}}" value="{{$nextrenewdate}}">
                                    <p>
                                        <span class="font-normal">Subscription End Date : </span>
                                        <span class="text-success">{{date('d-m-Y H:i:s',strtotime($renewdate))}}</span>
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
                                   {{-- <p>
                                        <span class="font-normal">Collection Time  : </span><span class="text-success">{{$order['o_collection_time']}}</span>
                                    </p>--}}
                                   @elseif($order['o_tracking_no']!='')
                                    <p>
                                        <span class="font-normal">Tracking Link : </span><span class="text-success">{{$order->tracking_link->tracking_url}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Tracking No : </span><span class="text-success">{{$order['o_tracking_no']}}</span>
                                    </p>
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
        @if($count=='0' && count($orderdetails)>'0')
            <p>No orders found</p>
        @endif
        </div>
   
   
@endsection