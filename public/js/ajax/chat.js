/* chat js */
$( document ).ready(function() {
	
var test = localStorage["homepage-slider-caption"];
	function saveGameState() {
   
	
	alert(test);
    localStorage["delight"] = 1255543;}
	// load new chat (for the customer) //
	
	function saveGameState() {
    if (!supportsLocalStorage()) { return false; }
    localStorage["halma.game.in.progress"] = gGameInProgress;
    for (var i = 0; i < kNumPieces; i++) {
	localStorage["halma.piece." + i + ".row"] = gPieces[i].row;
	localStorage["halma.piece." + i + ".column"] = gPieces[i].column;
    }
    localStorage["halma.selectedpiece"] = gSelectedPieceIndex;
    localStorage["halma.selectedpiecehasmoved"] = gSelectedPieceHasMoved;
    localStorage["halma.movecount"] = gMoveCount;
    return true;
}
	
	
	//setInterval(function() {
		$.ajax({
                    url: "http://app.contact25.com/js/ajax/show_chat.php",
                    data: {
						
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						alert("nope");
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
 						$(".text_area").html(data);
						localStorage["chat"] = data;
                    }

        });
		
			$.ajax({
                    url: "http://app.contact25.com/index.php",
                    data: {
						
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
						
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
 						$("#displayer").html(data);
						localStorage["headersave2"] = data;
                    }

        });
		
	//}, 5000);

	$('.send_chat_message').click(function () {
		
		var enter_text = $('#enter_text').val();
		$.ajax({
                    url: "js/ajax/add_to_chat.php",
                    data: {
								enter_text:enter_text
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
 						$(".text_area").html(data);
                    }

        });
	});	

$('#nicere').click(function () {
		
		//var enter_text = $('#enter_text').val();
		$.ajax({
                    url: "show_chat.php",
                    data: {
								
								
							},
                    dataType: "text",
                    type: "POST",
                    error: function () {
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
 						alert();
                    }

        });
	});	
	 	 
	
});