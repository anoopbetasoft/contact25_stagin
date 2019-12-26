//function initialize_datepicker(data){
//var id  = $(data).attr('id');
//$("#"+id).bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
//$("#p_service_time").bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY - HH:mm' });
//}

// Checking if product is editing or not
/*var mainUrl = $("#weburl").val();
var type = $(data).attr('data-value');
var type_div = $(data).attr('data-product_type');
var type_row_div =  $(data).attr('data-product_type_row');
var type_val = $(data).attr('data-value');
var anchor_html = $(data).html();*/

/*$("."+type_div).css('display','block');
$("."+type_row_div).css('display','block');*/

var type_val = $('#updateproductype').val();
if (type_val != '') {
    var mainUrl = $("#weburl").val();
    var productid = $('#productid').val();
    if (type_val == 1) {
        //alert('here');
        //$('.box_div').css('display', 'block');  // TO DISPLAY BOX IT DIV
        //$('.wrapper_quality_display').css('display', 'block');
        //$('#dynamic_load_type').load(mainUrl + '/add_pro_item');
        //$(".item_div").css('display', 'block');

    } else if (type_val == 3) {
        $('.box_div').css('display', 'block');  // TO DISPLAY BOX IT DIV
        $('.wrapper_quality_display').css('display', 'none');
        $('#dynamic_load_type').load(mainUrl + '/add_pro_subs/' + productid);
        $(".item_div").css('display', 'none');
    } else if (type_val == 2) {
        $('.wrapper_quality_display').css('display', 'none');
        $('#dynamic_load_type').load(mainUrl + '/add_pro_service/' + productid);
        $(".delivery_option").css('display', 'none');
        $(".item_div").css('display', 'none');
        $('.box_div').css('display', 'none');   // DONT DISPLAY BOX IT DIV

    }
    $("input[name='p_type']").val(type_val);
}


//$('#p_type_button').html(anchor_html);
var updateproducttype = $('#updateproductype').val();
if (updateproducttype) {

}
$('#submitbutton').click(function () {
    //var formD = new FormData($('#update_auth')[0]);
    /*var proimg = $('input[name="pro_img[]"]').val();
    alert(proimg);*/
    var formD = new FormData($('#add_product')[0]);
    //var formD = $('#add_product').serialize();
    var mainUrl = $("#weburl").val();
    var image = $('#imgInp').val();
    //alert(formD);
    //ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl + "/product_add_process",
        data: formD,
        dataType: "json",
        processData: false,
        contentType: false,

        success: function (data) {
            if (data.success == 0) { //error
                Swal.fire("Error", data.message, "warning");
            }
            if (data.success == 1) { //settings updated
                window.location.replace(mainUrl + '/myproducts/');
            }
        },
        error: function (data) {
            //alert("Some error occured"); //location.reload(); return false;
            Swal.fire('Error occured while inserting data');
        }
    });

});

function barcode_scan() {
    //var data = "Download the app and you'll be able to quickly list your stuff faster using a barcode scanner.Links to app stores<span style=float:right;cursor: pointer;><img src=http://newapp.contact25.com/assets/images/store_app.png><img src=http://newapp.contact25.com/assets/images/store_play.png></span>";
    //swal('Barcode Scanner',data,'success');
    //swal({ title: '<i>HTML</i> <u>example</u>', type: 'info', html: true, text: 'You can use <b>bold text</b>, ' + '<a href="//github.com">links</a> ' + 'and other HTML tags', showCloseButton: true, showCancelButton: true, confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!', cancelButtonText: '<i class="fa fa-thumbs-down"></i>' })
    /*Swal.fire({
    title: 'Barcode Scanner',
    text: data,
    imageUrl: 'https://wasabiBD.github.io/test-repo/dia2/images/feito.png',
    imageWidth: 164,
    imageHeight: 205,
    padding: 10,
    animation: true,
  });*/
    Swal.fire(
        'Barcode Scanner',
        "Download the app and you'll be able to quickly list your stuff faster using a barcode scanner.<br><span style=cursor: pointer;><br><img src=http://newapp.contact25.com/assets/images/store_app.png><img src=http://newapp.contact25.com/assets/images/store_play.png></span>",
        'success'
    )
}

