$(function () {
    $('input:radio').screwDefaultButtons({
        checked:     "url(/t/v2/static/image/form/checkbox_focus.png)",
        unchecked:    "url(/t/v2/static/image/form/checkbox.png)",
        width:        18,
        height:        17,
        onchange: "agendavote($(this))"
    });
});

function agendavote(obj){
    //alert(tis);
    //console.log(obj);
    //console.log( obj.find('input').attr('ID') );
    //alert (obj.find('input').attr('ID'));
    
    $.post('/ajax/agendavote/', { vote : obj.find('input').attr('ID') }, function(data){
        if(data && data.result=='success'){
        	$("#vote-save-"+data.agendaID).fadeIn();
        	var t = setTimeout('$("#vote-save-'+data.agendaID+'").fadeOut();',3000);
        	//alert('Oyunuz kaydedildi. Teşekkür ederiz.');
        	
        }
        
        //window.location.reload();    
    }, 'json');    
}