<html class=" js no-flexbox canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths" style=""><!--<![endif]--><head>
        <meta charset="utf-8">
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        
		<!-- SEO Meta -->
		<title>Democratus :: Giriş</title>
		<meta content="Bir başka sosyal paylaşım platformu..." name="description">
		<meta content="noindex, nofollow" name="robots">
		<meta content="noindex, nofollow" name="googlebot"><!-- // SEO Meta -->
        
		<meta content="width=device-width" name="viewport">
		
		
		<?php 
			global $model;
			$model->addStyle('http://fonts.googleapis.com/css?family=Ubuntu:700&subset=latin,latin-ext', 'http://fonts.googleapis.com/css?family=Ubuntu:700&subset=latin,latin-ext', 1 ); 
			$model->addStyle($this->url.'css/bootstrap-custom-build.css', 'bootstrap-custom-build.css', 1 ); 
			$model->addStyle($this->url.'css/bootstrap-helper.css', 'bootstrap-helper.css', 1 ); 
			$model->addStyle($this->url.'css/blitzer/jquery-ui-1.9.2.custom.min.css', 'blitzer/jquery-ui-1.9.2.custom.min.css', 1 ); 
			$model->addStyle($this->url.'css/welcome.css', 'app.css', 1 ); 
			echo  '<!--[if lte IE 9]>';
			$model->addStyle($this->url.'css/ie.css', 'ie.css', 1 ); 
			echo '<![endif]-->';
			
	        
		?>
		<?php 
	       
		?>

		
		
		<!-- Google Web Font -->
		
	 	<!--[if lte IE 9]><link rel="stylesheet" href="css/ie.css"><![endif]-->
		<link href="favicon.ico" rel="shortcut icon">
		<!-- Bu web sitesinin tasarımı ve kodlaması özgündür! -->
		<meta content="Atölye15" name="generator">
    </head>
    <body class="atolye15_body login">
    {{main}} 
    		<h1 class="logo"><a href="#">Democratus</a><span>Dünyayı fikirlerinle şekillendir!</span></h1>
    		
    		<div class="clearfix"></div>
    		
    		{{login}}
    		
    		<div class="clearfix"></div>
    		<? /*
    		<ul class="features">
    			<li>
    				<div class="img"><img alt="" src="<?=TEMPLATEURL?>/ala/img/feature-1.jpg"></div>
    				<h3>Fikrini paylaş,<br>sesini tüm ülke duysun!</h3>
    			</li>
    			<li>
    				<div class="img"><img alt="" src="<?=TEMPLATEURL?>/ala/img/feature-2.jpg"></div>
    				<h3>Referandumlara oy ver,<br>ülke gündemini değiştir!</h3>
    			</li>
    			<li>
    				<div class="img"><img alt="" src="<?=TEMPLATEURL?>/ala/img/feature-3.jpg"></div>
    				<h3>#konu ve #kurum sayfalarıyla,<br>yönetimlere yardım et!</h3>
    			</li>
    			<li class="last">
    				<div class="img"><img alt="" src="<?=TEMPLATEURL?>/ala/img/feature-4.jpg"></div>
    				<h3>Vekil seçilerek,<br>gündemleri sen belirle!</h3>
    			</li>
    		</ul>
    		*/ ?>
    		<div class="clearfix"></div>
    		
    		<section class="signup" style="display:none;">
    			<button id="wellcome-register-button" class="btn btn-large btn-block">Hemen Kayıt Ol</button>
    			<div class="clearfix"></div>
    		</section>
    		
    		<div class="clearfix"></div>
    
       <!-- <script>window.jQuery || document.write('&lt;script src="js/jquery-1.8.2.min.js"&gt;&lt;\/script&gt;')</script><script src="js/jquery-1.8.2.min.js"></script>
				<script src="js/login.js"></script>
    -->
    {{googleanalytics}}
</body></html>