function removeServiceHour(data) {					// FOR DELETING SERVICE HOURS
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
        url: mainUrl + "/removeServiceHours",
        data: {"id": id},
        dataType: "json",
        success: function (response) {
            if (response.success == 1) {
                $("#service_hour_id_" + id).remove();
                $('.weekday').prop('selectedIndex', 0);
                $('.hours_start').prop('selectedIndex', 18);
                $('.hours_end').prop('selectedIndex', 34);
            } else {
                //alert(response.message);
                //swal.fire(response.message);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: response.message
                });
                return false;
            }
        },
        error: function (response) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
    //alert(id); return false;
}

function add_service_times(data) {           // FOR ADDING SERVICE HOURS

    //userday
    var user_day = $('.serviceweekday').find('option:selected').val();
    var user_day_name = $('.serviceweekday').find('option:selected').attr('data-user_day_name');

    //user time
    var user_start_time = $('.servicehours_start').find('option:selected').val();
    var user_end_time = $('.servicehours_end').find('option:selected').val();

    var mainUrl = $("#weburl").val();
    //ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: mainUrl + "/addServiceHours",
        data: {
            "user_day": user_day,
            "user_day_name": user_day_name,
            "user_start_time": user_start_time,
            "user_end_time": user_end_time
        },
        dataType: "json",
        success: function (data) {
            if (data.success == 1) {
                if(data.type=='update')
                {
                    $('#service_hour_id_'+data.id).remove();
                    $('.list_service_hours').append(data.message);
                }
                else
                {
                    $('.list_service_hours').append(data.message);
                }
                //$('.list_open_hours').append(data.message);
                $('.weekday').prop('selectedIndex', 0);
                $('.hours_start').prop('selectedIndex', 18);
                $('.hours_end').prop('selectedIndex', 34);
            } else {
                /*alert(data.message);*/
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message
                });
                return false;
            }
        },
        error: function (data) {
            alert("Some error occured"); //location.reload(); return false;
        }
    });
}

function servicelead() {
    Swal.fire("Service Lead Time", "The Service Lead Time means the amount of time you would like before an order for your services. So, if you've currently got 1 week of work you might set this to 7 days. If you've got no work, just leave it blank and an order could come in for your service today.", "success");
}

function movebox(id) {
    $('.move').css('display', 'none');
    var value = $('#move' + id).css('display', 'block');

}

function updatemovebox(key, room_id, box_id, box_key, totalbox, totalitems) {
    var mainUrl = $("#weburl").val();
    var box_id = box_id;
    var move_to_box_id = $('#moveto' + box_id).children('option:selected').val();
    var boxitems = parseInt($('#boxitem' + box_id).text());
    var moveboxitems = parseInt($('#boxitem' + move_to_box_id).text());
    var newboxitem = boxitems + moveboxitems;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl + "/move_box",
        data: {'box_id': box_id, 'move_box_id': move_to_box_id},
        dataType: "json",
        success: function (data) {
            if (data.success == 0) { //error
                Swal.fire("Error", data.message, "warning");
            }
            if (data.success == 1) { //settings updated
                /*if(box_key<totalbox)
                {
                    // Loop For Renaming Boxes
                    for(var i=box_key;i<=totalbox;i++)
                    {
                        var boxkey = i-1;
                        if(i!=box_key)
                        {
                            $('#boxname'+room_id+i).val('BOX '+boxkey);
                        }
                    }
                }*/
                $('#boxitem' + move_to_box_id).text(newboxitem);
                $('#boxdelete' + box_id).css('display', 'none');
                $('#icon' + box_id).css('display', 'none');
                var oldroomitem = parseInt($('#roomitemcount' + room_id).text());
                var newroomitem = parseInt($('#roomitemcount' + data.roomid).text());
                var oldroom = oldroomitem - boxitems;
                var newroom = newroomitem + boxitems;
                $('#roomitemcount' + room_id).text(oldroom);
                $('#roomitemcount' + data.roomid).text(newroom);
            }
        },
        error: function (data) {
            //alert("Some error occured"); //location.reload(); return false;
            Swal.fire('Error occured while inserting data');
        }
    });


}

function edit_room(id) {
    //alert(id);
    $('#roomname' + id).css('display', 'none');
    $('#roomeditbutton' + id).css('display', 'none');
    $('#editpart' + id).css('display', 'inline-block');
}

