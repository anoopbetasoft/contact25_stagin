<option value="0" disabled selected="selected">Select</option>
@if($product_details[0]['p_price_per_optn'] == 1) 
    @for($i = 1; $i <= 31; $i++)
        @if($i == 1)
        <option value="{{$i}}">Lend for {{$i}} day</option>
        @else
        <option value="{{$i}}">Lend for {{$i}} days</option>
        @endif
    @endfor
@elseif($product_details[0]['p_price_per_optn'] == 2)
    @for($i = 1; $i <= 12; $i++)
        @if($i == 1)
        <option value="{{$i}}">Lend for {{$i}} week</option>
        @else
        <option value="{{$i}}">Lend for {{$i}} weeks</option>
        @endif
    @endfor
@elseif($product_details[0]['p_price_per_optn'] == 3)
    @for($i = 1; $i <= 12; $i++)
        @if($i == 1)
        <option value="{{$i}}">Lend for {{$i}} month</option>
        @else
        <option value="{{$i}}">Lend for {{$i}} months</option>
        @endif
    @endfor
@else
    @for($i = 1; $i <= 2; $i++)
        @if($i == 1)
        <option value="{{$i}}">Lend for {{$i}} year</option>
        @else
        <option value="{{$i}}">Lend for {{$i}} years</option>
        @endif
    @endfor
@endif