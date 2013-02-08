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
    window.location.reload();    
    }, 'json');    
}