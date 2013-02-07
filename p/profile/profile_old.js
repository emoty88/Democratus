$().ready(function() { 
	wallready();
	$("#get_moreFollowing").click(function(){
		
		var start = 20*loadFollowing;
		var limit = 20;
		var data = {start:start, limit:limit, profileID:profileID}
		$.post("/ajax/get_followingMore",data, function(response){
            if(response && response.result=='success'){
				$("#followingContent").append(response.html);
                loadFollowing++;
            } else {
                
            }
            
        },'json');
	});
	$("#get_moreFollower").click(function(){
		
		var start = 20*loadFollower;
		var limit = 20;
		var data = {start:start, limit:limit, profileID:profileID}
		$.post("/ajax/get_followerMore",data, function(response){
            if(response && response.result=='success'){
				$("#followerContent").append(response.html);
                loadFollower++;
            } else {
                
            }
            
        },'json');
	});
});
