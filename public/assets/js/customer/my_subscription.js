function cancelsubscription(id)
{
	var mainUrl = $("#weburl").val();
	var nextrenew = $('#nextrenew'+id).val();
	Swal.fire({
          title: 'Cancel Subscription',
          html: "It's ok to change your mind - After cancelling subscription you are no more subscribed and you will not charged on "+nextrenew,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm'
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
            url: mainUrl+"/buyercancelsubscription",
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