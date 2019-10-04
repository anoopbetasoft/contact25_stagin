
@php
$minute_diff = '';
$timeslot = '';
$address = $sellercountry[0]['street_address1'];
$pincode = $sellercountry[0]['pincode'];
$mapcountry = $sellercountry[0]['country'];
$title = "Buy ".$product_details[0]['p_title']." for ".$currency_symbol[0]->symbol."".$product_details[0]['p_selling_price']." | ".$sellercountry[0]['street_address1']." ".$sellercountry[0]['country']."";
if($product_details[0]['p_description']!='')
{
    $description = $product_details[0]['p_description'];
}
else
{
    if(Auth::user()->country==$sellercountry[0]['country'] && $open_hrs_status == 1) //if delivery and collection is available 
    {
    $description ="Buy ".$product_details[0]['p_title']." for ".$currency_symbol[0]->symbol." ".$product_details[0]['p_selling_price']." available for Collection from ".$sellercountry[0]['street_address1']." , ".$sellercountry[0]['country']." OR ".$sellercountry[0]['country']." Delivery" ;
    }
    elseif(Auth::user()->country==$sellercountry[0]['country'] && $open_hrs_status != 1) //if delivery is available
    {
        $description ="Buy ".$product_details[0]['p_title']." for ".$currency_symbol[0]->symbol." ".$product_details[0]['p_selling_price']." available for ".$sellercountry[0]['country']." Delivery";
    }
    elseif(Auth::user()->country!=$sellercountry[0]['country'] && $open_hrs_status == 1)  //if collection is available
    {
        $description ="Buy ".$product_details[0]['p_title']." for ".$currency_symbol[0]->symbol." ".$product_details[0]['p_selling_price']." available for Collection from ".$sellercountry[0]['street_address1']." , ".$sellercountry[0]['country']."";
    }
    else  // if nothing is available
    {

    }
}
$friendcheck = explode(',',$product_details[0]['p_sell_to']);
if(in_array('2',$friendcheck) || in_array('3',$friendcheck))
{
    $robot = 'yes';
}
else
{
    $robot = 'no';
}
@endphp
@extends('layouts.customer',['title'=>$title,'description'=>$description,'robot'=>$robot])



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

    <style type="text/css">
        #collect_item i.fas.fa-chevron-circle-left {
    color: #00c292;
    font-size: 30px;
    margin-bottom: 20px;
}
#deliver_item i.fas.fa-chevron-circle-left {
    color: #AB8CE4;
    font-size: 30px;
    margin-bottom: 20px;
}
 #card-image.american-express {
    background-position: 0 -370px;
}
#card_number.visa {
      background-position: 2px -160px, 330px -56px;
    }
  
    #card_number.valid.visa {
      background-position: 2px -160px, 330px -84px;
    }
  
    #card_number.mastercard {
      background-position: 2px -244px, 330px -56px;
    }
  
    #card_number.valid.mastercard {
      background-position: 2px -243px, 330px -84px;
    }
  
    #card_number.visa_electron {
      background-position: 2px -202px, 330px -56px;
    }
  
    #card_number.valid.visa_electron {
      background-position: 2px -202px, 330px -84px;
    }
  
    #card_number.maestro {
      background-position: 2px -286px, 330px -56px;
    }
  
    #card_number.valid.maestro {
      background-position: 2px -286px, 330px -84px;
    }
  
    #card_number.discover {
      background-position: 2px -328px, 330px -56px;
    }
  
    #card_number.valid.discover {
      background-position: 2px -328px, 330px -84px;
    }
    #card-image.master-card {
    background-position: 0 -281px;
}
#my-sample-form.master-card {
    color: #fff;
    background-color: #363636;
    background: -webkit-linear-gradient(335deg, #d82332, #d82332 50%, #f1ad3d 50%, #f1ad3d);
    background: linear-gradient(115deg, #d82332, #d82332 50%, #f1ad3d 50%, #f1ad3d);
}
#my-sample-form.visa {
    color: #fff;
    background-color: #0D4AA2;
}
#card-image.visa {
    background-position: 0 -398px;
}
#my-sample-form.discover {
    color: #fff;
    background-color: #ff6000;
    background: -webkit-linear-gradient(#d14310, #f7961e);
    background: linear-gradient(#d14310, #f7961e);
}
#card-image.discover {
    background-position: 0 -163px;
}#my-sample-form.unionpay, #my-sample-form.jcb, #my-sample-form.diners-club {
    color: #fff;
    background-color: #363636;
}#card-image.diners-club {
    background-position: 0 -133px;
}
#my-sample-form {
    background-color: #FFF;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
    padding: 3em 3em 2em;
    width: 80%;
    margin-bottom: 2em;
    -webkit-transition: all 600ms cubic-bezier(0.2, 1.3, 0.7, 1);
    transition: all 600ms cubic-bezier(0.2, 1.3, 0.7, 1);
    -webkit-animation: cardIntro 500ms cubic-bezier(0.2, 1.3, 0.7, 1);
    animation: cardIntro 500ms cubic-bezier(0.2, 1.3, 0.7, 1);
    z-index: 1;
}    
        .form-container {
    max-width: 500px;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    /* background-color: #EEE; */
    -webkit-box-pack: center;
    -webkit-justify-c-ontent: center;
    -ms-flex-pack: center;
    /* justify-content: center; */
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    position: relative;
}
#my-sample-form.unionpay, #my-sample-form.jcb, #my-sample-form.diners-club {
    color: #fff !important;
    background-color: #363636 !important;
}
.card-shape, #my-sample-form.visa, #my-sample-form.master-card, #my-sample-form.maestro, #my-sample-form.american-express, #my-sample-form.discover, #my-sample-form.unionpay, #my-sample-form.jcb, #my-sample-form.diners-club {
    border-radius: 6px;
    padding: 2em 2em 1em;
}.cardinfo-card-number, .cardinfo-card-number2, .cardinfo-exp-date, .cardinfo-cvv .postal-code {
    -webkit-transition: -webkit-transform 0.3s;
    transition: -webkit-transform 0.3s;
    transition: transform 0.3s;
    transition: transform 0.3s, -webkit-transform 0.3s;
}

