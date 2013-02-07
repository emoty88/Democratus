<?php
    class followings_block extends control{

        public function block(){
            global $model, $db, $l;
            
            if($model->profileID<1) return;

            $myID = intval( $model->profileID );
            
            $SELECT = "SELECT f.followerID, p.*";
            $SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
            
            $FROM   = "\n FROM #__follow AS f";
            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followingID";
            $WHERE  = "\n WHERE f.followerID=".$db->quote($myID);
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ORDER BY f.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            
            $db->setQuery('SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
            $count = intval( $db->loadResult() );
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            if(count($rows)){     
?>

                        <!-- Following [Begin] -->
                        <div class="box" id="following">
                            <span class="title" style="float: left">Takip ettikleri</span>
                            <span class="all_users"><a href="/followings/<?=$model->profileID?>">Hepsini Gör (<?=$count?>)</a></span>
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
                        <!-- Following [End] -->

<?php  
            } else {
?>                
                        <!-- Following [Begin] -->
                        <div class="box" id="following">
                            <span class="title" style="float: left">Takip ettikleri</span>
                            <div class="clear"></div>
                            <div class="line"></div>
                            <span>Takip listende kimse yok.</span>
                            <a href="/search/">Haydi şimdi takip edebileceğin kişileri bulalım!</a>
                        
                        </div>
                        <!-- Following [End] -->
<?php
            }         
        }
    }
?>