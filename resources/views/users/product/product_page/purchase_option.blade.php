@php
    $product_details[0]['p_selling_price'] = number_format($product_details[0]['p_selling_price'],$decimal_place[0]['decimal_places']);
    $product_details[0]['p_selling_price'] = str_replace('.00','',$product_details[0]['p_selling_price']);
$product_details[0]['p_lend_price'] = number_format($product_details[0]['p_lend_price'],$decimal_place[0]['decimal_places']);
    $product_details[0]['p_lend_price'] = str_replace('.00','',$product_details[0]['p_lend_price']);
@endphp
@if($product_details[0]['p_type'] == 1) <!-- item -->
    <option value="{{$product_details[0]['p_selling_price']}}" data-purchange_type="buy_options" class="text-info" style="font-family: Poppins,sans-sarif"> Buy  {{$currency_symbol[0]->symbol}}{{$product_details[0]['p_selling_price']}}</option>
    @if(!empty($product_details[0]['p_item_lend_options']))
    <option value="{{$product_details[0]['p_lend_price']}}" data-purchange_type="lending_options" class="text-warning"  style="font-family: Poppins,sans-sarif"><i class="fa fa-refresh text-warning"></i>Lend  {{$currency_symbol[0]->symbol}}{{$product_details[0]['p_lend_price']}}</option>
    @endif
@elseif($product_details[0]['p_type'] == 2)<!-- service -->
   
    <option value="{{$product_details[0]['p_selling_price']}}" selected="selected" data-purchange_type="service_options" class="text-info" style="font-family: Poppins,sans-sarif">Buy  {{$currency_symbol[0]->symbol}}{{$product_details[0]['p_selling_price']}} / {{$product_details[0]['service_time']}}{{$product_details[0]['service_time_type']}}</option>
    
@else<!-- subscribe -->
    
    <option value="{{$product_details[0]['p_selling_price']}}" class="text-info"  selected="selected" data-purchange_type="subscription_options" style="font-family: Poppins,sans-sarif">Subscribe  {{$currency_symbol[0]->symbol}}{{$product_details[0]['p_subs_price']}}</option>
   
@endif