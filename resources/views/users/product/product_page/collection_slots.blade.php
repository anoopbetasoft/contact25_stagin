
<?php 
#get the color class
$lastno = (string)$time_count;
$color_no = $lastno[strlen($lastno)-1];
if(date('Y-m-d',strtotime($data['date'])) == date('Y-m-d'))
{
    //$editedtime = date('h:i:s',strtotime('+7 Hours'));
    $minute = date('i',strtotime(date('Y-m-d h:i:s')));
    if(strtotime(date('h:i:s A',strtotime($data['start_time']))) < strtotime(date('h:i:s A')))
    {
        if($minute!='00')
        {
            $minute_diff = 60 - $minute;
            //$newtime = date('h:00');
            $newtime = date('H:00');
            $start = new DateTimeImmutable($newtime);
            if($minute_diff<11)
            {
                $start = $start->add(new DateInterval('PT2H'));
                $data['start_time'] = date('h:00 a',strtotime('+2 hours'));
                //$start = $start->format('g:i a');
            }
            else
            {
                $start = $start->add(new DateInterval('PT1H'));
                $data['start_time'] = date('h:00 a',strtotime('+1 hours'));
                //$start = $start->format('g:i a');
            }
            $end = new DateTimeImmutable($data['end_time']);
            
        }
        else
        {
            //$start = new DateTimeImmutable($data['start_time']);
            $start = $start->add(new DateInterval('PT10M'));
            $data['start_time'] = date('h:00 a');
            $end = new DateTimeImmutable($data['end_time']);
           
        }
    }
    else
    {
       $start = new DateTimeImmutable($data['start_time']);
       $end = new DateTimeImmutable($data['end_time']);
    }
    
}
else
{

    $start = new DateTimeImmutable($data['start_time']);
    $end = new DateTimeImmutable($data['end_time']);
}
#check if 30 minutes or not
$diff_by = '';
if($product_details[0]['p_type']=='2')   // IF IT IS SERVICE
{
    if($product_details[0]['service_time_type']=='min')
    {
        $diff_by = 'PT'.$product_details[0]['service_time'].'M';
    }
    elseif($product_details[0]['service_time_type']=='hr')
    {   
        $diff_by = 'PT60M';
    }
    elseif($product_details[0]['service_time_type']=='hrs')
    {   
        $servicetime = 60 * $product_details[0]['service_time'];
        $diff_by = 'PT'.$servicetime.'M';
    }
    else
    {   
        $servicetime = 60 * 24 * $product_details[0]['service_time'];
        $diff_by = 'PT'.$servicetime.'M';
    }

    if(strpos($data['start_time'], '30') !== false && strpos($data['end_time'], '30') === false){
         #10:30 to 4 
        $diff_type = 90;
    }

    if (strpos($data['start_time'], '30') === false && strpos($data['end_time'], '30')!== false)  { 
        #10:00 to 4:30 
       $diff_type = 30;
       
    }
    elseif((strpos($data['start_time'], '30') !== false && strpos($data['end_time'], '30') !== false) || (strpos($data['start_time'], '30') === false && strpos($data['end_time'], '30') === false) ){
          # 10:30 to 5:30 || 10:00 to 5:00 
        $diff_type = 60;
        
    }
}
else               // IF IT IS NOT SERVICE
{
    if(strpos($data['start_time'], '30') !== false && strpos($data['end_time'], '30') === false){
        $diff_by =  'PT60M'; #10:30 to 4 
        $diff_type = 90;
    }

    if (strpos($data['start_time'], '30') === false && strpos($data['end_time'], '30')!== false)  { 
       $diff_by =  'PT60M'; #10:00 to 4:30 
       $diff_type = 30;
       
    }
    elseif((strpos($data['start_time'], '30') !== false && strpos($data['end_time'], '30') !== false) || (strpos($data['start_time'], '30') === false && strpos($data['end_time'], '30') === false) ){
        $diff_by =  'PT60M';  # 10:30 to 5:30 || 10:00 to 5:00 
        $diff_type = 60;
        
    }
}
$interval = new DateInterval($diff_by); //15 minute interval
$range = new DatePeriod($start, $interval, $end);
$iterate_range = (iterator_count($range));
?>
<?php
//$a = 1;
if($time_count%5==0 && $time_count!=0)
{
    //$prev = $a;
    if((($time_count/5)-1)==0)
    {
        ?>
        <p class="lead">
                                                <button type="button" class="btn btn-success btn-lg" onclick="showAll_slots({{$time_count/5}},{{($time_count/5)-1}});"><span class="fa fa-angle-down"></span></button>
                                            </p>
        <?php
    }
    else
    {
        
        ?>
        <p class="lead">
                                                <button type="button" class="btn btn-success btn-lg" onclick="showAll_slots({{$time_count/5}},{{($time_count/5)-1}});"><span class="fa fa-angle-down"></span></button>
                                            </p>
        <?php

    }
}
//echo "<pre>";print_r($range); echo "</pre>";
/*foreach($range as $rangecount => $time){
    echo "<pre>";print_r($time); echo "</pre>";
}*/
?>
@if( $time_count/5 > 0)
@if($time_count%5==0)
</div>
<div id="{{$time_count/5}}" style="display: none;">
     <button type="button" class="btn btn-success btn-lg" onclick="showprevious_slots({{($time_count/5)-1}},{{$time_count/5}});"><span class="fa fa-angle-up"></span></button>
