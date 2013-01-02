<?php 
class urlshorter{
	 static public function main(){
        global $model, $db;
    }
	public function useBitly($url)
	{
		error_reporting(0);
		require_once( COREPATH.'urlshorter/bitly.php' );
		$results = bitly_v3_shorten($url);
		return $results;		
	}
	public function findUrl($t)
	{
		$reg_exUrl = "/(http|https|ftp|ftps|www)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		$text=str_replace(" www."," http://www.", " ".$t);
		
		if(preg_match_all($reg_exUrl, $text, $url)) {
			//  echo preg_replace($reg_exUrl, "<a href=".$url[0].">".$url[0]."</a> ", $text);
			return $url[0];
		} else {
			return false;
		}
	}
	public function changeUrlShort($text)
	{	
		$urls=$this->findUrl($text);
		$text=str_replace(" www."," http://www.", " ".$text);
		if($urls)
		{
			foreach($urls as $u)
			{
				$tsu=$this->useBitly($u);
				$text=str_replace($u,$tsu["url"],$text);
			}
		}
		return $text;
		
	}
}
?>
