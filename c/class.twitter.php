<?php 
class twitter{      
        public $twitter_key             = 'i0n7BGuUZ6xLXzs1ixg';
        public $twitter_secret          = 'wMv4BGlmUyWc3pz4v8rwDZFMuOxWUtRFYtPSbYP03Y'; 
        
        public function __construct() {
            if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
                    $this->twitter_key = 'm6Wxc0XrYoIpSI9Nfuhu6A';
                    $this->twitter_secret = 'NU2wI0SaFeJbEunfQhwxvB3q69XDtGur2cOcMpEI';
            }
        }
	 static public function main(){
        global $model, $db;
        
    }
	public function sendTweet($tweetText,$ID)
	{
		global $model,$db;
		require_once( COREPATH.'smclass/twitter/twitteroauth.php' );
		//require_once( COREPATH.'smclass/facebook/facebook.php' );
		$tokens=$this->get_user_tokens();
		$twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret,$tokens->key,$tokens->secret);
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
		$sonuc=$twitteroauth->post('statuses/update', array("status" => $tweetTextFinal));
		return $sonuc;
	}
	public function get_user_tokens()
	{
		global $model, $db;
		$retun=new stdClass();
		
		$db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'twitter' AND userID = " . $db->quote($model->profileID) . "" );
		$oauth = null;
        if($db->loadObject($oauth)){
	       $retun->key=$oauth->user_oauth_token;
	       $retun->secret=$oauth->user_oauth_token_secret;
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
}
?>
