<!doctype html>
<html lang="en-us">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> 
	<!--<meta http-equiv="x-ua-compatible" content="ie=edge">-->
	<title>Log in | Dashboard UI Kit</title>
	<meta name="description" content="Dashboard UI Kit">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600" rel="stylesheet">

	<!-- Favicon -->
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

	<!-- Stylesheet -->
	<link rel="stylesheet" href="{{asset('admin-login/node_modules/bootstrap/dist/css/bootstrap.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/main.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('img/flags.png')}}">
	<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
</head>
<body class="o-page o-page--center">


@yield('content')




<!-- Main javascsript -->
<script src="{{asset('admin-login/node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('assets/js/main.min.js')}}"></script>
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script src="{{asset('assets/js/data.js')}}"></script>

<script>
    window.ga = function () {
        ga.q.push(arguments)
    };
    ga.q = [];
    ga.l = +new Date;
    ga('create', 'UA-88739867-2', 'auto');
    ga('send', 'pageview')
</script>
<script src="https://www.google-analytics.com/analytics.js" async defer></script>


<script type="text/javascript">
    function show(elementId) {
        document.getElementById("login_form").style.display = "none";
        document.getElementById("register_form").style.display = "none";
        document.getElementById("forgot_form").style.display = "none";
        document.getElementById(elementId).style.display = "block";
    }
</script>
@stack('scripts')
</body>

</html> 