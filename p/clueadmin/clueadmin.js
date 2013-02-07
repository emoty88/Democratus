

function cluegridready(){
    $('.rowedit').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'edit', { ID: ID }, function(data){ 
            if(data.result == 'success') {
                dialog = new Boxy(data.html, {title: "Edit Clue", closeable: true, modal: true, unloadOnHide: true});
                $('#save').click(function(){
                    $.post(ajaxurl + 'save',  $('#editform').serialize(), function(data){
                            var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                            setTimeout(function(){ a.hide().unload(); }, 2000 );
                    },'json');
                    
                    dialog.hide().unload();
                    $("#cluegrid").flexReload();
                });
            }  
        }, 'json');
        return false;
    });
    
    $('.rowtoggle').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'toggle', { ID: ID }, function(data){ $('#cluegrid').flexReload(); });
        return false;
    });
}

function add (com,grid){
    $.post(ajaxurl + 'add', { ID: 0 }, function(data){ 
        if(data.result == 'success') {
            dialog = new Boxy(data.html, {title: "Add Clue", closeable: true, modal: true, unloadOnHide: true});
            $('#save').click(function(){
                $.post(ajaxurl + 'save',  $('#addform').serialize(), function(data){
                    if(data.result == 'success') {
                        
                        var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                        setTimeout(function(){ a.hide().unload(); }, 2000 );
                        
                        
                        
                    }
                },'json');
                
                dialog.hide().unload();
                $("#cluegrid").flexReload();
            });
            
            
        }  
    }, 'json');
}
 