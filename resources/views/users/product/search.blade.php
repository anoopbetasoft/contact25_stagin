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
  	@if($stopwordstatus!='yes')
		@if(count($product_list) > 0)
		<div class="col-lg-12">
		<h2>Search results for '{{$keyword}}'</h2>
		</div>
			@foreach($product_list as $p)
			@php
			$product_repeat = 0;
			$encoded = base64_encode($p['id']);
			$sell_to = explode(',',$p['p_sell_to']);  // converting to array for checking product sell to friend , neighbour
			$lend_to = explode(',',$p['p_item_lend_options']);   // converting to array 
			$p_slug =  "buy-".$p['p_slug']; $country = Auth::user()->country; $id = $p['id'];
			$country = str_replace(' ','-',$country);
			@endphp
			@if(in_array('1',$sell_to) || in_array('2',$sell_to) || in_array('3',$sell_to) || in_array('1',$lend_to) || in_array('2',$lend_to) || in_array('3',$lend_to) && $sellercountry[0]['country']==Auth::user()->country) 
			@php
			$product_repeat = $p['id'];
			@endphp
			<div class="col-lg-3">
	            <div class="card">
	            	<div class="white-box-pd buy-page" data-s_id="814440">
						<div class="product-img">

							@if($p['p_quantity'] < 1)

				                @if(empty($p['p_image']))
				                    <img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @else
				                    <?php $p_img_arr = explode(',', $p['p_image']);?>
				                    <img class="d-block w-100" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @endif
		                	
							@else 
							<a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>">
				                @if(empty($p['p_image']))
				                    <img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @else
				                    <?php $p_img_arr = explode(',', $p['p_image']);?>
				                    <img class="d-block w-100" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @endif
		                	</a>
							@endif

							
	            		</div>
		                <div class="card-body">
		                	@if($p['p_type'] == 3 ) <!-- subscription -->
			                	<!-- <span data-toggle="tooltip" data-placement="top" title=" Subsription">
			                		<i class="fa fa-archive"></i>
			                	</span> -->
			                	<span data-toggle="tooltip" data-placement="top" class="text-purple"  title="Subscription/Membership Price">
			                		<i class="fa fa-refresh"></i>  {{$p->currency->symbol}}@if($p['p_subs_price']=='0'){{'Free'}}@else{{$p['p_subs_price']}}@endif
			                		@if(!empty($p['p_price_per_optn']))
				                		@if($p['p_price_per_optn'] == 1)
				                			/ Day
				                		@elseif($p['p_price_per_optn'] == 2)
				                			/ Week
				                		@elseif($p['p_price_per_optn'] == 3)
				                			/ Month
				                		@else 
				                			/ Year
				                		@endif
			                		@endif
			                	</span>
		                	@elseif($p['p_type'] == 1) <!-- item-->
			                	<!-- <span data-toggle="tooltip" data-placement="top" title=" Item">
			                		<i class="fa fa-archive"></i>
			                	</span> -->
								@php
									$p['p_selling_price'] = number_format($p['p_selling_price'],$decimal_place[0]['decimal_places']);
                                    $p['p_selling_price'] = str_replace('.00','',$p['p_selling_price']);
								$p['p_lend_price'] = number_format($p['p_lend_price'],$decimal_place[0]['decimal_places']);
                                    $p['p_lend_price'] = str_replace('.00','',$p['p_lend_price']);
								@endphp
			                	<span data-toggle="tooltip" class="text-info" data-placement="top" title="Selling Price">
			                		<i class="fa fa-tag"></i>   {{$p->currency->symbol}}@if($p['p_selling_price']=='0'){{'Free'}}@else{{$p['p_selling_price']}}@endif
			                	</span>
			                	@if(!empty($p['p_item_lend_options']))
			                	<span data-toggle="tooltip" class="text-warning" data-placement="top" title="Lending Price">

			                		<i class="fa fa-refresh"></i>  {{$p->currency->symbol}}@if($p['p_lend_price']=='0'){{'Free'}}@else{{$p['p_lend_price']}}@endif
			                		@if(!empty($p['p_price_per_optn']))
				                		@if($p['p_price_per_optn'] == 1)
				                			/ Day
				                		@elseif($p['p_price_per_optn'] == 2)
				                			/ Week
				                		@elseif($p['p_price_per_optn'] == 3)
				                			/ Month
				                		@else 
				                			/ Year
				                		@endif
			                		@endif
			                	</span>
			                	@endif
		                	@elseif($p['p_type'] == 2) <!-- service-->
		                	
			                	<!-- <span data-toggle="tooltip" data-placement="top" title=" Membership">
			                		<i class="fa fa-archive"></i>
			                	</span> -->
								@php
									$p['p_selling_price'] = number_format($p['p_selling_price'],$decimal_place[0]['decimal_places']);
                                    $p['p_selling_price'] = str_replace('.00','',$p['p_selling_price']);
								@endphp
		                		<span data-toggle="tooltip" class="text-info" data-placement="top" title="Selling Price">
				                	<i class="fa fa-tag"></i> {{$p->currency->symbol}}@if($p['p_selling_price']=='0'){{'Free'}}@else{{$p['p_selling_price']}}@endif
				                </span>
			                	
		                	@endif
		                   	<hr>
		                    <h3 class="font-normal">
		                    	@if($p['p_quantity'] < 1) 
		                    	{{ucwords($p['p_title'])}}
		                    	<br>
		                    	<span class="text-danger"> Out of Stock </span>
		                    	@else
		                    	<a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>">{{ucwords($p['p_title'])}}</a>

		                    	@endif

		                    	<!-- <a href="{{--url('/product')--}}/{{--$p_slug--}}">{{--ucwords($p['p_title'])--}}</a> -->
		                    	<!-- <a href="{{--url('/products_page')--}}/{{--$p['id']--}}/{{--$p['user_id']--}}">{{--ucwords($p['p_title'])--}}</a> -->
		                    </h3>

		                    <p class="m-b-0 m-t-10">
		                    	@if(strlen($p['p_description']) > 35)
		                    		<span data-toggle="tooltip" data-placement="top" title="{{$p['p_description']}}">{{substr($p['p_description'], 0, 35)}}</span>...
		                    	@else
		                    		{{substr($p['p_description'], 0, 35)}}
		                    	@endif
		                    </p>
		                </div>
		            </div>
	            </div>
	        </div>
	        @elseif(in_array('4',$sell_to) || in_array('4',$lend_to)  && $sellercountry[0]['country']==Auth::user()->country && $product_repeat != $p['id'])  
	        <div class="col-lg-3">
	            <div class="card">
	            	<div class="white-box-pd buy-page" data-s_id="814440">
						<div class="product-img">

							@if($p['p_quantity'] < 1)

				                @if(empty($p['p_image']))
				                    <img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @else
				                    <?php $p_img_arr = explode(',', $p['p_image']);?>
				                    <img class="d-block w-100" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @endif
		                	
							@else 
							<a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>">
				                @if(empty($p['p_image']))
				                    <img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @else
				                    <?php $p_img_arr = explode(',', $p['p_image']);?>
				                    <img class="d-block w-100" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="{{$p['p_title']}} image1" style="width: 100%;object-fit: contain;">
				                @endif
		                	</a>
							@endif

							
	            		</div>
		                <div class="card-body">
		                	@if($p['p_type'] == 3 ) <!-- subscription -->
			                	<!-- <span data-toggle="tooltip" data-placement="top" title=" Subsription">
			                		<i class="fa fa-archive"></i>
			                	</span> -->
								@php
									$p['p_subs_price'] = number_format($p['p_subs_price'],$decimal_place[0]['decimal_places']);
                                    $p['p_subs_price'] = str_replace('.00','',$p['p_subs_price']);
								@endphp
			                	<span data-toggle="tooltip" data-placement="top" class="text-purple"  title="Subscription/Membership Price">
			                		<i class="fa fa-refresh"></i>  {{$p->currency->symbol}}@if($p['p_subs_price']=='0'){{'Free'}}@else {{$p['p_subs_price']}}@endif
			                		@if(!empty($p['p_price_per_optn']))
				                		@if($p['p_price_per_optn'] == 1)
				                			/ Day
				                		@elseif($p['p_price_per_optn'] == 2)
				                			/ Week
				                		@elseif($p['p_price_per_optn'] == 3)
				                			/ Month
				                		@else 
				                			/ Year
				                		@endif
			                		@endif
			                	</span>
		                	@elseif($p['p_type'] == 1) <!-- item-->
			                	<!-- <span data-toggle="tooltip" data-placement="top" title=" Item">
			                		<i class="fa fa-archive"></i>
			                	</span> -->
								@php
									$p['p_selling_price'] = number_format($p['p_selling_price'],$decimal_place[0]['decimal_places']);
                                    $p['p_selling_price'] = str_replace('.00','',$p['p_selling_price']);
								$p['p_lend_price'] = number_format($p['p_lend_price'],$decimal_place[0]['decimal_places']);
                                    $p['p_lend_price'] = str_replace('.00','',$p['p_lend_price']);
								@endphp
			                	<span data-toggle="tooltip" class="text-info" data-placement="top" title="Selling Price">
			                		<i class="fa fa-tag"></i>   {{$p->currency->symbol}}@if($p['p_selling_price']=='0'){{'Free'}}@else{{$p['p_selling_price']}}@endif
			                	</span>
			                	@if(!empty($p['p_item_lend_options']))
			                	<span data-toggle="tooltip" class="text-warning" data-placement="top" title="Lending Price">

			                		<i class="fa fa-refresh"></i>{{$p->currency->symbol}}@if($p['p_lend_price']=='0'){{'Free'}}@else{{$p['p_lend_price']}}@endif
			                		@if(!empty($p['p_price_per_optn']))
				                		@if($p['p_price_per_optn'] == 1)
				                			/ Day
				                		@elseif($p['p_price_per_optn'] == 2)
				                			/ Week
				                		@elseif($p['p_price_per_optn'] == 3)
				                			/ Month
				                		@else 
				                			/ Year
				                		@endif
			                		@endif
			                	</span>
			                	@endif
		                	@elseif($p['p_type'] == 2) <!-- service-->
		                	
			                	<!-- <span data-toggle="tooltip" data-placement="top" title=" Membership">
			                		<i class="fa fa-archive"></i>
			                	</span> -->
								@php
									$p['p_selling_price'] = number_format($p['p_selling_price'],$decimal_place[0]['decimal_places']);
                                    $p['p_selling_price'] = str_replace('.00','',$p['p_selling_price']);
								@endphp
		                		<span data-toggle="tooltip" class="text-info" data-placement="top" title="Selling Price">
				                	<i class="fa fa-tag"></i>{{$p->currency->symbol}}@if($p['p_selling_price']=='0'){{'Free'}}@else{{$p['p_selling_price']}}@endif
				                </span>
			                	
		                	@endif
		                   	<hr>
		                    <h3 class="font-normal">
		                    	@if($p['p_quantity'] < 1) 
		                    	{{ucwords($p['p_title'])}}
		                    	<br>
		                    	<span class="text-danger"> Out of Stock </span>
		                    	@else
		                    	<a href="<?php echo url($p_slug.'-'.$country.'/'.$encoded) ?>">{{ucwords($p['p_title'])}}</a>

		                    	@endif

		                    	<!-- <a href="{{--url('/product')--}}/{{--$p_slug--}}">{{--ucwords($p['p_title'])--}}</a> -->
		                    	<!-- <a href="{{--url('/products_page')--}}/{{--$p['id']--}}/{{--$p['user_id']--}}">{{--ucwords($p['p_title'])--}}</a> -->
		                    </h3>

		                    <p class="m-b-0 m-t-10">
		                    	@if(strlen($p['p_description']) > 35)
		                    		<span data-toggle="tooltip" data-placement="top" title="{{$p['p_description']}}">{{substr($p['p_description'], 0, 35)}}</span>...
		                    	@else
		                    		{{substr($p['p_description'], 0, 35)}}
		                    	@endif
		                    </p>
		                </div>
		            </div>
	            </div>
	        </div>
	        @endif
			@endforeach
		</div>
		<div class="row">
			<div class="col-md-12">
				{{ $product_list->links() }}
			</div>
		</div>
		@else
		<div class="row">
			<div class="col-lg-12">
				<h1>We're sorry</h1>
				<p>We can't seem to find any products which match your search for '{{$keyword}}'</p>
			</div>
		</div>
		@endif
	@else
	<div class="col-lg-12">
 		<h1>We're sorry</h1>
		<p>We can't seem to find any products which match your search for '{{$keyword}}'</p>
	</div>
	@endif	

@endsection