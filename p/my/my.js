jQuery(document).ready(function ($) {
	$('#kullanici_adi').focusout(function(){
		$.post("/ajax/check_settings/username", {value: $("#kullanici_adi").val()}, function(data){ 
		        if(data.status == 'success'){
		        	if(data.validate == 'false'){
		        		$('#usernameValidate').show();
		            	$('#usernameValidate').tooltip();
		            	
		            	disable_save_button();
		            	return;
		        	}else{
		        		$('#usernameValidate').hide();
		            	enable_save_button();
		        	}
		            if(data.there == 'true'){
		            	$('#usernameCheck').show();
		            	$('#usernameCheck').tooltip();
		            	disable_save_button();
		            	return;
		            }else{
		            	$('#usernameCheck').hide();
		            	enable_save_button();
		            }
		        }
		    },'json');   
	});
	
	$('#eposta').focusout(function(){
		$.post("/ajax/check_settings/email", {value: $('#eposta').val()}, function(data){ 
				
		        if(data.status == 'success'){
		        	if(data.validate == 'false'){
		        		$('#emailValidate').show();
		            	$('#emailValidate').tooltip();
		            	disable_save_button();
		            	return;
		        	}else{
		        		$('#emailValidate').hide();
		            	enable_save_button();
		        	}
		            if(data.there == 'true'){
		            	$('#emailCheck').show();
		            	$('#emailCheck').tooltip();
		            	disable_save_button();
		            }else{
		            	$('#emailCheck').hide();
		            	enable_save_button();
		            }
		        }
		    },'json');   
	});
	
	$('#ad_soyad').focusout(function(){
		$.post("/ajax/check_settings/name", {value: $('#ad_soyad').val()}, function(data){ 
		        if(data.status == 'success'){
		        	if(data.validate == 'false'){
		        		$('#ad_soyadValidate').show();
		            	$('#ad_soyadValidate').tooltip();
		            	disable_save_button();
		        	}else{
		            	$('#ad_soyadValidate').hide();
		            	enable_save_button();
		            }
		           
		        }
		    },'json');   
	});
	
	
	
	
	$('#kaydet_dgmesi').live('click',function(){
		var data = $('#profil_bilgileri_formu').serialize();
		console.log(data);
		$(this).html('Kaydediliyor');
		$.post("/ajax/save_settings", data, function(data){ 
		        if(data.status == 'success'){
		        	$('#kaydet_dgmesi').html('Kaydedildi');
		           
		        }else{
		        	$('#kaydet_dgmesi').html('Hata oluştu');
		        }
		},'json');   
	});
        
        $("#myprivacysave").live('click', function(){
            $("#myprivacysave").attr("disabled", true);
            $(this).html('Kaydediliyor');
            $.post( "/ajax/myprivacysave", $('#myprivacyform').serialize(), function(data){ 
                
                if(data.status == 'success'){
                    $("#myprivacysave").html('Kaydedildi');
                    
                }else{
                    $("#myprivacysave").html('Bir sorun oluştu');
                }
                
            },'json');

            $(this).removeAttr("disabled");
            return false;
        
        });
	
	function disable_save_button(){
		
		$('#kaydet_dgmesi').attr('disabled','disabled');
	}
	function enable_save_button(){
		$('#kaydet_dgmesi').removeAttr('disabled') ;
	}
});