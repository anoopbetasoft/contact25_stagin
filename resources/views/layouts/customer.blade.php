<!-- layout-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(isset($description) && $description!='')
    <meta name="description" content="{{ $description }}">
    @else
    <meta name="description" content="">
    @endif
    @if(isset($robot) && $robot=='yes')
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    @endif
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon.png')}}">
    <title>
 @if(isset($title))
            {{ $title }}
@else        
    {{ config('app.name') }}
@endif
</title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.css" id="theme-styles">
    <link href="{{asset('assets/node_modules/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('assets/node_modules/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
    <!--Toaster Popup message CSS -->

    <link href="{{asset('assets/node_modules/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"> 
    <link rel="stylesheet" href="{{asset('assets/css/customer/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/customer/style.min.css')}}">
    @if(Request::route()->getName() == "view_profile")
    <link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/node_modules/switchery/dist/switchery.min.css')}}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    @elseif(Request::route()->getName() == "add_product" || Request::route()->getName() == "edit_product")
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.css" id="theme-styles">
    <!-- <link rel="stylesheet" href="{{asset('assets/node_modules/bootstrap/dist/css/bootstrap.min.css')}}"> -->
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link href="{{asset('assets/css/tab-page.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/customer/developers.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">

    <link rel="stylesheet" href="{{asset('assets/node_modules/icheck/skins/all.css')}}">
    <link href="{{asset('assets/css/customer/form-icheck.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
    
    <link rel="stylesheet" href="assets/css/customer/ribbon-page.css">

    <!-- Dashboard 1 Page CSS -->
    <!-- <link href="dist/css/pages/dashboard1.css" rel="stylesheet"> -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
.mobile_search {
      position: absolute;
    z-index: 1;
    top: 67px;
    left: 0;
    width: 100%;
}
.mobile_search .form-control {
   width: 100%;
    border-radius: 20px;
    border: 1px solid #e8e5e5;
    background: #fff;
}
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
@media (min-width:1024px){
   .mobile_search{
    display: none;
   }
   .search
   {
    display: none;
   }
   .web-search
   {
    display: none;
   }
   #hidep
   {
    display: inline-block;
   }
}
@media (min-width:800px){ 
   .search
   {
    display: none;
   }
   .web-search
   {
    display: none;
   }
   #hidep
   {
    display: block;
   }
}
@media (max-width:767px){
.swal2-container{
z-index:9999;}
}

</style>
    <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>
</head>

<body class="skin-default fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">{{config('app.name')}}</p>
        </div>
    </div>
    <div id="main-wrapper">
        <header class="topbar">
            <!-- include header -->
            @include('layouts._includes.customer_header')
            
        </header>
         <div class="mobile_search">
                 <form class="" action="/search" method="post">
                     @csrf
                    <input type="text" class="form-control" id="mobilesearch" name="keyword" placeholder="Search" style="display: none;">
                </form>
            </div>
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            @include('layouts._includes.customer_left_sidebar')
            <!-- left sidebar -->
            <!-- End Sidebar scroll-->
        </aside>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div style="padding: 10px;"></div>
                @yield('content')
                @include('layouts._includes.customer_right_sidebar')
            </div>
        </div>
       <!--  <footer class="footer">
            © 2018 Eliteadmin by themedesigner.in
        </footer> -->
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    <input type="hidden" value="{{url('/')}}" id="weburl">
    <script src="{{asset('admin-login/node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('assets/node_modules/morrisjs/morris.min.js')}}"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="{{asset('admin-login/node_modules/popper/popper.min.js')}}"></script>
    <script src="{{asset('admin-login/node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
     <script src="{{asset('assets/js/customer/perfect-scrollbar.jquery.min.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('assets/js/customer/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('assets/js/customer/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('assets/js/customer/custom.min.js')}}"></script>
    <!--morris JavaScript -->
    <script src="{{asset('assets/node_modules/raphael/raphael-min.js')}}"></script>
    
    <script src="{{asset('assets/node_modules/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- Popup message jquery -->

    <script src="{{asset('assets/node_modules/toast-master/js/jquery.toast.js')}}"></script>
    <!-- Chart JS -->
     <script src="{{asset('assets/js/customer/dashboard1.js')}}"></script>
    <!-- <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script> -->
    <!-- dropify -->
    <script src="{{asset('assets/node_modules/dropify/dist/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/node_modules/moment/moment.js')}}"></script>

    <script src="{{asset('assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <script src="{{asset('assets/node_modules/icheck/icheck.min.js')}}"></script>
    <script src="{{asset('assets/node_modules/icheck/icheck.init.js')}}"></script>

    @if(Request::route()->getName() == "view_profile")
    <script src="{{asset('assets/js/customer/view_profile.js')}}"></script>
    <script src="{{asset('assets/js/customer/add_holiday.js')}}"></script>
    <script src="{{asset('assets/js/intlTelInput.js')}}"></script>
    <script src="{{asset('assets/js/data.js')}}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{asset('assets/node_modules/switchery/dist/switchery.min.js')}}"></script>
    
    @elseif(Request::route()->getName() == "product_add")
    <script src="{{asset('assets/js/customer/product_add.js')}}"></script>
    @elseif(Request::route()->getName() == "add_product" || Request::route()->getName() == "edit_product")
    <script src="{{asset('assets/node_modules/jqueryui/jquery-ui.min.js')}}"></script> 
    <script src="{{asset('assets/js/customer/add_product.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.js"></script>

     @elseif(Request::route()->getName() == "products_page")
     <script src="https://js.braintreegateway.com/web/3.50.1/js/client.min.js"></script>

