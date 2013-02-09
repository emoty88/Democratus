<?php
    class voice_plugin extends control{
        public function main(){
        	// bu sayfanın açılması için page tablosuna kayıt eklenmeli
        	global $model, $db, $l;
			$model->template="ala";
			$model->view="voice";
			$model->title = 'Democratus';
			
			$model->addHeaderElement();
			
			$c_voice = new voice($model->paths[1]);
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='voice'");
			$model->addScript("voiceID = ".$c_voice->_ID);
			
			$voiceRObj = $c_voice->get_return_object($c_voice->_voice);
			$model->addScript("voiceObj = ".json_encode($voiceRObj));
			$model->addScript("$(document).ready(function (){
				voiceDetail($('.voice_hover_area'));
			});")
			?>
			
			<?
        }
	}
?>