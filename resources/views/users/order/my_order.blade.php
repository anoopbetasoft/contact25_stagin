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
    <div class="row">
            @if(count($orderdetails) > 0)
                @foreach($orderdetails as $order)
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
                                        <span class="font-normal">Order # : </span><span class="text-success">{{$order['order_id']}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Quantity : </span><span class="text-success">{{$order['o_quantity']}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Total : </span><span class="text-success">{{$order->currency->symbol}} {{$order['o_purchased_for']}}</span>
                                    </p>
                                    <p>
                                        <span class="font-normal">Order Status : </span>
                                        
                                        @if($order['o_dispatched']==0)
                                        <button class="btn btn-warning" >Processing</button>
                                        @elseif($order['o_dispatched'] != 0)
                                        <button class="btn btn-purple">Dispatched</button>
                                        @elseif($order['o_shipped'] != 0)
                                        <button class="btn btn-info">Dispatched</button>
                                        @elseif($order['o_complete'] != 0)
                                        <button class="btn btn-success">Dispatched</button>
                                        @endif
                                        
                                    </p>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p>No orders found</p>
            @endif     
        </div>
   
   
@endsection