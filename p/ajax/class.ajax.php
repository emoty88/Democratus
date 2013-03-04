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
	public function get_wall(){
		global $model, $db;
		$model->mode = 0;
		$response = new stdClass;
		
		$start		= filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		$limit		= filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);
        $profileID	= filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
        $onlyProfile= filter_input(INPUT_POST, 'onlyProfile', FILTER_SANITIZE_NUMBER_INT);
		$hashTag 	= filter_input(INPUT_POST, 'hashTag', FILTER_SANITIZE_STRING);
		$keyword 	= filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
		$pos	 	= filter_input(INPUT_POST, 'pos', FILTER_SANITIZE_STRING);
		
        $c_voice 	= new voice;
		$response->status	= "success";
		$response->voices	= $c_voice->get_voices_for_wall($profileID, $start, $limit ,$onlyProfile, $hashTag, $keyword, $pos);
        echo json_encode($response);
	}
	public function get_newWallCount()
	{
		global $model, $db;
		
		$start		= filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		$limit		= filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);
        $profileID	= filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
        $onlyProfile= filter_input(INPUT_POST, 'onlyProfile', FILTER_SANITIZE_NUMBER_INT);
		$hashTag = filter_input(INPUT_POST, 'hashTag', FILTER_SANITIZE_STRING);
		$keyword = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
		$pos	 = filter_input(INPUT_POST, 'pos', FILTER_SANITIZE_STRING);

		$c_voice = new  voice();
		echo  $c_voice->get_newVoiceCount($profileID, $start, $limit ,$onlyProfile, $hashTag, $keyword, $pos);
	}
	public function get_archiveSearch()
	{
		global $model, $db;
		$model->mode = 0;
		$response = new stdClass;
		
		$start		= filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		$keyword = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
		
        $c_parliament	 	= new parliament;
		$response->status	= "success";
		$agenda				= $c_parliament->get_archiveSearch($keyword, 20, $start);
		//$response->users	= $c_profile->get_profileMultiReturtnObj($users);
	}
	public function get_userSearch()
	{
		global $model, $db;
		$model->mode = 0;
		$response = new stdClass;
		
		$start		= filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		$keyword = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
		
        $c_profile 	= new profile;
		$response->status	= "success";
		$users				= $c_profile->get_userSearch($keyword, 20, $start);
		$response->users	= $c_profile->get_profileMultiReturtnObj($users);
        echo json_encode($response);
	}
	public function get_voiceSearch()
	{
		global $model, $db;
		$model->mode = 0;
		$response = new stdClass;
		
		$start		= filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		$keyword = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
		
        $c_voice 	= new voice;
		$response->status	= "success";
		$response->voices	= $c_voice->get_voiceSearch($keyword, 20, $start);
        echo json_encode($response);
	}
	public function get_voiceImage()
	{
		global $db,$model;
		$voiceID = filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
		$db->setQuery("select * from shareimage where shareID=".$db->quote($voiceID)." and status='1' limit 1 ");
		
		if($db->loadObject($resim))
		{
			$response["status"]="success";
			$response["image"]=$resim;
			$response["reeImagePath"]=$model->getImage($resim->imagepath,500,700,"scale");
		}
		else
		{
			$response["status"]="error";
			$response["errorMessage"]="Resim Bulunamadı";
		}
		
		echo json_encode($response);
	}
	public function set_share_voice()
	{
        global $model, $db;
        $response = array();
		
        try{
        	
            if($model->profile->status==5) // yeni versiyonda düzenlenecek;
			{
				$response["status"]="error";
				$response["message"]="Hesabınızı aktive etmeden paylaşım yapamazsınız.";
				$response["eval"]="warninShow_notActivateWriteVoice();";
				echo json_encode($response);
				die;
			}
			
            $share 	= new stdClass;
			$urlS	= new urlshorter;
			$c_voice= new voice;
			$share->di=strip_tags( html_entity_decode( htmlspecialchars_decode(filter_input(INPUT_POST, 'voice_text', FILTER_SANITIZE_STRING), ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) );
			$share->di=$urlS->changeUrlShort($share->di); 
            $share->di=  mb_substr($share->di , 0, 200 ) ; 
            $share->onlyProfile=0;
    
            if(@$_POST["replying"]>0)// yeni versiyonda düzenlenicek // linkler otomatik gelecek
        	{
        		$share->di=trim($share->di);
        		if(strpos($share->di, "+voice")===false)
				{
					$share->di="+voice ".$share->di;	
				}
				if(strpos($share->di, "+voice")==0)
				$share->onlyProfile=1;
        		$share->di=str_replace("+voice", '<a href="/voice/'.$_POST["replying"].'">+voice</a>', $share->di);
				$share->isReply="1";
				$share->replyID=$_POST["replying"];
			}
			else
			{
				$share->isReply="0";
				$share->replyID="0";
			}
			if (@$_POST["linkli"]=="profile")// yeni versiyonda düzenlenicek 
        	{
        		$share->di=str_replace("@".$_POST["profileName"], '<a href="/profile/'.$_POST["profileID"].'">@'.$_POST["profileName"].'</a>', $share->di);
        	}
            
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
			
            if( $db->insertObject('di', $share,"ID") ){
            	$share->ID=$db->insertid();
				//KM::identify($model->user->email); // aktif edilince açılacak 
			    //KM::record('writingvoice');
			     
               if($share->isReply == "1"){
                		$db->setQuery("select profileID from di where ID='".$share->replyID."'");
                		$id = $db->loadResult();
                		$model->notice($id, 'mentionDi', $db->insertid(),$share->replyID);
                		
                		//other commenters notice
	                    //$notNotice=$db->setQuery("SELECT profileID FROM notsendnotice WHERE diID='".$_POST["sesHakkındaID"]."'");
		                //$notNotice = $dbez->get_col("SELECT profileID FROM notsendnotice WHERE diID='".$_POST["sesHakkındaID"]."'");
						
						$db->setQuery("SELECT profileID FROM notsendnotice WHERE diID='".$share->replyID."'");
						$notNotice=$db->loadResultArray();
	                   	if(count($notNotice))
	                    	$db->setQuery("SELECT profileID FROM di WHERE replyID=".$share->replyID." AND profileID NOT IN (".implode(",", $notNotice).") GROUP BY profileID");
	                    else 
	                    	$db->setQuery("SELECT profileID FROM di WHERE replyID=".$share->replyID." GROUP BY profileID");
	                    
	                    $dicc = $db->loadObjectList();
		             	if(count($dicc)){
		                	foreach($dicc as $dic){
		                    	if($dic->profileID==$share->profileID) continue;
		                       	$model->notice($dic->profileID, 'mentiontoReplied', $share->ID, $share->replyID);
		                  	}
		             	}
                	}
                	else if (@$_POST["linkli"]=="profile")
                	{
                		$model->notice($_POST["profileID"], 'mentionProfile',$db->insertid());
                	}
				
                        
                if($model->profile->facebookPaylasizin==1 && $share->onlyProfile==0  && false) //facebook app düzeltilince false kalkıcak
                {
                	$fb = new facebookClass();
					
                	$fb->send_post(strip_tags($share->di),$share->ID);
                }
					
            	if($model->profile->twitterPaylasizin==1 && $share->onlyProfile==0  && false)  // twitter app düzeltilince false kalkacak 
                {
                	$tw=new twitter();
                	print_r($tw->sendTweet(strip_tags($share->di),$share->ID));
                }
                $response['status'] = 'success';
				 
				$share->sharerimage = $model->profile->image;
				$share->sharername = $model->profile->name;
				$share->sharerDeputy = $model->profile->deputy;
				$share->permalink = $model->profile->permalink;
				$share->count_reply = 0;
				$share->count_like = 0;
				$share->count_dislike = 0;
				$share->count_reShare = 0;
				
                $response['voice'] 	= $c_voice->get_return_object($share);
				
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
				//prosesler classını yaz  bir kişi ses yazınca notification lar  puanlar  counlar  o clas içerisinde  tetiklensin
				$int = new induction;
				//var_dump($int);
				$int->set_voice_intduction("new_share",$share);
				
				
            } else {
                throw new Exception('record error');
            }
            
          
        } catch(Exception $e){
            //share error
            $response['status'] = 'error'; 
            $response['message'] = $e->getMessage();
			//var_dump($e);
        }
        echo json_encode($response);
        die;
	}
	public function upload_image()
	{
		//Example of how to use this uploader class...
		//You can uncomment the following lines (minus the require) to use these as your defaults.
		
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("jpeg","jpg","gif","png");
		// max file size in bytes
		$sizeLimit = 2 * 1024 * 1024; // dosya upload limitini arttır in php.ini
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$upDir="voiceImage";
		if(@$_REQUEST["uploadType"]=="hasTag")
		{
			$upDir="hashtag/".$_REQUEST["hastag"];
		}
		else if(@$_REQUEST["uploadType"]=="cover")
		{
			$uniqueP = date("y_m_d");
			$upDir="coverImage/".$uniqueP;
		}
                
                else if(@$_REQUEST["uploadType"]=="profileImage")
		{
			$uniqueP = date("y_m_d");
			$upDir="p_image/".$uniqueP;
		}
		
		if(!file_exists(UPLOADPATH.$upDir))
		{
			$olustur = mkdir(UPLOADPATH.$upDir, 0777);
		}
		$result = $uploader->handleUpload(UPLOADPATH.$upDir.'/');
		$result["uploadDir"]=$upDir;
	
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}


 	public function login()
 	{// yeniden düzenlenicek 
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
	public function agendavote()
	{
    	global $model, $db, $l;
		//$agendaID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
		$vote = filter_input(INPUT_POST, 'vote', FILTER_SANITIZE_STRING);
		$profileID = intval( $model->profileID );
		$agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
		$response = array();
		try{
        	
      		$response["agendaID"]=$agendaID;
            if( !array_key_exists($vote, config::$votetypes) )
           	{
            	throw new Exception('Hata oluştu');
			}
            	
            $db->setQuery('SELECT a.* FROM agenda AS a WHERE ID='.$db->quote($agendaID).' LIMIT 1');
            $agenda=null;
           	if($db->loadObject($agenda)){
           		$db->setQuery('SELECT av.* FROM agendavote AS av WHERE agendaID='.$db->quote($agendaID).' AND profileID='.$db->quote($profileID).' LIMIT 1');
            	$av=null;
	            if($db->loadObject($av)){
	            	$av->vote        = $vote;
	                $av->datetime    = date('Y-m-d H:i:s');
	                $av->ip          = $_SERVER['REMOTE_ADDR']; 
	                if($db->updateObject('agendavote', $av, 'ID')){
	                	//new vote saved
	                    $response['status']='success';
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
                        $response['status']='success';
                        $response['message']='Oyunuz Kaydedildi';
                  	} else {
                    	throw new Exception('Kayıt Hatası');
                  	}
              	}
            } else {                
            	throw new Exception('Hata !');
         	}
                    
           	//KM::identify($model->user->email);
			//KM::record('agendavote');   
    	} catch (Exception $e){
        	$response['result']='error';
            $response['message']='Hata !';
     	}
  		echo json_encode($response);
	}
	public function get_agenda_statistic()
	{
		$ID	= filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
		$res["status"]="success";
		$res["statistic"]=parliament::get_agendaPercent($ID);
		echo json_encode($res);
	}
	public function get_noticeCount()
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
	public function get_messageCount()
	{
            
		global $model;
		$c_message = new messageClass();
		echo $c_message->getCount($model->profileID);
	}
	public function redi(){
    	global $model, $db;
    	$ID  = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
     	$response = array();
        try{
        	$db->setQuery('SELECT * FROM di WHERE rediID  = ' . $db->quote($ID) . ' AND profileID= '. $db->quote($model->profileID) . ' AND status=1');
			$paylasildi = null;
           	if($db->loadObject($paylasildi))
			{
				$response["status"]="success";
				$response["type"]="removed";
				$paylasildi->status = 0;
				$db->updateObject("di", $paylasildi, "ID");
				echo json_encode($response);
				die;
			}
        	
        	$db->setQuery('SELECT * FROM di WHERE ID = ' . $db->quote($ID) . ' AND status > 0');
         	$voice = null;
           	if(!$db->loadObject($voice)) throw new Exception('voice bulunamadı');
            
            $db->setQuery('SELECT p.*, u.email FROM profile AS p, user AS u WHERE p.ID = ' . $db->quote($voice->profileID) . ' AND u.ID=p.ID');
            $profile = null;
            if(!$db->loadObject($profile)) throw new Exception('profil bulunamadı');
            
			//initialize the share data
            $share = new stdClass;
            
            if($voice->redi>0){
            	if($model->profileID != $voice->redi){
                	$share->redi = $voice->redi; 
              	} else {
                	$share->redi = 0; 
            	}
         	} elseif ($model->profileID != $voice->profileID){//kendi di'n değilse redi gibi algıla
            	$share->redi = $voice->profileID;
                $share->rediID = $ID;
         	} 
            
			$share->di          = $voice->di;
            $share->datetime    = date('Y-m-d H:i:s');
            $share->profileID   = intval( $model->profileID );
            $share->ip          = $_SERVER['REMOTE_ADDR'];
            $share->status      = 1;
                
            if( $db->insertObject('#__di', $share) ){
             	$share->ID=$db->insertid();
            	$response['status'] = 'success';
                
                $model->notice($voice->profileID, 'redi', $voice->ID, $share->ID);
                if($profile->emailperms>0)
                	$model->sendsystemmail( $profile->email, 'Ses\'iniz başkaları tarafından paylaşıldı', 'Merhaba, <br /> <a href="http://democratus.com/'.$model->profile->permalink.'"> '.$model->profile->name.' </a> isimli kullanıcı sizin bir ses’inizi kendi '.profile::getfollowercount($model->profileID).' adet takipçisi ile paylaştı. Şimdi sizi daha fazla insan duyuyor. <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                
				$int = new induction;
				$int->set_voice_intduction("redi_share",$share);
				
          	} else {
            	throw new Exception('record error');
           	}
   		} catch(Exception $e){
        	//share error
            $response['status'] = 'error'; 
            $response['message'] = $e->getMessage();
      	}
        echo json_encode($response);
	}
	function voice_like()
	{
		global $model, $db;
		$profileID	= $model->profileID;
		$voiceID	= filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
		$likeType	= filter_input(INPUT_POST, 'likeType', FILTER_SANITIZE_NUMBER_INT);
		$response	= array();
		
		$SELECT = "SELECT * ";
		$FROM	= "\n FROM  dilike ";
		$WHERE	= "\n WHERE diID='".$voiceID."' AND profileID='".$profileID."' ";
		$db->setQuery($SELECT.$FROM.$WHERE);
		
		$sharelike = new stdClass;
        if($db->loadObject($sharelike)){
        	$reverse = FALSE;
       		if($likeType==1 && $sharelike->dilike2==1)
        	{
        		$reverse = TRUE;
        	}
			elseif($likeType==2 && $sharelike->dilike1==1) {
				$reverse = TRUE;
			}
			else {
				$response["status"]	= 'error';
				$response["errors"] = "Zaten bu işlemi gerçekleştirdiniz";
				echo json_encode($response);
				return 0;
			}
			
        	
        	switch($likeType){
            	case 1: $sharelike->dilike1=1; $sharelike->dilike2=0; break;
              	case 2: $sharelike->dilike1=0; $sharelike->dilike2=1; break;
             	default: $sharelike->dilike1=0; $sharelike->dilike2=0;
         	}
			
			$sharelike->canceldate	= date('Y-m-d H:i:s');
			$sharelike->cancelIP	= $_SERVER['REMOTE_ADDR'];
			if( $db->updateObject('dilike', $sharelike, 'ID') ){
           		$response["status"]	= 'success';
           	} else {
            	$response["status"]	= 'error';
           	}
                    
     	} else {
        	$reverse = FALSE;
           	switch($likeType){
            	case 1: $sharelike->dilike1=1; $sharelike->dilike2=0; break;
              	case 2: $sharelike->dilike1=0; $sharelike->dilike2=1; break;
             	default: $sharelike->dilike1=1; $sharelike->dilike2=0;
         	}
         	
			$sharelike->diID     	= intval($voiceID);
            $sharelike->datetime    = date('Y-m-d H:i:s');
            $sharelike->profileID   = intval( $profileID );
            $sharelike->ip          = $_SERVER['REMOTE_ADDR'];
                
            if( $db->insertObject('dilike', $sharelike) ){
            	$response["status"]	= 'success';
           	} else {
           		$response["status"]	= 'error';
           	}
      	}
		
		$int = new induction;
		$sharelike->ID=$sharelike->diID; // puan class ına  id için element gönderiliyor;
		$sharelike->reverse = $reverse; // like dislike sayarken aynı seste değişme varsa çıkartma yapılacak
		
		$c_voice = new voice($sharelike->diID);
		$sharelike->voice = $c_voice->_voice;
		$int->set_voice_intduction("like_voice",$sharelike);
		
		echo json_encode($response);
	}
	function voice_delete()
	{
		global $model;
		$voiceID = filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
		$c_voice = new voice($voiceID);
		$return = $c_voice->delete();
		if($return)
		{
			$response["status"] = "success";
			$int = new induction;
			$int->set_voice_intduction("delete",$c_voice->_voice);
		}
		else
		{
			$response["status"] = "error";
		}
		echo json_encode($response);
	}
	function get_voiceIconCount()
	{
		global $model, $db;
		$IDs=$_REQUEST["voiceIDs"];
		$IDok=array();
		$returnData=array();
		$profileID	= $model->profileID;
		
		foreach ($IDs as $id) {
			
			if(!in_array($id, $IDok))
			{
				$IDok[]=$id;
				$rt["ID"]=$id;
				$c_voice	= new voice($id);
				
				$rt["count_like"]=$c_voice->_voice->count_like;
				$rt["count_dislike"]=$c_voice->_voice->count_dislike;
				$rt["count_reShare"]=$c_voice->_voice->count_reShare;
				$rt["count_reply"]=$c_voice->_voice->count_reply;
				
				$returnData[]=$rt;
			}
			
		}
		echo json_encode($returnData);
	}
	function get_voiceIconText()
	{
		global $model, $db;
		$IDs=$_REQUEST["voiceIDs"];
		$IDok=array();
		$returnData=array();
		$profileID	= $model->profileID;
		foreach ($IDs as $id) {
			if(!in_array($id, $IDok))
			{
				$IDok[]=$id;
				$rt["ID"]=$id;
				$c_voice	= new voice;
				
				$rt["redi"]=$c_voice->get_ismyRedi($id);
				
				$rt["likeType"]=$c_voice->get_ismyLike($id);
				$returnData[]=$rt;
			}
			
		}
		echo json_encode($returnData);
	}
	function get_agendasObj()
	{
		global $model;
		$c_parliament=new parliament;
		$return["status"]	= "success";
		$agendas	= $c_parliament->get_agenda();
		$return["agendas"]	= $c_parliament->get_agendaReturnObject($agendas);
		
		echo json_encode($return);
	}
	function get_deputyList()
	{
		global $model;
		$c_profile=new profile;
		$return["status"]	= "success";
		$return["deputys"]	= $c_profile->get_deputyList();
		echo json_encode($return);
	}
	function get_oldAgenda()
	{
		global $model;
		$c_parliament=new parliament;
		$return["status"]	= "success";
		$start = 0;
		if(isset($_REQUEST["start"]) && $_REQUEST["start"]>0)
		{
			$start = $_REQUEST["start"];
		}
		$keyword="";
		if( isset($_REQUEST["keyword"])  && $_REQUEST["keyword"]!="")
		{
			$keyword = $_REQUEST["keyword"];
		}
		$agendas	= $c_parliament->get_oldAgenda($start, $keyword);
		$return["olAgendas"]= $c_parliament->get_agendaReturnObject($agendas);
		echo json_encode($return);
	}
	function get_myDeputy()
	{
		global $model;
		$c_profile=new profile;
		$return["status"]	= "success";
		$deputy	= $c_profile->get_myDeputy();
		$return["myDeputy"]= $c_profile->get_myDeputyReturnObj($deputy);
		echo json_encode($return);
	}
	function get_myFollowing()
	{
		global $model;
		if(@$_REQUEST["limit"])
		{
			$limit	= filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);
		}
		else {
			$limit=20;
		}
		if(@$_REQUEST["start"])
		{
			$start	= filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		}
		else {
			$start=0;
		}
		if(@$_REQUEST["keyword"])
		{
			$keyword	= filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
		}
		else {
			$keyword="";
		}
		
		$c_profile=new profile;
		$return["status"]	= "success";
		$following	= $c_profile->get_following($limit,$start,-1, $keyword);
		$return["myFollowing"]= $c_profile->get_profileMultiReturtnObj($following);
		
		echo json_encode($return);
	}
	function set_vekilOyu()
	{
		global $model;
		$deptyID	= filter_input(INPUT_POST, 'deputyID', FILTER_SANITIZE_NUMBER_INT);
		$return		= array();
		$c_profile	= new profile;
		$myDCount	= $c_profile->get_myDuputy_count();
		if($myDCount > 9)
		{
			$return["status"] = "error";
			$return["errorMsg"] = "Vekil Oyu sınırını geçtiniz.";
		}
		else {
			$c_parliament = new parliament;
			$a=$c_parliament->set_vekilOyu($deptyID);
			if($a["status"]=="success")
			{
				$return["status"]="success";
				
			}
			else
			{
				$return["status"]=$a["status"];
				$return["errorMsg"]=$a["errorMsg"];
			}
		}
		echo json_encode($return);
	}
	function get_kalanOyCount(){
		$c_profile	= new profile;
		$myDCount	= $c_profile->get_myDuputy_count();
		echo (10-$myDCount);
	}
	function get_proposal()
	{
		$c_parliament	= new parliament;
		$return		= array();
		$proposal	= $c_parliament->get_proposal();
		$return["status"]	= $proposal['result'];
		$return['message']= $proposal['message'];
                if($return['status'] == 'success')
                    $return["proposals"] = $proposal['proposal'];
		echo json_encode($return);
	}
	function set_proposal(){
		$c_parliament	= new parliament;
		$proposalTxt   	= strip_tags( html_entity_decode( htmlspecialchars_decode(filter_input(INPUT_POST, 'proposalTxt', FILTER_SANITIZE_STRING), ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) );
		$poroposalRt	= $c_parliament->set_proposal($proposalTxt);
		echo json_encode($poroposalRt);
	}
	function get_messageDialog()
	{
		global $model;
		$c_message = new messageClass;
		$return = array("status"=>"success");
		$dialogs = $c_message->getDialogList($model->profileID);
		$return["dialogs"] = $c_message->getDialogListRObj($dialogs);
		echo json_encode($return);
	}
	function get_messageDialogDetail()
	{
		global $model;
		$c_message = new messageClass;
		$return = array("status"=>"success");
		$fID   	= filter_input(INPUT_POST, 'fID', FILTER_SANITIZE_NUMBER_INT );
        $before = filter_input(INPUT_POST, 'before',FILTER_SANITIZE_STRING);
        if(empty($before))
            $before = NULL;
		$dialogs = $c_message->getDialog($model->profileID, $fID,$before);
		$return["dialogs"] = $c_message->getDialogDetailRObj($dialogs);
                if(sizeof($dialogs)<1){
                    $return['last']='x';
                }
                $return['last'] = (string)$dialogs[0]['_id'];
		echo json_encode($return);
	}
	function send_message()
	{
		global $model;
		$c_message = new messageClass;
		$return = array("status"=>"success");
		$fPerma   	= filter_input(INPUT_POST, 'friendPerma', FILTER_SANITIZE_STRING );
		$message   	= filter_input(INPUT_POST, 'msgText', FILTER_SANITIZE_STRING );
		$fID = profile::change_perma2ID($fPerma);
                $profileClass = new profile;
                $test = $profileClass->get_porfileObject($fID);
                if(!isset($test->ID)){
                    echo '{"result": "error"}';
                    return;
                }
			
                if(empty($message)){
                        echo '{"result": "empty"}';
                        return;
                }
                
                if($fID == $model->profileID){
                    echo '{"result": "error"}';
                    return;
                }
		if($c_message->insertMessage($model->profileID, $fID, $message))
		{
			$return = array("status"=>"success");
		}
		else
			{
				$return = array("status"=>"error");
			}
		echo json_encode($return);
	}
	function get_followersAutoComplite(){
		global $model;
		$c_profile = new profile;
		$keyword = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING );
		$following	= $c_profile->get_follower(7,0,-1, $keyword);
		$return = $c_profile->get_profileMultiReturtnObj($following);
		echo json_encode($return);
	}
	function facebook_get_loginUrl()
	{
		global $model;
		$return["status"]="success";
		$c_profile = new profile;
		$perm = $_REQUEST["perm"];
		$c_facebook = new facebookClass();
		$return["url"]=$c_facebook->get_loginUrl($perm);
		
		echo json_encode($return);
	}
	function facebook_get_friendSuggestion()
	{
		global $model, $db;
		$returnA = array("status"=>"success");
		$c_facebook = new facebookClass;
		$friends = $c_facebook->get_friend($model->profile->fbID);
		$dFriend = $c_facebook->get_friendSuggestion($friends);
		
		$c_profile = new profile();
		$returnA["friendList"] = $c_profile->get_profileMultiReturtnObj($dFriend); 
		echo json_encode($returnA);
	}
	function twitter_get_friendSuggestion()
	{
		global $model, $db;
		$returnA = array("status"=>"success");
		$c_twitter = new twitterClass;
		$friends = $c_twitter->get_friends($model->profile->fbID);
		//var_dump($friends);
		$dFriend = $c_twitter->get_friendSuggestion($friends);
		
		$c_profile = new profile();
		$returnA["friendList"] = $c_profile->get_profileMultiReturtnObj($dFriend); 
		echo json_encode($returnA);
	}
	function check_settings(){
		global $model, $db;
		
		$returnA['status'] = 'success';
		$returnA['there'] = 'false';
		$value = trim(filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING));
		
		switch($model->paths[2]){
			case 'username': 
				
				if(!profile::valid_perma($value)){
					$returnA['validate'] = 'false';
					break;
				}
				$SELECT = "SELECT count(permalink) ";
				$FROM 	= "\n FROM profile";
				$WHERE 	= "\n WHERE ID <> " . $db->quote ( intval ( $model->profileID ) );
				$WHERE .= "\n AND permalink = '$value'";
				$LIMIT 	= ' LIMIT 1';
				$db->setQuery ( $SELECT . $FROM  . $WHERE  .	$LIMIT );
				$rows = $db->loadResult ();
			
				if($rows) $returnA['there'] = 'true';
					
			break;
				
			case 'email':
				if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
					$returnA['validate'] = 'false';
				};
				$SELECT = "SELECT count(ID) ";
				$FROM 	= "\n FROM user";
				$WHERE 	= "\n WHERE ID <> " . $db->quote ( intval ( $model->profileID ) );
				$WHERE .= "\n AND status>0 ";
				$WHERE .= "\n AND email = '$value'";
				$LIMIT 	= ' LIMIT 1';
				$db->setQuery ( $SELECT . $FROM  . $WHERE  .	$LIMIT );
				$rows = $db->loadResult ();
			
				if($rows) $returnA['there'] = 'true';
					
			break;
			
			case 'name':
				if(strlen($value)<2){
					$returnA['validate'] = 'false';
				}
			break;
		}
		echo json_encode($returnA);
		
	}
	
	function save_settings(){
		global $model,$db;
		
		$returnA = array();
		
		$returnA['status'] = 'success';
		$obj = new stdClass;
		$objusr = new stdClass;
		
		$obj->ID = $model->profileID; 
		$obj->permalink = trim(filter_input(INPUT_POST, 'kullanici_adi', FILTER_SANITIZE_STRING));
		$obj->name = trim(filter_input(INPUT_POST, 'ad_soyad', FILTER_SANITIZE_STRING));
		
		if(strlen($obj->permalink)<4 or strlen($obj->name)<2){
			$returnA['status'] = 'errorrr';
			echo json_encode($returnA);
			return;
		}
		

		if(model::checkPermalink($obj->permalink)>0){
			$returnA['status'] = 'error';
			echo json_encode($returnA);
			return;
		}
		
		
		$letters = "/^([a-zA-Z0-9._-]+)$/"; 
		if(!preg_match($letters, $obj->permalink)){
			$returnA['status'] = 'error';
			echo json_encode($returnA);
			return;
		}
		
		
		$gun = trim(filter_input(INPUT_POST, 'gun', FILTER_SANITIZE_NUMBER_INT));
		$ay = trim(filter_input(INPUT_POST, 'ay', FILTER_SANITIZE_STRING));
		$yil = trim(filter_input(INPUT_POST, 'yil', FILTER_SANITIZE_NUMBER_INT));
		
		$ay = model::trMonth2int($ay);
		/*
		if(false and !checkdate(intval($ay),intval($gun),intval($yil))){
		
			$returnA['status'] = 'errorr';
			echo json_encode($returnA);
			return;
		}
*/
		

		$sex = trim(filter_input(INPUT_POST, 'cinsiyet', FILTER_SANITIZE_STRING));
		
		$obj->birth = date('Y-m-d',mktime(0,0,0,$ay,$gun,$yil));
		$obj->motto = trim(strip_tags(filter_input(INPUT_POST, 'motto', FILTER_SANITIZE_STRING)));
/*
if(strlen($obj->sex)>1)
		$obj->sex = model::trSex2sex(filter_input(INPUT_POST, 'cinsiyet', FILTER_SANITIZE_STRING));
else
*/
		$obj->sex = 1;
		$objusr->ID = $model->profileID;
		$objusr->email = trim(filter_input(INPUT_POST, 'eposta', FILTER_SANITIZE_STRING));
		
		
		
		if(model::checkEmailThere($objusr->email)>0){
			$returnA['status'] = 'error';
			echo json_encode($returnA);
			return;
		}
		
		if(!filter_var($objusr->email,FILTER_VALIDATE_EMAIL)){
			$returnA['status'] = 'erro';
			echo json_encode($returnA);
			return;	
		}
		
	
		try{
		
		$re1 = $db->updateObject('profile', $obj, 'ID', 0);
		$re2 = $db->updateObject('user', $objusr, 'ID', 0);
}catch(Exception $e){
	if(in_array( $_SERVER['REMOTE_ADDR'], array('193.255.28.251'))){
			error_reporting(E_ALL); ini_set("display_errors", 1);
			//echo '{"status":"success"}';
//print_r($e);
			return ;
			
		}
}
		
	

		if(!($re1 && $re2)){
			$returnA['status'] = 'err';
		}
		
		
		echo json_encode($returnA);
	}

	public function follow(){
            global $model, $db;
            $model->mode = 0;
            $response = array("status" => "success");
	    try{
	        $followingID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
	        if($followingID == $model->profileID) throw new Exception('Kendi kendini takip edemessin');
	        $c_profile = new profile;
			$res = $c_profile->set_follow($followingID);
			
			if($res["status"]!="success")
			{
				throw new Exception ($res["message"]);
			}
	    } catch (Exception $e){
	        $response['status'] = 'error';
	        $response['message'] = $e->getMessage();
	    }
	    
	    echo json_encode($response);
	}
	
	public function get_voiceReply()
	{
		global $model, $db;
    	$model->mode = 0;
   		$response = array("status" => "success");
	    try{
	       	$vID = filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
			$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
			$limit = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);
		   	$c_voice = new voice($vID);
			$voices =  $c_voice->get_reply(null, $start, $limit);
			//$response["voice_totalCount"] = $c_voice->get_replyCount();
			$response["voice_count"] = count($voices);
			foreach($voices as $v)
			{
				$response["voices"][] = $c_voice->get_return_object($v, 32, 32);	
				$response["lastID"] = $v->ID;
			}
	    } catch (Exception $e){
	        $response['status'] = 'error';
	        $response['message'] = $e->getMessage();
	    }
	    
	    echo json_encode($response);
	}
	public function get_countVoiceReply()
	{
		global $model, $db;
    	$model->mode = 0;
   		$response = array("status" => "success");
	    try{
	       	$vID = filter_input(INPUT_POST, 'voiceID', FILTER_SANITIZE_NUMBER_INT);
			$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		   	$c_voice = new voice($vID);
			
			//$response["voice_totalCount"] = $c_voice->get_replyCount();
			$response["count"] = $c_voice->get_replyCount(null, $start);
			
	    } catch (Exception $e){
	        $response['status'] = 'error';
	        $response['message'] = $e->getMessage();
	    }
	    
	    echo json_encode($response);
	}
	public function get_parentVoice()
	{
		global $model, $db;
    	$model->mode = 0;
   		$response = array("status" => "success");
	    try{
	       	$vID = filter_input(INPUT_POST, 'replyID', FILTER_SANITIZE_NUMBER_INT);
		   	$c_voice = new voice($vID);
			$voice =  $c_voice->get_parent();
			$response["voice"] = $c_voice->get_return_object($voice, 32, 32);	
			
	    } catch (Exception $e){
	        $response['status'] = 'error';
	        $response['message'] = $e->getMessage();
	    }
	    
	    echo json_encode($response);
	}

        public function register(){  
            global $model, $db, $l;            
            
            $model->mode = 0;
            $response = array();
            //print_r($_POST);
            try{
                

                $form               = new stdClass;
                $form->name         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->userName    	= strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->email        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->password     = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->password2    = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password2'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->male    = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'male'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $form->captcha      = filter_input(INPUT_POST, 'captcha', FILTER_SANITIZE_STRING);                
                
                
                if( strlen( $form->name ) < 3 ){
                    $response['field'] = 'name';
                    throw new Exception('Adınızı kontrol eder misiniz.', 1);                    
                }
				
				
                $ka = $form->userName;

                if(strlen($ka)<3 || strlen($ka)>25){
                        $response['field'] = 'userName';
                        throw new Exception('Kullanıcı adınız en az 6 en fazla 25 karakter olmalıdır.', 1);       
                } 

                $letters = "/^([a-zA-Z0-9._-]+)$/"; 
                if(!preg_match($letters, $ka)){
                        $response['field'] = 'userName';
                        throw new Exception('Sadece harf, rakam ve (- _ .) karakterlerinden oluşan bir kullanıcı adı belirlemelisiniz. ', 1); 
                }


                $query = "SELECT permalink FROM page WHERE permalink=".$db->Quote($ka);  
                $db->setQuery($query);
                $varmi="";
                $db->loadObject($varmi);
                if(count($varmi)>0){
                        $response["field"]="userName";
                        throw new Exception('Seçmiş olduğunuz kullanıcı adı uygun değildir.', 1); 
                }

                $query = "SELECT permalink FROM profile WHERE permalink=".$db->Quote($ka);  
                $db->setQuery($query);
                $varmi="";
                $db->loadObject($varmi);
                if(count($varmi)>0){
                        $response["field"]="userName";
                        throw new Exception('Seçmiş olduğunuz kullanıcı adı uygun değildir.', 1); 
                }
				               
                if(!isEmail($form->email)){
                    $response['field'] = 'email';
                    throw new Exception('Email adresi geçerli değil.'.$form->email,1);
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
                //bilgiler normal mi?
                
                
                //kaydet
                $profile = new stdClass;    
                $profile->name = $form->name;
				$profile->permalink = $ka;
                $profile->status = 5;
                $profile->class = 1;
                $profile->sex = $form->male;
                
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
                            $request = new stdClass;
                    
                            $request->email     = strtolower( trim( $user->email ) );
                            $request->key       = md5( KEY . time() . uniqid() );
                            $request->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                            $request->datetime  = date('Y-m-d H:i:s');
                            $request->status    = 0;
                            $model->login('ID='.intval($user->ID),'register');
                            if($db->insertObject('userrequest', $request)){
                                $response['status'] = 'success';
								$response["action"] = 'redirect';
                                $response['message'] = 'Üyeliğinizi aktive etmek için lütfen mail kutunuzu kontrol ediniz. Mailler bazen spam kutunuza da düşebilir unutmayınız. Onay maili kısa süre içerisinde ulaşacaktır.';
                                
                                $model->sendsystemmail($request->email, 'democratus hesabınızı onaylayın', 'Merhaba, <br /> democratus hesabınızı aktif hale getirmenize sadece bir adım kaldı. Aşağıdaki linke tıklamanız yahut tarayıcınızın adres çubuğuna yapıştırmanız yeterli:<br /><a href="http://democratus.com/user/activate/'.$request->key.'"> http://democratus.com/user/activate/'.$request->key.'</a> <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                                
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
	 public function myprivacysave(){
    	global $model,$db;
        $profile = $model->profile;
        $response = array();
        $response['status'] = 'success';
        $response['message'] = 'Kaydedildi';
        $profile->showbirth     = filter_input(INPUT_POST, 'showbirth', FILTER_SANITIZE_NUMBER_INT);
        $profile->showmotto     = filter_input(INPUT_POST, 'showmotto', FILTER_SANITIZE_NUMBER_INT);
        $profile->showdies      = filter_input(INPUT_POST, 'showdies', FILTER_SANITIZE_NUMBER_INT);
        $profile->dicomment     = filter_input(INPUT_POST, 'dicomment', FILTER_SANITIZE_NUMBER_INT);
        $profile->showhometown  = filter_input(INPUT_POST, 'showhometown', FILTER_SANITIZE_NUMBER_INT);
        $profile->showcountry   = filter_input(INPUT_POST, 'showcountry', FILTER_SANITIZE_NUMBER_INT);
        $profile->showcity      = filter_input(INPUT_POST, 'showcity', FILTER_SANITIZE_NUMBER_INT);
        $profile->showeducation = filter_input(INPUT_POST, 'showeducation', FILTER_SANITIZE_NUMBER_INT);
        $profile->showhobbies   = filter_input(INPUT_POST, 'showhobbies', FILTER_SANITIZE_NUMBER_INT);
        $profile->showlanguages = filter_input(INPUT_POST, 'showlanguages', FILTER_SANITIZE_NUMBER_INT);
        $profile->showfollowers = filter_input(INPUT_POST, 'showfollowers', FILTER_SANITIZE_NUMBER_INT);
        $profile->showfollowings = filter_input(INPUT_POST, 'showfollowings', FILTER_SANITIZE_NUMBER_INT);
        try{
            if(!$db->updateObject('profile', $profile, 'ID')){
                throw new Exception('Bir sorun oluştu');
            }
        }  catch (Exception $e){
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }
        
        echo json_encode($response);
    }

	public function get_imageGalery()
	{
		global $model, $db;
		$profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
		$c_profile = new profile($profileID);
		$galery = $c_profile->get_imageGalery();
		echo json_encode($galery);
	}
	public function set_coverImage()
	{
		global $model, $db;
		$c_profile = new profile();
		$uProfile = new stdClass;
		$uProfile->ID = $model->profileID;
		$uProfile->coverImage = $_REQUEST["imageData"]["uploadDir"].SLASH.$_REQUEST["imageData"]["fileName"];
		
		if($c_profile->update_profile($uProfile))
		{
			$response["status"] = "success";
			$response["imageUrl"] = $model->getcoverimage($uProfile->coverImage);
		}
		else 
		{
			$response["status"] = "error"	;
		}
		echo json_encode($response);
	}
	public function get_who2follow()
	{
		global $model;
		$c_profile = new profile();
		$response = new stdClass;
		$response->status	= "success";
		$persons	= $c_profile->get_who2follow();
		if($persons==false)
		{
			$response->status = "error";
			$response->error  = "Kimse Takip edilmiyor";
			
		}
		else
		{
			$personsObj = $c_profile->get_porfileObject($persons);
			$response->persons = $c_profile->get_profileMultiReturtnObj($personsObj);
		}
		echo json_encode($response);
	}
    public function set_profileImage()
	{
		global $model, $db;
		$c_profile = new profile();
		$uProfile = new stdClass;
		$uProfile->ID = $model->profileID;
		$uProfile->image = $_REQUEST["imageData"]["uploadDir"].SLASH.$_REQUEST["imageData"]["fileName"];
		if($c_profile->update_profile($uProfile))
		{
			$response["status"] = "success";
			$response["imageUrl"] = $model->getProfileImage($uProfile->image,200,200);
		}
		else 
		{
			$response["status"] = "error"	;
		}
		echo json_encode($response);
	}
        
        public function set_proposal_vote(){
            global $model, $db;
            $rArray = array();
            $profileID = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_NUMBER_INT);
            if(parliament::set_proposal_vote($profileID,$value)){
                $rArray['status']='success';
            }else{
                $rArray['status']='error';
            }
            echo json_encode($rArray);
        }

        
        public function set_popularToProposal()
	{
		
		$popularDiID=intval($_POST["populardiID"]);
		$rJson=array();
		
		$sonuc=  proposal::set_popularDitoProposal($popularDiID);
		if($sonuc=='true')
		{
			$rJson["status"]="success";
		}
		else if($sonuc=='notVekil')
		{
			$rJson["status"]="error";
                        $rJson["message"]='Vekil değil';
		}else if($sonuc=='proposalCount'){
                        $rJson["status"]="error";
                        $rJson['message']='En fazla 3 tasarı göndereblirsiniz';
                }
		echo json_encode($rJson);
	}
        public function proposal_delete(){
            global $model;
                $pID = filter_input(INPUT_POST, 'pID', FILTER_SANITIZE_NUMBER_INT);
		$return = parliament::proposal_delete($pID);
		if($return)
		{
			$response["status"] = "success";
			
		}
		else
		{
			$response["status"] = "error";
		}
		echo json_encode($response);
        }
        public function send_activationMail()	{
		global $model,$db;
                $mail=$model->user->email;
		$db->setQuery("SELECT * FROM userrequest WHERE email='".$mail."' and status=0");
                $db->loadObject($ur);
		if(count($ur)>0 and $ur->email = $mail){
			//print_r($ur);
                        //echo 'democratus hesabınızı onaylayın', 'Merhaba, <br /> democratus hesabınızı aktif hale getirmenize sadece bir adım kaldı. Aşağıdaki linke tıklamanız yahut tarayıcınızın adres çubuğuna yapıştırmanız yeterli:<br /><a href="http://democratus.com/user/activate/'.$ur->key.'"> http://democratus.com/user/activate/'.$ur->key.'</a> <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!';
		
			$model->sendsystemmail($mail, 'democratus hesabınızı onaylayın', 'Merhaba, <br /> democratus hesabınızı aktif hale getirmenize sadece bir adım kaldı. Aşağıdaki linke tıklamanız yahut tarayıcınızın adres çubuğuna yapıştırmanız yeterli:<br /><a href="http://democratus.com/user/activate/'.$ur->key.'"> http://democratus.com/user/activate/'.$ur->key.'</a> <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
		
                        $response['status'] = 'success';
                } else {
                        $response['status'] = 'error';
		}
                echo json_encode($response);
	}
    public function change_password(){
        $inputArray['password']= filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING);
        $inputArray['password_new']=  filter_input(INPUT_POST, 'password_new');
        $inputArray['password_new2']=  filter_input(INPUT_POST, 'password_new2');
        //print_r($inputArray);
        $returnArray=profile::change_password($inputArray);
        echo json_encode($returnArray);
    }
	public function get_popularVoice()
	{
		global $model, $db;
		$type = filter_input(INPUT_POST, 'type',FILTER_SANITIZE_STRING);
		$c_pVoice = new popularvoice;
		switch ($type) {
			case 'popular':
				$start = 0 ;
				$limit = 7 ;
				$sLimit = 0 ;
				$onlyProfile = 0;
				$zeroCond = false;
			break;
			case 'raise':
				$start = 0 ;
				$limit = 7 ;
				$sLimit = 7 ;
				$onlyProfile = 0;
				$zeroCond = false;
			break;
			case 'all':
				$start = 0 ;
				$limit = 7 ;
				$sLimit = 0 ;
				$onlyProfile = 0;
				$zeroCond = TRUE;
			break;
			default:
				
				break;
		}
		$voices=$c_pVoice->get_popularVoice($start , $limit , $sLimit, $onlyProfile, $zeroCond);
		$return = new stdClass;
		$return->status = "success";
		$return->voices = $voices;
		echo json_encode($return);
	}

	public function get_promotedVoice()
	{
		global $model, $db;
		$hashTag	= filter_input(INPUT_POST, 'hashTag',FILTER_SANITIZE_STRING);
		$c_voice = new voice;
		$response = new stdClass;
		
		$voice = $c_voice->get_promotedVoice($hashTag);
		
		$response = new stdClass;
		$response->status = "success";
		$response->voice = $c_voice-> get_return_object($voice);
		echo json_encode($response);
	}
 
    public function delete_dialog(){
        global $model, $db;
        $messageClass = new messageClass();
        $r_array = array();
        $r_array['status'] = 'success';
        $userID = $model->profileID;
        $user2ID = filter_input(INPUT_POST, "perma",FILTER_SANITIZE_STRING);
        $user2ID = profile::change_perma2ID($user2ID);
        if($user2ID<2){
            $r_array['status'] = 'error';
            echo json_encode($r_array);
            return;
        }
        if(!$messageClass->delete($userID, $user2ID, FALSE, TRUE)){
            $r_array['status'] = 'error';
        }
        
        echo json_encode($r_array);
    }
	function get_hashtagAgenda()
	{
		global $model, $db;
		$rt = new stdClass;
		$rt->status = "success";
		$c_parliament = new parliament;
		$active = filter_input(INPUT_POST, 'active', FILTER_SANITIZE_NUMBER_INT);
		$agendas = $c_parliament->get_hastagAgenda($active);
		$rt->agendas = $c_parliament->get_agendaReturnObject($agendas, TRUE);
		
		echo json_encode($rt);
	}
	function toggle_agenda()
	{
		global $model, $db;
		$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
		$c_parliament = new parliament;
		if($c_parliament->toggle_agenda($ID))
		{
			$r->status = "success";
		}
		else
		{
			$r->status = "error";
		}
		echo json_encode($r);
	}
	public function set_agendaHashtag()
	{
		global $model;
		$voice = $_POST["voice"];
		$r = new stdClass;
		$c_parliament = new parliament;
		if($c_parliament->set_agendaHashtag($voice))
		{
			$r->status = "success";
		}
		else
		{
			$r->status = "error";
		}
		echo json_encode($r);
	}
    public function facebookta_paylas(){
        
        $fb = new facebookClass();
        $izin =  $fb->yazmaizniVarmi();
        $postIcerik = filter_input(INPUT_GET, 'text',FILTER_SANITIZE_STRING);
        $f = filter_input(INPUT_GET, 'f',FILTER_SANITIZE_STRING);
        //$izin['durum']='login';
                    

        if($izin['durum']=='login'){
            echo json_encode($izin);
            
       }  elseif($izin['durum']=='izinVar' and $f == 'false') {
           $fb->share_democratus_with_friends($postIcerik);
           global  $model;
           $model->mode = 1;
           echo '<script>window.close();</script>';
          echo json_encode($izin);
           //die;
       } elseif($f == 'false') {
           
           global  $model;
           $model->mode = 1;
           echo '<script>window.close();</script>';
           echo json_encode($izin);
       }  else {
           global  $model;
           $model->mode = 1;
           echo '<script>window.close();</script>';
           echo json_encode($izin);
       }
       
        
       
    }
   public function twitterda_paylas1(){
       global $model,$db;
        $tw = new twitterClass();
        require_once CLASSPATH.'/smclass/twitter/twitteroauth.php';
        $tk = array();
        if($tw->user_tokens_check()){
            $access_token = $tw->get_user_tokens();
            $tk['oauth_token'] = $access_token->user_oauth_token;
            $tk['oauth_token_secret'] = $access_token->user_oauth_token_secret; 
            $_SESSION['access_token'] = $access_token;
        }  else {
            $twitteroauth = new TwitterOAuth($tw->twitter_key, $tw->twitter_secret,$_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
            $tk = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
            $_SESSION['access_token'] = $access_token;
        }
        
        //print_r($tk);
        $twitteroauth = new TwitterOAuth($tw->twitter_key, $tw->twitter_secret, $tk['oauth_token'], $tk['oauth_token_secret']);
       
                // Save it in a session var
       
        if(!$tw->user_tokens_check()){
            $oauth = new stdClass;
            $oauth->userID  = $model->profileID;
            $oauth->oauth_provider  = 'twitter';
            $oauth->oauth_uid       = $model->profileID;
            $oauth->username       = $model->profile->permalink;
            $oauth->oauth_token       = $_SESSION['oauth_token'];
            $oauth->oauth_token_secret       = $_SESSION['oauth_token_secret'];
            $oauth->user_oauth_token = $tk['oauth_token'];
            $oauth->user_oauth_token_secret = $tk['oauth_token_secret'];
            $oauth->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
            $oauth->datetime  = date('Y-m-d H:i:s');
            $oauth->status    = 1;

            //print_r($);

            $db->insertObject('oauth', $oauth );
        }
               
        $text = filter_input(INPUT_GET, 'text',FILTER_SANITIZE_STRING);
        $sonuc=$twitteroauth->post('statuses/update', array("status" => substr($text,0,115).'... http://democratus.com'));
        print_r($sonuc);
        echo '<script>window.close();</script>';
        //print_r($tw->sendTweet('test lfsdlşkf',40000));
        
    }
    public function twitterda_paylas(){
        global $model,$db;
        $tw = new twitterClass();
        $text = filter_input(INPUT_GET, 'text',FILTER_SANITIZE_STRING);
        $token = $tw->twO->getRequestToken('http://democ.com/ajax/twitterda_paylas1?text='.urlencode($text));
        $_SESSION['oauth_token'] = $token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
         if($tw->user_tokens_check()){
        
            $this->twitterda_paylas1(0);
            
        }else{
            
            $url = $tw->twO->getAuthorizeURL($token['oauth_token'],FALSE);
            header('Location: '.$url);
        }
        
    }
    
    public function get_follows(){
        $profileID = intval(filter_input(INPUT_POST, 'profileID',FILTER_SANITIZE_STRING));
        $start = intval(filter_input(INPUT_POST, 'start',FILTER_SANITIZE_STRING));
        $c_profile = new profile();
        $profiles = $c_profile->get_following(20, $start, $c_profile->get_porfileObject($profileID));
        $profiles = $c_profile->get_profileMultiReturtnObj($profiles);
        $return['profiles'] = $profiles;
        //$return['profiles'] = $c_profile->get_following(20, $start, $c_profile->get_porfileObject($profileID));
        $return['status'] = 'success';
        echo json_encode($return);
    }
    
    public function get_followers(){
        $profileID = intval(filter_input(INPUT_POST, 'profileID',FILTER_SANITIZE_STRING));
        $start = intval(filter_input(INPUT_POST, 'start',FILTER_SANITIZE_STRING));
        $c_profile = new profile();
        $profiles = $c_profile->get_follower(20, $start, $c_profile->get_porfileObject($profileID));
       
        $profiles = $c_profile->get_profileMultiReturtnObj($profiles);
        
        $return['profiles'] = $profiles;//$c_profile->get_follower(20, $start, $c_profile->get_porfileObject($profileID));
        $return['status'] = 'success';
        echo json_encode($return);
    }
	
	public function profilecomplaintmenu()
	{
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
                            <textarea maxlength="200" name="message"></textarea>
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
                $response['status'] = 'success';
            } else 
                throw new Exception('kayıt hatası oluştu');

        } catch (Exception $e){
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }
        
        echo json_encode($response);
    }
    
    function from_sm(){
        global $model, $db;
        $returnA = array("status"=>"success");
        if($_SESSION['from_sm'] == 'facebook' or TRUE ){
            $c_facebook = new facebookClass;
            $friends = $c_facebook->get_friend($model->profile->fbID);
            $dFriend = $c_facebook->get_friendSuggestion($friends);
            $type = 'facebook';
            
        }elseif($_SESSION['from_sm'] == 'twitter'){
            $c_twitter = new twitterClass;
            $friends = $c_twitter->get_friends($model->profile->fbID);            
            $dFriend = $c_twitter->get_friendSuggestion($friends);
            $type = 'twitter';
        }else{
            $_SESSION['from_sm'] = '';
            unset($_SESSION['from_sm']);
            return;
        }
        $_SESSION['from_sm'] = '';
        unset($_SESSION['from_sm']);
       // e($profileID, $type, $ID2, $ID3 = null, $subtype = null)
       $result = array();
       foreach ($dFriend as $d){
           $model->notice($d->ID,'friendFromSm',NULL,NULL,$type);
          
       }
         
    }

	function staticContent()
	{
		global $model, $db;
		$cID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT );
		$db->setQuery("SELECT * FROM pagecontent WHERE pageID=".$db->quote($cID)."");
		$page = $return = new stdClass;
		if($db->loadObject($page))
		{
			$return->status = "success";
			$return->title	= $page->title;
			$return->content= $page->content;
		}
		echo json_encode($return);
	}

}
?>