<!-- Load Hosted Fields component. -->
<script src="https://js.braintreegateway.com/web/3.50.1/js/hosted-fields.min.js"></script>
    <script src="{{asset('assets/js/customer/product_page.js')}}"></script>
    @endif
    <script src="{{asset('assets/js/customer/right_sidebar.js')}}"></script>
       <script type="text/javascript">
        $(document).ready(function() {
            // Basic
            $('.dropify').dropify();
            init();
            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });
        $('.selectpicker').selectpicker();
        
    </script>
    <script src="{{asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="{{asset('assets/node_modules/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/node_modules/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script>
    // MAterial Date picker    
    $('#mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
    $('#timepicker').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
    $('#date-format').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD  - HH:mm:ss' });
    $('#date-format2').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD - HH:mm:ss' });

    /*function initialize_datepicker(data){
    var id  = $(data).attr('id');
    $("#"+id).bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
    }*/
    $('#min-date').bootstrapMaterialDatePicker({ format: 'DD/MM/YYYY HH:mm', minDate: new Date() });
    // Clock pickers
    $('#single-input').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    $('.clockpicker').clockpicker({
        donetext: 'Done',
    }).find('input').change(function() {
        console.log(this.value);
    });
    $('#check-minutes').click(function(e) {
        // Have to stop propagation here
        e.stopPropagation();
        input.clockpicker('show').clockpicker('toggleView', 'minutes');
    });
    if (/mobile/i.test(navigator.userAgent)) {
        $('input').prop('readOnly', true);
    }
    // Colorpicker
    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker({
        mode: 'gradient'
    });
    // Date Picker
    jQuery('.mydatepicker, #datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    jQuery('#date-range').datepicker({
        toggleActive: true
    });
    jQuery('#datepicker-inline').datepicker({
        todayHighlight: true
    });
    // Daterange picker
    $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-daterange-timepicker').daterangepicker({
        timePicker: true,
        format: 'MM/DD/YYYY h:mm A',
        timePickerIncrement: 30,
        timePicker12Hour: true,
        timePickerSeconds: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    $('.input-limit-datepicker').daterangepicker({
        format: 'MM/DD/YYYY',
        minDate: '06/01/2015',
        maxDate: '06/30/2015',
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        dateLimit: {
            days: 6
        }
    });
    </script>
    <script type="text/javascript">
        $('#searchclick').click(function(){
            $('#mobilesearch').toggle();
        })
    </script>
</body>

</html>