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
    $model->addScript($this->url . 'static/javascript/jquery.js', 'static/javascript/jquery.js', 1 );
    $model->addScript($this->url . 'static/javascript/cufon.js', 'static/javascript/cufon.js', 1 );
    $model->addScript($this->url . 'static/javascript/ronnia.js', 'static/javascript/ronnia.js', 1 );
    $model->addScript($this->url . 'static/javascript/popup.js', 'static/javascript/popup.js', 1 );
    $model->addScript($this->url . 'static/javascript/checkbox.js', 'static/javascript/checkbox.js', 1 );
    $model->addScript($this->url . 'static/javascript/slider.js', 'static/javascript/slider.js', 1 );
    $model->addScript($this->url . 'static/javascript/generic.js', 'static/javascript/generic.js', 1 );
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
					checked: 	"url(static/image/form/checkbox_focus.png)",
					unchecked:	"url(static/image/form/checkbox.png)",
					width:		18,
					height:		17
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
						{{userinfo}}
						
                        {{followings}}
						
						{{followers}}
					</div>
					<!-- Main Left Column [End] -->
					
					<!-- Main Center Column [Begin] -->
					<div class="center">
                        {{main}}
						
                        {{agendalist}}
                        
						<div class="join_democratus"></div>
						
						<div class="box" id="confirmed_agenda">
							<span class="title">Oylanmış Gündemler</span>
							
							<div class="box">
								<div class="head">
									<div class="image"><img src="<?=$this->url;?>static/image/users/medium/1.jpg" /></div>
									<div class="content">
										<div class="top">
											<span class="name">Robert Smith</span>
											<span class="date">3 gün önce</span>
											<span class="post_right">	
												<span class="comment">Söyleş</span>
												<span class="share">Paylaş</span>
												<span class="x" rel="1"></span>
											</span>
										</div>
										<div class="line_center"></div>
										<div class="bottom">
											<p>Leicester Üniversitesi Bilgisayar Oyunları Dizaynı Bölümü’ne kabul edilen 20 yaşındaki Chris Staniforth’un ölüm nedenin ‘akciğere bağlı damar tıkanıklığı’ olarak da bilinen ‘pulmonary embolism’ olduğu açıklandı. Söz konusu hastalığın en büyük nedeninin ‘hareketsiz saatlerce aynı pozisyonda oturmak.</p>
											<span>İstanbul, 35, www.google.com</span>
										</div>
									</div>
								</div>
								
								<div class="clear"></div>
								
								<div class="line_center"></div>
								
								<div class="statistic">
									<span>Aldığı Oy: 344</span>
									<span>Sonuç: Kesinlikle Katılıyorum %44</span>
								</div>
								
								<div class="clear"></div>
								
								
							</div>
							
							<div class="page_more"></div>
						</div>
						
						
						
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