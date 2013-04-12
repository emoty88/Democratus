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
	
           	$agendasNonSort=$c_parliament->get_agenda($type, $parentID);
			$agendas = $c_parliament->short_agandaNew($agendasNonSort);
                        
            if(!$model->checkLogin(FALSE)){
                foreach ($agendas as $ag)
                $model->addScript("get_meclis_istatistik($ag->ID);");
            }
			
        	?>
        		<!-- Kırmızı Bileşen -->
        		<section class="bilesen kirmizi kontroller_var" id="meclis_gagget">
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
                                    <h5><a href="/<?=profile::change_ID2perma($a->deputyID)?>"><?=$a->deputyname?></a></h5>
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
        public function block_old(){
        	global $model, $db, $l; 
        	
        	//$model->addStyle(TEMPLATEURL . 'v2/static/css/bootstrap.css', 'bootstrap.css', 1 );
            //$model->addStyle(TEMPLATEURL . 'v2/static/css/bootstrap-responsive.css', 'bootstrap-responsive.css', 1 );
            
            $model->addScript(PLUGINURL . 'lib/fancybox/jquery.fancybox-1.3.4.pack.js', 'fancybox.js', 1 );
	        $model->addScript('$(document).ready(function() {
	        		
					$(".fnc").fancybox({ 
						\'transitionIn\'	:	\'elastic\',
						\'transitionOut\'	:	\'elastic\',
						\'speedIn\'		:	400, 
						\'speedOut\'		:	300, 
						\'overlayShow\'	:	false
					});
					
				});	
			');
	        $model->addStyle(PLUGINURL . 'lib/fancybox/jquery.fancybox-1.3.4.css', 'fancybox.css', 1 );
            $c_parliament=new parliament;
			$type=0;
			$parentID=0;
			if($model->paths[0]=="t")
			{
				$type="hastagID";
				$parentID=profile::change_perma2ID($model->paths[1]);
			}
            $agendas=$c_parliament->get_agenda($type, $parentID);
			
            if(count($agendas)){
			?>
			<script type="text/javascript">
			<!--
				$(function() {
				    $('.meclisTabButon').bind('click', function (e) {
				        $("#activeMt").val($(this).attr("rel"));
				    });
				    //var tIn=setTimeout("nextAgendaSelect()",30000);
				});
				function nextAgendaSelect()
				{
					var selectedAgenda=$("#activeMt").val();
					var selectedIter=$("#agendaIter-"+selectedAgenda).val();
					
					var nextIter=1;
					if(selectedIter!=7)
						nextIter=parseInt(selectedIter)+1;
					nextAgenda=$('input[value="'+nextIter+'"]').attr("rel");
					$('a[href="#agenda-'+nextAgenda+'"]').tab("show");
					$("#activeMt").val(nextAgenda)
					tIn=setTimeout("nextAgendaSelect()",30000);
				}
			//-->
			</script>
			<div class="roundedcontent">
				<h1>
					<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> <a href="/mchange" class="fnc">Türkiye</a> Meclisi 
				</h1>
				<input type="hidden" value="<?=$agendas[0]->ID?>" id="activeMt" />
				<div id="meclisTablari" class="tabs-content">
				<?php 
				$kont=0;
				foreach($agendas as $agenda){                   
                    
                    $db->setQuery('SELECT av.vote, COUNT(*) AS votecount FROM agendavote AS av WHERE av.agendaID='.$db->quote($agenda->ID).' GROUP BY av.vote ORDER BY av.vote');
                    $voted = $db->loadObjectList('vote');
                    $totalvote = 0;
                    if(count($voted)) foreach($voted as $v) $totalvote += $v->votecount;
                    

                    $optioans = array(1=>'Kesinlikle Katılıyorum',
                                     2=>'Katılıyorum',
                                     3=>'Kararsızım',
                                     4=>'Katılmıyorum',
                                     5=>'Kesinlikle Katılmıyorum');
                    
                    $ocolors = array(1=>'#88b131',
                                     2=>'progress-success',
                                     3=>'progress-warning',
                                     4=>'progress-danger',
                                     5=>'#ff6f32');
                                                                       
                    
                    $statistic_Line 	= '';
                    
                    foreach(config::$votetypes as $key=>$option){
                        
                        //oy oranini hesapla
                        if(array_key_exists($key, $voted))
                            $percent = floor( ($voted[$key]->votecount * 100) / $totalvote );
                        else 
                            $percent = 0;
                        
                        //oylanmismi?
                        $checked =  $agenda->myvote==$key?' checked="checked"':'';
						$statistic_Line.='
							<tr>
								<td class="quest">
								<label class="checkbox" for="parliament_choose_'.$agenda->ID.'_'.$key.'">'.$option.'</label>
								</td>
								<td>
								<input type="radio" name="poll_choose_'.$agenda->ID.'" id="parliament_choose_'.$agenda->ID.'_'.$key.'" class="parliamentoption" '.$checked.' style="float:right;"/>
								</td>
								<td>%'.$percent.'</td>
								<td class="bars"><div style="margin-bottom: 5px;" class="progress  '.$ocolors[$key].'"><div style="width: '.$percent.'%" class="bar"></div></div></td>
							</tr>
						';                
                      
                        if($agenda->diID>0){
                            $agendatitle = '<a href="/di/'.$agenda->diID.'">'.$agenda->title.'</a>';
                        } else {
                            $agendatitle = $agenda->title;
                        }
                        
                        
                        
                    }      
                    $class="tab-pane fade in";
                    if($kont==0)
                    {
                    	$class="tab-pane active fade in";
                    	$kont=1;
                    }     
					
					if($agenda->mecliseAlan>0)
					{
						$profileClass=new profile;
						$viaTxt='<a href="/profile/'.$agenda->mecliseAlan.'">'.$profileClass->get_name($agenda->mecliseAlan).'</a> üzerinden ';
					}
					else
					{
						$viaTxt="";		
					}  
            	?>   
                <div id="agenda-<?=$agenda->ID?>" class="<?=$class?>"> 
				<p style="height: 80px;"><?=$agendatitle?></p>
				<p class="righttext"><?=$viaTxt?><a href="/profile/<?=$agenda->deputyID?>"><?=$agenda->deputyname?></a></p>
				<hr>
				<div class="alert alert-success" id="vote-save-<?=$agenda->ID?>" style="display:none;">
					<p style="margin:0;">Oyunuz başarı ile kaydedildi.</p>
				</div>
				<table class="polltable">
				<?=$statistic_Line?>
				</table>
				
				</div>
				<?php 
				}
				?>
				</div>
				<div class="pagination">
				
		        <ul >
		        <?php 	
		        $i=0;
		        foreach($agendas as $agenda){   
		        	$i++;
		        	$active="";
		        	if($i==1)
		        	$active=" class='active' ";
		          echo '<li '.$active.'>
		          			<input type="hidden" id="agendaIter-'.$agenda->ID.'" rel="'.$agenda->ID.'" value="'.$i.'" />
		          			<a href="#agenda-'.$agenda->ID.'" rel="'.$agenda->ID.'" class="meclisTabButon" data-toggle="tab" >'.$i.'</a>
		          		</li>';
		       	}
		        ?>
		        </ul>
	      </div>
	      	<?php 	$deputyLink="";
	      			if($model->profile->deputy==1)
	      				$deputyLink="#tasari";
	      	?>
		  		<button class="btn100 btn-danger margin5 tooltip-bottom" data-original-title="" onclick="location.href='/archive<?=$deputyLink?>';">MECLİSE GİRİŞ</button>
			
			
			<? 
            }   
        }
    }
?>
