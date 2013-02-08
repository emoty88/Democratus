/**
 * @author caner türkmen 
 * for democratus.com
 * turkmencaner@gmail.com
 */
$(document).ready(function(){

});
function show_alertBox(toolName,heading,text,buton)
{
	$("#"+toolName+"-heading").html(heading);
	$("#"+toolName+"-textArea").html(text);
	$("#"+toolName+"-butonArea").html(buton);
	$("#"+toolName+"Content").fadeIn();
	
}
function hide_alertBox(toolName)
{
	$("#"+toolName+"-heading").html("");
	$("#"+toolName+"-textArea").html("");
	$("#"+toolName+"-butonArea").html("");
	$("#"+toolName+"Content").fadeOut();
}
function warninShow_notActivateWriteVoice()
{
	var aHeader="";//"Hesabınız henüz aktive edilmemiş.";
	var aTextr="Democratus üzerinde paylaşım yapabilmek için Hesabınızı aktive etmelisiniz.";
	var aButton="";//'<a class="btn btn-danger" href="javascript:send_activateMail(\\\''.$model->user->email.'\\\');">Aktivasyon mailini yeniden gönder</a>';
  	show_alertBox('alert',aHeader, aTextr, aButton);
}
