@php
$selltooption = App\User::where('id',Auth::id())->get();
@endphp
<?php
if(isset($product))
{
	$lendingarray = explode(',',$product[0]['p_item_lend_options']);

}
if(isset($selltooption) && !isset($product)) {
if ($selltooption[0]['lend_to_neighbour'] == '1') {
?>
<input type="hidden" name="lend_to_neighbour" value="4">
<?php
}
if ($selltooption[0]['lend_to_uk'] == '1') {
?>
<input type="hidden" name="lend_to_uk" value="5">
<?php
}
if ($selltooption[0]['lend_to_friend'] == '1'){
	?>
	<input type="hidden" name="lend_to_friend" value="1">
<?php
}
}
if(isset($selltooption) && isset($product)) {
if (in_array('4',$lendingarray)) {
?>
<input type="hidden" name="lend_to_neighbour" value="4">
<?php
}
if (in_array('5',$lendingarray)) {
?>
<input type="hidden" name="lend_to_uk" value="5">
<?php
}
if (in_array('1',$lendingarray)){
?>
<input type="hidden" name="lend_to_friend" value="1">
<?php
}
?>
@foreach($friend_group as $sell_to_group)
	@if(in_array($sell_to_group['id'],$lendingarray))
		<input type="hidden" name="lend_to_group[]" class="lend_to_group" value="{{$sell_to_group['id']}}">
	@endif
@endforeach
	<?php
}

?>
@foreach($p_items_option as $otpns)

	@if($otpns['id'] ==2|| $otpns['id']==3) 
		@php $class_sell = 'warning'; @endphp
	@elseif($otpns['id'] ==4)
		@php $class_sell = 'purple';@endphp
	@elseif($otpns['id'] ==5)
		@php $class_sell = 'success';@endphp
	@else
		@php $class_sell = 'danger';@endphp
	@endif
	<?php
	if(isset($product))
	{
		?>
	<div class="checkbox checkbox-{{$class_sell}} checkbox-circle sell-wrapper" @if($otpns['id'] ==2 || $otpns['id']==3) style="cursor:pointer;margin-left:20px;" id="lend_toggle_{{$otpns['id']}}" @endif>
		<input id="checkbox_friend_group_lend_{{$otpns['id']}}"  data-value="{{$otpns['id']}}" type="checkbox" style="cursor:pointer;" @if( in_array('1',$lendingarray) && $otpns['id']=='1') {{"checked"}} @endif @if(in_array('4',$lendingarray) && $otpns['id']=='4') {{"checked"}} @endif @if(in_array('5',$lendingarray) && $otpns['id']=='5') {{"checked"}} @endif onclick="product_lend_to(this);" @if(in_array('5',$lendingarray) && Auth::user()->inpost_status=='0' && count($delivery)=='0') disabled @endif>
		<label for="checkbox_friend_group_lend_{{$otpns['id']}}" class="text-{{$class_sell}}" style="cursor:pointer;">@if($otpns['id']==5){{Auth::user()->country}}@else{{$otpns['display_text']}}@endif</label>
		<span class="text-danger" onclick="toggle_lend_to(this);">@if($otpns['id'] ==1 && count($friend_group) > 0)<i class="fas fa-chevron-down" id="friend_groups_popup-lend" style="padding:10px;"></i>@endif</span>
	</div>
	@if($otpns['id']=='1')
	@foreach($friend_group as $sell_to_group)
<div class="checkbox checkbox-warning checkbox-circle sell-wrapper lend_toggle" style="cursor:pointer;margin-left:20px;" id="lend_toggle_{{$sell_to_group['id']}}" style="cursor:pointer;margin-left:20px;">
	<input id="checkbox_friend_group_lend_{{$sell_to_group['id']}}" class="checkbox_friend_group_lend"  data-value="{{$sell_to_group['id']}}" type="checkbox" style="cursor:pointer;" @if(in_array($sell_to_group['id'],$lendingarray)) {{"checked"}} @endif onclick="product_lend_to(this);" >
		<label for="checkbox_friend_group_lend_{{$sell_to_group['id']}}" class="text-warning" style="cursor:pointer;">{{$sell_to_group['group_name']}}</label>
		<span class="text-danger" onclick="toggle_lend_to(this);"></span>
	</div>
@endforeach
	@endif
	<?php
	}
	else
	{
		?>
	<div class="checkbox checkbox-{{$class_sell}} checkbox-circle sell-wrapper" @if($otpns['id'] ==2 || $otpns['id']==3) style="cursor:pointer;margin-left:20px;" id="lend_toggle_{{$otpns['id']}}" @endif>
		<input id="checkbox_friend_group_lend_{{$otpns['id']}}"  data-value="{{$otpns['id']}}" type="checkbox" style="cursor:pointer;" @if($otpns['id'] ==1 && $selltooption[0]['lend_to_friend']=='1') {{"checked"}} @endif @if($otpns['id'] ==4 && $selltooption[0]['lend_to_neighbour']=='1') {{"checked"}} @endif @if($otpns['id'] ==5 && $selltooption[0]['lend_to_uk']=='1') {{"checked"}} @endif onclick="product_lend_to(this);" @if($otpns['id']=='5' && Auth::user()->inpost_status=='0' && count($delivery)=='0') disabled @endif>
		<label for="checkbox_friend_group_lend_{{$otpns['id']}}" class="text-{{$class_sell}}" style="cursor:pointer;">@if($otpns['id']==5){{Auth::user()->country}}@else{{$otpns['display_text']}}@endif</label>
		<span class="text-danger" onclick="toggle_lend_to(this);">@if($otpns['id'] ==1 && count($friend_group) > 0)<i class="fas fa-chevron-down" id="friend_groups_popup-lend" style="padding:10px;"></i>@endif</span>
	</div>
	@if($otpns['id']=='1')
		@foreach($friend_group as $sell_to_group)
			<div class="checkbox checkbox-warning checkbox-circle sell-wrapper lend_toggle" style="cursor:pointer;margin-left:20px;" id="lend_toggle_{{$sell_to_group['id']}}">
				<input id="checkbox_friend_group_lend_{{$sell_to_group['id']}}" class="checkbox_friend_group_lend"  data-value="{{$sell_to_group['id']}}" type="checkbox" style="cursor:pointer;" @if($selltooption[0]['lend_to_friend']!='1' && $selltooption[0]['sell_to_friend_of_friend']=='1') {{"checked"}} @endif onclick="product_lend_to(this);">
				<label for="checkbox_friend_group_lend_{{$sell_to_group['id']}}" class="text-warning" style="cursor:pointer;">{{$sell_to_group['group_name']}}</label>
				<span class="text-danger" onclick="toggle_lend_to(this);"></span>
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
	<input type="hidden" name="p_lend_to[]" value="{{$product[0]['p_lend_options']}}">
<?php
}
else
{
	?>
<input type="hidden" name="p_lend_to[]" value="">
<?php
}
?>