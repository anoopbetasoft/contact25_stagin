
    @csrf
    <input type="hidden" name="otp_val" value="">
    <input type="hidden" name="contact_no_hidden" value="">
    <div class="alert alert-danger auth_danger" style="display: none"></div>
    <div class="alert alert-success auth_success" style="display: none"></div>
    <div class="form-body">
        <!-- <h4>Holiday</h4> -->
        <label class="control-label text-success" style="font-size:24px">Holiday</label>
        <hr>
        <!-- name -->
        <div class="card-body">
<div class="display_holidays"><div class="col-md-12 col-lg-12 col-xlg-12 col-sm-12 col-xs-12">
                        <label class="control-label text-success" style="font-size:24px"> 
                            <i class="fas fa-calendar-alt"></i> 
                            Holidays
                        </label>
                    </div>
                    <div id="holidaydiv">
                    @foreach($holiday as $holidaylist)
                    @php
                    $timedata = explode(' - ',$holidaylist->start);
                    $timedata2 = explode(' - ',$holidaylist->end);
                    $startday = date("D", strtotime($timedata[0]));
                    $endday = date("D", strtotime($timedata2[0]));


                    $starttime=date_parse_from_format("Y-m-d", $holidaylist->start);
                    $startmonth=date('F', mktime(0, 0, 0, $starttime['month'], 10));
                    $endtime=date_parse_from_format("Y-m-d", $holidaylist->end);
                    $endmonth=date('F', mktime(0, 0, 0, $endtime['month'], 10));

                    $startdata = explode('-',$holidaylist->start);
                    $enddata = explode('-',$holidaylist->end);
                   

                    $starttime = $timedata[1];
                    $starttime = date('g:i', strtotime($starttime));
                    $endtime = $timedata2[1];
                    $endtime = date('g:i', strtotime($endtime));
                    $startdate = $startdata[2];
                    $startyear = $startdata[0];

                    $enddate = $enddata[2];
                    $endyear = $enddata[0];
                    @endphp
 
                    <div id="{{$holidaylist->id}}">
                        <div class="row m-t-40">
                        
                                    <!-- Column -->
                                    <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto;  min-width: 0;">
                                        <div class="card">
                                           <div class="ribbon-vwrapper" style="padding-right:0px; padding-bottom:0px;">
                                    <div class="ribbon ribbon-bookmark ribbon-vertical-l ribbon-info" style="height: 115px;">
                                        <i class="mdi mdi-airplane-takeoff"></i>
                                            </div>
                                            <div>
                                               <h3 class="text-purple">Start</h3>
                                                                                                        
                                                <p class="text-muted" style="font-size: 13px;">{{$startday}} {{$startdate}}  {{$startmonth}} {{$startyear}}</p>
                                                <b style="font-size: 11px;">({{$starttime}})</b> 
                                            </div>
                                            </div>
                                        </div>  
                                    </div>
                                    
                                    <!-- Column -->
                                    
                                    <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto;  min-width: 0;">
                                        <div class="card">
                                            <div class="ribbon-vwrapper" style="padding-right:0px; padding-bottom:0px;">
                                    <div class="ribbon ribbon-bookmark ribbon-vertical-l ribbon-danger" style="height: 115px;">
                                        <i class="mdi mdi-airplane-landing"></i>
                                    </div>
                                                <div>
                                                <h3 class="text-primary">End</h3>
                                                 <p class="text-muted" style="font-size: 13px;">{{$endday}} {{$enddate}}  {{$endmonth}} {{$endyear}}</p>
                                                <b style="font-size: 11px;">({{$endtime}})</b> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    
                                    <div class="col-md-12 col-lg-2 col-xlg-2 col-sm-12 col-xs-12">
                                      <div class="card padding-top:0px;">
                                            <button type="button" class="btn btn-light remove_holiday" style="margin-top:25px;" data-holiday_id="23" onclick="deleteholiday({{$holidaylist->id}});">
                                        <i class="ti-close" aria-hidden="true"></i>
                                        </button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            @endforeach
                            <!-- END OF HOLIDAY LOOP -->
                        </div>  <!-- END OF HOLIDAY DIV -->
                                
                                    <hr>    
                    
    
</div>
                                    
                                                
                                    <form id="add_holiday" method="post">
                                        @csrf
                                     <div class="col-md-8 col-sm-12 col-xs-12">
                                         <label class="control-label text-info" style="font-size:24px"> <i class="fas fa-calendar-alt"></i> Start Time</label>
                                         <div class="input-group mb-3">
                                             <div id="date-format-hidden" style="display: none;">"2019-08-30T04:37:07.205Z"</div>
                                    
                                        
                                         
                                     <input type="text" name="start" id="date-format" class="form-control" placeholder="Saturday 01 June 2019 - 09:00" data-dtp="dtp_KShpN">     
                                         </div>
                                     </div>
 <div class="col-md-8 col-sm-12 col-xs-12">
                                         <label class="control-label text-danger" style="font-size:24px"> <i class="fas fa-calendar-alt"></i> End Time</label>
                                         <div class="input-group mb-3">
                                    <div id="date-format2-hidden" style="display: none;">"2019-09-02T04:37:12.005Z"</div>                            <input type="text" name="end" id="date-format2" class="form-control" placeholder="Monday 24 June 2019 - 21:00" data-dtp="dtp_jT58w">
 </div>
 </div>
<div class="card col-md-12 col-lg-3 col-xlg-3 col-sm-12 col-xs-12">
                      <button class="btn btn-info add_holiday_time" type="submit"><i class="mdi mdi-calendar-plus"></i> Add Holiday</button>          
                    </div>
</div>
     
    </div>