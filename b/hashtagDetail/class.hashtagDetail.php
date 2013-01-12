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
					<img class="banner" src="<?=$model->getcoverimage($p->coverImage,640,206,"cutout");?>" alt="">
					<div class="asil_alan">
						<a class="profil_resmi" href="#">
							<img src="<?=$model->getprofileimage($p->image,105,130,"cutout");?>" alt="">
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
							<div class="caroufredsel_wrapper" style="display: block; text-align: start; float: none; position: relative; top: 0px; right: 0px; bottom: 0px; left: 0px; z-index: auto; width: 534px; height: 54px; margin: 15px 0px 0px; overflow: hidden;">
								<div id="imageGaleryArea" class="img-slider-content image_carousel" style="text-align: left; float: none; position: absolute; top: 0px; right: auto; bottom: auto; left: 0px; margin: 0px; width: 2670px; height: 54px;">
									
								</div>
							</div>
							<div class="clearfix"></div>
							<!--
							<a href="#" id="slider-prev" class="prev disabled" style="display: block;"><span>geri</span></a>
							<a href="#" id="slider-next" class="next" style="display: block;"><span>ileri</span></a>
							-->
						</aside>	
					</div>
				</article>
			</section>
        	<?
            }
    }
?>