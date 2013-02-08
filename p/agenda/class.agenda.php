<?php
    class agenda_plugin extends control{
        
        public function main(){
            global $model, $db;
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'v2';
            $model->view = 'home';
            
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript($model->pluginurl . 'agenda.js', 'agenda.js', 1 );
            
            
?>
                        <div id="parliament">
                            <div class="main_title"></div>
                        
                    
                            
                            <div id="parliament_slider">
                                <div class="slides_container">
                                    
                                    <!-- Slide BOX 1 -->
                                    <div class="slide_box">
                                        <div class="head">
                                            <div class="image"><img src="static/image/users/medium/1.jpg" /></div>
                                            <div class="qoute">
                                                <blockquote>
                                                    <p>Türkiye, sorunları sıfırlama politikasında bazı puanlar kazandı. Fakat sorunun duvarı yükseldikçe, bu siyaseti sürdürmekteki acizlik açıkça görülüyor. Libya sorununda Ankara, NATO’nun askeri müdahalesinin yanında durmak zorunda kaldı. Afganistan’da yine öyle... Suriye ve müttefiklerine yönelik tutum</p>
                                                </blockquote>
                                                <span class="cite">Tahsin Durur</span>
                                            </div>
                                        </div>

                                        <div class="clear"></div>
                                        <div class="line"></div>

                                        <div class="form">
                                            <label for="parliament_choose_1_1">Kesinliklike Katılıyorum</label>
                                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_1" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_1_2">Katılıyorum</label>
                                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_2" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_1_3">Kararsızım</label>
                                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_3" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_1_4">Katılmıyorum</label>
                                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_4" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_1_5">Kesinlikle Katılmıyorum</label>
                                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_5" />                        
                                        </div>
                                        <div class="vertical_line"></div>
                                        <div class="statistic">
                                            <img src="static/image/background/box/parliament/statistic.png" />
                                            <a href="#" class="show_result">Sonuçları Gör</a>
                                        </div>
                                    </div>
                                    
                                    <!-- Slide BOX 2 -->
                                    <div class="slide_box">
                                        
                                        <div class="head">
                                            <div class="image"><img src="static/image/users/medium/2.jpg" /></div>
                                            <div class="qoute">
                                                <blockquote>
                                                    <p>Türkiye, sorunları sıfırlama politikasında bazı puanlar kazandı. Fakat sorunun duvarı yükseldikçe, bu siyaseti sürdürmekteki acizlik açıkça görülüyor. </p>
                                                </blockquote>
                                                <span class="cite">Tahsin Durur</span>
                                            </div>
                                        </div>

                                        <div class="clear"></div>
                                        <div class="line"></div>

                                        <div class="form">
                                            <label for="parliament_choose_2_1">Kesinliklike Katılıyorum</label>
                                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_1" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_2_2">Katılıyorum</label>
                                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_2" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_2_3">Kararsızım</label>
                                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_3" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_2_4">Katılmıyorum</label>
                                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_4" />

                                            <div class="clear"></div>

                                            <label for="parliament_choose_2_5">Kesinlikle Katılmıyorum</label>
                                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_5" />                        
                                        </div>
                                        
                                        <div class="vertical_line"></div>
                                            <div class="statistic">
                                                <img src="static/image/background/box/parliament/statistic.png" />
                                                <a href="#" class="show_result">Sonuçları Gör</a>
                                        </div>                                    
                                    </div>
                                </div>

                            </div>

                            
                            <div class="buttons">
                                <button class="button_1"></button>
                                <button class="button_2"></button>
                            </div>
                            
                        </div>

<?php            
        }
        public function main1(){
            global $model, $db;
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->view = 'agenda';
            
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript($model->pluginurl . 'agenda.js', 'agenda.js', 1 );
            $classes = array('world'=>1, 'region'=>2, 'country'=>3, 'city'=>4, 'foryou'=>10);
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 ); 
?>            
<div id="meclislogo"><img src="<?php echo TEMPLATEURL.'default/images/meclislogo.gif';?>" alt=""></div>
<div id="agendacontainer">


<?php
    if(intval($model->paths[1])>0){
        $agendaID = intval($model->paths[1]);
        $agenda=null;
        $db->setQuery('SELECT a.* FROM agenda AS a WHERE a.ID='.$db->quote($agendaID).' LIMIT 1');
        if($db->loadObject($agenda)){
            
        } else {
            
        }
    } else {
        $agenda=null;
        $db->setQuery('SELECT a.* FROM agenda AS a WHERE '.$db->quote(date('Y-m-d H:i:s')).' BETWEEN a.starttime AND a.endtime AND class='.$db->quote($classes['country']).' ORDER BY ID desc LIMIT 1');

    
        if($db->loadObject($agenda)){
            
        } else {
            
        }
    }
    
                $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
                $response['ID'] = $agenda->ID;
                $response['image'] = $img;
                $response['title'] = $agenda->title;
                $response['dateinfo'] = asdatetime( $agenda->starttime,'d F Y').' günü oylamaya açıldı';
                $response['isvotable'] = 1;
                $response['agendagolink'] = '/agenda/'.$agenda->ID;
                $response['agendagotitle'] = 'Meclise Gir';
                $response['agendatimeleft'] = 'Gündemde oy kullanmak için <strong>'.time_left(strtotime($agenda->endtime)).'</strong> kaldı.';
                $response['moreinfo'] = 'daha fazla bilgi';
                $response['agendalastcomment'] = '';
                $response['agendalastcomments'] = '';
                
                $db->setQuery('SELECT COUNT(*) FROM agendacomment AS ac WHERE ac.agendaID='.$db->quote($agenda->ID));
                $totalcomments = intval( $db->loadResult() );                
                
                if($totalcomments > 0){
                    $SELECT = "SELECT ac.*, p.image, p.name";
                    $FROM   = "\n FROM agendacomment AS ac";
                    $JOIN   = "\n JOIN profile AS p ON p.ID = ac.profileID";
                    $WHERE  = "\n WHERE ac.agendaID = " . $db->quote($agenda->ID);
                    $ORDER  = "\n ORDER BY ac.ID DESC";
                    $LIMIT  = "\n LIMIT 4";

                    $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$ORDER.$LIMIT);
                    
                    $comments = $db->loadObjectList();
                    if(count($comments)){
                        $i=0;
                        foreach($comments as $comment){
                            $i++;
                            if($i==1){
                                $response['agendalastcomment'] = '<img height="32" align="middle" width="32" class="profileimage" alt="" src="'.$model->getProfileImage($comment->image, 32,32,'cutout').'"> <span><strong>'.time_since(strtotime($comment->datetime)).' önce '.$comment->name.':</strong> '.$comment->comment.'» Devamı</span>';
                            } else {
                                $response['agendalastcomments'] .= '<strong>'.$comment->name.'</strong> ';
                            }
                        }
                        $response['agendalastcomments'] .= ' ve '.$totalcomments.' yorumu okumak için tıklayın';
                        
                    }
                    
                    
                    
                }
                
                
                
                
                
                
                
                $db->setQuery('SELECT COUNT(*) FROM agendavote AS av WHERE av.agendaID='.$db->quote($agenda->ID));
                $totalvote = intval( $db->loadResult() );
                
                $db->setQuery('SELECT av.optionID, COUNT(*) AS total FROM agendavote AS av WHERE av.agendaID='.$db->quote($agenda->ID) . 'GROUP BY av.optionID');
                $votes = $db->loadObjectList('optionID');                
                                

                $db->setQuery('SELECT ao.* FROM agendaoption AS ao WHERE ao.agendaID='.$db->quote($agenda->ID));
                $aos = $db->loadObjectList();
                $options = array();
                if(count($aos)){
                    foreach($aos as $ao){
                        
                        if($totalvote > 0){
                            if(array_key_exists($ao->ID, $votes)){
                                $percent = floor( ($votes[$ao->ID]->total * 100) / $totalvote );
                            } else {
                                $percent = 0;
                            }
                        } else {
                            $percent = 0;
                        }                        
                        
                        $options[$ao->ID]= array('title'=>$ao->title, 'percent'=>$percent);
                    }
                }
                
                $response['options'] = $options;    
    

