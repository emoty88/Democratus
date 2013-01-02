<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <title>Democratus</title>
        <link href="demcratus.com/images/democratusFav.ico" rel="shortcut icon" type="image/x-icon" />
        <!-- CASCADING STYLE SHEETS [BEGIN] -->
        <link type="text/css" rel="stylesheet" href="static/style/reset.css" />
        <link type="text/css" rel="stylesheet" href="static/style/generic.css" />
        <link type="text/css" rel="stylesheet" href="static/style/boxes.css" />
        <link type="text/css" rel="stylesheet" href="static/style/popup.css" />
        <link type="text/css" rel="stylesheet" href="static/style/tooltip.css" />
        <!--[if IE 8]>
            <style type="text/css">
                .main .left .box .users_small { margin-left: 3px; }
                .main .left .box .users_small li { margin-right: 7px !important;}
            </style>
        <![endif]-->
        <!-- CASCADING STYLE SHEETS [END] -->
        
        <?php
    global $model;
    $model->addStyle($this->url . 'static/style/reset.css', 'static/style/reset.css', 1 );  
    $model->addStyle($this->url . 'static/style/generic.css', 'static/style/generic.css', 1 );  
    $model->addStyle($this->url . 'static/style/boxes.css', 'static/style/boxes.css', 1 );  
    $model->addStyle($this->url . 'static/style/popup.css', 'static/style/popup.css', 1 );  
    $model->addStyle($this->url . 'static/style/tooltip.css', 'static/style/tooltip.css', 1 );  
    $model->addStyle('.main .left .box .users_small { margin-left: 3px; }.main .left .box .users_small li { margin-right: 7px !important;}', null, 0, 'IE 8' );  
    
    
    //$model->addScript('http://use.typekit.com/yqj2pnd.js', 'http://use.typekit.com/yqj2pnd.js', 1 );
    //$model->addScript('try{Typekit.load();}catch(e){}');
    //$model->addScript($this->url . 'static/javascript/jquery.js', 'static/javascript/jquery.js', 1 );
    //$model->addScript($this->url . 'static/javascript/jquery-ui.js', 'static/javascript/jquery-ui.js', 1 );
    $model->addScript($this->url . 'static/javascript/cufon.js', 'static/javascript/cufon.js', 1 );
    $model->addScript($this->url . 'static/javascript/ronnia.js', 'static/javascript/ronnia.js', 1 );
    $model->addScript($this->url . 'static/javascript/popup.js', 'static/javascript/popup.js', 1 );
    $model->addScript($this->url . 'static/javascript/checkbox.js', 'static/javascript/checkbox.js', 1 );
    $model->addScript($this->url . 'static/javascript/slider.js', 'static/javascript/slider.js', 1 );
    $model->addScript($this->url . 'static/javascript/generic.js', 'static/javascript/generic.js', 1 );
    $model->addScript($this->url . 'static/javascript/countdown.js', 'static/javascript/countdown.js', 1);
    $model->addScript($this->url . 'static/javascript/easing.js', 'static/javascript/easing.js', 1);
    $model->addScript($this->url . 'static/javascript/mousewheel.js', 'static/javascript/mousewheel.js', 1);
    $model->addScript($this->url . 'static/javascript/scrollbar.js', 'static/javascript/scrollbar.js', 1);
    
    $model->addScript($this->url . 'static/javascript/corners.js', 'static/javascript/corners.js', 1 , 'IE 8');
    $model->addScript("
                curvyCorners.addEvent(window, 'load', initCorners);
                function initCorners()
                {
                    var settings = {
                        tl: { radius: 5 },
                        tr: { radius: 5 },
                        bl: { radius: 5 },
                        br: { radius: 5 },
                        antiAlias: true }

                    curvyCorners(settings, '.box');
                    curvyCorners(settings, '.main');
                    
                  }", null, 0 , 'IE 8');
                  
    $model->addScript($this->url . 'static/javascript/script.js', 'static/javascript/script.js', 1 );
?>
        
        <!-- JAVASCRIPTS [BEGIN] -->
        <script type="text/javascript" src="http://use.typekit.com/yqj2pnd.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
        
        <script type="text/javascript" src="static/javascript/jquery.js"></script>
        <script type="text/javascript" src="static/javascript/cufon.js"></script>
        <script type="text/javascript" src="static/javascript/ronnia.js"></script>
        <script type="text/javascript" src="static/javascript/popup.js"></script>
        <script type="text/javascript" src="static/javascript/checkbox.js"></script>
        <script type="text/javascript" src="static/javascript/slider.js"></script>
        <script type="text/javascript" src="static/javascript/generic.js"></script>
        <!-- JAVASCRIPTS [END] -->
        
        <!--[if IE]>
            <script type="text/javascript" src="static/javascript/corners.js"></script>
            <script type="text/javascript">
                curvyCorners.addEvent(window, 'load', initCorners);
                function initCorners()
                {
                    var settings = {
                        tl: { radius: 5 },
                        tr: { radius: 5 },
                        bl: { radius: 5 },
                        br: { radius: 5 },
                        antiAlias: true }

                    curvyCorners(settings, '.box');
                    curvyCorners(settings, '.main');
                    
                  }
            </script>
        <![endif]-->
        
        <script type="text/javascript">
            $(function () {
                $('input:radio').screwDefaultButtons({
                    checked:     "url(static/image/form/checkbox_focus.png)",
                    unchecked:    "url(static/image/form/checkbox.png)",
                    width:        18,
                    height:        17
                });
            });
        </script>
    </head>
    
    <body>
        
        <!-- CONTAINER [BEGIN] -->
        <div id="container">
            
            <!-- HEADER [BEGIN] -->
            <div id="header">
                
                <!-- Logo & Search [Begin] -->
                <div class="logo">
                    <a href="/"><img src="<?=$this->url;?>static/image/background/header/logo.png" alt="Democratus - The Political Network by Which You Can Shape Your World" /></a>
                </div>
<?php
    if($model->profileID>0) {
?>              
  {{search}}{{headermenu}}
<?php
    } else {
?>

                 {{headerlogin}}
<?php
    }
?>
                
                
                
            </div>
            
            <!-- MAIN WRAPPER [BEGIN] -->
            <div id="main_wrapper">
                
                <!-- Main [Begin] -->
                <div class="main_left"></div>
                <div class="main">
                    
                    <!-- Main Left Column [Begin] -->
                    <div class="left">
                        {{followings2}}
                        
                        {{followers2}}
                    </div>
                    <!-- Main Left Column [End] -->
                    
                    <!-- Main Center Column [Begin] -->
                    <div class="center">
                        {{main}}
                    </div>
                    <!-- Main Center Column [End] -->
                    
                    <!-- Main Right Column [Begin] -->
                    <div class="right">
                        
                        {{adversite}}
                        
                        {{populardies}}
                                                
                        {{clue}}
                        
                    </div>
                    <!-- Main Right Column [End] -->

                </div>
                <div class="main_right"></div>
                <!-- Main [End] -->
            
            </div>
            <!-- MAIN WRAPPER [END] -->
            
            
            <div class="clear"></div>
            
            {{footer}}
        
        </div>
        <!-- CONTAINER [END] -->
        	<script type="text/javascript">
	(function (d, w, c) {
		(w[c] = w[c] || []).push(function() {
			try {
				w.yaCounter14085859 = new Ya.Metrika({id:14085859, enableAll: true, trackHash:true, webvisor:true});
			} catch(e) {}
		});
		
		var n = d.getElementsByTagName("script")[0],
			s = d.createElement("script"),
			f = function () { n.parentNode.insertBefore(s, n); };
		s.type = "text/javascript";
		s.async = true;
		s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

		if (w.opera == "[object Opera]") {
			d.addEventListener("DOMContentLoaded", f);
		} else { f(); }
	})(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="//mc.yandex.ru/watch/14085859" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
    </body>
</html>