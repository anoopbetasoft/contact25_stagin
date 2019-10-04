@php
$address = $sellercountry[0]['street_address1'];

$title = "Buy ".$product_details[0]['p_title']." for £".$product_details[0]['p_selling_price']." | ".$sellercountry[0]['street_address1']." ".$sellercountry[0]['country']."";
if($product_details[0]['p_description']!='')
{
    $description = $product_details[0]['p_description'];
}
else
{
    if(Auth::user()->country==$sellercountry[0]['country'] && $open_hrs_status == 1) //if delivery and collection is available 
    {
    $description ="Buy ".$product_details[0]['p_title']." for £ ".$product_details[0]['p_selling_price']." available for Collection from ".$sellercountry[0]['street_address1']." , ".$sellercountry[0]['country']." OR ".$sellercountry[0]['country']." Delivery" ;
    }
    elseif(Auth::user()->country==$sellercountry[0]['country'] && $open_hrs_status != 1) //if delivery is available
    {
        $description ="Buy ".$product_details[0]['p_title']." for £ ".$product_details[0]['p_selling_price']." available for ".$sellercountry[0]['country']." Delivery";
    }
    elseif(Auth::user()->country!=$sellercountry[0]['country'] && $open_hrs_status == 1)  //if collection is available
    {
        $description ="Buy ".$product_details[0]['p_title']." for £ ".$product_details[0]['p_selling_price']." available for Collection from ".$sellercountry[0]['street_address1']." , ".$sellercountry[0]['country']."";
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

<div class="card">
                        <div class="white-box-pd buy-page">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card-body">
                                        <i class="fa fa-check-circle"></i>
                                        <p>Success Order.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>