function updateroom(id) {
    var roomname = $('#roomedit' + id).val();
    var mainUrl = $("#weburl").val();
    //ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: mainUrl + "/update_room",
        data: {'display_text': roomname, 'id': id},
        dataType: "json",
        success: function (data) {
            if (data.success == 0) { //error
                Swal.fire("Error", data.message, "warning");
            }
            if (data.success == 1) { //settings updated
                $('#roomname' + id).text(roomname);
                $('#roomname' + id).css('display', 'inline-block');
                $('#roomeditbutton' + id).css('display', 'inline-block');
                $('#editpart' + id).css('display', 'none');
            }
        },
        error: function (data) {
            //alert("Some error occured"); //location.reload(); return false;
            Swal.fire('Error occured while inserting data');
        }
    });
}

function showbox(id, roomid) {
    //alert("boxitem"+id);
    $('#boxheading').css('display', 'block');
    $('.add_new_room_select').css('display', 'block');
    $('.newboxtextbox').css('display', 'none');
    $('.new_room_box').css('display', 'none');
    $('.boxitems').css('display', 'none');
    $('#box' + id).css('display', 'block');
    $('.rooms').removeClass('active');
    $('#room' + id).addClass('active');
    $('#roomname').val(roomid);
    $('#boxname').val('');
    $('#boxid').val('');
    $('.newbox').html('+ &ensp;&ensp;');
    $('.newbox').removeClass('active');

}

function newroom() {
    $('#boxheading').css('display', 'block');
    $('.add_new_room_select').css('display', 'none');
    $('.newboxtextbox').css('display', 'block');
    $('.new_room_box').css('display', 'block');
    $('.boxitems').css('display', 'none');
    $('.rooms').removeClass('active');
    $('#boxname').val('');
    $('#boxid').val('');
    $('#roomname').val('');
    $('.newbox').html('+ &ensp;&ensp;');
    $('.newbox').removeClass('active');
}

function boxselect(roomid, boxid, boxname) {
    $('#boxname').val('Box ' + boxname);
    $('#boxid').val(boxid);
    //alert($('#boxname').val());

    $('.box-item').removeClass('active');
    $('#boxdelete' + boxid).addClass('active');
    $('.newbox').html('+ &ensp;&ensp;');
    $('.newbox').removeClass('active');
}

function newbox(roomid, boxid) {
    $('.box-item').removeClass('active');
    $('.newbox').html('+ &ensp;&ensp;');
    $('.newbox').removeClass('active');
    $('#boxname').val('Box ' + boxid);
    $('#box' + roomid + boxid).html('<span class="badge badge-success" style="float:right;">0</span><i class="ti-package"></i> Box ' + boxid);
    $('#box' + roomid + boxid).addClass('active');

}

function newbox2(roomid, boxid, id) {
    $('.box-item').removeClass('active');
    $('.newbox').html('+ &ensp;&ensp;');
    $('.newbox').removeClass('active');
    $('#boxname').val(boxid);
    $('#boxid').val(id);
    $('#box' + roomid + id).html('<span class="badge badge-success" style="float:right;">0</span><i class="ti-package"></i> ' + boxid);
    $('#box' + roomid + id).addClass('active');

}

