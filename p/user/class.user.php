<?php
    class user_plugin extends control{
        
        public function main(){
            global $model, $db;

            if($model->userID > 0 ){
                switch ($model->paths[1]) {
                    case 'login': $this->login(); break;
                    case 'logout': $this->logout(); break;
					case 'logoutNoRedi': $this->logout(false); break;
                    case 'ajax': $this->ajax(); break;
                    case 'activate': $this->logout(0); $this->activate(); break;
                    case 'emailactivate': $this->emailactivate(); break;
                    default: $model->redirect('/my/');
                } 
            } else {
                switch ($model->paths[1]) {
                    case 'login': $this->login(); break;
                    case 'logout': $this->logout(); break;
                    case 'logoutNoRedi': $this->logout(false); break;
                    case 'new': $this->newuser(); break;
                    case 'ajax': $this->ajax(); break;
                    case 'activate': $this->activate(); break;
                    case 'emailactivate': $this->emailactivate(); break;
                    case 'resetpassword': $this->resetpassword(); break;
                    case 'newusersave': $this->newusersave(); break;
                    default: $model->redirect('/');
                } 
                
            }
            
        }
        
        public function login(){
            global $model, $db;
            
            if (($_SERVER['REQUEST_METHOD']=='POST') && isset($_POST['user']) && isset($_POST['pass'])){
                $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_EMAIL);
                $pass = filter_input(INPUT_POST, "pass", FILTER_SANITIZE_STRING); 
                //echo $user . '#' . $pass;
                       
                $pass = md5(KEY . trim( $pass ) );
                
                //die ($pass);
                
                $SELECT = "SELECT u.*";
                $FROM   = "\n FROM #__user AS u";
                $WHERE  = "\n WHERE u.email=".$db->quote($user)." AND u.pass = ".$db->quote($pass);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                
                $result = null;
                if( $db->loadObject($result) ){
                    
                    
                    //login ol
                    $session = new stdClass;
                    
                    $session->sid        = md5( KEY . session_id() . uniqid() );
                    $session->ip        = ip2long( filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING ) );
                    $session->timeout   = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT)); //saniye
                    $session->starttime = date('Y-m-d H:i:s');
                    $session->endtime   = date('Y-m-d H:i:s');
                    $session->userID    = $result->ID;
                    $session->profileID = $result->profileID;
                    $session->status    = 1;
                    
                    if($db->insertObject('session', $session)){
                        //logged in
                        
                        //oturum idsini cookieye yaz
                        setcookie("sid", $session->sid, time()+intval(SESSIONTIMEOUT), COOKIEPATH, COOKIEDOMAIN);
                        
                        //print_r($_COOKIE);
                        //echo $session->sid;
                        
                        //die;
                        //yönlendirme gerekiyorsa yönlendir
                        $redirecturl = filter_input(INPUT_COOKIE, 'redirecturl', FILTER_SANITIZE_URL);
                        
                        if(strlen($redirecturl)){
                            unset( $_COOKIE['redirecturl'] );
                            $model->redirect($redirecturl, 1); die;
                        } else {
                            $model->redirect('/', 1); die;
                        }
                        
                        
                    } else {
                        //not logged in
                        throw new Exception('Kullanıcı adı veya şifre hatalı');
                    }
                    
                    /*
                    $result->sid        = md5( KEY . session_id() . uniqid() );
                    $result->sip        = ip2long( filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING ) );
                    $result->stimeout   = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT)); //saniye
                    
                    
                    //oturum bilgilerini dbye yaz
                    if($db->updateObject('#__user', $result, 'ID')){
                        
                        //oturum idsini cookieye yaz
                        setcookie("sid", $result->sid, time()+intval(SESSIONTIMEOUT), COOKIEPATH, COOKIEDOMAIN);
                        
                        //yönlendirme gerekiyorsa yönlendir
                        
                        $redirecturl = filter_input(INPUT_COOKIE, 'redirecturl', FILTER_SANITIZE_URL);
                        
                        if(strlen($redirecturl)){
                            unset( $_COOKIE['redirecturl'] );
                            $model->redirect($redirecturl, 1); die;
                        } else {
                            $model->redirect('/', 1); die;
                        }
                        
                    }
                    */
                    return;                
                } else {
                    throw new Exception('Kullanıcı adı veya şifre hatalı');
                }
            }
            
