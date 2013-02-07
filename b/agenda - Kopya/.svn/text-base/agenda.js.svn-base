

$(function(){
   agendablockcontainerready()
   agendablockready(); 
});

function agendablockcontainerready(){
    $('#agendatabs li').click(function(){
        $('#agendatabs li').removeClass("active");
        $(this).addClass("active");
        $.post( '/agenda/ajax/agenda/', { what: $(this).attr('rel') }, function(data){ 

            var result = '';

            result += '  <div id="agendaimage"> <img src="' + data.image + '" width="500" height="120" alt="" /> </div>';
            result += '  <div id="agendainfo">23 Mart 2010, çarşamba günü oylama açıldı.</div>';
            result += '  <div id="agendatitle">'+data.title+'</div>';
            result += '  <div id="agendago"><a href="'+data.agendagolink+'">'+data.agendagotitle+'</a></div>';
            result += '  <div id="agendalastcomment">'+data.agendalastcomment+'</div>';
            result += '  <div id="agendalastcomments">'+data.agendalastcomments+'</div>';
            result += '  <div id="agendatimeleft">'+data.agendatimeleft+'</div>';
            
            $('#agenda').html(result);
            activeagenda = data.ID;
            agendablockready(data.ID);
        }, 'json');        
    });
}


function agendablockready(agendaID){
    
    $('.votebutton').click(function(){
        $(this).attr('disabled', 'disabled');
        var agendaID = $(this).attr('rel');
       // var optionID = $('input[name=ao'+agendaID+']:checked').val();
        
    });    
}