<?php
    class newHashtagDetail_block extends control{
        public function block(){
        	global $model, $db, $l;
			$userPerma	= $model->paths[0];
			$c_profile 	= new profile($userPerma);
			$p			= $c_profile->profile;
			$onLogin = $model->checkLogin();
			
        	?>
        	<section class="satir" id="profil">
				<article>
				<? 
					if($c_profile->_isAdmin)
					{
						?>
						<div style="float:right; margin:15px 15px -50px 15px; z-index: 9999; position: relative; ">
							<div class="pImageUpload" data-upload="cover">
								<button data-ftext="Resmi Güncelle"  style="" class="btn btn-info "  type="button">Resmi Güncelle</button>
							</div>
						</div>
						<?				
					} 
					?>
					<img class="banner" style="width: 640px; height: 206px;" src="<?=$model->getcoverimage($p->coverImage,640,206,"cutout");?>" alt="">
					<div class="asil_alan">
						
						
						<header style="padding: 10px; margin-top:-205px; background-color: rgba(255,255,255,0.7);">
							<address>
								<h1>
									<a href="javascript:;" title="<?=$p->name?>"><?=$p->name?></a>
								</h1>
							</address>
		
							<h2><?=$p->motto?></h2>
				
						</header>
						
						
						
					</div>
						<div style="margin:0px 10px; text-align: center;">
							<div class="btn-group" style="width: 100%; ">
								<button class="btn btn-large btn-success" type="button" style="width: 50%">Destekle</button>
								<button class="btn btn-large btn-danger" type="button" style="width: 50%">Köstekle</button>
							</div>
							<div class="progress progress-striped active" style="height: 45px;">
							    <div class="bar bar-success" style="width: 50%; background-size:45px 45px;">
							    	<div style="padding-top:12px; font-size:16pt; font-weight: bold;">%50 Destek</div>
							    </div>
							    <div class="bar bar-danger" style="width: 50%; background-size:45px 45px;">
							    	<div style="padding-top:12px; font-size:16pt; font-weight: bold;">%50 Köstek</div>
							    </div>
						    </div>
						</div>
				</article>
			</section>
			<? 
			if($onLogin) {
			?>
			<div class="satir" >
				<div id="yeni_yazi_yaz" class="karakter_sayaci_tutucu htShareArea">
					<textarea id="replyTextArea_0" name="yeni_yazi" class="karakteri_sayilacak_alan" placeholder="Fikrini paylaş..." rows="2">#<?=$userPerma?> </textarea>
					<div class="kalan_karakter_mesaji"><span class="karakter_sayaci">200</span> karakter</div>
					
					<div class="kontroller">
						
                     	<img id="voice-share-progress" style="position:absolute; width:20px; right:150px; margin-top: 3px; display: none" src="/t/ala/img/loading.gif" />
						<button id="share_voice" class="btn btn-danger" onclick="share_voice(this)" data-randID="0" >Paylaş</button>
						
						<input type="hidden" name="replyer_0" id="replyer_0" value="0" />
						
						<a id="fine-uploader-btn_0" class="fineUploader" href="javascript:;" onclick="globalRandID=0;" data-randID="0">
							<i id="bootstrapped-fine-uploader" class="atolye15-ikon-gorsel atolye15-ikon-24"></i>
						</a>
						<?
						if($c_profile->_isAdmin)
						{
						?>
							<label for="make_agenda" style="float: right; margin-right: 10px; margin-top: 5px;">Gündeme Ekle</label>
							<input type="checkbox" id="make_agenda" name="make_agenda" value="true" style="float:right; margin-right: 5px; margin-top: 8px;"/>
						<?
						}
						?>
						<div id="fine-uploader-msg_0"></div>	
						
					</div>
					<div class="clearfix"></div>
				</div>
				<? } ?>
				
				<input type="hidden" id="initem_0" name="initem" value="0" />
			    <input type="hidden" id="initem-name_0" name="initem-name" value="0" />
			</div><!-- Yeni Yazı Yaz -->
        	<?
            }
    }
?>