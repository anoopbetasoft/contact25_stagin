@if(isset($product))
	<input name="p_quality" type="hidden" value="{{$product[0]['p_quality']}}">
@else
	<input name="p_quality" type="hidden" value="1">
@endif
<div class="btn-group">
	@if(isset($product))
		<button type="button" id="p_quality_button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			@if($product[0]['p_quality']=='1')
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				New
			@elseif($product[0]['p_quality']=='2')
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fas fa-star-half"></i>
				Like New
			@elseif($product[0]['p_quality']=='3')
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				Good
			@elseif($product[0]['p_quality']=='4')
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fas fa-star-half"></i>
				OK
			@endif
			<span class="caret" style="float:right;margin-top:8px;"></span>
		</button>
	@else
		<button type="button" id="p_quality_button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    	<span class="item_type">
    		<i class="fa fa-star"></i>
    		<span class="star1">
    			<i class="fa fa-star"></i><i class="fa fa-star"></i>
    		</span>
    		<span class="star2">
    			<i class="fa fa-star"></i>
    		</span>

    	</span>
			<span class="quality_label_show">
	    	New
	    </span>
			<span class="caret" style="float:right;margin-top:8px;"></span>
		</button>
	@endif

		<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 35px, 0px); top: 0px; left: 0px; will-change: transform;">
			<a class="dropdown-item quality_dropdown q_1" href="javascript:void(0)" data-quantity="1" data-quantity-label="New" data-star1="1" data-star2="1" onclick="p_quality_selected(this);" data-value="1" id="q_1">
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				New
			</a>

			<a class="dropdown-item quality_dropdown q_2" href="javascript:void(0)" data-quantity="2" data-quantity-label="Like New" data-star1="1" data-star2="0.5"  onclick="p_quality_selected(this);" data-value="2" id="q_2">
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fas fa-star-half"></i>
				Like New
			</a>

			<a class="dropdown-item quality_dropdown q_3" href="javascript:void(0)" data-quantity="3" data-quantity-label="Good" data-star1="1" data-star2="0" onclick="p_quality_selected(this);" data-value="3" id="q_3">
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				Good
			</a>

			<a class="dropdown-item quality_dropdown q_4" href="javascript:void(0)" data-quantity="4" data-quantity-label="OK" data-star1="0.5" data-star2="0" onclick="p_quality_selected(this);" data-value="4" id="q_4">
				<i class="fa fa-star"></i>
				<i class="fa fa-star"></i>
				<i class="fas fa-star-half"></i>
				OK
			</a>

		</div>

</div>