var dialog, activeagenda=0, archivestart=0;
var linkKarakter=0;
var linkCount=0;
var popoverProfileGet=new Array();
var notVoiceDetail=0;
var wallmoreAction=0;

$().ready(function() {
	
	//interactionPlay();
	//$(".tooltip-top").tooltip("show");
	$('button[data-toggle="tab"]').live("click",function(){
		var gotoTabName=$(this).attr("rel");
		currentTab=gotoTabName;
		if(gotoTabName!=undefined && gotoTabName.length>0)
			location.href="#"+gotoTabName;
	});
	
	var loc = location.href;
    var tabName=loc.split("#");
    if(tabName[1]!=undefined && tabName[1].length>0){
    	$('button[href="#tab-'+tabName[1]+'"]').tab("show");
    	currentTab=tabName[1];
    }

    
	$(".numberSay").live("keyup",function (){
		var id=$(this).attr("id");
		var sayi=200 - $(this).val().length;
		//console.log(linkCount);
		//console.log(linkKarakter);
		if(linkCount>0)
		{
			sayi=sayi+linkKarakter;
			sayi=sayi-(linkCount*20);
		}
		
		$('#'+id+'Number').html(sayi);
		if(sayi<0){
			$('#'+id+'Number').parent().css("color","#FF0000");
			$('#'+id+'Button').attr("disabled","true");
		}
		else
		{
			$('#'+id+'Button').removeAttr("disabled");
			$('#'+id+'Number').parent().css("color","#9B9B9B");
		}
			
	});

	
    var container = '';
    $(container + ' .follow').click(function(){ 
        var btn = $(this);
        var ID = btn.attr('rel');
		follow(ID);
    });

    $(container + ' .unfollow').click(function(){
        var btn = $(this);
        var ID = btn.attr('rel');
        unfollow(ID);  
    });  
     dicommentready();
     profileready();
    
    $('#sharedi').click(function(){
        var di = $('#shareditext').val();

        if(1==1)
        {
        	var linkli =$("#linkli").val();
	        var profileName =$("#profileName").val();
	        var profileID =$("#profileID").val();
	        var otherPID =$("#otherPID").val();
	        
	        var initem =$("#initem").val();
	        var initemName =$("#initem-name").val();
	        
	        var data="di="+di+"&linkli="+linkli+"&profileName="+profileName+"&profileID="+profileID+"&otherPID="+otherPID+"&initem="+initem+"&initemName="+initemName;
	        if(di.length<1) return;


	        $.post("/ajax/share",data, function(response){
	            if(response && response.result=='success'){
	                //alert('success');
	                //window.document.location.reload();
	            } else {
	                if(response.eval!=undefined)
	                {
	                	eval(response.eval);
	                }
	                else if(response.message!=undefined)
	                {
	                	alert(response.message);
	                }
	                else
	                {
	                	alert("Bilinmeyen bir hata sebebi ile paylaşımınız kaydedilemedi.");
	                }
	                //alert('error');
	            }
	            
	        },'json');	
	               
	        if($(".proposal").is(":checked"))
	        {
	        	 $.post("/ajax/ppaddnew", { spot: di }, function(data){ 
		            if(data && data.result=='success'){
		  			 	//asd
		            } else {
		                //alert('error');
		            }
		        },'json');
	        }
        }
        
        
    });
        
    $('#sharediGB').click(function(){
        var di = $('#geribildirim').val();
        var linkli =$("#linkliGB").val();
        var profileName =$("#profileNameGB").val();
        var profileID =$("#profileIDGB").val();
        var data="di="+di+"&linkli="+linkli+"&profileName="+profileName+"&profileID="+profileID;
        if(di.length<1) return;
        $.post("/ajax/share",data, function(data){
            if(data && data.result=='success'){
                //alert('success');
                window.document.location.reload();
            } else {
                //alert('error');
                window.document.location.reload();
            }
            
        },'json');
        
    });    
    $('#ppsend').click(function(){
        $('#ppsend').attr("disabled", "disabled");
        
        
        var pp = $('#pptext').val();
        if(pp.length<1) return;
        $.post("/ajax/ppaddnew", { spot: pp }, function(data){ 
            if(data && data.result=='success'){
                //alert('success');
                window.document.location.reload();
            } else {
                alert(data.message);
                window.document.location.reload();
            }
        },'json')
        .error(function() { alert("connection error"); $('#ppsend').removeAttr("disabled"); })
        .success(function() { $('#ppsend').removeAttr("disabled"); })
        ;        
        
        //$(this).removeAttr("disabled");
    });
    
    
   
    
    
    $('#wallmore').click(function(){ 
        var pid = $(this).attr('rel');
        var walltype = $("#walltype").val();
        
        $.post("/ajax/wallmore", { profileID: pid, start: wallstart, walltype:walltype }, function(data){ 
            if(data && data.count > 0){
                $('#wallcontainer').append(data.html);
                wallstart = data.start;
                //alert($('#wall' + data.start).html());
                wallready(wallstart);
                dicommentready();
                profilePopoverReady();
            } else {
                $('#wallcontainer').append(data.html);
                $('#wallmore').hide();
            }
        },'json');
        
    });
    
    
    $('#wallmorefollow').click(function(){ 
        var pid = $(this).attr('rel');
        var walltype = "follow";
        var wallstart=$('#wallstartfollow').val();
        wallmoreAction=1;
        $('#wallcontainerfollow').append('<p id="loadingTxt" style="width:500px; text-align:center;"> <img src="/images/loading.gif" />  </p>')
        
        $.post("/ajax/wallmore", { profileID: pid, start: wallstart, walltype:walltype }, function(data){ 
            if(data && data.count > 0){
            	
            	$("#loadingTxt").remove();
            	$('#wallcontainerfollow').append(data.html);
                wallstart = data.start;
                $('#wallstartfollow').val(wallstart);
                //alert($('#wall' + data.start).html());
                wallready(wallstart);
                dicommentready();
                profilePopoverReady();
                wallmoreAction=0;
            } else {
            	$("#loadingTxt").remove();
                $('#wallcontainerfollow').append(data.html);
                $('#wallmore').hide();
            }
        },'json');
        
        
    });
    $('#wallmorecagrilar').click(function(){ 
        var pid = $(this).attr('rel');
        var cagrilarstart=$('#cagrilarstart').val();
        $('#wallcagrilarContent').append("<p id='loadingTxt'>Yükleniyor..</p>")
        $.post("/ajax/cagrilarmore", { profileID: pid, start: cagrilarstart}, function(data){ 
            if(data && data.count > 0){
            	$("#loadingTxt").remove();
            	$('#wallcagrilarContent').append(data.html);
            	cagrilarstart = data.start;
                $('#cagrilarstart').val(cagrilarstart);
                wallready(cagrilarstart);
                dicommentready();
                profilePopoverReady();
            } else {
            	$("#loadingTxt").remove();
                $('#wallcagrilarContent').append(data.html);
                $('#wallmorecagrilar').hide();
            }
        },'json');
        
    });
    $('#getNewDiesFollow').click(function(){ 
        var pid = $(this).attr('rel');
        var walltype = "follow";
        var wallfirst=$('#wallfirstfollow').val();     
        get_newVoice(pid,walltype,wallfirst);
        /*
        $('#getNewDiesFollow').html("<p id='loadingTxt'>Yükleniyor..</p>")
   
        $.post("/ajax/wallmoreUp", { profileID: pid, first: wallfirst, walltype:walltype }, function(data){ 
            if(data && data.count > 0){
            	$("#loadingTxt").remove();
            	$('#getNewDiesFollow').after(data.html);
                wallfirst = data.first;
                $('#wallfirstfollow').val(wallfirst);
                $('#getNewDiesFollow').html(" ");
                $('#getNewDiesFollow').hide();
                //alert($('#wall' + data.start).html());
                //wallready(wallstart);
                dicommentready();
                profilePopoverReady();
            } else {
                $('#wallcontainerfollow').prepend(data.html);
                $('#wallmore').hide();
            }
        },'json');
        */
    });
    $('#getNewDiesDeputy').click(function(){ 
        var pid = $(this).attr('rel');
        var walltype = "deputy";
        var wallfirst=$('#wallfirstdeputy').val();
        
        $('#getNewDiesDeputy').html("<p id='loadingTxt'>Yükleniyor..</p>")
        $.post("/ajax/wallmoreUp", { profileID: pid, first: wallfirst, walltype:walltype }, function(data){ 
            if(data && data.count > 0){
            	$("#loadingTxt").remove();
            	$('#getNewDiesDeputy').after(data.html);
                wallfirst = data.first;
                $('#wallfirstdeputy').val(wallfirst);
                $('#getNewDiesDeputy').html(" ");
                $('#getNewDiesDeputy').hide();
                //alert($('#wall' + data.start).html());
                //wallready();
                dicommentready();
                profilePopoverReady();
            } else {
                $('#wallcontainerdeputy').prepend(data.html);
                $('#getNewDiesFollow').hide();
            }
        },'json');
        
    });

    $('#wallmoredeputy').click(function(){ 
        var pid = $(this).attr('rel');
        var walltype = "deputy";
        var wallstart=$('#wallstartdeputy').val();
        $('#wallcontainerdeputy').append("<p id='loadingTxt'>Yükleniyor..</p>")
        $.post("/ajax/wallmore", { profileID: pid, start: wallstart, walltype:walltype }, function(data){ 
            if(data && data.count > 0){
            	$("#loadingTxt").remove();
            	$('#wallcontainerdeputy').append(data.html);
                wallstart = data.start;
                $('#wallstartdeputy').val(wallstart);
                //alert($('#wall' + data.start).html());
                //wallready(wallstart);
                dicommentready();
                profilePopoverReady();
            } else {
                $('#wallcontainerdeputy').append(data.html);
                $('#wallmore').hide();
            }
        },'json');
        
    });

    function get_newVoice(pid,walltype,wallfirst)
    {
        $('#getNewDiesFollow').html("<p id='loadingTxt'>Yükleniyor..</p>");
        $.post("/ajax/wallmoreUp", { profileID: pid, first: wallfirst, walltype:walltype }, function(response){ 
            if(response && response.count > 0){
            	$("#loadingTxt").remove();
            	$('#getNewDiesFollow').after(response.html);
                wallfirst = response.first;
                $('#wallfirstfollow').val(wallfirst);
                $('#getNewDiesFollow').html(" ");
                $('#getNewDiesFollow').hide();
                //alert($('#wall' + data.start).html());
                //wallready(wallstart);
                dicommentready();
                profilePopoverReady();
            } else {
                //$('#wallcontainerfollow').prepend(data.html);
                $('#wallmore').hide();
                $("#loadingTxt").remove();
            }
        },'json');
    }
    $('#archivemore').click(function(){
        var pid = $(this).attr('rel');
        
        $.post("/archive/more", { start: archivestart }, function(data){ 
            if(data && data.count > 0){
                $('#confirmed_posts').append(data.html);
                archivestart = data.nextstart;
                //wallready(archivestart);
                dicommentready();
                profilePopoverReady();
            } else {
                $('#confirmed_posts').append(data.html);
                $('#archivemore').hide();
            }
        },'json');
        
    });
    $('#archivemoreNew').click(function(){
        var pid = $(this).attr('rel');
        
        $.post("/archive/more", { start: archivestart }, function(data){ 
            if(data && data.count > 0){
                $('#confirmed_posts').append(data.html);
                archivestart = data.nextstart;
                //wallready(archivestart);
                dicommentready();
                profilePopoverReady();
            } else {
                $('#confirmed_posts').append(data.html);
                $('#archivemoreNew').hide();
            }
        },'json');
        
    });
     
    
    $('.idea').hover(function(){
        $(this).find('.statistic_tip___').show();
    }, function(){
        $(this).find('.statistic_tip___').hide();
    });
    
   $(".voice-content").live("click",function(){
   	 	if($(this).hasClass("openVoice"))
   		{
   			$(this).removeClass("openVoice"); 	
   		}	
   		else
   		{
   			$(".openVoice").removeClass("openVoice"); 
   			$(this).addClass("openVoice"); 
   		}
   });
   
	profilePopoverReady();
   $("a").live("hover",function(){
   		var link =$(this).attr("href");
   		if(link!=undefined && link.substring(0, 1)=="/")
   		{
   			//içe direk linkler
   			var pathList=link.split("/");
   			switch(pathList[1])
   			{
   				case "profile":get_profileBio(pathList[2]); break;
   				//case "di":alert("di"); break;
   				//case "t":alert("tag"); break;
   			}
   		}
   });
   	if(paths[0]!="welcome"){
   		var detaylar={html:true, title:"Bildirimler", content:'<div class="popoverContent-noticeIcon">Yükleniyor</div>',placement:"bottom",trigger:"manual"};
		$("#noticeIcon").popover(detaylar); 
		
		var detaylar={html:true, title:"Messajlar", content:'<div class="popoverContent-messageIcon">Yükleniyor</div>',placement:"bottom",trigger:"manual"};
		$("#messageIcon").popover(detaylar); 
   	}
  	$("#noticeIcon").live("click",function(){
  		$("#noticeIcon").popover("toggle"); 
  		$.post("/notice/mini",  function(data){
			$(".popoverContent-noticeIcon").html(data);
		},'html');
  	});
  	$("#messageIcon").live("click",function(){
  		$("#messageIcon").popover("toggle"); 
  		$.post("/message/ajax/get_dialog",  function(response){
			$(".popoverContent-messageIcon").html(response);
		},'html');
  	});
    /* 
	$(".voice-content").mouseover(function(){
		//alert("test");
		$(".lost-item").show("visibility","hidden"); 
	});
	$(".voice-content").mouseout(function(){
		$(".lost-item").hide("visibility","none"); 
		console.log("asd");
	});
	*/
});//document ready sonu 

