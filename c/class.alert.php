<?php
	class alert{
		static public function main()
		{
			global $model, $db;
			if($model->profile->status==5)
			{
				self::alertShow_mailonay();
				
			}	
			//self::social_firend_find();
		}
		public function social_firend_find()
		{
			global $model;
			$aHeader="Break break arkadaş arıyorum!";
			$aTextr="Diğer sosyal ağlardaki arkadaşlarını bul ve takip et.";
			$aButton='<a class="btn btn-danger" href="/my">Arkadaşlarımı Bul</a>';
			$model->addScript("$(document).ready(function(){ show_alertBox('alert','".$aHeader."','".$aTextr."','".$aButton."'); });  ");
		}
		
		public function alertShow_mailonay()
		{
			global $model;
			$aHeader="Hesabınız henüz aktive edilmemiş.";
			$aTextr="Hesabınızı aktive etmek için ".$model->user->email." adresine yolladığımız linke tıklamanız gerekmektedir.";
			$aButton='<a class="btn btn-danger" href="javascript:send_activateMail(\\\''.$model->user->email.'\\\');">Aktivasyon mailini yeniden gönder</a>';
			$model->addScript("$(document).ready(function(){ show_alertBox('alert','".$aHeader."','".$aTextr."','".$aButton."'); });  ");
		}
		public function warninShow_notActivateWriteVoice()
		{
			global $model;
			$aHeader="Hesabınız henüz aktive edilmemiş.";
			$aTextr="Democratus üzerinde paylaşım yapabilmek için Hesabınızı aktive etmelisiniz.";
			$aButton="";//'<a class="btn btn-danger" href="javascript:send_activateMail(\\\''.$model->user->email.'\\\');">Aktivasyon mailini yeniden gönder</a>';
			$model->addScript("$(document).ready(function(){ show_alertBox('warning','".$aHeader."','".$aTextr."','".$aButton."'); });  ");
		}
	}
?>
