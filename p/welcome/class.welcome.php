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

            $model->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
           	// $model->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery.tmpl.js", "jquery.tmpl.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery-ui.js", "jquery-ui.js", 1);

            $model->addScript($model->pluginurl . 'welcome.js', 'welcome.js', 1);
            $model->addScript("paths=".json_encode($model->paths));
            $model->addScript("plugin='welcome'");
            if(isset($_GET['register']))
                $model->addScript("$(document).ready(function()".'{'."$('#wellcome-register-button').click()});");	
			
		}
        
        public function main_old(){
            global $model, $db;
            
            $model->title = 'The Political Network';
            $model->description = 'by Which You Can Shape Your World';
            
            //print_r($_COOKIE);
            $model->initTemplate('v2','welcome');
            $model->view = 'wellcome';
           
            //$model->addScript(PLUGINURL . 'lib/en.js', 'en.js', 1 );
            //$model->addScript(PLUGINURL . 'lib/'.$model->language.'.js', $model->language.'.js', 1);
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1 );
            $model->addScript($model->pluginurl . 'welcome.js', 'welcome.js', 1 );

			$model->addScript(TEMPLATEURL . 'v2/static/js/custom-form-elements.js', 'custom-form-elements.js', 1 );
            $model->addStyle(TEMPLATEURL . 'v2/static/css/bootstrap.css', 'bootstrap.css', 1 );
            $model->addStyle(TEMPLATEURL . 'v2/static/css/bootstrap-responsive.css', 'bootstrap-responsive.css', 1 );
            $model->addStyle($model->pluginurl  . 'welcome.css', 'welcome.css', 1 );
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            //$model->addStyle(TEMPLATEURL . 'default/form.css', 'jquery-ui.css', 1 );
            
        	if($model->paths[1]=="signin")
			{
				$model->addScript("$(document).ready(function(){formChange('formLogin'); });");
			}
            
            //require_once(PLUGINPATH.'lib/recaptcha/recaptchalib.php'); 
            
?>
<div id="changeButon" style="position: relative;    z-index: 10;">
		<input type="button" id="girisBtn" class="girisBtn" value="Giriş Yap"  style="display:none;" onclick="formChange('form');" />
		<input type="button" id="kaydolBtn" class="kaydolBtn" value="Kayıt Ol" onclick="formChange('formLogin');" />
	</div>
	<div id="welcomeCenter" >
		<img src="<?=TEMPLATEURL?>/v2/static/images/welcomeCenter.png" />
	</div>
	<div id="logo">
		<img src="<?=TEMPLATEURL?>beta/img/loginLogo.png" />
	</div>	
	<div id="motto">
		<img src="<?=TEMPLATEURL?>/v2/static/images/motto.png" />
	</div>
	<div id="welcomeSocialBtn"> 
		<div id="faceicon" onclick="javascript:location.href='/oauth/facebook';"></div>
		<div id="twittericon" onclick="javascript:location.href='/oauth/twitter';"></div>
	</div>
	<div id="form">
	<form action="" method="post" id="loginF"> 
		
		<div style="clear:both;"></div>
		<input type="text" tabindex="2" name="email" id="loginemail" value="E-Posta:" onFocus="if(this.value=='E-Posta:')this.value='';" onblur="if(this.value=='') this.value='E-Posta:'; " >
		
		<input type="text" id="loginpass_show"  tabindex="2" class="focus" value="Parola" rel="loginpass"  name="pw" />
		<input type="password" id="loginpass" name="password" value="" class="blur" style="display:none;" />
		
		<span id="forget_password" class="forget_password">
			<a href="javascript:;" class="dlink" style="float:right">Şifremi Unuttum</a>
		</span>
		<div style="clear:both;"></div> 
		<input type="submit"  id="loginBtnA"  class="girisBtn" value="Giriş Yap" />
	</form>
	</div>	
	<div id="formLogin"> 
	<form id="applyform" action="" method="post">
		<div style="clear:both;"></div>
		<input type="text" name="name" tabindex="5" value="İsim Soyisim:" onFocus="if(this.value=='İsim Soyisim:')this.value='';" onblur="if(this.value=='') this.value='İsim Soyisim:'; " >
		<input type="text" name="userName" tabindex="5" value="Kullanıcı Adı:" onFocus="if(this.value=='Kullanıcı Adı:')this.value='';" onblur="if(this.value=='') this.value='Kullanıcı Adı:'; " >
		<input type="text" name="email" tabindex="6" value="E-Posta:" onFocus="if(this.value=='E-Posta:')this.value='';" onblur="if(this.value=='') this.value='E-Posta:'; " >
		<input type="text" id="password_show"  tabindex="7" class="focus" value="Parola" rel="password"  name="pw" />
		<input type="password" tabindex="8" name="password" id="password" value="" class="blur" style="display:none;"/>
		<input type="text" id="password2_show"  tabindex="9" class="focus" value="Parola Tekrar" rel="password2" name="pw2" />
		<input type="password" tabindex="10" name="password2" id="password2" value="" class="blur" style="display:none;"/>
		<select id="selectB" name="male" tabindex="11">  
			<option value="unknow">Cinsiyet</option>
			<option value="male">Erkek</option>
			<option value="female">Kadın</option>
		</select>
		<input type="checkbox" name="sozlesmeyiOkudum" id="agree" class="styled" value="1" tabindex="12" />
		<label for="agree" id="soLabel"> Kullanım <a href="http://democratus.com/about#hizmet" target="_blank">sözleşmesini okudum</a>, kabul ediyorum</label>
		<input type="button" id="registerBtn" class="kaydolBtn" value="Kayıt Ol" tabindex="13"/>	
	</form>
	<div id="message" style="display:none; color:#fff; margin-top:5px;">
		
	</div>
	</div>