function profilePopoverReady()
{
	   
   
	$("a").each(function(){
  		var link =$(this).attr("href");
  		if(link != undefined)
  		{
  			if(link.substring(0, 1)=="/")
	   		{
	   			//içe direk linkler
	   			var pathList=link.split("/");
	   			switch(pathList[1])
	   			{
	   				case "profile":profilePopoverInit(this,pathList[2]); break;
	   				//case "di":alert("di"); break;
	   				//case "t":alert("tag"); break;
	   			}
	   		}
  		}

  	});
}
function profilePopoverInit(linkDom,userID)
{
	//console.log($(linkDom).attr("href"));
	var detaylar={html:true,content:'<div class="popoverContent-'+userID+'">Yükleniyor</div>',placement:"bottom"};
	$(linkDom).popover(detaylar);
	//console.log($('.popoverLoad-"'+userID).val());
}
function get_profileBio(userID)
{ 
	if($("#userLoad-"+userID).val()=="1")
	{
		$(".popoverContent-"+userID).html(popoverProfileGet[userID]);
		//$(".popoverContent-"+userID).html($(".popoverProfileContent-"+userID).html());
		//console.log(popoverProfileGet[userID]);
	}
	else
	{
		$.post("/ajax/get_profilePoppver", { ID: userID }, function(data){
			popoverProfileGet[userID]=data.html;
			$(".popoverContent-"+userID).html(data.html);
			$("body").prepend('<input type="hidden" value="1" id="userLoad-'+userID+'" />');
		},'json');
	}

}
//sikayetModalProfile
function profileready(){

    $('#profilecomplaint').click(function(){
        //alert('bu kısmı yazıyorum');

        var ID = $(this).attr('rel');
        $("#uygulaBtnProfile").attr("rel",ID);
        $.post("/ajax/profilecomplaintmenu", { ID: ID }, function(data){ 
            if(data && data.result=='success'){
            	$('.modal-bodyProfile').html(data.html);
				$('#sikayetModalProfile').modal('show'); 
				/*
                var dialogheight = data.height;

                var dialog = $("<div id=\"dialog\"></div>").html( data.html ).dialog({
                         width: 300,
                         height: dialogheight,
                         title: "Profili Şikayet Et!", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Tamam": function() {
                                $.post('/ajax/profilecomplaintmenusend/'+ID, $('#pxmenuform' + ID).serialize(), function(data){
                                    
                                },'json');
                                

                                $( this ).dialog( "close" );
                                $( this ).remove();
                                location.reload(false);

                            },
                            "İptal": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                            }
                         }
                    });
                    
                    $("div.complaintbox > *").css({ opacity:0.3 });
                    $("div.complaintbox :input").attr("disabled", "disabled");
                    
                    $("#pxmenuform"+ID+" input[name=complaint]").click(function(){
                        if(this.checked){
                            $("div.complaintbox > *").css({ opacity:1 });;
                            $("div.complaintbox :input").removeAttr("disabled");
                        } else {
                            $("div.complaintbox > *").css({ opacity:0.3 });
                            $("div.complaintbox :input").attr("disabled", "disabled");
                        }
                    });
                    
				*/
                
                
                
                
            } else {
                
                alert(data.message);
                
            }
        },'json');
        
        
        
    });
}


