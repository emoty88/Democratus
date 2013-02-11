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
		//echo "Popular sesler";
		?>
		<section class="banner">
				<header>
					<h1>Ses Getirenler</h1>
				</header>
				<!-- <img alt="" src="img/banner-adaylarim.png"> -->
		
				<div class="clearfix"></div>
			</section>
		<?
    }
}
?>