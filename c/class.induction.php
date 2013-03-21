<?php 
class induction {

	public function set_voice_intduction($islem, $voice){
		global $model, $db;

		$puanClass	= new puan;
		$c_counter	= new counter;
		switch ($islem) {
			case 'new_share':
				$puanClass->puanIslem($voice->profileID,"1",$voice);
				$c_counter->set_profileCount($voice->profileID, "voice");
				if($voice->replyID>0)
				{
					$c_voice = new voice($voice->replyID);
					$puanClass->puanIslem($c_voice->_voice->profileID,"5",$c_voice->_voice);
					$c_counter->set_voiceCount($c_voice->_ID, "reply");
				}
				break;
				
			case 'redi_share':
                $puanClass->puanIslem($voice->redi,"4",$voice);
				$c_counter->set_profileCount($model->profileID, "voice");
				$c_counter->set_voiceCount($voice->rediID , "reShare");
				break;
				
			case 'like_voice':
				if($voice->dilike1==1)
	            {
					if($puanClass->get_oyGecerlimi($voice->voice->profileID,"2",$voice))// bu eylenden puan almıyorsa count ta yok
	            	{
	            		$puanClass->puanIslem($voice->voice->profileID,"2",$voice);
					}
					$c_counter->set_profileCount($voice->voice->profileID, "like");
					$c_counter->set_voiceCount($voice->voice->ID, "like");
					
					$model->notice($voice->voice->profileID, 'dilike', $voice->voice->ID, null, "dilike1");
					if($voice->reverse)
					{
						$c_counter->set_profileCount($voice->voice->profileID, "dislike", "-");
						$c_counter->set_voiceCount($voice->voice->ID, "dislike", "-");
					}
	            }
	            else if($voice->dilike2==1)
	            {
					if($puanClass->get_oyGecerlimi($voice->voice->profileID,"3",$voice)) // bu eylenden puan almıyorsa count ta yok
					{
						$puanClass->puanIslem($voice->voice->profileID,"3",$voice);
					}
					$c_counter->set_profileCount($voice->voice->profileID, "dislike");
					$c_counter->set_voiceCount($voice->voice->ID, "dislike");
					
					$model->notice($voice->voice->profileID, 'dilike', $voice->voice->ID, null, "dilike2");
					if($voice->reverse)
					{
						$c_counter->set_profileCount($voice->voice->profileID, "like", "-");
						$c_counter->set_voiceCount($voice->voice->ID, "like", "-");
					}
	            }
               	//$puanClass->puanIslem($voice->profileID,"4",$voice);
				
				//bir ses taktir edildiğinde profile ve ses e eklenicek eğer saygıdan taktire dönmüşse yada tam terse rakamlar yer değiştiricel
				
				
				//$c_counter->set_voiceCount($voice->ID, "reShare");
				break;
			case 'delete':
				$puanClass->puanIslem($voice->profileID,"7",$voice);
				$c_counter->set_profileCount($voice->profileID, "voice","-");
				if($voice->isReply=="1")
				{
					$c_counter->set_voiceCount($voice->replyID, "reply", "-");
				}
				break;
		}
		
	}
	public function set_profile_intduction($islem, $profile, $followID=0){
		global $model, $db;

		$puanClass	= new puan;
		$c_counter	= new counter;
		switch ($islem) {
			case 'follow':
				$model->notice($profile->ID, 'follow', $followID);

	           	$puanClass->puanIslem($profile->ID, "10");
	            if($profile->type=="profile")// profilse mail gönder hashtag ise gönderme
				{
					if($profile->emailperms>0)
	            	$model->sendsystemmail( $profile->email, 'Bir yeni takipçiniz var', 'Merhaba, <br /> <a href="http://democratus.com/'.$model->parmalink.'"> '.$model->profile->name.' </a> isimli kullanıcı artık sizi takip ediyor.  Bundan böyle sesiniz ona da ulaşacak.  <br /> <br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
				}
				
				$c_counter->set_profileCount($profile->ID, "follower");
				$c_counter->set_profileCount($model->profileID, "following");
			break;
			case "unfollow":
				$c_counter->set_profileCount($profile->ID, "follower", "-");
				$c_counter->set_profileCount($model->profileID, "following", "-");
			break;
			case 'deputy':
				$c_counter->set_profileCount($profile->ID, "deputy");
			break;
		}
	}
}
?>