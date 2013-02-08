<?php
    class ajax_plugin extends control{
        
        public function main(){
            global $model, $db;
            $model->mode=0;
            
            $method = (string) '' . $model->paths[1];
            
            if($method == 'main') die;
            
            if(method_exists($this, $method )){
                $this->$method();
				die();
            }
        }
        
        public function profilecomplaintmenu(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $response['height'] = 320;
                $html = '';
                $ID          = intval( filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT ) );
                
                if($ID<=0) throw new Exception('bi sorun var!');
                //profili bul
                $db->setQuery('SELECT * FROM profile WHERE ID = ' . $db->quote($ID).' AND status>0');
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profil bulunamadı!');
                
                //takip ediyor musun? bul
                $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($model->profileID).' AND followingID = ' . $db->quote( $profile->ID));
                $follow = null;
                if($db->loadObject($follow)){
                    //evet onu takip ediyorsun
                    if($follow->followerstatus >0 && $follow->followingstatus>0)
                    
                    //takipten çıkar
                    $html .= '<p><input type="checkbox" name="unfollow" value="1" />'. $profile->name . ' ı takip listemden çıkar</p>';
                    
                } else {
                    
                }
                
                                
                //o seni takip ediyor mu? bul
                $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($profile->ID).' AND followingID = ' . $db->quote( $model->profileID));
                $follow2 = null;
                if($db->loadObject($follow2)){
                    //evet seni takip ediyor
                    
                    //öyleyse engelleyebilirsin
                    //$html .= '<p><input type="checkbox" name="block" value="1" />'. $profile->name . ' ı engelle</p>';
                    
                } else {
                    
                }
                /*
                //kaldır
                if($di->profileID==$model->profileID){
                    //throw new Exception('bu senin di\'n :)');
                    $html .= '<p><input type="checkbox" name="remove" value="1" />Bu di\'yi kaldır</p>';
                    $response['height'] = 120;
                }
                */
                
                //takip etme
                
                
                
                //engelle
                
                
                //şikayet et
                if($profile->ID!=$model->profileID){
                    $html .= '<p><input type="checkbox" name="complaint" value="1" />Şikayet et</p>';
                    $html .= '<div class="complaintbox" style="margin: 0 0 0 30px;">';
                    
                    $html .= '<p><strong>Gerekçeniz:</strong></p>';
                    $html .= '<p>'.$model->array_to_select(config::$prreasons, 'reason').'</p>';
                    $html .= '<p><strong>Notunuz:</strong><br />
                                <textarea maxlength="200" name="note"></textarea>
                              </p>';
                              
                    $html .= '</div>';
                }
                
                $html .= '<input type="hidden" name="ID" value="'.$ID.'" />';
                
                $html = '<form id="pxmenuform'.$ID.'" class="dialogform">'.$html.'</form>';
                $html = '<div id="pxmenu'.$ID.'">'.$html.'</div>';
                
                $response['html'] = $html;
                $response['result'] = 'success';
                $response['message'] = 'ok';
                

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        public function profilecomplaintmenusend(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $response['height'] = 320;
                $html = '';
                $ID          = intval( filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT ) );
                $remove      = intval( filter_input(INPUT_POST, 'remove', FILTER_SANITIZE_NUMBER_INT ) );
                $unfollow    = intval( filter_input(INPUT_POST, 'unfollow', FILTER_SANITIZE_NUMBER_INT ) );
                
                $complaint   = intval( filter_input(INPUT_POST, 'complaint', FILTER_SANITIZE_NUMBER_INT ) );
                $reason      = intval( filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_NUMBER_INT ) );
                $note        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                
                if($ID<=0) throw new Exception('bi sorun var!');
                //di'yi bul
                $db->setQuery('SELECT * FROM profile WHERE ID = ' . $db->quote($ID).' AND status>0');
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profil bulunamadı!');
                /*
                //sil - kontrol et
                if(0 && $remove>0){
                    
                    if($di->profileID!=$model->profileID) throw new Exception('bu senin di\'n değil ki!');
                    
                    $di->status = 0;
                    if(!$db->updateObject('di', $di, 'ID', 0)) throw new Exception('hata oluştu!');
                    
                }
                */
                
                if($unfollow>0){
                    //takip ediyor musun? bul
                    $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($model->profileID).' AND followingID = ' . $db->quote( $profile->ID));
                    $follow = null;
                    if($db->loadObject($follow)){
                        $follow->followerstatus = 0;
                        $follow->followingstatus = 0;
                        $follow->status = 0;
                        if(!$db->updateObject('follow', $follow, 'ID', 0)) throw new Exception('hata oluştu!');
                    } else  {
                        //zaten yok ki!
                        throw new Exception('zaten takip etmiyorsun ki!');
                    }
                    
                    
                    
                    
                    
                    
                }
                
                if($complaint>0){
                    
                    $dc = new stdClass;
                
                    //$dc->diID        = $ID;
                    $dc->profileID   = $profile->ID;
                    $dc->fromID      = $model->profileID;
                    $dc->reason      = $reason;
                    $dc->message     = $note;
                    $dc->status      = 1;
                    $dc->datetime    = date('Y-m-d H:i:s');
                    $dc->ip          = $_SERVER['REMOTE_ADDR'];
                    
                    if(!$db->insertObject('profilecomplaint', $dc)) throw new Exception('hata oluştu!');
                    
                    
                }
                 
                //$response['html'] = $html;
                $response['result'] = 'success';
                $response['message'] = 'ok';
                

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        
        public function dixmenu(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $response['height'] = 320;
                $html = '';
                $ID          = intval( filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT ) );
                
                if($ID<=0) throw new Exception('bi sorun var!');
                //di'yi bul
                $db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($ID).' AND status>0');
                $di = null;
                if(!$db->loadObject($di)) throw new Exception('ses bulunamadı!');
                
                //profili bul
                $db->setQuery('SELECT * FROM profile WHERE ID = ' . $db->quote($di->profileID).' AND status>0');
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profil bulunamadı!');
                
                //takip ediyor musun? bul
                $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($model->profileID).' AND followingID = ' . $db->quote( $profile->ID));
                $follow = null;
                if($db->loadObject($follow)){
                    //evet onu takip ediyorsun
                    if($follow->followerstatus >0 && $follow->followingstatus>0)
                    {
	                    //takipten çıkar
	                    $html .= '<p><input type="checkbox" name="unfollow" value="1" />'. $profile->name . ' ı takip listemden çıkar</p>';
                    }
                } else {
                    
                }
                
                                
                //o seni takip ediyor mu? bul
                $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($profile->ID).' AND followingID = ' . $db->quote( $model->profileID));
                $follow2 = null;
                if($db->loadObject($follow2)){
                    //evet seni takip ediyor
                    
                    //öyleyse engelleyebilirsin
                    //$html .= '<p><input type="checkbox" name="block" value="1" />'. $profile->name . ' ı engelle</p>';
                    
                } else {
                    
                }
                
                //kaldır
                if($di->profileID==$model->profileID){
                    //throw new Exception('bu senin di\'n :)');
                    $html .= '<p><input type="checkbox" name="remove" value="1" />Bu sesi kaldır</p>';
                    $response['height'] = 120;
                }
                
                //takip etme
                
                
                
                //engelle
                
                
                //şikayet et
                if($di->profileID!=$model->profileID){
                    $html .= '<p><input type="checkbox" name="complaint" value="1" />Şikayet et</p>';
                    $html .= '<div class="complaintbox" style="margin: 0 0 0 30px;">';
                    
                    $html .= '<p><strong>Gerekçeniz:</strong></p>';
                    $html .= '<p>'.$model->array_to_select(config::$direasons, 'reason').'</p>';
                    $html .= '<p><strong>Notunuz:</strong><br />
                                <textarea maxlength="200" name="note"></textarea>
                              </p>';
                              
                    $html .= '</div>';
                }
                
                $html .= '<input type="hidden" name="ID" value="'.$ID.'" />';
                
                $html = '<form id="dixmenuform'.$ID.'" class="dialogform">'.$html.'</form>';
                $html = '<div id="dixmenu'.$ID.'">'.$html.'</div>';
                
                $response['html'] = $html;
                $response['result'] = 'success';
                $response['message'] = 'ok';
                

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
			die;
        }
        
        
        public function dixmenusend(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $response['height'] = 320;
                $html = '';
                $ID          = intval( filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT ) );
                $remove      = intval( filter_input(INPUT_POST, 'remove', FILTER_SANITIZE_NUMBER_INT ) );
                $unfollow    = intval( filter_input(INPUT_POST, 'unfollow', FILTER_SANITIZE_NUMBER_INT ) );
                
                $complaint   = intval( filter_input(INPUT_POST, 'complaint', FILTER_SANITIZE_NUMBER_INT ) );
                $reason      = intval( filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_NUMBER_INT ) );
                $note        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                
                if($ID<=0) throw new Exception('bi sorun var!');
                //di'yi bul
                $db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($ID).' AND status>0');
                $di = null;
                if(!$db->loadObject($di)) throw new Exception('di bulunamadı!');
                
                //sil - kontrol et
                if($remove>0){
                    
                    if($di->profileID!=$model->profileID) throw new Exception('bu senin di\'n değil ki!');
                    
                    $di->status = 0;
                    if(!$db->updateObject('di', $di, 'ID', 0)) throw new Exception('hata oluştu!');
                    $log=new stdClass();
                    $log->userID=$model->profileID;
					$log->event="diDelete";
					$log->itemID=$ID;
					$log->ip=$_SERVER['REMOTE_ADDR'];
					$db->insertObject('log', $log);
					
					$puanClass=new puan;
                   	$puanClass->puanIslem($di->profileID, "7", $di);
					 
                }
                
                if($unfollow>0){
                    //takip ediyor musun? bul
                    $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($model->profileID).' AND followingID = ' . $db->quote( $di->profileID));
                    $follow = null;
                    if($db->loadObject($follow)){
                        $follow->followerstatus = 0;
                        $follow->followingstatus = 0;
                        $follow->status = 0;
                        if(!$db->updateObject('follow', $follow, 'ID', 0)) throw new Exception('hata oluştu!');
                    } else  {
                        //zaten yok ki!
                        throw new Exception('zaten takip etmiyorsun ki!');
                    }
                    
                    
                    
                    
                    
                    
                }
                
                if($complaint>0){
                    
                    $dc = new stdClass;
                
                    $dc->diID        = $ID;
                    $dc->profileID   = $di->profileID;
                    $dc->fromID      = $model->profileID;
                    $dc->reason      = $reason;
                    $dc->message     = $note;
                    $dc->status      = 1;
                    $dc->datetime    = date('Y-m-d H:i:s');
                    $dc->ip          = $_SERVER['REMOTE_ADDR'];
                    
                    if(!$db->insertObject('dicomplaint', $dc)) throw new Exception('hata oluştu!');
                    
                    
                }
                 
                //$response['html'] = $html;
                $response['result'] = 'success';
                $response['message'] = 'ok';
                

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        public function dicxmenu(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $response['height'] = 320;
                $html = '';
                $ID          = intval( filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT ) );
                
                if($ID<=0) throw new Exception('bi sorun var!');
                //di'yi bul
                $db->setQuery('SELECT * FROM dicomment WHERE ID = ' . $db->quote($ID).' AND status>0');
                $dic = null;
                if(!$db->loadObject($dic)) throw new Exception('yankı bulunamadı!');
                
                                
                //if($ID<=0) throw new Exception('bi sorun var!');
                //di'yi bul
                //$db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($dic->diID).' AND status>0');
                $db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($dic->diID).' ');
                $di = null;
                if(!$db->loadObject($di)) throw new Exception('ses bulunamadı!');
                
                //profili bul
                //$db->setQuery('SELECT * FROM profile WHERE ID = ' . $db->quote($dic->profileID).' AND status>0');
                $db->setQuery('SELECT * FROM profile WHERE ID = ' . $db->quote($dic->profileID).' ');
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profil bulunamadı!');
                
                //takip ediyor musun? bul
                $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($model->profileID).' AND followingID = ' . $db->quote( $profile->ID));
                $follow = null;
                if($db->loadObject($follow)){
                    //evet onu takip ediyorsun
                    if($follow->followerstatus >0 && $follow->followingstatus>0)
                    
                    //takipten çıkar
                    $html .= '<p><input type="checkbox" name="unfollow" value="1" />'. $profile->name . ' ı takip listemden çıkar</p>';
                    
                } else {
                    
                }
                
                                
                //o seni takip ediyor mu? bul
                $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($profile->ID).' AND followingID = ' . $db->quote( $model->profileID));
                $follow2 = null;
                if($db->loadObject($follow2)){
                    //evet seni takip ediyor
                    
                    //öyleyse engelleyebilirsin
                    //$html .= '<p><input type="checkbox" name="block" value="1" />'. $profile->name . ' ı engelle</p>';
                    
                } else {
                    
                }
                
                //kaldır
                if($di->profileID==$model->profileID || $dic->profileID==$model->profileID){
                    //throw new Exception('bu senin di\'n :)');
                    $html .= '<p><input type="checkbox" name="remove" value="1" />Bu yorumu kaldır</p>';
                    $response['height'] = 160;
                    //die('tamaam');
                }
                
                //die($di->profileID.' - '.$model->profileID);
                
                //takip etme
                
                
                
                //engelle
                
                
                //şikayet et
                if($di->profileID!=$model->profileID && $dic->profileID!=$model->profileID){
                    $html .= '<p><input type="checkbox" name="complaint" value="1" />Şikayet et</p>';
                    $html .= '<div class="complaintbox" style="margin: 0 0 0 30px;">';
                    
                    $html .= '<p><strong>Gerekçeniz:</strong></p>';
                    $html .= '<p>'.$model->array_to_select(config::$dicommentreasons, 'reason').'</p>';
                    $html .= '<p><strong>Notunuz:</strong><br />
                                <textarea maxlength="200" name="note"></textarea>
                              </p>';
                              
                    $html .= '</div>';
                }
                
                $html .= '<input type="hidden" name="ID" value="'.$ID.'" />';
                
                $html = '<form id="dicxmenuform'.$ID.'" class="dialogform">'.$html.'</form>';
                $html = '<div id="dicxmenu'.$ID.'">'.$html.'</div>';
                
                $response['html'] = $html;
                $response['result'] = 'success';
                $response['message'] = 'ok';
                

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }        
        
        public function dicxmenusend(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $response['height'] = 320;
                $html = '';
                $ID          = intval( filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT ) );
                $remove      = intval( filter_input(INPUT_POST, 'remove', FILTER_SANITIZE_NUMBER_INT ) );
                $unfollow    = intval( filter_input(INPUT_POST, 'unfollow', FILTER_SANITIZE_NUMBER_INT ) );
                
                $complaint   = intval( filter_input(INPUT_POST, 'complaint', FILTER_SANITIZE_NUMBER_INT ) );
                $reason      = intval( filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_NUMBER_INT ) );
                $note        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                
                if($ID<=0) throw new Exception('bi sorun var!');
                //di yorumunu bul
                $db->setQuery('SELECT * FROM dicomment WHERE ID = ' . $db->quote($ID).' AND status>0');
                $dic = null;
                if(!$db->loadObject($dic)) throw new Exception('di yorumu bulunamadı!');
                
                //di'yi bul
                $db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($dic->diID).' AND status>0');
                $di = null;
                if(!$db->loadObject($di)) throw new Exception('di bulunamadı!');                
                
                //sil - kontrol et
                if($remove>0){
                    
                    if($di->profileID!=$model->profileID && $dic->profileID!=$model->profileID) throw new Exception('senin değil ki!');
                    
                    $dic->status = 0;
                    if(!$db->updateObject('dicomment', $dic, 'ID', 0)) throw new Exception('hata oluştu!');
                    
					$log=new stdClass();
                    $log->userID=$model->profileID;
					$log->event="diComentDelete";
					$log->itemID=$ID;
					$log->ip=$_SERVER['REMOTE_ADDR'];
					$db->insertObject('log', $log);
					
					
					$puanClass=new puan;
                   	$puanClass->puanIslem($dic->profileID, "6",  $dic);
                }
                
                if($unfollow>0){
                    //takip ediyor musun? bul
                    $db->setQuery('SELECT * FROM follow WHERE followerID = ' . $db->quote($model->profileID).' AND followingID = ' . $db->quote( $dic->profileID));
                    $follow = null;
                    if($db->loadObject($follow)){
                        $follow->followerstatus = 0;
                        $follow->followingstatus = 0;
                        $follow->status = 0;
                        if(!$db->updateObject('follow', $follow, 'ID', 0)) throw new Exception('hata oluştu!');
                    } else  {
                        //zaten yok ki!
                        throw new Exception('zaten takip etmiyorsun ki!');
                    }
                    
                }
                
                if($complaint>0){
                    
                    $dcc = new stdClass;
                
                    $dcc->dicID       = $ID;
                    $dcc->diID        = $dic->diID;
                    $dcc->profileID   = $di->profileID;
                    $dcc->fromID      = $model->profileID;
                    $dcc->reason      = $reason;
                    $dcc->message     = $note;
                    $dcc->status      = 1;
                    $dcc->datetime    = date('Y-m-d H:i:s');
                    $dcc->ip          = $_SERVER['REMOTE_ADDR'];
                    
                    if(!$db->insertObject('dicommentcomplaint', $dcc)) throw new Exception('hata oluştu!');
                    
                    
                }
                 
                //$response['html'] = $html;
                $response['result'] = 'success';
                $response['message'] = 'ok';
                

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        public function profilecomplaint(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID          = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT );
                $complaint   = filter_input(INPUT_POST, 'complaint', FILTER_SANITIZE_NUMBER_INT );
                $reason   = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_NUMBER_INT );
                $message   	 = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS );
                
                $db->setQuery('SELECT * FROM profile WHERE ID = ' . $db->quote($ID).' AND status>0');
                
                if(!$db->loadObject($pr)) throw new Exception('profil bulunamadı');
                
                //ekle
                
                $pc = new stdClass;
                
                //$pc->diID        = $ID;
                $pc->profileID   = $pr->ID;
                $pc->fromID      = $model->profileID;
                $pc->message     = $message;
                $pc->reason      = $reason;
                $pc->status      = 1;
                $pc->datetime    = date('Y-m-d H:i:s');
                $pc->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('profilecomplaint', $pc)) {
                    $response['result'] = 'success';
                } else 
                    throw new Exception('kayıt hatası oluştu');

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }        

        public function dicomplaint(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID          = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT );
                $complaint   = filter_input(INPUT_POST, 'complaint', FILTER_SANITIZE_NUMBER_INT );
                
                $db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($ID).' AND status>0');
                
                if(!$db->loadObject($di)) throw new Exception('di bulunamadı');
                
                //ekle
                
                $dc = new stdClass;
                
                $dc->diID        = $ID;
                $dc->profileID   = $di->profileID;
                $dc->fromID      = $model->profileID;
                $dc->reason      = $complaint;
                $dc->status      = 1;
                $dc->datetime    = date('Y-m-d H:i:s');
                $dc->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('dicomplaint', $dc)) {
                    $response['result'] = 'success';
                } else 
                    throw new Exception('kayıt hatası oluştu');

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        
        public function ppaddnew(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                //$title     = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING );
                $spot   = filter_input(INPUT_POST, 'spot', FILTER_SANITIZE_STRING );
                
                //millet vekili mi?
                if($model->profile->deputy<1) throw new Exception('Sen milletvekili değilsin ki!');                
                $ppCount=proposal::get_poroposalCount();
				if($ppCount>2){throw new Exception('Bir günde enfazla 3 Gündem maddesi yazabilirsiniz.');};
                //aynı başlıktan var mı?
                
                //valla vaktim yok
                
                //ekle
                //echo "a"; //ajax sayfayı yenilemesin caner
                $pp = new stdClass;
                
                $pp->title       = $spot;
                $pp->spot        = $spot;
                $pp->deputyID    = $model->profileID;
                $pp->status      = 1;
                $pp->datetime    = date('Y-m-d H:i:s');
                $pp->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('proposal', $pp)) {
                    $response['result'] = 'success';
                } else 
                    throw new Exception('kayıt hatası oluştu');

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }        


        public function ppadd(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $title     = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING );
                $spot   = filter_input(INPUT_POST, 'spot', FILTER_SANITIZE_STRING );
                
                //millet vekili mi?
                if($model->profile->deputy<1) throw new Exception('Sen milletvekili değilsin ki!');                
                
                //aynı başlıktan var mı?
                
                //valla vaktim yok
                
                //ekle
                
                $pp = new stdClass;
                
                $pp->title       = $title;
                $pp->spot        = $spot;
                $pp->deputyID    = $model->profileID;
                $pp->status      = 1;
                $pp->datetime    = date('Y-m-d H:i:s');
                $pp->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('proposal', $pp)) {
                    $response['result'] = 'success';
                } else 
                    throw new Exception('kayıt hatası oluştu');

            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        } 
        
        public function ppvote(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID     = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT );
                $vote   = filter_input(INPUT_POST, 'vote', FILTER_SANITIZE_STRING );
                
                //if($ID == $model->profileID) throw new Exception('iyi misin?');
                
                $db->setQuery("SELECT pp.* FROM proposal AS pp WHERE pp.ID=".$db->quote($ID)." AND pp.status>0 AND pp.datetime>" . $db->quote(date('Y-m-d H:i:s', LASTELECTION)));
                $pp = null;
                if($db->loadObject($pp)) {
                    //if($pp->deputyID==$model->profileID) throw new Exception('bu senin tasarın. iyi misin?');
                } 
                else 
                {
                	throw new Exception('tasarı yok ki');
				}
                $db->setQuery("SELECT ppv.* FROM proposalvote AS ppv WHERE ppv.deputyID=".$db->quote($model->profileID)." AND ppv.proposalID=".$db->quote($ID));
                $ppvote = null;
                if(!$db->loadObject($ppvote)){
                    //first deputy start
                    $ppvote = new stdClass;
                    $ppvote->proposalID = $ID;
                    $ppvote->deputyID    = $model->profileID;
                    foreach(config::$proposalvotetypes AS $vt){
                        if($vote == $vt) $ppvote->$vt = 1;
                        //elseif($vt !='complaint') $ppvote->$vt = 0;
                        else $ppvote->$vt = 0;
                    }
                    $ppvote->datetime    = date('Y-m-d H:i:s');
                    $ppvote->ip          = $_SERVER['REMOTE_ADDR'];
                    $ppvote->status      = 1;
                    if($db->insertObject('proposalvote', $ppvote)){
                        $response['result'] = 'success';
                        $response['buttons'] = proposal::getbuttons($pp->ID);
                        //$ID2 = $db->insertid();
                        $db->setQuery("SELECT deputyID FROM proposal WHERE ID=".$db->quote($pp->ID));
                        $profileID = intval($db->loadResult());
                        //$model->notice($profileID, 'proposalvote', $ID);
                        
                    } else {
                        $response['result'] = 'error';
                    }
                    //first contact end
                } else {
                    foreach(config::$proposalvotetypes AS $vt){
                        if($vote == $vt) $ppvote->$vt = 1;
                        //elseif($vt !='complaint') $ppvote->$vt = 0;
                        else $ppvote->$vt = 0;
                    }
                    $ppvote->datetime    = date('Y-m-d H:i:s');
                    $ppvote->ip          = $_SERVER['REMOTE_ADDR'];
                    $ppvote->status      = 1;
                    
                    if($db->updateObject('proposalvote', $ppvote, 'ID')){
                        $response['result'] = 'success';
                        $response['buttons'] = proposal::getbuttons($pp->ID);
                        //$ID2 = $db->insertid();
                        $db->setQuery("SELECT deputyID FROM proposal WHERE ID=".$db->quote($pp->ID));
                        $profileID = intval($db->loadResult());
                        //$model->notice($profileID, 'proposalvote', $ID);
                    } else {
                        $response['result'] = 'error';
                    }                    
                    
                    
                    
                }
            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            $proposalClass= new proposal;
			$uygunmu=$proposalClass->check_flashProposal($ID);
			if($uygunmu)
			{
				$proposalClass->proposalToAgenda($ID);
			}
            echo json_encode($response);
        }        
        
        
        
        public function deputyadd(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                if($ID == $model->profileID) throw new Exception('iyi misin?');
                
                $db->setQuery("SELECT p.*, u.email AS email FROM profile AS p, user AS u WHERE p.ID=".$db->quote($ID)." AND u.ID=p.ID AND p.status>0");
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profil yok ki');
                
                $db->setQuery("SELECT COUNT(*) FROM mydeputy AS md WHERE md.profileID=".$db->quote($model->profileID)." AND md.status>0 AND md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) )."");
                $deputy_count = intval( $db->loadResult() );
                if($deputy_count>=config::$mydeputylimit)throw new Exception('limiti astin');
                
                
                $db->setQuery("SELECT md.* FROM mydeputy AS md WHERE md.profileID=".$db->quote($model->profileID)." AND md.deputyID=".$db->quote($ID));
                $deputy = null;
                if(!$db->loadObject($deputy)){
                    //first deputy start
                    $deputy = new stdClass;
                    $deputy->profileID   = $model->profileID;
                    $deputy->deputyID    = $ID;
                    $deputy->datetime    = date('Y-m-d H:i:s');
                    $deputy->ip          = $_SERVER['REMOTE_ADDR'];
                    $deputy->status      = 1;
                    
                    if($db->insertObject('mydeputy', $deputy)){
                        $response['result'] = 'success';
                        $deputy->ID = $db->insertid();
                        $model->notice($ID, 'deputy', $deputy->ID);
						 $puanClass=new puan;
                   		 $puanClass->puanIslem($deputy->deputyID, "70", $deputy);
						 
                        if($profile->emailperms>0)
                            $model->sendsystemmail( $profile->email, 'Vekil seçiminde bir oy kazandınız', 'Tebrik ediyoruz, <br /> <a href="http://democratus.com/profile/'.$model->profileID.'"> '.$model->profile->name.' </a> isimli destekçiniz önümüzdeki haftanın meclisi için sizi, vekil adayı olarak gösterdi.  En çok oyu alan adaylardan biri olun ve vekil seçilerek meclis gündemini siz belirleyin. <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                    } else {
                        $response['result'] = 'error';
                    }
                    //first contact end
                } else {
                    //daha Ã¶nce kontakt var, duruma bakÄ±yoruz
                    //$deputy->profileID = $model->profileID;
                    //$deputy->deputyID = $ID;
                    $lastdeputy = strtotime($deputy->datetime);
                    
                    $deputy->datetime    = date('Y-m-d H:i:s');
                    $deputy->ip          = $_SERVER['REMOTE_ADDR'];
                    $deputy->status      = 1;
                    
                    if($db->updateObject('mydeputy', $deputy, 'ID')){
                        $response['result'] = 'success';
                        $model->notice($ID, 'deputy', $deputy->ID);
						 $puanClass=new puan;
                   		 $puanClass->puanIslem($deputy->deputyID, "70", $deputy);
                        //$response['datetime'] = date('Y-m-d H:i:s', $lastdeputy);
                        if($lastdeputy<(time() - 7 * 24 * 60 * 60 ) && ($profile->emailperms>0)) // 7 gün önce 
                            $model->sendsystemmail( $profile->email, 'Vekil seçiminde bir oy kazandınız', 'Tebrik ediyoruz, <br /> <a href="http://democratus.com/profile/'.$model->profileID.'"> '.$model->profile->name.' </a> isimli destekçiniz önümüzdeki haftanın meclisi için sizi, vekil adayı olarak gösterdi.  En çok oyu alan adaylardan biri olun ve vekil seçilerek meclis gündemini siz belirleyin. <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                        
                    } else {
                        $response['result'] = 'error';
                    }                    
                    
                    
                    
                }
            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }        
        
        public function deputyremove(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                if($ID == $model->profileID) throw new Exception('iyi misin?');
                
                $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID=".$db->quote($ID)." AND p.status>0");
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profil yok ki');
                
                $db->setQuery("SELECT md.* FROM mydeputy AS md WHERE md.profileID=".$db->quote($model->profileID)." AND md.deputyID=".$db->quote($ID));
                $deputy = null;
                if(!$db->loadObject($deputy)){
                    //first deputy start
                    $deputy = new stdClass;
                    $deputy->profileID   = $model->profileID;
                    $deputy->deputyID    = $ID;
                    //$deputy->datetime    = date('Y-m-d H:i:s');
                    $deputy->ip          = $_SERVER['REMOTE_ADDR'];
                    $deputy->status      = 0;
                    
                    if($db->insertObject('mydeputy', $deputy)){
                        $response['result'] = 'success';
                    } else {
                        $response['result'] = 'error';
                    }
                    //first contact end
                } else {
                    //daha Ã¶nce kontakt var, duruma bakÄ±yoruz
                    //$deputy->profileID = $model->profileID;
                    //$deputy->deputyID = $ID;
                    //$deputy->datetime    = date('Y-m-d H:i:s');
                    $deputy->ip          = $_SERVER['REMOTE_ADDR'];
                    $deputy->status      = 0;
                    
                    if($db->updateObject('mydeputy', $deputy, 'ID')){
                        $response['result'] = 'success';
                    } else {
                        $response['result'] = 'error';
                    }                    
                    
                    
                    
                }
            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }        
        
        public function agendavote(){
            global $model, $db, $l;
            //$agendaID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $vote = filter_input(INPUT_POST, 'vote', FILTER_SANITIZE_STRING);
            $profileID = intval( $model->profileID );
            
            $response = array();
            
            try{
                if(preg_match('/parliament_choose_(\d+)_(\d+)/i', $vote, $m)){
                    $agendaID = intval($m[1]);
                    $vote = intval($m[2]);
                    $response["agendaID"]=$agendaID;
                    if( !array_key_exists($vote, config::$votetypes) )
                        throw new Exception('Hata oluştu');
                    
                } else 
                    throw new Exception('Hata oluştu');
                    
                    
            
                $db->setQuery('SELECT a.* FROM agenda AS a WHERE ID='.$db->quote($agendaID).' LIMIT 1');
                $agenda=null;
                if($db->loadObject($agenda)){
                    
                    $db->setQuery('SELECT av.* FROM agendavote AS av WHERE agendaID='.$db->quote($agendaID).' AND profileID='.$db->quote($profileID).' LIMIT 1');
                    $av=null;
                    if($db->loadObject($av)){
                        //voted

                        //$av = new stdClass;
                        //$av->agendaID    = $agendaID;
                        //$av->profileID   = $profileID;
                        $av->vote        = $vote;
                        $av->datetime    = date('Y-m-d H:i:s');
                        $av->ip          = $_SERVER['REMOTE_ADDR']; 
                        
                        if($db->updateObject('agendavote', $av, 'ID')){
                            //new vote saved
                            $response['result']='success';
                            $response['message']='Oyunuz Kaydedildi';
                        } else {
                            //not saved
                            //$response['result']='error';
                            //$response['message']='Kayıt Hatası';
                            throw new Exception('Kayıt Hatası');
                        }                    
                        
                    } else {
                        //not voted
                        $av = new stdClass;
                        $av->agendaID    = $agendaID;
                        $av->profileID   = $profileID;
                        $av->vote        = $vote;
                        $av->datetime    = date('Y-m-d H:i:s');
                        $av->ip          = $_SERVER['REMOTE_ADDR']; 
                        
                        if($db->insertObject('agendavote', $av)){
                            //new vote saved
                            $response['result']='success';
                            $response['message']='Oyunuz Kaydedildi';
                        } else {
                            //not saved
                            //$response['result']='error';
                            //$response['message']='Kayıt Hatası';
                            throw new Exception('Kayıt Hatası');
                        }
                    }
                    //$response['result']='success';
                } else {                
                    throw new Exception('Hata !');
                }
                    
                    
                    
                 KM::identify($model->user->email);
				 KM::record('agendavote');   
                    
                
            } catch (Exception $e){
                $response['result']='error';
                $response['message']='Hata !';
            }
           
            echo json_encode($response);
            
            
            
            
            return;
            $response = array();
            
            $db->setQuery('SELECT a.* FROM agenda AS a WHERE ID='.$db->quote($agendaID).' LIMIT 1');
            $agenda=null;
            if($db->loadObject($agenda)){
                
                $db->setQuery('SELECT av.* FROM agendavote AS av WHERE agendaID='.$db->quote($agendaID).' AND profileID='.$db->quote($profileID).' LIMIT 1');
                $av=null;
                if($db->loadObject($av)){
                    //voted

                    //$av = new stdClass;
                    //$av->agendaID    = $agendaID;
                    //$av->profileID   = $profileID;
                    $av->vote        = $vote;
                    $av->datetime    = date('Y-m-d H:i:s');
                    $av->ip          = $_SERVER['REMOTE_ADDR']; 
                    
                    if($db->updateObject('agendavote', $av, 'ID')){
                        //new vote saved
                        $response['result']='success';
                        $response['message']='Oyunuz Kaydedildi';
                    } else {
                        //not saved
                        $response['result']='error';
                        $response['message']='Kayıt Hatası';
                    }                    
                    
                } else {
                    //not voted
                    $av = new stdClass;
                    $av->agendaID    = $agendaID;
                    $av->profileID   = $profileID;
                    $av->vote        = $vote;
                    $av->datetime    = date('Y-m-d H:i:s');
                    $av->ip          = $_SERVER['REMOTE_ADDR']; 
                    
                    if($db->insertObject('agendavote', $av)){
                        //new vote saved
                        $response['result']='success';
                        $response['message']='Oyunuz Kaydedildi';
                    } else {
                        //not saved
                        $response['result']='error';
                        $response['message']='Kayıt Hatası';
                    }
                }
                //$response['result']='success';
            } else {                
                $response['result']='error';
                $response['message']='Hata !';
            }
            
            //$response['image'] = $model->getProfileImage($model->profile->image, 60, 60, 'cutout');
            
            echo json_encode($response);
        }
        
        
        public function dicomment(){
            global $model, $db;
            
            $response = array();
            try{
                
                
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $comm = htmlspecialchars_decode( filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING), ENT_QUOTES);
                $comm = $model->splitword($comm);
                $comm = mb_substr($comm, 0, 250);
                
                //$comm = mb_substr($model->splitword($comm), 0, 200);
                //$comm = $model->splitword( strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING), ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) ), 0, 20 );
                //$comm = mb_substr( $model->splitword( strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING), ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) )), 0, 200 );
                //echo(strlen($comm));
                //echo($comm);
                //die;
            
                $db->setQuery("SELECT * FROM di WHERE ID=".$db->quote($ID) . " LIMIT 1");
                $di = null;
                if(!$db->loadObject($di)) throw new Exception('share not found');
                
                $comment = new stdClass;
                
                $comment->diID = intval($ID);
                $comment->profileID = intval($model->profileID);
                $comment->comment = $comm;
                $comment->status = 1;
                $comment->datetime    = date('Y-m-d H:i:s');
                $comment->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('dicomment', $comment)) {
                    $dicommentID = $db->insertid();
                    $comment->ID=$dicommentID;
                    $response['result'] = 'success';
                    $response['commentID'] = $dicommentID;
                    $response['comment'] = di::getdicomment($dicommentID);
                    
                    //notice
                    $db->setQuery("SELECT count(profileID) FROM notsendnotice WHERE diID='".$di->ID."' and profileID='".$di->profileID."'");
                    if($db->loadResult()<1)
                    $model->notice($di->profileID, 'dicomment', $dicommentID, $ID);
                    
                    //other commenters notice
                    $db->setQuery("SELECT profileID FROM notsendnotice WHERE diID='".$di->ID."'");
                    $notNotice=$db->loadResultArray();
                   	if(count($notNotice))
                    $db->setQuery("SELECT * FROM dicomment WHERE diID=".$di->ID." AND profileID NOT IN (".implode(",", $notNotice).") GROUP BY profileID");
                    else 
                    $db->setQuery("SELECT * FROM dicomment WHERE diID=".$di->ID." GROUP BY profileID");
                    
                    $dicc = $db->loadObjectList();
                    if(count($dicc))
                        foreach($dicc as $dic){
                            if($dic->profileID==$di->profileID) continue;
                            $model->notice($dic->profileID, 'dicommentcomment', $dicommentID, $ID);
                            
                        }
                   //other commenters notice END 
                   //puanEkle
                   $puanClass=new puan;
                   $puanClass->puanIslem($di->profileID, "5", $comment);
                    
                    
                } else {
                    $response['result'] = 'error';
                    $response['comment'] = '';
                }
            } catch (Exception $e){
                $response['result'] = 'error';
                $response['html'] = '';
            }
            echo json_encode($response);
        }        
        
        public function wallmore(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            
            $profileID   = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            $start       = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
            $walltype       = filter_input(INPUT_POST, 'walltype', FILTER_SANITIZE_STRING);
            
             $di = new di;
			if($walltype=="deputy")
            $response = $di->getdies($profileID, $start, 7,"deputy",0);
            else if($walltype=="follow")
            $response = $di->getdies($profileID, $start, 7,"follow",0);
            else 
            $response = $di->getdies($profileID, $start, 7,"all",0);
            echo json_encode($response);
        }
        public function cagrilarmore(){
        	global $model, $db;
        	$model->mode = 0;
        	$response = array();
        
        	$profileID   = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
        	$start       = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
        
        	$di = new di;
			$response = $di->getCagrilarDies($profileID, $start, 7);
			
        	echo json_encode($response);
        }
        public function wallmoreUp(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            
            $profileID   = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            $first       = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_NUMBER_INT);
            $walltype       = filter_input(INPUT_POST, 'walltype', FILTER_SANITIZE_STRING);
            $di = new di;
			if($walltype=="deputy")
            $response = $di->getNewdies($profileID, $first,"deputy");
            else if($walltype=="follow")
            $response = $di->getNewdies($profileID, $first,"follow");
            else 
            $response = $di->getNewdies($profileID, $first,"all");
            echo json_encode($response);
        }
        public function wallmoreMobil(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            
            $profileID   = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            $start       = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
            $walltype       = filter_input(INPUT_POST, 'walltype', FILTER_SANITIZE_STRING);
            
             $di = new di;
			if($walltype=="deputy")
            $response = $di->getdiesMobile($profileID, $start, 7,"deputy");
            else if($walltype=="follow")
            $response = $di->getdiesMobile($profileID, $start, 7,"follow");
            else 
            $response = $di->getdiesMobile($profileID, $start, 7,"all");
            echo json_encode($response);
        }
        public function diinfo(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            
            $ID   = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);

            $di = new di;
            $response = $di->getlikeinfo($ID);
            echo json_encode($response);
        }
        
        
        public function redi(){
            global $model, $db;
            $ID  = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            
            $response = array();
            
            try{
                
                
                $db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($ID) . ' AND status > 0');
                $di = null;
                if(!$db->loadObject($di)) throw new Exception('di bulunamadı');
                
                                
                $db->setQuery('SELECT p.*, u.email FROM profile AS p, user AS u WHERE p.ID = ' . $db->quote($di->profileID) . ' AND u.ID=p.ID');
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profil bulunamadı');
                
                
                //initialize the share data
                $share = new stdClass;
                //daha önce paylaşılmışsa aynı bilgileri kullanarak paylaş
                if($di->redi>0){
                    if($model->profileID != $di->redi){
                       $share->redi = $di->redi; 
                    } else {
                       $share->redi = 0; 
                    }
                } elseif ($model->profileID != $di->profileID){//kendi di'n değilse redi gibi algıla
                    $share->redi = $di->profileID;
                    $share->rediID = $ID;
                } 
                    
                
                
                    
                    
                $share->di          = $di->di;
                $share->datetime    = date('Y-m-d H:i:s');
                $share->profileID   = intval( $model->profileID );
                $share->ip          = $_SERVER['REMOTE_ADDR'];
                $share->status      = 1;
                
                if( $db->insertObject('#__di', $share) ){
                	$share->ID=$db->insertid();
                    $response['result'] = 'success';
                    $model->notice($di->profileID, 'redi', $share->ID, $di->ID);
                    if($profile->emailperms>0)
                        $model->sendsystemmail( $profile->email, 'Ses\'iniz başkaları tarafından paylaşıldı', 'Merhaba, <br /> <a href="http://democratus.com/profile/'.$model->profileID.'"> '.$model->profile->name.' </a> isimli kullanıcı sizin bir ses’inizi kendi '.profile::getfollowercount($model->profileID).' adet takipçisi ile paylaştı. Şimdi sizi daha fazla insan duyuyor. <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                    
                   $puanClass=new puan;
                   $puanClass->puanIslem($di->profileID,"4",$di);
                } else {
                    throw new Exception('record error');
                }
                
                
            } catch(Exception $e){
                //share error
                $response['result'] = 'error'; 
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
            
        }
        
        public function share(){
            global $model, $db, $dbez;
            //$type       = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
            //$profileID  = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            
            $response = array();
            
            try{
               //var_dump($_POST);
                //is allowed to share this profile?
                
                //initialize the share data
                if($model->profile->status==5)
				{
					$response["result"]="error";
					$response["message"]="Hesabınızı aktive etmeden paylaşım yapamazsınız.";
					$response["eval"]="warninShow_notActivateWriteVoice();";
					//$response["eval"]="alert('asdasd');";
					echo json_encode($response);
					die;
				}
                $share = new stdClass;
				$urlS=new urlshorter;
				$share->di=strip_tags( html_entity_decode( htmlspecialchars_decode(filter_input(INPUT_POST, 'di', FILTER_SANITIZE_STRING), ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) );
				$share->di=$urlS->changeUrlShort($share->di); 
                $share->di=  mb_substr($share->di , 0, 200 ) ; 
                $share->onlyProfile=0;
         
                if(@$_POST["linkli"]=="voice")
            	{
            		$share->di=trim($share->di);
            		if(strpos($share->di, "+voice")===false)
					{
						$share->di="+voice ".$share->di;	
					}
					if(strpos($share->di, "+voice")==0)
					$share->onlyProfile=1;
            		$share->di=str_replace("+voice", '<a href="/di/'.$_POST["sesHakkındaID"].'">+voice</a>', $share->di);
					$share->isReply="1";
					$share->replyID=$_POST["sesHakkındaID"];
				}
				else if (@$_POST["linkli"]=="profile")
            	{
            		$share->di=str_replace("@".$_POST["profileName"], '<a href="/profile/'.$_POST["profileID"].'">@'.$_POST["profileName"].'</a>', $share->di);
            	}
                //$sha
                //$share->di          =  mb_substr( $model->splitword( strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'di', FILTER_SANITIZE_STRING), ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) )), 0, 200 ) ;
                $user->ID = NULL;
            	$share->datetime    = date('Y-m-d H:i:s');
                $share->rediID   	= 0;
                $share->ip          = $_SERVER['REMOTE_ADDR'];
				$share->initem		= @$_POST["initem"];
				if(!isset($_POST["otherPID"]))
				{
					$_POST["otherPID"]="default";
				}
				if(@$_POST["otherPID"]=="default")
				{
					$share->profileID   = intval( $model->profileID );
				}
				else
				{
					$share->profileID   = intval( $_POST["otherPID"] );
					$share->profileType	= "tagPage";
				}	
                //var_dump($share);
                //die;
                if( $db->insertObject('#__di', $share,"ID") ){
                	$share->ID=$db->insertid();
					KM::identify($model->user->email);
				    KM::record('writingvoice');
                   if(@$_POST["linkli"]=="voice"){
	                		$db->setQuery("select profileID from di where ID='".$_POST["sesHakkındaID"]."'");
	                		$id = $db->loadResult();
	                		$model->notice($id, 'mentionDi', $db->insertid(),$_POST["sesHakkındaID"]);
	                		
	                		//other commenters notice
		                    //$notNotice=$db->setQuery("SELECT profileID FROM notsendnotice WHERE diID='".$_POST["sesHakkındaID"]."'");
			                $notNotice = $dbez->get_col("SELECT profileID FROM notsendnotice WHERE diID='".$_POST["sesHakkındaID"]."'");
							
		                   	if(count($notNotice))
		                    $db->setQuery("SELECT profileID FROM di WHERE replyID=".$_POST["sesHakkındaID"]." AND profileID NOT IN (".implode(",", $notNotice).") GROUP BY profileID");
		                    else 
		                    $db->setQuery("SELECT profileID FROM di WHERE replyID=".$_POST["sesHakkındaID"]." GROUP BY profileID");
		                    
		                    $dicc = $db->loadObjectList();
			             	if(count($dicc)){
			                	foreach($dicc as $dic){
			                    	if($dic->profileID==$share->profileID) continue;
			                            $model->notice($dic->profileID, 'mentiontoReplied', $share->ID, $_POST["sesHakkındaID"]);
			                            
			                        }
			             	}
			                        
							
		                    
	                	}
	                	else if (@$_POST["linkli"]=="profile")
	                	{
	                		$model->notice($_POST["profileID"], 'mentionProfile',$db->insertid());
	                	}
	                if($model->profile->facebookPaylasizin==1 && $share->onlyProfile==0)
	                {
	                	$fb=new facebooknew();
	                	$fb->facebookPost(strip_tags($share->di),$share->ID);
	                }
                	if($model->profile->twitterPaylasizin==1 && $share->onlyProfile==0) 
	                {
	                	$tw=new twitter();
	                	$tw->sendTweet(strip_tags($share->di),$share->ID);
	                }
                    $response['result'] = 'success';
                    $response['di'] = $share->di;
					
					if($share->initem=="1")
					{
						$shareimage=new stdClass;
						$shareimage->ID=null;
						$shareimage->shareID = $share->ID;
						$shareimag->profileID = $model->profileID;
						$shareimage->imagepath = "voiceImage/".$_POST["initemName"];
						
						 if( $db->insertObject('shareimage', $shareimage,"ID") ){
						 	
						 }
						 else {
							 throw new Exception('Resim Kaydedilemedi');
						 }
						
					}
					$puan=new puan();
					$puan->puanIslem($share->profileID,"1",$share);
                } else {
                    throw new Exception('record error');
                }
                
                
            } catch(Exception $e){
                //share error
                $response['result'] = 'error'; 
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
            die;
        }
        /*
        public function sharecomment(){ die();
            global $model, $db;
            
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $comm = htmlspecialchars_decode( filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING), ENT_QUOTES);
                
                echo(strlen($comm));
                die();
            
                $db->setQuery("SELECT s.* FROM share AS s WHERE s.ID=".$db->quote($ID) . " LIMIT 1");
                $share = null;
                if(!$db->loadObject($share)) throw new Exception('share not found');
                
                $comment = new stdClass;
                
                $comment->shareID = intval($ID);
                $comment->profileID = intval($model->profileID);
                $comment->comment = $comm;
                $comment->status = 1;
                $comment->userID = intval($model->user->ID);
                $comment->datetime    = date('Y-m-d H:i:s');
                $comment->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('sharecomment', $comment)) {
                    echo 'success';
                } else {
                    echo 'error';
                }
            } catch (Exception $e){
                
            }
            
        }
        */
        public function likeCancel()
        {
        	global $model;
        	$model->mode=0;
        	
        	try{
        		require_once(PLUGINPATH . 'ajax/class.ajax_like.php');
        		$ajaxlike=new ajax_like;
        		$ID=filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
        		$type=$model->paths[2];
        		
        		if($type=="di")
        			$ajaxlike->diLikeCancel($ID);
        		else if($type=="dicomment")
        			$ajaxlike->diCommentLikeCancel($ID);
        	}
        	catch(Exeption $e)
        	{
        		echo "error";
        	}
        }
        
        public function like(){
            global $model;
            $model->mode = 0;
            try{
                require_once(PLUGINPATH . 'ajax/class.ajax_like.php');
                $ajaxlike = new ajax_like;
                
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $like = filter_input(INPUT_POST, 'like', FILTER_SANITIZE_STRING);
                
                $method = (string) '' . $model->paths[2];
                if(method_exists($ajaxlike, $method )){
                    $ajaxlike->$method($ID, $like);
                } else {
                    
                }
            } catch (Exception $e){
                
            }
        }
        
        public function getsharecomments(){
            global $model, $db;
            try{
                $shareID    = filter_input(INPUT_POST, 'shareID', FILTER_SANITIZE_NUMBER_INT);
                $limit      = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);
                
                $db->setQuery("SELECT s.ID, s.status FROM share AS s WHERE s.ID=" . $db->quote( $shareID ) . " LIMIT 1");
                $share = null;
                if(!$db->loadObject($share)) 
                    throw new Exception('share not found');
                
                $SELECT = "SELECT sc.*, p.name, p.image AS profileimage FROM sharecomment AS sc";
                $JOIN   = "\n LEFT JOIN profile AS p ON p.ID = sc.profileID";
                $WHERE  = "\n WHERE sc.shareID=" . $db->quote( $shareID ) . " AND sc.status>0";
                $ORDER  = "\n ";
                $LIMIT  = "\n ";
                $COMMENT= "\n #".__FILE__." - ".__LINE__;
                
                
                //$db->setQuery("SELECT sc.* FROM sharecomment AS sc WHERE sc.shareID=" . $db->quote( $shareID ) . " AND sc.status>0" );
                $db->setQuery( $SELECT . $JOIN . $WHERE . $LIMIT. $COMMENT);
                $comments = $db->loadObjectList();
                
                if(count($comments)){
                    $i=0;
                    foreach($comments as $comment){
                        $i++;
                        $sharecommentlike = $this->getsharecommentlikeinfo($comment->ID);
                        
?>
              <div class="commentbox" id="comment<?=$comment->ID?>">
                <div class="commentimg"><img src="<?=$model->getProfileImage($comment->profileimage, 60, 60, 'cutout')?>" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"><?=$comment->comment?></div>
                  <div class="commentlikebox" id="commentlikebox<?=$comment->ID?>">
                    <div class="sharecommentappreciatebutton" id="sharecommentappreciatebutton<?=$comment->ID?>" rel="<?=$comment->ID?>" onclick="javascript:sharecommentlike(<?=$comment->ID?>, 2)">Takdir Et (<?=$sharecommentlike->appreciate?>)</div>
                    <div class="sharecommentregardbutton" id="sharecommentregardbutton<?=$comment->ID?>" rel="<?=$comment->ID?>" onclick="javascript:sharecommentlike(<?=$comment->ID?>, 1)">Saygı Duy (<?=$sharecommentlike->regard?>)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong><?=$comment->name?></strong> <?=$comment->datetime?>de yazdı.</div>
              </div>
<?php
                    }
                    
                    
                    
                }
                
?>



<?php                
                
                
                
            
            } catch (Exception $e) {
                
            }
        }
        
        
        private function getsharecommentlikeinfo($ID){
            global $model, $db;
            
            $db->setQuery('SELECT SUM(regard) AS regard, SUM(appreciate) AS appreciate FROM sharecommentlike WHERE commentID = ' . $db->quote($ID) );
            if( $db->loadObject($result) )
                return $result;
            else {
                return (object) array('regard'=>0, 'appreciate'=>0 );
            }
        }


        
        public function userrequest(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $valid = filter_input(INPUT_POST, 'recaptcha_challenge_field');
                
                if(strlen($email)<2) throw new Exception('login - email - error');
                
                require_once(PLUGINPATH.'lib/recaptcha/recaptchalib.php');
                
                $resp = recaptcha_check_answer ("6LdXIQsAAAAAALPkziAcWU2gEs74F2C8_ixJhYoJ",$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
                if (!$resp->is_valid)  throw new Exception('gÃ¼venlik kodu hatalÄ±');
                
                
                $SELECT = "SELECT u.*";
                $FROM   = "\n FROM #__user AS u";
                $WHERE  = "\n WHERE u.email=".$db->quote($email);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                
                $result = null;
                if( $db->loadObject($result) ){    
                    //zaten Ã¼ye
                    throw new Exception('bu email zaten Ã¼ye');
                }
                            
                
                $SELECT = "SELECT ur.*";
                $FROM   = "\n FROM #__userrequest AS ur";
                $WHERE  = "\n WHERE ur.email=".$db->quote($email);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                
                $result = null;
                if( $db->loadObject($result) ){
                    //zaten talep edilmis
                    
                    
                    
                } else {
                    //new request                                            
                    $request = new stdClass;
                    
                    $request->email     = strtolower( trim( $email ) );
                    $request->key       = md5( KEY . time() . uniqid() );
                    $request->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                    $request->datetime  = date('Y-m-d H:i:s');
                    $request->status    = 0;
                    
                    if($db->insertObject('userrequest', $request)){
                        $response['status'] = 'success';
                        mail($request->email, 'baÅŸvuru onayÄ±', 'onay linki: http://democratus.com/user/activate/'.$request->key);
                        
                        
                    } else {
                        throw new Exception('kayÄ±t hatasÄ±');
                    }
                }
                
            } catch (Exception $e){
                //die('error');
                $response['status'] = 'error';
                $response['status-message'] = $e->getMessage();
                $response['status-code'] = $e->getCode();
            }
            
            echo json_encode($response);
        }      
        
        public function login(){
            global $model, $db;
            $model->mode = 0;
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
            $response = array();
            try{
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $pass  = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
                $remember  = filter_input(INPUT_POST, 'remember', FILTER_SANITIZE_NUMBER_INT);
                
                if(strlen($email)<2) throw new Exception('login - email - error');
                if(strlen($pass)<2) throw new Exception('login - password - error');
                
                //die($email . ' - ' . $pass);
                $pass = md5(KEY . trim( $pass ) );
                
                //die ($pass);
                
                //di.datetime > DATE_ADD(NOW(), INTERVAL -1 DAY)
                $SELECT	= "SELECT count(*) as Sayi ";
				$FROM	= "\n FROM loginsec";
				$WHERE	= "\n WHERE loginfailuredate > DATE_ADD(NOW(), INTERVAL -10 MINUTE)";
				//$WHERE	.= "\n and (kullanici=".$db->quote($email)." or ip=".$db->quote($_SERVER['REMOTE_ADDR']).")";
				$WHERE	.= "\n and (kullanici=".$db->quote($email).")";
                $db->setQuery( $SELECT . $FROM . $WHERE );
				if( $db->loadObject($result) )
				{
					if($result->Sayi>6)
					{
						throw new Exception('Çok fazla hatalı giriş denemesi yaptınız, lütfen daha sonra tekrar deneyin. 
Eğer parolanızı unuttuysanız Şifremi Unuttum butonuna tıklayabilirsiniz.');
					}
					
				}
                if($model) 
                
                $SELECT = "SELECT u.*, p.temelPuanHesaplandi";
                $FROM   = "\n FROM user AS u";
				$JOIN	= "\n LEFT JOIN profile as p on p.ID=u.ID";
                $WHERE  = "\n WHERE (u.email=".$db->quote($email)." OR p.permalink=".$db->quote($email).") AND u.pass = ".$db->quote($pass);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $JOIN .  $WHERE . $LIMIT );
                
                $result = null;

                if( $db->loadObject($result) ){
                    /**                       
                    if($result->ID==1001) {
                        echo $remember;
                        die('bitti');
                    }
                    /**/
                    //login ol
                    $session = new stdClass;
                    
                    $session->sid        = md5( KEY . session_id() . uniqid() );
                    $session->ip        =  filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING ) ;
                    if($remember>0)
                        $session->timeout   = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT * 30)); //saniye
                    else
                        $session->timeout   = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT)); //saniye
					$prIP = new stdClass();
					$prIP->lastLoginIP = $_SERVER['REMOTE_ADDR'];
					$prIP->ID=$result->ID; 
					$db->updateObject('profile', $prIP,"ID");
                    
					$session->starttime = date('Y-m-d H:i:s');
                    $session->endtime   = date('Y-m-d H:i:s');
                    $session->userID    = $result->ID;
                    $session->profileID = $result->ID;
                    $session->remember  = $remember;
                    $session->status    = 1;
                    
                    if($db->insertObject('session', $session)){
                        //logged in
                        
                        //oturum idsini cookieye yaz
                        setcookie("sid", $session->sid, strtotime( $session->timeout ), COOKIEPATH, COOKIEDOMAIN);
                        //setcookie("sid", $session->sid, time()+intval(SESSIONTIMEOUT), COOKIEPATH, COOKIEDOMAIN);
                        
                        //yÃ¶nlendirme gerekiyorsa yÃ¶nlendir
                        //$redirecturl = filter_input(INPUT_COOKIE, 'redirecturl', FILTER_SANITIZE_URL);
                        /*
                        if(strlen($redirecturl)){
                            unset( $_COOKIE['redirecturl'] );
                            $model->redirect($redirecturl, 1); die;
                        } else {
                            $model->redirect('/', 1); die;
                        }
                        */
                        //echo 'success';
                        $response['status'] = 'success';
						if($result->temelPuanHesaplandi=="0")
						{
							$puan = new puan;
							$puan->temelPuanIsle($result);
						}
                        
						
                        
                    } else {
                        //not logged in
                        
						$loginSec=new stdClass;
						$loginSec->kullanici=$email;
						$loginSec->loginfailuredate=date('Y-m-d H:i:s');
						$loginSec->ip=$_SERVER['REMOTE_ADDR'];
						$db->insertObject('loginsec', $loginSec);
						
						throw new Exception('Kullanıcı adı veya şifre hatalı');
                    }
                } else {
                    $loginSec=new stdClass;
					$loginSec->kullanici=$email;
					$loginSec->loginfailuredate=date('Y-m-d H:i:s');
					$loginSec->ip=$_SERVER['REMOTE_ADDR'];
					
					$db->insertObject('loginsec', $loginSec); 
					throw new Exception('Kullanıcı adı veya şifre hatalı');
					
                }
                
            } catch (Exception $e){
                //die('error');
                
				
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
                $response['code'] = $e->getCode();
            }
            
            //print_r ($response);
            KM::identify($email);
			KM::record('login');
            echo json_encode($response);
        }        
        
        
        public function follow(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                if($ID == $model->profileID) throw new Exception('iyi misin?');
                
                $db->setQuery("SELECT p.*, u.email AS email FROM profile AS p, user AS u WHERE p.ID=".$db->quote($ID)." AND u.ID=p.ID");
                //$db->setQuery("SELECT p.*, u.email AS eposta FROM profile AS p, user AS u WHERE p.ID=".$db->quote($ID))." AND u.ID=p.ID";
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profile not found');
                
                $db->setQuery("SELECT f.* FROM follow AS f WHERE f.followingID=".$db->quote($ID)." AND f.followerID=".$db->quote($model->profileID));
                $follow = null;
                if(!$db->loadObject($follow)){
                    //first contact start
                    $follow = new stdClass;
                    $follow->followerID = $model->profileID;
                    $follow->followerstatus = 1;
                    
                    $follow->followingID = $ID;
                    $follow->followingstatus = 1;
                    $follow->datetime = date('Y-m-d H:i:s');
                    $follow->status = 1;
                    
                    if($db->insertObject('follow', $follow)){
                        $ID2 = $db->insertid();
                        $response['status'] = 'success';
                        //notice
                        $ID2 = $db->insertid();
                        $model->notice($ID, 'follow', $ID2);
                        //echo  'posta:' . $profile->eposta;
                        //die( $profile->eposta);
                        //mail gönder
                        //buraya Puan
                       	$puanClass=new puan;
                   		$puanClass->puanIslem($ID, "10");
                        if($profile->type=="profile")// profilse mail gönder hashtag ise gönderme
						{
							if($profile->emailperms>0)
                            $model->sendsystemmail( $profile->email, 'Bir yeni takipçiniz var', 'Merhaba, <br /> <a href="http://democratus.com/profile/'.$model->profileID.'"> '.$model->profile->name.' </a> isimli kullanıcı artık sizi takip ediyor.  Bundan böyle sesiniz ona da ulaşacak.  <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
						}
                        
                        
                                                                
                    } else {
                        $response['status'] = 'error';
                    }
                    //first contact end
                } else {
                    //daha önce kontakt var, duruma bakÄ±yoruz
                    $follow->followerID = $model->profileID;
                    $follow->followerstatus = 1;
                    
                    //takip ettiÄŸi kiÅŸi engellemiÅŸse ekleyemesin ***************************************
                    $follow->followingID = $ID;
                    $follow->followingstatus = 1;
                    $follow->datetime = date('Y-m-d H:i:s');
                    $follow->status = 1;
                    
                    if($db->updateObject('follow', $follow, 'ID')){
                        
                        $response['status'] = 'success';
                        //notice
                        $puanClass=new puan;
                   		$puanClass->puanIslem($ID, "10");
                        $model->notice($ID, 'follow', $follow->ID);
                    } else {
                        $response['status'] = 'error';
                    }                    
                    
                    
                    
                }
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        public function unfollow(){
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                
                $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID=".$db->quote($ID));
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profile not found');
                
                $db->setQuery("SELECT f.* FROM follow AS f WHERE f.followingID=".$db->quote($ID)." AND f.followerID=".$db->quote($model->profileID));
                $follow = null;
                if(!$db->loadObject($follow)){
                    //first contact start
                    $follow = new stdClass;
                    $follow->followerID = $model->profileID;
                    $follow->followerstatus = 1;
                    
                    $follow->followingID = $ID;
                    $follow->followingstatus = 0;
                    $follow->datetime = date('Y-m-d H:i:s');
                    $follow->status = 0;
                    
                    if($db->insertObject('follow', $follow)){
                        $response['status'] = 'success';
                    } else {
                        $response['status'] = 'error';
                    }
                    //first contact end
                } else {
                    //daha Ã¶nce kontakt var, duruma bakÄ±yoruz
                    $follow->followerID = $model->profileID;
                    $follow->followerstatus = 0;
                    
                    //takip ettiÄŸi kiÅŸi engellemiÅŸse ekleyemesin ***************************************
                    $follow->followingID = $ID;
                    $follow->followingstatus = 0;
                    $follow->datetime = date('Y-m-d H:i:s');
                    $follow->status = 0;
                    
                    if($db->updateObject('follow', $follow, 'ID')){
                        $response['status'] = 'success';
                    } else {
                        $response['status'] = 'error';
                    }                    
                    
                    
                    
                }
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        
        public function block(){ die;
            
            global $model, $db;
            $model->mode = 0;
            $response = array();
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                
                $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID=".$db->quote($ID));
                $profile = null;
                if(!$db->loadObject($profile)) throw new Exception('profile not found');
                
                $db->setQuery("SELECT f.* FROM follow AS f WHERE f.followingID=".$db->quote($ID)." AND f.followerID=".$db->quote($model->profileID));
                $follow = null;
                if(!$db->loadObject($follow)){
                    //first contact start
                    $follow = new stdClass;
                    $follow->followerID = $model->profileID;
                    $follow->followerstatus = 1;
                    
                    $follow->followingID = $ID;
                    $follow->followingstatus = 1;
                    $follow->status = 1;
                    
                    if($db->insertObject('follow', $follow)){
                        $response['status'] = 'success';
                    } else {
                        $response['status'] = 'error';
                    }
                    //first contact end
                } else {
                    //daha Ã¶nce kontakt var, duruma bakÄ±yoruz
                    $follow->followerID = $model->profileID;
                    $follow->followerstatus = 1;
                    
                    //takip ettiÄŸi kiÅŸi engellemiÅŸse ekleyemesin ***************************************
                    $follow->followingID = $ID;
                    $follow->followingstatus = 1;
                    $follow->status = 1;
                    
                    if($db->updateObject('follow', $follow, 'ID')){
                        $response['status'] = 'success';
                    } else {
                        $response['status'] = 'error';
                    }                    
                    
                    
                    
                }
            } catch (Exception $e){
                $response['status'] = 'error';
            }
            
            echo json_encode($response);
        }        
        
        
        public function getcities(){
            global $model, $db;            
            $countryID = intval( filter_input(INPUT_POST, 'countryID', FILTER_SANITIZE_NUMBER_INT) );
            $db->setQuery('SELECT ct.* FROM city AS ct WHERE ct.countryID='.$db->quote($countryID).' ORDER BY ct.city;');
            $items = $db->loadAssocList();
            echo json_encode($items);
        }
        
        public function register(){ // todo 
            global $model, $db, $l;            
            
            $model->mode = 0;
            $response = array();
            
            try{
                

                $form               = new stdClass;
                $form->name         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
				$form->userName    	= strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->email        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->password     = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->password2    = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password2'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->male    = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'male'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->captcha      = filter_input(INPUT_POST, 'captcha', FILTER_SANITIZE_STRING);                
                /*
                $form->country      = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_NUMBER_INT);
                $form->city         = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_NUMBER_INT);                
                $form->birthday     = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_NUMBER_INT);                
                $form->birthmonth   = filter_input(INPUT_POST, 'birthmonth', FILTER_SANITIZE_NUMBER_INT);                
                $form->birth        = filter_input(INPUT_POST, 'birth', FILTER_SANITIZE_NUMBER_INT);                
                */
                
				/*
                if( strlen( $form->captcha ) < 4 ){
                    $response['field'] = 'captcha';
                    throw new Exception('doğrulama kodu eksik', 1);                    
                }
                */
                
                /*    
                if( !isset($_SESSION['captcha']) || $form->captcha!=$_SESSION['captcha'] ){
                    $response['field'] = 'captcha';
                    throw new Exception('doğrulama kodu hatalı', 1);
                }
                */
                
                if( strlen( $form->name ) < 3 ){
                    $response['field'] = 'name';
                    throw new Exception('Adınızı kontrol eder misiniz.', 1);                    
                }
				
				
				$ka = $form->userName;
				
				if(strlen($ka)<3 || strlen($ka)>25)
				{
					$response['field'] = 'name';
                    throw new Exception('Kullanıcı adınız en az 6 en fazla 25 karakter olmalıdır.', 1);       
				} 
				
				$letters = "/^([a-zA-Z0-9._-]+)$/"; 
				if(!preg_match($letters, $ka))
				{
					$response['field'] = 'name';
                    throw new Exception('Sadece harf, rakam ve (- _ .) karakterlerinden oluşan bir kullanıcı adı belirlemelisiniz. ', 1); 
				}
			
				
				$query = "SELECT permalink FROM page WHERE permalink=".$db->Quote($ka);  
				$db->setQuery($query);
				$varmi="";
	            $db->loadObject($varmi);
				if(count($varmi)>0)
				{
					$response["field"]="name";
					throw new Exception('Seçmiş olduğunuz kullanıcı adı uygun değildir.', 1); 
				}
				
				$query = "SELECT permalink FROM profile WHERE permalink=".$db->Quote($ka);  
				$db->setQuery($query);
				$varmi="";
	            $db->loadObject($varmi);
				if(count($varmi)>0)
				{
					$response["field"]="name";
					throw new Exception('Seçmiş olduğunuz kullanıcı adı uygun değildir.', 1); 
				}
				               
                if(!isEmail($form->email)){
                    $response['field'] = 'email';
                    throw new Exception('Email adresi geçerli değil',1);
                }
                
                //bu mail kayıtlı mı?
                $db->setQuery("SELECT COUNT(email) FROM user WHERE email=".$db->quote($form->email));
                if(intval($db->loadResult())>0){
                    $response['field'] = 'email';
                    throw new Exception('Email adresi kayıtlı, şifrenizi yenileyebilirsiniz',1);
                }
                    
                if( strlen( $form->password ) < 4 ){
                    $response['field'] = 'password';
                    throw new Exception('Şifreniz çok kısa', 1);
                }
                    
                if($form->password != strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password2'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') ) ){
                    $response['field'] = 'password2';
                    throw new Exception('Şifreleriniz aynı değil', 1);
                }
                
                /*
                if( intval( $form->birthday ) < 1 ||  intval( $form->birthday ) > 31){
                    $response['field'] = 'birthday';
                    throw new Exception('doğum gününüz?', 1);
                }
                
                if( intval( $form->birthmonth ) < 1 ||  intval( $form->birthmonth ) > 12){
                    $response['field'] = 'birthmonth';
                    throw new Exception('doğum ayınız?', 1);
                }
                
                if( intval( $form->birth ) < 1940 ||  intval( $form->birth ) > 2000){
                    $response['field'] = 'birth';
                    throw new Exception('doğum yılınız?', 1);
                }
                $birth = mktime(0, 0, 0, $form->birthmonth, $form->birthday, $form->birth);    
                */
                //bilgiler normal mi?
                
                
                //kaydet
                $profile = new stdClass;    
                $profile->name = $form->name;
				$profile->permalink = $ka;
                $profile->status = 5;
                $profile->class = 1;
                $profile->sex = $form->male;
                
                //$profile->countryID = $form->country;
                //$profile->cityID = $form->city;
                
                //$profile->birth = date('Y-m-d', mktime(0, 0, 0, $form->birthmonth, $form->birthday, $form->birth));
                
                
                
                //profil kaydet
                
                
                //user kaydet
                
                if( $db->insertObject('profile', $profile ) ){
                        //echo 'profile ok';
                        
                        $user = new stdClass;
                        $user->email = $form->email;
                        $user->pass = md5(KEY . trim( $form->password ));
                        $user->status = 5;
                        $user->ID = $db->insertid();
                        $user->registertime = date('Y-m-d H:i:s');
                        
                        if( $db->insertObject('user', $user ) ){
                            //echo 'user ok';
                            /*
                            $pr = new stdClass;
                            $pr->ID        = $user->ID;
                            //$pr->userID    = $db->insertid();
                            
                            $db->updateObject('profile', $pr, 'ID');
                            */
                            
                            
                            $request = new stdClass;
                    
                            $request->email     = strtolower( trim( $user->email ) );
                            $request->key       = md5( KEY . time() . uniqid() );
                            $request->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                            $request->datetime  = date('Y-m-d H:i:s');
                            $request->status    = 0;
                            
                            if($db->insertObject('userrequest', $request)){
                                $response['status'] = 'success';
                                //$response['message'] = 'Kayıt başvurunuz alındı. Lütfen mail kutunuzu kontrol edin. Onay maili birkaç dakika içerisinde ulaşacaktır.';
                                $response['message'] = 'Üyeliğinizi aktive etmek için lütfen mail kutunuzu kontrol edin. Onay maili birkaç dakika içerisinde ulaşacaktır.';
                                
                                
                                //$model->sendsystemmail($request->email, 'Başvuru onayı', 'Merhabalar, <br /> Democratus.com üzerinden yapmış olduğunuz üyelik başvurunuzu tamamlamak için şu onay linkine tıklamalı veya tarayıcınızın adres çubuğuna yapıştırmalısınız:<br /><a href="http://democratus.com/user/activate/'.$request->key.'"> http://democratus.com/user/activate/'.$request->key.'</a>');
                                
                                
                                $model->sendsystemmail($request->email, 'democratus hesabınızı onaylayın', 'Merhaba, <br /> democratus hesabınızı aktif hale getirmenize sadece bir adım kaldı. Aşağıdaki linke tıklamanız yahut tarayıcınızın adres çubuğuna yapıştırmanız yeterli:<br /><a href="http://democratus.com/user/activate/'.$request->key.'"> http://democratus.com/user/activate/'.$request->key.'</a> <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                                
                                $_SESSION['captcha'] = null;
                                
                            } else {
                                
                                throw new Exception('kayıt hatası');
                            }
                            
                            
        
                            
                        } else {
                            //user ID 
                            $db->setQuery('DELETE FROM profile WHERE ID = ' . intval($user->ID) );
                            $db->uquery();
                            @mail('developer@democratus.com', 'userID çakışması', 'User ID:'.$user->ID);
                        }
                        
                        
                    }
                
                //login yap  session oluştur
                
                
                
                
                
                
                
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
        public function resetpassword(){ // die('resetpassword'); 
            global $model, $db, $l;            
            
            $model->mode = 0;
            $response = array();
            
            try{
                

                
                $email        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );

                if(!isEmail($email)){
                    $response['field'] = 'email';
                    throw new Exception('email adresi geçerli değil',1);
                }
                
                //bu mail kayıtlı mı?
                $db->setQuery("SELECT * FROM user WHERE email=".$db->quote($email));
                $user = null;
                if( ! $db->loadObject($user)){
                    $response['field'] = 'email';
                    throw new Exception('email adresi bulunamadı',1);
                }
                    
                //reset kaydı oluştur.
                
                
                $request = new stdClass;
                    
                $request->email     = strtolower( trim( $email ) );
                $request->userID    = $user->ID;
                $request->profileID = $user->ID;
                $request->key       = md5( KEY . time() . uniqid() );
                $request->ip        =  filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                $request->datetime  = date('Y-m-d H:i:s');
                $request->status    = 1;
                
                if($db->insertObject('resetpassword', $request)){
                    $response['status'] = 'success';
                    $response['message'] = 'Şifre yenileme başvurunuz alındı. Lütfen mail kutunuzu kontrol edin. Yenileme maili birkaç dakika içerisinde ulaşacaktır.';
                    $model->sendsystemmail($request->email, 'Şifre yenileme işlemi', 'Merhabalar, <br /> Democratus.com üyelik şifrenizi yenilemek için şu linkine tıklamalı veya tarayıcınızın adres çubuğuna yapıştırmalısınız:<br /><a href="http://democratus.com/user/resetpassword/'.$request->key.'"> http://democratus.com/user/resetpassword/'.$request->key.'</a>');
                    //mail($request->email, 'başvuru onayı', 'onay linki: http://democratus.com/user/activate/'.$request->key);
                    //echo '<h3>Kayıt başarılı. mailinizi kontrol ediniz.</h3>'.$request->email;
                    $_SESSION['captcha'] = null;
                    
                } else {
                    
                    throw new Exception('kayıt hatası');
                }
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response);
        }
        
     public function notsendnotice()
     {
     	global $db;
     	$nsn= new stdClass;
     	$nsn->profileID=$_POST["profileID"]; 
     	$nsn->diID=$_POST["diID"];
     	if($db->insertObject('notsendnotice', $nsn)){
     		echo '{"result":"success"}';
     	}
     }  
	  public function deleteProposal()
     {
     	global $db;
     	global $model;
     	$db->setQuery("SELECT * FROM proposal WHERE ID=".$db->quote($_POST["ID"]));
		$db->loadObject($proposal);
		if($proposal->deputyID==$model->profileID || $proposal->mecliseAlan==$model->profileID)
		{
			$pp= new stdClass;
			$pp->st=0;
			$pp->ID=$_POST["ID"];
			$db->updateObject('proposal', $pp, 'ID');
			$response['result'] = 'success';
            $response['message'] = 'ok';
            echo json_encode($response);
		}
		else
		{
			$response['result'] = 'error';
            $response['message'] = 'Tasarı Silinemedi Tekrar Deneyiniz';
            echo json_encode($response);
		}
    }
	public function getMentionPerson()
	{
		global $db;
     	global $model;
		$searchW=str_replace("@","",$_POST["searchword"]);
		 
		$SELECT = "SELECT DISTINCT f.followingID, p.*";
        $FROM   = "\n FROM #__follow AS f";
        $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followingID";
        $WHERE  = "\n WHERE f.followerID=".$db->quote($model->profileID);
        $WHERE .= "\n AND f.status>0";
        $WHERE .= "\n AND p.name like '".$searchW."%' ";
        $ORDER  = "\n ORDER BY f.datetime DESC";
        $LIMIT  = "\n LIMIT 10";
		
     	$db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$ORDER.$LIMIT);
		$kisiler=$db->loadObjectList();
		$secili=" ui-state-hover";
		$returnT= '<ol id="selectable">';
			foreach($kisiler as $k)
			{
				
				$returnT.='<li class="ui-widget-content'.$secili.'" rel="'.$searchW.'">';
				$returnT.='<img src="'.$model->getProfileImage($k->image, 30,30, 'cutout').'" width="30" height="30" />';
				$returnT.='<input type="hidden" class="userID" value="'.$k->ID.'" />';
				$returnT.=$k->name.'</li>';
				$secili="";
			}
			
			$returnT.='</ol>';
		echo $returnT;
	}
	public function yazmaizni()
	{
		global $db;
     	global $model;
     	if($_GET)
     	{
     		echo "<pre>";
     		var_dump($_GET);
     		echo "</pre>";
     	}
     	$fbn= new facebooknew();
     	$result = $fbn->yazmaizniVarmi();
     	if($result["durum"]=="login")
     	header( "Location: " . $result["loginUrl"] );
     	var_dump($result);
	}
	public function noticeCount()
	{
		global $model, $db;
		
		$SELECT = "SELECT count(*) FROM (SELECT distinct n.ID3";
		$FROM   = "\n FROM notice AS n";
		$JOIN   = "\n JOIN profile AS p ON p.ID=n.fromID";
		$WHERE  = "\n WHERE n.profileID=".$db->quote(intval( $model->profileID ));
		$WHERE .= "\n AND n.datetime>".$db->quote( asdatetime( $model->profile->noticetime, 'Y-m-d H:i:s' ));
		$WHERE .= "\n AND n.ID3 UNION ";
		$WHERE .= " SELECT distinct n.ID2 FROM notice AS n JOIN profile AS p ON p.ID=n.fromID ";
		$WHERE .= " WHERE n.profileID=".$db->quote(intval( $model->profileID ));
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
			echo intval( $result );
		else
			echo null;
	}
	public function interaction()
	{
		global $model, $db; 
		$lastID= filter_input(INPUT_POST, 'first', FILTER_SANITIZE_NUMBER_INT ) ;
		$limit= filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT ) ;
		$SELECT = "SELECT di.* , p.image AS sharerimage, p.ID AS sharerID, p.name AS sharername, p.showdies, p.dicomment ";
		$FROM   = "\n FROM di AS di";
		$JOIN   = "\n JOIN profile AS p ON p.ID=di.profileID";
		$WHERE  = "\n WHERE di.profileID in (select followingID from follow where followerstatus=1 and followingstatus=1 and followerID='".$model->profileID."')";
		$WHERE  .= "\n AND di.status=1 and p.status=1";
		$WHERE  .="\n AND di.ID>".$lastID;
		$ORDER  ="\n order by di.datetime desc";
		$LIMIT  = "\n LIMIT 10";
		

		$db->setQuery($SELECT . $FROM . $JOIN . $WHERE. $ORDER . $LIMIT);
		$result = $db->loadObjectList();
		$response=array();
		$response["first"]=$result[0]->ID;
		
		$html="";
		if(count($result))
		{
			$response["count"]=count($result);
			$response["result"]="success";
			foreach($result as $row)
			{
				
				if($row->rediID>0)
				{
					$html.='<li class="iaLine" style="background-color:#ffab61;" id="interactionLine-'.$row->ID.'">
								
								<i class="icon-comment"></i>
								<a href="/profile/'.$row->sharerID.'">
									'.$row->sharername.'
								</a> ; 
								<a href="/di/'.$row->ID.'">
									Bir ses 
								</a>
								Paylaştı.
							</li>';
					//di paylaştı
				}
				else
				{
					$html.='<li class="iaLine" style="background-color:#ffab61;" id="interactionLine-'.$row->ID.'">
								
								<i class="icon-comment"></i>
								<a href="/profile/'.$row->sharerID.'">
									'.$row->sharername.'
								</a> ; 
								<a href="/di/'.$row->ID.'">
									Bir ses 
								</a>
								yazdı.
								</a>
							</li>';
				}
				
				//echo $row->sharername." kişisi ".$row->ID." id li bir ses yazdı";
				//di yazdı
				//echo "<br/>";
			}
		}
		else
		{
			$response["result"]="error";
		}
		$response["html"]=$html;
		echo json_encode($response);
		//die;
	}
	public function mobileDumyService()
	{
		global $db;
		$SELECT = "SELECT di.* ";
		$FROM   = "\n FROM di AS di";
		$LIMIT  = "\n LIMIT 10";
		
		
		$db->setQuery($SELECT . $FROM  . $LIMIT);
		$result = $db->loadObjectList();
	
		echo json_encode($result);
		
	}
	public function get_voiceImage()
	{
		global $db,$model;
		$voiceID = filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
		$db->setQuery("select * from shareimage where shareID=".$db->quote($voiceID)." and status='1' limit 1 ");
		if($db->loadObject($resim))
		{
			$response["success"]="success";
			$response["image"]=$resim;
			$response["reeImagePath"]=$model->getImage($resim->imagepath,500,700,"scale");
		}
		else
		{
			$response["success"]="success";
			$response["errorMessage"]="Resim Bulunamadı";
		}
		
		echo json_encode($response);
	}
	public function get_repliedVoice()
	{
		global $db,$model;
		$voiceID = filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
		$diClass=new di;
		$response["success"]="success";
		$response["html"]=$diClass->get_singleDi($voiceID);
		echo json_encode($response);
	}
	public function get_voiceReply()
	{ 
		global $db,$model;
		
		$voiceID = filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
		$diClass=new di;
		$response["success"]="success";
		$voiceDt=$diClass->get_replyVoice($voiceID);
		$response["count"]=$voiceDt["count"];
		$response["html"]=$voiceDt["html"];
		echo json_encode($response);
	}
	public function upload_image()
	{
		//Example of how to use this uploader class...
		//You can uncomment the following lines (minus the require) to use these as your defaults.
		
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("jpeg","jpg","gif","png");
		// max file size in bytes
		$sizeLimit = 4 * 1024 * 1024;
		
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$upDir="voiceImage";
		if(@$_REQUEST["uploadType"]=="hasTag")
		{
			$upDir="hashtag/".$_REQUEST["hastag"];
			
			if(!file_exists(UPLOADPATH.$upDir))
			{
				$olustur = mkdir(UPLOADPATH.$upDir, 0777);
			}
		}

		$result = $uploader->handleUpload(UPLOADPATH.$upDir.'/');
		$result["uploadDir"]=$upDir;
	
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	public function set_popularToProposal()
	{
		global $model;
		$popularDiID=intval($_POST["populardiID"]);
		$rJson=array();
		$ppClass=new proposal;
		$sonuc=$ppClass->set_popularDitoProposal($popularDiID);
		if($sonuc)
		{
			$rJson["success"]="success";
		}
		else
		{
			$rJson["success"]="error";
		}
		echo json_encode($rJson);
	}
	public function get_profilePoppver()
	{
		$profileC=new profile;
		$returnData["html"]=$profileC->get_profilePopover(intval($_POST["ID"]));
		echo json_encode($returnData);
	}
	public function send_activationMail()
	{
		global $model,$db;
		if($_POST["mail"]!="")
		{
			$mail=$_POST["mail"];
		}
		else
		{
			$mail=$model->user->email;
		}
		
		$db->setQuery("SELECT * FROM userrequest WHERE email='".$mail."' and status=0");
		$userRequest = $db->loadResultArray();
		if(count($userRequest)>0)
		{
			$ur=$userRequest[0];
			$model->sendsystemmail($mail, 'democratus hesabınızı onaylayın', 'Merhaba, <br /> democratus hesabınızı aktif hale getirmenize sadece bir adım kaldı. Aşağıdaki linke tıklamanız yahut tarayıcınızın adres çubuğuna yapıştırmanız yeterli:<br /><a href="http://democratus.com/user/activate/'.$ur->key.'"> http://democratus.com/user/activate/'.$ur->key.'</a> <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
		}
		else {
                $request = new stdClass;            
	        $request->email     = strtolower( trim( $mail ) );
	        $request->key       = md5( KEY . time() . uniqid() );
	        $request->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
	        $request->datetime  = date('Y-m-d H:i:s');
	        $request->status    = 0;
	                            
                    if($db->insertObject('userrequest', $request)){
                            $response['status'] = 'success';
                            $response['message'] = 'Üyeliğinizi aktive etmek için lütfen mail kutunuzu kontrol edin. Onay maili birkaç dakika içerisinde ulaşacaktır.';
                            $model->sendsystemmail($mail, 'democratus hesabınızı onaylayın', 'Merhaba, <br /> democratus hesabınızı aktif hale getirmenize sadece bir adım kaldı. Aşağıdaki linke tıklamanız yahut tarayıcınızın adres çubuğuna yapıştırmanız yeterli:<br /><a href="http://democratus.com/user/activate/'.$request->key.'"> http://democratus.com/user/activate/'.$request->key.'</a> <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                            $_SESSION['captcha'] = null;
                    } else {
                            throw new Exception('kayıt hatası');
                    }
		}
	}
	public function get_followingMore()
	{
		global $model, $db;
		$response=array();
		$profileID=$_POST["profileID"];
		$limit=$_POST["limit"];
		$start=$_POST["start"];
		$c_profile=new profile(profile::get_porfileObject($profileID));
		$rows=$c_profile->get_following($limit,$start);
		$str = $c_profile->get_porfileMiniHtml($rows);
		$str = preg_replace('/(\v|\s)+/', ' ', $str);
		$response["html"]=$str;
		$response["result"]="success";
		echo json_encode($response);
	}
	public function get_moreReply()
	{
		global $model;
		$diID= filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
		$start= filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		$limit= filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);
		$response=new stdClass;
		$dreplied=di::get_voiceReply($diID,$start,$limit);
		$response->result="success";
		$response->html=$dreplied["html"];
		$response->start=$dreplied["start"];
		$response->stop=$dreplied["stop"];
		$response->count=$dreplied["count"];
		echo json_encode($response);
	}
 }
?>