$(function () {


    //var p_sell_to = [];

    //p_sell_to = [4,5];
    var p_sell_to = [];
    if ($("input[name='sell_to_neighbour']").val() == '4') {
        p_sell_to.push('4');
    }
    if ($("input[name='sell_to_uk']").val() == '5') {
        p_sell_to.push('5');
    }
    if ($("input[name='sell_to_friend']").val() == '1') {
        p_sell_to.push('1');
    }
    if ($("input[name='sell_to_group[]']").val() != '') {
        for (var sell_k = 0; sell_k < $("input[name='sell_to_group[]']").length; sell_k++) {
            //p_lend_to.push($("input[name='lend_to_group[+lend_k+]']").val());
            p_sell_to.push($('.sell_to_group').eq(sell_k).val());
        }
    }
    /*if($("input[name='pselltovalue']").val()!='')
    {
        p_sell_to.push($("input[name='pselltovalue']").val());
    }*/

    //p_sell_to.push('4');
    //p_sell_to.push('5');
    $("input[name='p_sell_to[]']").val(p_sell_to);

    var p_lend_to = [];
    //p_lend_to.push('4');
    //p_lend_to.push('5');
    if ($("input[name='lend_to_neighbour']").val() == '4') {
        p_lend_to.push('4');
    }
    if ($("input[name='lend_to_uk']").val() == '5') {
        p_lend_to.push('5');
    }
    if ($("input[name='lend_to_friend']").val() == '1') {
        p_lend_to.push('1');
    }
    if ($("input[name='lend_to_group[]']").val() != '') {
        for (var lend_k = 0; lend_k < $("input[name='lend_to_group[]']").length; lend_k++) {
            //p_lend_to.push($("input[name='lend_to_group[+lend_k+]']").val());
            p_lend_to.push($('.lend_to_group').eq(lend_k).val());
        }
    }
    /*if($("input[name='plendtovalue']").val()!='')
    {
        p_lend_to.push($("input[name='plendtovalue']").val());
    }*/
    $("input[name='p_lend_to[]']").val(p_lend_to);

    //form submit
    var storedFiles = [];
    var dropIndex;
    /*$("#displayMore").sortable({
        handle: 'img',
           cursor: 'move',
        update: function(event, ui) {
            $(this).find('img').each(function (index){
                storedFiles[index] = $(this).attr('id');
            });
        }
    });*/

    //$('#displayMore img').disableSelection();


    $("#add_product").submit(function (e) {
        e.preventDefault();
        $('input[name="pro_img[]"]').val(storedFiles);

        $('#add_product').unbind('submit').submit();
    });

});


// product type
function p_type_selected(data) {

    var mainUrl = $("#weburl").val();
    var type = $(data).attr('data-value');
    var type_div = $(data).attr('data-product_type');
    var type_row_div = $(data).attr('data-product_type_row');
    var type_val = $(data).attr('data-value');
    var anchor_html = $(data).html();

    $("." + type_div).css('display', 'block');
    $("." + type_row_div).css('display', 'block');
    if (type_val == 1) {
        $('.box_div').css('display', 'block');  // TO DISPLAY BOX IT DIV
        $('.wrapper_quality_display').css('display', 'block');
        $('#dynamic_load_type').load(mainUrl + '/add_pro_item');
        $(".item_div").css('display', 'block');
        $('.delivery_option').css('display','block');

    } else if (type_val == 3) {
        $('.box_div').css('display', 'block');  // TO DISPLAY BOX IT DIV
        $('.wrapper_quality_display').css('display', 'none');
        $('#dynamic_load_type').load(mainUrl + '/add_pro_subs');
        $(".item_div").css('display', 'none');
       // $('.delivery_option').css('display','none');
    } else {
        $('.wrapper_quality_display').css('display', 'none');
        $('#dynamic_load_type').load(mainUrl + '/add_pro_service');
        $(".delivery_option").css('display', 'none');
        $(".item_div").css('display', 'none');
        $('.box_div').css('display', 'none');   // DONT DISPLAY BOX IT DIV
        //$('.delivery_option').css('display','none');

    }

    $("input[name='p_type']").val(type);
    $('#p_type_button').html(anchor_html);

}


//quality dropdown
function p_quality_selected(data) {

    var a_id = $(data).attr('id');

    var quality = $(data).attr('data-value');
    var anchor_html = $(data).html();

    $('#p_quality_button').html(anchor_html);
    $("input[name='p_quality']").val(quality);


    $(data).css('display', 'none');
    $(".quality_dropdown:not(#" + a_id + ")").css('display', 'block');
}

//product sell to
//var p_sell_to = [];
//p_sell_to.push('4');
//p_sell_to.push('5');
var p_sell_to = [];
if ($("input[name='sell_to_neighbour']").val() == '4') {
    p_sell_to.push('4');
}
if ($("input[name='sell_to_uk']").val() == '5') {
    p_sell_to.push('5');
}
if ($("input[name='sell_to_friend']").val() == '1') {
    p_sell_to.push('1');
}
if ($("input[name='sell_to_group[]']").val() != '') {
    for (var sell_k = 0; sell_k < $("input[name='sell_to_group[]']").length; sell_k++) {
        //p_lend_to.push($("input[name='lend_to_group[+lend_k+]']").val());
        p_sell_to.push($('.sell_to_group').eq(sell_k).val());
    }
}
$("input[name='p_sell_to[]']").val(p_sell_to);

