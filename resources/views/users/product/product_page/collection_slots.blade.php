<?php
#Get Booked Collection Time From Order Table
$o_collection_time = array();
$final_slots = array();
$final_collection_time = array();
foreach ($orders as $order) {
    $o_collection_time[] = $order['o_collection_time'];         // Getting Booked Slots from order table for Services to not display booked slot

}
$ordered_collection_time = implode('|', $o_collection_time);
$seperated_collection_time = explode('|', $ordered_collection_time);

for ($i = 0; $i < count($seperated_collection_time); $i++) {
    $final_slots[] = explode(',', $seperated_collection_time[$i]);
}

for ($i = 0; $i < count($final_slots); $i++) {
    for ($j = 0; $j < count($final_slots[$i]); $j++) {
        $final_collection_time[] = explode(' - ', $final_slots[$i][$j]);
        //echo $final_slots[$i][$j];
    }
}
/*print_r($final_collection_time);
die;*/
#get the color class
$lastno = (string)$time_count;
$color_no = $lastno[strlen($lastno) - 1];
//dd(date('h:i:s A'));
//dd(date('h:i:s A'));
        if($data['break_time']=='0')
            {
                $data['start_time'] = explode(',',$data['start_time']);
                $data['end_time'] = explode(',',$data['end_time']);
            }
