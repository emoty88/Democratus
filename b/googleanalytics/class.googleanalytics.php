<?php
    class googleanalytics_block extends control{
        
        public function block(){
            global $model, $db, $l;
?>
                <!-- Google Analytics [Begin] -->
                <script type="text/javascript">

				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', 'UA-27503553-1']);
				  _gaq.push(['_trackPageview']);
				
				  (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();
				
				</script>
                <!-- Google Analytics [End] -->
<?php
        }
    }
?>