function product_sell_to(data) {
    var p_sell_selected = $(data).attr('data-value');
    if ($(data).is(":checked")) {
        if ($(data).attr('data-value') == '1') {
            /*for (var i = 0; i < p_sell_to.length; i++) {
                alert('hello');
                if (p_sell_to[i] != 1 && p_sell_to[i] != 4 && p_sell_to[i] != 5) {
                    //p_sell_to.splice(i,1);
                    p_sell_to.splice($.inArray(p_sell_to[i], p_sell_to), 1);
                }
            }*/
            $.each(p_sell_to, function (index, value) {
                if (value != 1 && value != 4 && value != 5) p_sell_to.splice($.inArray(index, p_sell_to), 1);
            });
        }
        if ($(data).attr('data-value') != '1' || $(data).attr('data-value') != '4' || $(data).attr('data-value') != '5') {		// If friend group is selected then unselect all friends
            var index = p_sell_to.indexOf('1');
            if (index > -1) {
                p_sell_to.splice(index, 1);
            }
        }
        p_sell_to.push(p_sell_selected);
    } else {
        var index = p_sell_to.indexOf(p_sell_selected);
        if (index > -1) {
            //p_sell_to.splice($.inArray(p_sell_selected, p_sell_to), 1);
            p_sell_to.splice(index, 1);
        }
    }
    $("input[name='p_sell_to[]']").val(p_sell_to);


    if ($(data).attr('data-value') == 1) {
        /*$("#checkbox_friend_group_sell_2").prop('checked', false);
        $("#checkbox_friend_group_sell_3").prop('checked', false);*/
        $('.checkbox_friend_group_sell').prop('checked', false);

    }
    if ($(data).attr('data-value') != 1 && $(data).attr('data-value') != 4 && $(data).attr('data-value') != 5) {
        $("#checkbox_friend_group_sell_1").prop('checked', false);
    }

}

//sell to toggle
function toggle_sell_to(data) {
    //$("#sell_toggle_2, #sell_toggle_3").toggle('slow');
    $(".sell_toggle").toggle('slow');
    $(data).find("#friend_groups_popup-sell").toggleClass('fa-chevron-down fa-chevron-right');
}

//product lend to : item type
var p_lend_to = [];
if ($("input[name='lend_to_neighbour']").val() == '4') {
    p_lend_to.push('4');
}
if ($("input[name='lend_to_uk']").val() == '5') {
    p_lend_to.push('5');
}
if ($("input[name='lend_to_friend']").val() == '1') {
    p_lend_to.push('1');
}
if ($("input[name='lend_to_group[]']").val() != '') {
    for (var lend_k = 0; lend_k < $("input[name='lend_to_group[]']").length; lend_k++) {
        //p_lend_to.push($("input[name='lend_to_group[+lend_k+]']").val());
        p_lend_to.push($('.lend_to_group').eq(lend_k).val());
    }
    //p_lend_to.push($("input[name='lend_to_group[]']").val());
}
$("input[name='p_lend_to[]']").val(p_lend_to);
//p_lend_to.push('4');
//p_lend_to.push('5');
var countlendto = 0;

function product_lend_to(data) {
    if (countlendto == '0') {
        Swal.fire(
            "Lending out your stuff?", "Brilliant - just remember that if the item is broken or damaged Contact25 accept no liability. If you are lending we recommend getting insurance if you are unwilling to accept the risks involved. Now that's out of the way, happy lending!", "success")
    }
    countlendto++;
    var p_lend_selected = $(data).attr('data-value');
    if ($(data).is(":checked")) {
        if ($(data).attr('data-value') == '1') {
            //console.log(p_lend_to);
            /*for (var i = 0; i < p_sell_to.length; i++) {
                alert('hello');
                if (p_sell_to[i] != 1 && p_sell_to[i] != 4 && p_sell_to[i] != 5) {
                    //p_sell_to.splice(i,1);
                    p_sell_to.splice($.inArray(p_sell_to[i], p_sell_to), 1);
                }
            }*/
            console.log(p_lend_to);
            $.each(p_lend_to, function (index, value) {
                if (value != 1 && value != 4 && value != 5) p_lend_to.splice($.inArray(index, p_lend_to), 1);
                //console.log(p_lend_to);

            });
            console.log(p_lend_to);
        }
        if ($(data).attr('data-value') != '1' || $(data).attr('data-value') != '4' || $(data).attr('data-value') != '5') {		// If friend group is selected then unselect all friends
            var index = p_lend_to.indexOf('1');
            if (index > -1) {
                p_lend_to.splice(index, 1);
            }
        }
        p_lend_to.push(p_lend_selected);
    } else {

        p_lend_to.splice($.inArray(p_lend_selected, p_lend_to), 1);
    }
    $("input[name='p_lend_to[]']").val(p_lend_to);

    if (p_lend_to.length == 0) {
        $(".item_lend_price").css('display', 'none');
    } else if (p_lend_to.length > 0) {
        $(".item_lend_price").css('display', 'block');
    }

    /*if($(data).attr('data-value') == 2 || $(data).attr('data-value') == 3){

    }*/
    if ($(data).attr('data-value') == 1) {
        /*$("#checkbox_friend_group_lend_2").prop('checked', false);
        $("#checkbox_friend_group_lend_3").prop('checked', false);*/
        $(".checkbox_friend_group_lend").prop('checked', false);
    }
    if ($(data).attr('data-value') != 1 && $(data).attr('data-value') != 4 && $(data).attr('data-value') != 5) {
        $("#checkbox_friend_group_lend_1").prop('checked', false);
    }
}


