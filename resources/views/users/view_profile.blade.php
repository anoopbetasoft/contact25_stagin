@extends('layouts.customer')


@section('content')
    <style type="text/css">

        .form-control {
            color: #777;
            min-height: 38px;
            /*display: initial;*/
            display: block;
        }

        form label {
            color: #777;
        }

        .profile-tabs.nav-tabs .nav-link.active {
            border-bottom: 2px solid #fb9678;
            color: #fb9678;
            border-color: #fff #fff #fb9678;
        }

        .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
            border-color: #fff #fff #fff;
        }

        .deliverydescription {
            resize: vertical;
        }

        @media (max-width: 550px) {
            .vtabs .tabs-vertical {
                display: table-row;
            }

            .vtabs .tab-content {
                padding: 20px 0px;

            }

            .nav-tabs .nav-item {
                width: 100%
            }

           /* .checkbox {
                padding-left: 0px;
                line-height: 14px;
            }*/

            .checkbox label {
                display: block;
            }
        }
    </style>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ucwords(Auth::user()->name)}}</h4>
                    <h6 class="card-subtitle"></h6>
                    <input type="hidden" value="{{$uDetails[0]['contact_country']}}" id="contact_country">
                    <!-- Nav tabs -->
                    <div class="">
                        <ul class="nav nav-tabs profile-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home2"
                                                    role="tab"><span class="hidden-sm-up"><i
                                                class="mdi mdi-account"></i></span> <span
                                            class="hidden-xs-down">Profile</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#holiday2" role="tab"><span
                                            class="hidden-sm-up"><i class="mdi mdi-airplane-takeoff"></i></span> <span
                                            class="hidden-xs-down">Holiday</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#delivery" role="tab"><span
                                            class="hidden-sm-up"><i class="mdi mdi-truck"></i></span> <span
                                            class="hidden-xs-down">Delivery</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#return" role="tab"><span
                                            class="hidden-sm-up"><i class="fas fa-truck-loading"
                                                                    style="font-size:11px;"></i></span> <span
                                            class="hidden-xs-down">Returns</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#communication" role="tab"><span
                                            class="hidden-sm-up"><i class="mdi mdi-email"></i></span> <span
                                            class="hidden-xs-down">Communication</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profile2" role="tab"><span
                                            class="hidden-sm-up"><i class="mdi mdi-key"></i></span> <span
                                            class="hidden-xs-down">Security</span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#return_options"
                                                    role="tab"><span class="hidden-sm-up"><i class="fas fa-truck-loading"
                                                                                             style="font-size:11px;"></i></span>
                                    <span class="hidden-xs-down">Return Options</span></a></li>
                            <!-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages2" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Messages</span></a> </li> -->
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- profile app -->
                            <div class="tab-pane active" id="home2" role="tabpanel">

                                @include('users.view_profile.profile', [
                                "uDetails[0]['email']"=> $uDetails[0]['email'],
                                "uDetails[0]['name']"=> $uDetails[0]['name'],
                                "uDetails[0]['contact_no']"=> $uDetails[0]['contact_no'],
                                "uDetails[0]['avatar']" =>$uDetails[0]['avatar']
                                ])

                            </div>
                            <!-- security tab -->
                            <?php
                            if ($uDetails[0]['two_way_auth'] == 1) {
                                $style_val = "display:block;";
                                $twoauth_val = "Enabled";
                                $check_val = "checked";
                            } else {
                                $style_val = "display:none;";
                                $twoauth_val = "Disabled";
                                $check_val = "";
                            }
                            ?>
                            <div class="tab-pane" id="profile2" role="tabpanel">

                                @include('users.view_profile.security', ['style_val'=> $style_val,'twoauth_val'=> $twoauth_val, 'check_val'=> $check_val])

                            </div>
                            <div class="tab-pane" id="holiday2" role="tabpanel">

                                @include('users.view_profile.holiday', ['style_val'=> $style_val,'twoauth_val'=> $twoauth_val, 'check_val'=> $check_val])

                            </div>
                            <div class="tab-pane" id="delivery" role="tabpanel">

                                @include('users.view_profile.delivery', ['style_val'=> $style_val,'twoauth_val'=> $twoauth_val, 'check_val'=> $check_val])

                            </div>
                            <div class="tab-pane" id="return" role="tabpanel">

                                @include('users.view_profile.return', ['style_val'=> $style_val,'twoauth_val'=> $twoauth_val, 'check_val'=> $check_val])

                            </div>
                            <div class="tab-pane" id="communication" role="tabpanel">

                                @include('users.view_profile.communication', ['style_val'=> $style_val,'twoauth_val'=> $twoauth_val, 'check_val'=> $check_val])

                            </div>
                            <div class="tab-pane" id="return_options" role="tabpanel">

                                @include('users.view_profile.return_options', ['style_val'=> $style_val,'twoauth_val'=> $twoauth_val, 'check_val'=> $check_val])

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection