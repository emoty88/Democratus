<?php
    class wall_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
            
            if($model->userID<1)
                return $this->notloggedin();
            
            
            //echo 'home';
            $this->shareform();
            
            $followerID = intval( $model->user->ID );
            
            $SELECT = "SELECT s.*";
            $FROM   = "\n FROM #__share AS s";
            $JOIN   = "\n LEFT JOIN #__follow AS f ON f.followingID = s.profileID";
            //$JOIN   = "\n ";
            $WHERE  = "\n WHERE (s.profileID = " . $db->quote($followerID) . " OR f.followerID=".$db->quote($followerID).")";
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ORDER BY s.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            echo '<h3>' . $db->_sql . '</h3>';
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1);
            
            if(count($rows)){
                foreach($rows as $row){
                    echo '<div id="share'.$row->ID.'" class="share">';
                    echo '<p>' . date('Y-m-d H:i:s', strtotime($row->datetime)) . ' - ' .  $row->title . '</p>';
                    echo '<span class="likebox">';
                    echo '<a href="#regard" rel="' . $row->ID . '" class="shareregardbutton">'.$l['regard'].'</a> | <a href="#appreciate" rel="' . $row->ID . '" class="shareappreciatebutton">'.$l['appreciate'].'</a> ';
                    echo '</span>';
                    echo '<div class="sharecommentsbox" rel="'.$row->ID.'">&nbsp;</div>';
                    echo '<form>';
                    echo '<textarea name="sharecomment'.$row->ID.'"></textarea>';
                    
                    echo '<input type="button" value="yorumla" class="sharecommentbutton" rel="'.$row->ID.'">';
                    echo '<form>';
                    
                    echo '</div>';
                    
                }
            } else {
                echo '<p>not found</p>';
            }
            
            
            
        }
        
        public function notloggedin(){
            echo 'not logged in';
        }
        
        public function shareform(){
?>

    <form action="/share/" method="post">
      <input type="text" name="title" />
      <input type="submit" value="share">
    </form>

<?php
        }
    }
?>