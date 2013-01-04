<?php
    class sharetextbox_block extends control{
        public function block()
		{
			global $model;
						
			$model->addScript(PLUGINURL . 'lib/fineuploader/jquery.fineuploader-3.0.js', 'fileuploader-3.0.js', 1 );
			$model->addStyle(PLUGINURL . 'lib/fineuploader/fileuploader.css', 'fileuploader.css', 1 );
			?>
			<!-- Yeni Yazı Yaz -->
			<div class="satir">
				<div id="yeni_yazi_yaz" class="karakter_sayaci_tutucu">
					<textarea id="replyTextArea_0" name="yeni_yazi" class="karakteri_sayilacak_alan" placeholder="Fikrini Paylaş..." rows="2"></textarea>
					<div class="kalan_karakter_mesaji"><span class="karakter_sayaci">200</span> karakter</div>
					
					<div class="kontroller">
						<button id="share_voice" class="btn btn-danger" onclick="share_voice(this)" data-randID="0" >Paylaş</button>
						
						<input type="hidden" name="replyer_0" id="replyer_0" value="0" />
						<a id="fine-uploader-btn" href="javascript:void(0)">
							<i id="bootstrapped-fine-uploader" class="atolye15-ikon-gorsel atolye15-ikon-24"></i>
						</a>
						<div id="fine-uploader-msg"></div>	
						
					</div>
					<div class="clearfix"></div>
				</div>
				<input type="hidden" id="initem" name="initem" value="0" />
			    <input type="hidden" id="initem-name" name="initem-name" value="0" />
			</div><!-- Yeni Yazı Yaz -->
			<?
		}
        public function bloc_oldk(){
        	global $model, $db, $l; 
			$defaultValue="";
			
			
			if($model->paths[0]=="t")
			{
				$defaultValue="#".$model->paths[1]." ";
			}
			$model->addScript(PLUGINURL . 'lib/fineuploader/fileuploader.js', 'fileuploader.js', 1 );
			$model->addStyle(PLUGINURL . 'lib/fineuploader/fileuploader.css', 'fileuploader.css', 1 );
        	?>
        		<div class="roundedcontent shareidea">
					<div class="textarea">
						<textarea rows="3" id="shareditext" placeholder="Fikrini Paylaş" class="input-xlarge numberSay tooltip-top" data-original-title="Takipçilerinize 200 karakterlik sesler ile fikirlerinizi ve birikimlerinizi duyurun." onblur="yorumDar('shareditext')" onfocus="yorumGenis('shareditext')" style="width: 400px; height: 15px; resize:none; overflow: auto;" ><?=$defaultValue?></textarea>
			            <div style="display: none;" id="degerler">
						<input type="hidden" value="0" id="linkli">
						<input type="hidden" value="" id="profileName">
						<input type="hidden" value="" id="profileID">
						</div>
			            <ul style="float: right; list-style: none; margin: 0;">
			            	<li style="float: left; display: none;" class="hideArea-shareditext">
			            		<span style="color: #9B9B9B; font-size:10pt; margin-right: 10px; margin-top: 5px;">
			            			<span id="shareditextNumber" style="float: none;font-size:10pt;">200</span> Karakter
			            		</span>
			            		<?
			            		if($model->profile->deputy==1){
								?>
			            		<span style="color: #9B9B9B; font-size:10pt; margin-right: 10px; margin-top: 5px;">
			            			<input type="checkbox" class="proposal" value="1" id="proposal" style="float: left; margin-right: 5px;" />
			            			<label for="proposal" style="color:#9b9b9b; float: left;">Tasarı Olsun</label>
			            		</span>
			            		<? } ?>
			            		<?php
			            		$tagVarmi=$model->get_otherProfile();
								
								if($tagVarmi->status)
								{ ?>
									<select name="otherPID" id="otherPID" style="width:150px; ">
				            			<option value="default"><?=$model->profile->name." ".$model->profile->surname?></option>
				            			<?php 
				            			foreach($tagVarmi->tag as $t)
										{
											?><option value="<?=$t->ID?>">#<?=$t->permalink?></option><?
										}
				            			?>
				            		</select>
				            	<?
								}
								else 
								{
									?>
									<input type="hidden" name="otherPID" id="otherPID" value="default" />
									<?
								}
			            		?>
			            		
							
			            	</li>
			            	<li style="float: left;">
			            		
			            		<button class="btn btn-gonder tooltip-top" data-original-title="" id="shareditextButton">Paylaş</button>
			            	</li>
			            	
			            </ul>
			            <div class="hideArea-shareditext" style="display:none;">
			            	
			            		<div id="resimYukleAlan">		
									<noscript>			
										<p>Resim Yükleyebilmek için Javascript lerin aktif olması gerekli.</p>
										<!-- or put a simple form for upload here -->
									</noscript>         
								</div>
								<div style="clear:both;"></div>
								<ul id="separate-list" style="list-style: none; float:left; margin:0; padding:0;"></ul>
								<?php
								$model->addScript("window.onload = createUploader;");
								?>
			            	
			            </div>
			            <input type="hidden" id="initem" name="initem" value="0" />
			            <input type="hidden" id="initem-name" name="initem-name" value="0" />
		            </div>
		            <div id="mentionDisplay" style="position: absolute; z-index: 999;"></div>
				</div>
				<div style="clear:both;"></div>
        	<?
        }
	}
?>
