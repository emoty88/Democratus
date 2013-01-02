$().ready(function() {
    $('.toggle').click(function(){ $(this).next().toggle(); });
});

function togglenc(ID){
    $.post(ajaxurl + 'togglenc', { ID: ID }, function(data){ 
        $("#ncgrid").flexReload();    
    });
}

function togglefilter(ID){
    $.post(ajaxurl + 'togglefilter', { ID: ID }, function(data){ 
        $("#filtersgrid").flexReload();    
    });
}

function togglelinker(ID){
    $.post(ajaxurl + 'togglelinker', { ID: ID }, function(data){ 
        $("#linkersgrid").flexReload();    
    });
}



function addlinker (com,grid){
     var ncID = $("input#ncID").val();
     $.post(ajaxurl + 'addlinker', { ncID: ncID }, function(data){ 
        if(data.result == 'success') {
            dialog = new Boxy(data.html, {title: "Add Linker", closeable: true, modal: true, fixed: false, unloadOnHide: true});
            $('#savelinker').click(function(){
                $.post(ajaxurl + 'savelinker',  $('#addlinkerform').serialize(), function(data){
                    if(data.result == 'success') {
                        
                        var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                        setTimeout(function(){ a.hide().unload(); }, 2000 );
                        
                        
                        
                    }
                },'json');
                
                dialog.hide().unload();
                $("#linkersgrid").flexReload();
            });
            
            
        }  
    }, 'json');
}

function addfilter (com,grid){
      var ncID = $("input#ncID").val();
     $.post(ajaxurl + 'addfilter', { ncID: ncID }, function(data){ 
        if(data.result == 'success') {
            dialog = new Boxy(data.html, {title: "Add Filter", closeable: true, modal: true, fixed: false, unloadOnHide: true});
            $('#savefilter').click(function(){
                $.post(ajaxurl + 'savefilter',  $('#addfilterform').serialize(), function(data){
                    if(data.result == 'success') {
                        
                        var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                        setTimeout(function(){ a.hide().unload(); }, 2000 );
                        
                        
                        
                    }
                },'json');
                
                dialog.hide().unload();
                $("#filtersgrid").flexReload();
            });
            
            
        }  
    }, 'json');
}
function addnc (com,grid){
    $.post(ajaxurl + 'addnc', { ID: 0 }, function(data){ 
        if(data.result == 'success') {
            dialog = new Boxy(data.html, {title: "Add News Crawler", closeable: true, modal: true, unloadOnHide: true});
            $('#savenc').click(function(){
                $.post(ajaxurl + 'savenc',  $('#addncform').serialize(), function(data){
                    if(data.result == 'success') {
                        
                        var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                        setTimeout(function(){ a.hide().unload(); }, 2000 );
                        
                        
                        
                    }
                },'json');
                
                dialog.hide().unload();
                $("#ncgrid").flexReload();
            });
            
            
        }  
    }, 'json');
}

function editnc (ID){
    $.post(ajaxurl + 'editnc', { ID: ID }, function(data){ 
        if(data.result == 'success') {
            dialog = new Boxy(data.html, {title: "Edit News Crawler", closeable: true, modal: true, unloadOnHide: true});
            $('#savenc').click(function(){
                $.post(ajaxurl + 'savenc',  $('#editncform').serialize(), function(data){
                    //if(data.result == 'success') {
                        
                        var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                        setTimeout(function(){ a.hide().unload(); }, 2000 );
                        
                        
                    //}
                },'json');
                
                dialog.hide().unload();
                $("#ncgrid").flexReload();
            });
            
            
        }  
    }, 'json');
}

function editlinker (ID){
    $.post(ajaxurl + 'editlinker', { ID: ID }, function(data){ 
        if(data.result == 'success') {
            dialog = new Boxy(data.html, {title: "Edit Linker", closeable: true, modal: true, unloadOnHide: true});
            $('#savelinker').click(function(){
                $.post(ajaxurl + 'savelinker',  $('#editlinkerform').serialize(), function(data){
                 
                        var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                        setTimeout(function(){ a.hide().unload(); }, 2000 );
                },'json');
                
                dialog.hide().unload();
                $("#linkersgrid").flexReload();
            });
            
            
        }  
    }, 'json');
}

function editfilter (ID){
    $.post(ajaxurl + 'editfilter', { ID: ID }, function(data){ 
        if(data.result == 'success') {
            dialog = new Boxy(data.html, {title: "Edit Filter", closeable: true, modal: true, unloadOnHide: true});
            $('#savefilter').click(function(){
                $.post(ajaxurl + 'savefilter',  $('#editfilterform').serialize(), function(data){                        
                        var a = new Boxy("<div class=\"info\"><p>"+data.message+"</p></div>", {modal: false,closeable: false});
                        setTimeout(function(){ a.hide().unload(); }, 2000 );
                 },'json');
                
                dialog.hide().unload();
                $("#filtersgrid").flexReload();
            });
            
            
        }  
    }, 'json');
}function movefilter (ID, move){
    $.post(ajaxurl + 'movefilter', { ID: ID, move: move }, function(data){ 
        if(data.result == 'success') {            
            $("#filtersgrid").flexReload();
        }  
    }, 'json');
}

function linkerfilter (com,grid){
    var ncID = $("input#ncID").val();
    this.params=param = [ { name : 'ncID', value : ncID } ];
    return true;
}
function filterfilter (com,grid){
    var ncID = $("input#ncID").val();
    this.params=param = [ { name : 'ncID', value : ncID } ];
    return true;
}

function ncfilter (com,grid){ return true; }