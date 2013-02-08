  $().ready(function() { 
      $("#time").countdown({
        date: DATEINPHP,
        onChange: function( event, timer ){
            
        },
        onComplete: function( event ){
            $(this).html("Oylama Sona Erdi!");
        },
        leadingZero: true
    });  
    $("#vekilSecSerch").live("keyup",function(e){
    	if($(this).val().length>2)
      	{
            $.post("/archive/search", {word: $(this).val()}, function(data){ 
            	
                $("#takipcilerContent").html(data.html);
             
            },'json');
      	}
    });
    $(".pageChange").live("click",function(){
    	var sayfa;
    	if($(this).attr("rel")=="pre")
    		sayfa=parseInt($("#currentPage").val())-1;
    	else if($(this).attr("rel")=="next")
    		sayfa=parseInt($("#currentPage").val())+1;
    	else if($(this).attr("rel")==0)
    		return 0;
    	else
    		sayfa =$(this).attr("rel");
        $.post("/archive/page", {page: sayfa}, function(data){ 
            $("#takipcilerContent").html(data.html);
            $("#currentPage").val(data.page);
            $("#paging").html(data.navNumHtml);
        },'json');
    });
});
  
function statisticLineChange(ID)
{
	if(activeStatistic==ID)
	{
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
      
              
      
