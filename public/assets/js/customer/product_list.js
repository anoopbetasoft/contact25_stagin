
$(".qty_update").focus(function() { $(this).select(); } );
$(".price_update").focus(function() { $(this).select(); } );
$(".subs-price_update").focus(function() { $(this).select(); } );

function sorting(sortinvalue)
{
    var mainUrl = $("#weburl").val();
        
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        type: 'POST',
        url: mainUrl+"/product_list_ajax",
        data:{'sort_by':sortinvalue},
        success: function(data) {
        $('#productscontent').html(data);
        },
        error: function(data) {
        //alert("Some error occured"); //location.reload(); return false;
        Swal.fire('Error occured while fetching data');
        }
        });
        
}
function sortingroom(sortinvalue,roomname)
{
		var mainUrl = $("#weburl").val();
        
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        type: 'POST',
        url: mainUrl+"/product_list_ajax",
        data:{'sort_by':sortinvalue},
        success: function(data) {
        $('#productscontent').html(data);
            $.ajax({
            type: 'POST',
            url: mainUrl+"/product_box_list_ajax",
            data:{'room_id':sortinvalue},
            success: function(data2) {
            $('#room_duration_label').html(roomname);
            $('#box_box_dropdown').html(data2);
                if(sortinvalue=='allrooms')
                {
                   $('#box_box_dropdown').html(''); 
                }
            }
            }); 
        },
        error: function(data) {
        //alert("Some error occured"); //location.reload(); return false;
        Swal.fire('Error occured while fetching data');
        }
        });
}
function sortingbox(sortinvalue,boxname)
{
        var mainUrl = $("#weburl").val();
        
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        type: 'POST',
        url: mainUrl+"/product_box_ajax",
        data:{'box_id':sortinvalue},
        success: function(data) {
        $('#productscontent').html(data);
        $('#box_duration_label').html(boxname);
            
        },
        error: function(data) {
        //alert("Some error occured"); //location.reload(); return false;
        Swal.fire('Error occured while fetching data');
        }
        });
}
function sortallbox(sortinvalue,boxname)
{
    var mainUrl = $("#weburl").val();
        
        $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        type: 'POST',
        url: mainUrl+"/product_all_box_ajax",
        data:{'room_id':sortinvalue},
        success: function(data) {
        $('#productscontent').html(data);
        $('#box_duration_label').html(boxname);
            
        },
        error: function(data) {
        //alert("Some error occured"); //location.reload(); return false;
        Swal.fire('Error occured while fetching data');
        }
        });
}

function updateall(id)
{
	var mainUrl = $("#weburl").val();
	var p_selling_price = $('#price_update'+id).val();
	var p_subs_price = $('#subs-price_update'+id).val();
	var code = $('#code').val();
	var id = $('#id'+id).val();
	var p_quantity = $('#qty_update'+id).val();
	$.ajaxSetup({
	headers: {
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
	});
	$.ajax({
	type: 'POST',
	url: mainUrl+"/updateproduct",
	data:{'id':id,'p_selling_price':p_selling_price,'p_subs_price':p_subs_price,'p_quantity':p_quantity,'code':code},
	dataType : "json",
	success: function(data) {
	if(data.success == 0){ //error
	Swal.fire("Error",data.message,"warning");
	}  
	if(data.success == 1){ //settings updated
	    $('#qty_update'+id).val(p_quantity);
	    if(p_selling_price!='not')
        {
            $('#price_update'+id).val(data.p_selling_price);
        }
	    if(p_subs_price!='not')
        {
            $('#subs-price_update'+id).val(data.p_subs_price);
        }
	Swal.fire("Success","Updated successfully");

	}
	},
	error: function(data) {
	//alert("Some error occured"); //location.reload(); return false;
	Swal.fire('Error occured while inserting data');
	}
	});

}