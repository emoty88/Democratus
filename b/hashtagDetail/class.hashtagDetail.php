<?php
    class hashtagDetail_block extends control{
        public function block(){
        	global $model, $db, $l;
			$userPerma	= $model->paths[0];
			$c_profile 	= new profile($userPerma);
			$p			= $c_profile->profile;
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
						
						<a class="profil_resmi" href="#">
							<img src="<?=$model->getprofileimage($p->image,105,130,"cutout");?>" alt="<?=$p->name?> Profil resmi" >
						</a>
						<header>
							<address>
								<h1>
									<a href="javascript:;" title="<?=$p->name?>"><?=$p->name?></a>
								</h1>
							</address>
							<? 
							/* Bu alanın net karşılığı henüz yok
							<h2 class="etiketler">
								<a href="#">#2014secim</a>, <a href="#">#yerelsecim</a>, <a href="#">#chpsecim</a>
							</h2>
							 *
							 */
							?>
							<h2><?=$p->motto?></h2>
							<?
							/* Bu alanın datası henüz çekilmiyor
							<address class="yoneticiler">
								<strong>Yöneticiler:</strong> 
								<a title="Kazım Taş" href="#">Kazım Taş</a>, 
								<a title="Kazım Taş" href="#">Mustafa Cankar</a>
							</address>
							 * 
							 */
							?>
							</header>
						<aside class="takip_bilgileri">
							<ul class="istatistik_listesi_2">
									
								<? 
								if($p->ID != $model->profileID)
								{ 
									
								
									$followHide = "";
									$unFollowHide = "display:none";
									if($c_profile->isFollow($p->ID, $model->profileID)){
										$followHide="display:none";
										$unFollowHide ="";
									} 

								?>
								
									<li><button type="button" class="btn btn follow follow-<?=$p->ID?>" style="<?=$followHide?>" onclick="follow(profileID);">Takip Et</button></li>
									<li><button type="button" class="btn btn-info unfollow unfollow-<?=$p->ID?>" style="<?=$unFollowHide?>" onclick="follow(profileID);" data-unfText="Takibi Bırak" data-fText="Takip Ediliyor">Takip Ediliyor</button></li>
								<? 
								}
								?>
								
								<li><strong><?=$p->count_follower?></strong> TAKİPÇİ</li>
							</ul>
						</aside>
						<aside style="padding-left: 33px">
							<!--
							<div class="caroufredsel_wrapper" style="display: block; text-align: start; float: none; position: relative; top: 0px; right: 0px; bottom: 0px; left: 0px; z-index: auto; width: 534px; height: 54px; margin: 15px 0px 0px; overflow: hidden;">
								<div id="imageGaleryArea" class="img-slider-content image_carousel" style="text-align: left; float: none; position: absolute; top: 0px; right: auto; bottom: auto; left: 0px; margin: 0px; width: 2670px; height: 54px;">
									
								</div>
							</div>
							<div class="clearfix"></div>
							-->
							<!--
							<a href="#" id="slider-prev" class="prev disabled" style="display: block;"><span>geri</span></a>
							<a href="#" id="slider-next" class="next" style="display: block;"><span>ileri</span></a>
							-->
						</aside>	
						
					</div>
						
				</article>
			</section>
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
						<div id="fine-uploader-msg_0"></div>	
						
					</div>
					<div class="clearfix"></div>
				</div>
				
				<input type="hidden" id="initem_0" name="initem" value="0" />
			    <input type="hidden" id="initem-name_0" name="initem-name" value="0" />
			</div><!-- Yeni Yazı Yaz -->
        	<?
            }
    }
?>