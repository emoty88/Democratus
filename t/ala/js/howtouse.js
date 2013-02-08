var stepsHome = [
				{
					placement:"bottom", 
					domSelector: "#yeni_yazi_yaz",
					title:"Fikrini Paylaş", 
					content: "<p style='font-size:10pt;'>200 karaktere kadar yazacağın \"ses\"lerle fikrini takipçilerine duyurabilirsin. Duvarında da takip ettiğin kişilerin \"ses\"lerini bulabilirsin.</p>", 
					buttons: ["next_btn","stop_btn"]
				},
				{
					placement:"bottom", 
					domSelector: "#sesozel", 
					title:"Yazılmış Bir Ses", 
					content: "<p style='font-size:10pt;'>Bir \"ses\"i yanıtlamak istersen üzerine tıklaman yeterli. +voice linki hangi \"ses\"i yanıtladığını gösterecektir.</p>", 
					buttons: ["prev_btn", "next_btn", "stop_btn"],
					onAfterScrtip: "loadDumyVoice(38750)"
				},
				{
					placement:"right", 
					domSelector: "#meclis_gagget", 
					title:"Ülke Meclisi", 
					content: "<p style='font-size:10pt;'>Haftalık seçimlerle iş başına gelen vekillerin her gün hazırladığı referandumları oylayarak fikrini tüm ülkeyle paylaşabilirsin. Böylece ülke gündemini her gün belirlersin.</p>", 
					buttons: ["prev_btn", "next_btn", "stop_btn"]
				},
				{
					placement:"right", 
					domSelector: "#popularVoice_gadget", 
					title:"Ses Getirenler", 
					content: "<p style='font-size:10pt;'>Son 24 saat içerisinde ülke genelinde en çok etki oluşturan paylaşımları bu alanda bulacaksın. Daha fazlası için \"Ses Getirenler\" sayfasını ziyaret edebilirsin.</p>", 
					buttons: ["prev_btn", "next_btn", "stop_btn"]
				},
				{
					placement:"right", 
					domSelector: "#popularVoice_gadget", 
					title:"", 
					content: "", 
					buttons: ["prev_btn", "next_btn", "stop_btn"],
					onAfterScrtip: "location.href='/democratus/startTour';"
				}
			];
			/*
			{
					placement:"bottom", 
					domSelector: ".asil_alan", 
					title:"Konu kurum sayfaları", 
					content: "<p style='font-size:10pt;'>Konu ve kurumları #linkler in özel bir sayfa haline gelmiş halidir. İlgilendiklerini takip ederek onlardan haberdar olabilir, sayfa duvarında ilgili tüm 'ses' leri görebilirsin</p>", 
					buttons: ["next_btn", "stop_btn"]
					
				},
				*/
var stepsHashTag =[
				
				{
					placement:"bottom", 
					domSelector: ".htShareArea", 
					title:"Konu - Kurum Sayfaları", 
					content: "<p style='font-size:10pt;'>Konu ve kurumları, #link haline getiriyoruz. İlgilendiklerini takip ederek onlardan haberdar olabilir, sayfa duvarında ilgili tüm sesleri görebilirsin.</p>", 
					buttons: ["next_btn", "stop_btn"]
					
				},
				{
					placement:"right", 
					domSelector: ".hashTag_slide", 
					title:"Konu - Kurum Sayfasının Gündem ve Ses Getirenleri", 
					content: "<p style='font-size:10pt;'>Gündemler sayfa yöneticileri tarafından hazırlanır. Bu gündemleri oylarınla sen şekillendirirsin. Ayrıca son 24 saatte en çok reaksiyon alan \"ses\"leri Ses Getirenler alanında bulacaksın.</p>", 
					buttons: ["prev_btn", "next_btn", "stop_btn"]
					
				}
				,
				{
					placement:"right", 
					domSelector: ".hashTag_slide", 
					title:"", 
					content: "", 
					buttons: ["prev_btn", "next_btn", "stop_btn"],
					onAfterScrtip: "location.href='/my#arkadasB';"
				}
				];
			
