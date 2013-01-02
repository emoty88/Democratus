var dialog;

$(function(){
   //imageuploadbutton = $("#imageuploadbutton"), interval;
});

function checknull(value){
    return value==null?'':value;
}

function agenda_edit_save(){
    var ID = $("#editdialog input#ID").val();
    var title = $("#editdialog input#title").val();
    var parentID = $("select#parentID").val();
    dialog.hide();
    dialog.unload();
    $.post(ajaxurl + 'save', { ID: ID, title: title, parentID: parentID  }, function(data){ eval(data); });
    
    $('#grid1').flexReload();
    return false;
}

function agendagetready(){
    $('.rowedit').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'edit', { ID: ID }, function(data){ 
            //eval(data); 
            
        
        
        
        var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="form">'
                + '<div class="column">'
                + '<p><label>Title</label>'
                + '<input type="text" name="title" id="title" value="' + data.title + '" /><span class="required">*</span>'
                + '</p>'
                + '<p><label>Permalink</label>'
                + '<input type="text" name="permalink" id="permalink" value="' + data.permalink + '" /><span class="required">*</span>'
                + '</p>'
                
                + '<p><label>Class</label>' + data.class + '</p>'
                + '<p><label>Region</label>' + data.regionselect + '</p>'
                + '<p><label>Country</label>' + data.countryselect + '</p>'
                + '<p><label>City</label>' + data.cityselect + '</p>'
                
                + '<p><label>Start Time</label>'
                + '<input type="text" name="starttime" id="starttime" value="' + data.starttime + '" />'
                + '</p>'
                + '<p><label>End Time</label>'
                + '<input type="text" name="endtime" id="endtime" value="' + data.endtime + '" />'
                + '</p>'
                + '<p><label>Spot</label>'
                + '<textarea name="spot" id="spot">' + data.spot + '</textarea>'
                + '</p>'
                + '</div>'
                + '<div class="column">'
                + '<p>'
                + '<textarea name="content" id="agenda_content">' + data.content + '</textarea>'
                + '</p>'
                + '<input type="hidden" name="ID" id="ID" value="' + data.ID + '" />'
                + '</div>'
                + '</form>';        
        
        var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                         width: 850,
                         height: 400,
                         title: "Agenda", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Save": function() {
                                $.post(ajaxurl + "save", {  
                                                            ID: $("#editdialog input[name=ID]").val(), 
                                                            title: $("#editdialog input[name=title]").val(),
                                                            permalink: $("#editdialog input[name=permalink]").val(),
                                                            class: $("#editdialog select[name=class]").val(),
                                                            region: $("#editdialog select[name=region]").val(),
                                                            country: $("#editdialog select[name=country]").val(),
                                                            city: $("#editdialog select[name=city]").val(),
                                                            starttime: $("#editdialog input[name=starttime]").val(),
                                                            endtime: $("#editdialog input[name=endtime]").val(),
                                                            spot: $("#editdialog textarea[name=spot]").val(),
                                                            content: tinyMCE.editors["agenda_content"].getContent()
                                                            
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
                    
                    $("#starttime").mask("9999-99-99 99:99:99");
                    $("#endtime").mask("9999-99-99 99:99:99");
                    tinymceinit("agenda_content");
        
                    //init country selection
                    $("#editdialog select[name=country]").change(function() {
                        $.post(ajaxurl + "getcities", { countryID: $(this).val() }, function(data){ 
                            
                            var city = $('#editdialog select[name=city]');
                            
                            city.html('');
                            
                            for (row in data){
                                city.append('<option value="'+data[row].ID+'">'+data[row].city+'</option>');
                            }
                            
                        },'json');
                    });        
        
        
        
        
        }, "json");
        return false;
    });
    
    
    $("#editdialog select[name=country]").change(function() {
        $.post(ajaxurl + "getcities", { countryID: $(this).val() }, function(data){ 
            
            var city = $('#editdialog select[name=city]');
            
            city.html('');
            
            for (row in data){
                city.append('<option value="'+data[row].ID+'">'+data[row].city+'</option>');
            }
            
        },'json');
    });    
    
    
    $('.rowtrans').click(function() {
        var ID = $(this).attr('rel');
        var language = $(this).attr('language');
        //alert(language);
        $.post(ajaxurl + 'translate', { ID: ID, language: language }, function(data){ 

        
        var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="dialogform">'
                + '<div class="column">'
                + '<p><label>Original Title</label>'
                + '<input type="text" value="' + data.orj.title + '" disabled="disabled" />'
                + '<label>Trans Title</label>'
                + '<input type="text" name="transtitle" id="title" value="' + data.trans.title + '" />'
                + '</p>'
                + '<p><label>Original Spot</label>'
                + '<textarea name="spot" disabled="disabled">' + data.orj.spot + '</textarea>'
                + '<label>Translation Spot</label>'
                + '<textarea name="transspot">' + data.trans.spot + '</textarea>'
                + '</p>'
                + '<p>Original Content'
                + '<textarea name="content" id="agenda_content" disabled="disabled">' + data.orj.content + '</textarea>'
                + 'Translation Content'
                + '<textarea name="transcontent" id="agenda_transcontent">' + data.trans.content + '</textarea>'
                + '</p>'
                + '</div>'
                + '<div class="column">';
                
                //options eklenecek foreach fonksiyonunu bul
                
                $.each(data.opt, function(key, value) {
                     
                    form += '<p class="option">' + value.title + '<br />';
                    form += '<input type="text" name="option" value="'+checknull(value.transtitle)+'" rel="'+key+'" style="width:360px" />';
                    form += '</p>';
                    
                });
                
                
                
                
                
          form  +='</div>'
                + '<input type="hidden" name="ID" id="ID" value="' + data.ID + '" />'
                + '</form>';        
        
        var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                         width: 850,
                         height: 400,
                         title: "Agenda Translation For: " + language , 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Save": function() {
                                
                                $.post(ajaxurl + "transsave", {  
                                                            agendaID: ID, 
                                                            language: language, 
                                                            transtitle: $("#dialog input[name=transtitle]").val(),
                                                            transspot: $("#dialog textarea[name=transspot]").val(),
                                                            transcontent: tinyMCE.editors["agenda_transcontent"].getContent()
                                                            }, function(data){ eval(data); });
                                                            
                                $('#dialog p.option input[name=option]').each(function(e) {
                                    
                                    $.post(ajaxurl + "optiontranssave", {                              
                                                    agendaID: ID, 
                                                    language: language, 
                                                    optionID: $(this).attr('rel'),
                                                    transtitle: $(this).val()
                                                    }, function(data){});
                            
                                });                                                            
                                                            
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
                    
                    $("#starttime").mask("9999-99-99 99:99:99");
                    $("#endtime").mask("9999-99-99 99:99:99");
                    tinymceinitreadonly("agenda_content");
                    tinymceinit("agenda_transcontent");
                    
        
        
        
        }, "json");
        return false;
    });
    
    
    $('.rowtoggle').click(function() {
        var ID = $(this).attr('rel');
        $.post(ajaxurl + 'toggle', { ID: ID }, function(data){ $('#grid1').flexReload(); });
        return false;
    });
    
    $('.rowimageedit').click(function() {
        var ID = $(this).attr('rel');
        
        var $dialog = $("<div id=\"dialog\"></div>").html("<div id=\"imagescontainer\" rel=\""+ID+"\"></div><div id=\"imageuploadbutton\">Upload</div>").dialog({
                 width: 420,
                 height: 400,
                 title: "Image Manager", 
                 modal: true,
                 resizable: true,
                 close: function() { $( this ).remove(); },
                 buttons: {                     
                    "Save": function() {
                        $.post(ajaxurl + "imagesave", {                              
                                                    ID: ID, 
                                                    imageID: $("#imagescontainer input[name=selected]:checked").val()
                                                    }, function(data){ 
                                                        //eval(data); 
                                                    });
                                                    
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
            
            
            
            imagesload(ID);
            var uploader = new qq.FileUploader({
                element: document.getElementById('imageuploadbutton'),
                // url of the server-side upload script, should be on the same domain
                action: ajaxurl + 'imageupload',
                // additional data to send, name-value pairs
                params: {agendaID: ID},
                // validation    
                // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],        
                // each file size limit in bytes
                // this option isn't supported in all browsers
                sizeLimit: 0, // max size   
                minSizeLimit: 0, // min size

                // set to true to output server response to console
                debug: false,
                // events         
                // you can return false to abort submit
                onSubmit: function(id, fileName){},
                onProgress: function(id, fileName, loaded, total){},
                onComplete: function(id, fileName, responseJSON){imagesload (ID)},
                onCancel: function(id, fileName){},

                messages: {
                    // error messages, see qq.FileUploaderBasic for content            
                },
                showMessage: function(message){ 
                    //alert(message); 
                }
            });
            return false;
    });
    
  
    $('.rowoptionedit').click(function() {
        var ID = $(this).attr('rel');
        
        var $dialog = $("<div id=\"dialog\"></div>").html("<div id=\"options\" rel=\""+ID+"\"></div><div id=\"optionsnew\" rel=\""+ID+"\"></div><div id=\"addoption\">Add</div>").dialog({
                 width: 420,
                 height: 400,
                 title: "Option Manager", 
                 modal: true,
                 resizable: true,
                 close: function() { $( this ).remove(); },
                 buttons: {                     
                    "Save": function() {
                        
                        $('#dialog p.option input[name=option]').each(function(e) {
                            
                            $.post(ajaxurl + "optionsave", {                              
                                                    agendaID: ID, 
                                                    optionID: $(this).attr('rel'),
                                                    title: $(this).val()
                                                    }, function(data){ 
                                                        //eval(data); 
                                                    });
                            
                        });
                                                    
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
            optionsload(ID);
            
            
            $('#addoption').click(function() {
                $('<p class="option"><input type="text" name="option" value="" rel="0" style="width:360px" /><input type="button" value="X" class="removebutton removebuttonnew" style="float:right;" rel="0" /></p>').appendTo('#optionsnew');
                $('#options .removebuttonnew').click(function(){$(this).parent().remove();})
                
                
            });
            
            
            return false;
    });
    
  
    $('tbody tr td').each(function() {
        $(this).dblclick(function(e) {
            var row = $(this).parents('tr');
            var ID = row.children('td').eq(0).children('div').get(0).innerHTML;
            
            $.post(ajaxurl + 'edit', { ID: ID }, function(data){ eval(data); });
        });
    });

  
}

function agendaaddclick (com,grid){
    /*
    $.post(ajaxurl + 'edit', { ID: 0 }, function(data){ 
        eval(data); 
    
    });
    */
    
    
    
    
    $.post(ajaxurl + 'edit', { ID: 0 }, function(data){ 
            //eval(data); 
            
        
        
        
        var form = '<form name="editdialog" id="editdialog" onsubmit="return false;" class="form">'
                + '<div class="column">'
                + '<p><label>Title</label>'
                + '<input type="text" name="title" id="title" value="' + data.title + '" /><span class="required">*</span>'
                + '</p>'
                + '<p><label>Permalink</label>'
                + '<input type="text" name="permalink" id="permalink" value="' + data.permalink + '" /><span class="required">*</span>'
                + '</p>'
                
                + '<p><label>Class</label>' + data.class + '</p>'
                + '<p><label>Region</label>' + data.regionselect + '</p>'
                + '<p><label>Country</label>' + data.countryselect + '</p>'
                + '<p><label>City</label>' + data.cityselect + '</p>'
                
                + '<p><label>Start Time</label>'
                + '<input type="text" name="starttime" id="starttime" value="' + data.starttime + '" />'
                + '</p>'
                + '<p><label>End Time</label>'
                + '<input type="text" name="endtime" id="endtime" value="' + data.endtime + '" />'
                + '</p>'
                + '<p><label>Spot</label>'
                + '<textarea name="spot" id="spot">' + data.spot + '</textarea>'
                + '</p>'
                + '</div>'
                + '<div class="column">'
                + '<p>'
                + '<textarea name="content" id="agenda_content">' + data.content + '</textarea>'
                + '</p>'
                + '<input type="hidden" name="ID" id="ID" value="' + data.ID + '" />'
                + '</div>'
                + '</form>';        
        
        var dialog = $("<div id=\"dialog\"></div>").html( form ).dialog({
                         width: 850,
                         height: 400,
                         title: "Agenda", 
                         modal: true,
                         resizable: true,
                         close: function() { $( this ).remove(); },
                         buttons: {
                            "Save": function() {
                                $.post(ajaxurl + "save", {  
                                                            ID: $("#editdialog input[name=ID]").val(), 
                                                            title: $("#editdialog input[name=title]").val(),
                                                            permalink: $("#editdialog input[name=permalink]").val(),
                                                            class: $("#editdialog select[name=class]").val(),
                                                            region: $("#editdialog select[name=region]").val(),
                                                            country: $("#editdialog select[name=country]").val(),
                                                            city: $("#editdialog select[name=city]").val(),
                                                            starttime: $("#editdialog input[name=starttime]").val(),
                                                            endtime: $("#editdialog input[name=endtime]").val(),
                                                            spot: $("#editdialog textarea[name=spot]").val(),
                                                            content: tinyMCE.editors["agenda_content"].getContent()
                                                            
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
                    
                    $("#starttime").mask("9999-99-99 99:99:99");
                    $("#endtime").mask("9999-99-99 99:99:99");
                    tinymceinit("agenda_content");
        
                    //init country selection
                    $("#editdialog select[name=country]").change(function() {
                        $.post(ajaxurl + "getcities", { countryID: $(this).val() }, function(data){ 
                            
                            var city = $('#editdialog select[name=city]');
                            
                            city.html('');
                            
                            for (row in data){
                                city.append('<option value="'+data[row].ID+'">'+data[row].city+'</option>');
                            }
                            
                        },'json');
                    });        
        
        
        
        
        }, "json");
        return false;
    }


function agendafilter (com,grid){ return;
    var fiterstatus = $("input#fiterstatus").val();
    this.params=param = [
                     { name : 'fiterstatus', value : fiterstatus }
                    ];
    return true;
}

function imagesload (ID){
    $.post(ajaxurl + 'images', { ID: ID }, function(data){ 
        $("#imagescontainer").html(data);
        
        $("#imagescontainer .removebutton").click(function(){
        
                var imageID = $(this).attr('rel');
                Boxy.ask("Silinsin mi", ["Evet", "Hayır"], function(val) {
                    if(val=="Evet"){
                        $.post(ajaxurl + 'imagedelete', { ID: imageID }, function(data){ 
                            //eval(data); 
                            imagesload(ID); 
                        });
                    }
                    
                }, {title: "Silinsin mi?"});
               
               return false;
    
            });
    });
    
   
}

function optionsload (ID){
    $.post(ajaxurl + 'options', { ID: ID }, function(data){ 
        $("#options").html(data);
        $("#options .removebutton").click(function(){
            var optionID = $(this).attr('rel');
            Boxy.ask("Silinsin mi", ["Evet", "Hayır"], function(val) {
                if(val=="Evet"){
                    $.post(ajaxurl + 'optiondelete', { ID: optionID, agendaID: ID }, function(data){ 
                        //eval(data); 
                        optionsload(ID); 
                    });
                }
                
            }, {title: "Silinsin mi?"});
           return false;
        });
        
    });
}