<?php
    class welcome_plugin extends control{
    	
		public function main(){
                        model::checkLogin(1, '/', 1);
			global $model, $db;
			
			$model->title = 'The Political Network';
            $model->description = 'by Which You Can Shape Your World';

       		//print_r($_COOKIE);
            $model->initTemplate('ala','welcome');
            $model->view = 'welcome';

			$model->addScript("http://code.jquery.com/jquery-1.10.1.min.js","jquery-1.10.1.min.js",1);
			$model->addScript(TEMPLATEURL.'ala/js/bootstrap.min.js','bootstrap.min.js',1);
			$model->addScript(TEMPLATEURL.'ala/js/script.js','script.js',1);
			$model->addScript(TEMPLATEURL.'ala/js/lib/jquery.mousewheel-3.0.6.pack.js','jquery.mousewheel-3.0.6.pack.js',1);
			$model->addScript(TEMPLATEURL.'ala/js/fancybox/jquery.fancybox.js','jquery.fancybox.js',1);
			$model->addScript("
				$(document).ready(function() {
					$('.fnc').fancybox();
				});
			");

            $model->addScript($model->pluginurl . 'welcome.js', 'welcome.js', 1);
            $model->addScript("paths=".json_encode($model->paths));
            $model->addScript("plugin='welcome'");
			$model->addScript("redirect='/'");
			if(isset($_SERVER['HTTP_REFERER']))
			{
				$urlD = parse_url($_SERVER['HTTP_REFERER']);
				if(($urlD["host"]== "democratus.com" || $urlD["host"]== "www.democratus.com") && $urlD["path"]!= "/popularvoice" )
				{
					$model->addScript("redirect='".$urlD["path"]."'");
				}
			}
			
            ?>
                <header id="header" class="site-header">
			        <div class="container">
			          <div class="row-fluid">
			            <div class="span12">
			              <h1><a class="site-title pull-left" href="/"> Democratus</a></h1>
			              <div class="user text-right">
			                <a class="user-login fnc" href="#login" >Giriş</a> | 
			                <a class="user-regidter fnc" href="#register">Kayıt Ol</a>
			              </div>
			            </div><!-- /span12 -->
			          </div><!-- /row-fluid -->
			        </div><!-- /container -->
			      
			      <div class="container" style="padding-bottom: 40px;">
			        <div class="row-fluid">
			          <div class="span12">
			          
			            <div class="header-description">
			              <h2 style="line-height: 85px;">Fikirlerinle Dünyayı sekillendir!</h2>
			              <!--<a class="header-btn" href="#">Join now</a> -->
			            </div><!-- /header-description -->
			            
			            <div class="header-features">
			              <div class="row-fluid">
			                <div class="span6 header-feature">
			                  <h3><i class="icon-random"></i> &nbsp; Fikirlerini Paylas.</h3>
			                  <p>Fikirlerini paylaş sesini tüm ülke duysun. Referanduma oy ver ülke gündemini değiştir.</p>
			                </div>
			                <div class="span6 header-feature">
			                  <h3> <i class="icon-group"></i> &nbsp; Yönetime katıl.</h3>
			                  <p>#konu ve #kurum sayfalarıyla, yönetimlere yardımcı ol. Vekil seçilerek gündemi sen belirle.</p>
			                </div>
			              </div>
			            </div><!-- /header-features -->
			            
			          </div><!-- /span12 -->
			        </div><!-- /row-fluid -->
			      </div><!-- /container -->
			    </header>
			    
			
			
			    <section id="testimonials" class="testimonials">
			      <div class="container">
			      
			        <div id="testimonials-carousel" class="carousel slide">
			          <div class="carousel-inner">
			             {{welcomeagenda}}
			          </div>
			          <a class="carousel-control left" href="#testimonials-carousel" data-slide="prev">&lsaquo;</a>
			          <a class="carousel-control right" href="#testimonials-carousel" data-slide="next">&rsaquo;</a>
			        </div><!-- /testimonials-carousel -->
			        
			      </div><!-- /container -->
			    </section>   
			    
			    
			    <footer id="footer" class="footer">
			      <div class="container">
			        <div class="row-fluid">
			          <div class="span12">
			            <h1 class="footer-title pull-left"><a href="/"> Democratus</a></h1>
			            
			            <p class="pull-right copyright">
			              &copy Tüm hakları saklıdır.
			            </p>
			          </div>
			        </div>
			      </div>
			    </footer>
				<div style="display:none;">
					<div id="login" class="login-form" style="margin-left:0;">
					
						<h1>Giriş Yap</h1> 
						<p id="welcomeLogin-error" class="welcomeError" style=""></p>
						<ul class="social-btn"> 
							<li class="fb">
								<span>Facebook ile bağlan</span>
							</li>
							<li class="tw">
								<span>Twitter ile bağlan</span>
							</li>
						</ul>
						<ul class="login-List">
							<li><input class="inputText" id="login-user" type="text" placeholder="Kullanıcı adı - E Posta"/></li>
							<li><input class="inputText" id="login-pass" type="Password" placeholder="Parola"/></li>
						</ul>
						<div class="formFooter">
							<div class="formFooterButtons">
								<button type="submit" class="btn btn-success" style="float:right; padding: 10px 20px; margin-right:4px;">
									<span id="login-action" class="buttonText">Giriş Yap</span>
								</button>
							</div>
							<div class="bootom-Link">
								<a href="#forgotPass" class="forgotPassword fnc">Şifremi unuttum</a>
								<br> 
								<a class="fnc" href="#register">Kayıt ol</a>
							</div>
						</div>
					</form>
					</div>
					<div id="register" class="login-form" style="margin-left:0;">
					<form id="register-form" >	
						<h1>Kayıt Ol</h1> 
						<p id="welcomeRegister-error" class="welcomeError" style=""></p>
						<ul class="login-List">
							<li><input id="register-name" class="inputText" type="text" placeholder="Ad Soyad"/></li>
							<li><input id="register-user" class="inputText" type="text" placeholder="Kullanıcı Adı"/></li>
							<li><input id="register-email" class="inputText" type="text" placeholder="E- Posta"/></li>
							
							<li><input id="register-pass" class="inputText" type="text" placeholder="Parola"/></li>
							<li><input id="register-pass2" class="inputText" type="text" placeholder="Tekrar Parola"/></li>
							<li>
								<select id="register-sex" class="selectBox" >
									<option value="unknow" selected="true">Cinsiyet</option>
									<option value="male">Erkek</option>
									<option value="female">Kadın</option>
								</select>
							</li>
							<li>
								<input type="checkbox" style="float: left; margin-left: 15px;" id="agree">
								<p style=""> 
									Kullanım 
									<a target="_blank" href="http://democratus.com/privacy" style="color:#db522f;font-family: Helvetica;">
										sözleşmesini okudum
									</a>
									, kabul ediyorum.
								</p>
							</li>
						</ul>
						<div class="formFooter">
							<div class="formFooterButtons">
								<button type="submit" class="btn btn-success" style="float:right; padding: 10px 20px; margin-right:4px;">
									<span class="buttonText">Kayıt</span>
								</button>
							</div>
							<div class="bootom-Link">
								<a href="#forgotPass" class="forgotPassword fnc">Şifremi unuttum</a>
								<br> 
								<a class="fnc" href="#login">Giriş Yap</a>
							</div>
						</div>
					</form>
					</div>	
					<div id="forgotPass" class="login-form" style="margin-left:0;">
						<h1>Şifremi Unuttum</h1> 
						<ul class="social-btn"> 
							<li class="fb">
								<span>Facebook ile bağlan</span>
							</li>
							<li class="tw">
								<span>Twitter ile bağlan</span>
							</li>
						</ul>
						<ul class="login-List">
							<li><input class="inputText" type="text" placeholder="E Posta"/></li>
							
						</ul>
						<div class="formFooter">
							<div class="formFooterButtons">
								<button type="submit" class="btn btn-success" style="float:right; padding: 10px 20px; margin-right:4px;">
									<span class="buttonText">Şifre Yenile</span>
								</button>
							</div>
							<div class="bootom-Link">
								<a href="#forgotPass" class="forgotPassword fnc">Şifremi unuttum</a>
								<br> 
								<a class="fnc" href="#register">Kayıt ol</a>
							</div>
						</div>
					</div>
				</div>
            <?php
			
		}
    }
?>