<div style="clear:both;"></div>
	<div class="footer">
		<img src="<?=TEMPLATEURL?>/v2/static/images/hr.png" align="center" />
		<div class="footerContent">
		<div style="margin-left:35px; float:left;">
			<a href="https://www.facebook.com/democratustr" target="_blank"><img src="<?=TEMPLATEURL?>/v2/static/images/fbminiicon.png" /></a>
			<a href="https://twitter.com/#!/democratus_tr" target="_blank"><img src="<?=TEMPLATEURL?>/v2/static/images/twminiicon.png" /></a>
		</div>
			<div style="float:right; color:#ccc;">
				<a href="/about">Democratus</a> | 
				<a href="/contact">İletişim</a>  
			</div>
		</div>
	</div>
	
<?php
        }
        public function main1(){
            global $model, $db;
            
            //print_r($_COOKIE);
            
            $model->view = 'wellcome';
            $model->addScript(PLUGINURL . 'lib/en.js', 'en.js', 1 );
            $model->addScript(PLUGINURL . 'lib/'.$model->language.'.js', $model->language.'.js', 1);
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1 );
            $model->addScript($model->pluginurl . 'wellcome.js', 'wellcome.js', 1 );
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            require_once(PLUGINPATH.'lib/recaptcha/recaptchalib.php');
            
?>
<!--wellcome-->
<div id="wellcome">
  <!--wellcome-head-->
  <div id="wellcome-head">
    <div id="wellcome-headleft">Yeni Anket: <strong>Libya Krizinde Türkiye’nin Tavrı Yerinde mi?</strong></div>
    <div id="wellcome-headright">
      <input type="text" name="wellcome-search-input" id="wellcome-search-input" value="" />
      <input type="button" name="gosearch" id="wellcome-gosearch" value="" />
    </div>
  </div>
  <!--wellcome-head END-->
  <div id="wellcome-spot">
    <h1>Gündemi Belirle, Düşüncelerini Paylaş</h1>
  </div>
  <div id="wellcome-underlogo">
    <div id="wellcome-underlogo-newfriends">Democratus ile yeni <a href="#">arkadaşlar edinin.</a></div>
    <div id="wellcome-underlogo-sharemind">Düşüncelerinizi tüm <a href="#">dünya ile paylaşın.</a></div>
    <div id="wellcome-underlogo-makeagenda">Gündemi siz <a href="#">belirleyin.</a></div>
  </div>
  <div id="loginbox">
      <form action="/user/login/" method="post" id="loginform" onsubmit="" >
        <input type="text" id="loginemail" value="" class="logininput" />
        <input type="password" id="loginpass" value="" class="logininput" />
        <input type="submit" value="GİRİŞ" id="wellcome-login" />
      </form>
    <div id="loginbox-forget">&gt;<a href="#">Şifremi Unuttum</a></div>
  </div>
  <div id="wellcome-register">
    <h3 id="wellcome-register-title">Kaydolun, Gündemi Siz Belirleyin. </h3>
    <div id="wellcome-register-button">Ücretsiz Democratus Hesabı <a href="#">Oluşturun.</a></div>
    <div id="wellcome-registerbox" style="display: none;">
      <form action="/user/new/" method="post" class="form" id="applyform">
        <span class="message">all fields are required</span>
        <p>
          <label>E-mail</label>
          <input type="text" id="email" name="email" value="" autocomplete="off" />
          
        </p>
        
        <p>
          <label>Password</label>
          <input type="password" id="password" name="password" value="" class="small" autocomplete="off" />
          
        </p>        
        
        <p>
          <label>Password Again</label>
          <input type="password" id="password2" name="password2" value="" class="small" autocomplete="off" />
          
        </p>
              
      
        <p><label>Name</label>
        <input type="text" name="name" value="" autocomplete="off" />
        </p>

        <p><label>Motto</label>
        <textarea cols="5" rows="5" name="motto" id="motto"></textarea>
        </p>

        <p><label>Country</label>
        <?php echo $model->country_to_select('country', 146)?>
        
        </p>

        <p><label>City</label>
        <?php echo $model->city_to_select('city', 146, 9928)?>
        
        </p>

        <p>
            <?php echo recaptcha_get_html("6LdXIQsAAAAAANstV0tV1XiVkrrCMZTegsaIJsRz"); ?>
        </p>
        
        <p id="wellcomeagree">
          <label>&nbsp;</label>
          <input type="checkbox" value="1" name="agree" id="agree" />Üyelik kurallarını okumuş gibi yaptım.
        
        </p>
        
        
        <p>
          <label>&nbsp;</label>
          <input type="submit" value="Kaydol" id="applybutton" disabled="disabled" />
        </p>
      </form>
    </div>
  </div>
</div>
<!--wellcome END-->
<?php
        }
    }
?>
