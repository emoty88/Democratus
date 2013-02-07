$().ready(function() {
	$("#wallmoreHashT").live("click",function (){
		//alert($(this).attr("rel"));
		var wallstart = $(this).attr('rel');
        //var walltype = $("#walltype").val();
        
        
        $.post("/t/ajax/more", { hasTag: hasTag, start: wallstart }, function(data){ 
            if(data && data.count > 0){
                $('#wallcontainer').append(data.html);
                wallstart = data.start;
                //alert($('#wall' + data.start).html());
                wallready(wallstart);
                dicommentready();
            } else {
                $('#wallcontainer').append(data.html);
                $('#wallmore').hide();
            }
        },'json');
        
	});
	$("#htEdit").live("click",function (){
		$("#htMotto-static").hide();
		$("#htMotto-dinamic").show();
		
		$("#htFollowBtn").hide();
		$("#htSaveBtn").show();
	});
	$(".removeAgenda").live("click",function(){
		var ID = $(this).attr("rel");
		remove_tagProposal(ID);
	});
});
function htSave()
{
	$.post("/t/ajax/changeProfile", {permaLink: paths[1],motto:$("#motto-txt").val()}, 
		function(data){
        	if(data.status=='success'){
            		$("#htMotto-static td p").text($("#motto-txt").val());
					$("#htMotto-dinamic").hide();
					$("#htMotto-static").show();	
					
					$("#htSaveBtn").hide();
					$("#htFollowBtn").show();
            } else {
            		$("#htMotto-dinamic").hide();
					$("#htMotto-static").show();	
					
					$("#htSaveBtn").hide();
					$("#htFollowBtn").show();
           	}
    	}, 'json');
    	
}
function add_tagProposal() 
{
	var proposalText=$("#newtagProposal").val();
	var permaLink= paths[1];
	
	$.post("/t/ajax/add_tagProposal", {permaLink: permaLink, proposalText:proposalText}, 
		function(response){
			console.log(response);
        	/*
        	if(response.status=='success'){
            		
            } else {
            		
           	}
           	*/
    	}, 'json');	
}
function remove_tagProposal(ID)
{
	var permaLink= paths[1]
	$.post("/t/ajax/remove_tagAgenda", {permaLink: permaLink, ID:ID}, 
		function(response){
			
        	if(response.status=='success'){
            	$("#agendaAd-"+response.agendaID).remove();
            } else {
            	alert(response.errormsg);		
           	}
           	
    	}, 'json');	
}