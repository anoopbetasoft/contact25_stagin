@php
    $sellingoption = App\User::where('id',Auth::id())->get();
@endphp
<!-- Modal -->
<div id="friend_group" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create/Edit Friend Group</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

</div>
</div>
<div class="right-sidebar ps ps--theme_default" data-ps-id="c2d03ecc-fc4e-3c6d-efa7-053658f4fd96">

    <div class="slimscrollright">

        <div class="rpanel-title" style="background-color:#ec038d"> Settings
            <span><i class="ti-close right-side-toggle"></i></span>
        </div>
        <div class="r-panel-body">

            <div class="user_settings">
                <ul>
                    <li>
                        <b>
                            <span style="color: #ec038d">Sell to </span> <span
                                    style="font-size:20px; color:green">*</span>
                        </b>
                    </li>
                    <li>
                        <div class="btn-group sell_to_friends" data-toggle="buttons" onclick="sell_to_friends()">
                            <label class="btn sell_to_friends" style="padding:0px;">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" name="sell_to_friends"
                                           class="custom-control-input checkbox-danger friendinput" id="sell_to_friends"
                                           value="sell to friend to database" @if($sellingoption[0]['sell_to_friend']=='1') {{'checked'}} @endif>
                                    <label class="custom-control-label text-danger"
                                           for="sell_to_friends">Friends</label>
                                </div>
                            </label>
                            <br>
                        </div>

                        <!-- <i class="fas fa-chevron-down" data-toggle="modal" data-target="#friend_group"></i> -->
                        @php
                            $friend_groups = App\friend_group::where('user_id',Auth::user()->id)->get();
                        @endphp

                        @foreach($friend_groups as $friendgroup)
                            <div class="btn-group sell_to_friends_of_friends" data-toggle="buttons"
                                 onclick="updategroupsellto({{$friendgroup->id}});">
                                <label class="btn sell_to_{{$friendgroup->id}}" style="padding:0px;">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input checkbox-info"
                                               id="sell_to_{{$friendgroup->id}}" @if($friendgroup->sell_to=='1') {{'checked'}} @endif>
                                        <label class="custom-control-label text-info"
                                               for="sell_to_{{$friendgroup->id}}">{{$friendgroup->group_name}}</label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                        <div class="btn-group sell_to_neighbours" data-toggle="buttons" onclick="sell_to_neighbours()">
                            <label class="btn sell_to_neighbours" style="padding:0px;">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input"
                                           id="sell_to_neighbours" @if($sellingoption[0]['sell_to_neighbour']=='1') {{'checked'}} @endif>
                                    <label class="custom-control-label text-purple"
                                           for="sell_to_neighbours">Neighbours</label>
                                </div>
                            </label>
                        </div>

                        <div class="btn-group sell_to_uk" data-toggle="buttons" onclick="sell_to_uk()">
                            <label class="btn sell_to_uk" style="padding:0px;">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input"
                                           id="sell_to_uk" @if($sellingoption[0]['sell_to_uk']=='1') {{'checked'}} @endif>
                                    <label class="custom-control-label text-success"
                                           for="sell_to_uk">{{Auth::user()->country}}</label>
                                </div>
                            </label>
                        </div>
                    </li>
                </ul>
                <ul>
                    <li>
                        <b>
                            <span style="color: #ec038d">Lend to </span><span
                                    style="font-size:20px; color:green">*</span>
                        </b>
                    </li>
                    <li>
                        <div class="btn-group lend_to_friends" data-toggle="buttons" onclick="lend_to_friends()">
                            <label class="btn lend_to_friends" style="padding:0px;">
                                <div class="custom-control custom-checkbox mr-sm-2 ">
                                    <input type="checkbox" class="custom-control-input checkbox-danger"
                                           id="lend_to_friends" @if($sellingoption[0]['lend_to_friend']=='1') {{'checked'}} @endif>
                                    <label class="custom-control-label text-danger"
                                           for="lend_to_friends">Friends</label>
                                </div>
                            </label><br>
                        </div>
                        @foreach($friend_groups as $friendgroup)
                            <div class="btn-group sell_to_friends_of_friends" data-toggle="buttons"
                                 onclick="updategrouplendto({{$friendgroup->id}});">
                                <label class="btn sell_to_{{$friendgroup->id}}" style="padding:0px;">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input checkbox-info"
                                               id="lend_to_{{$friendgroup->id}}" @if($friendgroup->lend_to=='1') {{'checked'}} @endif>
                                        <label class="custom-control-label text-info"
                                               for="lend_to_{{$friendgroup->id}}">{{$friendgroup->group_name}}</label>
                                    </div>
                                </label>
                            </div>
                    @endforeach

                    <!--  <div class="btn-group lend_to_friends_of_friends" data-toggle="buttons" onclick="updatesellto();">
                            <label class="btn lend_to_friends_of_friends" style="padding:0px;">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input checkbox-info" id="lend_to_friends_of_friends" @if($sellingoption[0]['lend_to_friend_of_friend']=='1') {{'checked'}} @endif>
                                    <label class="custom-control-label text-info" for="checkbox1">Friends of Friends</label>
                                </div>
                            </label>
                        </div>
 -->
                        <div class="btn-group lend_to_neighbours" data-toggle="buttons" onclick="lend_to_neighbours()">
                            <label class="btn lend_to_neighbours" style="padding:0px;">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input"
                                           id="lend_to_neighbours" @if($sellingoption[0]['lend_to_neighbour']=='1') {{'checked'}} @endif>
                                    <label class="custom-control-label text-purple"
                                           for="lend_to_neighbours">Neighbours</label>
                                </div>
                            </label>
                        </div>

                        <div class="btn-group lend_to_uk" data-toggle="buttons" onclick="lend_to_uk()">
                            <label class="btn lend_to_uk" style="padding:0px;">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input"
                                           id="lend_to_uk" @if($sellingoption[0]['lend_to_uk']=='1') {{'checked'}} @endif>
                                    <label class="custom-control-label text-success"
                                           for="lend_to_uk">{{Auth::user()->country}}</label>
                                </div>
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
            <span style="font-size:20px; color:green">*</span>
            <span style="font-size:10px;">These are just defaults (who you sell/share your stuff with is up to you)
            </span>
            <ul id="themecolors" class="m-t-20">
                <li class="text-info"><b>Opening Hours</b><i class="ti-location-pin"></i></li>
                <li style="font-size:10px">(times you can accept a collection)</li>
                <li>
                    <ul class="list_open_hours">
                        @php
                            $user_hrs = App\Users_opening_hr::where('user_id',Auth::user()->id)->orderBy('user_day','ASC')->get();
                        @endphp
                        @if(count($user_hrs) > 0)

                             @foreach($user_hrs as $key => $hrs)
                                @php
                                $filtered = array();
                                        if($hrs['break_time']>'0')
                                        {
                                            $filterstarttime = explode(',',$hrs['user_start_time']);
                                            $filterendtime = explode(',',$hrs['user_end_time']);
                                            for($j=0;$j<count($filterstarttime);$j++)
                                            {
                                            $filterstarttime[$j] = date("g:i a", strtotime($filterstarttime[$j]));
                                            $filterendtime[$j] = date("g:i a", strtotime($filterendtime[$j]));
                                            $filtered[$j] = $filterstarttime[$j].' - '.$filterendtime[$j];
                                            }
                                            $filteredfinal = implode(',',$filtered);
                                        }
                                        else
                                        {
                                            $filteredfinal = date("g:i a", strtotime($hrs['user_start_time'])).' - '.date("g:i a", strtotime($hrs['user_end_time']));
                                        }
                                @endphp
                                <li id="hour_id_{{$hrs['id']}}"
                                    style="font-size:10px">{{ucwords($hrs['user_day_name'])}}
                                    ({{$filteredfinal}}) <span class="text-danger"
                                                                                                 data-user_hr_id="{{$hrs['id']}}"
                                                                                                 onclick="removeOpenHour(this);"> <i
                                                class="fa fa-trash" aria-hidden="true"></i> </span></li>
                            @endforeach

                        @endif
                    </ul>
                    <?php //echo "<pre>";print_r($user_hrs); echo "</pre>"; ?>
                </li>

                <li class="open_times" style=" font-size:11px"></li>

                <ul class="hours-select clearfix inline-lasyout up-4" style="font-size:10px;">
                    <li>
                        <select class="weekday text-info">
                            <option value="1" data-user_day_name="monday">Mon</option>
                            <option value="2" data-user_day_name="tuesday">Tue</option>
                            <option value="3" data-user_day_name="wednesday">Wed</option>
                            <option value="4" data-user_day_name="thursday">Thur</option>
                            <option value="5" data-user_day_name="friday">Fri</option>
                            <option value="6" data-user_day_name="saturday">Sat</option>
                            <option value="7" data-user_day_name="sunday">Sun</option>
                        </select>
                        <?php
                        $begin = new DateTime("00:00");
                        $end = new DateTime("24:00");
                        $interval = DateInterval::createFromDateString('30 min');
                        $times = new DatePeriod($begin, $interval, $end);
                        ?>
                        <select class="hours_start text-info">
                            <?php
                            foreach ($times as $time) {

                                if ($time->format('H:i:s') == '09:00:00') {
                                    $select_start = 'selected';
                                } else {
                                    $select_start = '';
                                }

                                echo '<option value="' . $time->format('H:i:s') . '" ' . $select_start . ' >' . $time->format('h:i  a') . '</option>';
                            }
                            ?>
                        </select>

                        <select class="hours_end text-info">
                            <?php
                            foreach ($times as $time) {
                                if ($time->format('H:i:s') == '17:00:00') {
                                    $select_end = 'selected';
                                } else {
                                    $select_end = '';
                                }

                                echo '<option value="' . $time->format('H:i:s') . '" ' . $select_end . ' >' . $time->format('h:i  a') . '</option>';
                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        <button class="btn btn-rounded btn-info  add_opening_times" onclick="add_opening_times(this);">
                            Add Opening Hours
                        </button>
                    </li>
                    <br>
                    <br> <span style="float:right"><a href="{{route('view_profile')}}">Advanced Settings</a></span>
                </ul>
            </ul>

        </div>
    </div>
    <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;">
        <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px;">
        <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
    </div>
</div>