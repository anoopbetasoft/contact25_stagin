<!DOCTYPE html>
<html>
<head>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif;">
    
    <div class="logo" style="text-align: center; padding: 30px 0px;">

       <a href="{{URL::to('/')}}/dashboard" target="_blank"><img src="{{asset('admin-login/images/logo-text.png')}}" alt="{{config('app.name')}}" title="{{config('app.name')}}" data-auto-embed="attachment"/></a>

    </div>
    <div class="mail-content" style="padding: 0px 100px;">
        {{ Illuminate\Mail\Markdown::parse($slot) }}{{ $subcopy ?? '' }}
    
    </div>
</body>
</html>
