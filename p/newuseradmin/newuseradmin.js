var dialog = '';
$(function(){

});



function usergridready(){
    $('.rowedit').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'edit', { ID: ID }, function(data){ 

            
            var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="dialogform">'
                + '<div class="column">'
                + '<p><label>Email</label>'
                + '<input type="text" name="email" value="' + data.user.email + '" />'
                + '</p>'
                + '</div>'
                + '</p>'
                + '<p><label>Roles</label><br />'
                + data.roles + ''
                + '</p>'
                + '<input type="hidden" name="ID" id="ID" value="' + data.ID + '" />'
                + '</form>';
                
                
            var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                     width: 450,
                     height: 400,
                     title: "User", 
                     modal: true,
                     resizable: true,
                     close: function() { $( this ).remove(); },
                     buttons: {
                        "Save": function() {
                            var role = 0;
                            $('#dialog form input[name=role]').each(function(e) {
                                
                                if($(this).attr('checked')) role = role + ($(this).val() * 1);
                                
                            });

                            $.post(ajaxurl + "save", {  
                                                        ID: ID, 
                                                        email: $("#dialog input[name=email]").val(),
                                                        role: role
                                                        }, function(data){ eval(data); });
                                                        
                            $( this ).dialog( "close" );
                            $( this ).remove();
                            $("#grid1").flexReload();
                        },
                        "Cancel": function() {
                            $( this ).dialog( "close" );
                            $( this ).remove();
                        }
                     }
                });

        }, 'json');
        return false;
    });
    
    $('.rowchangepass').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'edit', { ID: ID }, function(data){ 

            
            var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="dialogform">'
                + '<p><label>Password</label>'
                + '<input type="password" name="password" value="" />'
                + '</p>'
                + '<p><label>Password Again</label>'
                + '<input type="password" name="password2" value="" />'
                + '</p>'

                + '<input type="hidden" name="ID" id="ID" value="' + data.ID + '" />'
                + '</form>';
                
                
            var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                     width: 450,
                     height: 200,
                     title: "User", 
                     modal: true,
                     resizable: true,
                     close: function() { $( this ).remove(); },
                     buttons: {
                        "Save": function() {


                            $.post(ajaxurl + "changepass", {  
                                                        ID: ID, 
                                                        password: $("#dialog input[name=password]").val(),
                                                        password2: $("#dialog input[name=password2]").val()
                                                        }, function(data){ eval(data); });
                                                        
                            $( this ).dialog( "close" );
                            $( this ).remove();
                            $("#grid1").flexReload();
                        },
                        "Cancel": function() {
                            $( this ).dialog( "close" );
                            $( this ).remove();
                        }
                     }
                });

        }, 'json');
        return false;
    });
        
    $('.rowtoggle').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'toggle', { ID: ID }, function(data){ $('#grid1').flexReload(); });
        return false;
    });    
    $('.notVekil').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'notVekil', { ID: ID }, function(data){ 
        	$('#grid1').flexReload(); 
        	//console.log(data);
        });
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

