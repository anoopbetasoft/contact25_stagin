@if(count($box)>'1')
<button type="button" id="box_duration_label" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="ti-package"></i> <span id="box_label"> All Boxes</span>
					</button>
<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
						<a class="dropdown-item dropdown-box" href="javascript:void(0)" data-box-id="0" onclick="sortallbox('{{$box['0']['p_rooms_id']}}','All Boxes');"> All Boxes</a>		 
								@foreach($box as $boxes)
								<a class="dropdown-item dropdown-box" href="javascript:void(0)" data-box-id="{{$boxes->id}}" onclick="sortingbox('{{$boxes->id}}','{{$boxes->box_name}}');">{{$boxes->box_name}}</a>
								@endforeach
										
								
</div>
@endif