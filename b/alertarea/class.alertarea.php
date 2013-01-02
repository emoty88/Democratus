<?php
    class alertarea_block extends control{
        
        public function block(){
        	global $model;
			$model->addScript(BLOCKURL."alertarea/uicnr_alert.js","uicnr_alert.js",1); 
			$alertClass=new alert;
			$alertClass->main();
	        ?>
	        	
	
		        <div id="alertContent" class="alert alert-block alert-error fade in" style="width:890px; display:table; margin:0 auto 10px auto; display: none;">
			    	<button type="button" id="alertClose" class="close" data-dismiss="alert">×</button>
			        <h4 id="alert-heading" class="alert-heading"></h4>
			        <p id="alert-textArea"></p>
			        <p id="alert-butonArea"></p>
			        <!--<a class="btn btn-danger" href="#">Take this action</a>-->
		   		</div>

	        	<div id="warningContent" class="alert alert-block" style="width:890px; display:table; margin:0 auto 10px auto; display: none;">
					<button type="button" class="close" data-dismiss="alert">×</button>
				  	<h4 id="warning-heading"></h4>
				 	<p id="warning-textArea"></p>
			        <p id="warning-butonArea"></p>
				</div>
				<div style="clear: both"></div>
	        <?php
		}
    }
?>
