function homeDiLoaderFollow()
{
    var pid = 0;
    var walltype = "follow";
    var wallfirst=$('#wallfirstfollow').val();
    $.post("/ajax/wallmoreUp", { profileID: pid, first:wallfirst, walltype:walltype }, function(data){ 
        if(data && data.count > 0){
        	if(!$('#getNewDiesFollow').is(":visible"))
        	$('#getNewDiesFollow').show();
        	$('#getNewDiesFollow').html(""+data.count+" Tane Yeni Ses Yüklemek İçin Tıklayınız");
        }
        setTimeout("homeDiLoaderFollow()",3000);
    },'json');
    
}
function homeDiLoaderDeputy()
{
    var pid = 0;
    var walltype = "deputy";
    var wallfirst=$('#wallfirstdeputy').val();
    $.post("/ajax/wallmoreUp", { profileID: pid, first:wallfirst, walltype:walltype }, function(data){ 
        if(data && data.count > 0){
        	if(!$('#getNewDiesDeputy').is(":visible"))
        	$('#getNewDiesDeputy').show();
        	$('#getNewDiesDeputy').html(""+data.count+" Tane Yeni Ses Yüklemek İçin Tıklayınız");
        }
        setTimeout("homeDiLoaderDeputy()",3000);
    },'json');
    
}
function noticeCounter()
{
	$.post("/ajax/noticeCount",function(data){
		//console.log(data);
		$("#noticeCountC").html(data);
		if(data>0)
		{
			var titleArr=$("title").text().split("-");
			//console.log(titleArr);
			//console.log(titleArr[0])
			if(titleArr.length>1)
				$("title").text("("+data+") - "+titleArr[1]);
			else
				$("title").text("("+data+") - "+titleArr[0]);
			
		}
		
		setTimeout("noticeCounter()",3000);
	},"json");
}
$().ready(function() {
	noticeCounter();
});