function dicommentready(){
    $('.comment').hover(function(){
        $(this).find('.hover').show();
    }, function(){
        $(this).find('.hover').hide();
    });
    
    $('.comment .hover .x').click(function(){
        //alert('bu kısmı yazıyorum');
        
        var ID = $(this).attr('rel');
        
        $.post("/ajax/dicxmenu", { ID: ID }, function(data){ 
            if(data && data.result=='success'){
                var dialogheight = data.height;

                var dialog = $("<div id=\"dialog\"></div>").html( data.html ).dialog({
                         width: 300,
                         height: dialogheight,
                         title: "Yankılar", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Tamam": function() {
                                $.post('/ajax/dicxmenusend/'+ID, $('#dicxmenuform' + ID).serialize(), function(data){
                                    location.reload(false);  
                                },'json');
                                
                                $( this ).dialog( "close" );
                                $( this ).remove();
                                
                                

                            },
                            "Iptal": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                            }
                         }
                    });
                    
                    $("div.complaintbox > *").css({ opacity:0.3 });
                    $("div.complaintbox :input").attr("disabled", "disabled");
                    
                    $("#dicxmenuform"+ID+" input[name=complaint]").click(function(){
                        if(this.checked){
                            $("div.complaintbox > *").css({ opacity:1 });;
                            $("div.complaintbox :input").removeAttr("disabled");
                        } else {
                            $("div.complaintbox > *").css({ opacity:0.3 });
                            $("div.complaintbox :input").attr("disabled", "disabled");
                        }
                    });
                    

                
                
                
                
            } else {
                
                alert(data.message);
                
            }
        },'json');
        
        
        
    });
    
    $("#uygulaBtn").click(function (){
    	var ID=$(this).attr("rel");
    	var formData=$(".dialogform").serialize();
    	$('.modal-body').html("Şikayetiniz İletiliyor Lütfen Bekleyiniz");
    	$.post('/ajax/dixmenusend/'+ID, formData, function(data){
    		//$('#sikayetModal').modal('hide');
    		$('.modal-body').html(data);
    		if(data.result=="success")
    		{
    			$('#sikayetModal').modal('hide');
    			$("#voice-"+ID).fadeOut(750); // sedece silmede bu çalışıcak diğerinde çalışmayacak
    		}
        },'json');
    }); 
    
    $("#uygulaBtnProfile").click(function (){
    	
    	var ID=$(this).attr("rel");
    	var formData=$(".dialogform").serialize();
    	$('.modal-bodyProfile').html("Şikayetiniz İletiliyor Lütfen Bekleyiniz");
    	$.post('/ajax/profilecomplaint/', formData, 
    	function(data){
    		//$('#sikayetModal').modal('hide');
    		$('.modal-bodyProfile').html(data);
    		if(data.result=="success")
    		{
    			$('#sikayetModalProfile').modal('hide');
    			//$("#voice-"+ID).fadeOut(750); // sedece silmede bu çalışıcak diğerinde çalışmayacak
    		}
        },'json');
    }); 
    
    /*
    if($("#editdialog input[name=type]:checked").val()){
        $.post('/ajax/profilecomplaint/', {  
                                    ID: ID,
                                    complaint: $("#editdialog input[name=type]:checked").val()
                                    }, 
                                    function(data){ 
                                        
                                        alert('Şikayetiniz alınmıştır. Teşekkür ederiz.');
                                    }, 'json');
        */
    $('.diSikayet').click(function(){
        var ID = $(this).attr('rel');
        $("#uygulaBtn").attr("rel",ID);
        $.post("/ajax/dixmenu", { ID: ID }, function(data){ 
            if(data && data.result=='success'){
            	$('.modal-body').html(data.html);
				$('#sikayetModal').modal('show');
            	/*
                var dialogheight = data.height;
                var dialog = $("<div id=\"dialog\"></div>").html( data.html ).dialog({
                         width: 300,
                         height: dialogheight,
                         title: "Yankılar", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Tamam": function() {
                                $.post('/ajax/dicxmenusend/'+ID, $('#dicxmenuform' + ID).serialize(), function(data){
                                    location.reload(false);  
                                },'json');
                                
                                $( this ).dialog( "close" );
                                $( this ).remove();
                                
                                

                            },
                            "Iptal": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                            }
                         }
                    });
                    
                    $("div.complaintbox > *").css({ opacity:0.3 });
                    $("div.complaintbox :input").attr("disabled", "disabled");
                    
                    $("#dicxmenuform"+ID+" input[name=complaint]").click(function(){
                        if(this.checked){
                            $("div.complaintbox > *").css({ opacity:1 });;
                            $("div.complaintbox :input").removeAttr("disabled");
                        } else {
                            $("div.complaintbox > *").css({ opacity:0.3 });
                            $("div.complaintbox :input").attr("disabled", "disabled");
                        }
                    });
                    */
            } else {
                alert(data.message);
            }
        },'json');
         
    });
}