@endif
@if(strtotime($data['start_time']) < strtotime($data['end_time']))  
<!-- <p class="lead">
                                                <button type="button" class="btn btn-success btn-lg" onclick="showprevious_slots({{($time_count/5)-1}},{{$time_count/5}});"><span class="fa fa-angle-up"></span></button>
                                            </p> -->
    <span class="list-group-item hide_timeslots each_through" data-color_type="{{$color_values[$color_no]}}">
        <span class="fa fa-circle {{$color_class[$color_no]}}  m-r-10"></span>
        <span class="{{$color_class[$color_no]}}">
           <a style="font-weight:bold;" class="timeslot_day" onclick="timeslot_day(this);" id="slot_time_{{$time_count}}" >{{ ucwords($data['day'])}}</a>
           , <a class="timeslot_day" onclick="timeslot_day(this);" id="slot_time_{{$time_count}}" >{{date("g:ia",strtotime($data['start_time']))}} -  {{ date("g:ia",strtotime($data['end_time']))}} ({{$data['date']}})
           </a>
        </span>
        <span>
        <ul class="icheck-list slot_time_{{$time_count}} product_page_ul"  style="display: none" >
            @foreach($range as $rangecount => $time)
              <li style="padding: 0px"> 
                    <div class="radiocheck">
                        @if($diff_type == 30 && $iterate_range == ($rangecount+1))
                        @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:i a')) < strtotime($data['end_time']))
                            <input type="radio"  id="radio_{{$time_count}}_{{$rangecount}}" class="radioselect check collect_condition" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" name="slot_time" data-raw_date = "{{$data['day']}}">
                            <label class="" for="radio_{{$time_count}}_{{$rangecount}}">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date("g:i a", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @elseif($diff_type == 90 && $rangecount==0)
                        @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:i a')) < strtotime($data['end_time']))
                            <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}" class="radioselect check collect_condition" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" name="slot_time" data-raw_date = "{{$data['day']}}">
                            <label class="" for="radio_{{$time_count}}_{{$rangecount}}" onclick="showcollectiondiv();">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date("g:i a", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @else
                        @if(strtotime($time->add($interval)->format('g:i a')) < strtotime($data['end_time']))
                            <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}" class="radioselect check collect_condition checkboxselect" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" name="slot_time" data-raw_date = "{{$data['day']}}">

                            <label class="" for="radio_{{$time_count}}_{{$rangecount}}" onclick="showcollectiondiv();">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date("g:i a", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @endif
                    </div>
                    <div class="checkboxcheck custom-control  custom-checkbox" id="checkboxdiv_{{$time_count}}_{{$rangecount}}" style="display: none;">
                        @if($diff_type == 30 && $iterate_range == ($rangecount+1))
                        @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:i a')) < strtotime($data['end_time']))
                            <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}" name="service_slot_time[]" class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}} custom-control-input" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" data-raw_date = "{{$data['day']}}" onclick="checkboxvalidate2(this);">
                            <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}" onclick="checkboxvalidate({{$time_count}},{{$rangecount}});"> {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date("g:i a", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @elseif($diff_type == 90 && $rangecount==0)
                          @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:i a')) < strtotime($data['end_time'])) 
                            <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}" name="service_slot_time[]" class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}} custom-control-input" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" data-raw_date = "{{$data['day']}}" onclick="checkboxvalidate2(this);">
                            <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}" onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date("g:i a", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @else
                           @if(strtotime($time->add($interval)->format('g:i a')) < strtotime($data['end_time']))
                            <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}" name="service_slot_time[]" class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}} custom-control-input" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" data-raw_date = "{{$data['day']}}" onclick="checkboxvalidate2(this);">
                            <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}" onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date("g:i a", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @endif
                    </div>
                    
                </li>
                
            @endforeach
        </ul>
        </span>
    </span>
    @endif
    

