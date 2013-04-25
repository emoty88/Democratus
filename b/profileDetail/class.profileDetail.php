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
						<img id="profileCoverImage" class="banner" src="<?=$model->getcoverimage($p->coverImage,640,206,"cutout");?>" alt="">
						<div class="asil_alan">
							<a class="profil_resmi fnc" href="<?=$model->getprofileimage($p->image,525,650,"cutout")?>">
								<img src="<?=$model->getprofileimage($p->image,105,130,"cutout");?>" alt="">
							</a>
							<header>
								<address>
									<h1>
										<?php if($c_profile->profile->deputy == "1")
											  {
											  	echo '<i class="atolye15-rutbe-" style="width:30px; height:30px; background-size:30px;"></i>';
											  }
										?>
										<a href="javascript:;" class="profilename" title="<?=$p->name?>"><?=$p->name?></a>
										
										
									</h1>
								</address>
								<h2><?=$p->motto?></h2>
							</header>
							
							<aside class="tablo_tutucu">
								<table class="table table-bordered">
									<tr>
										<td colspan="3" class="rutbe meclis_uyeligi"><i class="atolye15-rutbe-meclis-uyeligi"></i> <?=$p->count_deputy?> dönem mecliste yer aldı.</td>
										<td rowspan="2" class="etiketler">
                                            <?php //print_r(profile::get_hastagInterest($p)); ?>
                                            <?php foreach (profile::get_hastagInterest($p) as $hs) : ?>
											
											<a href="/<?=$hs->permalink?>"><?=$hs->name?></a>  
											
                                            <?php endforeach; ?>
                                            <div class="show-all-tags">
												<a href="javascript:;">İlgi Alanları</a>
											</div>
										</td>
									</tr>
									<tr>
										<td class="istatistik"><strong><?=$p->count_voice?></strong> SES</td>
										<td class="istatistik"><strong><?=$p->count_like?></strong> TAKDİR</td>
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
								<div class="btn-group">
					                <button data-toggle="dropdown" class="btn dropdown-toggle" style="position: absolute; left: -60px;">
					                	
					                	<span class="icon-user"></span>
					                	<span class="caret"></span>
					                </button>
					                <ul class="dropdown-menu pull-right" style="margin-top:35px; margin-right: 5px; ">
					                  
					                  <?php
					                  	$followHide = "";
										$unFollowHide = "display:none";
										$vekilOBtn = "";
										if($c_profile->isFollow($p->ID, $model->profileID)){
											$followHide="display:none";
											$unFollowHide =""; 
											$vekilOBtn = '<li><a onclick="vekilOyu('.$p->ID.'); " href="javascript:;" >Vekil Olsun</a></li>';
										} 
										
					                 	echo $vekilOBtn;
					                 	?>
					                 	<li ><a href="javascript:;" id="profilecomplaint" rel="<?=$p->ID?>" style=""> Şikayet Et  </a></li>
					                  	<li ><a href="javascript:;" id="profileBlock" rel="<?=$p->ID?>" onclick="block_user(<?=$p->ID?>)">Engelle </a></li>
					                </ul>
					          	</div>
								<ul class="istatistik_listesi_2">
									
									<? 
									if($p->ID != $model->profileID)
									{ 
										
									?>
									
										<li>
											<button type="button" class="btn btn follow follow-<?=$p->ID?>" style="<?=$followHide?>" onclick="follow(profileID);">Takip Et</button>
											<button type="button" class="btn btn-info unfollow unfollow-<?=$p->ID?>" style="<?=$unFollowHide?>" onclick="follow(profileID);" data-unfText="Takibi Bırak" data-fText="Takip Ediliyor">Takip Ediliyor</button>
										</li>
										
									<? 
									}
									?>
									<li><span class="puan"><strong><?=$p->puan?></strong> PUAN</span></li>
									<li id="follow" data-follow="follows" data-id="<?=$p->ID?>" data-clear="true"><strong><?=$p->count_following?></strong> TAKİP ETTİĞİ</li>
									<li id="follow" data-follow="followers" data-id="<?=$p->ID?>" data-clear="true"><strong><?=$p->count_follower?></strong> TAKİPÇİ</li>
									
								</ul>
							</aside>
						</div>
					</article>
				</section><!-- // Profil -->
        	<?
            }
    }
?>