function wallready(wallID){
    if(wallID==null) 
        wallID = '#firstwall';
    else
        wallID = '#wall' + wallID;
    
    //console.log(wallID);
    $(wallID + ' .idea').hover(function(){
        $(this).find('.statistic_tip').show();
    }, function(){
        $(this).find('.statistic_tip').hide();
    });
    
    $(wallID + ' .idea .statistic_tip .x').click(function(){

        
        var ID = $(this).attr('rel');
        
        $.post("/ajax/dixmenu", { ID: ID }, function(data){ 
            if(data && data.result=='success'){
                var dialogheight = data.height;

                var dialog = $("<div id=\"dialog\"></div>").html( data.html ).dialog({
                         width: 300,
                         height: dialogheight,
                         title: "Ses", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Tamam": function() {
                                $.post('/ajax/dixmenusend/'+ID, $('#dixmenuform' + ID).serialize(), function(data){
                                    
                                },'json');
                                

                                $( this ).dialog( "close" );
                                $( this ).remove();
                                //location.reload(false);

                            },
                            "İptal": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                            }
                         }
                    });
                    
                    $("div.complaintbox > *").css({ opacity:0.3 });
                    $("div.complaintbox :input").attr("disabled", "disabled");
                    
                    $("#dixmenuform"+ID+" input[name=complaint]").click(function(){
                        if(this.checked){
                            $("div.complaintbox > *").css({ opacity:1 });;
                            $("div.complaintbox :input").removeAttr("disabled");
                        } else {
                            $("div.complaintbox > *").css({ opacity:0.3 });
                            $("div.complaintbox :input").attr("disabled", "disabled");
                        }
                    });
                    

                
                
                
                
            } else {
                
                alert(data.message);
                
            }
        },'json');
        
        
        
    });
}

