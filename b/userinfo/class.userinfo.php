<?php
    class userinfo_block extends control{
        
        public function block(){
            global $model, $db, $l;      
?>
                        <!-- User Info [Begin] -->                        
                        <div class="box" id="user_info">
                                <span class="name"><?=$model->profile->name?></span>
                                
                                <div class="content">
                                    <!-- Profile Image -->
                                    <a href="/my/photos"><img src="<?=$model->getProfileImage($model->profile->image, 100,100, 'cutout')?>" width="100" height="100" /></a>
                                    <div class="mini_info">
                                        <p><?=$model->profile->education?></p>
                                    </div>
                                    <!-- Navigation -->
                                    <ul class="nav">
<?php
    $notice_count = intval( $this->get_notice_count($model->profileID) );
    if($notice_count>0){
        $notice_count = ' ('.$notice_count.') ';
    } else {
        $notice_count = '';
    }
    
?> 
                                        <a href="/notice"><li>Olaylar<?=$notice_count?></li></a>
                                        <a href="/my/profile"><li>Profili Güncelle</li></a>
                                        <a href="/my/privacy"><li>Gizlilik Ayarları</li></a>
                                        <a href="/my/account"><li>Hesap Ayarları</li></a>                                        
                                        <a href="/user/logout"><li>Çıkış</li></a>
                                    </ul>
                                    
                                    <div class="clear"></div>
                                    
                                    <p class="info"><?=str_replace("\n", "<br />\n",$model->splitword( $model->profile->motto , 28));?></p>
                                    
                                    <div class="line_1"></div>
                                    <div class="line_2"></div>
                                    
                                    <table border="0" cellpadding="0" cellspacing="0" class="statistic">
                                        <tr>
                                            <td><span><?=$this->get_di_count($model->profileID);?></span> Ses</td>
                                            <td class="td_center"><span><?=$this->get_dilike1_count($model->profileID);?></span> Takdir</td>
                                            <td><span><?=$this->get_dilike2_count($model->profileID);?></span> Saygı</td>
                                        </tr>
                                    </table>
                                
                                </div>
                                
                                <div style="margin-top: 5px;"class="clear"></div>
                        </div>
                        <!-- User Info [End] -->
<?php
        }

        public function get_di_count($profileID){
            global $model, $db;
            $db->setQuery('SELECT COUNT(ID) FROM di WHERE profileID='.$db->quote($profileID).' AND status>0');
            $result = $db->loadResult();
            if( $result ) 
                return intval( $result );
            else 
                return null;
        }
    
        public function get_dilike1_count($profileID){
            global $model, $db;
            $db->setQuery('SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID='.$db->quote($profileID).' AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>'.$db->quote($profileID).' AND di.status>0');
            $result = $db->loadResult();
            if( $result ) 
                return intval( $result );
            else 
                return null;
        }
        
        public function get_dilike2_count($profileID){
            global $model, $db;
            $db->setQuery('SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID='.$db->quote($profileID).' AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>'.$db->quote($profileID).' AND di.status>0');
            $result = $db->loadResult();
            if( $result ) 
                return intval( $result );
            else 
                return null;
        }
        
        
        public function get_notice_count($profileID){
            global $model, $db;
            
            $SELECT = "SELECT count(*) FROM (SELECT distinct n.ID3";
            $FROM   = "\n FROM notice AS n";
            $JOIN   = "\n JOIN profile AS p ON p.ID=n.fromID";
            $WHERE  = "\n WHERE n.profileID=".$db->quote(intval( $profileID ));
            $WHERE .= "\n AND n.datetime>".$db->quote( asdatetime( $model->profile->noticetime, 'Y-m-d H:i:s' ));
            $WHERE .= "\n AND n.ID3 UNION ";
            $WHERE .= " SELECT distinct n.ID2 FROM notice AS n JOIN profile AS p ON p.ID=n.fromID "; 
            $WHERE .= " WHERE n.profileID=".$db->quote(intval( $profileID ));
            $WHERE .= " AND n.datetime>".$db->quote( asdatetime( $model->profile->noticetime, 'Y-m-d H:i:s' ));
            $WHERE .= " AND n.ID3 IS NULL ) Say";
            //$WHERE .= "\n AND n.datetime>".$db->quote(  date('Y-m-d H:i:s', time()-60*60*60) );
            //$ORDER  = "\n ORDER BY n.ID DESC";
            $LIMIT  = "\n "; 
            //echo $WHERE;
			//Burayar Açıklamayı yaz

            //echo $SELECT . $FROM . $JOIN . $WHERE  . $LIMIT;
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE  . $LIMIT);
            //die;
            //$db->setQuery('SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID='.$db->quote($profileID).' AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>'.$db->quote($profileID).' AND di.status>0');
            $result = $db->loadResult();
            if( $result ) 
                return intval( $result );
            else 
                return null;
        }        
        
    }
?>