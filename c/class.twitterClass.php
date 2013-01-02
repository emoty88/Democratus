<?php 
class twitterClass{      
        public $twitter_key             = 'i0n7BGuUZ6xLXzs1ixg';
        public $twitter_secret          = 'wMv4BGlmUyWc3pz4v8rwDZFMuOxWUtRFYtPSbYP03Y'; 
        public $twO						= "";
		public $afterOath				= false;
		
	public function __construct()
	{
		require_once( COREPATH.'smclass/twitter/twitteroauth.php' );
		$this->twO = new TwitterOAuth($this->twitter_key, $this->twitter_secret);
	
	}
	
	static public function main(){
        global $model, $db;
        
    }
	public function get_loginUrl()
	{
		$request_token = $this->twO->getRequestToken("http://democratusala.com/my/twitterReturn");
		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		$this->twO = new TwitterOAuth($this->twitter_key, $this->twitter_secret,$_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
		$url = $this->twO->getAuthorizeURL($request_token['oauth_token']);	
		return $url;
	}
	public function sendTweet($tweetText,$ID)
	{
		global $model,$db;
		$tokens=$this->get_user_tokens();
		$twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret,$tokens->user_oauth_token,$tokens->user_oauth_token_secret);
		$tweetTextFinal=substr ($tweetText, 0 , 110);
		
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
		$tweetTextFinal.="... - ".$link;
		$sonuc=$this->twO->post('statuses/update', array("status" => $tweetTextFinal));
		return $sonuc;
	}
	public function get_user_tokens()
	{
		global $model, $db;
		$retun=new stdClass();
		
		$db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'twitter' AND userID = " . $db->quote($model->profileID) . "" );
		$oauth = null;
        if($db->loadObject($oauth)){
	       $retun->user_oauth_token=$oauth->user_oauth_token;
	       $retun->user_oauth_token_secret=$oauth->user_oauth_token_secret;
	       $retun->username=$oauth->username;
        }
        return $retun;
	}
	public function user_tokens_check()
	{
		global $model, $db;
		
		$db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'twitter' AND userID = " . $db->quote($model->profileID) . "" );
		$oauth = null;

        if($db->loadObject($oauth)){
	        if($oauth->user_oauth_token=="0" || $oauth->user_oauth_token_secret=="0")
	        {
	        	return 0;
	        }
	        else
	        {
	        	return 1;
	        }
        }
		else {
			return 0;
		}
	}
	public function user_profile_get()
	{
		global $model, $db;
		$t=$this->get_user_tokens();
		return json_decode(file_get_contents("https://api.twitter.com/1/users/show.json?screen_name=".$t->username));
	}
	public function get_friends()
	{
		global $model;
		$users = array();
		$tokens=$this->get_user_tokens();
		$this->twO = new TwitterOAuth($this->twitter_key, $this->twitter_secret,$tokens->user_oauth_token,$tokens->user_oauth_token_secret);
		$user_info = $this->twO->get('friends/ids', array('cursor' => -1));
		$users = $user_info->ids;

        while($user_info->next_cursor != 0){            	
        	$user_info = $this->twO->get('friends/ids.json', array('cursor' => $user_info->next_cursor));
			$users = array_merge($users,$user_info->ids);
     	}
		return $users;		
	}
	public function get_friendSuggestion($friends)
	{
		global $model, $db;
		
		$profiles = array();
		$i=1;
		$fSlice = array_chunk($friends, 100);

		foreach($fSlice as $f)
		{
			$query = "SELECT ID, name ,permalink, motto, image FROM profile WHERE twID IN (".implode(",", $f).") ";	// takip ettiklerini getirme 
			$db->setQuery($query);
			$p = NULL;
			$p = $db->loadObjectList();
			$profiles = array_merge($profiles,$p);	
							
			$i++;
		}
		return $profiles;
	}
}
?>
