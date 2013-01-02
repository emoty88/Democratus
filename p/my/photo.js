    $(function(){
    
    var button = $("#myphotoupload"), interval;
        
        new AjaxUpload(button, {
            action: ajaxurl + 'imageupload', 
            name: "uploadfile",
            hoverClass:"",
            onSubmit : function(file, ext){
                // change button text, when user selects file            
                button.text("Uploading");
                                
                // If you want to allow uploading only 1 file at time,
                // you can disable upload button
                this.disable();
                
                // Uploding -> Uploading. -> Uploading...
                interval = window.setInterval(function(){
                    var text = button.text();
                    if (text.length < 13){
                        button.text(text + ".");                    
                    } else {
                        button.text("Uploading");                
                    }
                }, 200);
            },
            onComplete: function(file, response){
                button.text("Upload");
                            
                window.clearInterval(interval);
                            
                // enable upload button
                this.enable();
                window.document.location.href = window.document.location.href;                      
            }
        }); 
        
    });