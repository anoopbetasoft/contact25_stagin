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
        
        
        <div class="row display_my_sales">
            @if(count($soldItems) > 0)
                @foreach($soldItems as $sales)
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="white-box ribbon-wrapper card">
                        <div class="ribbon ribbon-corner ribbon-right ribbon-info" style="font-size:10px; padding-top:24px; padding-right:17px;">
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
                                <img class="d-block w-100" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="{{$sales['product_details']['p_title']}}" min-height="200px;">
                            @else
                                <img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$sales['product_details']['p_title']}}"  min-height="200px;">
                            @endif
                            
                        </div> 
                        <h1>{{$sales->currency->symbol}} {{$sales['o_purchased_for']}}
                            <!--isbn code-->
                           <!--  <span style="font-size: 12px">
                                <small class="text-muted db"> 8717868117642 </small>
                            </span> -->
                        </h1>
                        <div style="height:55px;">
                            <h3 class="box-title m-b-0">
                                {{$sales['product_details']['p_title']}}
                            </h3>
                        </div>
                        <div style="padding:5px"></div>
                        <a class="btn btn-warning btn-block waves-effect waves-light" style="height: 60px;font-size:30px;" href="{{route('inpost-label',['order_id'=>$sales['id']])}}">INPOST 
                            <i class="fas fa-shipping-fast"></i>
                        </a>
                        <a href="" class="btn btn-dark  btn-block waves-effect waves-light ">
                            Where is my inpost locker?
                        </a>
                        <a class="btn btn-danger btn-block waves-effect waves-light" style="height: 60px;font-size:30px;">
                            Royal Mail 
                            <i class="fas fa-shipping-fast"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <p>You haven't made any sales yet.</p>
            @endif
        </div>
   
   
@endsection