<div class="btn-group">
	<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="p_type_button" >
		<span class="item_icon">
			@if($p_types[0]->id == 1) 
			<i class='ti-package'></i> 
			@elseif($p_types[0]->id == 2) 
			<i class='fa fa-user'></i> 
			@else 
			<i class='fa fa-repeat'></i> 
			@endif
		</span> 
		<span class="item_label">{{ucwords($p_types[0]->type)}} </span> 
		<span class="caret" style="float:right;margin-top:8px;"></span>
	</button>
	<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 35px, 0px); top: 0px; left: 0px; will-change: transform;" id="p_type_options">
		@foreach($p_types as $type)

			<a class="dropdown-item item_type_dropdown" href="javascript:void(0)" data-itemtype="1" data-itemtypelabel="Item" data-icon="ti-package" data-product_type ="{{$type['type']}}_div" data-product_type_row="main_row_{{$type['type']}}" onclick="p_type_selected(this);" data-value="{{$type['id']}}">
				@if($type['id'] == 1)
				<i class='ti-package'></i> 
				@elseif($type['id'] == 2) 
				<i class='fa fa-user'></i> 
				@else <i class='fa fa-repeat'></i> 
				@endif 
				{{ucwords($type['display_text'])}}
			</a>
	    @endforeach
		
	</div>
	<input type="hidden" value="{{$p_types[0]->id}}" name="p_type" >
</div>