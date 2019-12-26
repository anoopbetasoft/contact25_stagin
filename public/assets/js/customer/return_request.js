function acceptrequestinpost(id)
{
    var mainUrl = $("#weburl").val();
    Swal.fire({
        title: 'Accept Return Request',
        html: "By clicking confirm it will generate the label and buyer will return the item",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: mainUrl+"/accept_return_request_inpost",
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
                        $('#returnorder'+id).remove();
                        $('#order_status'+id).html('');
                        $('#order_status'+id).html('<button class="btn btn-success" id="status'+id+'">+data.message+</button>');
                        //$('#status'+id).html('Cancelled');
                        Swal.fire(data.message);

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
function acceptrequest(id)
{
    var mainUrl = $("#weburl").val();
    Swal.fire({
        title: 'Accept Return Request',
        html: "By clicking confirm it will provide your address where buyer return the item",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: mainUrl+"/accept_return_request",
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
                        $('#returnrequest'+id).html('');
                        $('#returnrequest'+id).html('<button class="btn btn-success" onclick="refunditem(id);">Click if you received item<i class="fas fa-check"></i></button>');
                        //$('#status'+id).html('Cancelled');
                       // Swal.fire(data.message);

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
function declinerequest(id)
{
    var mainUrl = $("#weburl").val();
    Swal.fire({
        title: 'Decline Return Request',
        html: "By clicking confirm it will decline return request from buyer.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: mainUrl+"/decline_return_request",
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
                        $('#returnrequest'+id).html('');
                        $('#returnrequest'+id).html('Return Request Rejected');
                        //$('#status'+id).html('Cancelled');
                        // Swal.fire(data.message);

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
function refunditem(id)
{
    var mainUrl = $("#weburl").val();
    Swal.fire({
        title: 'I received item',
        html: "By clicking confirm it will refund the amount to buyer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: mainUrl+"/refund_return_request",
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
                        $('#returnrequest'+id).html('');
                        $('#returnrequest'+id).html(data.message2);
                        //$('#status'+id).html('Cancelled');
                        // Swal.fire(data.message);

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