//lend_to_toggle
function toggle_lend_to(data) {
    //$("#lend_toggle_2, #lend_toggle_3").toggle('slow');
    $(".lend_toggle").toggle('slow');
    $(data).find("#friend_groups_popup-lend").toggleClass('fa-chevron-down fa-chevron-right');
}

var selected_type_val_array = [];

//product service type : serive type
function service_type_selected(data) {

    var radioDiv = $(data).attr('data-service_type');
    var selected_type_val = $(data).attr('data-value');
    var selected_id = $(data).attr('id');
    var ser_option_values = $("input[name='serviceoption']").val();

    if ((selected_type_val == '' || selected_type_val == null) && (radioDiv == '' || radioDiv == null)) {
        alert('Please select a service type.');
        return false;
    }
    $("." + radioDiv).css('display', 'block');
    if ($(data).is(':checked')) {
        selected_type_val_array.push(selected_type_val);
    } else {
        var index = selected_type_val_array.indexOf(selected_type_val);
        if (index > -1) {
            selected_type_val_array.splice(index, 1);
        }
    }

    $('.service_div').find('.checkbox-circle.sell-wrapper.checkbox_service_list').find('input[type="checkbox"]').each(function (n, obj) {
        if ($(this).is(':checked')) {
            //selected_type_val_array.push(selected_type_val);
            //alert('checked');
        } else {
            //selected_type_val_array.splice(selected_type_val, 1);
            $("." + $(obj).attr('data-service_type')).css('display', 'none');

        }
        /*if($(obj).attr('data-service_type') != null && $(obj).attr('data-service_type') != radioDiv){
            $("."+$(obj).attr('data-service_type')).css('display','none');
        }

        if(selected_id != $(obj).attr('id')){
            $("#"+$(obj).attr('id')).prop('checked', false);
        }*/

    });
    if (ser_option_values == '') {
        ser_option_values = selected_type_val;
    } else {
        ser_option_values = ser_option_values + '-' + selected_type_val;
    }
    //alert(ser_option_values);
    $("input[name='p_service_option']").val(selected_type_val);
    //$("input[name='serviceoption']").val(ser_option_values);
    //console.log(selected_type_val_array);
    $("input[name='serviceoption']").val(selected_type_val_array);
}

var selected_type_val_array = [];

