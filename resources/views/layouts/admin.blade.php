<!doctype html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Home Overview | Dashboard UI Kit</title>
	<meta name="description" content="Dashboard UI Kit">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!-- Favicon -->
	<link rel="apple-touch-icon" href="apple-touch-icon.png">

	<link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/images/favicon.png')}}">

	<!-- Stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/css/main.min.css')}}">
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

	@if(Request::route()->getName() == "account-settings")
	<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/node_modules/switchery/dist/switchery.min.css')}}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	@endif
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/css/bootstrap-select.min.css">
</head>
<body class="o-page">


@include('layouts._includes.sidebar')

<main class="o-page__content">

	@include('layouts._includes.header')
	@yield('content')
	<input type="hidden" id="admin-url" value="{{url('/')}}">
</main>

<!-- Main javascsript -->

<script src="{{asset('admin-login/node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('admin-login/node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="https://dashboard.zawiastudio.com/demo/js/main.min.js?v=2.0"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

@if(Request::route()->getName() == "account-settings")
<script src="{{asset('admin-login/js/account.js')}}"></script>
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script src="{{asset('assets/js/data.js')}}"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{asset('assets/node_modules/switchery/dist/switchery.min.js')}}"></script>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/js/bootstrap-select.min.js"></script>
    
<script>
	//tooltip
	$( function() {
	    $(document).tooltip();
	});
    window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
    ga('create','UA-88739867-2','auto');ga('send','pageview')
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });
    $('.selectpicker').selectpicker();
</script>
<script src="https://www.google-analytics.com/analytics.js" async defer></script>
</body>
</html>