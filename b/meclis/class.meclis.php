<?php
    class meclis_block extends control{
        public function block(){
        	global $model;
        	
            $c_parliament=new parliament;
			$type=0;
			$parentID=0;
			
			$meclisName = "Türkiye Meclisi";
			$is_hashTag = false;
			if(isset($model->page->permalink) && $model->page->permalink=="hashTag")//chech ed 
			{
				
				$type="hastagID";
				$c_profile = new profile();
				$parentID= $c_profile->change_perma2ID($model->paths[0]);
				$meclisName = $model->paths[0]."";
				$is_hashTag=true;
			}
			$mobilMeclis = true;
			$mobilMeclisClass = "hidden-phone";

			if($model->page == null || $model->page->permalink=="parliament")
			{
				$mobilMeclis=false;
				$mobilMeclisClass = "";
			}
		
           	$agendasNonSort=$c_parliament->get_agenda($type, $parentID);
			$agendas = $c_parliament->short_agandaNew($agendasNonSort);
                        
            if(!$model->checkLogin(FALSE)){
                foreach ($agendas as $ag)
                $model->addScript("get_meclis_istatistik($ag->ID);");
            }
			
			if($mobilMeclis)
			{
        	?>

        		<!-- Kırmızı Bileşen -->
        		<section class="bilesen kirmizi kontroller_var visible-phone" id="meclis_gagget">
					<header>
						<hgroup>
							<h1><?=$meclisName?></h1>
							<? if($is_hashTag) {?>
								<h2>Gündemi</h6>
							<? } else { ?>
								<h2>(Referandum)</h6>
							<? } ?>
						</hgroup>
					</header>
					<aside class="kontroller">
						<?php 
						if(!$is_hashTag)
						{ ?>
						<a href="/parliament" class="sayfaya_git fnc" title="Tümünü görüntüle &rarr;">
							<?
							$new_agenda = $c_parliament->count_agenda($type, $parentID);
							
							if($new_agenda>0)
							{?>
								<span class="etiket"><?=$new_agenda?></span>
							<?}
							?>
							<i class="atolye15-ikon-ok atolye15-ikon-24"></i>
						</a>
						<? } ?>
					</aside>
				</section>
			<?php
			}
			?>
        		<section class="bilesen kirmizi kontroller_var <?=$mobilMeclisClass?>" id="meclis_gagget">
					<header>
						<hgroup>
							<h1><?=$meclisName?></h1>
							<? if($is_hashTag) {?>
								<h2>Gündemi</h6>
							<? } else { ?>
								<h2>(Referandum)</h6>
							<? } ?>
						</hgroup>
					</header>
					
					<aside class="kontroller">
						<?php 
						if(!$is_hashTag)
						{ ?>
						<a href="/parliament" class="sayfaya_git fnc" title="Tümünü görüntüle &rarr;">
							<?
							$new_agenda = $c_parliament->count_agenda($type, $parentID);
							
							if($new_agenda>0)
							{?>
								<span class="etiket"><?=$new_agenda?></span>
							<?}
							?>
							<i class="atolye15-ikon-ok atolye15-ikon-24"></i>
						</a>
						<? } ?>
					</aside>
					<div class="bilesen_icerigi dolgu_1 list_carousel">
						<ul id="kirmizi-bilesen">
							<?php
							foreach($agendas as $a)
							{
								?>
								<li>
									<img src="<?=$model->getProfileImage($a->deputyimage,22,22,"cutout")?>" alt="profile-img">
                                    <h5><a href="/<?=$a->deputyPerma?>"><?=$a->deputyname?></a></h5>
									<p style="height: 160px;">
										<a href="/voice/<?=$a->diID?>" style="text-decoration: none; font-weight: normal;">
											<?=$a->title?>
										</a>
									</p>
									<div id="meclis-bottom-box-<?=$a->ID?>" class="meclis_bottom_box">
									<? 
									if(!$a->myvote>0)
									{ ?>
										
											<a href="javascript:;" data-choice="2" data-agendaID="<?=$a->ID?>" class="btn btn-mini meclis_oy">Katılıyorum</a>
											<a href="javascript:;" data-choice="3" data-agendaID="<?=$a->ID?>" class="btn btn-mini meclis_oy">Kararsızım</a>
											<a href="javascript:;" data-choice="4" data-agendaID="<?=$a->ID?>" class="btn btn-mini meclis_oy">Katılmıyorum</a>								
									<?
									}else
									{
										$vp=parliament::get_agendaPercent($a->ID);
										$olumlu=$vp["olumlu"];
										$olumsuz=$vp["olumsuz"];
										$fikiryok=$vp["fikiryok"];
					
										if($olumlu > 0)
										{
										?>
											<div class="bar yuzdeler_meclis olumlu" style="width: <?=$olumlu?>%;"><?=$olumlu?></div>
										<?
										}
										if($fikiryok > 0)
										{
										?>
											<div class="bar yuzdeler_meclis fikir-yok" style="width: <?=$fikiryok?>%;"><?=$fikiryok?></div>
										<?
										}
										if($olumsuz > 0)
										{
										?>
											<div class="bar yuzdeler_meclis olumsuz" style="width: <?=$olumsuz?>%;"><?=$olumsuz?></div>
										<?
										}
										
									}
									?>
									</div>
								</li>
								<?
							}
							?>
						</ul>	
						<section class="sd-bottom">
							<div class="pagination" id="kirmizi-bilesen-pag"></div>
						</section>
					</div>
				</section>
			<?
			
			
        }
    }
?>