var buttons = {
				next_btn : {value:"Sonraki", click: "next_step()", class: "btn-info"},
				prev_btn : {value:"Önceki", click: "prev_step()", class: "btn-info"},
				stop_btn : {value:"Bitir", click: "hide_stepAll()" ,class: "btn-danger"}
			  };
var showStep = -1;
$(document).ready(function () {
	$("BODY").prepend('<div class="overlay" ></div>');
	switch(plugin)
   	{
   		case "hashTag" : op=true; steps = stepsHashTag; break;
   		case "home" : op=true; steps = stepsHome; break;
   		default : op = false;
   		
   	}  	
   	if(op)
	for(var i=0; i<steps.length; i++)
	{
		init_step(i);
	}
	//show_step(0); // tetikleyici
});

function init_step(stepIndex)
{
	step = steps[stepIndex];
	var options = {html: true, title:get_title(stepIndex) ,content : get_content(stepIndex), trigger:"manual" , placement: step.placement};
	$(step.domSelector).popover(options);
}
function show_step(stepIndex)
{
	//hide_stepAll();
	hide_step(showStep);
	step = steps[stepIndex];
	if(step.onAfterScrtip)
	{
		eval(step.onAfterScrtip);
	}
	$(step.domSelector).css("position","relative");
	$(step.domSelector).css("z-index","10099");
	$(".overlay").show();
	$(step.domSelector).popover("show");
	showStep = stepIndex;
	$("BODY").scrollTo( $(step.domSelector), { duration:1000, axis:'y', offset:-100 });
}
function hide_step(stepIndex)
{
	if(stepIndex== -1)
	return;
	//console.log(stepIndex);
	step = steps[stepIndex];
	$(step.domSelector).popover("hide");
	$(step.domSelector).css("z-index","100");
	$(".overlay").hide();
}
function hide_stepAll()
{
	for(var i=0; i<steps.length; i++)
	{
		hide_step(i);
	}
	showStep = -1;	
}
function next_step()
{
	show_step(showStep+1);
	return false;
}
function prev_step()
{
	show_step(showStep-1);
	return false;
}
function get_content(stepIndex)
{
	var step = steps[stepIndex]
	var html = step.content;
	if(step.buttons.length>0)
	{
		//console.log(step.buttons);
		html += '<hr style="margin:10px;" />';
		$.each(step.buttons, function(key,btn) {
			html += create_button(btn);
		});
	}
	
	return html;
}
function get_title(stepIndex)
{
	var step = steps[stepIndex]
	var title = step.title+'<i onclick="hide_stepAll();" class="icon-remove" style="float:right; cursor:pointer;"></i>';
	return title;
}
function create_button(btn_key)
{
	return '<button onclick="'+buttons[btn_key]["click"]+'" class="btn '+buttons[btn_key]["class"]+'" style="margin-right:10px;">'+buttons[btn_key]["value"]+'</button>'
}
function loadDumyVoice()
{
	 var ses = {
	 			"ID":"38749",
	 			"isMine":false,
	 			"sName":"#Democratus",
	 			"sPerma":"Democratus",
	 			"sImage":"http:\/\/democ.com\/u\/default-image\/default-profile-image_48x48cutout.png",
	 			"voice": " Bir ses'e cevaben yazılan iletiler sadece yazarın duvarına düşmekte. Ancak +voice linkinin önüne bir karakter yahut yazı girilmesi durumunda bu ses, yazarın tüm takipçilerinin duvarı1na düşmektedir.", 
	 			"sTime":"3 ay, 3 hafta",
	 			"initem":0,
	 			"replyCount":"0",
	 			"replyID":"0",
	 			"randNum":4870,
	 			"reShareCount": 0,
	 			"likeCount":0,
	 			"dislikeCount":0
	 			};
	 
	 $("#orta_alan_container").prepend("<div id='sesozel' ></div>");			

	 $("#duvaryazisi-tmpl").tmpl(ses,make_link).css("background-color", "#fff").prependTo("#sesozel");	
	 //voiceDetail($("div[data-randnum=4870]"));
	 
	 init_step(1);
}
