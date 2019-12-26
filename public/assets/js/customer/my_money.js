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