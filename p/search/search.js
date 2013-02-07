var keyword;

$().ready(function() {
    searchready();  
    searchmoreready('.result');

});

function searchready( ){
        $('#searchmore').click(function(){
        var start = $(this).attr('rel'); 
        
        //$(this).remove();
        
        $.post("/search/ajax/more", {
                start: start,
                keyword:keyword
            }, function(data){
                
                if(data && data.result=='success'){
                    $('#search_result').append(data.html);
                    $.each(data.ids, function(key, value) { 
                        searchmoreready( '#profile' + value + ' ' );
                    });
                    if(data.nextstart && data.count>0)
                        $('#searchmore').attr('rel', data.nextstart );
                    else 
                        $('#searchmore').remove();
                        
                    //alert(data.nextstart);
                } else {
                    $(this).remove();
                    alert(data.message);
                    
                }
        
        }, 'json');

    });    
}
function searchmoreready( container ){
    
    if (container == null) return;// container = '.result';


    
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
function statisticLineChange(ID)
    {
    	if(activeStatistic==ID){
    		$("#statisticLine-"+ID).fadeOut();
    		activeStatistic=0;
    	}
    	else
    	{
    		$(".statisticLine-all").fadeOut();
    		$("#statisticLine-"+ID).fadeIn();
    		activeStatistic=ID;
    	}
    			
    }
