<?php
    class my_plugin extends control{
    	public function main()
		{
           	model::checkLogin(1);
			global $model, $db, $l;
			$model->template="ala";
			$model->view="my";
			$model->title = 'Democratus';
            $model->addScript('var share = 0');
            if(isset($_GET['share']))
                $model->addScript('share =1');
                        
                            
                    
			$model->addHeaderElement();
			
			$model->addScript(PLUGINURL."my/my.js", "my.js", 1);
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
			if($model->paths[1]=="afterRegister")
			{
				return $this->afterRegister($model->paths[2]);
			}
			?>
			<section class="banner">
				<header>
					<h1>Profil Ayarları</h1>
				</header>
				
				<nav>
					<ul class="alt_menu visible-desktop" id="tab-container">
						<li class="active"><a href="#tab-profilA" rel="profilA" data-toggle="tab">PROFİL AYARLARI</a></li>
						<li><a href="#tab-arkadasB" rel="arkadasB" data-toggle="tab">ARKADAŞ BUL</a></li>
						<li><a href="#tab-privacy" rel="privacy" data-toggle="tab">GİZLİLİK</a></li>
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
							<?php 
								$this->profile_form();
								$this->social_form(); //localde sorunlu 
								$this->passwordChange_form();
							?>   
						</div>
					</section>
					
				</div>
				<!-- Profile Ayarları Tab  Sonu -->
				<!-- Profile arkadaş Tab -->
				<div class="tab-pane fade in" id="tab-arkadasB">
					<section class="satir ilk_satir uste_cikar">
						<div class="satir_ic">
							<?php
								$this->findFriendSocial_form(); //localde sorunlu 
							?>
						</div>
					</section>             
                </div>
                <!-- Profile arkadaş Tab  Sonu --> 
                <!-- Gizlilik Tab -->
                <div class="tab-pane fade" id="tab-privacy">
	        		<section class="satir ilk_satir uste_cikar">
						<div class="satir_ic">
							<h1 class="sayfa_basligi">Gizlilik</h1>
							<?php 
								$this->privacy_form();
							?>
						</div>
						
					</section>
	            </div>
	            <!-- Gizlilik Tab Sonu -->
	    	</div>
		<?
		}
		public function privacy_form()
		{
			global $model;
			?>
			<p>Engelli kullanıclar</p>
			<div id="block_userList"></div>
			<?php
		}
		public function profile_form()
		{
			global $model;
			$c_profile = new profile ;
			$birth= date_parse($c_profile->profile->birth);
			$rt=$c_profile->check_userMin();
			if(!$rt["success"])
			{
				foreach($rt["errors"] as $e)
				{
					switch($e)
					{
						case "permalink" : $varningT[] = "Benzersiz kullanıcı adınızı "; break;
						case "email" : $varningT[] ="E-posta adresinizi "; break;
					}
				}
				echo '<div id="alertContent" class="alert alert-block alert-error fade in" style="width:90%; display:table; margin:0 auto 10px auto;">
			        <p id="alert-textArea">'.implode(" ve ", $varningT).' doğru ve eksiksiz şekilde girmelisiniz !</p>
		   		</div>';
			}
			?> 
			<header>
				<h1 class="sayfa_basligi">Profil Ayarlarım</h1>
			</header>			
			<form  id="profil_bilgileri_formu" name="profil_bilgileri_formu" method="post">
				<div class="sol_form_bolumu span4">
					<label for="kullanici_adi">Kullanıcı Adı</label>
					<input type="text" placeholder="Kullanıcı Adı" id="kullanici_adi" name="kullanici_adi" value="<?=$c_profile->profile->permalink?>">
					<i class="icon-exclamation-sign" id="usernameCheck" data-original-title="Bu kullanıcı adı daha önce alınmış." style="display: none;padding: 0"></i>
					<i class="icon-exclamation-sign" id="usernameValidate" data-original-title="Geçersiz kullanıcı adı." style="display: none;padding: 0"></i>
					
					<label for="ad_soyad">Ad Soyad</label>
					<input type="text" placeholder="Ad Soyad" id="ad_soyad" name="ad_soyad"  value="<?=$c_profile->profile->name?>">
					<i class="icon-exclamation-sign" id="ad_soyadValidate" data-original-title="Geçersiz isim." style="display: none;padding: 0"></i>
					
					<label for="eposta">E-posta adresi</label>
					<input type="text" placeholder="E-posta adresi" id="eposta" name="eposta" value="<?=$model->user->email?>">
					<i class="icon-exclamation-sign" id="emailCheck" data-original-title="Bu mail adresi kullanılıyor" style="display: none;padding: 0"></i>
					<i class="icon-exclamation-sign"id="emailValidate" data-original-title="Geçersiz email adresi." style="display: none;padding: 0"></i>
					
					<label for="motto">Motto</label>
					<textarea  placeholder="motto" id="motto" name="motto" style="width: 300px; height: 65px"><?=$model->profile->motto?></textarea>
					
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
						<input type="text" value="<?=model::sex2trSex($c_profile->profile->sex)?>" placeholder="Cinsiyet" name="cinsiyet" id="cinsiyet" class="span1">
						<div class="btn-group">
							<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
							<ul class="dropdown-menu pull-right">
								<li><a href="#">Erkek</a></li>
								<li><a href="#">Kadın</a></li>
							</ul>
						</div>
					</div>
                        
                    <div class="clearfix"></div>
                    <br />
					<button class="btn btn-success " type="button" id="kaydet_dgmesi">Kaydet</button>
					<div class="clearfix"></div>   
	                       

				</div><!-- /.sol_form_bolumu -->
				<br />
				<div class="sag_form_bolumu span3">
					<div class="profil_resmi">
						<img id="profileImage" alt="" src="<?=$model->getProfileImage($c_profile->profile->image,200,200,"cutout");?>">
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
					
				</div>

			</form>
			<?php
		}
		public function social_form()
		{
			global $model;
			/*-----*/
			?>
			<div style="clear:both"	></div>	
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
					
					$fb= new facebookClass();
					$izinVarmi=$fb->yazmaizniVarmi();
					//var_dump($response);
					if($izinVarmi)
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
						<?php
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
							$loginUrl=$fb->get_loginUrl('email,publish_stream,status_update');
							 
							?>
							<div class="social_connect">
		                        <a class="facebook_login" id="face_button" href="javascript:;" onclick="facebook_open_LoginWindow('<?=$loginUrl?>');">Facebook Bağla</a>
		                    </div>
							<?
						}
					
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
              <?php							
		/*-----*/
		}
		public function passwordChange_form()
		{
			?>
			<div style="clear: both;"></div>
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
            <?php
		}
		public function findFriendSocial_form()
		{
			global $model;
			?>
			<h4 class="">Twitter ve Facebook arkadaşlarından Democratus üyesi olanları bulup takip edebilirsin.</h4>
			<? 
			$c_facebook = new facebookClass;
			//var_dump($c_facebook);
			//die;
			if($model->profile->fbID)
			{
				?>
				<div class="social_connect">
                    <a class="facebook_login" id="face_button" href="javascript:;" onclick="facebook_friendFind(<?=$model->profile->fbID?>);">Facebook Arkadaşların</a>
                </div>
				<?
			}
			else 
			{
				?>
				<div class="social_connect">
                    <a class="facebook_login" id="face_button" href="javascript:;" onclick="facebook_open_LoginWindow('<?=$c_facebook->get_loginUrl();?>');">Facebook Arkadaşlarım</a>
                </div>
				<?
			}
			
			$c_twitter = new twitterClass;

			if($c_twitter->user_tokens_check()>0)
			{
			?>
				<div class="social_connect">
                    <a class="twitter_login" id="twit_buton" href="javascript:;" onclick="twitter_friendFind();">Twitter Arkadaşların</a>
                </div>
			<? 
			}
			else {
			?>
				<div class="social_connect">
                    <a class="twitter_login" id="twit_buton" href="javascript:;" onclick="twitter_open_LoginWindow('<?=$c_twitter->get_loginUrl();?>');">Twitter Arkadaşlarım</a>
                </div>
			<? 	
			}
				
			?>
            <div class="social_connect">
                <button class="btn share_with_friends ">Arkadaşlarınla Paylaş</button>
            </div>
            <div style="clear: both;"></div>
            <div class="social_connect2" style="display: none;">
                <h4>Democratus'u arkadaşlarınla paylaş.</h4>
                <textarea id="share-with-social-text" class="textarea">Siz de davetlisiniz; ulke gundemini fikirlerinizle sekillendirebileceginiz online meclis, demokratik sosyal ag</textarea>
                <div style="clear: both;"></div>
                <div class="social_connect">
                    <a class="twitter_login" onclick="javascript:share_totwit();" id="twit_buton" >Twitter'da paylaş</a>
                </div>
                <div class="social_connect">
                    <a class="facebook_login" onclick="javascript:share_tofacebook();" id="face_button" >Facebook'ta paylaş</a>
                </div>
            </div>
            <div style="clear: both;"></div>
			<div id="socialListArea">
				
			</div>
			<?php
		}
		public function afterRegister($step=1)
		{
			switch ($step) {
				case '1':
					echo '<section class="satir ilk_satir uste_cikar">';
						$this->profile_form();
						echo '<div style="clear:both;"></div><p>Democratustan en doğru şekilde faydalanmak için lütfen profil bilgilerinizi giriniz.';
					echo '</div>';
					break;
				case '2':
					echo '<section class="satir ilk_satir uste_cikar">';
						$this->hashtagSug();
						//echo '<div style="clear:both;"></div><p>Democratustan en doğru şekilde faydalanmak için lütfen profil bilgilerinizi giriniz.';
					echo '</div>';
					break;
				case '3':
					echo '<section class="satir ilk_satir uste_cikar">';
						$this->friendSugg();
						//echo '<div style="clear:both;"></div><p>Democratustan en doğru şekilde faydalanmak için lütfen profil bilgilerinizi giriniz.';
					echo '</div>';
					break;
				case '4':
					echo '<section class="satir ilk_satir uste_cikar">';
						$this->social_form();
						//echo '<div style="clear:both;"></div><p>Democratustan en doğru şekilde faydalanmak için lütfen profil bilgilerinizi giriniz.';
					echo '</div>';
					break;
				
			}
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
			echo "window.opener.location.href=window.opener.location.href; \n";
			echo "window.close();";
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
				echo "window.opener.location.href=window.opener.location.href; \n";
				echo ("window.close();");
			echo "</script>";
		}
		public function hashtagSug()
		{
			global $model;
			?>
				<h3>İlgi alanları</h3>
				<p>Democratus ta gündemden hızlıca haberdar olmak için ve gündemi şekillendirebilmek için ilgi alanlarınızı takip edin</p>
				<div id="hahtagSugg">
				</div>
				<div style="float: right; margin: 15px;">
					<button id="next_step" type="button" class="btn btn-success " onclick="location.href='/my/afterRegister/3';">Devam Et</button>
					<?//javascriptle kontrol et en az 5 hashtag takip edince bu sayfayı atla ?>
				</div>
			<?php 
			$model->addScript('$(document).ready(function (){ get_hashtagSugg(); });');
		
		}
		public function friendSugg()
		{
			global $model;
			?>
				<h3>Arkadaş önerileri</h3>
				<p>Bu kişileri takip etmek isteyebilirsiniz.</p>
				<div id="friendSugg">
				</div>
				<div style="float: right; margin: 15px;">
					<button id="next_step" type="button" class="btn btn-success " onclick="location.href='/my/afterRegister/4';">Devam Et</button>
					<?//javascriptle kontrol et en az 5 hashtag takip edince bu sayfayı atla ?>
				</div>
			<?php 
			$model->addScript('$(document).ready(function (){ get_friendSugg(); });');
		}
	}
?>