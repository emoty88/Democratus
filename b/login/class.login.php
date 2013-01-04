<?php
    class login_block extends control{
    	public function block ()
		{
			global $model;
			?>
			<section class="login" style="height: 150px; width: 530px">
    			<div class="login-box">
	       			<form>
	    				<input class="input" type="text" id="loginemail" placeholder="E-Posta Adresi">
	    				<input class="input" type="password" id="loginpass" placeholder="Şifre">
	    				<a class="btn" id="wellcome-login" href="javascript::void()">Giriş</a>
	    				<a class="forget_password" href="#">Şifremi Unuttum</a>
	    				
	    			</form>
	    			
	    			
	                <i class="or_before"></i>
	    			<span class="or">veya</span>
	    			<i class="or_after"></i>
	
	    			<div class="social_connect">
	    			
	    				<a class="facebook_login" href="#">Facebook ile Bağlan</a>
	    				<a class="twitter_login" href="#">Twitter ile Bağlan</a>
	    			
	    			</div>
    			</div>
    			<div class="login-box" style="display:none">
	    		
	    				<div style="float: left; width: 280px">
	    					<div class="social_connect">
	    						<a class="facebook_login" href="#">Facebook ile Bağlan</a>
	    					</div>
	    			
		    				<input class="input" type="text" name="name" id="name" placeholder="İsim Soyisim">
		    				<input class="input" type="text" name="userName" id="userName" placeholder="Kullanıcı Adı">
		    				<input class="input" type="text" name="email" id="email" placeholder="E-Posta Adresi">
		    				<div style="clear: both"></div>
		    				<input type="checkbox" id="agree" style="float: left" />
		    				<p style="color: white; font-size: 12px; width: 220px; padding-left: 15px"> 
								Kullanım 
								<a style="color: white;" href="http://democratus.com/about#hizmet" target="_blank">
									sözleşmesini okudum
								</a>
								, kabul ediyorum
							</p>
	    				</div>
	    				
		    			<div style="float: left; width: 240px;">
		    				<div class="social_connect">
	    						<a class="twitter_login" href="#">Twitter ile Bağlan</a>
    						</div>
		    				<input class="input" type="password" name="password" id="password" placeholder="Şifre">
		    				<input class="input" type="password" name="password2" id="password2" placeholder="Şifre Tekrar">
		    				<select name="male" id="selectB" tabindex="11">  
								<option value="unknow">Cinsiyet</option>
								<option value="male">Erkek</option>
								<option value="female">Kadın</option>
							</select>
							
		    				<a class="btn" id="registerBtn" href="javascript::void()">Kayıt Ol</a>
		    			</div>
    							
    			</div> 
    			  			
    		</section>
    		<div>
				<p id="message"></p>
			</div> 

			<?php
			
		}
		
	}