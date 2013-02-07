<?php
/*
  
                          <!-- Advertise [Begin] -->
                        <div class="advertise">
                        <iframe width="250" height="250" src="http://www.youtube.com/embed/WPlocAOMq-I" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <!-- Advertise [End] -->
*/

    class adversite_block extends control{
        
        
        public function block(){
            global $model, $db, $l;
            
            
            
            //if(filter_input(INPUT_SERVER, 'REMOTE_ADDR')!='78.189.30.253') 
                return $this->block_old();
            
            
            $SELECT = "SELECT DISTINCT di.*, sharer.ID AS sharerID, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, sharer.deputy AS deputy";
            $SELECT.= ", count(dilike.ID) AS popularite, (SELECT count(ID) FROM dilike AS dl2 WHERE dl2.diID=di.ID AND dl2.dilike2>0 ) AS popularite2";
            //$SELECT = "SELECT di.*, count(dilike.ID) AS popularite";
            $FROM   = "\n FROM dilike, di";
            $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";
            //$JOIN   = "\n ";
            
            $WHERE  = "\n WHERE di.datetime > DATE_ADD(NOW(), INTERVAL -1 DAY)";
            $WHERE .= "\n AND di.ID = dilike.diID AND dilike.dilike1>0";
            $WHERE .= "\n AND di.status>0";
            
            $GROUP  = "\n GROUP BY dilike.diID";
            $ORDER  = "\n ORDER BY popularite DESC";
            $LIMIT  = "\n LIMIT 5";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            
            
            if(count($rows)){
?>
                        <!-- Popular Di [Begin] -->
                        <div id="video_slide" class="box">
                            <span class="title_icon">Ses Getirenler</span>    
                            <div class="line"></div>
                            
                            <div id="video_slides">
                                <div class="slides_container">
<?php
        foreach($rows as $row){
?>
                                <div class="slide">
                                        <p><a href="/di/<?=$row->ID?>">“<?=$model->splitword( $row->di,30 )?>”</a></p>
                                        <div class="line"></div>
                                        <div class="bottom">
                                            <div class="name"><a href="/profile/<?=$row->sharerID?>"><?=$row->sharername?></a></div>
                                            <div class="statistic_tip2">
                                                <span class="box_1" rel="Takdir"><?=$row->popularite?></span>
                                                
                                            </div>
                                            <br />
                                            <span class="time"><?=$row->datetime?></span>
                                            
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                  
<?php
                }
?>                    
                                </div>
                            </div>
                            
                        </div>
                        <!-- Popular Di [End] -->
<?php

            } else {
                //echo '<p>not found</p>';
            }
            
            
            
        }
        
        
        
        
        public function block_old(){
            global $model;
?>
 
 
                        <!-- Advertise [Begin] -->
                        <div class="advertise">
						<a href="/my/account">
							<img src="<?=$model->templateurl?>static/image/advertise/demoface2.jpg" />
						</a>
                        </div>
                        <!-- Advertise [End] -->
<?php
        }
        
        
    }
?>