?>
    <ul id="agendatabs">
      <li rel="city" <?php if($agenda->class==$classes['city']) echo 'class="active"';?>>İstanbul</li>
      <li rel="country" <?php if($agenda->class==$classes['country']) echo 'class="active"';?>>Türkiye</li>
      <li rel="region" <?php if($agenda->class==$classes['region']) echo 'class="active"';?>>Bölge</li>
      <li rel="world" <?php if($agenda->class==$classes['world']) echo 'class="active"';?>>Dünya</li>
      <li rel="foryou" <?php if($agenda->class==$classes['foryou']) echo 'class="active"';?>>Sizce</li>
    </ul>
    
    
    
    
       <div id="agenda">
          <div id="agendaimage"> <img src="<?=$model->getImage($agenda->imagepath, 500, 120, 'cutout')?>" width="500" height="120" alt="" /> </div>
          <div id="agendainfo"><?=$response['dateinfo'];?></div>
          <div id="agendatitle"><?=$response['title']?></div>
          
          <div id="agendatimeinfo"><?=$response['agendatimeleft']?></div>
          <div id="agendamoreinfobtn" rel="<?=$agenda->ID?>">daha çok bilgi</div>
          
          
          <div id="agendaoptions">
<?php
            foreach($response['options'] as $optionID=>$option){            
?>          	
            <div class="agendaoption">
              <div class="agendaoptionleft"><input type="radio" id="ao<?=$optionID?>" value="<?=$optionID?>" name="ao<?=$agenda->ID?>"></div>
              <div class="agendaoptionbody"><?=$option['title']?></div>
              <div class="agendaoptionright"><?=$option['percent']?> %</div>
            </div>
<?php
            }
