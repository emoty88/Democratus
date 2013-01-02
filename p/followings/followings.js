$().ready(function() {
    followingmoreready();     
});

function followingmoreready( container ){
    
    if (container == null) container = '.result';

    $('#followingmore').click(function(){
        var start = $(this).attr('rel'); 
        
        $(this).remove();
        
        $.post("/following/ajax/more", {
                start: start,
                keyword:keyword
            }, function(data){
                
                if(data && data.result=='success'){
                    $('#following_result').append(data.html);
                    $.each(data.ids, function(key, value) { 
                        searchmoreready( '#profile' + value + ' ' );
                    });

                } else {
                    alert(data.message);
                }
        
        }, 'json');

    });
    
    $(container + ' .follow').click(function(){ 
        var btn = $(this);
        var ID = btn.attr('rel');
        $.post("/ajax/follow", {
                ID: $(this).attr('rel'),
                }, function(data){
                    
                    if(data.status=='success'){
                        //btn.remove();
                        $('#follow' + ID).toggle();
                        $('#unfollow' + ID).toggle();
                        
                    } else {
                        alert(data.message);
                    }
                    
                    
                    
                    
                }, 'json');
        
    });
    
    $(container + ' .unfollow').click(function(){
        var btn = $(this);
        var ID = btn.attr('rel');
        $.post("/ajax/unfollow", {
                ID: $(this).attr('rel'),
                }, function(data){
                    
                    if(data.status=='success'){
                        //btn.remove();
                        //alert(btn.attr('rel'));
                        $('#follow' + ID).toggle();
                        $('#unfollow' + ID).toggle();
                        
                    } else {
                        alert(data.message);
                    }
                    
                    
                    
                    
                }, 'json');
        
    }); 
    

}