<?php
    class followers2_block extends control{
        
        public function block(){
            global $model, $db, $l;
            
            if($model->profileID<1) return;
            
            
         
            
            $myID = intval( $model->paths[1] );
            
            $db->setQuery("SELECT ID, showfollowers FROM profile WHERE ID = " . $db->quote($myID) . " AND status>0" );
            $row = null;
            if($db->loadObject($row)){
                if(!profile::isallowed($row->ID, $row->showfollowers)) {
                    return;
                }
            } else {
                return;
            }
            
            $SELECT = "SELECT DISTINCT f.followingID, p.*";
            $FROM   = "\n FROM #__follow AS f";
            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followerID";
            $WHERE  = "\n WHERE f.followingID=".$db->quote($myID);
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ORDER BY f.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            
            $db->setQuery('SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
            $count = intval( $db->loadResult() );
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            
            
            if(count($rows)){
?>
                        <!-- Followers [Begin] -->
                        <div class="box" id="followers">
                            <span class="title" style="float: left">Takipçiler</span>
                            <span class="all_users"><a href="/followers/<?=$myID?>">Hepsini Gör (<?=$count?>)</a></span>
                            <div class="clear"></div>
                            <div class="line"></div>

                            <ul class="users_small">
<?php
        foreach($rows as $row){
?>
                                <li>
                                    <a href="/profile/<?=$row->ID?>"><img src="<?=$model->getProfileImage($row->image, 40, 40, 'cutout')?>" alt="" />
                                    <span><?=$model->shortname( $row->name )?></span></a>
                                </li>                  
<?php
                }
?>                    
                            </ul>
                        </div>
                        <!-- Followers [End] -->
<?php

            } else {
                //echo '<p>not found</p>';
            }
            
            
            
        }
    }
?>