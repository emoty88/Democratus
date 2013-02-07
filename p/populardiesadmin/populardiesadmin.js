var dialog = '';
$(function(){

});



function populardiesgridready(){

    $('.rowtoggle').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'toggle', { ID: ID }, function(data){ $('#grid1').flexReload(); });
        return false;
    });
    
  
}

function useraddclick (com,grid){
    $.post(ajaxurl + 'edit', { ID: 0 }, function(data){ eval(data); });
}

function userfilter (com,grid){ return;
    var fiterstatus = $("input#fiterstatus").val();
    this.params=param = [
                     { name : 'fiterstatus', value : fiterstatus }
                    ];
    return true;
}

