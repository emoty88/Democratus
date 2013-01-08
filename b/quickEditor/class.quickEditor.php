<?php
    class quickEditor_block extends control{
        public function block()
		{
			global $model;
			$model->addScript("$(document).ready(function (){
				init_quick_editor();
			});");
			?>
			<!-- Quick Editor -->
			<section id="quick-editor" class="dm-menu" style="display: none;">
				<div class="dm-menu-content karakter_sayaci_tutucu">
					<textarea id="replyTextArea_qe" name="quick-text" placeholder="Fikrini Paylaş..." class="karakteri_sayilacak_alan"></textarea>
					<div class="textarea-controller">
						<div class="kalan_karakter_mesaji">
							<span class="karakter_sayaci" data-limit="200">200</span> karakter
						</div>
						<div class="kontroller pull-right">
						
							<a id="fine-uploader-btn_0" class="fineUploader" href="javascript:;" data-randID="qe">
								<i id="bootstrapped-fine-uploader" class="atolye15-ikon-gorsel atolye15-ikon-24"></i>
							</a>
							<button id="quick-editor_btn" class="btn btn-danger" onclick="share_voice(this)" data-randID="qe">Paylaş</button>
							
							<input type="hidden" id="replying_qe" value="0" />
							<input type="hidden" id="initem_qe" name="initem" value="0" />
			  	 	 		<input type="hidden" id="initem-name_qe" name="initem-name" value="0" />
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="path-to-up"></div>
			</section>
			<?
		}
	}
?>

