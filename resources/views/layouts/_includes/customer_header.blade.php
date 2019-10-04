<!-- header-->
<nav class="navbar top-navbar navbar-expand-md navbar-dark">
    <!-- ============================================================== -->
    <!-- Logo -->
    <!-- ============================================================== -->
    <div class="navbar-header">
        <a class="navbar-brand" href="{{url('/dashboard')}}">
            <!-- Logo icon --><b>
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
                <img src="{{asset('admin-login/images/logo-icon.png')}}" alt="homepage" class="dark-logo" />
                <!-- Light Logo icon -->
                <img src="{{asset('admin-login/images/logo-icon.png')}}" alt="homepage" class="light-logo" />
            </b>
            <!--End Logo icon -->
            <!-- Logo text --><span>
             <!-- dark Logo text -->
             <img src="{{asset('admin-login/images/logo-text.png')}}" alt="homepage" class="dark-logo" />
             <!-- Light Logo text -->  
             <img src="{{asset('admin-login/images/logo-text.png')}}" class="light-logo" alt="homepage" /></span> </a>
    </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse">
        <!-- ============================================================== -->
        <!-- toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav mr-auto">
            <!-- This is  -->
            <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
            <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
            <!-- ============================================================== -->
            <!-- Search -->
            <!-- ============================================================== -->
            <li class="nav-item">
                <form class="app-search d-none d-md-block d-lg-block" action="/search" method="post">
                    @csrf
                    <input type="text" class="form-control web-search" name="keyword" placeholder="Search ..." >
                </form>
            </li>
        </ul>
        <!-- ============================================================== -->
        <!-- User profile and search -->
        <!-- ============================================================== -->
        <ul class="navbar-nav my-lg-0">
            @if(session('impersonated_by') )
            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="{{ url('/impersonate_leave') }}"><em class="fas fa-sign-out-alt" title="Back to my account"></em></a>
            </li> 
            @endif
            
            <!-- ============================================================== -->
            <!-- Comment -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown search">
                <a class="nav-link dropdown-toggle waves-effect waves-dark" id="searchclick" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><em class="fas fa-search"></em></a>

            </li>

           <!--  <div class="mobile_search">
                 <form class="" action="/search" method="post">
                     @csrf
                    <input type="text" class="form-control" id="mobilesearch" name="keyword" placeholder="Search" style="display: none;">
                </form>
            </div> -->
            <!-- ============================================================== -->
            <!-- End Comment -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Messages -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown">
                <a class="nav-link waves-effect waves-dark" href="{{url('/myproducts')}}" aria-haspopup="true" aria-expanded="false"><i class="ti-package"></i></a>
            </li>
            @if(Auth::user()->location=='')
            <li class="nav-item dropdown"> <a id="addproductbutton" class="nav-link waves-effect waves-dark" aria-haspopup="true" aria-expanded="false" onclick="showprofileerror();"><i class="fa fa-plus"></i></a>
            </li>
            @else
            <li class="nav-item dropdown"> <a class="nav-link waves-effect waves-dark" href="{{url('/add_product')}}" aria-haspopup="true" aria-expanded="false"><i class="fa fa-plus"></i></a>
            </li>
            @endif
            @if(Auth::user()->order_status=='1')
            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <i class="fa fa-signal"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have <?php echo $noofsale; ?> new sales.</div>
                                    </li>
                                    <li>
                                        <div class="message-center ps ps--theme_default" data-ps-id="c39bbb6b-096d-f1ce-af3e-d24535a85ba8">
                                            <!-- Message -->
                                            @foreach($salecount as $sales)
                                            @php
                                            $image = explode(',',$sales->p_image);
                                            $date1 = strtotime($sales->created_at);  
                                            $date2 = strtotime(date('Y-m-d h:i:s'));  
                                              
                                            // Formulate the Difference between two dates 
                                            $diff = abs($date2 - $date1); 

                                            $years = floor($diff / (365*60*60*24));  
                                              
                                              
                                            // To get the month, subtract it with years and 
                                            // divide the resultant date into 
                                            // total seconds in a month (30*60*60*24) 
                                            $months = floor(($diff - $years * 365*60*60*24) 
                                                                           / (30*60*60*24));  
                                              
                                              
                                            // To get the day, subtract it with years and  
                                            // months and divide the resultant date into 
                                            // total seconds in a days (60*60*24) 
                                            $days = floor(($diff - $years * 365*60*60*24 -  
                                                         $months*30*60*60*24)/ (60*60*24)); 
                                              
                                              
                                            // To get the hour, subtract it with years,  
                                            // months & seconds and divide the resultant 
                                            // date into total seconds in a hours (60*60) 
                                            $hours = floor(($diff - $years * 365*60*60*24  
                                                   - $months*30*60*60*24 - $days*60*60*24) 
                                                                               / (60*60)); 
                                           
                                            @endphp
                                            <a href="/my_sales">
                                                <div class="user-img"> <img src='{{asset("uploads/products/$image[0]")}}' alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>{{$sales->p_title}}</h5> <span class="mail-desc"></span> <span class="time">{{$days}} Days {{$hours}} Hours</span><i class="fa fa-truck" style="color:#9675ce;"> </i><span style="color:#9675ce;"> Deliver</span></div>
                                            </a>
                                            <!-- Message -->
                                            @endforeach
                                        <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px;"><div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="/my_sales"> <strong>All your sales</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
            @endif
            <!-- ============================================================== -->
            <!-- End Messages -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- mega menu -->
            <!-- ============================================================== -->
            
            <!-- ============================================================== -->
            <!-- End mega menu -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- User Profile -->
            <!-- ============================================================== -->
            <!-- <li class="nav-item dropdown u-pro">
                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user" class=""> <span class="hidden-md-down">Mark &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                <div class="dropdown-menu dropdown-menu-right animated flipInY">
                  
                    <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                  
                    <a href="javascript:void(0)" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a>
                   
                    <a href="javascript:void(0)" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                   
                    <div class="dropdown-divider"></div>
                    
                    <a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
                  
                    <div class="dropdown-divider"></div>
                   
                    <a class="dropdown-item"  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i>Log Out</a>
                   
                </div>
            </li> -->
            <!-- ============================================================== -->
            <!-- End User Profile -->
            <!-- ============================================================== -->
            <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
        </ul>
    </div>
</nav>