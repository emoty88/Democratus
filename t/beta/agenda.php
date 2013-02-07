<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php global $model; ?>
    <meta charset="utf-8">
    <title>Democratus - "Dünyayı fikirlerinle şekillendir!"</title>
    <link href="demcratus.com/images/democratusFav.ico" rel="shortcut icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <?php 
    
    $model->addStyle($this->url . 'docs/assets/css/bootstrap.css', 'docs/assets/css/bootstrap.css', 1 ); 
    $model->addStyle($this->url . 'docs/assets/css/bootstrap-responsive.css', 'docs/assets/css/bootstrap-responsive.css', 1 ); 
    $model->addStyle($this->url . 'docs/assets/css/docs.css', 'docs/assets/css/docs.css', 1 ); 
    $model->addStyle($this->url . 'docs/assets/js/google-code-prettify/prettify.css', 'docs/assets/js/google-code-prettify/prettify.css', 1 ); 
	$model->addStyle($this->url . 'http://html5shim.googlecode.com/svn/trunk/html5.js', 'http://html5shim.googlecode.com/svn/trunk/html5.js', 1,true, "if lt IE 9" );

	?> 
  
    <!-- Le fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="docs/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="docs/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="docs/assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>


    

<div class="container">
	<div class="navbar">
		{{navBarNew}}
  	</div>   
  		{{alertarea}}
	<div class="left-column">
		{{meclisMini}}
		<p></p>
		{{populardies}}
		<p></p>
		{{benzersiz}} 
	</div>
	<div class="right-column">		
        	{{main}} 
	</div> 
</div>


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
 	<?php 
 		//$model->addScript($this->url . 'docs/assets/js/jquery.js', 'jquery.js', 1);
 		/*
 		$model->addScript($this->url . 'docs/assets/js/google-code-prettify/prettify.js', 'prettify.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-transition.js', 'bootstrap-transition.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-alert.js', 'bootstrap-alert.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-modal.js', 'bootstrap-modal.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-dropdown.js', 'bootstrap-dropdown.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-scrollspy.js', 'bootstrap-scrollspy.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-tab.js', 'bootstrap-tab.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-tooltip.js', 'bootstrap-tooltip.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-popover.js', 'bootstrap-popover.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-button.js', 'bootstrap-button.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-collapse.js', 'bootstrap-collapse.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-carousel.js', 'bootstrap-carousel.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/bootstrap-typeahead.js', 'bootstrap-typeahead.js', 1);
 		$model->addScript($this->url . 'docs/assets/js/application.js', 'application.js', 1);
 		//$model->addScript($this->url . 'js/slider.js', 'slider.js', 1);
 		//$model->addScript($this->url . 'js/generic.js', 'generic.js', 1);
 		//$model->addStyle($this->url . 'style/boxes.css', 'boxes.css', 1);
 		*/
    
    ?>
    <script src="<?=$this->url?>docs/assets/js/google-code-prettify/prettify.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-transition.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-alert.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-modal.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-dropdown.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-scrollspy.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-tab.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-tooltip.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-popover.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-button.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-collapse.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-carousel.js"></script>
    <script src="<?=$this->url?>docs/assets/js/bootstrap-typeahead.js"></script>
    <script src="<?=$this->url?>docs/assets/js/application.js"></script>
    <script src="<?=$this->url?>docs/assets/js/checkbox.js"></script>
    <script src="<?=$this->url?>docs/assets/js/script.js"></script>
    <script src="<?=$this->url?>docs/assets/js/rutin.js"></script>
	{{geribildirim}}
	{{modal}}
	{{yandexmetrica}}
	{{kissmetrics}}
	{{footer}}
  </body>
</html>
