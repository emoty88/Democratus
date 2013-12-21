
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
    $('#login-action').click(function(){ 
    	$("#login-form").submit();
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
        var userName = $("#register-user").val();
        var email = $("#register-email").val();

		var password = $("#register-pass").val();  	
		var password2 = $("#register-pass2").val(); 
		console.log(password );
		console.log(password2 );

		var male = $("#register-sex").val();
		if(password != password2)
		{
			$('#welcomeRegister-error').text("Şifreler birbiri ile uyumsuz.");
        	$('#welcomeRegister-error').show();
        	return false;	
		}
		$.post( '/ajax/register/', { name: name, userName:userName, email: email, password: password, password2: password2, male:male }, 
			function(data)
			{
				if(data.status=='success'){
					$('#welcomeMessage-textArea').text(data.message);
					$('#registerBtn').text('Kayıt Başarılı');

					if(data.action=="redirect")
					{
						location.href="/";
						return false;
					}
					$('#registerBtn').attr('id','registerBtn2');
					$('#registerBtn2').attr('disabled','disabled');
				} 
				else {
					console.log();
					$('#welcomeRegister-error').text("* "+data.message);
	            	$('#welcomeRegister-error').show();
					//$('#'+data.field).focus();
					//$('#applyform [name=name]').focus();
				}
				
			}, 'json');
		return false; 
    });
  	$('#register-action').click(function(){ 
    	$("#register-form").submit();
    });
});