.cardinfo-card-number, .cardinfo-card-number2, .cardinfo-exp-date, .cardinfo-cvv .postal-code {
    -webkit-transition: -webkit-transform 0.3s;
    transition: -webkit-transform 0.3s;
    transition: transform 0.3s;
    transition: transform 0.3s, -webkit-transform 0.3s;
}
.cardinfo-card-number {
    position: relative;
}#my-sample-form.unionpay, #my-sample-form.jcb, #my-sample-form.diners-club {
    color: #fff !important;
    background-color: #363636 !important;
}
.input-wrapper {
    border-radius: 2px !important;
    background: rgba(255, 255, 255, 0.86) !important;
    height: 2.75em !important;
    border: 1px solid #eee !important;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.06) !important;
    padding: 5px 10px !important;
    margin-bottom: 1em !important;
}


#card-image.jcb {
    background-position: 0 -221px;
}
#card-image {
    position: absolute;
    top: 2em;
    right: 1em;
    width: 44px;
    height: 28px;
    background-image: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/346994/card_sprite.png) !important;
    background-size: 86px 458px;
    border-radius: 4px;
    background-position: -100px 0;
    background-repeat: no-repeat;
    margin-bottom: 1em;
}
#my-sample-form.american-express {
    color: #fff;
    background-color: #007CC3;
}
.cardinfo-wrapper {
    display: flex;
    }
    .cardinfo-exp-date {
    margin-right: 10px;
}
i.fas.fa-tags {
    position: absolute;
    z-index: 999;
    color: #fff;
    line-height: 25px;
    padding: 5px;
}
.list-group-item {
     border: 0px solid rgba(0, 0, 0, 0.125); 
}

.filter-option-inner-inner{
    font-family: "Poppins" , sans-serif;
}
.dropdown-item.active, .dropdown-item:active {
 background-color: unset;
}
a.text-info:focus, a.text-info:hover {
    color: #0286c1!important;
}
div#dropin-container {
    padding-left: 20px;
}
#my-sample-form {
    padding: 2em 2em 2em;
    width: 100%;}
    .payment_div
    {
        padding-top: 30px;
    }
