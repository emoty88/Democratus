
var steps = [
				{placement:"bottom", domSelector: "#yeni_yazi_yaz",title:"test", content: "<p>200 Karaktere kadar yazacağın 'ses' lerle fikrini takipçilerine duyurabilirsin. Duvarında ise takip ettiğin kişilerin 'ses' lerini bulabilirsin.</p>", buttons: ["next_btn","stop_btn"]},
				{placement:"right", domSelector: "#duvara_git", title:"test", content: "<p>test ikinci kontent budur</p>", buttons: ["prev_btn", "next_btn", "stop_btn"]},
				{placement:"right", domSelector: "#meclis_gagget", title:"test", content: "<p>test ikinci kontent budur</p>", buttons: ["prev_btn", "next_btn", "stop_btn"]},
				{placement:"right", domSelector: "#popularVoice_gadget", title:"test", content: "<p>test ikinci kontent budur</p>", buttons: ["prev_btn",  "stop_btn"]}
			];
var buttons = {
				next_btn : {value:"Sonraki", click: "next_step()", class: "btn-info"},
				prev_btn : {value:"Önceki", click: "prev_step()", class: "btn-info"},
				stop_btn : {value:"Bitir", click: "hide_stepAll()" ,class: "btn-danger"}
			  };
var showStep = -1;
$(document).ready(function () {
	$("BODY").prepend('<div class="overlay" ></div>');
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
	$(step.domSelector).css("z-index","1000");
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