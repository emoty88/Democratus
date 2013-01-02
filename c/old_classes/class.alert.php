<?php
	class alert{
		static public function main()
		{
			global $model, $db;
			if($model->profile->status==5)
			{
				self::alertShow_mailonay();
				//die;
			}	
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
