
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Contact25 - Login</title>
    <!-- page css -->
    <link rel="stylesheet" href="{{asset('assets/css/login-register-lock.css')}}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.min.css')}}">
    
	<link rel="stylesheet" href="{{asset('img/flags.png')}}">
	<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/customer/developers.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-default card-no-border">
	@yield('content')
    <script src="{{asset('admin-login/node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/js/main.min.js')}}"></script>
	<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
	<script src="{{asset('assets/js/data.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('admin-login/node_modules/popper/popper.min.js')}}"></script>

    <script src="{{asset('admin-login/node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="https://www.google-analytics.com/analytics.js" async defer></script>
    <script src="{{asset('assets/js/login.js')}}"></script>
    <input type="hidden" value="{{url('/')}}" id="web_url">
</body>

</html>