$('#wellcome-login').live('click',function() {
var email = $('#loginemail').attr('value');
var pass = $('#loginpass').attr('value');
//if( !isemail(email) ) return false;
if( !(email.length >1) || !(pass.length >1) ) return false;
$.post( '/ajax/login/', { email: email, pass: pass }, function(data){
if(data.status=='success'){
//alert('success');
window.location = redirect;
} else {
$('#welcomeMessage-textArea').text(data.message);
$('#welcomeMessage').show();
}
}, 'json');
return false;
}); 