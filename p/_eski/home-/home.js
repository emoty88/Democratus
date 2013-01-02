var profileID = 0;
$().ready(function() {

    $('#shareitmenu .photomenu').click(function() {
        $('#sharephoto').show();
        $('#shareurl').hide();
        $('#sharestatus').hide();
    });
    
    $('#shareitmenu .urlmenu').click(function() {
        $('#sharephoto').hide();
        $('#shareurl').show();
        $('#sharestatus').hide();
    });
    
    $('#shareitmenu .notemenu').click(function() {
        $('#sharephoto').hide();
        $('#shareurl').hide();
        $('#sharestatus').show();
    });
    
    $('#shareurlbtn').click(function(){
    
        //kontrol
        
        var title = $('#shareurltitle').val();
        var url = $('#shareurlurl').val();
        
                
        $.post("/ajax/share", { profileID: profileID, type: 2, title: title, url: url }, function(data){ 
            if(data && data.status=='success'){
                alert('success');
                //window.document.location.href = window.document.location.href;
            } else {
                alert('error');
            }
        },'json');
        
    });
    
    
    $('#sharestatusbtn').click(function(){
        //kontrol
        
        var status = $('#shareittext').val();
        if(status.length<1) return;
                
        $.post("/ajax/share", { profileID: profileID, type: 1, status: status }, function(data){ 
            if(data && data.status=='success'){
                alert('success');
                window.document.location.href = window.document.location.href;
            } else {
                alert('error');
            }
        },'json');
        
    });
    
    
    
    $('.sharecommentsbox').each(function(i, e) {
       $.post("/ajax/getsharecomments", {
                        shareID: $(this).attr('rel'),
                        limit: 3
                        }, function(data){
                            $(e).html(data);
                            //sharecommentsready();
                            
                        });
                        
    });
    
    //sharecommentsready();
    
    
    $('.sharecommentbutton').click(function() {
        $(this).attr('disabled','disabled');
        var ID = $(this).attr('rel');
        $.post("/ajax/sharecomment", {
                        ID: ID,
                        comment: $("#sharecomment"+ID).val()
                        }, function(data){
                            //alert(data);
                            
                            $.post("/ajax/getsharecomments", {
                                shareID: ID,
                                limit: 3
                                }, function(data){
                                    $("#sharecommentsbox" + ID).html(data);
                                });
                            
                            
                        });
        $("#sharecomment"+ID).val('');
        $(this).removeAttr('disabled');
        return false;
    });
    
    
    $('.shareregardbutton').click(function() {
        $.post("/ajax/like/share", {ID: $(this).attr('rel'), like: 1}, function(data){ 
            //alert(data); 
        });
        return false;
    });
    
    $('.shareappreciatebutton').click(function() {
        $.post("/ajax/like/share", {ID: $(this).attr('rel'), like: 2}, function(data){ 
            //alert(data); 
        });
        return false;
    });
    
    $('.shareunlikebutton').click(function() {
        $.post("/ajax/like/share", {ID: $(this).attr('rel'), like: 0}, function(data){ 
            //alert(data); 
        });
        return false;
    });
    
    
    
});

function sharecommentlike(ID, like){
    $.post("/ajax/like/sharecomment", {ID: ID, like: like}, function(data){ 
            //alert(data); 
        });
    return false;
}