/*$('.friendinput').click(function(){
   if($('#sell_to_friends').is(':checked'))
   {
        alert('checked');
   }
   else
   {
        alert('not checked');
   }
});*/
/*function updatesellto()
{
    var sell_to_friend = 0;
    var sell_to_friend_of_friend = 0;
    var sell_to_neighbour = 0;
    var sell_to_uk = 0;
    var lend_to_friend = 0;
    var lend_to_friend_of_friend = 0;
    var lend_to_neighbours = 0;
    var lend_to_uk = 0;
    if($('#sell_to_friends').is(':checked')
    sell_to_friends_of_friends
    sell_to_neighbours
    sell_to_uk
    lend_to_friends
    lend_to_friends_of_friends
    lend_to_neighbours
    lend_to_uk
}*/
function sell_to_friends()
{
    var sell_to_friend = 1;
    var mainUrl = $("#weburl").val();
    if($('#sell_to_friends').is(':checked'))
    {
        sell_to_friend = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"sell_to_friend":sell_to_friend},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function updategroupsellto(id)
{
    var sell_to_group = 1;
    var mainUrl = $("#weburl").val();
    if($('#sell_to_'+id).is(':checked'))
    {
        sell_to_group = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"sell_to":sell_to_group,"id":id},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
function updategrouplendto(id)
{
    var lend_to_group = 1;
    var mainUrl = $("#weburl").val();
    if($('#lend_to_'+id).is(':checked'))
    {
        lend_to_group = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"lend_to":lend_to_group,"id":id},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success");
            }else{
                Swal.fire(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
/*$('#sell_to_friends').bind('change', function() {
    var sell_to_friend = 0;
    var mainUrl = $("#weburl").val();
    if($('#sell_to_friends').is(':checked'))
    {
        sell_to_friend = 1;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"sell_to_friend":sell_to_friend},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                swal(
            "Success", "Update Successfull", "success");
            }else{
                swal(data.message);
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
    
});*/
/*$('#sell_to_friends_of_friends').bind('change', function() {*/
function sell_to_friends_of_friends()
{
    var sell_to_friend_of_friend = 1;
    var mainUrl = $("#weburl").val();
    if($('#sell_to_friends_of_friends').is(':checked'))
    {
        sell_to_friend_of_friend = 0;

    }
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"sell_to_friend_of_friend":sell_to_friend_of_friend},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success")
            }else{
                Swal.fire("Error while updating");
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
/*});*/
/*$('#sell_to_neighbours').bind('change', function() {*/
function sell_to_neighbours()
{
    var sell_to_neighbour = 1;
    var mainUrl = $("#weburl").val();
    if($('#sell_to_neighbours').is(':checked'))
    {
        sell_to_neighbour = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"sell_to_neighbour":sell_to_neighbour},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
               Swal.fire(
            "Success", "Update Successfull", "success")
            }else{
                Swal.fire("Error while updating");
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}
/*});*/
/*$('#sell_to_uk').bind('change', function() {*/
function sell_to_uk()
{
    var sell_to_uk = 1;
    var mainUrl = $("#weburl").val();
    if($('#sell_to_uk').is(':checked'))
    {
        sell_to_uk = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"sell_to_uk":sell_to_uk},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success")
            }else{
                Swal.fire("Error while updating");
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
  
}  
/*});*/
/*$('#lend_to_friends').bind('change', function() {*/
function lend_to_friends()
{

    var lend_to_friend = 1;
    var mainUrl = $("#weburl").val();
    if($('#lend_to_friends').is(':checked'))
    {
        lend_to_friend = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"lend_to_friend":lend_to_friend},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success")
            }else{
                Swal.fire("Error while updating");
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
    
/*});*/
}
/*$('#lend_to_friends_of_friends').bind('change', function() {*/
function lend_to_friends_of_friends()
{
    var lend_to_friend_of_friend = 1;
    var mainUrl = $("#weburl").val();
    if($('#lend_to_friends_of_friends').is(':checked'))
    {
        lend_to_friend_of_friend = 0;

    }
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"lend_to_friend_of_friend":lend_to_friend_of_friend},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success")
            }else{
                Swal.fire("Error while updating");
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
    
}
/*$('#lend_to_neighbours').bind('change', function() {*/
function lend_to_neighbours()
{
    var lend_to_neighbour = 1;
    var mainUrl = $("#weburl").val();
    if($('#lend_to_neighbours').is(':checked'))
    {
        lend_to_neighbour = 0;

    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"lend_to_neighbour":lend_to_neighbour},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success")
            }else{
                Swal.fire("Error while updating");
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
    
}
/*$('#lend_to_uk').bind('change', function() {*/
function lend_to_uk()
{
    var mainUrl = $("#weburl").val();
    var lend_to_uk = 1;
    if($('#lend_to_uk').is(':checked'))
    {
        lend_to_uk = 0;

    }
   $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl+"/updateselling",
        data:{"lend_to_uk":lend_to_uk},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                Swal.fire(
            "Success", "Update Successfull", "success")
            }else{
                Swal.fire("Error while updating");
            }
        },
        error: function(data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
    
/*});*/
}



function add_opening_times(data) {

	//userday
	var user_day = $('.weekday').find('option:selected').val();
	var user_day_name = $('.weekday').find('option:selected').attr('data-user_day_name');

	//user time
	var user_start_time = $('.hours_start').find('option:selected').val();
	var user_end_time = $('.hours_end').find('option:selected').val();

	var mainUrl = $("#weburl").val();
	//ajax
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

	$.ajax({
        type: 'POST',
        url: mainUrl+"/addOpenHours",
        data:{"user_day":user_day, "user_day_name":user_day_name, "user_start_time":user_start_time, "user_end_time":user_end_time},
        dataType : "json",
        success: function(data) {
            if(data.success == 1){
                if(data.type=='update')
                {
                    $('#hour_id_'+data.id).remove();
                    $('.list_open_hours').append(data.message);
                }
                else
                {
                    $('.list_open_hours').append(data.message);
                }
                $('.weekday').prop('selectedIndex',0);
                $('.hours_start').prop('selectedIndex',18);
                $('.hours_end').prop('selectedIndex',34);
            }else{
                alert(data.message);return false;
            }
        },
        error: function(data) {
        	alert("Some error occured"); //location.reload(); return false;
        }
    });
}

function removeOpenHour(data){
	var id = $(data).attr('data-user_hr_id');
	//var div_id = $(data).parent('li').attr('id');
	//console.log(div_id); return false;
	var mainUrl = $("#weburl").val();
	//ajax
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

	$.ajax({
        type: 'POST',
        url: mainUrl+"/removeOpenHours",
        data:{"id":id},
        dataType : "json",
        success: function(response) {
            if(response.success == 1){
                $("#hour_id_"+id).remove();
                $('.weekday').prop('selectedIndex',0);
                $('.hours_start').prop('selectedIndex',18);
                $('.hours_end').prop('selectedIndex',34);
            }else{
                alert(response.message);return false;
            }
        },
        error: function(response) {
        	alert("Some error occured"); //location.reload(); return false;
        }
    });
	//alert(id); return false;
}

