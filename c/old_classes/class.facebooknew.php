<?php 
class facebooknew{
        public $facebook_app_id         = '272887782761960';
        public $facebook_app_secret     = 'dd0f4c22abd2ff4d7f4dd7bd3518f86c';        
	 
	 static public function main(){
        global $model, $db;
        
    }
    public function yazmaizniVarmi()
    {
    	global $model, $db;
	    require_once( COREPATH.'smclass/facebook/facebook.php' );
		$facebook = new Facebook(array('appId' => $this->facebook_app_id, 'secret' => $this->facebook_app_secret));
		$user = $facebook->getUser(); 
		if(!$user){
			$login_url = $facebook->getLoginUrl(array( 'scope' => 'publish_stream'));
			//$model->redirect($login_url);
			$return["durum"]="login";
			$return["loginUrl"]=$login_url;
			
		}
		else 
		{	
			$permissions = $facebook->api("/me/permissions");
	    	if( array_key_exists('publish_stream', $permissions['data'][0]) ) {
	    		$return["durum"]="izinVar";
			} else {
				$return["durum"]="izinal";
				$return["izinUrl"]=$facebook->getLoginUrl(array("scope" => "publish_stream"));
				//header( "Location: " . $facebook->getLoginUrl(array("scope" => "publish_stream")) );
			}
		}
		return $return;
    } 
	public function facebookPost($postIcerik,$ID)
	{
		global $model, $db;
	    require_once( COREPATH.'smclass/facebook/facebook.php' );
		$facebook = new Facebook(array('appId' => $this->facebook_app_id, 'secret' => $this->facebook_app_secret));
		//$user = $facebook->getUser();
		
		$urlS=new urlshorter();
		$response=$urlS->useBitly("http://democratus.com/di/".$ID);
		if($response["url"]!="")
		{
			$link=$response["url"];
		}
		else 
		{
			$link="http://democratus.com/di/".$ID;
		}
		
		$ret_obj = $facebook->api('/'.$fbID.'/feed', 'POST',
			array(
				'message' =>$postIcerik
				));
	}
	public function get_fbID()
	{
		global $model, $db;
	    require_once( COREPATH.'smclass/facebook/facebook.php' );
		$facebook = new Facebook(array('appId' => $this->facebook_app_id, 'secret' => $this->facebook_app_secret));
		$user = $facebook->getUser(); 
		return $user;
	}
}
?>
