$(document).ready(function() { 
	 $('#wellcome-login').live('click',function() {
        
        
        var email = $('#loginemail').attr('value');
        var pass = $('#loginpass').attr('value');
        
        //if( !isemail(email) ) return false;
        
        if( !(email.length >1) || !(pass.length >1) ) return false;        
        
        $.post( '/ajax/login/', { email: email, pass: pass }, function(data){
            
            if(data.status=='success'){
                //alert('success');
                window.location = "/";
            } else {
                $('#message').text(data.message);
                                
            }
            
        }, 'json');
        return false;
    });

    $('#wellcome-register-button').click(function() {
		
        $('.login-box').fadeToggle('slow');
        
        if($(this).html()=='Giriş Yap'){
        	$(this).html('Hemen Kayıt Ol');
                $('#message').hide();
        }else{
        	$(this).html('Giriş Yap');
                $('#message').hide();
        }
        return false;
    });
    
    $('.input').keypress(function(e) {
        if(e.which == 13) {
            $('#wellcome-login').focus().click();
        }
    });
	
    $('#registerBtn').live('click',function() {
		
        try{
            if(!$("#agree").is(":checked"))
            {
            	$('#message').text("Lütfen kullanım sözleşmesini onaylayınız.");
            	$('#message').show();
            	return false;
            }
            	
            //name
            var name = $('#name').val();
            
            //userName
            var userName = $('#userName').val();
            
            //email
            var email = $('#email').val();
            
            //password    
            var password = $('#password').val();
            
            //password2
            var password2 = $('#password2').val();
            
            
          
          //birth
            var male = $('#selectB').val();
            
            //captcha
          //  var captcha = $('#applyform input[name=captcha]').val();
            
            //send
            $.post( '/ajax/register/', { name: name, userName:userName, email: email, password: password, password2: password2,male:male }, function(data){
               
                if(data.status=='success'){
                    $('#message').text(data.message);
                    $('#registerBtn').text('Kayıt Başarılı');
                    $('#registerBtn').attr('id','registerBtn2');
                    $('#registerBtn2').attr('disabled','disabled');
                    
                } else if (data.status=='error') {
                    $('#message').text(data.message);
                    $('#message').show();
                    $('#applyform [name='+data.field+']').focus();
                    //$('#applyform [name=name]').focus();
                } else {
                    $('#message').text('bir hata oluştu!');
                    $('#message').show();
                    $('#applyform [name=name]').focus();
                }
                
            }, 'json');
            return false;
        
        
        
        
        
            //adddlert("Welcome guest!");
        } catch(err) { //err.description
            //alert( err.description );
        }
        
        
    });
    
    $('.forget_password').click(function() {
        var dialog = $(".dialog").dialog({
                         width: 400,
                         height: 300,
                         title: "Şifre sıfırlama", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).hide(); },
                         buttons: {
                            "Tamam": function() {
                                var email = $("#email-forgot").val();
                                
                                if(email.length<3) return false;
                                
                                $.post("/ajax/resetpassword", {  
                                                            email: $("#dialog input[name=email]").val()
                                                            
                                                            }, function(data){ 
                                                                if(data.status=='success'){
                                                                    $('#message').text(data.message);;
                                                                    //window.location = "/";
                                                                } else {
                                                                    $('#message').text(data.message);
                                                                }
                                                            
                                                                //eval(data); 
                                                            }, 'json');
                                                            
                                                            
                                $( this ).dialog( "close" );
                                $( this ).hide();
                                
                            },
                            "İptal": function() {
                                $( this ).dialog( "close" );
                                $( this ).hide();
                            }
                         }
                    });
        
        

        return false;
    });
 
});

