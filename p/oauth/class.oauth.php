<?php
	class oauth_plugin extends control{
		private $facebook_app_id        = '272887782761960';
		private $facebook_app_secret    = 'dd0f4c22abd2ff4d7f4dd7bd3518f86c'; 
		
		private $twitter_key            = 'i0n7BGuUZ6xLXzs1ixg';
		private $twitter_secret         = 'wMv4BGlmUyWc3pz4v8rwDZFMuOxWUtRFYtPSbYP03Y';
        
		private $facebook;
		private $twitter;
		
		public function main(){
			global $model;
			$model->initTemplate('simple'); 
			switch($model->paths[1]){
				case 'facebook' : return $this->facebook() ; break;	
				case 'twitter'	: return $this->twitter(); break;
				case 'twitter2'	: return $this->twitter2(); break;
				case 'twitter_sug'	: return $this->twitterSuggestion(); break;		
			}
			
						/*
			$db->setQuery("select oauth_uid ,userID  from  oauth where oauth_provider = 'twitter'");
			$rows = $db->loadObjectList();
			
			
			foreach ($rows as $r) {
				
				
				
				//echo $obj->fbID."<BR/>";
				$obj->twID = $r->oauth_uid;
				$obj->ID = $r->userID;
				//
				if($obj->twID != NULL)
					$db->updateObject('profile',$obj,'ID');
				
			}
			

			die;
			
			*/
		}

		function twitterSuggestion(){
				global $model,$db;
        		require_once( $model->pluginpath.'twitter/twitteroauth.php' );
				$twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret,$_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
				$access_token = $twitteroauth->getAccessToken();
               
                                
                $user_profile = $twitteroauth->get('account/verify_credentials');
                $profile = NULL;
				$profile->twID = $user_profile->id;
				$profile->ID = $model->profileID;				
				$db->updateObject('profile',$profile,'ID');
			
                
                
                $users = array();
                $user_info = $twitteroauth->get('friends', array('cursor' => -1));
                $users = array_merge($users,$user_info->users);
                
                while($user_info->next_cursor != 0){                	
                	$user_info = $twitteroauth->get('friends', array('cursor' => $user_info->next_cursor));
					$users = array_merge($users,$user_info->users);
                }
				
				echo '<a href="http://cafeincampus.org/oauth/twitter"><h1>bul</h1></a>';
				echo "<pre>";
				
				//print_r($users);
				
				
				$query = "SELECT ID, name , permalink FROM profile WHERE twID IN ( ";
				foreach($users as $key=>$friend){
					$query .= $friend->id;
					
					if($key!=sizeof($users)-1)
						$query .=',';
					else {
						$query .= ')';
					}
					
				}
				
				//print_r($query);
				//die;
				$db->setQuery($query);
				$p1 = NULL;
				$p1 = $db->loadObjectList();
				
				
				$query = "SELECT ID, name , permalink FROM profile WHERE name IN ( ";
				foreach($users as $key=>$friend){
					$query .= "'".addslashes($friend->name)."'" ;
					
					if($key!=sizeof($users)-1)
						$query .=',';
					else {
						$query .= ')';
					}
					
				}
				
				
				
			//	print_r($query);
				//die;
				$db->setQuery($query);
				$p2 = NULL;
				$p2 = $db->loadObjectList();
				
				//$p1 = array();
				//$p2 =  array();
				$profiles = array_merge($p1,$p2);
				
				$profilesSuggest_ = array();
				$profilesSuggest = array();	
				foreach ($profiles as $profile) {
					$profilesSuggest_[$profile->ID] = $profile;
				}
				
				$key = 0;
				foreach ($profilesSuggest_ as  $value) {
					$profilesSuggest[$key] = $value;
					$key++;
				}
			
				//echo sizeof($p);
				
				
				print_r($p1);
				print_r($p2);
				print_r($profilesSuggest);
		}

		function twitter_new(){
			global $model, $db;
            require_once( $model->pluginpath.'twitter/twitteroauth.php' );            
            $twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret);
            // Requesting authentication tokens, the parameter is the URL we will be redirected to
            $request_token = $twitteroauth->getRequestToken('http://democratusala.com/sallamasyon');
            // Saving them into the session
            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
            // If everything goes well..
            if ($twitteroauth->http_code == 200) {
                // Let's generate the URL and redirect
                $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
               // die($url);
                //header('Location: ' . $url);
                echo $url;
          	 } else {
                // It's a bad idea to kill the script, but we've got to know when there's an error.
                die('Twitter cevap vermiyor!');
            } 
			
		}



	public function facebook(){
            global $model, $db;

            
            require_once( $model->pluginpath.'facebook/facebook.php' );
            
            $facebook = new Facebook(array('appId' => $this->facebook_app_id, 'secret' => $this->facebook_app_secret));
            
            $user = $facebook->getUser(); 
            
            
            if(!$user){
                $login_url = $facebook->getLoginUrl(array( 'scope' => 'email'));
                $model->redirect($login_url);
            }
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
                die($e->getMessage());
            }

            try{
                if (empty($user_profile )) throw new Exception('profile is empty');
                    
                    
                    $name         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['name'], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $uid         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['id'], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $email        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['email'], FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $username        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['username'], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                     if($_SERVER['REMOTE_ADDR']=='88.255.245.2522')
						{
							echo "<pre>";
							var_dump($user_profile);
							echo "</pre>";
							die;
						}
                    
                    
                    //oauth kaydı var mı?
                    
                    $db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'facebook' AND oauth_uid = " . $db->quote($user_profile['id']) . "" );
                    $oauth = null; // bu alanı oauth kaydından değil profildeki userID den kontrol edeceğiz 
                    if($db->loadObject($oauth)){
                        //login ol ve çık
                        //die('oauth var');
                        if($oauth->status>0)
                            $model->login('ID='.intval($oauth->userID),'facebook');
                        if($model->profile->fbID=="")
                        {
                        	$pro=new stdClass();
                        	$pro->ID=intval($oauth->userID);
                        	$pro->fbID=$db->quote($user_profile['id']);
                        	$db->updateObject("profile", $pro, "ID");
                        }
                        return $model->redirect('/');    
                    }
                    
                    $email = strtolower( trim( $user_profile['email'] ) );
                    
                    $db->setQuery("SELECT * FROM user WHERE email = " . $db->quote($email));
                    $user = null;
                    if($db->loadObject($user)){
                        echo '<h3>Bu email adresi ile zaten bir üyelik var.</h3>';
                        //buraya oauthrequest yazalım.
                        
                        $oauth = new stdClass;
                        $oauth->userID  = $user->ID;
                        $oauth->oauth_provider  = 'facebook';
                        $oauth->oauth_uid       = $uid;
                        $oauth->username       = $username;
                        $oauth->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                        $oauth->datetime  = date('Y-m-d H:i:s');
                        $oauth->status    = 0;
                        if( $db->insertObject('oauth', $oauth ) ){
                            
                            $request = new stdClass;
                    
                            $request->oauthID   = $db->insertid();
                            $request->email     = strtolower( trim( $email ) );
                            $request->key       = md5( KEY . time() . uniqid() );
                            $request->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                            $request->datetime  = date('Y-m-d H:i:s');
                            $request->status    = 0;
                            
                            if($db->insertObject('oauthrequest', $request)){
                                $response['status'] = 'success';
                                $model->sendsystemmail($request->email, 'Democratus hesabına facebook ile bağlanma izni', 'Democratus.com\'da var olan hesabına facebook ile bağlanma talebinizi aldık. Eğer facebook hesabınızı kullanmak istiyorsanız şu onay linkine tıklamalı ya da tarayıcınızın adres çubuğuna yapıştırmalısınız: http://democratus.com/oauth/activate/'.$request->key);
                                echo '<p>Size onaylamanız için bir e-posta gönderdik. Lütfen oradaki yönergeleri takip edin</p>';
                                
                            } else {
                                throw new Exception('kayıt hatası');
                            }
                            
                        }
                        
                        
                        
                        
                        
                        
                        
                        
                        return;    
                    }
                    
                    
                    //hayır ise profil, user oluştur ve oauth kaydı yap
                    
                   
                    
                    
                    
                    $profile = new stdClass;
                    $profile->name = $name;
					$oauth->permalink  = $c_porfile->normalize_permalink($username);
                    $profile->status = 1;
                    $profile->fbID = $uid;
                    
                    
                    if( $db->insertObject('profile', $profile ) ){
                        //echo 'profile ok';
                        
                        $user = new stdClass;
                        $user->email        = $email;
                        $user->status = 1;
                        $user->ID = $db->insertid();
                        $user->registertime = date('Y-m-d H:i:s');
                        
                        if( $db->insertObject('user', $user ) ){
                            
                            $oauth = new stdClass;
                            $oauth->userID  = $db->insertid();
                            $oauth->oauth_provider  = 'facebook';
                            $oauth->oauth_uid       = $uid;
                            $oauth->username       = $username;
                            $oauth->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                            $oauth->datetime  = date('Y-m-d H:i:s');
                            $oauth->status    = 1;
                            if( $db->insertObject('oauth', $oauth ) ){
                                echo '<h2>Yaşasın başardık</h2>';
                                $model->login('ID='.intval($oauth->userID), 'facebook');
                                return $model->redirect('/');
                                
                            } else throw new Exception('oauth insert');
                    } else throw new Exception('user insert');
                } else throw new Exception('oauth insert');
                
            } catch (Exception $e){
                
            }
            
            
            
            
            /*
            if(0 && $user){
                

                if (!empty($user_profile )) {
                    # User info ok? Let's print it (Here we will be adding the login and registering routines)
                    echo $user_profile['name'];
                    die;
                    
                    $username = $user_profile['name'];
                         $uid = $user_profile['id'];
                     $email = $user_profile['email'];
                    
                    
                    
                    $user = new User();
                    $userdata = $user->checkUser($uid, 'facebook', $username,$email,$twitter_otoken,$twitter_otoken_secret);
                    if(!empty($userdata)){
                        session_start();
                        $_SESSION['id'] = $userdata['id'];
             $_SESSION['oauth_id'] = $uid;

                        $_SESSION['username'] = $userdata['username'];
                        $_SESSION['email'] = $email;
                        $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
                        header("Location: home.php");
                    }
                } else {
                    # For testing purposes, if there was an error, let's kill the script
                    die("There was an error.");
                }
            } 
            */
                        
            

        }
	public function twitter2(){
            global $model, $db;
            require_once( $model->pluginpath.'twitter/twitteroauth.php' );
            
            if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
                
                // We've got everything we need
                $twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
                // Let's request the access token
                $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
                // Save it in a session var
                $_SESSION['access_token'] = $access_token;
                // Let's get the user's info
                $user_info = $twitteroauth->get('account/verify_credentials');
                
                
                //print_r($user_info);
                //die;
                
                try{
                    if (empty($user_info )) throw new Exception('profile is empty');
                    
                        
                        
                        $name         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->name, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $uid         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->id, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
						$userName         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->screen_name, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        
                        //$email        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info['email'], FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $username        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->screen_name, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $motto        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->description, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $location        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->location, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        //$birth        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->location, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        if($_SERVER['REMOTE_ADDR']=='127.0.0.11')
						{
							echo "<pre>";
							var_dump($user_info);
							echo "</pre>";
                                                        $db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'twitter' AND oauth_uid = " . $db->quote($uid) . "" );
                                                        $oauth = null;
                                                        if($db->loadObject($oauth)){
                                                            print_r($oauth);
                                                        }
							die;
						}
                        if (strlen($uid)<3) throw new Exception('id not valid');
                        
                        //oauth kaydı var mı?
                        
                        $db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'twitter' AND oauth_uid = " . $db->quote($uid) . "" );
                        $oauth = null;
                        if($db->loadObject($oauth)){
                            //login ol ve çık
                            //die('oauth var');
                            
                            if(strlen($oauth->oauth_token)==0){
                                $oauth->oauth_token         = $_SESSION['oauth_token'];
                                $oauth->oauth_token_secret  = $_SESSION['oauth_token_secret'];
                                $db->updateObject('oauth', $oauth, 'ID', 0);
                            }
                            
                            if($oauth->status>0)
                                $model->login('ID='.intval($oauth->userID), 'twitter');
                            
                            return $model->redirect('/');    
                        }
                        
                        
                        //hayır ise profil, user oluştur ve oauth kaydı yap
                        
                        
                        
                        $c_porfile = new profile;
                        
                        $profile = new stdClass;
                        $profile->name = $name;
						$profile->permalink = $c_porfile->normalize_permalink($userName);
                  		$profile->motto = $motto;
                        $profile->hometown = $location;
						$profile->twID 	= $uid;
                        $profile->status = 1;
                        
                        
                        if( $db->insertObject('profile', $profile ) ){
                            //echo 'profile ok';
                            
                            $user = new stdClass;
                            //$user->email        = $email;
                            $user->status = 1;
                            $user->ID = $db->insertid();
                            $user->registertime = date('Y-m-d H:i:s');
                            
                            if( $db->insertObject('user', $user ) ){
                                
                                $oauth = new stdClass;
                                $oauth->userID  = $db->insertid();
                                $oauth->oauth_provider  = 'twitter';
                                $oauth->oauth_uid       = $uid;
                                $oauth->username       = $username;
                                $oauth->oauth_token       = $_SESSION['oauth_token'];
                                $oauth->oauth_token_secret       = $_SESSION['oauth_token_secret'];
                                $oauth->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                                $oauth->datetime  = date('Y-m-d H:i:s');
                                $oauth->status    = 1;
                                if( $db->insertObject('oauth', $oauth ) ){
                                    //echo '<h2>Yaşasın başardık</h2>';
                                    $model->login('ID='.intval($oauth->userID), 'twitter');
                                    return $model->redirect('/');
                                    
                                } else throw new Exception('oauth insert');
                        } else throw new Exception('user insert');
                    } else throw new Exception('oauth insert');
                    
                } catch (Exception $e){
                    //return $model->redirect('/welcome');
                    die('beklenmedik bi hata oluştu');
                }
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                // Print user's info
                /*
                echo '<pre>';
                print_r($user_info);
                echo '</pre><br/>';
    
    /*
    if (isset($user_info->error)) {
        // Something's wrong, go back to square 1  
        header('Location: login-twitter.php');
    } else {
       $twitter_otoken=$_SESSION['oauth_token'];
       $twitter_otoken_secret=$_SESSION['oauth_token_secret'];
       $email='';
        $uid = $user_info->id;
        $username = $user_info->name;
        $user = new User();
        $userdata = $user->checkUser($uid, 'twitter', $username,$email,$twitter_otoken,$twitter_otoken_secret);
        if(!empty($userdata)){
            session_start();
            $_SESSION['id'] = $userdata['id'];
 $_SESSION['oauth_id'] = $uid;
            $_SESSION['username'] = $userdata['username'];
            $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
            header("Location: home.php");
        }
    }
    
    
    */
} else {
    // Something's missing, go back to square 1
    //header('Location: login-twitter.php');
    
    $model->redirect('/oauth/twitter/');
}
            
            
            
        }
        
        
        public function twitter(){
            global $model, $db;

            
            require_once( $model->pluginpath.'twitter/twitteroauth.php' );
            
            //$facebook = new Facebook(array('appId' => $this->facebook_app_id, 'secret' => $this->facebook_app_secret));
            
            $twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret);
            // Requesting authentication tokens, the parameter is the URL we will be redirected to
            $request_token = $twitteroauth->getRequestToken('http://democratus.com/oauth/twitter2/');
            
            // Saving them into the session

            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

            // If everything goes well..
            if ($twitteroauth->http_code == 200) {
                // Let's generate the URL and redirect
                $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
                //die($url);
                header('Location: ' . $url);
            } else {
                // It's a bad idea to kill the script, but we've got to know when there's an error.
                die('Twitter cevap vermiyor!');
            } 
        }
		
	}
?>