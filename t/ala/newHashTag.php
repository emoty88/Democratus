<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
		<?php 
			global $model;
			$model->addStyle('http://fonts.googleapis.com/css?family=Ubuntu:700&subset=latin,latin-ext', 'http://fonts.googleapis.com/css?family=Ubuntu:700&subset=latin,latin-ext', 1 ); 
			$model->addStyle($this->url.'css/bootstrap-custom-build.css', 'bootstrap-custom-build.css', 1 ); 
			$model->addStyle($this->url.'css/bootstrap-helper.css', 'bootstrap-helper.css', 1 ); 
			$model->addStyle($this->url.'css/ui-lightness/jquery-ui-1.9.1.custom.min.css', 'ui-lightness/jquery-ui-1.9.1.custom.min.css', 1 ); 
			$model->addStyle($this->url.'css/app.css', 'app.css', 1 ); 
			
		?>
		<?php 
	       
		?>

    </head>
    <body class="atolye15_body ana_sayfa">

		<!-- Atölye15 -->
		<div id="atolye15">

			<!--[if lt IE 7]>
				<p class="chromeframe">Modası geçmiş bir tarayıcı kullanıyorsunuz. Bu siteyi daha iyi görüntülemek için <a href="http://browsehappy.com/">Tarayıcınızı Bugün Güncelleyin</a> ya da <a href="http://www.google.com/chromeframe/?redirect=true">Google Chrome Çerçevesini Yükleyin</a></p>
			<![endif]-->

			{{header}}

			<!-- İçerik -->
			<div id="icerik">
				<div class="container">
					<div class="row prelative">
						
						<!-- Yan Alan -->
						<section id="yan_alan" class="span4">
							<div class="satirlar">
								
								{{sidebarTop}}
								
								<div class="satir bilesenler">
									
									{{quickEditor}}
									<div class="hashTag_slide">
									{{hashtagSug}}
									</div>
									
                                  	

								</div>
							</div>
						</section><!-- // Yan Alan -->

						<!-- Orta Alan -->
						<section id="orta_alan" class="span8">
							<div class="satirlar padding_yok">
								{{newHashtagDetail}}
								<div id="pormotedVoice"></div>
								<div style="margin-left:20px;">
									<span style="font-size: 12pt; font-weight: bold;">Talepler: </span> 
									<a href="#natural" id="nh-enyeni"  >En Yeni</a> / <a href="#popular" id="nh-eniyi" style="font-weight: normal;" >En İyi</a>
								</div>
								<div id="orta_alan_container">
									{{main}}
								</div>
							</div>
						</section><!-- // Orta Alan -->

					</div>
				</div>
			</div><!-- // İçerik -->
			<a href="/home/tour" class="tour_btn hidden-phone">
				<div>
					?
				</div>
			</a>
			{{footer}}

		</div> <!-- // Atölye15 -->

        <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
		{{jstemplates}}
		{{googleanalytics}}
    </body>
</html>