@media (max-width:420px){
#my-sample-form {
    padding: 1em 1em 1em !important;
    width: 100% !important;
}
}
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <div class="">
                 <h2 class="m-b-0 m-t-0" style="cursor: pointer;">{{$product_details[0]['p_title']}}
                        @if($product_details[0]['p_type'] == 1)                               
                           <span style="font-size:14px;"  class="@if($product_details[0]['p_quality'] == 1) text-success @elseif($product_details[0]['p_quality'] == 2) text-info @elseif($product_details[0]['p_quality'] == 3) text-info @else  text-warning @endif"> (New) </span> 
                        @endif 
                    </h2> 
                    @if(isset($main_heading_day))
                    @if($product_details[0]['p_type']=='2')
                    @php
                    $heading = 'Book';
                    @endphp
                    @else
                    @php
                    $heading = 'Collect';
                    @endphp
                    @endif
                    <?php
                    if(strtotime($timeslot_heading['start_time']) >= strtotime($timeslot_heading['end_time']))
                    {
                        foreach($collect_dates as $daykey => $newday)
                        {
                            if($daykey!='2')
                            {
                                if($main_heading_day != $newday['day'])
                                {
                                    $main_heading_day = $newday['day'];
                                    $timeslot_heading['start_time'] = date('g:ia',strtotime($newday['start_time']));
                                    $timeslot_heading['end_time'] = date('g:ia',strtotime($newday['end_time']));
                                }
                                else
                                {
                                
                                }
                            }
                            else
                            {
                                break;
                            }
                        }
                    }
                    ?>
                    {{$timeslot}}
                    <small class="text-success db collect_buy_message"><strong>{{$heading}}</strong> from <strong><span class="text-info" style="cursor: pointer;">*{{$main_heading_day}}*</span> </strong> between <strong style="cursor: pointer;">{{$timeslot_heading['start_time']}} and {{$timeslot_heading['end_time']}}</strong></small>
                    @else
                    <small class="text-success db collect_buy_message"></small>    
                    @endif
                    <hr>

                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="white-box text-center"> 
                                @include('users.product.product_page.product_image', ["product_details[0]['p_image']" => $product_details[0]['p_image']]) 
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-6">
                            <form id="add_to_cart" method="POST" action="{{url('/saveOrder')}}">
                                @csrf

                                <div class="alwayshidden" style="display: none">
                                    <h4><b>Added By : </b>{{$product_details[0]['userDet']['email']}}</h4>
                                    <h4><b>Contact : </b>{{$product_details[0]['userDet']['contact_code']}} - {{$product_details[0]['userDet']['contact_no']}}</h4>
                                    <h4> product type : {{$product_details[0]['p_type']}}</h4>
                                </div>
                                <input type="hidden" name="selling_price" value="{{$product_details[0]['p_selling_price']}}" >
                                <input type="hidden" name="subscribe_price" value="{{$product_details[0]['p_subs_price']}}" >
                                <input type="hidden" name="lending_price" value="{{$product_details[0]['p_lend_price']}}" >
                                <input type="hidden" name="lending_per_option" value="{{$product_details[0]['p_price_per_optn']}}" >
                                <input type="hidden" name="seller_id" value="{{$product_details[0]['userDet']['id']}}" >
                                <input type="hidden" name="product_id" value="{{$product_details[0]['id']}}" >
                                <input type="hidden" name="product_type" value="{{$product_details[0]['p_type']}}" >
                                <input type="hidden" name="currency" value="{{$product_details[0]['code']}}">

                                <!-- purchased value/sub total -->
                                @if($product_details[0]['p_type'] == 1)
                                <input id="purchase_val" type="hidden" value="{{$product_details[0]['p_selling_price']}}" name="purchase_val">
                                @else
                                <input id="purchase_val" type="hidden" value="{{$product_details[0]['p_selling_price']}}" name="purchase_val">
                                @endif
                                <!-- bill details -->
                                <input type="hidden" id="clientToken" name="clientToken" value="{{$clientToken}}">
                                <input type="hidden" id="braintree_id" name="braintree_id" value="{{$braintree_customer_id}}">
                                <input type="hidden" id="initial_token" name="initial_token" value="{{Braintree_ClientToken::generate()}}">
                                


                                <div class="row p-t-20">
                                    @if(!empty($product_details[0]['p_description']))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <!--  <label class="control-label" style="cursor: pointer;">Description</label> -->
                                            <span class="text-success" style="font-size:28px;cursor: pointer;">Description</span>
                                            <h4>{{$product_details[0]['p_description']}}</h4>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="quantity" @if($product_details[0]['p_type'] == 1) data-style="btn-info btn-secondary"  @elseif($product_details[0]['p_type'] == 2) data-style="btn-success btn-secondary" @else data-style="btn-purple btn-secondary" @endif class="selectpicker" id="p_quantity" onchange="quantity_change(this);">
                                                <?php for ($i=1; $i <= $product_details[0]['p_quantity'] ; $i++) { ?>
                                                    <option value="{{$i}}">Qty : {{$i}}</option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- purchasing options types -->
                                    @if(!empty($product_details[0]['p_item_lend_options']) && !empty($product_details[0]['p_lend_price']))
                                    <div class="col-md-4 lending_options" style="display: none">
                                        <div class="form-group">
                                            
                                            <select name="buy_product_option"  data-style="btn-warning btn-secondary" class="selectpicker" style="font-family: Poppins, sans-serif;">
                                                @include('users.product.product_page.purchase_type_lend', ['product_details[0]["p_price_per_optn"]' => $product_details[0]['p_price_per_optn']])
                                            </select>
                                        </div>
                                    </div>
                                    @endif


                                    @if($product_details[0]['p_type'] == 3)
                                    <div class="col-md-4 subscription_options">
                                        <div class="form-group">
                                           
                                            <select name="buy_product_option" data-style="btn-purple btn-secondary" class="selectpicker" style="font-family:Poppins, sans-serif;" onchange="subscribeOption(this)">
                                                @include('users.product.product_page.purchase_type_subs', ['product_details[0]["p_price_per_optn"]' => $product_details[0]['p_price_per_optn']])
                                            </select>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- purchasing options -->
                                    <div class="col-md-4">
                                        
                                        <div class="form-group">
                                             <input type="hidden" name="service_time" value="{{$product_details[0]['service_time']}}">
    <input type="hidden" name="service_time_type" value="{{$product_details[0]['service_time_type']}}">
    <input type="hidden" name="currency_symbol" value="{{$currency_symbol[0]->symbol}}">
                                            <select name="buy_product_option" @if($product_details[0]['p_type'] == 1) data-style="btn-info btn-secondary"  @elseif($product_details[0]['p_type'] == 2) data-style="btn-success btn-secondary" @else data-style="btn-purple btn-secondary" @endif class="selectpicker fa" id="purchase_type" style="font-family:Poppins, sans-serif;" onchange="buyOption_change(this);">

                                                @include('users.product.product_page.purchase_option', [

                                                'product_details[0]["p_type"]' => $product_details[0]['p_type'],

                                                'product_details[0]["p_selling_price"]' => $product_details[0]['p_selling_price'], 

                                                'product_details[0]["p_item_lend_options"]' =>  $product_details[0]['p_item_lend_options'], 

                                                'product_details[0]["p_lend_price"]' => $product_details[0]['p_lend_price'], 

                                                'product_details[0]["p_subs_price"]' => $product_details[0]['p_subs_price']

                                                ])
                                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <!-- collection type -->
                                     @foreach($sellercountry as $country)                            
                                    @if($open_hrs_status == 1 && Auth::user()->country==$country->country && $product_details[0]['p_type']!='2')
                                    <div class="row row-in" id="buttons" style="widows: 100%">
                                        @if($open_hrs_status == 1)
                                        
                                        <div class="col-lg-4 col-sm-7 row-in-br collect_it_select" style="cursor:pointer;">
                                       <div class="col-in row">
                                                <div class="col-md-6 col-sm-6 col-xs-6"><i class="fa fa-map-marker text-success" style="color:#00c292"></i>
                                                    <h5 class="text-muted vb">Collect it</h5>
                                                    </div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <h3 class="counter text-right m-t-15" style="font-size:26px;color:#00c292">{{$currency_symbol[0]->symbol}}FREE</h3>  
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    @if($product_details[0]['p_type']=='2')
                                                    <button class="btn btn-lg btn-success waves-effect waves-light m-t-20" type="button" style="width:100%"  data-hide_coll_type="deliver_item" data-show_coll_type = "collect_item" onclick="collection_type(this);" >
                                                        <i class="fa fa-map-marker"></i> Book Slot
                                                    </button>
                                                    @else
                                                    <button class="btn btn-lg btn-success waves-effect waves-light m-t-20" type="button" style="width:100%"  data-hide_coll_type="deliver_item" data-show_coll_type = "collect_item" onclick="collection_type(this);" >
                                                        <i class="fa fa-map-marker"></i> Collect 
                                                    </button>
                                                    @endif
                                                </div>
                                            </div> 
                                        </div>
                                        @endif
                                        @if(Auth::user()->country==$country->country && $product_details[0]['p_type']!='2')
                                        <div class="col-lg-4 col-sm-7  b-r-none deliver_it_select" style="cursor:pointer;">
                                            <div class="col-in row">
                                                <div class="col-md-6 col-sm-6 col-xs-6"> <i class="fa fa-truck" style="color:#9675ce"></i>
                                                    <h5 class="text-muted vb">Deliver it</h5>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <h3 class="counter text-right m-t-15 text-purple" style="font-size:22px;color:#9675ce">+ {{$currency_symbol[0]->symbol}}3.59</h3>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <button class="btn btn-lg btn-purple waves-effect waves-light m-t-20 delivery_it_select"  type="button" style="width:100%;background-color:#9675ce; color:white;" data-hide_coll_type="collect_item" data-show_coll_type="deliver_item" onclick="collection_type(this);">
                                                        <i class="fa fa-truck"></i> Deliver 
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                         <div class="col-lg-4 col-sm-7  b-r-none deliver_it_select" style="cursor:pointer;">
                                            <div class="col-in row">
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    @endforeach

                                    <!-- collection info -->
                                    @foreach($sellercountry as $country)                            
                                    @if(Auth::user()->country==$country->country)
                                    @if($product_details[0]['p_type']=='2')
                                    <div class="col-md-12" class="collection_info" id="collect_item"  
                                    style="display:block" >
                                    @else
                                    <div class="col-md-12" class="collection_info" id="collect_item"  
                                    style="display:none;" >
                                    @endif
                                    @else
                                    <div class="alert-danger">
                                        unfortunately you can not buy this product because you live in the {{Auth::user()->country}} and this item is for sale in {{$country->country}} only.
                                    </div>
                                    @if($product_details[0]['p_type']=='2')
                                    <div class="col-md-12" class="collection_info" id="collect_item"  
                                    style="display:block;" >
                                    @else
                                    <div class="col-md-12" class="collection_info" id="collect_item"  
                                    style="display:none" >
                                    @endif
                                    @endif
                                    @endforeach
                                    @foreach($sellercountry as $country)                            
                                    @if($open_hrs_status == 1 && Auth::user()->country==$country->country && $product_details[0]['p_type']!='2')
                                    <i class="fas fa-chevron-circle-left" onclick="hidecollectitem();" style="cursor: pointer;"></i><p id="collectiondate"></p>
                                    @endif
                                    @endforeach
                                        <div style="cursor: pointer;">
                                            <i class="fa fa-map-marker text-success" style="font-size:56px;color:#00c292"></i>
                                            @if($product_details[0]['p_type']=='2')
                                             <span class="text-success" style="font-size:28px;"> 
                                                Book Slot
                                            @else
                                            <span class="text-success" style="font-size:28px;"> 
                                                Collect it
                                            </span>
                                            @endif
                                        </div><BR>
                                        @if($product_details[0]['p_type']=='2')
                                        <h3 style="cursor: pointer;">Choose <span class="text-success" style="font-weight:bold;">Service</span> Time</h3>
                                        @else
                                       <h3 style="cursor: pointer;">Choose <span class="text-success" style="font-weight:bold;">Collection</span> Time</h3>
                                        @endif
                                        <!-- collection slots -->
                                        <div class="col-md-12 collection_slots"  @if($open_hrs_status == 1 ) style="display:block" @else style="display: none" @endif>
                                            <div class="list-group">
                                                <?php //echo "<pre>";print_r($collect_dates); echo "</pre>"; 
                                               
                                                ?>
                                                @foreach($collect_dates as $time_count => $data)
                                                    @include('users.product.product_page.collection_slots', 
                                                    [
                                                    'data["start_time"]' => $data['start_time'],
                                                    'data["end_time"]' => $data['end_time'], 
                                                    'time_count' =>  $time_count, 
                                                    'data["day"]' => $data['day'], 
                                                    'data["raw_date"]' => $data["raw_date"],
                                                    ])
                                                @endforeach 

                                            </div>

                                            <!-- <p class="lead">
                                                <button type="button" class="btn btn-success btn-lg" onclick="showAll_slots(this);"><span class="fa fa-angle-down"></span></button>
                                            </p> -->
                                        </div>
                                        <!-- conditions  for collection-->
                                        
                                         
                                        <div class="controls collection_control" style="display: none;">
                                           <h3>Check before Completing Order</h3>
                                            <!-- <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="x" name="timecheck_collect"  class="custom-control-input collect_condition" id="customCheck2" aria-invalid="false">
                                                    <label class="custom-control-label" for="customCheck2" style="cursor: pointer;">I will collect the <b>{{$product_details[0]['p_title']}}</b> <span class="text-success final_collect_time"></span></label>
                                                </div>
                                            </fieldset> -->
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="y" name="directioncheck_collect" class="custom-control-input collect_condition" id="customCheck3" aria-invalid="false">
                                                    @if($product_details[0]['p_type']=='2' && $product_details[0]['p_radius']!='')
                                                    <label class="custom-control-label" for="customCheck3" style="cursor: pointer;">I confirm the service will take place at {{Auth::user()->street_address1}},{{Auth::user()->pincode}}</label>
                                                    @elseif($product_details[0]['p_type']=='2' && $product_details[0]['p_radius']=='')
                                                    <label class="custom-control-label" for="customCheck3" style="cursor: pointer;">I've checked the directions and know how to get there <span class="text-success"><a href="https://www.google.com/maps?q=directions+to+{{$address}}+{{$pincode}}+{{$mapcountry}}" target="blank">check directions</a></span></label>
                                                    @else
                                                    <label class="custom-control-label" for="customCheck3" style="cursor: pointer;">I've checked the directions and know how to get there <span class="text-success"><a href="https://www.google.com/maps?q=directions+to+{{$address}}+{{$pincode}}+{{$mapcountry}}" target="blank">check directions</a></span></label>
                                                    @endif
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="y" name="returncheck_collect" class="custom-control-input collect_condition" id="customCheck4" aria-invalid="false">
                                                    @if($product_details[0]['p_type']=='2')
                                                    <label class="custom-control-label" for="customCheck4" style="cursor: pointer;">7 Day Money Back Guarantee - if I'm not satisfied with my service, I can claim a refund within 7 days <a href="{{url('/terms')}}" target="blank">Full Terms</a></label>
                                                    @else
                                                    <label class="custom-control-label" for="customCheck4" style="cursor: pointer;">30 Day Money Back Guarantee - if I'm not satisfied, I can return within 30 days <a href="{{url('/terms')}}" target="blank">Terms</a></label>
                                                	@endif
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                   
                            </form>
                         </div> 
                         <!--delivery info-->
                                    @foreach($sellercountry as $country)                            
                                    @if($open_hrs_status != 1 && Auth::user()->country==$country->country && $product_details[0]['p_type']!='2')
                                    </div>
                                    <div class="col-md-12 collection_info" id="deliver_item" 
                                    style="display:block">
                                    @else
                                    <div class="col-md-12 collection_info" id="deliver_item" 
                                    style="display:none">
                                    @endif
                                    @endforeach
                                    @foreach($sellercountry as $country)                            
                                    @if(Auth::user()->country==$country->country && $product_details[0]['p_type']!='2' && $open_hrs_status=='1')
                                    <i class="fas fa-chevron-circle-left" onclick="hidedeliveritem();" style="cursor: pointer;"></i>
                                    @endif
                                    @endforeach
                                        <div style="cursor: pointer;">
                                            <i class="fa fa-truck text-purple" style="font-size:56px;color:#00c292"></i>
                                            <span class="text-purple" style="font-size:28px;"> 
                                                Deliver it
                                            </span>
                                        </div><br>
                                       <!--  <h3>Choose <strong class="text-purple">Delivery</strong> Time</h3> -->
                                        <!-- conditions for delivery -->
                                        <h3 style="cursor: pointer;">Check before Completing Order</h3>
                                        <div class="controls">
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="y" name="returncheck_deliver" class="custom-control-input deliver_condition" id="customCheck5" aria-invalid="false">
                                                    <label class="custom-control-label" for="customCheck5" style="cursor: pointer;">30 Day Money Back Guarantee - if I'm not satisfied, I can return within 30 days<a onclick="terms();">Terms</a></label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <!-- Payment Div -->
                                    <div class="col-md-12 payment_div"  style="display: none;">
                                  <div class="row">
                                     <!-- <div class="col-md-3"></div>  -->
                                  <div class="col-md-12">  
                                   
                                        <div id="dropin-container">

  <div class="bg-illustration">
    

  </div>
  <header>
    <h3>Payment</h3>
  </header>
  
<div class="form-container" id="new_card">
  <form id="my-sample-form" class="scale-down">
    <div class="cardinfo-card-number">
      <label class="cardinfo-label" for="card-number">Card Number</label>
      <div class='input-wrapper' id="card-number"></div>
      <div id="card-image"></div>
    </div>

    <div class="cardinfo-wrapper">
      <div class="cardinfo-exp-date">
        <label class="cardinfo-label" for="expiration-date">Valid Thru</label>
        <div class='input-wrapper' id="expiration-date"></div>
      </div>

      <div class="cardinfo-cvv">
        <label class="cardinfo-label" for="cvv">CVV</label>
        <div class='input-wrapper' id="cvv"></div>
      </div>
    </div>
   <input type="submit" class="btn btn-info" value="PAY NOW" id="button-pay" style="display:none;color: #fff;">
  </form>
</div>
    </div>
    <a class="btn btn-info" id="submit-button" style="color:#fff;display: none;">Pay Here</a>
                                </div>
                    </div>
                </div>
                                    
                                   
                                </div>

                                 <!-- <div class="col-md-12 payment_div"  style="display: none;">
                                  <div class="row">
                                     <div class="col-md-3"></div> 
                                  <div class="col-md-9">  
                                   
                                        <div id="dropin-container">

  <div class="bg-illustration">
    

  </div>
  <header>
    <h3>Payment</h3>
  </header>
  
<div class="form-container" id="new_card">
  <form id="my-sample-form" class="scale-down">
    <div class="cardinfo-card-number">
      <label class="cardinfo-label" for="card-number">Card Number</label>
      <div class='input-wrapper' id="card-number"></div>
      <div id="card-image"></div>
    </div>

    <div class="cardinfo-wrapper">
      <div class="cardinfo-exp-date">
        <label class="cardinfo-label" for="expiration-date">Valid Thru</label>
        <div class='input-wrapper' id="expiration-date"></div>
      </div>

      <div class="cardinfo-cvv">
        <label class="cardinfo-label" for="cvv">CVV</label>
        <div class='input-wrapper' id="cvv"></div>
      </div>
    </div>
   <input type="submit" class="btn btn-info" value="PAY NOW" id="button-pay" style="display:none;color: #fff;">
  </form>
</div>
	</div>
	<a class="btn btn-info" id="submit-button" style="color:#fff;display: none;">Pay Here</a>
                                </div>
                    </div>
                </div> -->  <!-- col - md - 6 ends here -->
                </div> <!-- Row Ends here -->
                </div>
            </div>
        </div>
    </div>
    <!-- <script type="text/javascript">
        var clientToken = document.querySelector("#clientToken").value;

        var braintree_id = document.querySelector("#braintree_id").value;

        var button = document.querySelector('#submit-button');
        
        var amount = document.querySelector('#purchase_val').value;
        

        if(clientToken == ""){
            
            braintree.dropin.create({
                authorization: "{{ Braintree_ClientToken::generate() }}",
                container: '#dropin-container'
            }, function (createErr, instance) {
                button.addEventListener('click', function () {
                    instance.requestPaymentMethod(function (err, payload) {
                        $.get('{{ route('payment.process') }}', {payload,amount,braintree_id}, function (response) {
                            console.log(response);
                            if (response.success) {

                                $('.preloader').css('display','block');
                                var braintree_customer_id = response.customer_id;
                                
                                $("input[name='braintree_id']").val(braintree_customer_id);
                                $("#add_to_cart").submit();
                            } else {
                                $('.preloader').css('display','none');
                                alert('Payment failed');
                            }
                        }, 'json');
                    });
                });
            });
        }else{
            
            braintree.dropin.create({
                authorization: "{{ $clientToken }}",
                container: '#dropin-container'
            }, function (createErr, instance) {
                button.addEventListener('click', function () {
                    instance.requestPaymentMethod(function (err, payload) {
                        $.get('{{ route('payment.process') }}', {payload,amount,braintree_id}, function (response) {
                            console.log(response);
                            if (response.success) {

                                $('.preloader').css('display','block');
                                //var braintree_customer_id = response.transaction['customer']['id'];
                                //$("input[name='braintree_customer_id']").val(braintree_customer_id);
                                $("#add_to_cart").submit();
                            } else {
                                $('.preloader').css('display','none');
                                alert('Payment failed');
                            }
                        }, 'json');
                    });
                });
            });
        }
    </script> -->
@endsection

