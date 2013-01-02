<?php
    class propostal_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
            
            if($model->userID<1)
                return $model->redirect('/wellcome');

            $model->view = 'propostal';
            $model->initTemplate('v2', 'propostal');
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            //$model->addScript($model->pluginurl . 'election.js', 'election.js', 1);
            
            if($model->profile->deputy <=0){
                echo '<h3>Vekil olmadığınız için meclise giremezsiniz!</h3>';
                return;
            }
            //echo $model->profile->deputy;
            
?>


                        <div id="share_idea" class="box">
                            <span class="title_icon" style="width: 290px; float: left">Tasarı Yaz Gündemi Belirle</span>
                            <span class="character"><span class="number">200</span> Karakter</span>
                            
                            <div class="clear"></div>
                            <p>Vekil olarak tasarılarınızı yazın, diğer tasarıları oylayın ve bir sonraki günün gündemini siz belirleyin. Sistem her gece yarısı eski tasarıları silmektedir.</p>
                            <form method="post" onsubmit="return false;">
                                <div class="textarea"><textarea maxlength="200" id="pptext"></textarea></div>
                                <button id="ppsend">Gönder</button>
                                <div class="clear"></div>
                            </form>
                        </div>

<?php            
            
            
            
            $SELECT = "SELECT DISTINCT pp.*, pr.name, pr.image";
            $FROM   = "\n FROM propostal AS pp";
            $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID = pp.deputyID";
            $WHERE  = "\n WHERE pp.datetime>" . $db->quote(date('Y-m-d H:i:s', LASTELECTION)) ;
            $WHERE .= "\n AND pp.status>0";
            $WHERE .= "\n AND pp.used<1";
            $ORDER  = "\n ORDER BY pp.ID DESC";
            $LIMIT  = "\n LIMIT " . config::$mydeputylimit;
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            //echo '<h3 onclick="javascript:ppadd();">Gündem Yaz</h3>';
            //echo '<button onclick="javascript:ppadd();">Gündem Yaz</button>';
            
            //echo '<h3>Şu anki Gündemler</h3>';
            //echo '<div id="propostals" class="propostals">';
             
            if(count($rows)){
?>
                        <div class="box" id="vote_agenda">
                            <span class="title_icon">Oylanan Gündemler</span>
                            <div class="line_center"></div>
<?php
                $i = 0;
                foreach($rows as $row){
                    $i++;
                    
                    
?>

                            
                            <div class="box" style="background-color: #fdfbfb">
                                <div class="idea">
                                    <img src="<?=$model->getProfileImage($row->image, 60, 60, 'cutout')?>" class="image" />
                                    <div class="content" style="width: 320px">
                                        <div class="top">
                                            <span class="name"><?=$i?> - <?=$row->name?><a name="'.$row->ID.'">&nbsp;</a></span>
<?php
    //if($row->deputyID != $model->profileID){
    $result = propostal::getbuttons($row->ID);
?>                                            
                                            <span class="statistic_tip ppbuttons" id="ppbuttons<?=$row->ID?>">
                                                <?=$result['html'];?>
                                            </span>
<?php
    //}
?>  

                                            <div class="clear"></div>
                                        </div>
                                    
                                        <div class="line_center"></div>
                                        <div class="bottom">
                                            <p>
                                                <?=$row->spot?>
                                                <span style="display: block; margin: 10px 0 10px 0; font-style: italic"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
<?php                    
                }
?>
                            
                            <div class="clear"></div>
                        </div>
<?php                
            } else echo 'Hiç yok!';
            //<div class="page_more"></div>
 
        }
    }
?>