function dixready(){
    
}

function profilecomplaint(ID){
    
        
        var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="form">'
                
                + '<p>'
                + '<input type="radio" name="type" value="1" />Şiddet içeren içerik<br />'
                + '<input type="radio" name="type" value="2" />Pornografik paylaşım<br />'
                + '<input type="radio" name="type" value="3" />Kişisel haklara saldırı<br />'
                + '<input type="radio" name="type" value="4" />Diğer<br />'
                + '</p>'
                
                + '</form>';        
        
        var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                         width: 300,
                         height: 220,
                         title: "Şikayet", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Save": function() {
                                if($("#editdialog input[name=type]:checked").val()){
                                    $.post('/ajax/profilecomplaint/', {  
                                                                ID: ID,
                                                                complaint: $("#editdialog input[name=type]:checked").val()
                                                                }, 
                                                                function(data){ 
                                                                    
                                                                    alert('Şikayetiniz alınmıştır. Teşekkür ederiz.');
                                                                }, 'json');
                                                                
                                    $( this ).dialog( "close" );
                                    $( this ).remove();
                                }
                            },
                            "Cancel": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                                
                            }
                         }
                    });

}

function dicomplaint(ID){
    
        
        var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="form">'
                
                + '<p>'
                + '<input type="radio" name="type" value="1" />Şiddet içeren içerik<br />'
                + '<input type="radio" name="type" value="2" />Pornografik paylaşım<br />'
                + '<input type="radio" name="type" value="3" />Kişisel haklara saldırı<br />'
                + '<input type="radio" name="type" value="4" />Diğer<br />'
                + '</p>'
                
                + '</form>';        
        
        var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                         width: 300,
                         height: 220,
                         title: "Şikayet", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Save": function() {
                                if($("#editdialog input[name=type]:checked").val()){
                                    $.post('/ajax/dicomplaint/', {  
                                                                ID: ID,
                                                                complaint: $("#editdialog input[name=type]:checked").val()
                                                                }, 
                                                                function(data){ 
                                                                    
                                                                    alert('Şikayetiniz alınmıştır. Teşekkür ederiz.');
                                                                }, 'json');
                                                                
                                    $( this ).dialog( "close" );
                                    $( this ).remove();
                                }
                            },
                            "Cancel": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                                
                            }
                         }
                    });

}

