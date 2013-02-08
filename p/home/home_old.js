var profileID = 0;
var json_voices="";
//var currentTab="duvar";

$().ready(function() {
	
	//$("#members-list-tmpl").tmpl(movies).appendTo("#members-list");
	
	/*/ javascript template engine
	var tmp	 = "Hello %(user)s in Turkish";
	var data = [{ user: "lego" },{ user: "lego" },{ user: "lego" }];
	//data[0] = { user: "lego" };
	//data[1] = { user: "caner" };
	//data[2] = { user: "uğur" };
	var test = dji18njs.interpolateMulti(tmp, data, true,"<br/>\n" ); 
	console.log(data);
	console.log(test);
	*/
	$(window).scroll(function(){
	  	if(wallmoreAction==0 && $(window).scrollTop() == $(document).height() - $(window).height()){
	    	switch(currentTab)
	    	{
	    		case "duvar": $("#wallmorefollow").click(); break;
	    		case "vekiller" :$("#wallmoredeputy").click(); break;
	    		case "sesgetirenler" :return false; break;
	    		case "cagrilar" :$("#wallmorecagrilar").click(); break;	
	    	}
        }
	});
	
	
		var tYuk=parseInt($(".textarea").css("height"))+50; 
		var tLeft=parseInt($(".textarea").position().left); 
		var tTop=parseInt($(".textarea").position().top); 
		var tBottom=tTop+tYuk; 
		
		
		$("#mentionDisplay").css("left",tLeft);
		$("#mentionDisplay").css("top",tBottom);
		$("#mentionDisplay").css("width","400px");
		wallready();
		var start=/@/ig;
		var word=/@(\w+)/ig;
		var name="";
		
		var urlRegex = /(\b(https?:\/\/|ftp:\/\/|file:\/\/|www.)[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;


		
		$("#shareditext").live("keypress",function(key) 
		{
			if($('#selectable').is(':visible'))
			{
				if(key.keyCode==40 || key.keyCode==38 || key.keyCode==13)
				{
					if(key.keyCode==40)
					{
						
						if($(".ui-state-hover").is(":last-child"))
						{
						
						}
						else
						{
							var secili=$(".ui-state-hover").next();
							$(".ui-state-hover").removeClass("ui-state-hover");
							$(secili).addClass("ui-state-hover");
						}
						if($(".ui-state-hover").is(":visible"))
						{}
						else
						{
							$("#selectable li:first-child").addClass("ui-state-hover");
						}
					}
					if(key.keyCode==38)
					{
						if($(".ui-state-hover").is(":first-child"))
						{
						}
						else
						{
							var secili=$(".ui-state-hover").prev();
							$(".ui-state-hover").removeClass("ui-state-hover");
							$(secili).addClass("ui-state-hover");
						}
						if($(".ui-state-hover").is(":visible"))
						{}
						else
						{
							$("#selectable li:last-child").addClass("ui-state-hover");
						}
					}
					if(key.keyCode==13)
					{
						sec($(".ui-state-hover"));
					}
					return false;
				}
			}
		});
		$("#shareditext").live("keyup",function(key) 
		{
			if(key.keyCode==40 || key.keyCode==38 || key.keyCode==13)
			{
				return false;
			}
			var content=$(this).val();
			var go= content.match(start);
			var name= content.match(word);
			var dataString = 'searchword='+ name;
			
			
			var linkVarmi= content.match(urlRegex);
			if(linkVarmi)
			{
				var gLink=0;
				var kactane=linkVarmi.length;
				var i=0;
				for(i=0;i<kactane;i++)
				{
					gLink=gLink+linkVarmi[i].length;
				}
				
				linkKarakter=gLink;
				linkCount=kactane;
			}
			
			if(go)
			{
				if(name)
				{
					//$("#display").html('<ol id="selectable"><li class="ui-widget-content">Item 1</li><li class="ui-widget-content">Item 2</li><li class="ui-widget-content">Item 3</li</ol>').show("blind"); 
					//$("#mentionDisplay").html("yukleniyor").show(); 
					$.ajax({
					type: "POST",
					url: "/ajax/getMentionPerson",
					data: dataString,
					success: function(html)
					{
						//console.log(html);
						//$("#msgbox").hide();
						$("#mentionDisplay").html(html).show(); 
						mentionSelectInit();
					}
					});
				}
			}
			//return false;
		});
});
	function sec(dom)
	{
		var icerik = $("#shareditext").val();
		var aranan=$(dom).attr("rel");
		var bulunan=$(dom).text();
		var newicerik=icerik.replace("@"+aranan,"@"+bulunan+" ");
		var userID=$(dom).children(".userID").val();
		$("#shareditext").val(newicerik);
		
		$("#profileName").val(bulunan);
		$("#profileID").val(userID);
		$("#linkli").val("profile");
		
		
		$( "#selectable" ).remove();
		$("#mentionDisplay").hide();
		$("#shareditext").die();
	}
	function mentionSelectInit()
	{
		$( "#selectable" ).selectable({
			selected: function(event, ui) {
				sec(ui.selected);
				return false;
				var icerik = $("#shareditext").val();
				var aranan=$(ui.selected).attr("rel");
				var bulunan=$(ui.selected).text();
				var newicerik=icerik.replace("@"+aranan,"@"+bulunan+" ");
				var userID=$(ui.selected).children(".userID").val();
				$("#shareditext").val(newicerik);
				
				$("#profileName").val(bulunan);
				$("#profileID").val(userID);
				$("#linkli").val("profile");
				
				
				$( "#selectable" ).remove();
				$("#mentionDisplay").hide();
				$("#shareditext").die();
				//$("#degerler").append(mantionDID);
			}
		});
		$(".ui-widget-content").hover(function(){
			$(this).addClass("ui-state-hover")
		});
		$(".ui-widget-content").mouseout(function(){
			$(this).removeClass("ui-state-hover")
		});

	}


