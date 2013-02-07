var dialog = '';
$(function(){
    $('.boxy').boxy();
    $('#datepicker').datepicker({
                        inline: true
                    });
     
     $( "#dialog:ui-dialog" ).dialog( "destroy" );

 $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                "Delete all items": function() {
                    $( this ).dialog( "close" );
                },
                "Cancel": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        
        $( "#diaaa" ).dialog({ buttons: { "Ok": function() { $(this).dialog("close"); } } });


});

function locationadmin_edit_save(){
    var ID = $("#editdialog input#ID").val();
    var title = $("#editdialog input#title").val();
    var parentID = $("select#parentID").val();
    dialog.hide();
    dialog.unload();
    $.post(ajaxurl + 'save', { ID: ID, title: title, parentID: parentID  }, function(data){ eval(data); });
    
    $('#grid1').flexReload();
    return false;
}

function locationgetready(){
    $('.rowedit').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'edit', { ID: ID }, function(data){ eval(data); });
        return false;
    });

    
    $('.rowdelete').click(function() {
        var ID = $(this).attr('rel');
        Boxy.ask("Silinsin mi", ["Evet", "Hayır"], function(val) {
            if(val=="Evet"){
                $.post(ajaxurl + 'delete', { ID: ID }, function(data){ eval(data); $('#grid1').flexReload(); });
            }
            
        }, {title: "Burada birşeyler silinecek..."});
       
    return false;
  });
}

function locationaddclick (com,grid){
    $.post(ajaxurl + 'edit', { ID: 0 }, function(data){ eval(data); });
}
function locationfilter (com,grid){ return;
    var fiterstatus = $("input#fiterstatus").val();
    this.params=param = [
                     { name : 'fiterstatus', value : fiterstatus }
                    ];
    return true;
}