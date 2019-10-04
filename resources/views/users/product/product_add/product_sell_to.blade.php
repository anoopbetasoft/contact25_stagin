<select class="selectpicker product_sell_to" multiple data-style="form-control btn-secondary" name="p_sell_to[]" onchange="product_sell_to(this);">  
    @foreach($p_sell_to as $sell_to)
        <option value="{{$sell_to['id']}}">{{$sell_to['display_name']}}</option>
    @endforeach    
    
</select>