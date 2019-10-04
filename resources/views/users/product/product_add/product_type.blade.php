<select class="selectpicker" data-style="form-control btn-secondary" name="p_type" onchange="p_type_selected(this);" id="p_type_options">
    @foreach($p_types as $type)
        <option value="{{$type['id']}}" data-product_type ="{{$type['type']}}_div" data-product_type_row="main_row_{{$type['type']}}" data-content ="@if($type['id'] == 1) <i class='fa fa-archive'></i> @elseif($type['id'] == 2) <i class='fa fa-user'></i> @else <i class='fa fa-repeat'></i> @endif {{$type['display_text']}}" >{{$type['display_text']}}</option>
    @endforeach
</select>