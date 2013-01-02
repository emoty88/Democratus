var activeagenda=0;

$().ready(function(){
   agendacontainerready()
   agendaready(activeagenda); 
});



function agendacontainerready(){
    $('#agendatabs li').click(function(){
        $('#agendatabs li').removeClass("active");
        $(this).addClass("active");
        $.post( '/agenda/ajax/agenda/', { what: $(this).attr('rel') }, function(data){ 

            var result = '';
            
            result += '<div id="agendaimage"> <img src="' + data.image + '" width="500" height="120" alt="" /> </div>';
            result += '<div id="agendainfo">'+data.dateinfo+'</div>';
            result += '<div id="agendatitle">'+data.title+'</div>';
            result += '<div id="agendatimeinfo">'+data.agendatimeleft+'</div>';
            result += '<div id="agendamoreinfobtn" rel="'+data.ID+'">'+data.moreinfo+'</div>';
            
            
            
            result += '<div id="agendaoptions">';
            
            $.each(data.options, function(ID, option) {
                result += '<div class="agendaoption">';
                result += '<div class="agendaoptionleft">';
                result += '<input type="radio" name="ao'+data.ID+'" value="'+ID+'" id="ao'+ID+'" />';
                result += '</div>';
                result += '<div class="agendaoptionbody">'+option.title+'</div>';
                result += '<div class="agendaoptionright">' + option.percent + '%</div>';
                result += '</div>';
            });
            
            result += '</div>';
            result += '<div id="agendafooter">';
            result += '<div id="agendavoteinfo">Şu ana dek 12.453 kişi oy kullandı, 345 kişi yorumladı</div>';
            //if(data.isvotable)
                //result += '<div id="agendavote" class="votebutton" rel="'+data.ID+'">Oyunu Kullan</div>';
                result += '<input type="button" id="agendavote" class="votebutton" rel="'+data.ID+'" value="Oyunu Kullan" />';
            //else 
              //  result += '<div id="agendavote" class="votebutton" rel="'+data.ID+'" disabled="disabled">Oyunu Kullan</div>';
            result += '<input type="hidden" name="activeagenda" id="activeagenda" value="'+data.ID+'" />';
            result += '</div>';
            
            result += '<div id="agendacomments">&nbsp;</div>';

            $('#agenda').html(result);
            activeagenda = data.ID;
            agendaready(data.ID);
        }, 'json');        
    });
}


