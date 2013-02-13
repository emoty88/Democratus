<?php
    class populardies_block extends control{
        
        public function block(){
        	global $model;
			
			$c_pVoice = new popularvoice;
			$voices=$c_pVoice->get_popularVoice();
			?>
			<!-- Bileşen -->
				<section class="bilesen kontroller_var" id="popularVoice_gadget">
					<header>
						<hgroup>
							<h1>Ses Getirenler</h1>
						</hgroup>
					</header>
					<div class="bilesen_icerigi dolgu_1 list_carousel">
						<ul id="turkiye-meclisi">
							<?php 
								foreach($voices as $v)
								{
									?>
									<li>
										<img src="<?=$v->sImage?>" alt="<?=$v->sName?>-mini-profile-img">
                                                                                <h5><a href="<?=$v->sPerma?>"><?=$v->sName?></a> </h5>
                                                                                    <?php if($model->profile->deputy>0 and proposal::get_p2PoroposalCount()<3 and proposal::check_popular2proposal($v->ID)): ?>
                                                                                        
                                                                                    <i title="Tasarı olarak ata"  class="atolye15-ikon-yanitla atolye15-ikon-24 tasari-ata" onclick="populardiToPopular(<?=$v->ID?>,this)"></i>
                                                                                     <?php endif; ?></h5>
										<p>
											<a href="/voice/<?=$v->ID?>" style="text-decoration: none; font-weight: normal;">
												<?=$v->voice?>
											</a>
										</p>
                                            
                                    </li>
									<?
								}
							?>
						</ul>	
						<section class="sd-bottom">
							<div class="pagination" id="turkiye-meclisi-pag"></div>
						</section>
					</div>
						<aside class="kontroller">
						<a href="/popularvoice" class="sayfaya_git" title="Tümünü görüntüle &rarr;">
							<i class="atolye15-ikon-ok atolye15-ikon-24"></i>
						</a>
					</aside>
				</section>
			<?
		
		}
        public function block_old(){
            global $model, $db, $l;
            
            //if($model->userID<1) return;
            

            
            //(takdir/ (takdir+saygı)) -(şikayet/2 )
            $SELECT = "SELECT DISTINCT di.*, sharer.ID AS sharerID, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, sharer.deputy AS deputy";
            $SELECT.= ", count(dilike.ID) AS toplamoy, sum(dilike.dilike1) AS takdir, sum(dilike.dilike2) AS saygi";
            //$SELECT.= ", (SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID ) AS complaint";
           
            //$SELECT.= ",( sum(dilike.dilike1) - sum(dilike.dilike2) - ((SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID )*2))  AS popularite";
            $SELECT.= ", (sum(dilike.dilike1)*3+sum(dilike.dilike2)*1+(SELECT count(ID) FROM di AS diredi WHERE diredi.redi=di.ID )*10+(SELECT count(ID) FROM dicomment AS dicom WHERE dicom.diID=di.ID )*1) popularite";
            $FROM   = "\n FROM dilike, di";
            $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";            
            //$WHERE  = "\n WHERE di.datetime > DATE_ADD(NOW(), INTERVAL -1 DAY)";
            $WHERE = "\n WHERE di.ID = dilike.diID"; // AND
            $WHERE .= "\n AND di.status>0";
            $WHERE .= "\n AND di.popularstatus>0";
            $GROUP  = "\n GROUP BY dilike.diID";
            $ORDER  = "\n ORDER BY popularite DESC";
            $LIMIT  = "\n LIMIT 7";
            // Online Siteye atma local de çalışması için 
            //echo $SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT;
			//die;
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
          
        
         if(count($rows)){
            if($model->newDesign){ //new design
            	?>
            		<p></p>
            		<hr style="border:1px solid #DDDDDD;"/>
					<h1><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Ses Getirenler
					<div class="btn-group">
						<ul class="nav nav-tabs" id="tabs">
							<?php 
							$kont=0;
							foreach($rows as $row){
							$clasA="";
							if($kont==0)
							{
								$clasA=' class="active"';
								$kont=1;
							}
							?>
						        <li <?=$clasA?> ><a data-toggle="tabs" href="#<?=$row->ID?>">-</a></li>
							<?php 
							}
							?>
				        </ul>
				  	</div>
				  
					</h1>
					<div id="myTabsContent" class="tabs-content">
						<?php
						$kont=0;
						foreach($rows as $row){
							$clasA="tab-pane fade in";
							if($kont==0)
							{
								$clasA="tab-pane fade active in";
								$kont=1;
							}
							if($model->profile->deputy>0)
							{
								if(proposal::check_popular2proposal($row->ID))
								{
									$mecliseTasiTxt=' <a href="javascript:populardiToPopular('.$row->ID.');">Taslak Olarak Ata</a>';
								}
								else {
									$mecliseTasiTxt=' <a href="/archive#tasari">Taslak Olarak Atandı</a>';
								}
							}
							else {
								$mecliseTasiTxt='';
							}
						?>
							<div class="<?=$clasA?>" id="<?=$row->ID?>"> 
				              <p style="height: 70px;"><i><a href="/di/<?=$row->ID?>">“<?=$model->splitword( $row->di,30 )?>”</a></i></p>
							  <p class="righttext">
							  	<a href="/profile/<?=$row->sharerID?>"><?=$row->sharername?></a>
							  	-
							  	<?=$mecliseTasiTxt?>
							  </p>
				            </div>      
						<?php
						}
						?> 
                 	</div>
				</div>
            	<?php 
            }

            } else {
                //echo '<p>not found</p>';
            }
            
        
            
        }
    }
?>