if (date('Y-m-d', strtotime($data['date'])) == date('Y-m-d')) {
    //$editedtime = date('h:i:s',strtotime('+7 Hours'));
    $minute = date('i', strtotime(date('Y-m-d h:i:s')));
    //dd(date('Y-m-d h:i:s A',strtotime($data['start_time'])));
    if ($data['break_time'] > '0') {
        $start = array();
        $end = array();
        $data['start_time'] = explode(',', $data['start_time']);
        $data['end_time'] = explode(',', $data['end_time']);
        for ($j = 0; $j <= $data['break_time']; $j++) {
            if (strtotime(date('Y-m-d h:i:s A', strtotime($data['start_time'][$j]))) < strtotime(date('Y-m-d h:i:s A'))) { // Changable for multiple slots check with || OR for multiple start time
                if ($minute != '00') {
                    $minute_diff = 60 - $minute;
                    //$newtime = date('h:00');
                    $newtime = date('H:00');
                    $start[$j] = new DateTimeImmutable($newtime);
                    if ($minute_diff < 11) {
                        $start[$j] = $start[$j]->add(new DateInterval('PT2H'));
                        $data['start_time'][$j] = date('h:00 a', strtotime('+2 hours'));
                        //$start = $start->format('g:i a');
                    } else {
                        $start[$j] = $start[$j]->add(new DateInterval('PT1H'));
                        $data['start_time'][$j] = date('h:00 a', strtotime('+1 hours'));
                        //$start = $start->format('g:i a');
                    }
                    $end[$j] = new DateTimeImmutable($data['end_time'][$j]);

                } else {
                    $newtime = date('H:00');
                    $start[$j] = new DateTimeImmutable($newtime);
                    //$start = new DateTimeImmutable($data['start_time']);
                    $start[$j] = $start[$j]->add(new DateInterval('PT10M'));
                    $data['start_time'][$j] = date('h:00 a');
                    $end[$j] = new DateTimeImmutable($data['end_time'][$j]);

                }
            } else {
                $start[$j] = new DateTimeImmutable($data['start_time'][$j]);
                $end[$j] = new DateTimeImmutable($data['end_time'][$j]);
            }
        }
    } else {
        if (strtotime(date('Y-m-d h:i:s A', strtotime($data['start_time'][0]))) < strtotime(date('Y-m-d h:i:s A'))) { // Changable for multiple slots check with || OR for multiple start time
            $start = array();
            if ($minute != '00') {
                $minute_diff = 60 - $minute;
                //$newtime = date('h:00');
                $newtime = date('H:00');
                $start[0] = new DateTimeImmutable($newtime);
                if ($minute_diff < 11) {
                    $start[0] = $start[0]->add(new DateInterval('PT2H'));
                    $data['start_time'][0] = date('h:00 a', strtotime('+2 hours'));
                    //$start[0] = $start;
                       //$start = $start->format('g:i a');
                } else {
                    $start[0] = $start[0]->add(new DateInterval('PT1H'));
                    $data['start_time'][0] = date('h:00 a', strtotime('+1 hours'));
                    //$start[0] = $start;
                         //$start = $start->format('g:i a');
                    /*$start[$j] = new DateTimeImmutable($newtime);
                    //$start = new DateTimeImmutable($data['start_time']);
                    $start[$j] = $start[$j]->add(new DateInterval('PT10M'));
                    $data['start_time'][$j] = date('h:00 a');
                    $end[$j] = new DateTimeImmutable($data['end_time'][$j]);*/
                }
                $end[0] = new DateTimeImmutable($data['end_time'][0]);

            } else {
                //$start = new DateTimeImmutable($data['start_time']);
                $newtime = date('H:00');
                $start[0] = new DateTimeImmutable($newtime);
                $start[0] = $start[0]->add(new DateInterval('PT10M'));
                $data['start_time'][0] = date('h:00 a');
               //$start[0] = $start;
                $end[0] = new DateTimeImmutable($data['end_time'][0]);

            }
        } else {
            $start = array();
            $end = array();
            /*$newtime = date('H:00');
            $start[0] = new DateTimeImmutable($newtime);*/
            $start[0] = new DateTimeImmutable($data['start_time'][0]);
            $end[0] = new DateTimeImmutable($data['end_time'][0]);
        }
    }


} else {
    $start = array();
    $end = array();
    if ($data['break_time'] > '0') {
        $data['start_time'] = explode(',', $data['start_time']);
        $data['end_time'] = explode(',', $data['end_time']);
        for ($j = 0; $j <= $data['break_time']; $j++) {
            $start[$j] = new DateTimeImmutable($data['start_time'][$j]);
            $end[$j] = new DateTimeImmutable($data['end_time'][$j]);
        }
        //dd(date('Y-m-d h:i:s A',strtotime($data['start_time'])));
    } else {
        $start[0] = new DateTimeImmutable($data['start_time'][0]);
        $end[0] = new DateTimeImmutable($data['end_time'][0]);
    }
}
#check if 30 minutes or not
$diff_by = '';
if ($product_details[0]['p_type'] == '2')   // IF IT IS SERVICE
{
    if ($product_details[0]['service_time_type'] == 'min') {
        $diff_by = 'PT' . $product_details[0]['service_time'] . 'M';
    } elseif ($product_details[0]['service_time_type'] == 'hr') {
        $diff_by = 'PT60M';
    } elseif ($product_details[0]['service_time_type'] == 'hrs') {
        $servicetime = 60 * $product_details[0]['service_time'];
        $diff_by = 'PT' . $servicetime . 'M';
    } else {
        $servicetime = 60 * 24 * $product_details[0]['service_time'];
        $diff_by = 'PT' . $servicetime . 'M';
    }
    if ($data['break_time'] > '0')  // if it is multiple time slot
    {

        if (strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') === false) {
            #10:30 to 4
            $diff_type = 90;
        }

        if (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') !== false) {
            #10:00 to 4:30
            $diff_type = 30;
        } elseif ((strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') !== false) || (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') === false)) {
            # 10:30 to 5:30 || 10:00 to 5:00
            $diff_type = 60;

        }
    } else {
        if (strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') === false) {
            #10:30 to 4
            $diff_type = 90;
        }

        if (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') !== false) {
            #10:00 to 4:30
            $diff_type = 30;

        } elseif ((strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') !== false) || (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') === false)) {
            # 10:30 to 5:30 || 10:00 to 5:00
            $diff_type = 60;

        }
    }
} else               // IF IT IS NOT SERVICE
{
    if ($data['break_time'] > '0') {

        if (strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') === false) {
            $diff_by = 'PT60M'; #10:30 to 4
            $diff_type = 90;
        }

        if (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') !== false) {
            $diff_by = 'PT60M'; #10:00 to 4:30
            $diff_type = 30;

        } elseif ((strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') !== false) || (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') === false)) {
            $diff_by = 'PT60M';  # 10:30 to 5:30 || 10:00 to 5:00
            $diff_type = 60;

        }
    } else {
        if (strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') === false) {
            $diff_by = 'PT60M'; #10:30 to 4
            $diff_type = 90;
        }

        if (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') !== false) {
            $diff_by = 'PT60M'; #10:00 to 4:30
            $diff_type = 30;

        } elseif ((strpos($data['start_time'][0], '30') !== false && strpos($data['end_time'][0], '30') !== false) || (strpos($data['start_time'][0], '30') === false && strpos($data['end_time'][0], '30') === false)) {
            $diff_by = 'PT60M';  # 10:30 to 5:30 || 10:00 to 5:00
            $diff_type = 60;

        }
    }

}
if($data['break_time']>'0')
{
    //$range = array();
    //$iterate_range = array();
    $interval = new DateInterval($diff_by); //15 minute interval
    //$temprange = array();
    //$tempiterate_range = array();
    for($k=0;$k<=$data['break_time'];$k++)
        {
            $range[$k] = new DatePeriod($start[$k],$interval,$end[$k]);
            $iterate_range[$k] = (iterator_count($range[$k]));
           /* $range = array_merge($range,$temprange);
            $iterate_range = array_merge($iterate_range,$tempiterate_range[$k]);*/

        }

    /*$range = new DatePeriod($start, $interval, $end);
    $iterate_range = (iterator_count($range));*/
}
else
{
    $interval = new DateInterval($diff_by); //15 minute interval
    $range[0] = new DatePeriod($start[0], $interval, $end[0]);
    $iterate_range[0] = (iterator_count($range[0]));
}
/*$interval = new DateInterval($diff_by); //15 minute interval
$range = new DatePeriod($start, $interval, $end);
$iterate_range = (iterator_count($range));*/
?>