function redi(ID){
    $.post("/ajax/redi", {ID: ID}, function(data){ 
        if(data.result == 'success'){
            //$('#dilikeinfo' + ID ).html(data.likeinfo.html);
            alert('Duvarınıza eklendi!');
            window.document.location.reload();
        }
    },'json');    
} 

function ppvote(ID, vote){
    $.post("/ajax/ppvote", {ID: ID, vote: vote}, function(data){ 
        if(data.result == 'success'){
            $('#ppbuttons' + ID ).html(data.buttons.html);
        }
    },'json');    
}

function ppadd(){
    
        
        var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="form">'
                + '<div class="column">'
                + '<p><label>Başlık</label>'
                + '<input type="text" name="title" id="title" value="" /><span class="required">*</span>'
                + '</p>'
                + '<p><label>Kısa Açıklama</label>'
                + '<textarea name="spot" id="spot"></textarea>'
                + '</p>'
                + '</div>'
                + '</form>';        
        
        var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                         width: 500,
                         height: 300,
                         title: "New Propostal", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Save": function() {
                                $.post('/ajax/ppadd/', {  
                                                            title: $("#editdialog input[name=title]").val(),
                                                            spot: $("#editdialog textarea[name=spot]").val()
                                                            }, 
                                                            function(data){ 
                                                                
                                                                alert(data.result + ' - ' + data.message);
                                                            }, 'json');
                                                            
                                $( this ).dialog( "close" );
                                $( this ).remove();
                            },
                            "Cancel": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                                
                            }
                         }
                    });

}

function deputyadd(ID){ //alert($(this).html());
    $.post("/ajax/deputyadd", {ID: ID}, function(data){ 
        if(data.result == 'success'){
            //$('#dilikeinfo' + ID ).html(data.likeinfo.html);
            //alert('Vekiliniz onaylandı');
            $("#NotDeputy-"+ID).hide();
            $("#deputy-"+ID).show();
            location.reload();
        } 
        return data;
    },'json');    
}

function deputyremove(ID){
	return false;
	/*
    $.post("/ajax/deputyremove", {ID: ID}, function(data){ 
        if(data.result == 'success'){
            //$('#dilikeinfo' + ID ).html(data.likeinfo.html);
            //alert('Vekiliniz çıkarıldı');
        	$("#NotDeputy-"+ID).show();
            $("#deputy-"+ID).hide();
        	$("#vekilContent-"+ID).fadeOut();
        } 
        
        return data;
    },'json');    
    */
}

function dilikeCancel(ID){
    $.post("/ajax/likeCancel/di", {ID: ID}, function(data){ 
        if(data.result == 'success' && data.likeinfo.result=='success'){
            $('#dilikeinfo' + ID ).html(data.likeinfo.html);
        }
    },'json');    
}
function dilike(ID, like){
    $.post("/ajax/like/di", {ID: ID, like: like}, function(data){ 
        if(data.result == 'success' && data.likeinfo.result=='success'){
            $('#dilikeinfo' + ID ).html(data.likeinfo.html);
        }
    },'json');    
}
function dilikeCommentCancel(ID){
    $.post("/ajax/likeCancel/dicomment", {ID: ID}, function(data){ 
        if(data.result == 'success' && data.likeinfo.result=='success'){
            $('#diclikeinfo' + ID ).html(data.likeinfo.html);
        }
    },'json');    
}
function diclike(ID, like){
    $.post("/ajax/like/dic", {ID: ID, like: like}, function(data){ 
        if(data.result == 'success' && data.likeinfo.result=='success'){
            $('#diclikeinfo' + ID ).html(data.likeinfo.html);
        }
    },'json');    
}

