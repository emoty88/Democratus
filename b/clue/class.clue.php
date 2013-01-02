<?php
    class clue_block extends control{
        
        public function block(){
            global $model, $db;
            if(0&&$model->profileID!=1001){
	            if($model->newDesign)
	            { 
?>
					<!-- Clue(?) [Begin] -->
	            	<div class="roundedcontent">
						<h1><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Biliyor Musunuz?</h1>
						<p>İnzivaya çekilmek isteyen Tevfik Fikret, 
	                       çizimlerini kendi yaptığı, 
	                       Robert Koleji yamacındaki 3 katlı ahşap evine Farsça “bülbül yuvası” anlamına gelen 
	                       Aşiyan ismini koymuştur ki bu isim zamanla o bölgenin ismi olmuştur.</p>
					</div>
					<!-- Clue(?) [End] -->
<?php 
	            }
	            else 
	            {
?>
					<!-- Clue(?) [Begin] -->
	                <div id="clue" class="box">
	                	<span class="title_icon">Bunları biliyor muydunuz?</span>    
	                    <div class="line"></div>
	                    <p>İnzivaya çekilmek isteyen Tevfik Fikret, 
	                            	çizimlerini kendi yaptığı, 
	                            	Robert Koleji yamacındaki 3 katlı ahşap evine Farsça “bülbül yuvası” anlamına gelen 
	                            	Aşiyan ismini koymuştur ki bu isim zamanla o bölgenin ismi olmuştur.</p>
					</div>
	                <!-- Clue(?) [End] -->
<?php
	            }

            } else {
                
                
                
                
                
            $SELECT = "SELECT *";
            $FROM   = "\n FROM clue";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE status>0";
            $GROUP  = "\n ";
            $ORDER  = "\n ORDER BY ID DESC";
            $LIMIT  = "\n LIMIT 7";
   
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();                
                
                
            if(count($rows)){
            	if($model->newDesign)
            	{
?>
                        <!-- Clues [Begin] -->
						<div class="roundedcontent">
							<h1><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Biliyor Musunuz?
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
								?>
								
								    <div class="<?=$clasA?>" id="<?=$row->ID?>"> 
						              <p style="height: 120px;"><?=$row->clue?></p>
						            </div>      
								<?php
								}
								?> 
		                 	</div>                  
                        </div>
                        <!-- Clues [End] -->
<?php	
            	}
            	else
            	{
?>
                        <!-- Clues [Begin] -->
                        <div id="clue_slider" class="box">
                            <span class="title_icon">Bunları biliyor muydunuz?</span>    
                            <div class="line"></div>
                            
                            <div id="clue_slides">
                                <div class="slides_container">
<?php
        foreach($rows as $row){
?>
                                  <div class="slide">
                                        <p><?=$row->clue?></p>
                                        <div class="line"></div>
                                        <div class="bottom">&nbsp;</div>
                                        <div class="clear"></div>
                                    </div>
<?php
                }
?>                    
                                </div>
                            </div>
                            
                        </div>
                        <!-- Clues [End] -->
<?php
            	}
            } else {
                //echo '<p>not found</p>';
            }    
  
            }
            
            
        }
    }
?>
