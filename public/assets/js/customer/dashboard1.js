$('#email').keypress(function(){

    var email = $('#email').val();
    var mainUrl = $("#weburl").val();
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl+"/find-friend",
            data:{'email':email},
            dataType : "json",
            success: function(data) {
                if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.success == 1){ //settings updated
                    $('#frienddata').html(data.message);
                    
                }
            },
            error: function(data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });

});
function showprofileerror()
{
     Swal.fire(
  'Sell Stuff / Services / Subscriptions',
  "Want to sell on Contact25? Just spend 2 minutes finishing your profile page and you're good to go!<br><a href=/view_profile><button class=btn btn-info>Open Profile Page</button></a>",
  'success'
)
}
function deletegroup(id)
{
     var mainUrl = $("#weburl").val();
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
                Swal.fire({
          title: 'Are you sure?',
          text: "Are you sure to delete this friend group",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
            type: 'POST',
            url: mainUrl+"/delete-group",
            data:{'id':id},
            dataType : "json",
            success: function(data) {
                if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.success == 1){ //settings updated
                    $('#group'+id).css('display','none');
                    
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

function removefriend(senderid)
{
    $('#friend'+senderid).css('display','none');
}
function sendfriendrequest(senderid)
{
     var mainUrl = $("#weburl").val();
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl+"/send-friend-request",
            data:{'senderid':senderid},
            dataType : "json",
            success: function(data) {
                if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.success == 1){ //settings updated
                    $('#sendbuttontext'+senderid).html(data.message);
                    $('#ignorefriend'+senderid).css('display','none');
                }
            },
            error: function(data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });
}
function acceptfriendrequest(id)
{
    var mainUrl = $("#weburl").val();
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl+"/accept-friend-request",
            data:{'id':id},
            dataType : "json",
            success: function(data) {
                if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.success == 1){ //settings updated
                    $('#request'+id).css('display','none');
                    Swal.fire('Friends',data.message,'success');
                }
            },
            error: function(data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });
}
function deletefriendrequest(id,friend_list_1)
{
    var mainUrl = $("#weburl").val();
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl+"/delete-friend-request",
            data:{'id':id,'friend_id_1':friend_list_1},
            dataType : "json",
            success: function(data) {
                if(data.success == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.success == 1){ //settings updated
                    $('#request'+id).css('display','none');
                    Swal.fire('Removed',data.message,'warning');
                }
            },
            error: function(data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });
}
function friendscheckall()
{
      if($('#friendscheckall').prop('checked') == false)
      {
      $('.friendcheck').prop('checked', true);
      }
      else
      {
        $('.friendcheck').prop('checked', false);
      }
      
}
function friendscheckall2(id)
{
      if($('#friend'+id).prop('checked') == false)
      {
      $('#friendscheckall').prop('checked', true);
      }
      else
      {
        $('#friendscheckall').prop('checked', false);
      }
      
}
/*$("#friendcheckall").click(function () {
    alert('called');
      $('.friendcheck').not(this).prop('checked', this.checked);
    });*/


$(function () {
    "use strict";
    //This is for the Notification top right
    /*$.toast({
            heading: 'Welcome to Elite admin'
            , text: 'Use the predefined ones, or specify a custom position object.'
            , position: 'top-right'
            , loaderBg: '#ff6849'
            , icon: 'info'
            , hideAfter: 3500
            , stack: 6
        })*/
        // Dashboard 1 Morris-chart
    Morris.Area({
        element: 'morris-area-chart'
        , data: [{
                period: '2010'
                , iphone: 50
                , ipad: 80
                , itouch: 20
        }, {
                period: '2011'
                , iphone: 130
                , ipad: 100
                , itouch: 80
        }, {
                period: '2012'
                , iphone: 80
                , ipad: 60
                , itouch: 70
        }, {
                period: '2013'
                , iphone: 70
                , ipad: 200
                , itouch: 140
        }, {
                period: '2014'
                , iphone: 180
                , ipad: 150
                , itouch: 140
        }, {
                period: '2015'
                , iphone: 105
                , ipad: 100
                , itouch: 80
        }
            , {
                period: '2016'
                , iphone: 250
                , ipad: 150
                , itouch: 200
        }]
        , xkey: 'period'
        , ykeys: ['iphone', 'ipad', 'itouch']
        , labels: ['iPhone', 'iPad', 'iPod Touch']
        , pointSize: 3
        , fillOpacity: 0
        , pointStrokeColors: ['#00bfc7', '#fb9678', '#9675ce']
        , behaveLikeLine: true
        , gridLineColor: '#e0e0e0'
        , lineWidth: 3
        , hideHover: 'auto'
        , lineColors: ['#00bfc7', '#fb9678', '#9675ce']
        , resize: true
    });
    Morris.Area({
        element: 'morris-area-chart2'
        , data: [{
                period: '2010'
                , SiteA: 0
                , SiteB: 0
        , }, {
                period: '2011'
                , SiteA: 130
                , SiteB: 100
        , }, {
                period: '2012'
                , SiteA: 80
                , SiteB: 60
        , }, {
                period: '2013'
                , SiteA: 70
                , SiteB: 200
        , }, {
                period: '2014'
                , SiteA: 180
                , SiteB: 150
        , }, {
                period: '2015'
                , SiteA: 105
                , SiteB: 90
        , }
            , {
                period: '2016'
                , SiteA: 250
                , SiteB: 150
        , }]
        , xkey: 'period'
        , ykeys: ['SiteA', 'SiteB']
        , labels: ['Site A', 'Site B']
        , pointSize: 0
        , fillOpacity: 0.4
        , pointStrokeColors: ['#b4becb', '#01c0c8']
        , behaveLikeLine: true
        , gridLineColor: '#e0e0e0'
        , lineWidth: 0
        , smooth: false
        , hideHover: 'auto'
        , lineColors: ['#b4becb', '#01c0c8']
        , resize: true
    });
});    
    // sparkline
    var sparklineLogin = function() { 
        $('#sales1').sparkline([20, 40, 30], {
            type: 'pie',
            height: '90',
            resize: true,
            sliceColors: ['#01c0c8', '#7d5ab6', '#ffffff']
        });
        $('#sparkline2dash').sparkline([6, 10, 9, 11, 9, 10, 12], {
            type: 'bar',
            height: '154',
            barWidth: '4',
            resize: true,
            barSpacing: '10',
            barColor: '#25a6f7'
        });
        
    };    
    var sparkResize;
 
        $(window).resize(function(e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineLogin, 500);
        });
        sparklineLogin();
