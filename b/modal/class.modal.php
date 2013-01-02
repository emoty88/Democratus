 <?php
    class modal_block extends control{
    	public function block(){
    		?>
    				<div class="modal" id="sikayetModal" style="display: none;">
					    <div class="modal-header">
						    <button class="close" data-dismiss="modal">×</button>
						    <h3>Bu Ses'ten Neden Rahatsız Oldun</h3>
					    </div>
					    <div class="modal-body">
					    	<p></p>
					    </div>
					    <div class="modal-footer">
						    <a href="javascript:;" id="uygulaBtn" rel="0" class="btn btn-primary">Uygula</a>
					    </div>
				    </div>
				    <div class="modal" id="sikayetModalComment" style="display: none;">
					    <div class="modal-header">
						    <button class="close" data-dismiss="modal">×</button>
						    <h3>Bu Ses'ten Neden Rahatsız Oldun</h3>
					    </div>
					    <div class="modal-bodyComment">
					    	<p></p>
					    </div>
					    <div class="modal-footer">
						    <a href="javascript:;" id="uygulaBtnComment" rel="0" class="btn btn-primary">Uygula</a>
					    </div>
				    </div>
				    <div class="modal" id="sikayetModalProfile" style="display: none;">
					    <div class="modal-header">
						    <button class="close" data-dismiss="modal">×</button>
						    <h3>Bu Kişiden Neden Rahatsız Oldun</h3>
					    </div>
					    <div class="modal-bodyProfile">
					    	<p></p>
					    </div>
					    <div class="modal-footer">
						    <a href="javascript:;" id="uygulaBtnProfile" rel="0" class="btn btn-primary">Uygula</a>
					    </div>
				    </div>
    		<?php 
    	}
    }
?>
     				
