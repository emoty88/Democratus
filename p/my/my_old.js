var dialog;

$(function(){
    /*
    $( ".datefield" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
    });//.mask("9999-99-99");
    */
    $("#specialUrlSave").click(function(){
    	$.post("/my/ajax/kullaniciSave", { ka: $("#benzersizka").val() }, function(data){ 
            if(data.status=="error")
            {
            	$("#uyari").html("Uyarı: "+data.errorNote);
            	$("#specialUrlSave").attr("disabled","true");
            }
            else
            {
            	$("#uyari").html(data.successNote);
            	$("#specialUrlSave").removeAttr("disabled");
            	location.href=location.href;
            }
        },'json');
    });
	$("#specialUrlCheck").click(function(){
		//console.log("kontrol ediniz");
		$.post("/my/ajax/kullaniciCheck", { ka: $("#benzersizka").val() }, function(data){ 
            console.log(data.varmi);  
            if(data.status=="error")
            {
            	$("#uyari").html("Uyarı: "+data.errorNote);
            	$("#specialUrlSave").attr("disabled","true");
            }
            else
            {
            	$("#uyari").html(data.successNote);
            	$("#specialUrlSave").removeAttr("disabled");
            }
        },'json');
	});
	
    $("#benzersizka").live('keyup',function(e){
    	if($(this).val().length>5 && $(this).val().length<26)
    	{
    		var bka = $(this).val();
			letters = /^([a-zA-Z0-9._-]+)$/; 
			if (bka.match(letters) )   
			{   
			  if($("#uyari").html().length>0)
			  	$("#uyari").html("");
			  $("#specialUrlCheck").removeAttr("disabled");
			  $("#specialUrlSave").attr("disabled","true");
			}
			else {
				$("#uyari").html("Uyarı: Sadece harf, rakam ve (- _ .) karakterlerinden oluşan bir kullanıcı adı belirlemelisiniz. ");
				$("#specialUrlCheck").attr("disabled","true");
				$("#specialUrlSave").attr("disabled","true");
			}

    		//console.log($(this).val());
    	}
    	else
    	{
			  	$("#uyari").html("Uyarı: Kullanıcı adınız en az 6 en fazla 25 karakter olmalıdır.");
			  	$("#specialUrlCheck").attr("disabled","true");
			  	$("#specialUrlSave").attr("disabled","true");
    	}
		
	});  
    $("#myprofileform #countryID").change(function() {
        $.post(ajaxurl + "getcities", { countryID: $(this).val() }, function(data){ 
            
            var city = $('#cityID');
            
            city.html('');
            
            for (row in data){
                city.append('<option value="'+data[row].ID+'">'+data[row].city+'</option>');
            }
            
        },'json');
    });
    

    $("#myprofilesave").click(function(){
    	if($("#imageFileinput").val()!="")
    		ajaxFileUpload();
        $(this).attr("disabled", true);
        if($("#name").val().length<4)
        {	
        	$("#myprofileResponse").html("İsim Soyisim alanı en az 4 karakter olmalıdır.").addClass("alertBox").fadeIn(2000);
            var t=setTimeout("$('#myprofileResponse').fadeOut(2000);",7000);
        	return false;
        }
        $.post(ajaxurl + "myprofilesave", $('#myprofileform').serialize(), function(data){ 
            eval(data);
            //alert('Bilgileriniz kaydedildi.') ;
            $("#myprofileResponse").html("Bilgileriniz kaydedildi.").addClass("infoBox").fadeIn(2000);
            var t=setTimeout("$('#myprofileResponse').fadeOut(2000);",7000);
        });
        
        $(this).removeAttr("disabled");
        return false;
        
    });    

    $("#myemailchangebutton").click(function(){
        $(this).attr("disabled", true);
                     
        $.post(ajaxurl + "myemailchange", $('#myemailchangeform').serialize(), function(data){ 
            if(data && data.status=='success'){
                alert(data.message);
            }
            
            //eval(data); 
        },'json');
        
        $(this).removeAttr("disabled");
        return false;
        
    });
    
    $("#mypasswordchangebutton").click(function(){
        $(this).attr("disabled", true);
                     
        $.post(ajaxurl + "mypasswordchange", $('#mypasswordchangeform').serialize(), function(data){ 
            if(data && data.status=='success'){
                alert(data.message);
                
            } else {
                alert(data.message);
            }
        },'json');
        
        $(this).removeAttr("disabled");
        return false;
        
    });
    
    
    
    
    $("#myprivacysave").click(function(){
        $(this).attr("disabled", true);
                     
        $.post(ajaxurl + "myprivacysave", $('#myprivacyform').serialize(), function(data){ 
            eval(data);
            $("#myprivacyResponse").html("Bilgileriniz kaydedildi.").addClass("infoBox").fadeIn(2000);
            var t=setTimeout("$('#myprivacyResponse').fadeOut(2000);",7000);
            //alert('Bilgileriniz kaydedildi.') ;
        });
        
        $(this).removeAttr("disabled");
        return false;
        
    });


    $(".myphoto .makeprofilephoto").click(function(){
        $(this).attr("disabled", true);
        
        $.post(ajaxurl + "makeprofilephoto", { photoID: $(this).attr('rel') }, function(data){ 
            if(data && data.status=='success'){
                alert('success');
                window.document.location.href = window.document.location.href;
            } else {
                alert('error');
            }
        },'json');
        
        $(this).removeAttr("disabled");
        return false;
    });
    
    $(".makeProfilPhoto").click(function(){
        $(this).attr("disabled", true);
        
        $.post(ajaxurl + "makeprofilephoto", { photoID: $(this).attr('rel') }, function(data){ 
            if(data && data.status=='success'){
                alert('Profil Fotoğrafınız Başarı ile Güncellendi.');
                window.document.location.href = window.document.location.href;
            } else {
                alert('error');
            }
        },'json');
        
        $(this).removeAttr("disabled");
        return false;
    });
    
    
    $(".myphoto .removemyimage").click(function(){
        
        if(!confirm("Are you sure?")) return;
        
        $(this).attr("disabled", true);
        
        $.post(ajaxurl + "removemyimage", { photoID: $(this).attr('rel') }, function(data){ 
            if(data && data.status=='success'){
                alert('success');
                window.document.location.href = window.document.location.href;
            } else {
                alert('error');
            }
        },'json');
        
        $(this).removeAttr("disabled");
        return;
        
    });
    $(".removeImg").click(function(){
        if(!confirm("Bu Resmi Kaldırmak İstediğinize Eminmisiniz??")) return;
        var ID=$(this).attr('rel');
        $.post(ajaxurl + "removemyimage", { photoID: $(this).attr('rel') }, function(data){ 
        	if(data && data.status=='success'){
        		$("#imageListLi-"+ID).remove();
            	//alert('success');
                //window.document.location.href = window.document.location.href;
            } else {
                //alert('error');
            }
        },'json');
        
        $(this).removeAttr("disabled");
        return;
        
    });
});
	function fbPaylasimTogle()
    {
		$.ajax({
			  url: "/my/ajax/facebookPaylasIzin",
			  success: function(data){
			    if(data=="tamam");
			    location.href=location.href; 
			  }
			});
    }
	function twPaylasimTogle()
    {
		$.ajax({
			  url: "/my/ajax/twitterPaylasIzin",
			  success: function(data){
			    if(data=="tamam");
			    location.href=location.href; 
			  }
			});
    }
	function imageSliderPrev()
	{
		$('#imageSliderUl').animate({left:'+=215'}, 250,"linear");
	}
	function imageSliderNext()
	{
		$('#imageSliderUl').animate({left:'-=215'}, 250,"linear");
	}
	function ajaxFileUpload()
	{
		/*
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		*/
		$.ajaxFileUpload
		(
			{
				url:'/my/ajax/imageUploadNew',
				secureuri:false,
				fileElementId:'imageFileinput',
				dataType: 'json',
				data:{name:'image', id:'id'},
				success: function (data, status)
				{
					console.log(data);
					console.log(status);
					/*
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							alert(data.msg);
						}
					}
					*/
				},
				error: function (data, status, e)
				{
					console.log(data);
					console.log(status);
					console.log(e);
				}
			}
		)
		
		return false;

	}
