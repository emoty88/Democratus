<?php
    class followings_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
            
            if($model->userID<1) return;
            
            if($model->paths[1] == 'ajax') return $this->ajax();
            
            $model->initTemplate('v2', 'profilelist');
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1);
            $model->addScript($model->pluginurl . 'followings.js', 'followings.js', 1);

            $myID = intval( $model->paths[1] );

            if($myID<1) return;
            
            $SELECT = "SELECT DISTINCT f.followerID, p.*, f1.followerstatus, f1.followingstatus";
            $SELECT.= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
            $FROM   = "\n FROM follow AS f";
            $JOIN   = "\n JOIN profile AS p ON p.ID=f.followingID";
            $JOIN  .= "\n LEFT JOIN follow AS f1 ON f1.followingID=p.ID AND f1.followerID=".intval( $model->profileID );
            $WHERE  = "\n WHERE f.followerID=".$db->quote($myID);
            $WHERE .= "\n AND f.followerstatus>0";
            $WHERE .= "\n AND p.status>0";
            $ORDER  = "\n ORDER BY p.ID DESC";
            //$LIMIT  = "\n LIMIT $start, $limit";
            $LIMIT  = "\n ";
            
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            //echo $db->_sql;
            
            $rows = $db->loadObjectList();
            
            if(count($rows)){
?>
                        <div id="follow_result" class="box">
                            <span class="title_icon">Takip Ettikleri</span>    
                            <div class="line_center"></div>
<?php
        foreach($rows as $row){
            
                    if($model->profileID>0){
                        if($row->followingstatus>0){
                            $follow = 'hide';
                            $unfollow = '';
                        } else {
                            $follow = '';
                            $unfollow = 'hide';
                        }
                        
                        if($row->ID==$model->profileID){
                            $follow_button = '<span class="you">Sensin!</span>';
                        } else {
                            $follow_button = '<span id="follow'.$row->ID.'" class="follow '.$follow.'" rel="'.$row->ID.'">Takip Et</span>
                                              <span id="unfollow'.$row->ID.'" class="unfollow '.$unfollow.'" rel="'.$row->ID.'">Takip Etme!</span>
                                              ';
                        }
                    } else {
                        $follow_button = '';
                    }
                    
                    $html = '
                              <div class="result" id="profile'.$row->ID.'">
                                <div class="image"><a href="/profile/'.$row->ID.'"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'" style="width: 50px" /></a></div>
                                <div class="content">
                                    <div class="head">
                                        <span class="username"><a href="/profile/'.$row->ID.'">'.$row->name.'</a></span>
                                        <span class="statistic">
                                            <span>'.$row->di_count.' Ses</span>
                                            <span>'.$row->dilike1_count.' Takdir</span>
                                            <span>'.$row->dilike2_count.' Saygı</span>
                                            '.$follow_button.'
                                        </span>
                                    </div>
                                    <p>'.$row->motto.'</p>
                                    <span class="mini_about">'.$row->hometown.'</span>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                            ';
                            echo $html;

                }
?>                    
                        </div>
<?php

            } else {
                echo '<p>hiç bulunamadı</p>';
            }
        }
        
        public function ajax(){
            global $model;
            $model->mode = 0;
            $method = (string) 'ajax_' . $model->paths[2];
            if(method_exists($this, $method )){
                $this->$method();
            } else {
                
            }  
        }
        
        public function ajax_more(){
            global $model, $db;
            
            $followerID = intval( $model->profileID );            
            

            $keyword    = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
            $start      = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
            //$limit      = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);

            $found = $this->find($keyword, $start);
            if($found['count']>0){
                $found['html'].='<input type="button" id="followermore" value="more" rel="'.$found['nextstart'].'" />';
            } else {
                
            }
            
                $response['result'] = 'success';
                $response['ids'] = $found['ids'];
                $response['html'] = $found['html'];
                $response['count'] = $found['count'];
            
            echo json_encode($response);
        }        
        
        public function getlist($keyword, $start=0, $limit=7){
            global $model, $db, $l;
            $keyword = filter_var($keyword, FILTER_SANITIZE_STRING);
            $start = intval($start);
            if($start<0) $start = 0;
            
            $limit = intval($limit);
            if($limit<1) $limit = 7;
            
            $followerID = intval( $model->profileID );            
            
            
            $SELECT = "SELECT p.*, f.followerstatus, f.followingstatus";
            $SELECT.= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
            $FROM   = "\n FROM profile AS p";
            $JOIN   = "\n LEFT JOIN follow AS f ON f.followingID = p.ID AND f.followerID=" .$db->quote($followerID);
            $WHERE  = "\n WHERE p.name LIKE '%". $db->escape( $keyword )."%'";
            $WHERE .= "\n AND p.status>0";
            $ORDER  = "\n ORDER BY p.ID DESC";
            $LIMIT  = "\n LIMIT $start, $limit";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            //echo $db->_sql;
            //die;
            
            $rows = $db->loadObjectList();

            if(count($rows)){
                $i=0;
                $ids = array();
                $html = '';
                foreach($rows as $row){
                    $i++;
                    $ids[] = $row->ID;
                    //$profileinfo = profile::getinfobyrow($row);
                    
                    if($model->profileID>0){
                        if($row->followingstatus>0){
                            $follow = 'hide';
                            $unfollow = '';
                        } else {
                            $follow = '';
                            $unfollow = 'hide';
                        }
                        
                        if($row->ID==$model->profileID){
                            $follow_button = '<span class="you">Sensin!</span>';
                        } else {
                            $follow_button = '<span id="follow'.$row->ID.'" class="follow '.$follow.'" rel="'.$row->ID.'">Takip Et</span>
                                              <span id="unfollow'.$row->ID.'" class="unfollow '.$unfollow.'" rel="'.$row->ID.'">Takip Etme!</span>
                                              ';
                        }
                    } else {
                        $follow_button = '';
                    }
                    
                    $html .= '
                              <div class="result" id="profile'.$row->ID.'">
                                <div class="image"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'" style="width: 50px" /></div>
                                <div class="content">
                                    <div class="head">
                                        <span class="username"><a href="/profile/'.$row->ID.'">'.$row->name.'</a></span>
                                        <span class="statistic">
                                            <span>'.$row->di_count.' Ses</span>
                                            <span>'.$row->dilike1_count.' Takdir</span>
                                            <span>'.$row->dilike2_count.' Red</span>
                                            '.$follow_button.'
                                        </span>
                                    </div>
                                    <p>'.$row->motto.'</p>
                                    <span class="mini_about">'.$row->hometown.'</span>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                            ';
                }
                
                $response['html'] = $html;
                $response['count'] = count($rows);
                $response['start'] = $row->ID;
                $response['ids'] = $ids;
                $response['nextstart'] = $start + $i;
                
            } else {
                $response['html'] = 'hiç yok!';
                $response['count'] = 0;
                $response['start'] = 0;
                $response['ids'] = array();
                $response['nextstart'] = $start;
            }            
            return $response;
        }
    }
?>