?>
<form name="loginform" id="loginform" action="/user/login/" method="post" class="form">
  <p>
    <label>E-mail</label>
    <input type="text" name="user" id="user" value="" class="small" />
  </p>
  <p>
    <label>Password</label>
    <input type="password" name="pass" id="pass" value="" />
  </p>
  <p>
    <label>&nbsp;</label>
    <input type="submit" name="submit" id="submit" value="Login" /> &nbsp; &nbsp; <a href="/user/forgetpassword">Şifremi Unuttum</a>
  </p>
  <p>
    
  </p>
</form>
<?php            
            
            
            
        }
        
        public function logout($redirect=1){
            global $model, $db, $dbez;
            
            if($model->userID>0){
                $query = "UPDATE user"
                    . "\n SET `sid` = NULL, `stimeout` = NULL"
                    . "\n WHERE ID = ".$db->quote( $model->userID )
                    . "\n LIMIT 1"
                    ;
                
                $db->setQuery( $query );
                $db->uquery();
            }
            
            @session_regenerate_id(true);
            @session_unset();
            @session_destroy();
            
            setcookie("sid", '', time()-60*60*24*10, COOKIEPATH, COOKIEDOMAIN);
			KM::identify($model->user->email);
			KM::record('logout');
            if($redirect) $model->redirect('/');
        }
        
        public function activate(){
            global $model, $db;
            $model->initTemplate('simple');
            $model->title = 'New user | Democratus.com';
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript($model->pluginurl . 'user.js', 'user.js', 1 );
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addStyle(TEMPLATEURL . 'default/form.css', 'form.css', 1 );
            
            $activatekey = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($model->paths[2], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
            try{
                
                $SELECT = "SELECT ur.*";
                $FROM   = "\n FROM userrequest AS ur";
                $WHERE  = "\n WHERE ur.key=".$db->quote($activatekey);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                
                $request = null;
                if( $db->loadObject($request) ){
                    
                    //die($request->email);
                    
                    if($request->status==1) 
                        throw new Exception('zaten aktive edilmiş');
                     
                    $request->status=1;
                    if( !$db->updateObject('userrequest', $request, 'ID'))
                        throw new Exception('aktive edilirken hata oldu.');
                    
                    //üyeyi ve profili aktive et
                    $db->setQuery("SELECT * FROM user WHERE email=".$db->quote($request->email));
                    $user = null;
                    if($db->loadObject($user)){
                        //kullanıcı bulundu, aktive et
                        if($user->status==1) 
                            throw new Exception('kullanıcı zaten aktif');
                        
                        $user->status = 1;
                        if($db->updateObject('user', $user, 'ID')){
                            //echo '<h3>Aktivasyon başarılı</h3>';
                            
                        } else throw new Exception('aktivasyon sırasında bir hata oluştu');
                        
                        
                        $db->setQuery("SELECT ID FROM profile WHERE ID = " . $db->quote($user->ID));
                        
                        $profile = null;
                        if($db->loadObject($profile)){
                            $profile->status = 1; 
                            
                            if($db->updateObject('profile', $profile, 'ID')){
                            echo '<h3>Aktivasyon başarılı, giriş yapmak için lütfen <a href="/"> tıklayınız</a> </h3>';
                            
                            } else throw new Exception('profile aktivasyon sırasında bir hata oluştu');
                            
                            
                        }
                        
                    }
                    
                    
                    
                    
                    //isteği sil
                    
                    
                } else {
                    //request not found
                    throw new Exception('yok ki!');
                }
            } catch (Exception $e){
                if(DEBUG==1)
				{
					echo "<pre>";
					var_dump($e);
					echo "</pre>";
					die;
				}
                $model->redirect('/welcome');
				
            }
            
              
        }
        
        
        public function emailactivate(){ //die;
            global $model, $db;
            $model->initTemplate('simple');
            $model->title = 'Email change | Democratus.com';
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript($model->pluginurl . 'user.js', 'user.js', 1 );
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addStyle(TEMPLATEURL . 'default/form.css', 'form.css', 1 );
            
            $activatekey = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($model->paths[2], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
            try{
                
                $SELECT = "SELECT er.*";
                $FROM   = "\n FROM emailchangerequest AS er";
                $WHERE  = "\n WHERE er.key=".$db->quote($activatekey);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                
                $request = null;
                if( $db->loadObject($request) ){
                    
                    //die($request->email);
                    
                    if($request->status>0) 
                        throw new Exception('zaten aktive edilmiş');
                    
                    $request->status=1;
                    if( !$db->updateObject('emailchangerequest', $request, 'ID'))
                        throw new Exception('aktive edilirken hata oldu.');
                    
                    //üyeyi ve profili aktive et
                    $db->setQuery("SELECT * FROM user WHERE ID=".$db->quote($request->userID));
                    $user = null;
                    if($db->loadObject($user)){
                        //kullanıcı bulundu, aktive et
                        /*
                        if($user->status!=0) 
                            throw new Exception('kullanıcı zaten aktif');
                        */
                        
                        $user->email = $request->email;
                        if($db->updateObject('user', $user, 'ID')){
                            //echo '<h3>Aktivasyon başarılı</h3>';
                            
                            echo '<h3>Adres değişikliği onaylandı, devam etmek için lütfen <a href="/"> tıklayınız</a> </h3>';
                            
                        } else throw new Exception('değişiklik sırasında bir hata oluştu');

                        
                    }
                    
                    
       
                    
                } else {
                    //request not found
                    throw new Exception('yok ki!');
                }
            } catch (Exception $e){
                $model->redirect('/');
            }
            
              
        }
        
        
        public function resetpassword(){ //die('aaa');
            global $model, $db;
            $model->initTemplate('simple');
            $model->title = 'Şifre Yenileme | Democratus.com';
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript($model->pluginurl . 'user.js', 'user.js', 1 );
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addStyle(TEMPLATEURL . 'default/form.css', 'form.css', 1 );
            
            do{
                try{
                    
                    $key = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($model->paths[2], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        
                    $SELECT = "SELECT rp.*";
                    $FROM   = "\n FROM resetpassword AS rp";
                    $WHERE  = "\n WHERE rp.key=".$db->quote($key);
                    $WHERE .= "\n AND rp.status>0";
                    //$WHERE .= "\n AND rp.datetime=".$db->quote($request->email);
                    $LIMIT  = "\n LIMIT 1";
                    
                    $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                    
                    $request = null;
                    if( ! $db->loadObject($request) ) throw new Exception('kod eksik yada hatalı!');
                    
                    
                    
                    $SELECT = "SELECT u.*";
                    $FROM   = "\n FROM user AS u";
                    $WHERE  = "\n WHERE u.ID=".$db->quote($request->userID);
                    //$WHERE .= "\n AND u.profileID=".$db->quote($request->profileID);
                    $WHERE .= "\n AND u.email=".$db->quote($request->email);
                    $WHERE .= "\n AND u.status>0";
                    $LIMIT  = "\n LIMIT 1";
                    
                    $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                    
                    $user = null;
                    if( ! $db->loadObject($user) ) throw new Exception('kod eksik veya hatalı 2');
                    
                    
                    
                    
                    if (($_SERVER['REQUEST_METHOD']=='POST') && isset($_POST['password']) && isset($_POST['password2'])){
                        $form->password     = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $form->password2    = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password2'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        
                        if(strlen($form->password)<4) throw new Exception('şifreniz çok kısa!');
                        if($form->password!=$form->password2) throw new Exception('şifreleriniz uyuşmuyor!');
                        
                        $newpassword =  md5(KEY . trim( $form->password ));
                        
                        $user->pass = $newpassword;
                        
                        if($db->updateObject('user', $user, 'ID', 0)){
                            echo '<h3>Yeni şifreniz kaydedildi</h3>';
                            
                            echo '<p style="font-size:80%">Ana sayfaya gitmek için <a href="/"> tıklayınız. </a></p>';
                            
                            //$request->status = 0;
                            //$db->updateObject('resetpassword', $request, 'ID', 0);
                            $db->setQuery('UPDATE resetpassword SET status=0 WHERE email='.$db->quote($request->email));
                            $db->uquery();
                            
                            
                        } else {
                            throw new Exception('Kayıt hatası oluştu!');
                        }
                        
                        
                        
                        
                        
                        
                        return;
                    }
                } catch (Exception $e){
                    //ana sayfaya kaç
                    echo '<h4>' . $e->getMessage() . '</h4>';
                    echo '<p style="font-size:80%"> geri gitmek için <a href="'.$model->pageurl.'resetpassword/.'.$key.'" onclick="history.back(); return false;"> tıklayınız. </a><br />Ana sayfaya gitmek için <a href="/"> tıklayınız. </a></p>';
                    
                    
                    
                    return;
                    
                }
            } while(0);
                
?>

            <form action="<?=$model->pageurl;?>resetpassword/<?=$key?>" method="post" class="form">
            <p>
            <label>Yeni Şifreniz</label>
            <input type="password" name="password" id="password" value="" />
            </p>
            
            <p>
            <label>Tekrar</label>
            <input type="password" name="password2" id="password2" value="" />
            </p>
            <p>
            
            <label>&nbsp;</label>
            <input type="submit" value="Şifrem bu olsun" />
            </p>
            
            </form>

<?php                
                
                
            
            
             return;
            
            //keyi bul
            
            //kullanıcıyı bul
            
            //şifre ekranını göster
            
            
            
            
            
            try{
                
                $SELECT = "SELECT rp.*";
                $FROM   = "\n FROM resetpassword AS rp";
                $WHERE  = "\n WHERE rp.key=".$db->quote($key);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                
                $request = null;
                if( $db->loadObject($request) ){
                    
                    //die($request->email);
                    
                    if($request->status>0) 
                        throw new Exception('zaten aktive edilmiş');
                    
                    $request->status=1;
                    if( !$db->updateObject('userrequest', $request, 'ID'))
                        throw new Exception('aktive edilirken hata oldu.');
                    
                    //üyeyi ve profili aktive et
                    $db->setQuery("SELECT * FROM user WHERE email=".$db->quote($request->email));
                    $user = null;
                    if($db->loadObject($user)){
                        //kullanıcı bulundu, aktive et
                        if($user->status!=0) 
                            throw new Exception('kullanıcı zaten aktif');
                        
                        $user->status = 1;
                        if($db->updateObject('user', $user, 'ID')){
                            //echo '<h3>Aktivasyon başarılı</h3>';
                            
                        } else throw new Exception('aktivasyon sırasında bir hata oluştu');
                        
                        
                        $db->setQuery("SELECT * FROM profile WHERE ID = " . $db->quote($user->profileID));
                        
                        $profile = null;
                        if($db->loadObject($profile)){
                            $profile->status = 1; 
                            
                            if($db->updateObject('profile', $profile, 'ID')){
                            echo '<h3>Aktivasyon başarılı, giriş yapmak için lütfen <a href="/"> tıklayınız</a> </h3>';
                            
                            } else throw new Exception('profile aktivasyon sırasında bir hata oluştu');
                            
                            
                        }
                        
                    }
                    
                    
                    
                    
                    //isteği sil
                    
                    
                } else {
                    //request not found
                    throw new Exception('yok ki!');
                }
            } catch (Exception $e){
                //$model->redirect('/wellcome');
            }
            
              
        }
        
        public function newuser_asdfasdf(){ 
            global $model, $db;
            $model->title = 'New user | Democratus.com';
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript($model->pluginurl . 'user.js', 'user.js', 1 );
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addStyle(TEMPLATEURL . 'default/form.css', 'form.css', 1 );
            
            $model->initTemplate('v2');
            
            //if user logged in then exit
            
            
            
            if($_SERVER['REQUEST_METHOD']=='POST'){
                //new user save;
                try{
                
                    if($_SERVER['REQUEST_METHOD']!='POST') throw new Exception('post');

                    $form            = new stdClass;
                    $form->email     = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $form->pass      = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );


                    $form->name      = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $form->motto     = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'motto', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    
                    $form->countryID = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_NUMBER_INT);
                    $form->cityID    = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_NUMBER_INT);                

                    if(!isEmail($form->email))
                        throw new Exception('email adresi geçerli değil',1);
                    
                    //bu mail kayıtlı mı?
                    $db->setQuery("SELECT COUNT(email) FROM user WHERE email=".$db->quote($form->email));
                    if(intval($db->loadResult())>0)
                        throw new Exception('email adresi kayıtlı, şifrenizi yenileyebilirsiniz',1);
                        
                    if($form->pass != strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'password2'), ENT_QUOTES), ENT_QUOTES, 'UTF-8') ) )
                        throw new Exception('şifreleriniz aynı değil', 1);
                    
                    //bilgiler normal mi?
                    
                    
                    //kaydet
                    
                    
                    
                    $profile = new stdClass;
                    
                    $profile->name = $form->name;
                    $profile->motto = $form->motto;
                    $profile->countryID = $form->countryID;
                    $profile->cityID = $form->cityID;
                    $profile->status = 0;
                    $profile->class = 1;
                    
                    
                    
                    

                    if( $db->insertObject('profile', $profile ) ){
                        echo 'profile ok';
                        
                        $user = new stdClass;
                        $user->email = $form->email;
                        $user->pass = md5(KEY . trim( $form->pass ));
                        $user->status = 0;
                        $user->profileID = $db->insertid();
                        
                        if( $db->insertObject('user', $user ) ){
                            echo 'user ok';
                            
                            $pr = new stdClass;
                            $pr->ID        = $user->profileID;
                            $pr->userID    = $db->insertid();
                            
                            $db->updateObject('profile', $pr, 'ID');
                            
                            
                            
                            $request = new stdClass;
                    
                            $request->email     = strtolower( trim( $user->email ) );
                            $request->key       = md5( KEY . time() . uniqid() );
                            $request->ip        = ip2long( filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING ) );
                            $request->datetime  = date('Y-m-d H:i:s');
                            $request->status    = 0;
                            
                            if($db->insertObject('userrequest', $request)){
                                $response['status'] = 'success';
                                mail($request->email, 'başvuru onayı', 'onay linki: http://democratus.com/user/activate/'.$request->key);
                                echo '<h3>Kayıt başarılı. mailinizi kontrol ediniz.</h3>';
                                
                            } else {
                                throw new Exception('kayıt hatası');
                            }
                            
                            
        
                            
                        }
                        
                        
                    }
                    
                    
                    //aktivasyon emaili gönder
                    
                    
                    //var_dump($profile);
                    //die('save');
                    
                } catch (Exception $e){
                     //mesaj üret ve formu göstert
                     
                     
                     $this->newuserform($form, $e->getMessage(), $e->getCode());
                        
                }                
                
            } else {
                //boş form göstert                
                $profile = new stdClass;
                $profile->email= '';
                $profile->password = '';
                $profile->name  = '';
                $profile->motto = '';
                $profile->countryID = 146; //turkey
                $profile->cityID = 9928; //istanbul 
                
                $this->newuserform($profile, '');
            }
        }
        
        private function newuserform_asdfasdf($profile, $message='', $code=0){
            global $model, $db;
            
            require_once(PLUGINPATH.'lib/recaptcha/recaptchalib.php');
            
            if(strlen($message)){
?>
<div class=""><?=$message?></div>
<?php
            }
?>

<form action="/user/new/" method="post" class="form" id="newuserform" onsubmit="" >

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
<input type="text" name="name" value="<?=$profile->name?>" />
</p>

<p><label>Motto</label>
<textarea cols="5" rows="5" name="motto" id="motto"><?=$profile->motto?></textarea>
</p>

<p><label>Country</label>
<?php echo $model->country_to_select('countryID', $profile->countryID)?>
</p>

<p><label>City</label>
<?php echo $model->city_to_select('cityID', $profile->countryID, $profile->cityID)?>
</p>

<p>
            <?php echo recaptcha_get_html("6LdXIQsAAAAAANstV0tV1XiVkrrCMZTegsaIJsRz"); ?>
        </p>
        
        <p id="wellcomeagree">
          <label>&nbsp;</label>
          <input type="checkbox" value="1" name="agree" id="agree" />Üyelik kurallarını okumuş gibi yaptım.
        
        </p>


</p>
<p><label>&nbsp;</label>
<input type="submit" value="Kaydol" id="newusersave" /><span class="message" id="newusermessage">&nbsp;</span>
</p>
</form>
<?php            
        }

        public function newusersave_asdfasdfasdf(){
            global $model,$db;
            //$model->mode = 0;
            print_r($_POST);
            
            return;
            //new user
            $profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            try{
                if($profileID!=$model->user->profileID) throw new Exception('profileID' . $profileID . '-' . $model->user->profileID);
                
                $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID = " . $db->quote($profileID) . " LIMIT 1" );
                $profile = null;
                if($db->loadObject($profile)){
                    $profile->name      = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->motto     = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'motto', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->languages = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'languages', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->language  = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->hometown  = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'hometown', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->hobbies   = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'hobbies', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $profile->education = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    
                    $profile->living    = filter_input(INPUT_POST, 'living', FILTER_SANITIZE_NUMBER_INT);
                    $profile->birth     = asdatetime( filter_input(INPUT_POST, 'birth', FILTER_SANITIZE_STRING), 'Y-m-d' );
                    
                    $profile->countryID = filter_input(INPUT_POST, 'countryID', FILTER_SANITIZE_NUMBER_INT);
                    $profile->cityID    = filter_input(INPUT_POST, 'cityID', FILTER_SANITIZE_NUMBER_INT);
                    
                    if($db->updateObject('profile', $profile, 'ID')){
                        echo '$("#myaccountmessage").text("Success").fadeIn(100).delay(2000).fadeOut(100);';
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
        
    }
?>