function agendaready(agendaID){
    
    $('.votebutton').click(function(){  
        //alert( $(this).attr('disabled') );
        
        //alert( $(this).attr('disabled') );
        var agendaID = $(this).attr('rel');
        var optionID = $('input[name=ao'+agendaID+']:checked').val();

        if(agendaID>0 && optionID>0){
            $(this).attr('disabled', 'disabled');
            $.post('/agenda/ajax/vote/', { agendaID:agendaID, optionID:optionID }, function(data){ 
                
                //yorumlama ekranını göster
                var commentit ='';
                
                commentit += '<div id="commentitcontainer">';
                //commentit += '<textarea id="comment"></textarea>';
                //commentit += '<input type="checkbox" id="showinprofile" checked="checkd">Show in profile';
                //commentit += '<input type="button" id="commentitbutton" value="Comment">';
                //commentit += '</div>';
                
                commentit += '<div class="commentbox">';
                commentit += '<div class="commentimg"><img height="60" align="middle" width="60" alt="" src="'+ data.image +'"></div>';
                commentit += '<div class="comment">';
                commentit += '<div class="commenthead"><img height="6" width="407" alt="" src="/t/default/images/sharecomment-head.png"></div>';
                commentit += '<div class="commentbody"><textarea id="agendacomment" name="agendacomment"></textarea><br /><input type="checkbox" id="showinprofile" checked="checkd">Show in profile</div>';
                commentit += '<div class="commentlikebox">';
                commentit += '<input type="button" rel="95" id="commentitbutton" class="sharecommentbutton" value="Yorumla">';
                commentit += '</div>';
                commentit += '<div style="clear:both" class="commentfoot"><img height="6" width="407" alt="" src="/t/default/images/sharecomment-foot.png"></div>';
                commentit += '</div>';
                
                commentit += '</div><br clear="all" />';

                
                $('#agendacomments').prepend(commentit);
                
                $('#agendacomment').focus();
                
                
                $('#commentitbutton').click(function(){
                    var comment = $('#agendacomment').val();
                    var showinprofile = $('#showinprofile').attr('checked')?1:0;
                    $.post('/agenda/ajax/commentit/', { agendaID:agendaID, comment:comment, showinprofile: showinprofile, optionID:optionID }, function(data){ 
                            
                            $('#commentitcontainer').remove();
                        
                            if(data.result == 'success'){
                                //yorumlama kısmın sil
                                
                            
                                //son yorumu ekrana göster
                                comments = '<div class="commentbox">';
                                comments += '<div class="commentimg"><img height="60" align="middle" width="60" alt="" src="' + data.image + '"></div>';
                                comments += '<div class="comment">';
                                comments += '<div class="commenthead"><img height="6" width="407" alt="" src="/t/default/images/sharecomment-head.png"></div>';
                                comments += '<div class="commentbody">' + data.comment + '</div>';
                                comments += '<div class="commentlikebox">';
                                comments += '<div class="commentlikeboxbutton">Takdir Et(12)</div>';
                                comments += '<div class="commentlikeboxbutton">Saygı Duy(33)</div>';
                                comments += '</div>';
                                comments += '<div style="clear:both" class="commentfoot"><img height="6" width="407" alt="" src="/t/default/images/sharecomment-foot.png"></div>';
                                comments += '</div>';
                                comments += '<div class="commentinfo"><strong>' + data.name + '</strong> '+ data.info +'</div>';
                                comments += '</div>';
                                
                                $('#agendacomments').prepend(comments);
                            } else {
                                alert(data.message);
                            }
                        
                        
                    }, 'json');
                    
                });
                
            }, 'json');
        }
    });    
    //alert(activeagenda);
    if(activeagenda>0){
        $.post('/agenda/ajax/comments/', { agendaID:agendaID }, function(data){ 
            var comments = '';
            $.each(data.comments, function(ID, row) {
                //comments += '<div class="agendacomment" style="border: 1px solid #F00; margin:3px;">';
                //comments += '<span>' + row.comment + '</span>';
                //comments += '<span class="acregardbtn" rel="' + ID + '">regard</span>';
                //comments += '<span class="acappreciatebtn" rel="' + ID + '">appreciate</span>';
                //comments += '</div>';
                
                comments += '<div class="commentbox">';
                comments += '<div class="commentimg"><img height="60" align="middle" width="60" alt="" src="' + row.image + '"></div>';
                comments += '<div class="comment">';
                comments += '<div class="commenthead"><img height="6" width="407" alt="" src="/t/default/images/sharecomment-head.png"></div>';
                comments += '<div class="commentbody">' + row.comment + '</div>';
                comments += '<div class="commentlikebox">';
                comments += '<div class="commentlikeboxbutton">Takdir Et(12)</div>';
                comments += '<div class="commentlikeboxbutton">Saygı Duy(33)</div>';
                comments += '</div>';
                comments += '<div style="clear:both" class="commentfoot"><img height="6" width="407" alt="" src="/t/default/images/sharecomment-foot.png"></div>';
                comments += '</div>';
                comments += '<div class="commentinfo"><strong>' + row.name + '</strong> '+ row.info +'</div>';
                comments += '</div>';
                
                
                            
            });
            $('#agendacomments').html(comments);
            
            

            $('.acregardbtn').click(function(){
                $.post("/agenda/ajax/like/", { ID: $(this).attr('rel'), like: 1 }, function(data){ alert(data); });
            });    
            
            $('.acappreciatebtn').click(function(){
                $.post("/agenda/ajax/like/", { ID: $(this).attr('rel'), like: 2 }, function(data){ alert(data); });
            });
                
            $('.acunlikebtn').click(function(){
                $.post("/agenda/ajax/like/", { ID: $(this).attr('rel'), like: 0 }, function(data){ alert(data); });
            });             
            
        }, 'json');
    }
    
    
    $('#agendamoreinfobtn').click(function(){
        
        
        $.post("/agenda/ajax/moreinfo/", { ID: $(this).attr('rel') }, function(data){ 
            
            var dialog = $("<div id=\"dialog\"></div>").html( data.moreinfo ).dialog({
                         width: 600,
                         height: 400,
                         title: "More Info", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Close": function() {
                                $( this ).dialog( "close" );
                                $( this ).remove();
                            }
                         }
                    });            
            
            
            
        }, 'json');
        
        
    });
    
}