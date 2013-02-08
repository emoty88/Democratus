var dialog;

$(function(){
    
    $( ".datefield" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
    }).mask("9999-99-99");
    
    
    $("#newuserform #countryID").change(function() {
        var city = $('#newuserform #cityID');
        city.attr('disabled',true);
        $.post("/ajax/getcities", { countryID: $(this).val() }, function(data){ 
            city.html('');
            for (row in data){
                city.append('<option value="'+data[row].ID+'">'+data[row].city+'</option>');
            }
        },'json');
        city.removeAttr('disabled');
        
    });
    
    
    $("#newuserform").validate({
        errorClass: "validerror",
        validClass: "validsuccess",
        errorElement: "span",
        rules: {
            email:{required:true, email:true},
            password: {
                required: true,
                minlength: 4
            },
            password2: {
                required: true,
                minlength: 4,
                equalTo: "#password"
            },
            name: {
                required: true,
                minlength: 2
            },
            
            motto: {
                required: true,
                minlength: 3
            },
            country: {
                required: true
            },
            city: {
                required: true
            },
            agree: {
                required: true  
            },
            recaptcha_response_field: {
                required: true
            }
        },
        messages: {
            recaptcha_response_field: "",
            agree: ""
        }
    });

    
    
    

    $("#newusersasve").click(function(){
        $(this).attr("disabled", true);
                     
        $.post("/user/newusersave", $('#newuserform').serialize(), function(data){ 
            eval(data); 
        });
        
        $(this).removeAttr("disabled");
        //return false;
        
    });
});
 
 
function newusersave(){
    $("#newusersave").attr("disabled", true);
                     
    $.post("/user/newusersave", $('#newuserform').serialize(), function(data){ 
        eval(data); 
    });
    
    $("#newusersave").removeAttr("disabled");
    return false;
}