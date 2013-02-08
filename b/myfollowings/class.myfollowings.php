<?php
    class myfollowings_block extends control{
        
        public function block(){
            global $model, $db, $l;
            
            if($model->userID<1) return;

            //$myID = intval( $model->user->ID );
            
            $SELECT = "SELECT f.followerID, p.*";
            $SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
            
            $FROM   = "\n FROM #__follow AS f";
            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followingID";
            $WHERE  = "\n WHERE f.followerID=".$db->quote($model->profileID);
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ";//"ORDER BY s.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            if(count($rows)){
?>

<div class="leftbox">
        <!-- leftbox start -->
        <div class="leftbox-head">
          <h3>Takip Ettiklerim</h3>
        </div>
        <div class="leftbox-body">
          <div class="leftbox-subhead">Son olaylar</div>
<?php
        foreach($rows as $row){
?>
          <!--profilebox -->
          <div class="leftbox-profilebox"><img src="<?=$model->getProfileImage($row->image, 60, 60, 'cutout')?>" width="60" height="60" alt="" />
            <div class="leftbox-profile-body">
               <a href="/profile/<?=$row->ID?>">
               <?=$row->name?>
               </a>
<?php
    if($row->mydeputy>0){
        echo '<p onclick="javascript:deputyremove('.$row->ID.')">vekilim olmasın</p>';
    } else {
        echo '<p onclick="javascript:deputyadd('.$row->ID.')">vekilim olsun</p>';
    }
?>
               
               
               
            </div>
            <br class="clearfix" />
          </div>
          <!--profilebox END-->                    
<?php
                }
?>                    
        </div>
        <div class="leftbox-footer">&nbsp;</div>
      </div>
      <!-- leftbox end -->
<?php

            } else {
                //echo '<p>not found</p>';
            }
            
            
            
        }
    }
?>