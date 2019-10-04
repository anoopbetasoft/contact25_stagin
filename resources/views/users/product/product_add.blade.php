@extends('layouts.customer')


@section('content')

<div class="row">
    
    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>    
    @endif

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-info">
                <h4 class="m-b-0 text-white">Add new product</h4>
            </div>
            <div class="card-body">
                <form id="add_product" method="POST" action="{{url('/product_add_process')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        
                        <div class="row p-t-20">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Title</label>
                                    <input type="text" name="p_title" class="form-control" placeholder="Product Title" required> 
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Quantity</label>
                                    <input type="number" name="p_quantity"  class="form-control" placeholder="Quantity" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Product Type</label>
                                    @include('users.product.product_add.product_type', ['p_types' => $p_types])
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group pro_type_lend item_div">
                                    <label class="control-label">Lend To</label><Br/>
                                    <select class="selectpicker" multiple data-style="form-control btn-secondary" name="p_lend_to[]" onchange="product_lend_to(this);">  

                                        @foreach($p_items_option as $round => $otpns)
                                        <option value="{{$otpns['id']}}"  @if($round==0) selected @endif data-selected_option="{{$otpns['type']}}">{{$otpns['display_text']}}</option>
                                        @endforeach    
                                        
                                    </select>
                                </div>
                                <div class="form-group service_div" style="display: none">
                                    <label class="control-label">Service</label><Br/>
                                    <select class="selectpicker" data-style="form-control btn-secondary" name="serviceoption" onchange="service_type_selected(this);">
                                        <option disabled="disabled" selected="selected"> Select </option> 
                                        @foreach($p_service_option  as $series=> $otpns)
                                            
                                            <option id="serviceRadio{{$series}}" value="{{$otpns['id']}}" data-selected_option="{{$otpns['type']}}" data-service_type = "{{$otpns['type']}}_service"  class="service_otn_select">{{$otpns['display_text']}}</option>
                                        @endforeach
                                        <input type="hidden" name="p_service_option" value="">
                                    </select>
                                    <!-- @foreach($p_service_option  as $series=> $otpns)
                                    <div class="custom-control custom-radio listingradio">
                                        <input type="radio" id="serviceRadio{{$series}}" name="serviceoption" class="custom-control-input" data-service_type = "{{$otpns['type']}}_service" onclick="service_type_selected(this);" value="{{$otpns['id']}}" name="p_serive_type">
                                        <label class="custom-control-label" for="serviceRadio{{$series}}">
                                            {{$otpns['display_text']}}
                                        </label>
                                        <input type="hidden" name="p_service_option" value="">
                                    </div>
                                    @endforeach -->
                                </div>
                                <div class="form-group subs_member_div" style="display: none">
                                    <label class="control-label">Subscription</label><Br/>
                                    <select class="selectpicker" data-style="form-control btn-secondary" name="subsoption" onchange="subscription_type_selected(this);">

                                       <option disabled="disabled" selected="selected"> Select </option> 
                                        @foreach($p_subscription_option as $series=> $otpns)
                                            
                                            <option id="subsRadio{{$series}}" value="{{$otpns['id']}}" data-subs_type = "{{$otpns['type']}}_subs">{{$otpns['display_text']}}</option>
                                        @endforeach
                                        <input type="hidden" name="p_subscription_option" value="">
                                    </select>
                                    <!-- @foreach($p_subscription_option as $series=> $otpns)
                                    <div class="custom-control custom-radio listingradio">
                                        <input type="radio" id="subsRadio{{$series}}" name="serviceoption" class="custom-control-input" data-subs_type = "{{$otpns['type']}}_subs" onclick="subscription_type_selected(this);" value="{{$otpns['id']}}">
                                        <label class="custom-control-label" for="subsRadio{{$series}}" name="p_subs_type">
                                            {{$otpns['display_text']}}
                                        </label>
                                        <input type="hidden" name="p_subscription_option" value="">
                                    </div>
                                    @endforeach -->
                                </div>
                                
                            </div>
                            
                        </div>
                        

                        <!--/row-->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Product Quality</label>
                                    @include('users.product.product_add.product_quality')
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Sell Product To</label>
                                    @include('users.product.product_add.product_sell_to')
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label selling_product_price">Selling Price</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-info" type="button">{{$currency_symbol[0]->symbol}}</button>
                                    </div>
                                    <input type="number"  class="form-control" placeholder="Price" name="p_sell_price">
                                    <div class="input-group-prepend custom_dropdown_width subsc_price_optn" style="display:none">
                                        <select class="selectpicker" data-style="form-control btn-secondary"  name="p_sub_per_optn">
                                            <option value="1">Per Day</option>
                                            <option value="2">Per Week</option>
                                            <option value="3">Per Month</option>
                                            <option value="4">Per Year</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                      
                        <!-- item type product -->
                        <div class="main_row_item">
                            <div class="row">
                                @include('users.product.product_add.product_item')
                            </div>
                        </div>
                        <!-- subscription type product -->
                        <div class="main_row_subs_member"  style="display: none">
                            <div class="row">
                                @include('users.product.product_add.product_subscription')
                            </div>
                        </div>

                        <!-- service type product -->
                        <div class="main_row_service"  style="display: none">
                            <div class="row">
                                @include('users.product.product_add.product_service')
                            </div>
                        </div>
                        <!-- preview slider -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="imgPrev" style="width:300px;margin:0 auto;">
                                    <img src="#" id="displaySingle"  style="display: none">
                                    <div id="carouselProControls" class="carousel slide" data-ride="carousel" style="display: none">
                                        <div class="carousel-inner">
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselProControls" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselProControls" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                                <!-- <img id="blah" src="#" alt="your image" width="100%"/> -->
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="p_description"  class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20 item_div">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Choose the room you're storing in</label>
                                <select class="selectpicker" data-style="form-control btn-secondary" name="p_room">
                                    @foreach($p_room as $room)
                                    <option value="{{$room['id']}}"> {{$room['display_text']}} </option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Label a box</label>
                                <select class="selectpicker" data-style="form-control btn-secondary" name="p_box">
                                    <option value="1" data-content='<i class="ti-package"></i> Box 1'>Box 1</option>
                                    <option value="2" data-content='<i class="ti-package"></i> Box 2'>Box 2</option> 
                                    <option value="3" data-content='<i class="ti-package"></i> Box 3'>Box 3</option>  
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-info"> Add</button>
                        <button type="reset" class="btn btn-inverse">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            

@endsection