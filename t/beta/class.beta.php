<?php
    //die('default_view dosyası');
    class beta_view extends view{
        public $name = 'beta';
        
        public function main(){
            global $model, $db, $l;
            $model->addHeader("
			<script type=\"text/javascript\">
			
			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', 'UA-27503553-1']);
			  _gaq.push(['_setDomainName', 'democratus.com']);
			  _gaq.push(['_trackPageview']);
			
			  (function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			
			</script>");
            
            if(file_exists( $this->path . $model->view . '.php')) 
                include($this->path . $model->view . '.php');
            else
                include($this->path . 'default.php');
        }
        
    }
    
?>