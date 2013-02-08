<?php
class popularvoice_plugin extends control{ 
	public function main(){ //return print( $this->location_to_select('loca', 89) );
    	global $model, $db;
		$model->template="ala";
		$model->view="default";
		$model->title = 'Democratus - Sesgetirenler';
		
		$model->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
		$model->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
		$model->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
		$model->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
		$model->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
		$model->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);
		$model->addScript(TEMPLATEURL."ala/js/jquery.tmpl.js", "jquery.tmpl.js", 1);
		
		//$model->addScript(TEMPLATEURL."ala/js/howtouse.js", "howtouse.js", 1);
		//$model->addScript(TEMPLATEURL."ala/js/jquery.scrollTo.min.js", "jquery.scrollTo.min.js", 1);
		
		
		$model->addScript("paths=".json_encode($model->paths));
		$model->addScript("plugin='popularvoice'");
    }
}
?>