function agendavoteold(ID){
    var vote = $('#agenda' + ID + ' input[name=vote]:checked').val();
    
    $.post("/ajax/agendavote", {ID: ID, vote: vote}, function(data){ 
        if(data.result == 'success'){
            $('#votebutton' + ID ).attr('disabled', 'disabled');
            $('#votebuttonlabel' + ID ).text(data.message).fadeIn(700).delay(2000).fadeOut(700);
            
        } else {
            $('#votebutton' + ID ).attr('disabled', 'disabled');
            $('#votebuttonlabel' + ID ).text(data.message).fadeIn(700).delay(2000).fadeOut(700);
        }
        
    },'json');    
}
function tasariKaldir(ID)
{
	if(confirm("Bu tasarınızı silmek istediğinize emin misiniz?"))
	{
		 $.post("/ajax/deleteProposal", { ID: ID }, function(data){ 
	        if(data && data.result=="success"){
	        	window.document.location="/archive#tasari";
	        } else {
	            alert("Tasarı Silinemedi Tekrar Deneyiniz");
	        }
	    },"json");
	}else
	{
		return false;
	}
}
function checknull(value){return value==null?'':value;}

function isemail(address) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   return reg.test(address);
}
function interactionPlay()
{
	var first=$("#interactionFirstID").val();
	var count=1;
	if(first==0)
	{
		count=10;
	}
		$.post("/ajax/interaction", {first: first,count:count}, function(data){ 
	        if(data && data.result=="success"){
	        	
	        	$("#interactionFirstID").val(data.first);
	        	$("#interactionBar").prepend(data.html)
	        	
	        	var k=setTimeout(function(){$('.iaLine').css("background-color", 'transparent'); },2000);
	        	 

	        	//console.log(data.html);
	        	var t=setTimeout(function(){interactionPlay();},1000);
	        } else {
	           //alert("Tasarı Silinemedi Tekrar Deneyiniz");
	        	var t=setTimeout(function(){interactionPlay();},1000);
	        }
	    },"json");
	
}
function yorumGenis(id)
{
	$('#'+id).animate({
		    height: '70',
		    width: '500'
		  }, 500, function() {
		    // Animation complete.
			 $(".hideArea-"+id).fadeIn(700);
	});
}
function yorumDar (id)
{
	var element=document.getElementById(id);
	
	if(element.value==element.defaultValue)
	{ 
		$(".hideArea-"+id).fadeOut(250);
		$('#'+id).animate({
		    height: '15',
		    width: '400'
		  }, 500, function() {
		    // Animation complete.
			 
		});
	}
	/*
	if($("#"+id).val()=="")
	{ 
		$("#karakterGrubu").fadeOut(250);
		$('#'+id).animate({
		    height: '15',
		    width: '400'
		  }, 500, function() {
		    // Animation complete.
			 
		});
	}
	*/
}
function voiceDetail(voiceID,uniqueKey)
{
	if(notVoiceDetail==1)
	{
		notVoiceDetail=0;
		return false;
	}
	if($("#openStatus-"+voiceID+"-"+uniqueKey).val()==1)
	{
		$("#openStatus-"+voiceID+"-"+uniqueKey).val("0");
		$("#di_subArea-"+voiceID+"-"+uniqueKey).slideUp("fast");
		if(paths[0]!="di" && voiceID!=parseInt(paths[1]))
		$("#di_topArea-"+voiceID+"-"+uniqueKey).slideUp("fast");
	}
	else
	{
		$("#openStatus-"+voiceID+"-"+uniqueKey).val("1");
		$("#di_subArea-"+voiceID+"-"+uniqueKey).slideDown("fast");
		if(paths[0]!="di" && voiceID!=paths[1])
		$("#di_topArea-"+voiceID+"-"+uniqueKey).slideDown("fast");
	}
	if($("#itemLoaded-"+voiceID+"-"+uniqueKey).val()==0)
	{
		if($("#initem-"+voiceID+"-"+uniqueKey).val()==1 )
		{
			get_voiceImage(voiceID,uniqueKey);
		}
		if($("#isReply-"+voiceID+"-"+uniqueKey).val()==1 )
		{
			if(paths[0]!="di" && voiceID!=paths[1])
			get_repliedVoice(voiceID,$("#replyID-"+voiceID+"-"+uniqueKey).val(),uniqueKey);
			
		}
		if(paths[0]=="di"){
			if(voiceID!=parseInt(paths[1]))
				get_voiceReply(voiceID,uniqueKey);
		}
		else
		{
			get_voiceReply(voiceID,uniqueKey);
		}
	}
	
}
function get_voiceImage(voiceID,uniqueKey)
{
	$("#di_subAreaConten-"+voiceID+"-"+uniqueKey).html('<center><img src="/images/progress.gif" /> Yükleniyor.</center>');
	$.post('/ajax/get_voiceImage/'+voiceID, "voiceID="+voiceID,  function(data){
    		if(data.success=="success")
    		{
    			$("#di_subAreaConten-"+voiceID+"-"+uniqueKey).hide().html('<img src="'+data.reeImagePath+'" />').slideDown("fast");
    			$("#itemLoaded-"+voiceID+"-"+uniqueKey).val("1");
    		}
        },'json');
}
function get_repliedVoice(voiceID,repliedID,uniqueKey)
{
	//$("#di_topAreaConten-"+voiceID).html('<center><img src="/images/progress.gif" /> Yükleniyor.</center>');
	$.post('/ajax/get_repliedVoice/'+repliedID, "voiceID="+repliedID,  function(data){
    		if(data.success=="success")
    		{
    			$("#di_topAreaConten-"+voiceID+"-"+uniqueKey).hide().html(data.html.html).slideDown("fast");
    			$("#itemLoaded-"+voiceID+"-"+uniqueKey).val("1");
    		}
        },'json');
}


