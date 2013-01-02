<?php
    class profileDetail_block extends control{
        public function block(){
        	global $model, $db, $l;
			$userPerma	= $model->paths[0];
			$c_profile 	= new profile($userPerma);
			$p			= $c_profile->profile;
        	?>
        		<!-- Profil -->
				<section id="profil" class="satir">
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
								<h2><?=$p->motto?></h2>
							</header>
							<aside class="tablo_tutucu">
								<table class="table table-bordered">
									<tr>
										<td colspan="3" class="rutbe meclis_uyeligi"><i class="atolye15-rutbe-meclis-uyeligi"></i> <?=$p->count_deputy?> dönem mecliste yer aldı.</td>
										<td rowspan="2" class="etiketler">
											<!--
											<a href="#">#MecnununYolu</a>, 
											<a href="#">#TürkAskeriDemek</a>, 
											<a href="#">#kardeşlikicin</a>, 
											<a href="#">#Devrimiyidiriyi</a>
											-->
										</td>
									</tr>
									<tr>
										<td class="istatistik"><strong><?=$p->count_voice?></strong> SES</td>
										<td class="istatistik"><strong><?=$p->count_like?></strong> TAKTİR</td>
										<td class="istatistik"><strong><?=$p->count_dislike?></strong> SAYGI</td>
									</tr>
								</table>
								<div class="mobil_etiketler">
									<!--
									<a href="#">#MecnununYolu</a>, 
									<a href="#">#TürkAskeriDemek</a>, 
									<a href="#">#kardeşlikicin</a>, 
									<a href="#">#Devrimiyidiriyi</a>
									-->
								</div>
							</aside>
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
									<li><span class="puan"><strong><?=$p->puan?></strong> PUAN</span></li>
									<li><strong><?=$p->count_following?></strong> TAKİP ETTİĞİ</li>
									<li><strong><?=$p->count_follower?></strong> TAKİPÇİ</li>
								</ul>
							</aside>
						</div>
					</article>
				</section><!-- // Profil -->
        	<?
            }
    }
?>