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
				break;
				
			case 'redi_share':
                $puanClass->puanIslem($voice->redi,"4",$voice);
				$c_counter->set_profileCount($model->profileID, "voice");
				$c_counter->set_voiceCount($voice->ID, "reShare");
				break;
				
			case 'like_voice':
				if($voice->dilike1==1)
	            {
					if($puanClass->get_oyGecerlimi($voice->voice->profileID,"2",$voice))// bu eylenden puan almıyorsa count ta yok
	            	{
	            		$c_counter->set_profileCount($voice->voice->profileID, "like");
						$puanClass->puanIslem($voice->voice->profileID,"2",$voice);
					}
	            }
	            else if($voice->dilike2==1)
	            {
					if($puanClass->get_oyGecerlimi($voice->voice->profileID,"3",$voice)) // bu eylenden puan almıyorsa count ta yok
					{
						$c_counter->set_profileCount($voice->voice->profileID, "dislike");
						$puanClass->puanIslem($voice->voice->profileID,"3",$voice);
					}
	            }
               	//$puanClass->puanIslem($voice->profileID,"4",$voice);
				
				//bir ses taktir edildiğinde profile ve ses e eklenicek eğer saygıdan taktire dönmüşse yada tam terse rakamlar yer değiştiricel
				
				
				//$c_counter->set_voiceCount($voice->ID, "reShare");
				break;
		}
		
	}
	public function set_profile_intduction($islem, $profile, $followID){
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
		}
	}
}
?>