function createUploader(){            
	var uploader = new qq.FileUploader({
    	element: document.getElementById('resimYukleAlan'),
    	action: '/ajax/upload_image',
    	listElement: document.getElementById('separate-list'),
    	multiple:false,
    	maxConnections:1,
    	onComplete: function(id, fileName, responseJSON){
    		$("#initem").val("1");
    		$("#initem-name").val(responseJSON.fileName);
    		
    	},
    	debug: false
	});           
}
 function createDinamicUploader(elementID,data) {
 	
	var uploader = new qq.FileUploader({
		element: document.getElementById(elementID),
	    action: '/ajax/upload_image',
	    params: data,
		uploadButtonText: '<i class="icon-upload"></i> <strong>Resim Ekle</strong>',
		template: '<div class="qq-uploader">' +
		'<div class="qq-upload-button-Unique" style="">{uploadButtonText}</div>' +
		'<ul class="qq-upload-list" style="margin-top: 10px; text-align: center;"></ul>' +
		'</div>',
		 classes: {
			button: 'qq-upload-button-Unique',
			list: 'qq-upload-list',
			drop: 'qq-upload-list',
			progressBar: 'qq-progress-bar',
			file: 'qq-upload-file',
			spinner: 'qq-upload-spinner',
			finished: 'qq-upload-finished',
			size: 'qq-upload-size',
			cancel: 'qq-upload-cancel',
			failText: 'qq-upload-failed-text',
			success: 'alert alert-success',
			fail: 'alert alert-error',
			successIcon: null,
			failIcon: null
		 },
		 onComplete: function(id, fileName, responseJSON){
    		sevaHashTagImage(data,responseJSON);
    		
    	}
	});
}
function sevaHashTagImage(data,responseJSON)
{
	var data={hastag:data.hastag, imageID:data.imageID, uploadDir:responseJSON.uploadDir, imageName:responseJSON.fileName};
	$.post('/t/ajax/changeImage', data,  function(response){ 
			if(response.success=="success") 
			{
				changeHashTagImage(data,responseJSON);
			}
		},'json');
}
function changeHashTagImage(data,responseJSON)
{
	var data={hastag:data.hastag};
	$.post('/t/ajax/get_onlyPhotoHtml', data,  function(response){ 
			//console.log(response);
			if(response.success=="success") 
			{
				$("#image-Content").html(response.tagImage.html);
				//changeHashTagImage(data,responseJSON);
			}
		},'json');
}
function voice_mention_submit(id)
{
	data=$("#voiceMentionForm-"+id).serialize();
	$.post('/ajax/share', data,  function(response){ 
		if(response && response.result=='success'){
	   		window.document.location.reload();
	  	} else {
	    	if(response.eval!=undefined)
	        {
	          	eval(response.eval);
	        }
	        else if(data.message!=undefined)
	        {
	           	alert(response.message);
	        }
	        else
	        {
	           	alert("Bilinmeyen bir hata sebebi ile paylaşımınız kaydedilemedi.");
	        }
	         //alert('error');
	 	}
	},'json');
}
function populardiToPopular(ID)
{
	data="populardiID="+ID;
	$.post('/ajax/set_popularToProposal', data,  
		function(response){ 
			if(response.success=="success")
				alert("Tasarı olarak kaydedildi.");
			else
				alert("Sorun oluştu daha sonra tekrar deneyiniz.")
		},'json');
}
function parentStop()
{
	$(this).click(function(event){
		console.log(event);
		event.stopPropagation();
	});
}
function send_activateMail(mail)
{
	var data="mail="+mail;
	$.post('/ajax/send_activationMail', data,  
		function(response){ 
			hide_alertBox("alert");
			//mail gönderildi uyarısı 
			//console.log(response);
			/*
			if(response.success=="success")
				//alert("Tasarı olarak kaydedildi.");
			else
				//alert("Sorun oluştu daha sonra tekrar deneyiniz.")
			*/
		},'json');
		
}
	function follow(ID)
    {
        $.post("/ajax/follow", {
                ID: ID,
                }, function(response){
                    if(response.status=='success'){
                        $('#follow' + ID).toggle();
                        $('#unfollow' + ID).toggle();
                    } else {
                        alert(response.message);
                    }
           }, 'json');
    }
    function unfollow(ID)
    {
        $.post("/ajax/unfollow", {
                ID: ID,
                }, function(response){
                    if(response.status=='success'){
                        $('#follow' + ID).toggle();
                        $('#unfollow' + ID).toggle();
                        //window.document.location.reload();
                    } else {
                        alert(response.message);
                    }
		}, 'json');
    }