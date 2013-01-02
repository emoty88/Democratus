<?php
	class oauth_plugin extends control{
		private $facebook_app_id        = '142184682596814';
		private $facebook_app_secret    = '44c5a4a0d75c75f426c0a4560c66154b'; 
		private $twitter_key            = 'm6Wxc0XrYoIpSI9Nfuhu6A';
		private $twitter_secret         = 'NU2wI0SaFeJbEunfQhwxvB3q69XDtGur2cOcMpEI';
        
		private $facebook;
		private $twitter;
		
		public function main(){
			global $model;
			$model->initTemplate('simple'); 
			switch($model->paths[1]){
				case 'facebook' : return $this->facebook() ; break;	
				case 'twitter'	: return $this->twitter(); break;
				case 'twitter2'	: return $this->twitterSuggestion(); break;		
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

		function twitter(){
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
	
		
	}
?>