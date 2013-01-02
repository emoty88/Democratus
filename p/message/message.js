$(document).ready(function() {
	document.getElementById('focus').scrollIntoView();
	
 
});
 
function deleteMessage(id,mode){
	
	if(mode==1){
		$(".x").show();
	}elseif(mode==0){
		$.post('/message/ajax/delete', {'id':id , 0},  function(response){ 
		if(response && response.result=='success'){
	   		window.document.location.reload();
	  	} else {
	    	
	 	}
	},'json');
	}
	
}
    
function message_submit(){
	data=$("#messageForm").serialize();
	$.post('/message/ajax/send', data,  function(response){ 
		if(response && response.result=='success'){
	   		window.document.location.reload();
	  	} else {
	    	
	 	}
	},'json');
}