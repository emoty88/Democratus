$().ready(function() {


});

/*
function row_getready(){
    $('.rowbuttonedit').click(function() {
        //$.post("/ajax/like/", { what: "appreciate", ID: $(this).attr('rel') }, function(data){ eval(data); });
        alert('edit button');
        return false;
    });
    
    $('.rowbuttondelete').click(function() {
        //$.post("/ajax/like/", { what: "appreciate", ID: $(this).attr('rel') }, function(data){ eval(data); });
        alert('delete button');
        $(this).parent().flexReload();
        $('#grid1').flexReload();
        return false;
    });
}

function buttonclick(com,grid){
    if (com=='Delete'){
        confirm('Delete ' + $('.trSelected',grid).length + ' items?')
    } else if (com=='Add'){
        alert('Add New Item');
    }
}
*/
function tinymceinit(element){
    
    opt = {
        // General options
        mode : "exact",
        elements : element,
        //plugins : "pagebreak,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        paste_auto_cleanup_on_paste : true,
        paste_remove_styles : true,
        paste_remove_spans : true,
        paste_strip_class_attributes : true,

        // Theme options
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        
        // Skin options
        skin : "default",
        skin_variant : "silver",

        width : 355,
        height : 250,
        relative_urls : false
    };
    
    tinyMCE.init(opt);
}

function tinymceinitreadonly(element){
    
    opt = {
        readonly : true,
        // General options
        mode : "exact",
        elements : element,
        //plugins : "pagebreak,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        paste_auto_cleanup_on_paste : true,
        paste_remove_styles : true,
        paste_remove_spans : true,
        paste_strip_class_attributes : true,

        // Theme options
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        
        // Skin options
        skin : "default",
        skin_variant : "silver",

        width : 355,
        height : 250,
        relative_urls : false
    };
    
    tinyMCE.init(opt);
}