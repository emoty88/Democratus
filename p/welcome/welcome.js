$(document).ready(function(){
	$("#login-form").submit(function () {
		var email = $("#login-user").val();
		var pass = $("#login-pass").val();
		if( !(email.length >1) || !(pass.length >1) ) return false;        
	        
	    $.post( '/ajax/login/', { email: email, pass: pass }, function(data){
	        
	        if(data.status=='success'){
	            //alert('success');
	            window.location = redirect;
	        } else {
	            
	            $('#welcomeLogin-error').text("* "+data.message);
	            $('#welcomeLogin-error').show();
	                            
	        }
	        
	    }, 'json');
	    return false;
    });
    
    
    $("#register-form").submit(function () {
		$('#welcomeRegister-error').text("");
        $('#welcomeRegister-error').hide();
	  	if(!$("#agree").is(":checked"))
        {
        	$('#welcomeRegister-error').text("* Lütfen kullanım sözleşmesini onaylayınız.");
        	$('#welcomeRegister-error').show();
        	return false;
        }
        var name = $("#register-name").val();
        var user = $("#register-user").val();
        var email = $("#register-email").val();
 
		var password = $("#register-pass").val();  	
		var password2 = $("#register-pass2").val(); 
		//kontrolleri ekle  
	        
	    $.post( '/ajax/login/', { email: email, pass: pass }, function(data){
	        
	        if(data.status=='success'){
	            //alert('success');
	            window.location = redirect;
	        } else {
	            
	            $('#welcomeRegister-error').text("* "+data.message);
	            $('#welcomeRegister-error').show();
	                            
	        }
	        return false;
	    }, 'json');
	    return false;
    });
});