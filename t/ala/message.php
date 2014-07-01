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

									<? //{{meclis}} ?>

									{{populardies}}
                                    
                                    {{smsuggestion}}
									
									{{whotofollow}}

								</div>
							</div>
						</section><!-- // Yan Alan -->

						<!-- Orta Alan -->
						<section id="orta_alan" class="span8">
							<div class="satirlar padding_yok">
								<div id="orta_alan_container_">
									<section class="satir ilk_satir kirmizi_arkaplan" id="gelen_kutusu_baslik">
										<div class="satir_ic">
											<header>
                                                                                            <a href="/message" style="text-decoration: none;"><h1>Mesajlar</h1></a>
												<div class="komut_tutucu">
                                                                                                        <button id="delete_dialog" style="display: none" onclick="delete_dialog('<?=$model->paths[2]?>');" class="btn btn-medium fwbold">Konuşmayı Sil</button>
													<button onclick="new_messageToggle();" class="btn btn-medium fwbold">Yeni Mesaj</button>
                                                                                                       
												
                                                                                                </div>
											</header>
											<div class="clearfix"></div>
										</div>
									</section>
									<section class="satir ilk_satir" id="direkt_mesaj_formu_tutucu" style="margin-top: -20px; display:none;">
										<div class="satir_ic">
											<div name="direkt_mesaj_formu" id="direkt_mesaj_formu" >
												<div class="etiket_metin_kutusunun_ustunde">
													<label for="alici">Kime:</label>
													<input type="text" placeholder="Alıcının adı" id="alici" name="alici">
													<input type="hidden" name="aliciPerma" id="aliciPerma" value="0" />
												</div>
												<div class="karakter_sayaci_tutucu" id="yeni_yazi_yaz">
													<div class="textarea_tutucu">
														<textarea rows="4" placeholder="Mesaj" class="karakteri_sayilacak_alan" name="yeni_yazi" id="yeni_yazi"></textarea>
													</div>
													<div class="kalan_karakter_mesaji"><span class="karakter_sayaci">140</span> karakter</div>
	
													<div class="kontroller">
														<button class="btn btn-danger" onclick="send_newMesage();">Gönder</button>
														<!--
														<a href="javascript:void(0)" class="gorsel_ekle"><i class="atolye15-ikon-gorsel atolye15-ikon-24"></i></a>
														<a href="javascript:void(0)" class="dosya_ekle"><i class="atolye15-ikon-atac atolye15-ikon-24"></i></a>
														-->
													</div>
													<div class="clearfix"></div>
												</div>
											</div><!-- /#direkt_mesaj_formu -->
	
											<div class="clearfix"></div>
										</div>
									</section>
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