<?php
//$a = 1;
if($time_count % 5 == 0 && $time_count != 0)
{
//$prev = $a;
if((($time_count / 5) - 1) == 0)
{
?>
<p class="lead">
    <button type="button" class="btn btn-success btn-lg"
            onclick="showAll_slots({{$time_count/5}},{{($time_count/5)-1}});"><span class="fa fa-angle-down"></span>
    </button>
</p>
<?php
}
else
{
?>
<p class="lead">
    <button type="button" class="btn btn-success btn-lg"
            onclick="showAll_slots({{$time_count/5}},{{($time_count/5)-1}});"><span class="fa fa-angle-down"></span>
    </button>
</p>
<?php
}
}
?>
@if( $time_count/5 > 0)
@if($time_count%5==0)
</div>
<div id="{{$time_count/5}}" style="display: none;">
    <button type="button" class="btn btn-success btn-lg"
            onclick="showprevious_slots({{($time_count/5)-1}},{{$time_count/5}});"><span class="fa fa-angle-up"></span>
    </button>
@endif
{{--@if(strtotime(date('Y-m-d H:i:s' ,strtotime($data['start_time']))) < strtotime(date('Y-m-d H:i:s', strtotime($data['end_time']))) )--}}
@php
if($data['break_time']>'0')
{
    $k = $data['break_time'];
}
else
{
    $k = 0;
}
@endphp
    <?php
    $count = 0;
    ?>
