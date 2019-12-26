
    $(document).ready(function(){
        $( ".itemcounter-class" ).each(function( index ) {
             //loopcounter('itemcounter-'+index);
            var time = document.getElementById("itemcounter-class-"+index).value;
            var countDownDate = new Date(time).getTime();

            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;
                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
               // If the count down is over, write some text
                if (distance < 0 || time=='') {
                    var message = $('#collectitemmessage-'+index).val();
                    clearInterval(x);
                    document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-map-marker text-success\" style=\"color:#00c292\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600;font-size:11px;'> "+message+" </h4></b>";
                    $('#collectiontime-'+index).remove();
                }
                else
                {
                    var values = sortdays(days,hours,minutes,seconds);
                    var dayheading = values[0];
                    var hourheading = values[1];
                    var minuteheading = values[2];
                    var secondheading = values[3];
                    if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                    {
                        document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                    {
                        document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                    }
                    else if(days!=0 && minutes>9) // Show days and don't show seconds
                    {
                        document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days!=0 && minutes<10)  // show days and show seconds
                    {
                        document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                    }
                }
            }, 1000);
            //console.log('itemcounter-'+index);
        });
        $( ".itemdispatchcounter-class" ).each(function( index ) {
            //loopcounter('itemdispatchcounter-'+index);
            //console.log('itemdispatchcounter-'+index);
            var time = document.getElementById("itemdispatchcounter-class-"+index).value;
            var countDownDate = new Date(time).getTime();

            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"

                // If the count down is over, write some text
                if (distance < 0 || time=='') {
                    clearInterval(x);
                    document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-truck\" style=\"color:#9675ce\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600;font-size:12px;'> Overdue - Dispatch as soon as possible! </h4></b>";
                }
                else
                {
                    var values = sortdays(days,hours,minutes,seconds);
                    var dayheading = values[0];
                    var hourheading = values[1];
                    var minuteheading = values[2];
                    var secondheading = values[3];
                    if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                    {
                        document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                    {
                        document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + " " + secondheading + " )";
                    }
                    else if(days!=0 && minutes>9) // Show days and don't show seconds
                    {
                        document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days!=0 && minutes<10)  // show days and show seconds
                    {
                        document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                    }
                }
            }, 1000);
        });
        $( ".serviceitemcounter-class" ).each(function( index ) {
            //loopcounter('serviceitemcounter-'+index);
            // console.log('serviceitemcounter-'+index);
            var time = document.getElementById("demotimeserviceitemcounter-"+index).value;
            var countDownDate = new Date(time).getTime();
            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"


                // If the count down is over, write some text
                if (distance < 0 || time=='') {
                    clearInterval(x);
                    document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600'> Overdue </h4></b>";
                }
                else
                {
                    var values = sortdays(days,hours,minutes,seconds);
                    var dayheading = values[0];
                    var hourheading = values[1];
                    var minuteheading = values[2];
                    var secondheading = values[3];
                    if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                    {
                        document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                    {
                        document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                    }
                    else if(days!=0 && minutes>9) // Show days and don't show seconds
                    {
                        document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days!=0 && minutes<10)  // show days and show seconds
                    {
                        document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                    }
                }
            }, 1000);

        });
        $( ".subscriptioncounter-class" ).each(function( index ) {
            //loopcounter('subscriptioncounter-'+index);
            // console.log('serviceitemcounter-'+index);
            var time = document.getElementById("subscriptioncounter-"+index).value;
            var countDownDate = new Date(time).getTime();

            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"


                // If the count down is over, write some text
                if (distance < 0 || time=='') {
                    clearInterval(x);
                    document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-map-marker text-success\" style=\"color:#00c292\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600'> Overdue </h4></b>";
                }
                else
                {
                    var values = sortdays(days,hours,minutes,seconds);
                    var dayheading = values[0];
                    var hourheading = values[1];
                    var minuteheading = values[2];
                    var secondheading = values[3];
                    if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                    {
                        document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                    {
                        document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                    }
                    else if(days!=0 && minutes>9) // Show days and don't show seconds
                    {
                        document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + ") ";
                    }
                    else if(days!=0 && minutes<10)  // show days and show seconds
                    {
                        document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                    }
                }
            }, 1000);

        });
    });

