<?php
    class myfollowers_block extends control{
        
        public function block(){
            global $model, $db, $l;
            
            if($model->userID<1) return;
            
            
         
            
            $myID = intval( $model->user->ID );
            
            $SELECT = "SELECT DISTINCT f.followingID, p.*";
            $FROM   = "\n FROM #__follow AS f";
            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followerID";
            $WHERE  = "\n WHERE f.followingID=".$db->quote($myID);
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ";//"ORDER BY s.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            if(count($rows)){
?>

<div class="leftbox">
        <!-- leftbox start -->
        <div class="leftbox-head">
          <h3>Takip Edenler</h3>
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