@for($i=0;$i<=$k;$i++)    <!-- Loop for checking any start time is less than last end time for multiple slot -->
@if($count==0 && $start[$i]<$end[$k])
    <?php
    $count++;
    ?>
    <!-- <p class="lead">
                                                <button type="button" class="btn btn-success btn-lg" onclick="showprevious_slots({{($time_count/5)-1}},{{$time_count/5}});"><span class="fa fa-angle-up"></span></button>
                                            </p> -->
        <span class="list-group-item hide_timeslots each_through" data-color_type="{{$color_values[$color_no]}}">
        <span class="fa fa-circle {{$color_class[$color_no]}}  m-r-10"></span>
        <span class="{{$color_class[$color_no]}}">
           <a style="font-weight:bold;" class="timeslot_day" onclick="timeslot_day(this);"
              id="slot_time_{{$time_count}}">{{ ucwords($data['day'])}}</a>
           , <a class="timeslot_day" onclick="timeslot_day(this);" id="slot_time_{{$time_count}}">{{date("g:ia",strtotime($data['start_time'][$i]))}} -  {{ date("g:ia",strtotime($data['end_time'][$k]))}} ({{$data['date']}})
           </a>
        </span>
        <span>
        <ul class="icheck-list slot_time_{{$time_count}} product_page_ul" style="display: none">
         @for($l=0;$l<=$k;$l++)
            @foreach($range[$l] as $rangecount => $time)
                <?php
                $status = 1;
                for ($i = 0; $i < count($final_collection_time); $i++) // Checking wheather collection time is booked or not
                {
                    if (strtotime(date('h:i', strtotime($time->format('g:i a')))) == strtotime(date('h:i', strtotime($final_collection_time[$i][0]))) && strtotime(date('y-m-d', strtotime($data['date']))) == strtotime(date('y-m-d', strtotime($final_collection_time[$i][0])))) {
                        $status = 0;
                    }
                }
                if($status != 0 || $product_details[0]['p_type'] != '2') // If it is not service OR collection time is not booked
                {
                ?>
                <li style="padding: 0px">
                    <div class="radiocheck">
                        @if($diff_type == 30 && $iterate_range[$l] == ($rangecount+1))
                            @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:i a')) <= strtotime($data['end_time'][$l]))
                                <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       class="radioselect check collect_condition"
                                       value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                       name="slot_time" data-raw_date="{{$data['day']}}">
                                <label class=""
                                       for="radio_{{$time_count}}_{{$rangecount}}_{{$l}}">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date("g:i a", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @elseif($diff_type == 90 && $rangecount==0)
                            @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:i a')) <= strtotime($data['end_time'][$l]))
                                <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       class="radioselect check collect_condition"
                                       value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                       name="slot_time" data-raw_date="{{$data['day']}}">
                                <label class="" for="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       onclick="showcollectiondiv();">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date("g:i a", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @else
                            @if(strtotime($time->add($interval)->format('g:i a')) <= strtotime($data['end_time'][$l]))
                                <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       class="radioselect check collect_condition checkboxselect"
                                       value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                       name="slot_time" data-raw_date="{{$data['day']}}">

                                <label class="" for="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       onclick="showcollectiondiv();">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date("g:i a", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @endif
                    </div>
                    <div class="checkboxcheck custom-control  custom-checkbox"
                         id="checkboxdiv_{{$time_count}}_{{$rangecount}}" style="display: none;">
                        @if($diff_type == 30 && $iterate_range[$l] == ($rangecount+1))
                            @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:i a')) <= strtotime($data['end_time'][$l]))
                                <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       name="service_slot_time[]"
                                       class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}}_{{$l}} custom-control-input"
                                       value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                       data-raw_date="{{$data['day']}}" onclick="checkboxvalidate2(this);">
                                <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       onclick="checkboxvalidate({{$time_count}},{{$rangecount}});"> {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date("g:i a", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @elseif($diff_type == 90 && $rangecount==0)
                            @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:i a')) <= strtotime($data['end_time'][$l]))
                                <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       name="service_slot_time[]"
                                       class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}}_{{$l}} custom-control-input"
                                       value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                       data-raw_date="{{$data['day']}}" onclick="checkboxvalidate2(this);">
                                <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date("g:i a", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @else
                            @if(strtotime($time->add($interval)->format('g:i a')) <= strtotime($data['end_time'][$l]))
                                <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       name="service_slot_time[]"
                                       class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}}_{{$l}} custom-control-input"
                                       value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                       data-raw_date="{{$data['day']}}" onclick="checkboxvalidate2(this);">
                                <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                       onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date("g:i a", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif</label>
                            @endif
                        @endif
                    </div>
                    
                </li>
                <?php
                }
                ?>

            @endforeach
             @endfor
        </ul>
        </span>
    </span>
    @endif
    @endfor
    @else
        @if($time_count%5==0)
            <div id="{{$time_count/5}}">
                @endif
                @php
                    if($data['break_time']>'0')
                    {
                        $k = $data['break_time'];
                    }
                    else
                     {
                        $k = 0;
                     }
                @endphp
                {{--@if(strtotime(date('Y-m-d H:i:s' ,strtotime($data['start_time']))) < strtotime(date('Y-m-d H:i:s', strtotime($data['end_time']))))--}}
                <?php
                $count = 0;
                ?>
                @for($l=0;$l<=$k;$l++)    <!-- Loop for checking any start time is less than last end time for multiple slot -->
                    @if($count==0 && $start[$l]<$end[$k])

                   <?php
                        $count++;
                    ?>
                    <span class="list-group-item each_through" data-color_type="{{$color_values[$color_no]}}">
        <span class="fa fa-circle {{$color_class[$color_no]}}  m-r-10"></span>
        <span class="{{$color_class[$color_no]}}">
            <a style="font-weight:bold;" class="timeslot_day" onclick="timeslot_day(this);"
               id="slot_time_{{$time_count}}">
                {{ ucwords($data['day'])}}
            </a> , 
            <a class="timeslot_day" onclick="timeslot_day(this);" id="slot_time_{{$time_count}}">
            {{date("g:ia",strtotime($data['start_time'][$l]))}} -  {{ date("g:ia",strtotime($data['end_time'][$k]))}} ({{$data['date']}})
        </a>
        </span>
        <span>
            <ul class="icheck-list slot_time_{{$time_count}} product_page_ul" style="display: none; float: none;">
             @for($l=0;$l<=$k;$l++)
                @foreach($range[$l] as $rangecount => $time)
                    <?php
                    $status = 1;
                    for ($i = 0; $i < count($final_collection_time); $i++) // Checking wheather collection time is booked or not
                    {
                        //dd(strtotime(date('y-m-d'),strtotime($data['date'])));
                        if (strtotime(date('h:i', strtotime($time->format('g:i a')))) == strtotime(date('h:i', strtotime($final_collection_time[$i][0]))) && strtotime(date('y-m-d', strtotime($data['date']))) == strtotime(date('y-m-d', strtotime($final_collection_time[$i][0])))) {
                            $status = 0;
                        }
                    }

                    if($status != 0 || $product_details[0]['p_type'] != '2') // IF it is not service OR Collection Book Slot is not booked
                    {
                    ?>
                    <li style="padding: 0px"> 
                        <div class="custom-control custom-radio radiocheck">
                            @if($diff_type == 30 && $iterate_range[$l] == ($rangecount+1))
                                @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:ia')) <= strtotime($data['end_time'][$l]))
                                    <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           class="radioselect check collect_condition"
                                           value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                           name="slot_time" data-raw_date="{{$data['day']}}">
                                    <label class="" for="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           onclick="showcollectiondiv();">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'][$l])) {{date("g:ia", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:ia')}} @endif

                                    </label>
                                @endif

                            @elseif($diff_type == 90 && $rangecount==0)
                                @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:ia')) <= strtotime($data['end_time'][$l]))
                                    <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           class="radioselect check collect_condition"
                                           value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                           name="slot_time" data-raw_date="{{$data['day']}}">
                                    <label class="" for="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           onclick="showcollectiondiv();"> {{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'][$l])) {{date("g:ia", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                                @endif
                            @else
                                @if(strtotime($time->add($interval)->format('g:ia')) <= strtotime($data['end_time'][$l]))
                                    <input type="radio" id="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           class="radioselect check collect_condition"
                                           value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                           name="slot_time" data-raw_date="{{$data['day']}}">
                                    <label class="" for="radio_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           onclick="showcollectiondiv();"> {{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'][$l])) {{date("g:ia", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                                @endif
                            @endif
                        </div>
                        <div class="checkboxcheck custom-control  custom-checkbox"
                             id="checkboxdiv_{{$time_count}}_{{$rangecount}}" style="display: none;">
                        @if($diff_type == 30 && $iterate_range == ($rangecount+1))
                                @if(strtotime($time->add(new DateInterval('PT30M'))->format('g:ia')) <= strtotime($data['end_time'][$l]))
                                    <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           name="service_slot_time[]"
                                           class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}}_{{$l}} custom-control-input"
                                           value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                           data-raw_date="{{$data['day']}}" onclick="checkboxvalidate2(this);">
                                    <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'][$l])) {{date("g:ia", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                                @endif
                            @elseif($diff_type == 90 && $rangecount==0)
                                @if(strtotime($time->add(new DateInterval('PT90M'))->format('g:ia')) <= strtotime($data['end_time'][$l]))
                                    <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           name="service_slot_time[]"
                                           class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}}_{{$l}} custom-control-input"
                                           value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                           data-raw_date="{{$data['day']}}" onclick="checkboxvalidate2(this);">
                                    <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'][$l])) {{date("g:ia", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                                @endif
                            @else
                                @if(strtotime($time->add($interval)->format('g:ia')) <= strtotime($data['end_time'][$l]))
                                    <input type="checkbox" id="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           name="service_slot_time[]"
                                           class="checkboxselect checkbox_{{$time_count}}_{{$rangecount}}_{{$l}} custom-control-input"
                                           value="{{$data['raw_date']}} | {{$time->format('g:i a')}} - @if(strtotime($time->add($interval)->format('g:i a')) > strtotime($data['end_time'][$l])) {{date('g:i a', strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:i a')}} @endif"
                                           data-raw_date="{{$data['day']}}" onclick="checkboxvalidate2(this);">

                                    <label class="custom-control-label" for="checkbox_{{$time_count}}_{{$rangecount}}_{{$l}}"
                                           onclick="checkboxvalidate({{$time_count}},{{$rangecount}});">{{$time->format('g:ia')}} - @if(strtotime($time->add($interval)->format('g:ia')) > strtotime($data['end_time'][$l])) {{date("g:ia", strtotime($data['end_time'][$l]))}}  @else {{$time->add($interval)->format('g:ia')}} @endif</label>
                                @endif
                            @endif
                    </div>

                        
                    </li>
                    <?php
                    }
                    ?>
                @endforeach
              @endfor
            </ul>
        </span>
    </span>
@endif
@endfor
@endif
