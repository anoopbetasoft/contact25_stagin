function deleteholiday(id)
{
    	var mainUrl = $("#weburl").val();
    	var formD = new FormData($('#add_holiday')[0]);

    	$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/delete_holiday",
	        data:{"id":id},
	        dataType : "json",
	        success: function(data) {
	        	$.each(data.error, function (index, value) {
                    $("#" + index).html('<span class="error" style="color:red">' + value + '</span>');

                });
	        	if(data.success == 0){ //error
	        		//$('#'+id).css('display','none');
	        	}  
	        	if(data.success == 1){ //settings updated
	        			
	        			$('#'+id).css('display','none');

	        		     	}
	        	//hide messages
	        	setTimeout( 
                    function(){
                        $(".profile_danger").hide();
                        $(".profile_danger").hide();
                    } , 10000
                );
	        },
	    });
}
$( document ).ready(function() {
    $("#add_holiday").submit(function (e){
    	e.preventDefault();
    	var mainUrl = $("#weburl").val();
    	var formD = new FormData($('#add_holiday')[0]);

    	$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
		$.ajax({
	        type: 'POST',
	        url: mainUrl+"/add_holiday",
	        data:formD,
	        cache:false,
	        contentType: false,
	        processData: false,
	        success: function(data) {
	        	$.each(data.error, function (index, value) {
                    $("#" + index).html('<span class="error" style="color:red">' + value + '</span>');

                });
	        	if(data.success == 0){ //error
	        		/*$("#update_profile").find('.profile_danger').css('display','block').html(data.message);
	        		$("#update_profile").find('.profile_success').css('display','none').html('');
	        		$("#update_profile").trigger('reset');*/
	        		$('#date-format').val('');
	        		$('#date-format2').val('');
	        	}  
	        	if(data.success == 1){ //settings updated
	        		/*$("#update_profile").find('.profile_danger').css('display','none').html('');
	        		$("#update_profile").find('.profile_success').css('display','block').html(data.message);*/
	        		$('#holidaydiv').append(data.data);
	        		$('#date-format').val('');
	        		$('#date-format2').val('');
	        	}
	        	//hide messages
	        	setTimeout( 
                    function(){
                        $(".profile_danger").hide();
                        $(".profile_danger").hide();
                    } , 10000
                );
	        },
	    });
    });
});