//product subscription type : subscription type
function subscription_type_selected(data) {

    var radioDiv = $(data).attr('data-subs_type');
    var selected_type_val = $(data).attr('data-value');
    var selected_id = $(data).attr('id');
    var sub_option_values = $("input[name='p_subscription_option']").val();

    if ((selected_type_val == '' || selected_type_val == null) && (radioDiv == '' || radioDiv == null)) {
        alert('Please select a service type.');
        return false;
    }
    $("." + radioDiv).css('display', 'block');
    if ($(data).is(':checked')) {
        selected_type_val_array.push(selected_type_val);
    } else {
        var index = selected_type_val_array.indexOf(selected_type_val);
        selected_type_val_array.splice(index, 1);
    }
    $('.subs_member_div').find('.checkbox-circle.sell-wrapper.checkbox_subslist').find('input[type="checkbox"]').each(function (n, obj) {
        if ($(this).is(':checked')) {
            //alert('checked');
        } else {
            $("." + $(obj).attr('data-subs_type')).css('display', 'none');
        }
        if ($(obj).attr('data-subs_type') != null && $(obj).attr('data-subs_type') != radioDiv) {

            //$("."+$(obj).attr('data-subs_type')).css('display','none');
        }

        /*if(selected_id != $(obj).attr('id')){
            $("#"+$(obj).attr('id')).prop('checked', false);
        }*/

    });

    //for delivered
    if (selected_type_val == 2) {
        Swal.fire(
            "What does 'Delivered' mean?", "Delivered means you will delivery a product to your customers' physical address.", "success")
    }
    //console.log(sub_option_values);
    if (sub_option_values == '') {
        sub_option_values = selected_type_val;
    } else {
        //sub_option_values = sub_option_values+'-'+selected_type_val;
        sub_option_values = sub_option_values + '-' + selected_type_val;
    }
    //console.log('here');
    //$("input[name='p_subscription_option']").val(sub_option_values);
    $("input[name='p_subscription_option']").val(selected_type_val_array);
}

/* file preview*/

var filesToUpload = [];

function readURL(input) {

	$("#displayMore").css("display", "block");
    if (input.files) {
        //$("#displayMore").html('');
        var filesAmount = input.files.length;
        var id = 0;
        //var fileid = filesToUpload.length;
        var fileid = $("#displayMore > .removeImg_div > span").length;
		var storedFiles = [];

		tmpfileid = 0;
		//console.log(fileid);
        $(input.files).each(function (n,tmpfileid) {
        	//console.log(fileid);
        	/*if(fileid=='0' && filesAmount>='1')					// increment value of file id by 1 for multiple images and multiple image is selected
			{
				//console.log(n);
				fileid = n;
			}*/
        	/*if(fileid >'0' && filesAmount>='1')
			{
				//console.log(n);
				fileid = tmpfileid + n;
				n = tmpfileid + n;
				//console.log(fileid);
			}*/
        	if(fileid > 0)
			{
				tmpfileid = n + fileid;
				//fileid = 0;
				/*fileid = '0';*/
			}
        	else
			{
				tmpfileid = n;
			}
        	//console.log(tmpfileid);
        	//console.log(fileid);
        	//console.log(fileid);
        	/*if(fileid > '0' && filesAmount)
        	if(fileid>'0' && filesAmount>'1')
			{
				fileid = fileid + n + 1;
			}
        	if(fileid > '0' && filesAmount>='1')
			{
				fileid = fileid + 1;
			}*/
            //storedFiles[n] = 'image' + n;  // hide because it will start from 0 for single image
			storedFiles[n] = 'image' + tmpfileid;
            filesToUpload.push(event.target.files[n]);
            (function () {
                var firstDiv = $('<div/>', {
                    class: 'removeImg_div',
                });
                var reader = new FileReader();
                $("#displayMore").append(firstDiv);
                reader.onload = readSuccess;

                function readSuccess(event) {
					//console.log(storedFiles);
                    //console.log(event.target);
                    /*var $span = $('<span onclick="removeImg(this);" data-img_id="image' + n + '" style="cursor:pointer">&times;</span>', {
                        class: 'removeImg',
                    });*/						// Hide because for single image it will start from 0
					var $span = $('<span onclick="removeImg(this);" data-img_id="image' + tmpfileid + '" style="cursor:pointer">&times;</span>', {
						class: 'removeImg',
					});
					//var $span = $('');
                    /*var $img = $('<img/>', {
                        src: event.target.result,
                        id: 'image' + n,
                    });*/     // hide because for single image it is starting from 0
					var $img = $('<img/>', {
						src: event.target.result,
						id: 'image' + tmpfileid,
					});
					 firstDiv.append($span).append($img);
                };

                reader.readAsDataURL(input.files[n]);
            }());
        });
        $('input[name="pro_img[]"]').val(storedFiles);


    }
}

//description
function show_desc(data) {
    $(".descrip_hidden").toggle('slow');
    //alert($('.descrip_hidden').css('display'));
    if ($(".add_description_show").text() == "(+ Description)") {
        //$(".add_description_show").css('color','#fec107');
        //alert('- Description');
        $(".add_description_show").text("(- Description)");
        $(".add_description_show").css("color", "#fec107 !important");
        $(".add_description_show").removeClass("text-purple");
        $(".add_description_show").addClass("text-warning");

    } else {
        //$(".add_description_show").css('color','#fec107');
        //alert('+ Description');
        $(".add_description_show").css("color", "#ab8ce4 !important")
        $(".add_description_show").text("(+ Description)");
        $(".add_description_show").removeClass("text-warning");
        $(".add_description_show").addClass("text-purple");
    }
    //alert('.descrip_hidden'.css('display'));
}

