var dialog = '';
$(function(){

});



function proposalgridready(){
    
    $('.rowedit').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'edit', { ID: ID }, function(data){ 
            if(data.result == 'success') {
                dialog = new Boxy(data.html, {title: "Edit Proposal", closeable: true, modal: true, unloadOnHide: true});
                $('#save').click(function(){
                    $.post(ajaxurl + 'save',  $('#editform').serialize(), function(data){
                            var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                            setTimeout(function(){ a.hide().unload(); }, 2000 );
                    },'json');
                    
                    dialog.hide().unload();
                    $("#grid1").flexReload();
                });
            }  
        }, 'json');
        return false;
    });
    
    
    
    
    $('.rowtoggle').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'toggle', { ID: ID }, function(data){ $('#grid1').flexReload(); });
        return false;
    });
    
    
    $('.blockprofile').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'blockprofile', { ID: ID }, function(data){ $('#grid1').flexReload(); });
        return false;
    });
    
    $('.toggledi').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'toggledi', { ID: ID }, function(data){ $('#grid1').flexReload(); });
        return false;
    });    
    
    $('.toggledeputy').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'toggledeputy', { ID: ID }, function(data){ $('#grid1').flexReload(); });
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

