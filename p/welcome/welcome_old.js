if (screen.width <= 699) {
document.location = "/mobile/welcome";
}
$(document).ready(function() { 
	
    $('#wellcome-register-button').click(function() {
        $('#wellcome-registerbox').slideToggle('slow');
        return false;
    });


    $('#applybutton_old').click(function() { 
        try{
            
            //name
            var name = $('#applyform input[name=name]').val();
            
            //email
            var email = $('#applyform input[name=email]').val();
            
            //password    
            var password = $('#applyform input[name=password]').val();
            
            //password2
            var password2 = $('#applyform input[name=password2]').val();
            
            //country
            var country = $('#applyform select[name=country]').val();
            
            //city
            var city = $('#applyform select[name=city]').val();
            
            //birth day
            var birthday = $('#applyform select[name=birthday]').val();
            //birth month
            var birthmonth = $('#applyform select[name=birthmonth]').val();
            //birth
            var birth = $('#applyform select[name=birth]').val();
            
            //captcha
            var captcha = $('#applyform input[name=captcha]').val();
            
            //send
            $.post( '/ajax/register/', { name: name, email: email, password: password, password2: password2, country: country, city: city, birthday: birthday, birthmonth: birthmonth, birth: birth, captcha: captcha }, function(data){
                
                if(data.status=='success'){
                    alert(data.message);
                    window.location = "/";
                } else if (data.status=='error') {
                    $('#message').text("<pre>"+data.message+"</pre>");
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
        
        
        
        
    $('#wellcome-login').click(function() {
        
        
        var email = $('#loginemail').attr('value');
        var pass = $('#loginpass').attr('value');
        
        //if( !isemail(email) ) return false;
        
        if( !(email.length >1) || !(pass.length >1) ) return false;        
        
        $.post( '/ajax/login/', { email: email, pass: pass }, function(data){
            
            if(data.status=='success'){
                //alert('success');
                window.location = "/";
            } else {
                alert(data.message);
                                
            }
            
        }, 'json');
        return false;
    });
 
    
    $('#forget_password').click(function() {
        //alert('forget password');
        
        
        var dialog = $("<div id=\"dialog\"></div>").html( '<form action="/" method="post" class="dialogform" id="dialog"><p><label>E-posta:</label><input type="text" id="email" name="email" value="" autocomplete="off" /><span class="message">kayıt olurken kullandığınız e-posta adresiniz.</span></p></form>' ).dialog({
                         width: 400,
                         height: 160,
                         title: "Şifre sıfırlama", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Tamam": function() {
                                var email = $("#dialog input[name=email]").val();
                                
                                if(email.length<3) return false;
                                
                                $.post("/ajax/resetpassword", {  
                                                            email: $("#dialog input[name=email]").val()
                                                            
                                                            }, function(data){ 
                                                                if(data.status=='success'){
                                                                    alert(data.message);
                                                                    window.location = "/";
                                                                } else {
                                                                    alert(data.message);
                                                                }
                                                            
                                                                //eval(data); 
                                                            }, 'json');
                                                            
                                                            
                                $( this ).dialog( "close" );
                                $( this ).remove();
                                
                            },
                            "İptal": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                            }
                         }
                    });
        
        

        return false;
    });
    
    
    
    $("#country").change(function() {
        var city = $('#city');
        city.attr('disabled',true);
        $.post("/ajax/getcities", { countryID: $(this).val() }, function(data){ 
            city.html('');
            for (row in data){
                city.append('<option value="'+data[row].ID+'">'+data[row].city+'</option>');
            }
        },'json');
        city.removeAttr('disabled');
    });
    
    //$( ".datefield" ).mask("9999-99-99");
    
    $("#agree").click(function(){            
        $("#applybutton").attr("disabled", !this.checked);
    });
   });
    $(document).ready(function() {

    	
    	var docW=$(document).width();
    	var welcomeW=980;
    	//var welcomeW=$("#welcomeCenter").width();
    	//var logoW=$("#logo").width();
    	//var mottoW=$("#motto").width();
    	var logoW=338;
    	var mottoW=484;
    	var formW=$("#form").width();
    	var formLW=$("#formLogin").width();
    	var farkW=docW-welcomeW;
    	var farkLW=docW-logoW;
    	var farkMW=docW-mottoW;
    	var farkFW=docW-formW;
    	var farkFLW=docW-formLW;
    	var leftPosG=parseInt(farkW/2);
    	var leftPosL=parseInt(farkLW/2);
    	var leftPosM=parseInt(farkMW/2);
    	var leftPosF=parseInt(farkFW/2);
    	var leftPosFL=parseInt(farkFLW/2);
    	
    	$("#welcomeCenter").offset({ top: 0, left:leftPosG });
    	$("#logo").offset({ top: 50, left:leftPosL });
    	$("#motto").offset({ top: 150, left:leftPosM });
    	$("#form").offset({ top: 270, left:leftPosF });
    	$("#formLogin").offset({ top: 270, left:leftPosFL });
    	$("#welcomeSocialBtn").offset({ top: 250, left:leftPosFL });
    	$('#welcomeCenter').fadeIn(2000);
    	var t=setTimeout("$('#logo').fadeIn(1000);$('#motto').fadeIn(1000);$('#form').fadeIn(1000);$('#changeButon').fadeIn(1000); $('#welcomeSocialBtn').fadeIn(1000);",300);
        $('#registerBtn').click(function() {
        try{
            if(!$("#agree").is(":checked"))
            {
            	$('#message').text("Lütfen kullanım sözleşmesini onaylayınız.");
            	$('#message').show();
            	return false;
            }
            	
            //name
            var name = $('#applyform input[name=name]').val();
            
            //userName
            var userName = $('#applyform input[name=userName]').val();
            
            //email
            var email = $('#applyform input[name=email]').val();
            
            //password    
            var password = $('#applyform input[name=password]').val();
            
            //password2
            var password2 = $('#applyform input[name=password2]').val();
            
            //country
            var country = $('#applyform select[name=country]').val();
            
            //city
            var city = $('#applyform select[name=city]').val();
            
            //birth day
            var birthday = $('#applyform select[name=birthday]').val();
            //birth month
            var birthmonth = $('#applyform select[name=birthmonth]').val();
            //birth
            var birth = $('#applyform select[name=birth]').val();
            
          //birth
            var male = $('#applyform select[name=male]').val();
            
            //captcha
            var captcha = $('#applyform input[name=captcha]').val();
            
            //send
            $.post( '/ajax/register/', { name: name, userName:userName, email: email, password: password, password2: password2, country: country, city: city, birthday: birthday, birthmonth: birthmonth, birth: birth, captcha: captcha, male:male }, function(data){
                
                if(data.status=='success'){
                    //alert(data.message);
                    window.location = "/";
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
	$( "#loginBtnA" ).live("click",function(e){
			    var email = $('#loginemail').attr('value');
			    var pass = $('#loginpass').attr('value');
			    
			    //if( !isemail(email) ) return false; 
			    
			    if( !(email.length >1) || !(pass.length >1) ) return false;        
			    
			    $.post( '/ajax/login/', { email: email, pass: pass }, function(data){
			        
			        if(data.status=='success'){
			            //alert('success');
			            window.location = "/";
			        } else {
			            alert(data.message);
			                            
			        }
			        
			    }, 'json');
			    return false;
			});
});		
function formChange(show)
{
	if(show=="form"){
		showB="kaydolBtn";
		hideB="girisBtn";
		hide="formLogin";
	}
	else{
		hideB="kaydolBtn";
		showB="girisBtn";
		hide="form";
	}
	$('#'+hide).fadeOut(1000);
	$('#'+show).fadeIn(1000);
	$('#'+hideB).hide();
	$('#'+showB).show();
	
}
