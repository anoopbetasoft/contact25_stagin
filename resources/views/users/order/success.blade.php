
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
    <style>
    .success_page i{
           font-size: 42px;
    text-align: center;
    color: green;
    margin: 0 auto;
    display: block;
    margin-bottom: 10px
    }
    .success_page p{
        text-align: center;
        font-size:18px;
    }
    .buy-page.white-box-pd {
    min-height: 80px;
    padding: 20px;
}
.table {
    width: 50%;
    margin: 0 auto;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}
    @media (max-width:767px) {
        table.table.table-inverse {
            width: 100%;
        }
    }
</style> 

     <div class="card success_page">
                        <div class="white-box-pd buy-page">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card-body">
                                       @foreach($orderdetails as $order)
                                        <i class="fa fa-check-circle"></i>
                                        @if($order['o_total']=='0' || isset($discount))
                                        <p>Your Order is Successful.</p>
                                         <p>Thank you. Your order has been received.</p>
                                        @else
                                        <p>Your Payment Is Successful.</p>
                                         <p>Thank you. Your order has been received.</p>
                                        @endif
                                        @endforeach
                                        <table  class="table table-inverse" style="border:1px solid #ddd;"> 
                                            <tbody>
                                                @if(count($orderdetails) > 0)
                @foreach($orderdetails as $order)
                                                <tr>
                                                   <td><strong>Placed on :</strong></td>
                                                   <td>{{date_format($order['created_at'] , 'M d, Y H:i a')}}</td>
                                                </tr>
                                                <tr>
                                                   <td><strong>Order :</strong></td>
                                                   <td>{{$order['id']}}</td>
                                                </tr>
                                                <tr>
                                                   <td><strong>Quantity :</strong></td>
                                                   <td>{{$order['o_quantity']}}</td>
                                                </tr>
                                                <tr>
                                                   <td><strong>Total :</strong></td>
                                                   <td>
                                                       @php
                                                           $order['o_total'] = number_format($order['o_total'],$decimal_place[0]['decimal_places']);
                                                           $order['o_total'] = str_replace('.00','',$order['o_total']);
                                                       @endphp
                                                   @if($order['o_total']=='0')
                                               {{$order->currency->symbol}}Free @else
                                           {{$order->currency->symbol}}{{$order['o_total']}} 
                                            @endif</td>
                                                </tr>
                                                @if(isset($discount))
                                                    @php
                                                        $discount = number_format($discount,$decimal_place[0]['decimal_places']);
                                                        $discount = str_replace('.00','',$discount);
                                                    @endphp
                                                <tr>
                                                    <td><strong>Discount:</strong></td>
                                                    <td> {{$order->currency->symbol}}{{$discount}} </td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    @php
                                                        $order['o_delivery_charge'] = number_format($order['o_delivery_charge'],$decimal_place[0]['decimal_places']);
                                                         $order['o_delivery_charge'] = str_replace('.00','',$order['o_delivery_charge']);
                                                    @endphp
                                                   <td><strong>Delivery Charge :</strong></td>
                                                   <td>{{$order->currency->symbol}}{{$order['o_delivery_charge']}}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Delivery Provider :</strong></td>
                                                     @if($order['o_delivery_provider']=='inpost')
                                                     <td>Inpost Next Day</td>
                                                     @elseif($order['o_delivery_provider']=='0')
                                                     <td>None</td>
                                                     @elseif($order['o_delivery_provider']!='0' && $order['o_delivery_provider']!='inpost')
                                                     <td>{{$order->deliveryprovider->delivery_provider}}</td>
                                                   @endif
                                                </tr>
                                               <!-- <tr>
                                                   <td><strong>Order Status :</strong></td>
                                                   <td>@if($order['o_dispatched']==0)
                                        <button class="btn btn-warning" >Processing</button>
                                        @elseif($order['o_dispatched'] != 0)
                                        <button class="btn btn-purple">Dispatched</button>
                                        @elseif($order['o_shipped'] != 0)
                                        <button class="btn btn-info">Dispatched</button>
                                        @elseif($order['o_complete'] != 0)
                                        <button class="btn btn-success">Dispatched</button>
                                        @endif</td>
                                                </tr>-->
                                                @endforeach
                                                @else
                                                <tr>
                                                    No Orders Found
                                                </tr>
                                                @endif
                                            </tbody>
                                      </table>
                                    </div>
                                </div>
                            </div>
             
        
                        </div>
                    </div>
   

     @endsection

