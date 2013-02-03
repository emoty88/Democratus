<?php
    class my_plugin extends control{
    	public function main()
		{
                        model::checkLogin(1);
			global $model, $db, $l;
			$model->template="ala";
			$model->view="my";
			$model->title = 'Democratus';

			$model->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);

            $model->addScript(TEMPLATEURL."ala/js/jquery.tmpl.js", "jquery.tmpl.js", 1);
			
			$model->addScript("http://democratus.com/t/beta/docs/assets/js/checkbox.js","checkbox.js",1); // düzenle
            $model->addScript(PLUGINURL."my/my.js", "my.js", 1);
            $model->addScript(PLUGINURL . 'lib/fineuploader/jquery.fineuploader-3.0.js', 'fileuploader-3.0.js', 1 );
			$model->addStyle(PLUGINURL . 'lib/fineuploader/fileuploader.css', 'fileuploader.css', 1 );
			
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='my'");
			
			$c_profile = new profile ;
			$profile= $model->profile;
			if($model->paths[1]=="faceReturn")
			{
				return $this->faceReturn();
			}
			if($model->paths[1]=="twitterReturn")
			{
				return $this->twitterReturn();
			}
			
			
			$birth= date_parse($profile->birth);
			if($birth['day']<10){
				$birth['day'] = '0'.$birth['day'];
			}
			$birth['month']=model::int2trMonth($birth['month']);
	                        
	        $profileChecked[$profile->showprofile]=' checked="ture" ';
	        $birthChecked[$profile->showbirth]=' checked="ture" ';
	        $mottoChecked[$profile->showmotto]=' checked="ture" ';
	        $showdiesChecked[$profile->showdies]=' checked="ture" ';
	        $dicommentChecked[$profile->dicomment]=' checked="ture" ';
	        $hometownChecked[$profile->showhometown]=' checked="ture" ';
	        $countryChecked[$profile->showcountry]=' checked="ture" ';
	        $cityChecked[$profile->showcity]=' checked="ture" ';
	        $materialChecked[$profile->showmarital]=' checked="ture" ';
	        $educationChecked[$profile->showeducation]=' checked="ture" ';
	        $hobbiesChecked[$profile->showhobbies]=' checked="ture" ';
	
	        $langChecked[$profile->showlanguages]=' checked="ture" ';
	        $emailChecked[$profile->showemail]=' checked="ture" ';
	        $followersChecked[$profile->showfollowers]=' checked="ture" ';
	        $followingsChecked[$profile->showfollowings]=' checked="ture" ';
	        $photosChecked[$profile->showphotos]=' checked="ture" ';
			?>
			<section class="banner">
				<header>
					<h1>Profil Ayarları</h1>
				</header>
				
				<nav>
					<ul class="alt_menu visible-desktop" id="tab-container">
						<li class="active"><a href="#tab-profilA" rel="profilA" data-toggle="tab">PROFİL AYARLARI</a></li>
						<li><a href="#tab-arkadasB" rel="arkadasB" data-toggle="tab">ARKADAŞ BUL</a></li>
						<li><a href="#tab-gizlilik" rel="gizlilik" data-toggle="tab">GİZLİLİK</a></li>
						<li><a href="#tab-engellemeler" rel="engellemeler" data-toggle="tab">ENGELLEMELER</a></li>
					</ul>
					<select class="mobil_menu hidden-desktop" id="alt_menu_mobil">
						
					</select>
				</nav>
				<div class="clearfix"></div>
			</section>
			<div class="tab-content">
				<!-- Profile Ayarları Tab -->
				<div class="tab-pane fade in active" id="tab-profilA">
					
					<section class="satir ilk_satir uste_cikar" id="ayarlar">
						<div class="satir_ic">
							<header>
								<h1 class="sayfa_basligi">Profil Ayarlarım</h1>
							</header>
							<?php
								$rt=$c_profile->check_userMin();
								if(!$rt["success"])
								{
									foreach($rt["errors"] as $e)
									{
										switch($e)
										{
											case "permalink" : $varningT[] = "Benzersiz kullanıcı Adınızı "; break;
											case "email" : $varningT[] ="E-posta adresinizi "; break;
										}
									}
									echo "<p> ".implode(" ve ", $varningT)." doğru ve eksizsiz şekilde girmelisiniz !</p>";
								}
							?>
							<form  id="profil_bilgileri_formu" name="profil_bilgileri_formu" method="post">
							
								<div class="sol_form_bolumu span4">
									<label for="kullanici_adi">Kullanıcı Adı</label>
									<input type="text" placeholder="Kullanıcı Adı" id="kullanici_adi" name="kullanici_adi" value="<?=$profile->permalink?>">
									<i class="icon-exclamation-sign" id="usernameCheck" data-original-title="Bu kullanıcı adı daha önce alınmış." style="display: none;padding: 0"></i>
									<i class="icon-exclamation-sign" id="usernameValidate" data-original-title="Geçersiz kullanıcı adı." style="display: none;padding: 0"></i>
									
									<label for="ad_soyad">Ad Soyad</label>
									<input type="text" placeholder="Ad Soyad" id="ad_soyad" name="ad_soyad"  value="<?=$profile->name?>">
									<i class="icon-exclamation-sign" id="ad_soyadValidate" data-original-title="Geçersiz isim." style="display: none;padding: 0"></i>
									
									<label for="eposta">E-posta adresi</label>
									<input type="text" placeholder="E-posta adresi" id="eposta" name="eposta" value="<?=$model->user->email?>">
									<i class="icon-exclamation-sign" id="emailCheck" data-original-title="Bu mail adresi kullanılıyor" style="display: none;padding: 0"></i>
									<i class="icon-exclamation-sign"id="emailValidate" data-original-title="Geçersiz email adresi." style="display: none;padding: 0"></i>
									
									<label for="gun">Doğum Tarihi</label>
									<div class="yatayda_siralanacaklar">
										
										<!-- Gün -->
										<div class="yatayda_siralanacak">
											<div class="input-append akilli_select gun">
												<input type="text" value="<?=$birth['day']?>" maxlength="2" placeholder="Gün" name="gun" id="gun" class="span1">
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu pull-right">
														<?
														for($i=1;$i<32;$i++)
														{
															if($i<10)
																$yaz="0".$i;
															else
																$yaz=$i;
															echo '<li><a href="#">'.$yaz.'</a></li>';
														} 
														?>
													</ul>
												</div>
											</div>
										</div>

										<!-- Ay-->
										<div class="yatayda_siralanacak">
											<div class="input-append akilli_select ay">
												<input type="text" value="<?=$birth['month']?>" placeholder="Ay" name="ay" id="ay" class="span1">
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu pull-right">
														<li><a href="#">Ocak</a></li>
														<li><a href="#">Şubat</a></li>
														<li><a href="#">Mart</a></li>
														<li><a href="#">Nisan</a></li>
														<li><a href="#">Mayıs</a></li>
														<li><a href="#">Haziran</a></li>
														<li><a href="#">Temmuz</a></li>
														<li><a href="#">Ağustos</a></li>
														<li><a href="#">Eylül</a></li>
														<li><a href="#">Ekim</a></li>
														<li><a href="#">Kasım</a></li>
														<li><a href="#">Aralık</a></li>
													</ul>
												</div>
											</div>
										</div>

										<!-- Yıl -->
										<div class="yatayda_siralanacak">
											<div class="input-append akilli_select yil">
												
													<input value="<?=$birth['year']?>" type="text" maxlength="4" placeholder="Yıl" name="yil" id="yil" class="span1">
												
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu pull-right">
														<?php for($i=date('Y');$i>1899;$i--): ?>
														<li><a href="#"><?=$i?></a></li>
														<?php endfor; ?>
													</ul>
												</div>
											</div>
										</div>

										<div class="clearfix"></div>
									</div><!-- /.yatayda_siralanacaklar -->
									<div class="clearfix"></div>

									<label for="cinsiyet">Cinsiyet</label>
									<div class="input-append akilli_select cinsiyet">
										<input type="text" value="<?=model::sex2trSex($profile->sex)?>" placeholder="Cinsiyet" name="cinsiyet" id="cinsiyet" class="span1">
										<div class="btn-group">
											<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
											<ul class="dropdown-menu pull-right">
												<li><a href="#">Erkek</a></li>
												<li><a href="#">Kadın</a></li>
											</ul>
										</div>
									</div>
                                                                        
								</div><!-- /.sol_form_bolumu -->

								<div class="sag_form_bolumu span3">
									<div class="profil_resmi">
										<img id="profileImage" alt="" src="<?=$model->getProfileImage($profile->image,200,200,"cutout");?>">
										<p>Profil Resmi Yükle<br><small>(JPG, GIF, PNG); 1000 KB Max</small></p>
                                                                                <div class="myImageUpload" data-upload="profileImage">
                                                                                        <button data-ftext="Resmi Güncelle"  style="width: 209px" class="btn btn-info "  type="button">Resmi Güncelle</button>
                                                                                </div>
										<div style="display:none" class="suslu_dosya_yukle_dugmesi">
											<input style="" type="file" value="" id="profil_resmi" name="profil_resmi" />
											<div class="suslu_dosya_yukle_dugmesi_ic">
												<div class="input-append">
													<input  type="text" id="profil_resmi_gorunur" name="profil_resmi_gorunur" class="span1">
													<button type="button" class="btn">Dosya Seçiniz</button>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>

									<button class="btn btn-success pull-right" type="button" id="kaydet_dgmesi">Kaydet</button>
									<div class="clearfix"></div>
								</div>

							</form>

							<div class="clearfix"></div>
                                                        
                                                                  <br/>
                                                                  <br/>
                                                        <form id="sifre_degistirme_formu" name="sifre_degistirme_formu">
                                                             <header>
								<h1 class="sayfa_basligi">Şifre Değiştirme</h1>
                                                            </header> 
                                                            <div class="sol_form_bolumu span4" >
                                                                <label for="password">Eski Şifre</label>
                                                                <input type="password" placeholder="Eski Şifre" id="password" name="password" value="" />
                                                                <i class="icon-exclamation-sign" id="password_i" style=" display: none; padding: 0"></i>	
                                                                <label for="password_new">Yeni Şifre</label>
                                                                <input type="password" placeholder="Yeni Şifre" id="password_new" name="password_new" value="" />
                                                                <i class="icon-exclamation-sign" id="password_new_i" style="display: none;padding: 0"></i>	
                                                                <label for="password_new2">Yeni Şifre Tekrar</label>
                                                                <input type="password" placeholder="Yeni Şifre Tekrar" id="password_new2" name="password_new2" value="" />
                                                                <i class="icon-exclamation-sign" id="password_new2_i" style="display: none;padding: 0"></i>	
                                                                <div class="clearfix"></div>
                                                                <button  style="float:left" class="btn btn-success pull-right" type="button" id="change_password_button">Değiştir</button>
                                                                <i class="icon-exclamation-sign" id="button_i" style="display: none;padding: 0"></i>	
                                                                
                                                            </div>
                                                        </form>
						</div>
					</section>
					
				</div><!-- Profile Ayarları Tab  Sonu -->
				
				<!-- Profile arkadaş Tab -->
				<div class="tab-pane fade in" id="tab-arkadasB">
					<section class="satir ilk_satir uste_cikar">
						<div class="satir_ic">
							<h4 class="">Twitterdaki ve Facebook taki democratus kullanan arkadaşlarını bulup takip edebilirsin.</h4>
							
			    			
							
							<? 
							$c_facebook = new facebookClass;
							if($model->profile->fbID)
							{
								?>
								<div class="social_connect">
				    				<a class="facebook_login" href="javascript:;" onclick="facebook_friendFind(<?=$model->profile->fbID?>);">Facebook Arkadaşların</a>
				    			</div>
								<?
							}
							else 
							{
								?>
								<div class="social_connect">
				    				<a class="facebook_login" href="javascript:;" onclick="facebook_open_LoginWindow('<?=$c_facebook->get_loginUrl(0);?>');">Facebook Arkadaşlarım</a>
				    			</div>
								<?
							}
							
							$c_twitter = new twitterClass;
				
							if($c_twitter->user_tokens_check()>0)
							{
							?>
								<div class="social_connect">
			    					<a class="twitter_login" href="javascript:;" onclick="twitter_friendFind();">Twitter Arkadaşların</a>
			    				</div>
							<? 
							}
							else {
							?>
								<div class="social_connect">
			    					<a class="twitter_login" href="javascript:;" onclick="twitter_open_LoginWindow('<?=$c_twitter->get_loginUrl();?>');">Twitter Arkadaşlarım</a>
			    				</div>
							<? 	
							}
								
						?>
						</div>
						<div style="clear: both;"></div>
						<div id="socialListArea">
							
						</div>
					</section>
					
                </div><!-- Profile arkadaş Tab  Sonu -->
                <!-- Gizlilik Tab -->
                <div class="tab-pane fade in" id="tab-gizlilik">
                	<section class="satir ilk_satir uste_cikar">
						<div class="satir_ic">
	                        <form action="" method="post" id="myprivacyform">
                                                        
			                    <div class="" id="myprivacyResponse" style="display:none;"></div>
			                    <p></p>
			                    <table class="table table-bordered table-striped tblsecure" width="500">
			                        <thead>
			                            <tr>
		                                    <th></th>
			                                <th>Kimse</th>
			                                <th>Beni Takip Edenler</th>
			                                <th>Takip Ettiklerim</th>
			                                <th>Herkes</th>
			                            </tr>
			                        </thead>
			                            <?php /* ?>
			                            <tr>
			                                    <td>Profil</td>
			                                                    <td><label class="radio">
			                                        <input type="radio" name="showprofile" id="showprofile0" value="0" <?=@$profileChecked[0]?>></label></td>
			                                        <td><label class="radio">
			                                        <input type="radio" name="showprofile" id="showprofile1" value="1" <?=@$profileChecked[1]?>></label></td>
			                                        <td><label class="radio">
			                                        <input type="radio" name="showprofile" id="showprofile2" value="2" <?=@$profileChecked[2]?>></label></td>
			                                        <td><label class="radio">
			                                        <input type="radio" name="showprofile" id="showprofile5" value="5" <?=@$profileChecked[5]?>></label></td>
			                                            </tr>
			                                            <?php */ ?>
			                                <tr>
			                                    <td>Doğum Tarihim</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showbirth" id="showbirth0" value="0" <?=@$birthChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showbirth" id="showbirth1" value="1" <?=@$birthChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showbirth" id="showbirth2" value="2" <?=@$birthChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showbirth" id="showbirth5" value="5" <?=@$birthChecked[5]?>></label></td>
			                            </tr>
			                            <tr>
			                                    <td>Motto Cümlem</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showmotto" id="showmotto0" value="0" <?=@$mottoChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showmotto" id="showmotto1" value="1" <?=@$mottoChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showmotto" id="showmotto2" value="2" <?=@$mottoChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showmotto" id="showmotto5" value="5" <?=@$mottoChecked[5]?>></label></td>
			                            </tr>
			                            <tr>
			                                    <td>Paylaşımlarım</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showdies" id="showdies0" value="0" <?=@$showdiesChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showdies" id="showdies1" value="1" <?=@$showdiesChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showdies" id="showdies2" value="2" <?=@$showdiesChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showdies" id="showdies5" value="5" <?=@$showdiesChecked[5]?>></label></td>
			                            </tr>
			                            <?php /* ?>
			                            <tr>
			                                    <td nowrap>Yorum Görünürlüğü</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="dicomment" id="dicomment0" value="0" <?=@$dicommentChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="dicomment" id="dicomment1" value="1" <?=@$dicommentChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="dicomment" id="dicomment2" value="2" <?=@$dicommentChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="dicomment" id="dicomment5" value="5" <?=@$dicommentChecked[5]?>></label></td>
			                            </tr>   
			
			                            <tr>
			                                    <td>Memleket</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showhometown" id="showhometown0" value="0" <?=@$hometownChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showhometown" id="showhometown1" value="1" <?=@$hometownChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showhometown" id="showhometown2" value="2" <?=@$hometownChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showhometown" id="showhometown5" value="5" <?=@$hometownChecked[5]?>></label></td>
			                            </tr> 
			                                            <?php */ ?>             	
			                            <tr>
			                                    <td>Yaşadığın Ülke</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showcountry" id="showcountry0" value="0" <?=@$countryChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showcountry" id="showcountry1" value="1" <?=@$countryChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showcountry" id="showcountry2" value="2" <?=@$countryChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showcountry" id="showcountry5" value="5" <?=@$countryChecked[5]?>></label></td>
			                            </tr>            	
			                            <tr>
			                                    <td>Yaşadığın Şehir</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showcity" id="showcity0" value="0" <?=@$cityChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showcity" id="showcity1" value="1" <?=@$cityChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showcity" id="showcity2" value="2" <?=@$cityChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showcity" id="showcity5" value="5" <?=@$cityChecked[5]?>></label></td>
			                            </tr>          
			                                            <?php /* ?>
			                            <tr>
			                                    <td>Medeni durum</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showmarital" id="showmarital0" value="0" <?=@$materialChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showmarital" id="showmarital1" value="1" <?=@$materialChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showmarital" id="showmarital2" value="2" <?=@$materialChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showmarital" id="showmarital5" value="5" <?=@$materialChecked[5]?>></label></td>
			                            </tr>      	
			                            <?php */ ?>
			                            <tr>
			                                    <td>Eğitim Durumum</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showeducation" id="showeducation0" value="0" <?=@$educationChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showeducation" id="showeducation1" value="1" <?=@$educationChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showeducation" id="showeducation2" value="2" <?=@$educationChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showeducation" id="showeducation5" value="5" <?=@$educationChecked[5]?>></label></td>
			                            </tr>      	
			                            <tr>
			                                    <td>Hobilerim</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showhobbies" id="showhobbies0" value="0" <?=@$hobbiesChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showhobbies" id="showhobbies1" value="1" <?=@$hobbiesChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showhobbies" id="showhobbies2" value="2" <?=@$hobbiesChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showhobbies" id="showhobbies5" value="5" <?=@$hobbiesChecked[5]?>></label></td>
			                            </tr>  
			                            <tr>
			                                    <td>Bildiğim Diller</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showlanguages" id="showlanguages0" value="0" <?=@$langChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showlanguages" id="showlanguages1" value="1" <?=@$langChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showlanguages" id="showlanguages2" value="2" <?=@$langChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showlanguages" id="showlanguages5" value="5" <?=@$langChecked[5]?>></label></td>
			                            </tr>
			                            <?php /* ?>
			                            <tr>
			                                    <td>E-Mail</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showemail" id="showemail0" value="0" <?=@$emailChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showemail" id="showemail1" value="1" <?=@$emailChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showemail" id="showemail2" value="2" <?=@$emailChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showemail" id="showemail5" value="5" <?=@$emailChecked[5]?>></label></td>
			                            </tr>
			                            <?php */ ?>
			                            <tr>
			                                    <td>Takipçilerim</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showfollowers" id="showfollowers0" value="0" <?=@$followersChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showfollowers" id="showfollowers1" value="1" <?=@$followersChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showfollowers" id="showfollowers2" value="2" <?=@$followersChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showfollowers" id="showfollowers5" value="5" <?=@$followersChecked[5]?>></label></td>
			                            </tr>
			                            <tr>
			                                    <td>Takip Ettiklerim</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showfollowings" id="showfollowings0" value="0" <?=@$followingsChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showfollowings" id="showfollowings1" value="1" <?=@$followingsChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showfollowings" id="showfollowings2" value="2" <?=@$followingsChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showfollowings" id="showfollowings5" value="5" <?=@$followingsChecked[5]?>></label></td>
			                            </tr>
			                            <?php /* ?>
			                           <tr>
			                                    <td>Fotoğraflarım</td>
			                                <td><label class="radio">
			                                    <input type="radio" name="showphotos" id="showphotos0" value="0" <?=@$photosChecked[0]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showphotos" id="showphotos1" value="1" <?=@$photosChecked[1]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showphotos" id="showphotos2" value="2" <?=@$photosChecked[2]?>></label></td>
			                                    <td><label class="radio">
			                                    <input type="radio" name="showphotos" id="showphotos5" value="5" <?=@$photosChecked[5]?>></label></td>
			                            </tr>
			                            <?php */ ?>
			                        </table>
                                                       
                                <div style="clear: both; margin-bottom: 40px;"><button class="btn btn-success pull-right" type="button" id="myprivacysave">Kaydet</button></div>
                            </form>
                        </div>
                    </section>
                </div>
	             <div class="tab-pane fade" id="tab-engellemeler">
	
	        			<div class="roundedcontent shareidea">
							<h1>
								<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> 
								Bu Modül Test Aşamasındadır.
							</h1>
							<div class="clear"></div>
							<p>Kullanıcılarımıza kusursuz bir deneyim sunmak için bu modülü kapalı olarak test ediyoruz.</p>
							<br>
						</div>
	            </div>
	    </div>
			<?php
			//var_dump($model);
		}
		public function faceReturn()
		{
			global $model,$db;
			$model->mode=0;
			$c_facebook = new facebookClass;
			$uProfile = new stdClass;
			$uProfile->ID = $model->profileID;
			$uProfile->fbID = $c_facebook->get_facebookID();
			profile::update_profile($uProfile);
			echo "<script>";
			echo ("window.close();");
			echo "</script>";
			
		}
		public function twitterReturn()
		{
			global $model, $db;
			$model->mode=0;
			$c_twitter = new twitterClass;
			$c_twitter->twO = new TwitterOAuth($c_twitter->twitter_key, $c_twitter->twitter_secret,$_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
			$db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'twitter' AND userID = " . $db->quote($model->profileID) . "" );
			$oauth = null;
            if($db->loadObject($oauth)){
            	$oauth->user_oauth_token = $_SESSION['oauth_token'];
				$oauth->user_oauth_token_secret  = $_SESSION['oauth_token_secret'];
                $db->updateObject('oauth', $oauth, 'ID');
           	}
			else
			{
				$access_token =  $c_twitter->twO->getAccessToken($_GET['oauth_verifier']);
                // Save it in a session var
                $_SESSION['access_token'] = $access_token;
                // Let's get the user's info
                $user_info = $c_twitter->twO->get('account/verify_credentials');
				$name		= strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->name, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $uid        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->id, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $username   = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->screen_name, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $motto      = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->description, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                $location   = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->location, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                //$birth        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->location, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
        
                $oauth = new stdClass;
                $oauth->userID  		= $model->profileID;
                $oauth->oauth_provider  = 'twitter';
                $oauth->oauth_uid       = $uid;
                $oauth->username       	= $username;
                $oauth->oauth_token     = $_SESSION['oauth_token'];
                $oauth->oauth_token_secret	= $_SESSION['oauth_token_secret'];
                $oauth->user_oauth_token	=	$c_twitter->twO->token->key;
                $oauth->user_oauth_token_secret	=	$c_twitter->twO->token->secret;
                $oauth->ip        		= filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                $oauth->datetime  		= date('Y-m-d H:i:s');
                $oauth->status    		= 1;

                $db->insertObject('oauth', $oauth, 'ID');    
			}
			echo "<script>";
				echo ("window.close();");
			echo "</script>";
		}
        public function main_old(){
            global $model, $db;
            if($model->newDesign)
            $model->initTemplate('beta', 'my');
            else 
            $model->initTemplate('v2', 'my');
            
            if($model->userID<1) return;
            //ajax
            if($model->paths[1] == 'ajax'){
                $model->mode = 0;
                $method = (string) 'ajax_' . $model->paths[2];
                if(method_exists($this, $method )){
                    $this->$method();
                }
            } else { //other
            
                $model->addScript('var ajaxurl = "' . $model->pageurl . 'ajax/";');
                $model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
                $model->addScript(TEMPLATEURL."beta/js/ajaxfileupload.js","ajaxfileupload.js",1);
                $model->addScript(TEMPLATEURL."beta/js/jconfirmaction.jquery.js","jconfirmaction.jquery.js",1);
                $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1 );
                //$model->addScript(PLUGINURL . 'lib/upclick.js', 'upclick.js', 1 );
                //$model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1 );
                //$model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
                
                
                //$model->addScript(PLUGINURL . 'lib/boxy/boxy.js', 'boxy.js', 1 );
                //$model->addStyle(PLUGINURL . 'lib/boxy/boxy.css', 'boxy.css', 1 );
                //$model->addStyle('body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}' );
                
                //$model->addScript(PLUGINURL . 'lib/jquery/jquery.maskedinput.js', 'jquery.maskedinput.js', 1 );
                //$model->addScript(PLUGINURL . 'lib/ajaxupload/ajaxupload.js', 'ajaxupload.js', 1 );
                
                
                //$model->addScript(PLUGINURL . 'lib/jquery-ui/jquery-ui.js', 'jquery-ui.js', 1 );
                //$model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
                
                //$model->addScript(PLUGINURL . 'lib/flexigrid/flexigrid.js', 'flexigrid.js', 1 );
                //$model->addStyle(PLUGINURL . 'lib/flexigrid/flexigrid.css', 'flexigrid.css', 1 );
                
                //$model->addStyle(TEMPLATEURL . 'default/form.css', 'form.css', 1 );
                //$model->addScript(PLUGINURL . 'lib/tiny_mce/tiny_mce.js', 'tiny_mce.js', 1 );
                
                //$model->addScript(PLUGINURL . 'lib/fileuploader/fileuploader.js', 'fileuploader.js', 1 );
                //$model->addStyle(PLUGINURL . 'lib/fileuploader/fileuploader.css', 'fileuploader.css', 1 );
                
                //$model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1 );
                //$model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
                //$model->addScript($model->templateurl . 'static/javascript/jquery.js', 'static/javascript/jquery.js', 1 );
                //$model->addScript($model->templateurl . 'static/javascript/selectbox.js', 'static/javascript/selectbox.js', 1 );
                //$model->addStyle($model->templateurl . 'static/style/selectbox.css', 'static/style/selectbox.css', 1 );  
                $model->addScript($model->pluginurl . 'my.js', 'my.js', 1);        
            	$model->addScript("paths=".json_encode($model->paths));
            
                $method = (string) 'my_' . $model->paths[1];
                if(method_exists($this, $method )){
                    $this->$method();
                } else {
                    $this->my_profile();
                }    
            }
        }
        
        public function my_photos(){
            global $model, $db;
            $model->addScript(PLUGINURL . 'lib/ajaxupload/ajaxupload.js', 'ajaxupload.js', 1 );
            $model->addScript($model->pluginurl . 'my.js', 'my.js', 1 );
            $model->addScript($model->pluginurl . 'photo.js', 'photo.js', 1 );
            $photolimitreached = $this->photolimitreached();
            //var_dump( $photolimitreached );
                
            
            
?>
    <div id="myphotoscontainer">
    
      
      
      <div id="myphotos">
      
<?php


      if(!$photolimitreached){
?>      
       
      <div class="myphoto box" style="padding: 10px; width: 180px; float: left; height: 160px; margin-right: 5px; text-align: center;">
        
      
        <hr />
      
        <form enctype="multipart/form-data" action="/my/imageupload" method="POST">
        Yüklemek istediğiniz fotografı seçiniz:
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
        <input name="uploadfile" type="file" /><br />
        <input type="submit" value="Yükle" />
        </form>
<?php
    if(0){
?>        
        
          <p>&nbsp;</p>
      <p>
        <a href="javascript:;" id="myphotoupload" style="text-decoration: underline; padding: 50px 10px;">Fotograf Yükle</a> 
      </p>
      
<?php
    }
?>      

      </div> 
<?php
    } else {
        
?>      
       
      <div class="myphoto box" style="padding: 10px; width: 180px; float: left; height: 160px; margin-right: 5px; text-align: center;">
      <p>Fotograf yükleyebilmek için eski fotograflarınızdan bazılarını silmelisiniz.</p> 
      </div>                                                                           
<?php        
        
    }
    $db->setQuery("SELECT * FROM profileimage AS pi WHERE pi.profileID = " . intval( $model->profileID ) . " AND pi.status>0 ORDER BY pi.ID DESC" );
    $rows = $db->loadObjectList();
    
    if(count($rows)){
        foreach($rows as $row){
?>
        <div class="myphoto box" style="padding: 10px; width: 180px; float: left; height: 160px;margin-right: 5px;">
          <img src="<?=$model->getImage($row->imagepath, 160, 120, 'cutout')?>" alt="" width="160" height="120" class="image" style="background-color: white; padding: 5px; border: 1px solid #CCC;" />
          <p><a href="javascript:;" class="makeprofilephoto" rel="<?=$row->ID?>">profil fotografım olsun</a></p>
          <p><a href="javascript:;" class="removemyimage" rel="<?=$row->ID?>">sil</a></p>
        </div>
<?php
        }
    }
?>
      </div>
       
    </div>
<?php
        }
        
        
        
        
        public function my_account(){
            global $model, $db;
            
            //$model->addScript(PLUGINURL . 'lib/ajaxupload/ajaxupload.js', 'ajaxupload.js', 1 );
            $model->addScript($model->pluginurl . 'my.js', 'my.js', 1 );
            
            $model->title = 'Hesap Ayarlarım | Democratus.com';
?>


<div class="box" id="profile_information">
<div class="title">Email adresiniz</div>
<div class="line_center"></div>
<form action="/my/accountsave/" method="post" class="form" id="myemailchangeform" onsubmit="return false;" >
<p><label>E-Mail adresiniz</label>
<input type="text" name="email" value="<?=$model->user->email?>" /><span class="required">*</span>
</p>

<p><label>Bildirim mailleri</label>
<?php echo $model->array_to_select(array(1=>'Gelsin', 0=>'Gelmesin!'), 'emailperms', intval( $model->profile->emailperms ), false );?>
</p>



<p><label>&nbsp;</label>
<input type="submit" value="Değiştir" id="myemailchangebutton" /><span class="message" id="myemailchangemessage">&nbsp;</span>
</p>






</form>





<div class="title">Hesap Şifreniz</div>
<div class="line_center"></div>
<form action="/my/accountsave/" method="post" class="form" id="mypasswordchangeform" onsubmit="return false;" >
<p><label>Mevcut şifreniz</label>
<input type="password" name="oldpass" value="" /><span class="required">*</span>
</p>

<p><label>Yeni şifreniz</label>
<input type="password" name="newpass" value="" /><span class="required">*</span>
</p>

<p><label>Yeni şifreniz tekrar</label>
<input type="password" name="newpass2" value="" /><span class="required">*</span>
</p>


<p><label>&nbsp;</label>
<input type="submit" value="Değiştir" id="mypasswordchangebutton" /><span class="message" id="mypasswordchangemessage">&nbsp;</span>
</p>


</form>

<div class="title">Sosyal Medya </div>
<div class="line_center"></div>
<p><label>Demoratusta Yaptığınız paylaşımları Facebookta da paylaşın.</label></p>
<br/><br/>
<?php 
	if($model->profile->facebookPaylasizin==1)
	{
		echo '<p><a href="javascript:;" onclick="fbPaylasimTogle();" >Paylaşım onayını Kaldırın.</a></p>';
	}
	else
	{
?>
<p>
<?php 
	$fbn= new facebooknew();
	$response=$fbn->yazmaizniVarmi();
	if($_GET){
		if($model->profile->fbID=="")
		{
			$pro = new stdClass();
			$pro->fbID=$db->quote($fbn->get_fbID());
			$pro->ID=$model->profileID;
            $db->updateObject("profile", $pro, "ID");
            echo "<script>location.href=location.href;</script>";
		}
	}
	if($response["durum"]=="login")
	{ 	?>
		<a href="<?=$response["loginUrl"]?>">Facebook'ta Oturum Açın.</a>
	<?php }
	else if($response["durum"]=="izinal"){?>
		<a href="<?=$response["izinUrl"]?>">Facebook Uygulamasına İzin verin.</a>
	<? }
	else if($response["durum"]=="izinVar"){
		?>
		<input type="hidden" id="izin" name="izin" value="<?=$model->profile->facebookPaylasizin?>" /> 
		<a href="javascript:;" onclick="fbPaylasimTogle();" >Paylaşım onayı verin.</a>
	<? } ?>
</p>
<?php }?>
<div style="clear:both;"></div>
<div class="line_center"></div>
<p><label>Demoratusta Yaptığınız paylaşımları Twitter'da paylaşın.</label></p>
<br/><br/>
<?php 
	if($model->profile->twitterPaylasizin==1)
	{
		echo '<p><a href="javascript:;" onclick="twPaylasimTogle();" >Paylaşım onayını Kaldırın.</a></p>';
	}
	else
	{
?>
<p>
<?php 
	$tw= new twitter();
	$response=$tw->user_tokens_check();
	if($response==0)
	{ 	?>
		<a href="/oauth/twitterPaylasimOnayi">Twitter uygulamasına izin veriniz.</a>
	<?php }
	else if($response==1){?>
		<a href="/oauth/twitterPaylasimOnayi">Twitter uygulamasına izin veriniz.</a>
	<? }
	?>
</p>
<?php }?>
<div style="clear:both;"></div>

</div>

<?php            
            
            
            
            
        }
        
        
        
        
        
        
        public function my_profile(){
            global $model, $db;
            
            //$model->addScript(PLUGINURL . 'lib/ajaxupload/ajaxupload.js', 'ajaxupload.js', 1 );
            $model->addScript($model->pluginurl . 'my.js', 'my.js', 1 );
			if($model->newDesign)
			{//yeni tasarım başı
			$model->addScript('
            $(function(){
                $("textarea#motto").keyup(function(){
                    $(this).parent().find(".character .number").html(200 - $(this).val().length);
                });

                $("textarea#motto").parent().find(".character .number").html(200 - $("textarea#motto").val().length);
            });
            ');
            
            $model->title = 'Profil Ayarlarım | Democratus.com';
            $profileID = $model->profileID;
            $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID=".$db->quote($profileID));
            $profile = null;
            if($db->loadObject($profile)){
                
            } else {
                $profile = new stdClass;
                
            }
			?>
				
			<?php 
			//var_dump($model->progile->photo);
			?>
		<ul id="tab" class="nav nav-tabs">
			<li class="active">
				<button class="tabbtn" href="#tab-profil" rel="profil" data-toggle="tab">Profil Ayarları</button>
			</li>
            <li>
            	<button class="tabbtn" href="#tab-gizlilik" rel="gizlilik" data-toggle="tab">Gizlilik Ayarları</button>
            </li>
			<li>
				<button class="tabbtn" href="#tab-hesap" rel="hesap" data-toggle="tab">Hesap Ayarları</button>
			</li>
			<li>
				<button class="tabbtn last" href="#tab-engellemeler" rel="engellemeler" data-toggle="tab">Engellemeler</button>
			</li>
           
          </ul>
          
		<?php
		if($model->profile->permalink=="") 
		{ 
		?>
        <div  class="roundedcontent" style="">
        	<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Benzersiz Kullanıcı Adınızı Alın
				
			</h1>
			<p>
				Democratus kullanıcılarının sistem içerisinde kendi profilleri için tanımlayabilecekleri eşsiz adrese "Benzersiz Kullanıcı Adı" diyoruz.
				Bu adres ile profilinize özel bir link kazandırıyorsunuz. Sistem üzerindeki tüm işlemlerinizde zamanla bu kullanıcı adınız ile var olacaksınız.
				Benzersiz kullanıcı adınızı bir kez seçebilirsiniz. Bu nedenle işlemi sonlandırmadan önce tekrar kontrol etmenizi öneriyoruz. Benzersiz kullanıcı adınız en az 6 karakterden
				oluşmalıdır, boşluk ve özel karakter bulundurmamalıdır; harf ve rakalamlardan oluşmalıdır.
			</p>
			<input type="text" name="benzersizka" id="benzersizka" placeholder="Benzersiz Kullanıcı Adı" style="margin:3px;" maxlength="25"/>
			<button class="btn btn-vekil" id="specialUrlCheck" type="button" disabled="" style="margin-right: 3px;">Kontrol Et</button>
			<button class="btn btn-gonder" id="specialUrlSave" type="button" disabled="" style="float: none; ">Tanımla</button>
			<p></p>
			<span id="uyari"></span>
		</div>
		<p></p>
		<?php } ?>
        <div id="myTabContent" class="tabuser-content"style="margin-top:0px;">
       
            <div class="tab-pane fade in active" id="tab-profil">
           	
            <form id="myprofileform" target="" method="POST" style="margin:0;">
           
			<div class="roundedcontent shareidea">
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Profil Bilgileri 
				<span><button class="btn btn-vekil" id="myprofilesave" type="button">KAYDET</button></span>
			</h1>
			<div class="" id="myprofileResponse" style="display:none;"></div>
            <div class="form-left">
            <p>İstenen alanları doldurun.</p>
            <input type="text" class="input-block-level" name="name" placeholder="İsim  Soyisim" id="name" value="<?=$profile->name?>" />
            <?php
    //var_dump($profile->birth);
    $birthdate = strtotime( $profile->birth );
	    if($profile->birth==null){
	        $day = null;
	        $month = null;
	        $year = null;
	    } else {
	        
	        $day = intval( date('d', $birthdate) );
	        $month = intval( date('m', $birthdate) );
	        $year = intval( date('Y', $birthdate) );
	    }
	 // echo $day . ' ' . $l['months'][$month];
	?>
	<style>
		#birthday{
			width:70px;
		}	
		#birthmonth{
			width:70px;
		}
		#birth{
			width:90px;
		}
	</style>
	<?php echo $model->number_to_select('birthday',1, 31, $day, 'asc')?>
	<?php echo $model->number_to_select('birthmonth',1, 12, $month, 'asc')?>
	<?php echo $model->number_to_select('birth',1940, 1995, $year)?>
		<?php 
           /* <input type="text" class="input-block-level" placeholder="Soyisim" id="surname" value="<?=$profile->surname?>" />
            * 
            */
         ?>   
          <!-- drop downloar bu şekilde konfigre edilecek javascriptleri yazılmalı
          <div class="btn-group">
          <button class="btn btnformlong">Ülke Seçin</button>
          <button data-toggle="dropdown" class="btn dropdown-toggle drpformlong">
          	<span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li class="scrollspy">
				<ul>
					<li><a href="#">Hindistan</a></li>
					<li><a href="#">İran</a></li>
					<li><a href="#">İngiltere</a></li>
					<li><a href="#">Fransa</a></li>
					<li><a href="#">Almanya</a></li>
				</ul>
			</li>
		 	
          </ul>
         </div>
          -->
        <style>
			select{
			width:100%;
			}
		</style>		
		<?php echo $this->country_to_select('countryID', $profile->countryID)?>
        <?php echo $this->city_to_select('cityID', $profile->countryID, $profile->cityID)?>
        <?php echo $this->education_to_select('education', $profile->education)?>
        <?php echo $this->sex_to_select('sex', $profile->sex)?>
		<select name="language" id="language">
		    <option value="">&nbsp;</option>
		    <option value="en" <?=$profile->language=='en'?'selected="selected"':''?>>English</option>
		    <option value="tr" <?=$profile->language=='tr'?'selected="selected"':''?>>Türkçe</option>
		    <option value="ar" <?=$profile->language=='ar'?'selected="selected"':''?>>Arabic</option>
		    <option value="de" <?=$profile->language=='de'?'selected="selected"':''?>>Deutch</option>
		    <option value="ru" <?=$profile->language=='ru'?'selected="selected"':''?>>Russian</option>
		</select>


         <p></p>
            </div>
            <div class="form-right">
            <p>Profil fotografı yükleyin.</p>
            <form name="form" action="" method="POST" enctype="multipart/form-data">
            	<input class="input-file" id="imageFileinput" name="imageFileinput" title="YÜKLE" type="file" style="display: none;"/>
            </form>
            <style>
	            #imageSliderContent
	            {
					width:215px; height:150px; position: relative; overflow: hidden; z-index:55;
	            }
	            #imageSliderUl
	            {
					position: absolute; width: 2000px; z-index:5; list-style: none; margin: 0;
	            	 
	            }
	            #imageSliderUl li
	            {
					float: left; width: 215px;
	            }
            </style>

            <div class="roundedcontentwhite" style="width:215px;"> 
            <div id="imageSliderContent" >
	            <ul id="imageSliderUl" >
	            	<?php /*
	                $db->setQuery("SELECT * FROM profileimage AS pi WHERE pi.profileID = " . intval( $model->profileID ) . " AND pi.status>0 ORDER BY pi.ID DESC" );
				    $rows = $db->loadObjectList();
				    
				    if(count($rows)){
				    	$i=0;
				        foreach($rows as $row){
					?>
						<li id="imageListLi-<?=$row->ID?>">
						<?php if($i==1){?>
		            	<div style="width:30px; height:120px; float: left; cursor:pointer;" onclick="imageSliderPrev();">
		            		<img src="/t/beta/img/album_left.png" style="margin-top:17px;"/>
		            	</div>
		            	<?php }else{?>
		            	<div style="width:30px; height:120px; float: left;" ></div>
		            	<?php }?>
		            	<div style="float: left; width: 155px;">
		            		<div style="margin: 0 auto 0px; width: 85px; text-align: right;">
		            			<?php 
		            			if($row->imagepath!=$model->profile->image)
		            			{ ?>
		            			<a href="javascript:;" class="makeProfilPhoto" id="makeProfilPhoto-<?=$row->ID?>" rel="<?=$row->ID?>">+</a>
		            			<?php 	
		            			}
		            			?>
		            			<a href="javascript:;" class="removeImg" id="removeImg-<?=$row->ID?>" rel="<?=$row->ID?>">X</a>
			            	
			            	</div>
			            	<div class="inputpics"  style="margin-top:0; cursor:pointer; background-image:url('<?=$model->getImage($row->imagepath, 85, 85, 'cutout')?>'); ">
					            
				            </div>
			            </div>
			           <div style="width:30px; height:120px; float: right; cursor:pointer;" onclick="imageSliderNext();">
			           	<img src="/t/beta/img/album_right.png" style="margin-top:17px;"/>
			           </div>
			           <div style="clear:both;"></div>
		            	</li>
		            	<!-- 
				        <div class="myphoto box" style="padding: 10px; width: 180px; float: left; height: 160px;margin-right: 5px;">
				          <img src="<?=$model->getImage($row->imagepath, 85, 85, 'cutout')?>" alt="" width="160" height="120" class="image" style="background-color: white; padding: 5px; border: 1px solid #CCC;" />
				          <p><a href="javascript:;" class="makeprofilephoto" rel="<?=$row->ID?>">profil fotografım olsun</a></p>
				          <p><a href="javascript:;" class="removemyimage" rel="<?=$row->ID?>">sil</a></p>
				        </div> 
				        -->
					<?php
						$i=1;
				        }
				    }
					*/
				    ?>
		    		<script>
		    			$('#imageFileinput').live("click",function(){
		    				$("#imageUploadLi").hide();
		    			});
		    		</script>
	            	<li id="imageUploadLi">
		            	<div style="width:30px; height:120px; float: left;" onclick="">
		            		<!--<img src="/t/beta/img/album_left.png" style="margin-top:17px;"/>-->
		            	</div>
		            	<div style="float: left; width: 155px;">
		            		<div style="margin: 0 auto 0px; width: 85px; text-align: right;">
			            		&nbsp;
			            	</div>
			            	<div class="inputpics"  style="cursor:pointer; margin-top:0;" onclick="$('#imageFileinput').show().focus().click().hide();">
					           <span style="margin-top:45px;">YÜKLE</span>
				            </div>
			           	</div>
			           	<div style="clear:both;"></div> 
			           	
			            <p style="text-align:center;"><i>Maksimum 1000kb, jpg, png ya da gif</i></p>
			           
	            	</li>
	            	<li>
		            	<div style="width:30px; height:120px; float: left;" onclick="">
		            		<!--<img src="/t/beta/img/album_left.png" style="margin-top:17px;"/>-->
		            	</div>
		            	<div style="float: left; width: 155px;">
		            		<div style="margin: 0 auto 0px; width: 85px; text-align: right;">
			            		&nbsp;
			            	</div>
			            	<div class="inputpics"  style="cursor:pointer; margin-top:0;" onclick="$('#myprofilesave').focus().click(); $('#imageUploadLi').show();">
					           <span style="margin-top:45px;">Kaydet</span>
				            </div>
			           	</div>
			           	<div style="clear:both;"></div> 
			           	
			            <p style="text-align:center;"><i>Resmi Yüklemek İçin Kaydete Basınız</i></p>
			           
	            	</li>
	            </ul>
            </div>
            
            
            </div>
            <p></p>
            <input type="text" class="input-block-level" placeholder="Hobiler" name="hobbies" id="hobbies" value="<?=$profile->hobbies?>" />
            <input type="text" class="input-block-level" placeholder="Bildiği Diller" name="languages" id="languages" value="<?=$profile->languages?>" />
           
            
            </div>
            <textarea class="input-xlarge" placeholder="Kendinizi Anlatın" name="motto" id="motto" maxlength="200" rows="3"><?=$profile->motto?></textarea>
            
            <!-- <button class="btn btn-gonder">GÖNDER</button> -->
            
            <input type="hidden" name="profileID" value="<?=$profileID?>" />
		</div>
		</form>
			  

			</div>
            <div class="tab-pane fade" id="tab-gizlilik">
            <div class="roundedcontent shareidea">
        	<form action="" method="post" id="myprivacyform">
			<h1 style="text-align:left; font-size:16px;"><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Gizlilik Ayarı
				<span><button class="btn btn-vekil" type="button" id="myprivacysave">KAYDET</button></span>
			</h1>
			<div class="" id="myprivacyResponse" style="display:none;"></div>
            <p></p>
            <?php 
	            $profileChecked[$profile->showprofile]=' checked="ture" ';
	            $birthChecked[$profile->showbirth]=' checked="ture" ';
	            $mottoChecked[$profile->showmotto]=' checked="ture" ';
	            $showdiesChecked[$profile->showdies]=' checked="ture" ';
	            $dicommentChecked[$profile->dicomment]=' checked="ture" ';
	            $hometownChecked[$profile->showhometown]=' checked="ture" ';
	            $countryChecked[$profile->showcountry]=' checked="ture" ';
	            $cityChecked[$profile->showcity]=' checked="ture" ';
	            $materialChecked[$profile->showmarital]=' checked="ture" ';
	            $educationChecked[$profile->showeducation]=' checked="ture" ';
	            $hobbiesChecked[$profile->showhobbies]=' checked="ture" ';
	           
	            $langChecked[$profile->showlanguages]=' checked="ture" ';
	            $emailChecked[$profile->showemail]=' checked="ture" ';
	            $followersChecked[$profile->showfollowers]=' checked="ture" ';
	            $followingsChecked[$profile->showfollowings]=' checked="ture" ';
	            $photosChecked[$profile->showphotos]=' checked="ture" ';
	           	
            ?>
            <table class="table table-bordered table-striped tblsecure" width="500">
            <thead>
            	<tr>
                	<th></th>
                    <th>Kimse</th>
                    <th>Beni Takip Edenler</th>
                    <th>Takip Ettiklerim</th>
                    <th>Herkes</th>
                </tr>
            </thead>
            	<?php /* ?>
            	<tr>
                	<td>Profil</td>
					<td><label class="radio">
		            <input type="radio" name="showprofile" id="showprofile0" value="0" <?=@$profileChecked[0]?>></label></td>
		            <td><label class="radio">
		            <input type="radio" name="showprofile" id="showprofile1" value="1" <?=@$profileChecked[1]?>></label></td>
		            <td><label class="radio">
		            <input type="radio" name="showprofile" id="showprofile2" value="2" <?=@$profileChecked[2]?>></label></td>
		            <td><label class="radio">
		            <input type="radio" name="showprofile" id="showprofile5" value="5" <?=@$profileChecked[5]?>></label></td>
				</tr>
				<?php */ ?>
	            <tr>
                	<td>Doğum Tarihim</td>
                    <td><label class="radio">
	                <input type="radio" name="showbirth" id="showbirth0" value="0" <?=@$birthChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showbirth" id="showbirth1" value="1" <?=@$birthChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showbirth" id="showbirth2" value="2" <?=@$birthChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showbirth" id="showbirth5" value="5" <?=@$birthChecked[5]?>></label></td>
                </tr>
            	<tr>
                	<td>Motto Cümlem</td>
                    <td><label class="radio">
	                <input type="radio" name="showmotto" id="showmotto0" value="0" <?=@$mottoChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showmotto" id="showmotto1" value="1" <?=@$mottoChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showmotto" id="showmotto2" value="2" <?=@$mottoChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showmotto" id="showmotto5" value="5" <?=@$mottoChecked[5]?>></label></td>
                </tr>
            	<tr>
                	<td>Paylaşımlarım</td>
                    <td><label class="radio">
	                <input type="radio" name="showdies" id="showdies0" value="0" <?=@$showdiesChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showdies" id="showdies1" value="1" <?=@$showdiesChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showdies" id="showdies2" value="2" <?=@$showdiesChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showdies" id="showdies5" value="5" <?=@$showdiesChecked[5]?>></label></td>
                </tr>
                <?php /* ?>
            	<tr>
                	<td nowrap>Yorum Görünürlüğü</td>
                    <td><label class="radio">
	                <input type="radio" name="dicomment" id="dicomment0" value="0" <?=@$dicommentChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="dicomment" id="dicomment1" value="1" <?=@$dicommentChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="dicomment" id="dicomment2" value="2" <?=@$dicommentChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="dicomment" id="dicomment5" value="5" <?=@$dicommentChecked[5]?>></label></td>
                </tr>   
                       	
                <tr>
                	<td>Memleket</td>
                    <td><label class="radio">
	                <input type="radio" name="showhometown" id="showhometown0" value="0" <?=@$hometownChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showhometown" id="showhometown1" value="1" <?=@$hometownChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showhometown" id="showhometown2" value="2" <?=@$hometownChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showhometown" id="showhometown5" value="5" <?=@$hometownChecked[5]?>></label></td>
                </tr> 
				<?php */ ?>             	
                <tr>
                	<td>Yaşadığın Ülke</td>
                    <td><label class="radio">
                	<input type="radio" name="showcountry" id="showcountry0" value="0" <?=@$countryChecked[0]?>></label></td>
              		<td><label class="radio">
	                <input type="radio" name="showcountry" id="showcountry1" value="1" <?=@$countryChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showcountry" id="showcountry2" value="2" <?=@$countryChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showcountry" id="showcountry5" value="5" <?=@$countryChecked[5]?>></label></td>
                </tr>            	
                <tr>
                	<td>Yaşadığın Şehir</td>
                    <td><label class="radio">
                	<input type="radio" name="showcity" id="showcity0" value="0" <?=@$cityChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showcity" id="showcity1" value="1" <?=@$cityChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showcity" id="showcity2" value="2" <?=@$cityChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showcity" id="showcity5" value="5" <?=@$cityChecked[5]?>></label></td>
                </tr>          
				<?php /* ?>
                <tr>
                	<td>Medeni durum</td>
                    <td><label class="radio">
                	<input type="radio" name="showmarital" id="showmarital0" value="0" <?=@$materialChecked[0]?>></label></td>
              		<td><label class="radio">
	                <input type="radio" name="showmarital" id="showmarital1" value="1" <?=@$materialChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showmarital" id="showmarital2" value="2" <?=@$materialChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showmarital" id="showmarital5" value="5" <?=@$materialChecked[5]?>></label></td>
                </tr>      	
                <?php */ ?>
                <tr>
                	<td>Eğitim Durumum</td>
                    <td><label class="radio">
                	<input type="radio" name="showeducation" id="showeducation0" value="0" <?=@$educationChecked[0]?>></label></td>
              		<td><label class="radio">
                	<input type="radio" name="showeducation" id="showeducation1" value="1" <?=@$educationChecked[1]?>></label></td>
                	<td><label class="radio">
                	<input type="radio" name="showeducation" id="showeducation2" value="2" <?=@$educationChecked[2]?>></label></td>
                	<td><label class="radio">
                	<input type="radio" name="showeducation" id="showeducation5" value="5" <?=@$educationChecked[5]?>></label></td>
                </tr>      	
                <tr>
                	<td>Hobilerim</td>
                    <td><label class="radio">
                	<input type="radio" name="showhobbies" id="showhobbies0" value="0" <?=@$hobbiesChecked[0]?>></label></td>
              		<td><label class="radio">
	                <input type="radio" name="showhobbies" id="showhobbies1" value="1" <?=@$hobbiesChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showhobbies" id="showhobbies2" value="2" <?=@$hobbiesChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showhobbies" id="showhobbies5" value="5" <?=@$hobbiesChecked[5]?>></label></td>
                </tr>  
                <tr>
                	<td>Bildiğim Diller</td>
                    <td><label class="radio">
                	<input type="radio" name="showlanguages" id="showlanguages0" value="0" <?=@$langChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showlanguages" id="showlanguages1" value="1" <?=@$langChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showlanguages" id="showlanguages2" value="2" <?=@$langChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showlanguages" id="showlanguages5" value="5" <?=@$langChecked[5]?>></label></td>
                </tr>
                <?php /* ?>
                <tr>
                	<td>E-Mail</td>
                    <td><label class="radio">
                	<input type="radio" name="showemail" id="showemail0" value="0" <?=@$emailChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showemail" id="showemail1" value="1" <?=@$emailChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showemail" id="showemail2" value="2" <?=@$emailChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showemail" id="showemail5" value="5" <?=@$emailChecked[5]?>></label></td>
                </tr>
                <?php */ ?>
                <tr>
                	<td>Takipçilerim</td>
                    <td><label class="radio">
                	<input type="radio" name="showfollowers" id="showfollowers0" value="0" <?=@$followersChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showfollowers" id="showfollowers1" value="1" <?=@$followersChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showfollowers" id="showfollowers2" value="2" <?=@$followersChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showfollowers" id="showfollowers5" value="5" <?=@$followersChecked[5]?>></label></td>
                </tr>
                <tr>
                	<td>Takip Ettiklerim</td>
                    <td><label class="radio">
                	<input type="radio" name="showfollowings" id="showfollowings0" value="0" <?=@$followingsChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showfollowings" id="showfollowings1" value="1" <?=@$followingsChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showfollowings" id="showfollowings2" value="2" <?=@$followingsChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showfollowings" id="showfollowings5" value="5" <?=@$followingsChecked[5]?>></label></td>
                </tr>
                <?php /* ?>
               <tr>
                	<td>Fotoğraflarım</td>
                    <td><label class="radio">
                	<input type="radio" name="showphotos" id="showphotos0" value="0" <?=@$photosChecked[0]?>></label></td>
	              	<td><label class="radio">
	                <input type="radio" name="showphotos" id="showphotos1" value="1" <?=@$photosChecked[1]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showphotos" id="showphotos2" value="2" <?=@$photosChecked[2]?>></label></td>
	                <td><label class="radio">
	                <input type="radio" name="showphotos" id="showphotos5" value="5" <?=@$photosChecked[5]?>></label></td>
                </tr>
                <?php */ ?>
            </table>
			
			
			<input type="hidden" name="profileID" value="<?=$profileID?>" />
		</form>
		</div>
		</div>
     
            
            <div class="tab-pane fade" id="tab-hesap">
            
			<div class="roundedcontent shareidea">
			<form id="myemailchangeform" class="form" onsubmit="return false;" method="post" action="/my/accountsave/">
			<h1><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Hesap Ayarları 
			<span><button class="btn btn-vekil" type="button" id="myemailchangebutton">KAYDET</button></span>
			</h1>
			 <p>İstenen alanları doldurun.</p>
             <div style="display:block; position:relative; height:50px;">
             	<input type="text" class="input-append" placeholder="E Posta Adresi" name="email" id="email" style="float:left;" value="<?=$model->user->email?>"> 
             	<label class="checkbox formlabels">Bildirimleri e-posta yolu ile gönder<input type="checkbox" value="1" align="right" name="emailperms" <?php echo $model->profile->emailperms==1? 'checked="true"':''; ?>></label>
             </div>
              </form>
              
              <form id="mypasswordchangeform" class="form" onsubmit="return false;" method="post" action="/my/accountsave/">
              <h1>Şifre Değişikliği
              <span><button class="btn btn-vekil" type="button" id="mypasswordchangebutton">KAYDET</button></span>
              </h1>

              <div style="float:left; width:245px; height:120px; display:block">
              	<form action="/my/accountsave/" method="post" class="form" id="mypasswordchangeform" onsubmit="return false;" >
		              <input type="password" class="input-append" placeholder="Mevcut Şifreniz" id="oldpass" name="oldpass" />
		              <p></p>
		              <input type="password" class="input-append" placeholder="Yeni Şifreniz" id="newpass" name="newpass" />
		              <p></p>
		              <input type="password" class="input-append" placeholder="Yeni Şifrenizi tekrar girin" id="newpass2" name="newpass2" />
	              </form>
              </div>
              <div style="float:left; width:250px; height:120px; display:block;padding-top:30px; text-align:center">
              	<br/><i>Şifreniz en az 6 karakter olmalıdır. </i>
              </div>
			
			</form>
 				
              <h1>Sosyal Paylaşımlar</h1>
              	<div style="float:left; width:250px; height:60px; display:block">
              	<label class="checkbox formlabelsshort">
              		
              		<div class="facebook" style="float:left;">
              			<span class="fb-user">
              				<a id="facebookProfileLink" href="" target="_blank">
              					<img style="width: 30px;" id="facebookProfilePicture" src="">
              					<em id="facebookProfileName"></em>
              				</a>
              			</span>
              		</div>
              		<div style="clear:both;"></div>
              	<?php 
					
					if($model->profile->fbID!="0") 
					{
						
						?>
						<script>
							var fbID=<?=$model->profile->fbID?>;
							$(document).ready(function(){
								$.ajax({
									url:"https://graph.facebook.com/"+fbID,
									type:"GET",
									dataType:"json",
									success:function(data){
										//console.log(data);
										$("#facebookProfilePicture").attr("src","https://graph.facebook.com/"+data.username+"/picture");
										$("#facebookProfileName").html(data.name);
										$("#facebookProfileLink").attr("href","https://www.facebook.com/"+data.username);
									}
								});
							});
						</script>
						
						<?
						$che="";
						//var_dump($model->profile->facebookPaylasizin==1);
						if($model->profile->facebookPaylasizin=="1")
							$che='checked="true"';
						?>
							Ses'lerimi Facebook'ta paylaş<input type="checkbox" value="option1" align="right" <?=$che?> onchange="fbPaylasimTogle();">
						<? 
					}
					else
					{
						$fbn= new facebooknew();
						$response=$fbn->yazmaizniVarmi();
						if($_GET){
							if($model->profile->fbID=="")
							{
								$pro = new stdClass();
								$pro->fbID=$db->quote($fbn->get_fbID());
								$pro->ID=$model->profileID;
					            $db->updateObject("profile", $pro, "ID");
					            echo "<script>location.href=location.href;</script>";
							}
						}
						
						if($response["durum"]=="login")
						{ 	?>
							<a href="<?=$response["loginUrl"]?>"><img src="/t/beta/img/facebook_icon.png"/> Facebook'ta Oturum Açın..</a>
						<?php }
						else if($response["durum"]=="izinal"){?>
							<a href="<?=$response["izinUrl"]?>"><img src="/t/beta/img/facebook_icon.png"/> Facebook Uygulamasına İzin verin.</a>
						<? }
						else if($response["durum"]=="izinVar"){
							$che="";
							if($model->profile->facebookPaylasizin==1)
							$che='checked="true"';
							?>
							Ses'lerimi Facebook'ta paylaş<input type="checkbox" value="option1" align="right" <?=$che?> onchange="fbPaylasimTogle();">
						<? } 
						
					}//son else 
              	?>
              	</label>
              
              </div>
              <div style="float:left; width:250px; height:60px; display:block; text-align:left">
              	<label class="checkbox formlabelsshort">
              	<?php 
              	//if($model->profile->)
              	$che="";
				if($model->profile->twitterPaylasizin=="1")
					$che='checked="true"';
				$tw= new twitter();
				$response=$tw->user_tokens_check();
				//var_dump($response);
				if($response=="0"){?>
					<a href="/oauth/twitterPaylasimOnayi">
						<img src="/t/beta/img/twitter_mini-icon.jpg"/> Twitter uygulamasına izin veriniz.</a>
				<? }else { 
					$twitterP=$tw->user_profile_get();	
				?>
					<div class="twitter" style="float:left;">
              			<span class="tw-user">
              				<a id="twitterProfileLink" href="https://twitter.com/<?=$twitterP->screen_name?>" target="_blank">
              					<img style="width: 30px;" id="twitterProfilePicture" src="<?=$twitterP->profile_image_url?>">
              					<em id="twitterProfileName"><?=$twitterP->name?></em>
              				</a>
              			</span>
              		</div>
              		<div style="clear: both;"></div>
					Ses'lerimi Twitter'da paylaş<input type="checkbox" value="option1" align="right" <?=$che?> onchange="twPaylasimTogle();">
				<? } ?>
              		
              	</label>
              </div>
              <!--
              <h1>Hesap Ayarları</h1>
              <div style="float:left; width:250px; height:60px; display:block">
              <label class="checkbox formlabelsshort">Hesabımı geçici olarak dondur<input type="checkbox" value="option1" align="right"></label> <a href="#">Ayarla</a>
              </div>
              <div style="float:left; width:250px; height:60px; display:block; text-align:center"> <a href="#">Hesabımı tamamen silmek istiyorum</a></div>
			  
			 -->
			 </div>
			  <input type="hidden" name="profileID" value="<?=$profileID?>" />
			
            </div>
            <div class="tab-pane fade" id="tab-engellemeler">
           		
            			<div class="roundedcontent shareidea">
							<h1>
								<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> 
								Bu Modül Test Aşamasındadır.
							</h1>
							<div class="clear"></div>
							<p>Kullanıcılarımıza kusursuz bir deneyim sunmak için bu modülü kapalı olarak test ediyoruz.</p>
							<br>
						</div>
            <?php /* 
            <div class="roundedcontentsub" style="width:500px">
				<div class="blcklist-left"><img src="img/blck.png" /> <h1>Engellemeler</h1></div>
				<div class="blcklist-center">
					<a href="#">Engelli Listesi</a>
				</div>
				<div class="blcklist-right">
					<div class="pull-right">
            <input type="text" class="search-query span2" >
			<i class="icon-search"></i> 
          </div>
				</div>
              </div>
               <p></p>
			<div class="roundedcontent">
				<div class="usrlist-pic"><img src="img/user.jpg"></div>
				<div class="usrlist-info">
					<table class="table-striped">
						<tbody><tr>
							<th><span>Merve Altaylı</span></th>
							<th><a href="#">6 Ses</a></th>
							<th><a href="#">6 Takdir</a></th>
							<th><a href="#">0 Saygı</a></th>
							<th><a href="#">Kişiyi Sil</a></th>
						</tr>
						<tr>
							<td colspan="5"><p>8 Ekim 1977, Kahramanmaraş, Üniversite Mezunu, Ingilizce, Türkçe Boğaziçi Üniversitesi Türkiye Türkçesi ve Edebiyatı bölümü öğrencisiyim. Tüm yasakların yasaklanması taraftarıyım...</p></td>
						</tr>
					</tbody></table>
				</div>
				<div class="usrlist-set">
					<ul>
						<li><button class="btn btn-vekiladay">KALDIR</button></li>
						<li><strong>1213</strong> Takip Ettiği</li>
						<li><strong>131</strong> Takipçi</li>
					</ul>
				</div>
              </div>
			  <p></p>
			<div class="roundedcontent">
				<div class="usrlist-pic"><img src="img/user.jpg"></div>
				<div class="usrlist-info">
					<table class="table-striped">
						<tbody><tr>
							<th><span>Merve Altaylı</span></th>
							<th><a href="#">6 Ses</a></th>
							<th><a href="#">6 Takdir</a></th>
							<th><a href="#">0 Saygı</a></th>
							<th><a href="#">Kişiyi Sil</a></th>
						</tr>
						<tr>
							<td colspan="5"><p>8 Ekim 1977, Kahramanmaraş, Üniversite Mezunu, Ingilizce, Türkçe Boğaziçi Üniversitesi Türkiye Türkçesi ve Edebiyatı bölümü öğrencisiyim. Tüm yasakların yasaklanması taraftarıyım...</p></td>
						</tr>
					</tbody></table>
				</div>
				<div class="usrlist-set">
					<ul>
						<li><button class="btn btn-vekiladay">KALDIR</button></li>
						<li><strong>1213</strong> Takip Ettiği</li>
						<li><strong>131</strong> Takipçi</li>
					</ul>
				</div>
              </div>
                <p></p>
			<div class="roundedcontent">
				<div class="usrlist-pic"><img src="img/user.jpg"></div>
				<div class="usrlist-info">
					<table class="table-striped">
						<tbody><tr>
							<th><span>Merve Altaylı</span></th>
							<th><a href="#">6 Ses</a></th>
							<th><a href="#">6 Takdir</a></th>
							<th><a href="#">0 Saygı</a></th>
							<th><a href="#">Kişiyi Sil</a></th>
						</tr>
						<tr>
							<td colspan="5"><p>8 Ekim 1977, Kahramanmaraş, Üniversite Mezunu, Ingilizce, Türkçe Boğaziçi Üniversitesi Türkiye Türkçesi ve Edebiyatı bölümü öğrencisiyim. Tüm yasakların yasaklanması taraftarıyım...</p></td>
						</tr>
					</tbody></table>
				</div>
				<div class="usrlist-set">
					<ul>
						<li><button class="btn btn-vekiladay">KALDIR</button></li>
						<li><strong>1213</strong> Takip Ettiği</li>
						<li><strong>131</strong> Takipçi</li>
					</ul>
				</div>
              </div>
              -->*/?>
            </div>
          </div>
				<?php 
			}//eski tasarım sonu 
			else { //eski tasarım başı
            $model->addScript('
            $(function(){
                $("textarea#motto").keyup(function(){
                    $(this).parent().find(".character .number").html(200 - $(this).val().length);
                });

                $("textarea#motto").parent().find(".character .number").html(200 - $("textarea#motto").val().length);
            });
            ');
            
            $model->title = 'Profil Ayarlarım | Democratus.com';
            $profileID = $model->profileID;
            $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID=".$db->quote($profileID));
            $profile = null;
            if($db->loadObject($profile)){
                
            } else {
                $profile = new stdClass;
                
            }
?>
<!-- Profile Informations [Begin] -->
                        <div class="box" id="profile_information">
                            <div class="title">Profil Bilgileri</div>
                            <div class="line_center"></div> 
<form action="/my/profilesave/" method="post" class="form" id="myprofileform" onsubmit="return false;" style="margin:0;">
<p><label>Name</label>
<input type="text" name="name" value="<?=$profile->name?>" /><span class="required">*</span>
</p>
<?php
    //var_dump($profile->birth);
    $birthdate = strtotime( $profile->birth );
    if($profile->birth==null){
        $day = null;
        $month = null;
        $year = null;
    } else {
        
        $day = intval( date('d', $birthdate) );
        $month = intval( date('m', $birthdate) );
        $year = intval( date('Y', $birthdate) );
    }
 // echo $day . ' ' . $l['months'][$month];
?>


<p class="birth"><label>Doğum Tarihiniz</label>
<?php
    
?>

<?php echo $model->number_to_select('birthday',1, 31, $day, 'asc')?>
<?php echo $model->number_to_select('birthmonth',1, 12, $month, 'asc')?>
<?php echo $model->number_to_select('birth',1940, 1995, $year)?>
</p>

<p>
<div class="title" style="margin-top: 20px">Kendinizi Anlatın</div>
<div class="line_center"></div>
<div class="textarea_wrapper">
<textarea cols="5" rows="5" name="motto" id="motto" maxlength="200"><?=$profile->motto?></textarea>
<p class="character" style=""><span class="number">200</span> Karakter</p>
</div>

</p>

<p><label>Memleket</label>
<input type="text" name="hometown" id="hometown" value="<?=$profile->hometown?>" />
</p>

<p><label>Ülke</label>
<?php echo $this->country_to_select('countryID', $profile->countryID)?>
</p>

<p><label>Yaşadığı şehir</label>
<?php echo $this->city_to_select('cityID', $profile->countryID, $profile->cityID)?>
</p>




<p><label>Eğitim durumu</label>
<input type="text" name="education" id="education" value="<?=$profile->education?>" />
</p>

<p><label>Hobiler</label>
<input type="text" name="hobbies" id="hobbies" value="<?=$profile->hobbies?>" />
</p>

<p><label>Bildiği diller</label>
<input type="text" name="languages" id="languages" value="<?=$profile->languages?>" />
</p>

<p><label>Arayüz dili</label>
<select name="language" id="language">
    <option value="">&nbsp;</option>
    <option value="en" <?=$profile->language=='en'?'selected="selected"':''?>>English</option>
    <option value="tr" <?=$profile->language=='tr'?'selected="selected"':''?>>Türkçe</option>
    <option value="ar" <?=$profile->language=='ar'?'selected="selected"':''?>>Arabic</option>
    <option value="de" <?=$profile->language=='de'?'selected="selected"':''?>>Deutch</option>
    <option value="ru" <?=$profile->language=='ru'?'selected="selected"':''?>>Russian</option>
</select>

</p>
<p><label>&nbsp;</label>
<input type="submit" value="Kaydet" id="myprofilesave" /><span class="message" id="myaccountmessage">&nbsp;</span>
</p> 
<input type="hidden" name="profileID" id="profileID" value="<?=$profileID?>">
</form>

                            <div class="clear" style="margin-bottom: 20px;"></div>
                        </div>
                        <!-- Profile Informations [End] -->
<?php       }// eski tasarım sonu      
        }
		public function ajax_kullaniciSave()
		{
			global $model, $db;
        	$model->mode = 0;
			$response=array();
			$ka = filter_input(INPUT_POST, 'ka', FILTER_SANITIZE_STRING); 
			
			if($model->profile->permalink!="")
			{
				$response["status"]="error";
				$response["errorNote"]="Daha önde kullanıcı adı tanımlamışsınız ve değiştiremessiniz.";
				echo json_encode($response);
				die;
			}
			$query = "SELECT permalink FROM page WHERE permalink=".$db->Quote($ka);  
			$db->setQuery($query);
			$varmi="";
            $db->loadObject($varmi);
			if(count($varmi)>0)
			{
				$response["status"]="error";
				$response["errorNote"]="Seçmiş olduğunuz kullanıcı adı uygun değildir.";
				echo json_encode($response);
				die;
			}
			
			$query = "SELECT permalink FROM profile WHERE permalink=".$db->Quote($ka);  
			$db->setQuery($query);
			$varmi="";
            $db->loadObject($varmi);
			if(count($varmi)>0)
			{
				$response["status"]="error";
				$response["errorNote"]="Seçmiş olduğunuz kullanıcı adı başka bir kullanıcı tarafından kullanılmaktadır.";
				echo json_encode($response);
				die;
			}
			
			$query = "SELECT permalink FROM profile WHERE permalink=".$db->Quote($ka);  
			$db->setQuery($query);
			
			$profile = new stdClass;
			$profile->permalink = $ka;
			$profile->ID=$model->profileID;
			if($db->updateObject('profile', $profile, 'ID'))
			{
				$response["status"]="succuess";
				$response["successNote"]="Bu kullanıcı adınız tanımlayabilirsiniz.";
			}
			else {
				$response["status"]="error";
				$response["successNote"]="Kayıt sırasında beklenmedik bir hata oluştur lütfen tekrar deneyeniniz.";
			}
			
			
			echo json_encode($response);
		}
		public function ajax_kullaniciCheck()
		{
			global $model, $db;
        	$model->mode = 0;
			$response=array();
			$ka = filter_input(INPUT_POST, 'ka', FILTER_SANITIZE_STRING); 
			
			if(strlen($ka)<3 || strlen($ka)>25)
			{
				$response["status"]="error";
				$response["errorNote"]="Kullanıcı adınız en az 6 en fazla 25 karakter olmalıdır.";
				echo json_encode($response);
				die;     
			} 
			
			$letters = "/^([a-zA-Z0-9._-]+)$/"; 
			if(!preg_match($letters, $ka))
			{
				$response["status"]="error";
				$response["errorNote"]="Sadece harf, rakam ve (- _ .) karakterlerinden oluşan bir kullanıcı adı belirlemelisiniz. ";
				echo json_encode($response);
				die;  
			}
				
			$query = "SELECT permalink FROM page WHERE permalink=".$db->Quote($ka);  
			$db->setQuery($query);
			$varmi="";
            $db->loadObject($varmi);
			if(count($varmi)>0)
			{
				$response["status"]="error";
				$response["errorNote"]="Seçmiş olduğunuz kullanıcı adı uygun değildir.";
				echo json_encode($response);
				die;
			}
			
			$query = "SELECT permalink FROM profile WHERE permalink=".$db->Quote($ka);  
			$db->setQuery($query);
			$varmi="";
            $db->loadObject($varmi);
			if(count($varmi)>0)
			{
				$response["status"]="error";
				$response["errorNote"]="Seçmiş olduğunuz kullanıcı adı başka bir kullanıcı tarafından kullanılmaktadır.";
				echo json_encode($response);
				die;
			}
			
			$response["status"]="succuess";
			$response["successNote"]="Bu kullanıcı adınız tanımlayabilirsiniz.";
			echo json_encode($response);
		}
        public function ajax_imageUploadNew()
        {
        	//echo  var_dump($_FILES);
        	$error = "";
        	$msg = "";
        	$fileElementName = 'imageFileinput';
        	if(!empty($_FILES[$fileElementName]['error']))
        	{
        		switch($_FILES[$fileElementName]['error'])
        		{
        	
        			case '1':
        				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        				break;
        			case '2':
        				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        				break;
        			case '3':
        				$error = 'The uploaded file was only partially uploaded';
        				break;
        			case '4':
        				$error = 'No file was uploaded.';
        				break;
        	
        			case '6':
        				$error = 'Missing a temporary folder';
        				break;
        			case '7':
        				$error = 'Failed to write file to disk';
        				break;
        			case '8':
        				$error = 'File upload stopped by extension';
        				break;
        			case '999':
        			default:
        				$error = 'No error code avaiable';
        		}
        	}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
        	{
        		$error = 'No file was uploaded..';
        	}else
        	{
        		global $model, $db;
        		$model->mode = 0;
        		try{
        			$upload_name = $fileElementName;
        			$max_file_size_in_bytes = 2147483647;                // 2GB in bytes
        			$extension_whitelist = array("jpg", "gif", "png");    // Allowed file extensions
        			$valid_chars_regex = '.A-Z0-9_!@#$%^&()+={}\[\]\',~`-';                // Characters allowed in the file name (in a Regular Expression format)
        			
        			$save_path = $model->getUploadPath();
        			// Other variables
        			$MAX_FILENAME_LENGTH = 260;
        			$file_name = "";
        			$file_extension = "";
        			$uploadErrors = array(
        					0=>"There is no error, the file uploaded with success",
        					1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
        					2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        					3=>"The uploaded file was only partially uploaded",
        					4=>"No file was uploaded",
        					6=>"Missing a temporary folder"
        			);
        		
        		
        			// Validate the file size (Warning: the largest files supported by this code is 2GB)
        			$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
        			if (!$file_size || $file_size > $max_file_size_in_bytes) {
        				throw new Exception("File exceeds the maximum allowed size");
        			}
        		
        			if ($file_size <= 0) {
        				throw new Exception("File size outside allowed lower bound");
        			}
        		
        		
        			// Validate file name (for our purposes we'll just remove invalid characters)
        			$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "_", basename($_FILES[$upload_name]['name']));
        		
        			while(strstr($file_name,'__')) $file_name = str_replace('__', '_', $file_name);
        		
        			$path_info = pathinfo($_FILES[$upload_name]['name']);
        			$file_extension = strtolower($path_info["extension"]);
        		
        			$file_name = substr_replace($file_name,$file_extension,strlen($file_name)-strlen($file_extension));
        		
        			//$file_name = $path_info["basename"].'.'.$file_extension;
        		
        			if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
        				throw new Exception("Invalid file name");
        			}
        		
        		
        			// Validate that we won't over-write an existing file
        			if (file_exists($save_path . $file_name)) {
        				throw new Exception("File with this name already exists");
        			}
        		
        			// Validate file extension
        			$is_valid_extension = false;
        			foreach ($extension_whitelist as $extension) {
        				if (strcasecmp($file_extension, $extension) == 0) {
        					$is_valid_extension = true;
        					break;
        				}
        			}
        			if (!$is_valid_extension) {
        				throw new Exception("Invalid file extension");
        			}
        		
        			if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], UPLOADPATH.$save_path.$file_name)) {
        				throw new Exception("File could not be saved.");
        			}
        		
        		
        			$row = array();
        			$row['profileID'] = $model->profileID;
        			$row['imagepath'] = $save_path.$file_name;
        			$row['status'] = 1;
        			$row = (object) $row;
        		
        			 
        			/*
        			 $db->insertObject('profileimage', $row);
        			$pi = (object) array('path'=>$save_path.$file_name, 'pageID'=>$pageID );
        			*/
        			if( $db->insertObject('profileimage', $row) ){
        				//echo 'ok';
        				//echo $db->insertid();
        				$profile = new stdClass;
			            $profile->ID = $model->profileID;
			            $profile->image = $row->imagepath;
			            $db->updateObject('profile', $profile, 'ID');
        			} else {
        				//hata
        				throw new Exception('page image db insert error');
        			}
        		} catch(Exception $e) {
        			//if(!headers_sent()) header("HTTP/1.1 500 ".$e->getMessage());
        			$error.="-". $e->getMessage();
        		}
        		
        		
        		$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
        		$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
        		//for security reason, we force to remove all uploaded file
        		@unlink($_FILES[$fileElementName]);
        	}
        	echo "{";
        	echo				"error: '" . $error . "',\n";
        	echo				"msg: '" . $msg . "'\n";
        	echo "}";
        }
        public function ajax_myprofilesave(){
            global $model,$db;
            $profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            try{
                if($profileID!=$model->profileID) throw new Exception('profileID' . $profileID . '-' . $model->profileID);
                $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID = " . $db->quote($profileID) . " LIMIT 1" );
                $profile = null;
                if($db->loadObject($profile)){
                    $profile->name      = mb_substr( strip_tags( $model->splitword( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') )),0, 200);
                    $profile->motto     = mb_substr( strip_tags( $model->splitword( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'motto', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') )), 0, 200);
                    $profile->languages = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'languages', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->language  = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->hometown  = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'hometown', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->hobbies   = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'hobbies', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->education = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->sex		= strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'sex', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    
                    
                    $profile->living    = filter_input(INPUT_POST, 'living', FILTER_SANITIZE_NUMBER_INT);
                    
                    $birthday     = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_NUMBER_INT);                
                    $birthmonth   = filter_input(INPUT_POST, 'birthmonth', FILTER_SANITIZE_NUMBER_INT);                
                    $birth        = filter_input(INPUT_POST, 'birth', FILTER_SANITIZE_NUMBER_INT);
                    $profile->birth     = date('Y-m-d', mktime(0, 0, 0, $birthmonth, $birthday, $birth));
                    
                    $profile->countryID = filter_input(INPUT_POST, 'countryID', FILTER_SANITIZE_NUMBER_INT);
                    $profile->cityID    = filter_input(INPUT_POST, 'cityID', FILTER_SANITIZE_NUMBER_INT);
                    if(strlen($profile->name)<4)
                    {
                       	$cevap='$("#myprofileResponse").html("İsim Soyisim alanı en az 4 karakter olmalıdır.").addClass("alertBox").fadeIn(2000);';
           				$cevap.='var t=setTimeout("$(\'#myprofileResponse\').fadeOut(2000);",7000);';
           				echo $cevap;
                    }
                    else 
                    {
	                    if($db->updateObject('profile', $profile, 'ID')){
	                        echo '$("#myaccountmessage").text("Kaydedildi").fadeIn(100).delay(2000).fadeOut(100);';
	                    } else {
	                        throw new Exception('Connection Error');
	                    }
                    }
                    
                }
            }catch(Exception $e){
                echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'.$e->getMessage().'</p>').'").dialog({
                            modal: true,
                            title: "Error",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 2000 );
                        ';
            } 
        }        
        public function ajax_myemailchange(){
            global $model,$db;
            $model->mode = 0;
            $response = array();
            
            $newemail = strtolower( trim( filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ) );
            $emailperms = intval( filter_input(INPUT_POST, 'emailperms', FILTER_SANITIZE_NUMBER_INT) );
            
            try{
            
            
                if(!isEmail($newemail)) throw new Exception('Bu bir email değil ki!');
                
                //$db->setQuery("SELECT ID, emailperms FROM profile WHERE ID=".$model->profileID);
                
                $pr = new stdClass;
                $pr->ID = $model->profileID;
                $pr->emailperms = $emailperms;
                
                if( !$db->updateObject('profile', $pr, 'ID') ){
                    throw new Exception('Profil nedense bulunamadı!');
                }
                
                $response['status'] = 'success';
                $response['message'] = 'Kaydedildi';
                
                
                
                //başka bir kullanıcı var mı bu hesaba bağlı?
                //$db->setQuery("SELECT u.* FROM user AS u WHERE u.email = " . $db->quote($newemail) . " AND u.ID<>".$db->quote($model->userID)." LIMIT 1" );
                $db->setQuery("SELECT u.* FROM user AS u WHERE u.email = " . $db->quote($newemail) . " LIMIT 1" );
                $u = null;
                if($db->loadObject($u)){
                    if($u->ID != $model->userID)
                        throw new Exception('bu mail adresi kayıtlı zaten!');
                        
                } else {

                    //istek oluştur.
                    
                    $request = new stdClass;
                        
                    $request->email     = $newemail;
                    $request->userID    = $model->userID;
                    $request->key       = md5( KEY . time() . uniqid() );
                    $request->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                    $request->datetime  = date('Y-m-d H:i:s');
                    $request->status    = 0;
                    
                    if($db->insertObject('emailchangerequest', $request)){
                        $response['status'] = 'success';
                        $response['message'] = 'Yeni adres talebiniz alındı. Bu adresin aktive olması için size gönderdiğimiz onay mailindeki yönergeleri izleyin. Olur da onay mailinizi bulamazsanız spam klasörünüzü de kontrol ediverin.';
                        $model->sendsystemmail($request->email, 'Yeni email adres onayı', 'Merhabalar, <br /> Democratus.com üzerinden yapmış olduğunuz e-posta adresi değişikliğinin aktif olması için şu onay linkine tıklamalı veya tarayıcınızın adres çubuğuna yapıştırmalısınız:<br /><a href="http://democratus.com/user/activate/'.$request->key.'"> http://democratus.com/user/emailactivate/'.$request->key.'</a>');                    
                    } else {                    
                        throw new Exception('kayıt hatası');
                    }
                
                }
                
                
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response); 
        }        
        public function ajax_mypasswordchange(){
            global $model,$db;
            $model->mode = 0;
            $response = array();
            
            $oldpass    = trim( filter_input(INPUT_POST, 'oldpass', FILTER_SANITIZE_STRING) );
            $pass       = trim( filter_input(INPUT_POST, 'newpass', FILTER_SANITIZE_STRING) );
            $pass2      = trim( filter_input(INPUT_POST, 'newpass2', FILTER_SANITIZE_STRING) );
            
            try{
                $passo = md5(KEY . trim( $oldpass ) );
                
                if($model->user->pass!=$passo) throw new Exception('Eski şifrenizi bilemediniz!');
                if(strlen($pass)<6) throw new Exception('Şifre en az 6 karakter olmalı!');
                if($pass!=$pass2) throw new Exception('Şifreler aynı değil!');

                $model->user->pass = md5(KEY . trim( $pass ) );
                
                if($db->updateObject('user', $model->user, 'ID', false)){
                    
                    $response['status'] = 'success';
                    $response['message'] = 'Şifrenizi değiştirdiniz. Artık yeni şifrenizi kullanabilirsiniz.';                    
                } else {                    
                    throw new Exception('kayıt hatası');
                }
                
                
                
                
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            echo json_encode($response); 
        }
        
        public function ajax_imageupload(){
            global $model, $db;
            $model->mode = 0;
                  try{
                if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST')) throw new Exception('this is not a post');
                
                //$pageID     = intval($model->paths[2]);
                
                //if($pageID<=0) throw new Exception('pageID error');
            
                // Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
                $POST_MAX_SIZE = ini_get('post_max_size');
                $unit = strtoupper(substr($POST_MAX_SIZE, -1));
                $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

                if ((int)@$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
                    //header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
                    throw new Exception("POST exceeded maximum allowed size.");
                }
                
                // Settings
                $save_path = $model->getUploadPath();
                
                //mkdir($save_path);

                 // "c:\\web\\uploads\\";//getcwd() . "/uploads/";                // The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
                $upload_name = "uploadfile";
                $max_file_size_in_bytes = 2147483647;                // 2GB in bytes
                $extension_whitelist = array("jpg", "gif", "png");    // Allowed file extensions
                $valid_chars_regex = '.A-Z0-9_!@#$%^&()+={}\[\]\',~`-';                // Characters allowed in the file name (in a Regular Expression format)
                 
                // Other variables    
                $MAX_FILENAME_LENGTH = 260;
                $file_name = "";
                $file_extension = "";
                $uploadErrors = array(
                    0=>"There is no error, the file uploaded with success",
                    1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
                    2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                    3=>"The uploaded file was only partially uploaded",
                    4=>"No file was uploaded",
                    6=>"Missing a temporary folder"
                );
                
             //if post 



            //HandleError("test");
            // Validate the upload
                if (!isset($_FILES[$upload_name])) {
                    throw new Exception("No upload found in \$_FILES for " . $upload_name);
                } 
                elseif (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
                    throw new Exception($uploadErrors[$_FILES[$upload_name]["error"]].$_FILES[$upload_name]['name']);
                } 
                elseif (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
                    throw new Exception("Upload failed is_uploaded_file test.");
         
                } 
                elseif (!isset($_FILES[$upload_name]['name'])) {
                    throw new Exception("File has no name.");
                }
                
            // Validate the file size (Warning: the largest files supported by this code is 2GB)
                $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
                if (!$file_size || $file_size > $max_file_size_in_bytes) {
                    throw new Exception("File exceeds the maximum allowed size");
                }
                
                if ($file_size <= 0) {
                    throw new Exception("File size outside allowed lower bound");
                }

                
            // Validate file name (for our purposes we'll just remove invalid characters)
                $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "_", basename($_FILES[$upload_name]['name']));
                
                while(strstr($file_name,'__')) $file_name = str_replace('__', '_', $file_name);
                
                $path_info = pathinfo($_FILES[$upload_name]['name']);
                $file_extension = strtolower($path_info["extension"]);
                
                $file_name = substr_replace($file_name,$file_extension,strlen($file_name)-strlen($file_extension));
                
                //$file_name = $path_info["basename"].'.'.$file_extension;
                
                if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
                    throw new Exception("Invalid file name");
                }


            // Validate that we won't over-write an existing file
                if (file_exists($save_path . $file_name)) {
                    throw new Exception("File with this name already exists");
                }
                
            // Validate file extension
                $is_valid_extension = false;
                foreach ($extension_whitelist as $extension) {
                    if (strcasecmp($file_extension, $extension) == 0) {
                        $is_valid_extension = true;
                        break;
                    }
                }
                if (!$is_valid_extension) {
                    throw new Exception("Invalid file extension");
                }
                
                if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], UPLOADPATH.$save_path.$file_name)) {
                    throw new Exception("File could not be saved.");
                }
                

                $row = array();
                $row['profileID'] = $model->profileID;
                $row['imagepath'] = $save_path.$file_name;
                $row['status'] = 1;
                $row = (object) $row;
                

				$profile = new stdClass;
                $profile->ID = $model->profileID;
                $profile->image = $row['imagepath'];
                $db->updateObject('profile', $profile, 'ID');
                 /*
                    $db->insertObject('profileimage', $row); 
                $pi = (object) array('path'=>$save_path.$file_name, 'pageID'=>$pageID );         
                */
                if( $db->insertObject('profileimage', $row) ){
                    //echo 'ok';
                    echo $db->insertid();
                } else {
                    //hata
                    throw new Exception('page image db insert error');
                }
            } catch(Exception $e) {
                if(!headers_sent()) header("HTTP/1.1 500 ".$e->getMessage());
                echo $e->getMessage();
            }
            

            
            
            /*
            
            if($uploader->save()){
                echo htmlspecialchars(json_encode($uploader->result), ENT_NOQUOTES); // to pass data through iframe you will need to encode all html tags
                $row = array();
                $row['profileID'] = $model->profileID;
                $row['imagepath'] = $uploader->subpath . $uploader->filename . '.' . $uploader->fileext;
                $row['status'] = 1;
                $row = (object) $row;
                
                $db->insertObject('profileimage', $row);                
            } else {
                echo htmlspecialchars(json_encode($uploader->result), ENT_NOQUOTES);
            }
            */
        }        
        
        
        public function my_imageupload(){
            global $model, $db;
            //$model->mode = 0;
                  try{
                if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST')) throw new Exception('this is not a post');
                
                //$pageID     = intval($model->paths[2]);
                
                //if($pageID<=0) throw new Exception('pageID error');
            
                // Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
                $POST_MAX_SIZE = ini_get('post_max_size');
                $unit = strtoupper(substr($POST_MAX_SIZE, -1));
                $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

                if ((int)@$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
                    //header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
                    throw new Exception("POST exceeded maximum allowed size.");
                }
                
                // Settings
                $save_path = $model->getUploadPath();
                
                //mkdir($save_path);

                 // "c:\\web\\uploads\\";//getcwd() . "/uploads/";                // The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
                $upload_name = "uploadfile";
                $max_file_size_in_bytes = 2147483647;                // 2GB in bytes
                $extension_whitelist = array("jpg", "gif", "png");    // Allowed file extensions
                //$valid_chars_regex = '.A-Z0-9_!@#$%^&()+={}\[\]\',~`-';                // Characters allowed in the file name (in a Regular Expression format)
                $valid_chars_regex = '.A-Z0-9_-';                // Characters allowed in the file name (in a Regular Expression format)
                 
                // Other variables    
                $MAX_FILENAME_LENGTH = 260;
                $file_name = "";
                $file_extension = "";
                $uploadErrors = array(
                    0=>"There is no error, the file uploaded with success",
                    1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
                    2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                    3=>"The uploaded file was only partially uploaded",
                    4=>"No file was uploaded",
                    6=>"Missing a temporary folder"
                );
                
             //if post 



            //HandleError("test");
            // Validate the upload
                if (!isset($_FILES[$upload_name])) {
                    throw new Exception("No upload found in \$_FILES for " . $upload_name);
                } 
                elseif (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
                    throw new Exception($uploadErrors[$_FILES[$upload_name]["error"]].$_FILES[$upload_name]['name']);
                } 
                elseif (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
                    throw new Exception("Upload failed is_uploaded_file test.");
         
                } 
                elseif (!isset($_FILES[$upload_name]['name'])) {
                    throw new Exception("File has no name.");
                }
                
            // Validate the file size (Warning: the largest files supported by this code is 2GB)
                $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
                if (!$file_size || $file_size > $max_file_size_in_bytes) {
                    throw new Exception("File exceeds the maximum allowed size");
                }
                
                if ($file_size <= 0) {
                    throw new Exception("File size outside allowed lower bound");
                }

                
            // Validate file name (for our purposes we'll just remove invalid characters)
                $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "_", basename($_FILES[$upload_name]['name']));
                
                while(strstr($file_name,'__')) $file_name = str_replace('__', '_', $file_name);
                
                $path_info = pathinfo($_FILES[$upload_name]['name']);
                $file_extension = strtolower($path_info["extension"]);
                
                $file_name = substr_replace($file_name,$file_extension,strlen($file_name)-strlen($file_extension));
                
                //$file_name = $path_info["basename"].'.'.$file_extension;
                
                if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
                    throw new Exception("Invalid file name");
                }


            // Validate that we won't over-write an existing file
                if (file_exists($save_path . $file_name)) {
                    throw new Exception("File with this name already exists");
                }
                
            // Validate file extension
                $is_valid_extension = false;
                foreach ($extension_whitelist as $extension) {
                    if (strcasecmp($file_extension, $extension) == 0) {
                        $is_valid_extension = true;
                        break;
                    }
                }
                if (!$is_valid_extension) {
                    throw new Exception("Invalid file extension");
                }
                
                if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], UPLOADPATH.$save_path.$file_name)) {
                    throw new Exception("File could not be saved.");
                }
                

                $row = array();
                $row['profileID'] = $model->profileID;
                $row['imagepath'] = $save_path.$file_name;
                $row['status'] = 1;
                $row = (object) $row;
                
                               
                 /*
                    $db->insertObject('profileimage', $row); 
                $pi = (object) array('path'=>$save_path.$file_name, 'pageID'=>$pageID );         
                */
                if( $db->insertObject('profileimage', $row) ){
                    //echo 'ok';
                    //echo $db->insertid();
                    $model->redirect('/my/photos', 1);
                    
                    
                } else {
                    //hata
                    throw new Exception('page image db insert error');
                }
            } catch(Exception $e) {
                if(!headers_sent()) header("HTTP/1.1 500 ".$e->getMessage());
                echo $e->getMessage();
                
            }
            

            
            
            /*
            
            if($uploader->save()){
                echo htmlspecialchars(json_encode($uploader->result), ENT_NOQUOTES); // to pass data through iframe you will need to encode all html tags
                $row = array();
                $row['profileID'] = $model->profileID;
                $row['imagepath'] = $uploader->subpath . $uploader->filename . '.' . $uploader->fileext;
                $row['status'] = 1;
                $row = (object) $row;
                
                $db->insertObject('profileimage', $row);                
            } else {
                echo htmlspecialchars(json_encode($uploader->result), ENT_NOQUOTES);
            }
            */
        }  
                
        public function my_privacy(){
            
            global $model, $db;
            
            $model->addScript($model->pluginurl . 'my.js', 'my.js', 1 ); 
            $model->title = 'Gizlilik Ayarlarım | Democratus.com';
            $profileID = $model->profileID;
            $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID=".$db->quote($profileID));
            $profile = null;
            if($db->loadObject($profile)){
                
            } else {
                $profile = new stdClass;
                
            }
?>
<!-- Profile Informations [Begin] -->
                        <div class="box" id="profile_information">
                            <div class="title">Gizlilik Ayarlarım</div>
                            <div class="line_center"></div>

<form action="/my/privacysave/" method="post" class="form" id="myprivacyform" onsubmit="return false;" >

<p><label>Profil</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showprofile', $profile->showprofile, false);
?>
</p>
<p><label>Doğum tarihim</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showbirth', $profile->showbirth, false);
?>
</p>
<p><label>Motto cümlem</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showmotto', $profile->showmotto, false);
?>
</p>

<p><label>Paylaşımlarım</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showdies', $profile->showdies, false);
?>
</p>

<p><label>Yorum İzni</label>
<?php
    echo $model->array_to_select(config::$privacies, 'dicomment', $profile->dicomment, false);
?>
<span class="info">Paylaşımlarına kimlerin yorum yapacağını belirle.</span>
</p>

<p><label>Memleket</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showhometown', $profile->showhometown, false);
?>
</p>

<p><label>Ülke</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showcountry', $profile->showcountry, false);
?>
</p>

<p><label>Yaşadığın şehir</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showcity', $profile->showcity, false);
?>
</p>

<p><label>Medeni durum</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showmarital', $profile->showmarital, false);
?>
</p>

<p><label>Eğitim durumu</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showeducation', $profile->showeducation, false);
?>
</p>
<p><label>Hobilerim</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showhobbies', $profile->showhobbies, false);
?>
</p>
<p><label>Languages</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showlanguages', $profile->showlanguages, false);
?>
</p>

<p><label>E-posta adresim</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showemail', $profile->showemail, false);
?>
</p>

<p><label>Takipçilerim</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showfollowers', $profile->showfollowers, false);
?>
</p>

<p><label>Takip ettiklerim</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showfollowings', $profile->showfollowings, false);
?>
</p>

<p><label>Fotograflarım</label>
<?php
    echo $model->array_to_select(config::$privacies, 'showphotos', $profile->showphotos, false);
?>
</p>



<p><label>&nbsp;</label>
<input type="submit" value="Kaydet" id="myprivacysave" /><span class="message" id="myprivacymessage">&nbsp;</span>
</p>
<input type="hidden" name="profileID" value="<?=$profileID?>">
</form>

                            <div class="clear" style="margin-bottom: 20px;"></div>
                        </div>
                        <!-- Profile Informations [End] -->

<?php            
        }
        
        public function ajax_myprivacysave(){
            global $model,$db;
            
            $profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            try{
                if($profileID!=$model->profileID) throw new Exception('profileID' . $profileID . '-' . $model->profileID);
                
                $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID = " . $db->quote($profileID) . " LIMIT 1" );
                $profile = null;
                if($db->loadObject($profile)){

                    //$profile->showprofile   = filter_input(INPUT_POST, 'showprofile', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showbirth     = filter_input(INPUT_POST, 'showbirth', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showmotto     = filter_input(INPUT_POST, 'showmotto', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showdies      = filter_input(INPUT_POST, 'showdies', FILTER_SANITIZE_NUMBER_INT);
                    $profile->dicomment     = filter_input(INPUT_POST, 'dicomment', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showhometown  = filter_input(INPUT_POST, 'showhometown', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showcountry   = filter_input(INPUT_POST, 'showcountry', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showcity      = filter_input(INPUT_POST, 'showcity', FILTER_SANITIZE_NUMBER_INT);
                    //$profile->showmarital   = filter_input(INPUT_POST, 'showmarital', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showeducation = filter_input(INPUT_POST, 'showeducation', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showhobbies   = filter_input(INPUT_POST, 'showhobbies', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showlanguages = filter_input(INPUT_POST, 'showlanguages', FILTER_SANITIZE_NUMBER_INT);
                    //$profile->showemail     = filter_input(INPUT_POST, 'showemail', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showfollowers = filter_input(INPUT_POST, 'showfollowers', FILTER_SANITIZE_NUMBER_INT);
                    $profile->showfollowings = filter_input(INPUT_POST, 'showfollowings', FILTER_SANITIZE_NUMBER_INT);
                    //$profile->showphotos    = filter_input(INPUT_POST, 'showphotos', FILTER_SANITIZE_NUMBER_INT);

             

                    if($db->updateObject('profile', $profile, 'ID')){
                        echo '$("#myprivacymessage").text("Success").fadeIn(100).delay(2000).fadeOut(100);';
                    } else {
                        throw new Exception('Connection Error');
                    }
                    
                    
                }
            }catch(Exception $e){
                echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'.$e->getMessage().'</p>').'").dialog({
                            modal: true,
                            title: "Error",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 2000 );
                        ';
            } 
        }
        
        public function ajax_makeprofilephoto(){ 
            global $model, $db;
            
            $photoID = filter_input(INPUT_POST, 'photoID', FILTER_SANITIZE_NUMBER_INT);
            $response = array();
            
            try{
                if($photoID<1) throw new Exception ('Photo ID not valid');
                
                $db->setQuery("SELECT * FROM profileimage WHERE ID=" . $db->quote($photoID));
                $photo = null;
                if($db->loadObject($photo)){
                    $profile = new stdClass;
                    $profile->ID = $model->profileID;
                    $profile->image = $photo->imagepath;
                    if($db->updateObject('profile', $profile, 'ID')){
                        $model->profile->image=$photo->imagepath;
                        $response['status']='success';
                    } else {
                        throw new Exception('error');
                    }
                    
                    
                } else throw new Exception('error');
                
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            echo json_encode($response);
            
        }
        
        
        public function ajax_removemyimage(){ 
            global $model, $db;
            
            $photoID = filter_input(INPUT_POST, 'photoID', FILTER_SANITIZE_NUMBER_INT);
            $response = array();
            
            try{
                if($photoID<1) throw new Exception ('Photo ID not valid');
                
                $db->setQuery("SELECT * FROM profileimage WHERE ID=" . $db->quote($photoID));
                $photo = null;
                if($db->loadObject($photo)){
                    if($photo->profileID != $model->profileID) throw new Exception('this is not your photo');
                    $photo->status = 0;
                    
                    if($db->updateObject('profileimage', $photo, 'ID')){
                        $response['status']='success';
                    } else {
                        throw new Exception('error');
                    }
                    
                    
                } else throw new Exception('error');
                
            } catch (Exception $e){
                $response['status'] = 'error';
                $response['message'] = $e->getMessage();
            }
            echo json_encode($response);
            
        }        
        
        public function ajax_getcities(){
            global $model, $db;

            $db->setQuery('SELECT p.cityID FROM profile AS p WHERE p.ID='.$db->quote($model->user->ID));
            $cityID = null;
            $db->loadObject($cityID);
            $selected = intval($cityID->cityID);
            
            $countryID = filter_input(INPUT_POST, 'countryID', FILTER_SANITIZE_NUMBER_INT);
            
            $db->setQuery('SELECT ct.* FROM city AS ct WHERE ct.countryID='.$db->quote($countryID).' ORDER BY ct.city;');
            $items = $db->loadAssocList();
            
            echo json_encode($items);
            return;
            
            $html = '<select name="'.$name.'" id="'.$name.'">';
            $sel = 0 == $selected?' selected="selected"':'';
            $html.= '<option value="0"'.$sel.'>-</option>';
            foreach($items as $item){
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->city.'</option>';
            }
            
            $html.='</select>';
            
            return $html;
        }
        public function education_to_select($name,  $selected=null){
            global $model, $db; 
            $edu = new stdClass();
            
            $edu->ID="1";
            $edu->edu="ilköğretim";  
            $items[1]=$edu;
            $edu=null;
            $edu->ID="2";
            $edu->edu="ortaöğretim";  
            $items[2]=$edu;
            $edu=null;
            $edu->ID="3";
            $edu->edu="önlisans";  
            $items[3]=$edu;
            $edu=null;
            $edu->ID="4";
            $edu->edu="lisans";  
            $items[4]=$edu;
            $edu=null;
            $edu->ID="5";
            $edu->edu="yüksek lisans";  
            $items[5]=$edu;
            $edu=null;
            $edu->ID="6";
            $edu->edu="doktora";  
            $items[6]=$edu;
            $edu=null;

            $html = '<select name="'.$name.'" id="'.$name.'">';
            $sel = 0 == $selected?' selected="selected"':'';
            $html.= '<option value="0"'.$sel.'>Eğitiminiz</option>';
            foreach($items as $item){
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->edu.'</option>';
            }
            
            $html.='</select>';
            
            return $html;
        }
    	public function sex_to_select($name,  $selected=null){
            global $model, $db; 
            $edu = new stdClass();
            
            $edu->ID="unknow";
            $edu->edu="Cinsiyetiniz";  
            $items[1]=$edu;
            $edu=null;
            $edu->ID="male";
            $edu->edu="Erkek";  
            $items[2]=$edu;
            $edu=null;
            $edu->ID="female";
            $edu->edu="Kadın";  
            $items[3]=$edu;
            $edu=null;
            $edu->ID="other";
            $edu->edu="Diğer";
            $items[4]=$edu;
            $edu=null;
            
            $html = '<select name="'.$name.'" id="'.$name.'">';
            foreach($items as $item){
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->edu.'</option>';
            }
            
            $html.='</select>';
            
            return $html;
        }
        
        public function country_to_select($name, $selected=null){
            global $model, $db;
                        
            $db->setQuery('SELECT co.* FROM country AS co ORDER BY co.country;');
            $items = $db->loadObjectList();
            
            
            
            $html = '<select name="'.$name.'" id="'.$name.'">';
            $sel = 0 == $selected?' selected="selected"':'';
            $html.= '<option value="0"'.$sel.'>-</option>';
            foreach($items as $item){
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->country.'</option>';
            }
            
            $html.='</select>';
            
            return $html;
        }
        
        public function city_to_select($name, $countryID, $selected=null){
            global $model, $db;
                        
            $db->setQuery('SELECT ct.* FROM city AS ct WHERE ct.countryID='.$db->quote($countryID).' ORDER BY ct.city;');
            $items = $db->loadObjectList();
            
            
            
            $html = '<select name="'.$name.'" id="'.$name.'">';
            $sel = 0 == $selected?' selected="selected"':'';
            $html.= '<option value="0"'.$sel.'>-</option>';
            foreach($items as $item){
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->city.'</option>';
            }
            
            $html.='</select>';
            
            return $html;
        }
        
        public function photolimitreached(){
            global $model, $db;
            $db->setQuery("SELECT COUNT(*) FROM profileimage WHERE profileID=".$db->quote($model->profileID)." AND status>0");
            //echo intval($db->loadResult());
            return intval($db->loadResult())>=7;            
        }
        public function ajax_facebookPaylasIzin(){
            global $model,$db;            
            //$profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            //$izin = filter_input(INPUT_POST, 'izin', FILTER_SANITIZE_NUMBER_INT);,
            $gProfil= new stdClass();
            if($model->profile->facebookPaylasizin==1)
            $gProfil->facebookPaylasizin=0;
            else
            $gProfil->facebookPaylasizin=1;
            $gProfil->ID=$model->profileID;
            
            if($db->updateObject('profile', $gProfil, 'ID'))
            {
            	echo "tamam";
            }
            //mevcut izini bir hidden in  içine koyup ordan çekicem burda toggle yapmam gerek
            
        }
        public function ajax_twitterPaylasIzin(){
            global $model,$db;            
            //$profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            //$izin = filter_input(INPUT_POST, 'izin', FILTER_SANITIZE_NUMBER_INT);,
            $gProfil= new stdClass();
            if($model->profile->twitterPaylasizin==1)
            $gProfil->twitterPaylasizin=0;
            else
            $gProfil->twitterPaylasizin=1;
            $gProfil->ID=$model->profileID;
            
            if($db->updateObject('profile', $gProfil, 'ID'))
            {
            	echo "tamam";
            }
            //mevcut izini bir hidden in  içine koyup ordan çekicem burda toggle yapmam gerek
            
        }
    }
?>