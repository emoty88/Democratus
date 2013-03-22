<?php
class facebookClass{
		private $facebook_app_id        = '272887782761960';
		private $facebook_app_secret    = 'dd0f4c22abd2ff4d7f4dd7bd3518f86c';  
		private $fbID = 0;
		private $facebook;
	
	public function __construct ($fbID=0)
	{
		global $model,$db;
              
		require_once( 'c/smclass/facebook/facebook.php' );
		
        if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
            $this->facebook_app_id = '142184682596814';
            $this->facebook_app_secret = '44c5a4a0d75c75f426c0a4560c66154b';
        } 
		$this->facebook = new Facebook(
			array(
				'appId' => $this->facebook_app_id,
			 	'secret' => $this->facebook_app_secret
			 )
		);
		
		if($fbID!=0)
		{
			$this->fbID = $fbID;
		}
	}	
	public function main($fbID=0){
		global $model,$db;
			
		require_once( 'c/smclass/facebook/facebook.php' );
		
		$this->facebook = new Facebook(
			array(
				'appId' => $this->facebook_app_id,
			 	'secret' => $this->facebook_app_secret
			 )
		);
		if($fbID>0)
		{
			$this->fbID = $fbID;
		}
		die;
	    $user = $this->facebook->getUser(); 
			if(!$user){
            	$login_url = $this->facebook->getLoginUrl(array( 'scope' => 'email'));
				echo $login_url;
				//$model->redirect($login_url);
            }
			
			try{
			   $user_profile = $this->facebook->api('/me');
			   
			}catch(exception $e){
				die($e->getMessage());
			}
			
			print_r($user_profile);
			$profile = NULL;
			$profile->fbID = $user_profile['id'];
			$profile->ID = $model->profileID;				
			$db->updateObject('profile',$profile,'ID');
			
			switch ($model->paths[2]){
				case  'getFallowSuggestion': return $this->friendSuggestions(); return; break;
			}
	}
	public function get_userInfo()
	{
		 return  $this->facebook->api('/me');
	}
	public function get_facebookID()
	{
		 return  $this->facebook->getUser(); 
	}
	public function get_loginUrl($perm='email', $returnUrl='http://democratus.com/my/faceReturn', $display="popup")
	{
		$permD = array('scope'=>$perm ,'redirect_uri' => $returnUrl, 'display' => $display);
		return $this->facebook->getLoginUrl($permD);
	}
	/*
	function get_loginUrl($scope, $redirectUrl="")
	{
		echo "dasd";
		die;
		$param = array( 'scope' => implode(",", $scope));
		if($redirectUrl!="")
		{
			$param["redirect_uri"]=$redirectUrl;
		}
		var_dump($param);
		//$login_url = $this->facebook->getLoginUrl($param);
		
		return $login_url;
	}
	 * */
	public function get_friend($fbID=0)
	{
		if($fbID==0)
		{
			$fbID=$this->fbID;
		}	
		$friends = $this->facebook->api('/'.$fbID.'/friends');
		return $friends["data"];
	}
	public function get_friendSuggestion($friends)
	{
		global $model, $db;
		
		$profiles = array();
		$i=1;
		$fSlice = array_chunk($friends, 100);

		foreach($fSlice as $f)
		{
			$query = "SELECT ID, name ,permalink, motto, image FROM profile WHERE fbID IN (".implode(",", $this->get_multiFriendArray($f,"id")).") ";	// takip ettiklerini getirme 
			$db->setQuery($query);
			$p = NULL;
			$p = $db->loadObjectList();
			$profiles = array_merge($profiles,$p);	
							
			$i++;
		}
		return $profiles;
	}
	function get_multiFriendArray($friends,$KEY)
	{
		$returnA = array();
		foreach($friends as $f)
		{
			$returnA[]= $f[$KEY];
		}
		return $returnA;
	}
	public function send_post($postIcerik,$ID)
	{
		global $model, $db;
	    
		$fbID = $model->profile->fbID;
		$urlS=new urlshorter();
		$response=$urlS->useBitly("http://democratus.com/voice/".$ID);
		if($response["url"]!="")
		{
			$link=$response["url"];
		}
		else 
		{
			$link="http://democratus.com/voice/".$ID;
		}
	
		$ret_obj = $this->facebook->api('/'.$fbID.'/feed', 'POST',
			array(
				'message' => $postIcerik
			));
	}
        
    public function share_democratus_with_friends($postIcerik){
        $ret_obj = $this->facebook->api('/feed', 'POST',
		array(
			'message' => $postIcerik,
                            'link' => 'http://democratus.com',
                            'name' => 'Democratus',
                            'picture' => 'http://democratus.com/t/ala/img/login-logo2.png'
		));
        
    }

    public function yazmaizniVarmi()
    {
    	global $model, $db;
	  	if($model->profile->fbID=="0")
		{
			 $return = false;
		} 
		else
		{
			$permissions = $this->facebook->api("/".$model->profile->fbID."/permissions");
			if( array_key_exists('publish_stream', $permissions['data'][0]) ) {
		    	$return = true;
			}
			else
			{
				$return = false;
			} 	
		}
		return $return;
    } 
	
}
?>