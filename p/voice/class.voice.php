<?php
    class voice_plugin extends control{
        public function main(){
        	// bu sayfanın açılması için page tablosuna kayıt eklenmeli
        	global $model, $db, $l;
			$model->template="ala";
			$model->view="voice";
			$model->title = 'Democratus';
			
			$model->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery.tmpl.js", "jquery.tmpl.js", 1);
			
			$c_voice = new voice($model->paths[1]);
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='voice'");
			$model->addScript("voiceID = ".$c_voice->_ID);
			
			$voiceRObj = $c_voice->get_return_object($c_voice->_voice);
			$model->addScript("voiceObj = ".json_encode($voiceRObj));
			?>
			
			<?
        }
	}
?>