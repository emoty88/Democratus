<?php
class popularvoice_plugin extends control{ 
	public function main(){ //return print( $this->location_to_select('loca', 89) );
    	global $model, $db;
		$model->checkLogin(1);
		
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
				<ul class="alt_menu visible-desktop" id="tab-container" >
					<li class="active"><a href="#tab-sesgetirenler" rel="sesgetirenler" data-toggle="tab" >Ses Getirenler</a></li>
					<li><a href="#tab-yukselenler" rel="yukselenler" data-toggle="tab" >Yükselenler</a></li>
					<li><a href="#tab-ulkeduvari" rel="ulkeduvari" data-toggle="tab" >Ülke Duvarı</a></li>
				</ul>
				<select class="mobil_menu hidden-desktop" id="alt_menu_mobil">
					<option value="#tab-sesgetirenler">Ses Getirenler</option>
					<option value="#tab-yukselenler">Yükselenler</option>
					<option value="#tab-ulkeduvari">Ülke Duvarı</option>
				</select>
				<div class="clearfix"></div>
			</section>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="tab-sesgetirenler">
					<div id="sesgetirenler-content" style="z-index: 200;"></div>
				</div>
				<div class="tab-pane fade in active" id="tab-yukselenler">
					<div id="yukselenler-content" style="z-index: 200;"></div>
				</div>
				<div class="tab-pane fade in active" id="tab-ulkeduvari">
					<div id="ulkeduvari-content" style="z-index: 200;"></div>
				</div>
			</div>
		<?
    }
}
?>
