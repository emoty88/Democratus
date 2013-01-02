// Cufon - Ronnia Bold
//Cufon.replace('.box .title, .box .title_icon, .navigation li, .popup .title, .big_title_icon');
// jQuery - Document Ready Function
$(document).ready(function(){
    
    //wallready();
    
    $('#header_login_button').click(function() {
        
        
        var email = $('#header_login input[name=email]').attr('value');
        var password = $('#header_login input[name=password]').attr('value');
        var remember = $('#header_login input[name=remember]').attr('checked')?1:0; 
        
        if( !isemail(email) ) return false;
        
        if( !(email.length >1) || !(password.length >1) ) return false;        
        
        $.post( '/ajax/login/', { email: email, pass: password, remember: remember }, function(data){
            
            if(data.status=='success'){
                //alert('success');
                if(data.location)
                    window.location = data.location;
                else 
                    window.location = "/";
                    
            } else {
                alert(data.message);  
            }
            
        }, 'json');
        return false;
    });
    
    function search(){
        window.document.location.href = '/search/' + document.getElementById('searchtext').value;
        return false; 
    }
    
    


    $('#dicommenttext').keyup(function(){
        $('#dicommenttext').parent().find('.character .number').html(200 - $(this).val().length);
    });

	$('#share_idea textarea').keyup(function(){
        $('#share_idea .character .number').html(200 - $(this).val().length);
    });
    
    
	
	// Slider
    $('#video_slides').slides({
        preload                : true,
        generatePagination    : true,
        play                : 5000,
        pause                : 2500,
        hoverPause            : true,
        start                : 1,
    });
    
    $('#popular_slides').slides({
        preload                : true,
        generatePagination    : true,
        play                : 5000,
        pause                : 2500,
        hoverPause            : true,
        start                : 1,
    });    
	$('#clue_slides').slides({
		preload				: true,
		generatePagination	: true,
		play				: 5000,
		pause				: 2500,
		hoverPause			: true,
		start				: 1,
	});
	
	$('#parliament_slider').slides({
		generateNextPrev	: true,
		play	: 12000,
		generatePagination	: false,
		pause				: 15000,
		hoverPause			: true
	});	
	
	$('#confirmed_posts_slider').slides({
		preload				: true,
		generatePagination	: true,
		play				: 5000,
		pause				: 2500,
		hoverPause			: true,
		start				: 1
	});
	
	//parola alanlarında yazı göstermek için 
	//class ı focus olan input focuslanınca rel değerindeki idli input görünür kendisi gider
	$(".focus").live("focus",function (){
		showDomID=$(this).attr("rel");
		$("#"+showDomID).show();
		$("#"+showDomID).focus();
		$(this).hide();
		
	});
	//class ı blur olan input focuslanınca id değerinin sonuna _show eklenir ve o id li input gözükür kendisi gider değerindeki idli input görünür kendisi gider
	$(".blur").live("blur",function (){
		if($(this).val()=="")
		{
			showDomID=$(this).attr("id")+"_show";
			$("#"+showDomID).show();
			$(this).hide();
		}
	});	 
	
    $('#choose_users .user_group .user').click(function(){
        //$(this).delay(300).css({ 'background-color': '#edc6c6' });
        var ID = /deputy_(\d+)/i.exec($(this).attr('ID'));
        if($(this).hasClass('selected_user')){
            //ajax gonder
            deputyremove(ID[1]);
            $(this).remove();
        } else {
            //ajax gonder
        }
    });
	
	// Choose Deputy [Begin]
	$('#choose_friends .user_group .user').click(function(){
		//$(this).delay(300).css({ 'background-color': '#edc6c6' });
		var ID = /friend_(\d+)/i.exec($(this).attr('ID'));
        if($(this).hasClass('selected_user')){
            //ajax gonder
            deputyremove(ID[1]);
            $('.delete', this).hide();
        } else {
            //ajax gonder
           
            deputyadd(ID[1]);
            
            $('.delete', this).fadeIn('normal');
            $('.delete', this).click(function(e){e.stopPropagation();});
        }
        $(this).toggleClass('selected_user').delay(1000);
	});
	
});

