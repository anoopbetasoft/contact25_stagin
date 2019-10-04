@extends('layouts.customer')


@section('content')
    @if(session()->has('message3'))
    <div class="alert alert-success">
        {{ session()->get('message3') }}
    </div>
    @elseif(session()->has('message'))
    <div class="alert alert-danger">
        {{ session()->get('message') }}
    </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>    
    @endif
    @if(count($product_list) > 0)
        <div class="row">
        @foreach($product_list as $p)
            <div class="col-lg-3">
                <div class="card">
                    <div class="white-box-pd buy-page">
                        <div class="product-img">
                        @if(empty($p['p_image']))
                            <img src="{{asset('assets/images/logo-balls.png')}}" alt="{{$p['p_title']}} image1" style="width: 100%">
                        @else
                            <?php 
                            $p_img_arr = explode(',', $p['p_image']);
                            //echo "<pre>";print_r($p_img_arr); echo "</pre>";
                            if(count($p_img_arr) > 1){
                            ?>
                            <div id="carouselExampleControls{{$p['id']}}" class="carousel slide" data-ride="carousel" style="width: 200px;margin: 0 auto;">
                                <div class="carousel-inner">
                                    @foreach($p_img_arr as $key=>$img)
                                    <div class="carousel-item @if($key == 0) active @endif">
                                        <img class="d-block w-100" src='{{asset("uploads/products/$img")}}' alt="{{$p['p_title']}} image{{$key+1}}" style="width: 100%; margin: 0 auto;">
                                    </div>
                                    @endforeach
                                </div>

                                <a class="carousel-control-prev" href="#carouselExampleControls{{$p['id']}}" role="button" data-slide="prev" style="left: 10px;">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls{{$p['id']}}" role="button" data-slide="next" style="right: 10px;">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <?php }else{ ?>
                                <img class="" src='{{asset("uploads/products/$p_img_arr[0]")}}' alt="{{$p['p_title']}} image1" style="width: 100%">
                            <?php }?>
                        @endif
                        </div>
                        <div class="card-body">
                            <hr>
                            <h3 class="font-normal">{{$p['p_title']}}</h3>
                            <p class="m-b-0 m-t-10">{{$p['p_description']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        <div class="row">
            <div class="col-md-12">
                {{ $product_list->links() }}
            </div>
        </div>
    @else
    <div class="row">
        <div class="col-lg-4">

            No products are added yet.

        </div>
    </div>
    @endif
@endsection