
@php
$selltooption = App\User::where('id',Auth::id())->get();
@endphp
<?php
if(isset($product))
{
	$sellingarray = explode(',',$product[0]['p_sell_to']);
	$lendingarray = explode(',',$product[0]['p_item_lend_options']);

}
if(isset($selltooption) && !isset($product)) {
if ($selltooption[0]['sell_to_neighbour'] == '1') {
?>
<input type="hidden" name="sell_to_neighbour" value="4">
<?php
}
if ($selltooption[0]['sell_to_uk'] == '1') {
?>
<input type="hidden" name="sell_to_uk" value="5">
<?php
}
if($selltooption[0]['sell_to_friend']=='1')
{
	?>
<input type="hidden" name="sell_to_friend" value="1">
<?php
}
}
if(isset($selltooption) && isset($product)) {
if (in_array('4',$sellingarray)) {
?>
<input type="hidden" name="sell_to_neighbour" value="4">
<?php
}
if (in_array('5',$sellingarray)) {
?>
<input type="hidden" name="sell_to_uk" value="5">
<?php
}
if(in_array('1',$sellingarray))
{
?>
<input type="hidden" name="sell_to_friend" value="1">
<?php
}
?>
@foreach($friend_group as $sell_to_group)
	@if(in_array($sell_to_group['id'],$sellingarray))
		<input type="hidden" name="sell_to_group[]" class="sell_to_group" value="{{$sell_to_group['id']}}">
	@endif
@endforeach
<?php
}
if(isset($product))
{
	?>
	{{--<input type="hidden" name="pselltovalue" value="{{$product[0]['p_sell_to']}}">--}}
	<?php
}

?>
@foreach($p_sell_to as $sell_to)

	@if($sell_to['id'] ==2|| $sell_to['id']==3) 
		@php $class_sell = 'info'; @endphp
	@elseif($sell_to['id'] ==4)
		@php $class_sell = 'purple';@endphp
	@elseif($sell_to['id'] ==5)
		@php $class_sell = 'success';@endphp
	@else
		@php $class_sell = 'danger';@endphp
	@endif
	<?php
	if(isset($product))
	{
		?>
	<div class="checkbox checkbox-{{$class_sell}} checkbox-circle sell-wrapper" @if($sell_to['id'] ==2|| $sell_to['id']==3) style="cursor:pointer;margin-left:20px;" id="sell_toggle_{{$sell_to['id']}}" @endif>
		<input id="checkbox_friend_group_sell_{{$sell_to['id']}}"  data-value="{{$sell_to['id']}}" type="checkbox" style="cursor:pointer;" @if(in_array('1',$sellingarray) && $sell_to['id']=='1') {{"checked"}} @endif @if(in_array('4',$sellingarray) && $sell_to['id']=='4') {{"checked"}} @endif @if(in_array('5',$sellingarray) && $sell_to['id']=='5') {{"checked"}} @endif onclick="product_sell_to(this);" @if(in_array('5',$sellingarray) && Auth::user()->inpost_status=='0' && count($delivery)=='0') "disabled" @endif>
		<label for="checkbox_friend_group_sell_{{$sell_to['id']}}" class="text-{{$class_sell}}" style="cursor:pointer;">@if($sell_to['id']==5){{Auth::user()->country}} @else {{$sell_to['display_name']}} @endif</label>
		<span class="text-danger" onclick="toggle_sell_to(this);">@if($sell_to['id'] ==1 && count($friend_group) > 0)<i class="fas fa-chevron-down" id="friend_groups_popup-sell" style="padding:10px;"></i>@endif</span>
	</div>
	@if($sell_to['id']=='1')
		@foreach($friend_group as $sell_to_group)
			<div class="checkbox checkbox-info checkbox-circle sell-wrapper sell_toggle" style="cursor:pointer;margin-left:20px;" id="sell_toggle_{{$sell_to_group['id']}}">
				<input id="checkbox_friend_group_sell_{{$sell_to_group['id']}}" class="checkbox_friend_group_sell"  data-value="{{$sell_to_group['id']}}" type="checkbox" style="cursor:pointer;" @if(in_array($sell_to_group['id'],$sellingarray)) {{"checked"}} @endif onclick="product_sell_to(this);">
				<label for="checkbox_friend_group_sell_{{$sell_to_group['id']}}" class="text-info" style="cursor:pointer;">{{$sell_to_group['group_name']}}</label>
				<span class="text-danger" onclick="toggle_sell_to(this);"></span>
			</div>
		@endforeach
	@endif
	<?php

	}
	else
	{
		?>
	<div class="checkbox checkbox-{{$class_sell}} checkbox-circle sell-wrapper" @if($sell_to['id'] ==2|| $sell_to['id']==3) style="cursor:pointer;margin-left:20px;" id="sell_toggle_{{$sell_to['id']}}" @endif>
		<input id="checkbox_friend_group_sell_{{$sell_to['id']}}"  data-value="{{$sell_to['id']}}" type="checkbox" style="cursor:pointer;" @if($sell_to['id'] ==1 && $selltooption[0]['sell_to_friend']=='1') {{"checked"}} @endif @if($sell_to['id'] ==4 && $selltooption[0]['sell_to_neighbour']=='1') {{"checked"}} @endif @if($sell_to['id'] ==5 && $selltooption[0]['sell_to_uk']=='1') {{"checked"}} @endif onclick="product_sell_to(this);" @if($sell_to['id']==5 && Auth::user()->inpost_status=='0' && count($delivery)=='0') disabled @endif>
		<label for="checkbox_friend_group_sell_{{$sell_to['id']}}" class="text-{{$class_sell}}" style="cursor:pointer;">@if($sell_to['id']==5){{Auth::user()->country}} @else {{$sell_to['display_name']}} @endif</label>
		<span class="text-danger" onclick="toggle_sell_to(this);">@if($sell_to['id'] ==1 && count($friend_group) > 0)<i class="fas fa-chevron-down" id="friend_groups_popup-sell" style="padding:10px;"></i>@endif</span>
	</div>
	@if($sell_to['id']=='1')
		@foreach($friend_group as $sell_to_group)
			<div class="checkbox checkbox-info checkbox-circle sell-wrapper sell_toggle" style="cursor:pointer;margin-left:20px;" id="sell_toggle_{{$sell_to_group['id']}}">
				<input id="checkbox_friend_group_sell_{{$sell_to_group['id']}}" class="checkbox_friend_group_sell"  data-value="{{$sell_to_group['id']}}" type="checkbox" style="cursor:pointer;" @if($selltooption[0]['sell_to_friend']!='1' && $selltooption[0]['sell_to_friend_of_friend']=='1') {{"checked"}} @endif onclick="product_sell_to(this);">
				<label for="checkbox_friend_group_sell_{{$sell_to_group['id']}}" class="text-info" style="cursor:pointer;">{{$sell_to_group['group_name']}}</label>
				<span class="text-danger" onclick="toggle_sell_to(this);"></span>
			</div>
		@endforeach
	@endif
	<?php

	}
	?>
@endforeach
<?php
if(isset($product))
{
	?>
<input type="hidden" name="p_sell_to[]" value="{{$product[0]['p_sell_to']}}">
<?php
}
else
{
?>
<input type="hidden" name="p_sell_to[]" value="">
<?php
}
?>