function show_box_div(data) {
    $("#boxit").toggle('slow');
    var mainUrl = $("#weburl").val();
    //alert($('.descrip_hidden').css('display'));
    if ($(".box_it_show").text() == "(+Show)") {
        //$(".add_description_show").css('color','#fec107');
        //alert('- Description');
        $(".box_it_show").text("(-Hide)");
        $(".box_it_show").css("color", "#fec107 !important");
        $(".box_it_show").removeClass("text-purple");
        $(".box_it_show").addClass("text-warning");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl + "/update_box_preference",
            data: {'box_preference': '1'},
            dataType: "json",
            success: function (data) {
                if (data.success == 0) { //error
                    Swal.fire("Error", data.message, "warning");
                }
                if (data.success == 1) { //settings updated

                }
            },
            error: function (data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });

    } else {
        //$(".add_description_show").css('color','#fec107');
        //alert('+ Description');
        $(".box_it_show").css("color", "#ab8ce4 !important")
        $(".box_it_show").text("(+Show)");
        $(".box_it_show").removeClass("text-warning");
        $(".box_it_show").addClass("text-purple");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: mainUrl + "/update_box_preference",
            data: {'box_preference': '0'},
            dataType: "json",
            success: function (data) {
                if (data.success == 0) { //error
                    Swal.fire("Error", data.message, "warning");
                }
                if (data.success == 1) { //settings updated

                }
            },
            error: function (data) {
                //alert("Some error occured"); //location.reload(); return false;
                Swal.fire('Error occured while inserting data');
            }
        });

    }
}

$("#imgInp").change(function () {
    readURL(this);
});

/*$("#imgCam").change(function() {
	
	readURL(this);
	$('#carouselProControls').carousel({
		interval: 2000
	})
});*/


//var filesToUpload = [];

function removeImg(data) {


    var id = $(data).attr('data-img_id');
    var storedFiles = [];
    var file_index = $(data).parent().index();
    $('.old-img').eq(file_index).remove();
    //console.log(id);
    //console.log(id);

    // loop through the files array and check if the name of that file matches FileName
    // and get the index of the match
    //alert(filesToUpload);
    var limit = filesToUpload.length;
    var j = 0;
    for (i = 0; i < limit; i++) {
        //console.log(filesToUpload.length);
        var fileid = 'image' + i;
        if (fileid == id) {
            //console.log('matches!');
            filesToUpload.splice(i, 1);
        } else {
            storedFiles[j] = fileid;
            j++;
        }

    }

    $(data).remove();
    $("#" + id).remove();
    storedFiles.splice(i, 1);
    //console.log(storedFiles);
    $('input[name="pro_img[]"]').val('');
    $('input[name="pro_img[]"]').val(storedFiles);

    //console.log(filesToUpload);
    //console.log();
    readURL(filesToUpload);
    //$("#imgInp").fileUploader(filesToUpload);
}

function touchHandler(event) {
    var touch = event.changedTouches[0];

    var simulatedEvent = document.createEvent("MouseEvent");
    simulatedEvent.initMouseEvent({
            touchstart: "mousedown",
            touchmove: "mousemove",
            touchend: "mouseup"
        }[event.type], true, true, window, 1,
        touch.screenX, touch.screenY,
        touch.clientX, touch.clientY, false,
        false, false, false, 0, null);

    touch.target.dispatchEvent(simulatedEvent);
    //event.preventDefault();
}

function init() {
    document.addEventListener("touchstart", touchHandler, {passive: false});
    document.addEventListener("touchmove", touchHandler, {passive: false});
    document.addEventListener("touchend", touchHandler, {passive: false});
    document.addEventListener("touchcancel", touchHandler, {passive: false});
    /*   document.bind("touchstart", touchHandler, true);
     document.bind("touchmove", touchHandler, true);
     document.bind("touchend", touchHandler, true);
     document.bind("touchcancel", touchHandler, true);*/
}