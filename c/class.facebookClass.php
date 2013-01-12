<?php
class facebookClass{
		private $facebook_app_id        = '142184682596814';
		private $facebook_app_secret    = '44c5a4a0d75c75f426c0a4560c66154b';  
		private $fbID = 0;
		private $facebook;
	
	public function __construct ($fbID=0)
	{
		global $model,$db;
               
		require_once( 'c/smclass/facebook/facebook.php' );
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
	public function get_loginUrl($perm=0)
	{
		if(count($perm)>0)
		{
			$permD = array("scope"=>$perm,'redirect_uri' => SITEURL.'my/faceReturn', 'display' => 'popup');
		}
		return $this->facebook->getLoginUrl($permD);
	}
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
}
?>