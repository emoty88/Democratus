	di="yuklenmedi";
	wall="yuklenmedi";
	home="yuklenmedi";
	
	$.mobile.loadingMessage = "Yükleniyor..";
	function gotoWeb()
	{
		window.location = "/home";
	}
	function dilike(ID, like){
		$.post("/ajax/like/di", {ID: ID, like: like}, function(data){
		if(data.result == 'success' && data.likeinfo.result=='success'){
			//$('#dilikeinfo' + ID ).html(data.likeinfo.html);
			//$(".pageLoadEventCls").page('refresh');
			window.document.location.reload();
		}
		},'json');
	}
	function usermenuShow()
	{
		$('.userMenuMenuCls').last().toggle();
	}
	
	function login()
	{
		var email = $('#mail').val();
		var pass = $('#pass').val();
		//alert(email); 
		if( !(email.length >1) || !(pass.length >1) )  {alert("pass"); return false;}    
		$.post( '/ajax/login/', { email: email, pass: pass }, function(data){
			
			if(data.status=='success'){
				//alert('success');
				window.location = "/mobile/home";
			} else {
				alert(data.message);         
			}
		}, 'json');
		return false;
	}
	function logout()
	{
		showLoading("Çıkış Yapılıyor..");
		$.ajax({
			type: 'POST',
			url: "/user/logoutNoRedi",
            success: function () {
                location.href="/mobile/welcome";
            },
            error: function () {
                alert("Bir Sorun yaşandı lütfen tekrar deneyiniz");
                location.href=location.href;
            }
		});
	}
	function showLoading(text)
	{
		if(text==undefined)
		text="Yükleniyor...";
		$.mobile.loadingMessage = text;
		$.mobile.showPageLoadingMsg();
	}
	function hideLoading()
	{
		$.mobile.hidePageLoadingMsg ()
	}
	function changeProposalTimer (iter){
		changeProposal(iter);
		if(iter==7)
		{
			nextIter=1;
		}
		else
		{
			nextIter=iter+1;
		}
		var t=setTimeout("changeProposalTimer("+nextIter+")",10000);
	}
	function changeProposal(id)
	{
		$(document).scrollTop(0);
		$(".proposalContent").fadeOut(500);
		var t=setTimeout("$(\"#pro-"+id+"\").fadeIn(500);",500);
		
		$("#proNavContent a span.ui-btn-active").removeClass("ui-btn-active");
		$("#proNav-"+id+" span").addClass("ui-btn-active");
	}
	function comment2Di(diID)
	{
		showLoading("Yükleniyor.. deneme");
		var comment = $('#dicommenttext').val();
		if(comment.length<1){return false;}
		$.ajax({
			type: 'POST',
			url: "/ajax/dicomment",
			data: { ID: diID, comment: comment },
			dataType: "json",
            success: function (xml) {
        		hideLoading();
                location.href=location.href;
            },
            error: function () {
                alert("Bir Sorun yaşandı lütfen tekrar deneyiniz");
                location.href=location.href;
            }
		});

	}
	function initDi()
	{
		$('#dicommenttext').keyup(function(){
				$('#dicommenttext').parent().find('.character .number').html(200 - $(this).val().length);
		}); 
	}
	function initHome()
	{
		$(".parliamentoption").live("change",function(){
			$.post('/ajax/agendavote/', { vote : $(this).attr("id") }, function(data){
				if(data && data.result=='success'){
				alert('Oyunuz kaydedildi. Teşekkür ederiz.');
				} 
			});
		});
		changeProposal(1);
	}
	function initWall()
	{
		var wall="yuklendi";
		$("#wallmore").live("click",function (){
		showLoading("Yükleniyor..");
		var walltype = $("#walltype").val();
		var wallstart = $("#wallstart").val();
		var profileID=0;
		if(walltype=="profile")
		{
			profileID=$("#walltype").attr("rel");
			walltype="all";
		}
		$.post("/ajax/wallmoreMobil", { profileID: profileID, start: wallstart, walltype:walltype }, function(data){
			if(data && data.count > 0){
			$('#wallcontainer').append(data.html);
			$("#wallstart").val(data.start);
			$("a").addClass("ui-link");
			hideLoading();
			//alert($('#wall' + data.start).html());
			//wallready(wallstart);
			} else {
			$('#wallcontainer').append(data.html);
			$('#wallmore').hide();
			$("a").addClass("ui-link");
			hideLoading();
			}
			},'json'); 

		});
	}
    function sharedi(){
        var di = $('#shareditext').val();
        var data="di="+di;
        if(di.length<1) return;
        $.post("/ajax/share",data, function(data){
            if(data && data.result=='success'){
                //alert('success');
                window.document.location.reload();
            } else {
                alert('error');
            }
            
        },'json');
        
    }
$('.pageLoadEventCls').live('pageshow', function (event, ui) {
	initHome();
	initDi();
	initWall();
	
});