?>
          </div>
          
          <div id="agendafooter">
            <div id="agendavoteinfo">Şu ana dek 12.453 kişi oy kullandı, 345 kişi yorumladı</div>
            <input type="button" id="agendavote" class="votebutton" rel="<?=$agenda->ID?>" value="Oyunu Kullan" />
          </div>
          <input type="hidden" name="activeagenda" id="activeagenda" value="<?=$agenda->ID?>" />
          
          <div id="agendacomments" class="comments"></div>
<?php
    $model->addScript('activeagenda = ' . $agenda->ID);
?>          
        </div>
    
    
    
    
</div>
<?php     
        }
        
        public function block(){
            global $model, $db;
            
            
            $db->setQuery('SELECT a.* FROM agenda AS a ORDER BY ID desc LIMIT 1');
            $agenda=null;
            if($db->loadObject($agenda)){
                
                $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
                if(strlen($img)) 
                    echo '<img src="'.$img.'" alt="" />';
                    
                echo '<h1>'.$agenda->title.'</h1>';
                //echo '<h3>'. $agenda->starttime .' günü oylamaya açıldı</h3>';
                echo '<h3>'.asdatetime( $agenda->starttime,'d F Y').' günü oylamaya açıldı</h3>';
                
                echo '<h4><a href="/agenda/'.$agenda->ID.'"> Meclise gir</a></h4>';
                
                
            } else {
                echo 'agenda not found';
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
            
        public function ajax_agenda(){
            global $model, $db;
            $what = filter_input(INPUT_POST, 'what', FILTER_SANITIZE_STRING);
            $response = array();
            
            $classes = array('world'=>1, 'region'=>2, 'country'=>3, 'city'=>4, 'foryou'=>10);
            
            $class = array_key_exists($what, $classes) ? $classes[$what]:$classes[0];

            $db->setQuery('SELECT a.* FROM agenda AS a WHERE '.$db->quote(date('Y-m-d H:i:s')).' BETWEEN a.starttime AND a.endtime AND class='.$db->quote($class).' ORDER BY ID desc LIMIT 1');
            $agenda=null;
            if($db->loadObject($agenda)){
                
                $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
                $response['ID'] = $agenda->ID;
                $response['image'] = $img;
                $response['title'] = $agenda->title;
                $response['dateinfo'] = asdatetime( $agenda->starttime,'d F Y').' günü oylamaya açıldı';
                $response['isvotable'] = 1;
                $response['agendagolink'] = '/agenda/'.$agenda->ID;
                $response['agendagotitle'] = 'Meclise Gir';
                $response['moreinfo'] = 'Daha çok bilgi';
                $response['agendatimeleft'] = 'Gündemde oy kullanmak için <strong>'.time_left(strtotime($agenda->endtime)).'</strong> kaldı.';
                $response['agendalastcomment'] = '';
                $response['agendalastcomments'] = '';
                
                $db->setQuery('SELECT COUNT(*) FROM agendacomment AS ac WHERE ac.agendaID='.$db->quote($agenda->ID));
                $totalcomments = intval( $db->loadResult() );                
                
                if($totalcomments > 0){
                    $SELECT = "SELECT ac.*, p.image, p.name";
                    $FROM   = "\n FROM agendacomment AS ac";
                    $JOIN   = "\n JOIN profile AS p ON p.ID = ac.profileID";
                    $WHERE  = "\n WHERE ac.agendaID = " . $db->quote($agenda->ID);
                    $ORDER  = "\n ORDER BY ac.ID DESC";
                    $LIMIT  = "\n LIMIT 4";

                    $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$ORDER.$LIMIT);
                    
                    $comments = $db->loadObjectList();
                    if(count($comments)){
                        $i=0;
                        foreach($comments as $comment){
                            $i++;
                            if($i==1){
                                $response['agendalastcomment'] = '<img height="32" align="middle" width="32" class="profileimage" alt="" src="'.$model->getProfileImage($comment->image, 32,32,'cutout').'"> <span><strong>'.time_since(strtotime($comment->datetime)).' önce '.$comment->name.':</strong> '.$comment->comment.'» Devamı</span>';
                            } else {
                                $response['agendalastcomments'] .= '<strong>'.$comment->name.'</strong> ';
                            }
                        }
                        $response['agendalastcomments'] .= ' ve '.$totalcomments.' yorumu okumak için tıklayın';
                        
                    }
                    
                    
                    
                }
                
                
                
                //die('agenda');
                
                
                
                
                $db->setQuery('SELECT COUNT(*) FROM agendavote AS av WHERE av.agendaID='.$db->quote($agenda->ID));
                $totalvote = intval( $db->loadResult() );
                
                $db->setQuery('SELECT av.optionID, COUNT(*) AS total FROM agendavote AS av WHERE av.agendaID='.$db->quote($agenda->ID) . 'GROUP BY av.optionID');
                $votes = $db->loadObjectList('optionID');

                $db->setQuery('SELECT ao.* FROM agendaoption AS ao WHERE ao.agendaID='.$db->quote($agenda->ID));
                $aos = $db->loadObjectList();
                $options = array();
                if(count($aos)){
                    foreach($aos as $ao){
                        if($totalvote > 0){
                            if(array_key_exists($ao->ID, $votes)){
                                $percent = floor( ($votes[$ao->ID]->total * 100) / $totalvote );
                            } else {
                                $percent = 0;
                            }
                        } else {
                            $percent = 0;
                        }
                        
                        $options[$ao->ID]= array('title'=>$ao->title, 'percent'=>$percent);
                    }
                }
                
                $response['options'] = $options;
                
                
                $response['result']='success';
            } else {                
                $response['result']='error';
            }
            
            echo json_encode($response);
        }    

        public function ajax_vote(){
            global $model, $db;
            $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
            $optionID = filter_input(INPUT_POST, 'optionID', FILTER_SANITIZE_NUMBER_INT);
            $profileID = intval( $model->user->profileID );
            
            $response = array();
            
            $db->setQuery('SELECT a.* FROM agenda AS a WHERE ID='.$db->quote($agendaID).' LIMIT 1');
            $agenda=null;
            if($db->loadObject($agenda)){
                
                $db->setQuery('SELECT av.* FROM agendavote AS av WHERE agendaID='.$db->quote($agendaID).' AND profileID='.$db->quote($profileID).' LIMIT 1');
                $av=null;
                if($db->loadObject($av)){
                    //voted
                    $response['result']='error';
                    $response['message']='allready voted';
                } else {
                    //not voted
                    $av = new stdClass;
                    $av->agendaID    = $agendaID;
                    $av->profileID   = $profileID;
                    $av->optionID    = $optionID;
                    $av->datetime    = date('Y-m-d H:i:s');
                    $av->userID      = intval( $model->user->ID );
                    $av->ip          = ip2long($_SERVER['REMOTE_ADDR']); 
                    
                    if($db->insertObject('agendavote', $av)){
                        //new vote saved
                        $response['result']='success';
                    } else {
                        //not saved
                        $response['result']='error';
                        $response['message']='save error';
                    }
                }
                //$response['result']='success';
            } else {                
                $response['result']='error';
                $response['message']='error';
            }
            
            $response['image'] = $model->getProfileImage($model->profile->image, 60, 60, 'cutout');
            
            echo json_encode($response);
        }

        public function ajax_commentit(){
            global $model, $db;
            $agendaID   = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
            $optionID   = filter_input(INPUT_POST, 'optionID', FILTER_SANITIZE_NUMBER_INT);
            $sip        = filter_input(INPUT_POST, 'showinprofile', FILTER_SANITIZE_NUMBER_INT);
            $comment    = htmlspecialchars_decode( filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING), ENT_QUOTES);
            $profileID  = intval( $model->profileID );
            
            $response   = array();
            
            $db->setQuery('SELECT a.* FROM agenda AS a WHERE ID='.$db->quote($agendaID).' LIMIT 1');
            $agenda=null;
            if($db->loadObject($agenda)){
                
                $db->setQuery('SELECT ac.* FROM agendacomment AS ac WHERE agendaID='.$db->quote($agendaID).' AND profileID='.$db->quote($profileID).' LIMIT 1');
                $ac=null;
                if($db->loadObject($ac)){
                    //voted
                    $response['result']='error';
                    $response['message']='allready commented';
                } else {
                    //not voted
                    $ac = new stdClass;
                    $ac->agendaID    = $agendaID;
                    $ac->profileID   = $profileID;
                    $ac->optionID    = $optionID;
                    $ac->comment     = $comment;
                    $ac->datetime    = date('Y-m-d H:i:s');
                    $ac->userID      = intval( $model->profileID );
                    $ac->ip          = ip2long($_SERVER['REMOTE_ADDR']); 
                    
                    if($db->insertObject('agendacomment', $ac)){
                        //new comment saved
                        $response['result']='success';
                        $response['image']=$model->getProfileImage($model->profile->image, 60, 60, 'cutout');
                        $response['comment']=$comment;
                        $response['name']=$model->profile->name;
                        $response['info']= time_since(time()) . ' önce yazdı';
                        
                        
                        
                        
                        //show in profile
                        if($sip>0){
                            
                            //show in profile 
                            $share = new stdClass;
                            $share->url = '/agenda/' . $agenda->ID;
                            $share->title = $agenda->title;
                            $share->description = $comment;
                            
                            //save the share
                            $share->type        = 3; //agenda comment
                            
                            $share->datetime    = date('Y-m-d H:i:s');
                            $share->sharerID    = intval( $model->profileID );
                            $share->profileID   = intval( $model->profileID );
                            $share->userID      = intval( $model->user->ID );
                            $share->ip          = ip2long($_SERVER['REMOTE_ADDR']);
                            
                            if( $db->insertObject('share', $share) ){
                                $response['status'] = 'success';
                            } else {
                                throw new Exception('record error');
                            }                            
                            
                            
                            
                            //$response['result']='success';
                            
                        }
                        
                        
                    } else {
                        //not saved
                        $response['result']='error';
                        $response['message']='save error';
                    }
                }
                //$response['result']='success';
            } else {                
                $response['result']='error';
                $response['message']='undefined error';
            }
            
            echo json_encode($response);
        }

        public function ajax_like(){
            global $model, $db;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $like = filter_input(INPUT_POST, 'like', FILTER_SANITIZE_NUMBER_INT);
            
            $profileID = intval( $model->user->profileID );
            
            $response = array();
            
            $db->setQuery("SELECT acl.* FROM agendacommentlike AS acl WHERE acl.commentID=" . $db->quote($ID) . " AND acl.profileID=" . $db->quote($profileID) . " LIMIT 1" . "\n #".__FILE__." - ".__LINE__);
            $acl = null;
            if($db->loadObject($acl)){
                switch($like){
                    case 1: $acl->regard=1; $acl->appreciate=0; break;
                    case 2: $acl->regard=0; $acl->appreciate=1; break;
                    default: $acl->regard=0; $acl->appreciate=0;
                }
                $db->updateObject('agendacommentlike', $acl, 'ID');
            } else {
                $acl = new stdClass;
                
                switch($like){
                    case 1: $acl->regard=1; $acl->appreciate=0; break;
                    case 2: $acl->regard=0; $acl->appreciate=1; break;
                    default: $acl->regard=0; $acl->appreciate=0;
                }
                
                $acl->commentID   = intval($ID);
                $acl->datetime    = date('Y-m-d H:i:s');
                $acl->profileID   = intval( $profileID );
                $acl->userID      = intval( $model->user->ID );
                $acl->ip          = ip2long($_SERVER['REMOTE_ADDR']);                
                
                $db->insertObject('agendacommentlike', $acl);
            }
            
            
            
            echo json_encode($response);
        }     

        public function ajax_moreinfo(){
            global $model, $db;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            
            $profileID = intval( $model->user->profileID );
            
            $response = array();
            
            $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID=" . $db->quote($ID) . " LIMIT 1" . "\n #".__FILE__." - ".__LINE__);
            $a = null;
            if($db->loadObject($a)){
                $response['result'] = 'success';
                $response['moreinfo'] = $a->content;
            } else {
                $response['result'] = 'error';
                $response['moreinfo'] = '';

            }
            
            
            
            echo json_encode($response);
        }           

        public function ajax_comments(){
            global $model, $db;
            $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
            $response = array();
            
            $db->setQuery('SELECT a.* FROM agenda AS a WHERE a.ID='.$db->quote($agendaID).' LIMIT 1');
            $agenda=null;
            if($db->loadObject($agenda)){
                
                $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
                $response['ID'] = $agenda->ID;
                $response['image'] = $img;
                $response['title'] = $agenda->title;
                $response['dateinfo'] = asdatetime( $agenda->starttime,'d F Y').' günü oylamaya açıldı';
                $response['isvotable'] = 0;
                $response['voteinfo'] = 'this is vote info';
                
                $SELECT = "SELECT ac.*, p.image, p.name";
                $FROM   = "\n FROM agendacomment AS ac";
                $JOIN   = "\n JOIN profile AS p ON p.ID = ac.profileID";
                $WHERE  = "\n WHERE ac.agendaID = " . $db->quote($agenda->ID);
                $ORDER  = "\n ORDER BY ac.ID DESC";
                $LIMIT  = "\n LIMIT 10";

                $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$ORDER.$LIMIT);                
                
                //$db->setQuery('SELECT ac.* FROM agendacomment AS ac WHERE ac.agendaID='.$db->quote($agenda->ID));
                $acs = $db->loadObjectList();
                $comments = array();
                if(count($acs)){
                    foreach($acs as $ac){
                        $comments[$ac->ID]= array(
                                                'image'=>$model->getProfileImage( $ac->image,60, 60, 'cutout'),
                                                'name'=>$ac->name,
                                                'comment'=>$ac->comment,
                                                'info'=> time_since( strtotime( $ac->datetime ) ) . ' önce yazdı.'
                                                );
                    }
                }
                $response['comments'] = $comments;
                
                
                $response['result']='success';
            } else {
                
                $response['result']='error';
            }
            
            echo json_encode($response);
        }
            
    }
?>