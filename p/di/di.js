var profileID = 0;
$().ready(function() {
    //$(".repled-box:first").show();
    $('#dicommentita').click(function(){
        var comment = $('#dicommenttext').val();
        if(comment.length<1) {return false;}
        if(comment.length>200) {return false;}
        $.post("/ajax/dicomment", { ID: diID, comment: comment }, function(data){ 
            if(data && data.result=='success'){
                //alert('success');
                //window.document.location.href = window.document.location.href;
                $('#dicomments').append(data.comment.html);
                $('#dicommenttext').val('');
                window.document.location.reload();
            } else {
                alert('error');
            }
        },'json');
        
    });

    $("#load_moreComment").live("click",function() {
    	
   		$.post("/ajax/get_moreReply", { ID: diID, start: commentFID, limit:10 }, function(response){ 
            if(response && response.result=='success'){
                commentFID=response.start;
                loadVoiceCount=loadVoiceCount+response.count;
                if(totalReplyCount>loadVoiceCount)
                {
                	$("#count_span").text(totalReplyCount-loadVoiceCount);
                }
                else
                {
                	$("#get_moreReplyBox").remove();
                }
                $('#dicomments').prepend(response.html);
                $("#shareditext-"+diID).focus();
                
            } else {
                alert('error');
            }
        },'json');
    });
    
    $('#wallmore').click(function(){
        
        $.post("/ajax/wallmore", { profileID: profileID, start: wallstart }, function(data){ 
            if(data && data.count > 0){
                $('#wallcontainer').append(data.html);
                wallstart = data.start;
            } else {
                $('#wallcontainer').append(data.html);
                $('#wallmore').hide();
            }
        },'json');
        
    });
    
    
    
    $('.comment .xx').click(function(){
        var ID = $(this).attr('rel');
        
        $.post("/ajax/dixmenu", { ID: ID }, function(data){ 
            if(data && data.result=='success'){
                var dialogheight = data.height;

                var dialog = $("<div id=\"dialog\"></div>").html( data.html ).dialog({
                         width: 300,
                         height: dialogheight,
                         title: "Di", 
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
    $('.diCommentSikayet').click(function(){
        var ID = $(this).attr('rel');
        $("#uygulaBtnComment").attr("rel",ID);
        $.post("/ajax/dicxmenu", { ID: ID }, function(data){ 
        	if(data && data.result=='success'){
            	$('.modal-bodyComment').html(data.html);
				$('#sikayetModalComment').modal('show');
            } else {
                alert(data.message);
            }
        },'json');
    });
    
    $("#uygulaBtnComment").click(function (){
    	var ID=$(this).attr("rel");
    	
    	 $.post('/ajax/dicxmenusend/'+ID, $('#dicxmenuform' + ID).serialize(), function(data){
             if(data.result=="success")
             {
            	 //alert("alert");
            	 location.href=location.href;
             }
         },'json');
    });
    
});
function notsendnotice(profileID,diID)
{
    $.post("/ajax/notsendnotice", {profileID: profileID, diID: diID}, function(data){ 
        if(data.result == 'success'){ 
            alert("Artık Bildirim Almayacaksınız");
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
