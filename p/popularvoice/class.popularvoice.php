<?php
class popularvoice_plugin extends control{ 
	public function main(){ //return print( $this->location_to_select('loca', 89) );
    	global $model, $db;
		$model->template="ala";
		$model->view="default";
		$model->title = 'Democratus - Sesgetirenler';
		
		$model->addHeaderElement();
		
		$model->addScript("paths=".json_encode($model->paths));
		$model->addScript("plugin='popularvoice'");
    }
}
?>