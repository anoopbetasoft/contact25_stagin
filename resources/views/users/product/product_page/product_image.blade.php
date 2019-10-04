<?php 
#convert images to array
$imgArr = explode(',', $product_details[0]['p_image']); 

?>

@if(!empty($imgArr[0]) && count($imgArr) > 1)
<!--Carousel Wrapper-->
<div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
	<div class="carousel-inner" role="listbox">
		@foreach($imgArr  as $img_no => $imgs )
		<div class="carousel-item @if($img_no == 0)  active  @endif">
			<img class="d-block w-100 product_slider_img" src='{{asset("uploads/products/$imgs")}}' alt="{{$product_details[0]['p_title']}} image{{$img_no+1}}">
		</div>
		@endforeach
	</div>
	<a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
	<!--/.Controls-->
	<ol class="carousel-indicators" style="position: initial;">
		@foreach($imgArr as $img_no => $imgs)

		<li data-target="#carousel-thumb" data-slide-to="{{$img_no}}" class="@if($img_no == 0)  active  @endif" style="width:75px"> 
			<img class="d-block w-100 img-fluid" src='{{asset("uploads/products/$imgs")}}' alt="{{$product_details[0]['p_title']}} image{{$img_no+1}}" style="width: 100%;">
		</li>
		@endforeach
	</ol>
</div>
@elseif(!empty($imgArr[0]) && count($imgArr) == 1)
	<img class="d-block w-100 img-fluid" src='{{asset("uploads/products/$imgArr[0]")}}' alt="{{$product_details[0]['p_title']}} image1" style="object-fit: contain;">
@else
    <img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$product_details[0]['p_title']}} image1" style="width: 100%;">
@endif
