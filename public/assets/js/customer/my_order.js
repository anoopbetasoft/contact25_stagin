function notreceived(id)
{
    var ordered_on = $('#ordered_on'+id).val();
    var delivered_by = $('#delivered_by'+id).val();
    var days = $('#days'+id).val();
    var day_diff = $('#day_diff'+id).val();
    Swal.fire({
        title: 'Not Arrived?',
        html: "It's frustrating when things don't arrive. You ordered on "+ordered_on+" ("+day_diff+" days ago). We allow sellers up to "+days+" days to deliver your item. If they haven't delivered by "+delivered_by+", you'll be able to claim a full refund.",
        type: 'warning'
    })
}
function returnitem(id)
{
    var mainUrl = $("#weburl").val();
    Swal.fire({
        title: 'Return Item',
        html: "It's ok to change your mind - just select your reason below and we'll refund you in full.<br><select class=form-control name=return_type id=return_type><option value=0>Select</option><option value='1'>Product Damaged</option><option value='2'>Another Reason</option></select><br><br><input type=text name=return_reason id=return_reason class=form-control placeholder='Reason For Returning'>",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.value) {
            var return_type = $('#return_type').val();
            var return_reason = $('#return_reason').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: mainUrl+"/buyerreturnorder",
                data:{'id':id,'return_type':return_type,'return_reason':return_reason},
                dataType : "json",
                success: function(data) {
                    if(data.status == 0){ //error
                        Swal.fire("Error",data.message,"warning");
                    }
                    if(data.status == 1){ //settings updated
                        //$('#group'+id).css('display','none');
                        /*$('#cancel'+id).removeAttr('onclick');
                        $('#cancel'+id).html('Cancelled');*/
                        $('#returnorder'+id).remove();
                        $('#order_status'+id).html('');
                        $('#order_status'+id).html(data.message2);
                        //$('#status'+id).html('Cancelled');
                        Swal.fire({
                            title: 'Thank You',
                            html: data.message,
                            type: 'success',
                        });

                    }
                    if(data.status == 2)
                    {
                       $('#returnorder'+id).html('');
                       $('#returnorder'+id).html('<span class="font-normal">Return Item : </span>Return Request Pending');
                       Swal.fire('Return Request Pending From Seller');
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
function buyercancel(id)
{
	var mainUrl = $("#weburl").val();
	Swal.fire({
          title: 'Cancel Order',
          html: "It's ok to change your mind - just select your reason below and we'll refund you in full.<select class=form-control name=cancel_reason id=cancel_reason><option value='I've changed my mind'>I've changed my mind</option><option value='I've found a cheaper item/service'>I've found a cheaper item/service</option><option value='I ordered this by mistake'>I ordered this by mistake</option></select>",
          type: 'warning',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Cancel'
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
            url: mainUrl+"/buyercancelorder",
            data:{'id':id,'reason':reason},
            dataType : "json",
            success: function(data) {
                if(data.status == 0){ //error
                    Swal.fire("Error",data.message,"warning");
                }  
                if(data.status == 1){ //settings updated
                    //$('#group'+id).css('display','none');
                    /*$('#cancel'+id).removeAttr('onclick');
                    $('#cancel'+id).html('Cancelled');*/
                    $('#cancelorder'+id).css('display','none');
                    $('#status'+id).html('Cancelled');
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
function productdelivered(id)
{
    var mainUrl = $("#weburl").val();
    Swal.fire({
        title: 'Mark it as delivered',
        html: "By Clicking Yes the product will be marked as ",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {
            //var reason = $('#cancel_reason').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: mainUrl+"/buyerclaimdelivered",
                data:{'id':id},
                dataType : "json",
                success: function(data) {
                    if(data.status == 0){ //error
                        Swal.fire("Error",data.message,"warning");
                    }
                    if(data.status == 1){ //settings updated
                        //$('#group'+id).css('display','none');
                        /*$('#cancel'+id).removeAttr('onclick');
                        $('#cancel'+id).html('Cancelled');*/
                        $('#action'+id).remove();
                        $('#order_status'+id).html('');
                        $('#order_status'+id).html('<button class="btn btn-success" id="status'+id+'">Delivered</button>')
                        /*$('#cancelorder'+id).css('display','none');
                        $('#status'+id).html('Cancelled');*/
                        Swal.fire('Order Status Changed Successfully');

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
function claimitnotdelivered(id)
{
    var mainUrl = $("#weburl").val();
    Swal.fire({
        title: 'Claim it as not delivered',
        html: "By Clicking Yes the product will be marked as not delivered and refund is initiated",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {
            //var reason = $('#cancel_reason').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: mainUrl+"/claimitnotdelivered",
                data:{'id':id},
                dataType : "json",
                success: function(data) {
                    if(data.status == 0){ //error
                        Swal.fire("Error",data.message,"warning");
                    }
                    if(data.status == 1){ //settings updated
                        //$('#group'+id).css('display','none');
                        /*$('#cancel'+id).removeAttr('onclick');
                        $('#cancel'+id).html('Cancelled');*/
                        $('#action'+id).remove();
                        $('#order_status'+id).html('');
                        $('#order_status'+id).html(data.message2);
                        /*$('#cancelorder'+id).css('display','none');
                        $('#status'+id).html('Cancelled');*/
                        Swal.fire("success",data.message,"success");

                    }
                    /*if(data.status == 2)
                    {
                        window.location = data.message;
                    }*/
                },
                error: function(data) {
                    //alert("Some error occured"); //location.reload(); return false;
                    Swal.fire('Error occured while inserting data');
                }
            });
        }
    })
}