@else
@if($time_count%5==0)
<div id="{{$time_count/5}}">
@endif
@if(strtotime($data['start_time']) < strtotime($data['end_time']))
    <span class="list-group-item each_through"  data-color_type="{{$color_values[$color_no]}}">
        <span class="fa fa-circle {{$color_class[$color_no]}}  m-r-10"></span>
        <span class="{{$color_class[$color_no]}}" >
            <a style="font-weight:bold;" class="timeslot_day" onclick="timeslot_day(this);"id="slot_time_{{$time_count}}" >
                {{ ucwords($data['day'])}}
            </a> , 
            <a class="timeslot_day" onclick="timeslot_day(this);"id="slot_time_{{$time_count}}" >
            {{date("g:ia",strtotime($data['start_time']))}} -  {{ date("g:ia",strtotime($data['end_time']))}} ({{$data['date']}})
        </a>
        </span>
        <span>
            <ul class="icheck-list slot_time_{{$time_count}} product_page_ul"  style="display: none; float: none;">
                @foreach($range as $rangecount => $time)
                    
                    <li style="padding: 0px"> 
                        <div class="custom-control custom-radio radiocheck">
                            @if($diff_type == 30 && $iterate_range == ($rangecount+1))
                            @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:ia')) < strtotime($data['end_time']))
                                <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}" class="radioselect check collect_condition" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" name="slot_time" data-raw_date = "{{$data['day']}}">
                                <label class="" for="radio_{{$time_count}}_{{$rangecount}}" onclick="showcollectiondiv();">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'])) {{date("g:ia", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:ia')}} @endif

                                    </label>
                                    @endif

                            @elseif($diff_type == 90 && $rangecount==0)
                            @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:ia')) < strtotime($data['end_time']))
                                <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}" class="radioselect check collect_condition" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" name="slot_time" data-raw_date = "{{$data['day']}}">
                                <label class="" for="radio_{{$time_count}}_{{$rangecount}}" onclick="showcollectiondiv();"> {{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'])) {{date("g:ia", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                            @endif
                            @else
                            @if(strtotime($time->add($interval)->format('g:ia')) < strtotime($data['end_time']))
                                <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}" class="radioselect check collect_condition" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" name="slot_time" data-raw_date = "{{$data['day']}}">
                                <label class="" for="radio_{{$time_count}}_{{$rangecount}}" onclick="showcollectiondiv();"> {{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'])) {{date("g:ia", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                            @endif
                            @endif
                        </div>
                        <div class="checkboxcheck custom-control  custom-checkbox" id="checkboxdiv_{{$time_count}}_{{$rangecount}}" style="display: none;">
                        @if($diff_type == 30 && $iterate_range == ($rangecount+1))
                        @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:ia')) < strtotime($data['end_time']))
                            <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}" name="service_slot_time[]" class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}} custom-control-input" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" data-raw_date = "{{$data['day']}}" onclick="checkboxvalidate2(this);">
                            <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}" onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'])) {{date("g:ia", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                            @endif
                        @elseif($diff_type == 90 && $rangecount==0)
                           @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:ia')) < strtotime($data['end_time']))
                            <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}" name="service_slot_time[]" class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}} custom-control-input" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" data-raw_date = "{{$data['day']}}" onclick="checkboxvalidate2(this);">
                            <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}" onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'])) {{date("g:ia", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                            @endif
                        @else
                           @if(strtotime($time->add($interval)->format('g:ia')) < strtotime($data['end_time']))
                            <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}" name="service_slot_time[]" class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}} custom-control-input" value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'])) {{date('g:i a', strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:i a')}} @endif" data-raw_date = "{{$data['day']}}" onclick="checkboxvalidate2(this);">

                            <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}" onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'])) {{date("g:ia", strtotime($data['end_time']))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                            @endif
                        @endif
                    </div>

                        
                    </li>
                @endforeach
            </ul>
        </span>
    </span>
@endif
@endif