function sorting(sortinvalue)               // To Sort the page according to the type selected
{
   // alert(sortinvalue);
    var mainUrl = $("#weburl").val();
    if(sortinvalue!='pending' && sortinvalue!='cancelled' && sortinvalue!='completed' && sortinvalue!='dispatched')
    {
        var fixfilter = $('.filter1').find('.active').text();
    }
    else
    {
        var fixfilter = '';
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl+"/my_sales_ajax",
        data:{'sort_by':sortinvalue,'fixfilter':fixfilter},
        success: function(data) {
            $('.preloader').css('display','block');
            setTimeout(function () {
                $(".preloader").css('display','none');
                if(sortinvalue=='pending')
                {
                    $('.ordersfilter').removeClass('active');
                    $('.pending_orders').addClass('active');
                }
                if(sortinvalue=='cancelled')
                {
                    $('.ordersfilter').removeClass('active');
                    $('.cancelled_orders').addClass('active');
                }
                if(sortinvalue=='completed')
                {
                    $('.ordersfilter').removeClass('active');
                    $('.completed_orders').addClass('active');
                }
                if(sortinvalue=='dispatched')
                {
                    $('.ordersfilter').removeClass('active');
                    $('.dispatched').addClass('active');
                }
                /*if(sortinvalue!='pending' && sortinvalue!='cancelled' && sortinvalue!='completed')
                {
                    $('.ordersfilter').removeClass('active');
                }*/
                $('.display_my_sales').html(data);
                countdowntimer();
            }, 850); // in milliseconds
        },
        error: function(data) {
            //alert("Some error occured"); //location.reload(); return false;
            Swal.fire('Error occured while fetching data');
        }
    });
}
function countdowntimer() {
    $( ".itemcounter-class" ).each(function( index ) {
       //loopcounter('itemcounter-'+index);
        var time = document.getElementById("itemcounter-class-"+index).value;
        var countDownDate = new Date(time).getTime();

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            // If the count down is over, write some text
          /*  document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-map-marker\" style=\"color:#00c292\"></i> <h4 style='color: red;display: inline-block'> "+distance+ "</h4>";*/
            if (distance < 0 ||time=='') {
                var message = $('#collectitemmessage-'+index).val();
                clearInterval(x);
                document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-map-marker text-success\" style=\"color:#00c292\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600;font-size:11px;'> "+message+" </h4></b>";
                $('#collectiontime-'+index).remove();
            }
            else
            {
                var values = sortdays(days,hours,minutes,seconds);
                var dayheading = values[0];
                var hourheading = values[1];
                var minuteheading = values[2];
                var secondheading = values[3];
                if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                {
                    document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                }
                else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                {
                    document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                }
                else if(days!=0 && minutes>9) // Show days and don't show seconds
                {
                    document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + ") ";
                }
                else if(days!=0 && minutes<10)  // show days and show seconds
                {
                    document.getElementById("itemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                }
            }
        }, 1000);
        //console.log('itemcounter-'+index);
    });
    $( ".itemdispatchcounter-class" ).each(function( index ) {
        //loopcounter('itemdispatchcounter-'+index);
        //console.log('itemdispatchcounter-'+index);
        var time = document.getElementById("itemdispatchcounter-class-"+index).value;
        var countDownDate = new Date(time).getTime();

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"


            // If the count down is over, write some text
           /* document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-truck\" style=\"color:#00c292\"></i> <h4 style='color: red;display: inline-block'> "+distance+ "</h4>";*/
            if (distance < 0 || time=='') {
                clearInterval(x);
                document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-truck\" style=\"color:#9675ce\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600;font-size:12px;'> Overdue - Dispatch as soon as possible! </h4></b>";
            }
            else
            {
                var values = sortdays(days,hours,minutes,seconds);
                var dayheading = values[0];
                var hourheading = values[1];
                var minuteheading = values[2];
                var secondheading = values[3];
                if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                {
                    document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                }
                else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                {
                    document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") " + secondheading + " ";
                }
                else if(days!=0 && minutes>9) // Show days and don't show seconds
                {
                    document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + ") "+ minuteheading + " ";
                }
                else if(days!=0 && minutes<10)  // show days and show seconds
                {
                    document.getElementById("itemdispatchcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                }
            }
        }, 1000);
    });
    $( ".serviceitemcounter-class" ).each(function( index ) {
        //loopcounter('serviceitemcounter-'+index);
        // console.log('serviceitemcounter-'+index);
        var time = document.getElementById("demotimeserviceitemcounter-"+index).value;
        var countDownDate = new Date(time).getTime();

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"

            // If the count down is over, write some text
           /* document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> <h4 style='color: red;display: inline-block'> "+distance+" </h4>";*/
            if (distance < 0 || time=='') {
                clearInterval(x);
                document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600'> Overdue </h4></b>";
            }
            else
            {
                var values = sortdays(days,hours,minutes,seconds);
                var dayheading = values[0];
                var hourheading = values[1];
                var minuteheading = values[2];
                var secondheading = values[3];
                if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                {
                    document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                }
                else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                {
                    document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                }
                else if(days!=0 && minutes>9) // Show days and don't show seconds
                {
                    document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + ") ";
                }
                else if(days!=0 && minutes<10)  // show days and show seconds
                {
                    document.getElementById("serviceitemcounter-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                }


            }
        }, 1000);

    });
    $( ".subscriptioncounter-class" ).each(function( index ) {
        //loopcounter('subscriptioncounter-'+index);
        // console.log('serviceitemcounter-'+index);
        var time = document.getElementById("subscriptioncounter-"+index).value;
        var countDownDate = new Date(time).getTime();

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"


            // If the count down is over, write some text
           /* document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-map-marker\" style=\"color:#00c292\"></i> <h4 style='color: red;display: inline-block'> "+distance+" </h4>";*/
            if (distance < 0 || time=='') {
                clearInterval(x);
                document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-map-marker text-success\" style=\"color:#00c292\"></i> <b><h4 style='color: red;display: inline-block;font-weight: 600'> Overdue </h4></b>";
            }
            else
            {
                var values = sortdays(days,hours,minutes,seconds);
                var dayheading = values[0];
                var hourheading = values[1];
                var minuteheading = values[2];
                var secondheading = values[3];
                if(days==0 && minutes>9) // not show second if minute is greater than 9 and don't show days
                {
                    document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + ") ";
                }
                else if(days==0 && minutes<10) // show second if minute is less than 10 and dont show days
                {
                    document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                }
                else if(days!=0 && minutes>9) // Show days and don't show seconds
                {
                    document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + ") ";
                }
                else if(days!=0 && minutes<10)  // show days and show seconds
                {
                    document.getElementById("subscriptioncounter-class-"+index).innerHTML = "<i class=\"fa fa-user\" style=\"color:#00c292\"></i> (" + dayheading + " " + hourheading + " "+ minuteheading + " " + secondheading + ") ";
                }
            }
        }, 1000);

    });
}
function sortdays(days,hours,minutes,seconds)  // function for sorting countdown timer
{
    var dayheading = '';
    var hourheading = '';
    var minuteheading = '';
    var secondheading = '';
    if(days>'1')
    {
        dayheading = days + ' Days';
    }
    if(days == '1')
    {
        dayheading = days + ' Day';
    }
    if(days == '0')
    {
        dayheading = '';
    }
    if(minutes>'1')
    {
        minuteheading = minutes + ' Minutes';
    }
    if(minutes == '1')
    {
        minuteheading = minutes + ' Minute';
    }
    if(minutes == '0')
    {
        minuteheading = '';
    }
    if(hours>'1')
    {
        hourheading = hours + ' Hours';
    }
    if(hours == '1')
    {
        hourheading = hours + ' Hour';
    }
    if(hours == '0')
    {
        hourheading = '';
    }
    if(seconds>'1')
    {
        secondheading = seconds + ' Seconds';
    }
    if(seconds == '1')
    {
        secondheading = seconds + ' Second';
    }
    if(seconds == '0')
    {
        secondheading = '';
    }
    return [dayheading, hourheading, minuteheading, secondheading];

}
function completed(buyername,id)
{
  var mainUrl = $("#weburl").val();
	 Swal.fire({
          title: 'Complete',
          text: "This service has been completed for "+buyername,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
            $.ajax({
            type: 'POST',
            url: mainUrl+"/completeorder",
            data:{'id':id},
            dataType : "json",
            success: function(data) {
                if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.success == 1){ //settings updated
                    $('#completed'+id).removeAttr('onclick');
                    $('#cancel'+id).css('display','none');
                    Swal.fire("Success","Order completed successfully");
                    
                }
            },
            error: function(data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });
          }
        })
}
function collected(buyername,id)
{
  var mainUrl = $("#weburl").val();
	Swal.fire({
          title: 'Collection',
          text: "This order has been collected by "+buyername,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
            $.ajax({
            type: 'POST',
            url: mainUrl+"/collectorder",
            data:{'id':id},
            dataType : "json",
            success: function(data) {
                if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.success == 1){ //settings updated
                    $('#collected'+id).removeAttr('onclick');
                    $('#cancel'+id).css('display','none');
                    Swal.fire("Success","Order completed successfully");
                    
                }
            },
            error: function(data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });
          }
        })
}
function sellercancel(id)
{
	var mainUrl = $("#weburl").val();
	Swal.fire({
          title: 'Cancel Order',
          html: '<p>Its not good to make a habit of cancelling orders - cancel too many and we may review/suspend your account. But, everyone is human and mistakes happen. Just select the reason below and we will do the rest.</p><select class=form-control name=cancel_reason id=cancel_reason><option value="I cant find the item / it is now out of stock">I cant find the item / it is now out of stock</option><option value="The item I was going to send is damaged">The item I was going to send is damaged</option><option value="I cannot perform this service due to personal reasons">I cannot perform this service due to personal reasons</option></select>',
          type: 'warning',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Cancel Order'
        }).then((result) => {
        	 if (result.value) {
            var reason = $('#cancel_reason').val();
            $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
            $.ajax({
            type: 'POST',
            url: mainUrl+"/cancelorder",
            data:{'id':id,'reason':reason},
            dataType : "json",
            success: function(data) {
                if(data.status == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.status == 1){ //settings updated
                    //$('#group'+id).css('display','none');
                    $('#cancel'+id).removeAttr('onclick');
                    $('#cancel'+id).removeClass('btn-warning');
                    $('#cancel'+id).addClass('btn-danger');
                    $('#completed'+id).css('display','none');
                    $('#collected'+id).css('display','none');
                    $('#cancel'+id).html('Cancelled');
                    Swal.fire(data.message);

                }
                if(data.status == 2)
                {
                  window.location = data.message;
                }
            },
            error: function(data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });
          }
        })
}