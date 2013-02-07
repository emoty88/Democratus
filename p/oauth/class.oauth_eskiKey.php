<?php
    class oauth_plugin extends control{
        
        public $facebook_app_id         = '272887782761960';
        public $facebook_app_secret     = 'dd0f4c22abd2ff4d7f4dd7bd3518f86c';        
        public $twitter_key             = 'uxMejyJ1hajdfLBIAmluw';
        public $twitter_secret          = 'J75EjPiRKEGCGBCXjkQNxjPVlRo9e1MDK7H1Hh3WizU';
        
        public function main(){
            global $model, $db;
            $model->initTemplate('simple'); 
            //echo $model->pluginpath.'facebook/facebook.php';
            //echo 'aaa'        ;
            if($model->paths[1]=='facebook') return $this->facebook();
            elseif($model->paths[1]=='twitter') return $this->twitter();
            elseif($model->paths[1]=='twitter2') return $this->twitter2();
            elseif($model->paths[1]=='activate') return $this->activate();
        
        }
        
        public function activate(){
            global $model, $db;
            $model->initTemplate('simple');
            $model->title = 'Activate Connect | Democratus.com';
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript($model->pluginurl . 'user.js', 'user.js', 1 );
            
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addStyle(TEMPLATEURL . 'default/form.css', 'form.css', 1 );
            
            $activatekey = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($model->paths[2], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
            try{
                
                $SELECT = "SELECT oa.*";
                $FROM   = "\n FROM oauthrequest AS oa";
                $WHERE  = "\n WHERE oa.key=".$db->quote($activatekey);
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                //die($db->_sql);
                $request = null;
                if( $db->loadObject($request) ){
                    
                    //die($request->email);
                    
                    if($request->status>0) 
                        throw new Exception('zaten aktive edilmiş');
                    
                    $request->status=1;
                    if( !$db->updateObject('oauthrequest', $request, 'ID'))
                        throw new Exception('aktive edilirken hata oldu.');
                    
                    //üyeyi ve profili aktive et
                    $db->setQuery("SELECT * FROM oauth WHERE ID=".$db->quote($request->oauthID));
                    $oauth = null;
                    if($db->loadObject($oauth)){
                        //kullanıcı bulundu, aktive et
                        if($oauth->status!=0) 
                            throw new Exception('kullanıcı zaten aktif');
                        
                        $oauth->status = 1;
                        if($db->updateObject('oauth', $oauth, 'ID')){
                            echo '<h3>Aktivasyon başarılı</h3>';
                            switch($oauth->oauth_provider){
                                case 'facebook': $model->redirect('/oauth/facebook/'); break;
                                case 'twitter': $model->redirect('/oauth/twitter/'); break;
                                default: $model->redirect('/');
                            }
                            
                            
                            
                        } else throw new Exception('aktivasyon sırasında bir hata oluştu');
                        
                    }
                    
                    
                    
                    
                    //isteği sil
                    
                    
                } else {
                    //request not found
                    throw new Exception('yok ki!');
                }
            } catch (Exception $e){
                //echo $e->getMessage();
                $model->redirect('/');
            }
        }
        
        
        public function twitter2(){
            global $model, $db;
            require_once( $model->pluginpath.'twitter/twitteroauth.php' );
            
            if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
                
                // We've got everything we need
                $twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
                // Let's request the access token
                $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
                // Save it in a session var
                $_SESSION['access_token'] = $access_token;
                // Let's get the user's info
                $user_info = $twitteroauth->get('account/verify_credentials');
                
                
                //print_r($user_info);
                //die;
                
                try{
                    if (empty($user_info )) throw new Exception('profile is empty');
                    
                        
                        
                        $name         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->name, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $uid         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->id, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        //$email        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info['email'], FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $username        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->screen_name, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $motto        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->description, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        $location        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->location, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        //$birth        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_info->location, FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                        
                        if (strlen($uid)<3) throw new Exception('id not valid');
                        
                        //oauth kaydı var mı?
                        
                        $db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'twitter' AND oauth_uid = " . $db->quote($uid) . "" );
                        $oauth = null;
                        if($db->loadObject($oauth)){
                            //login ol ve çık
                            //die('oauth var');
                            
                            if(strlen($oauth->oauth_token)==0){
                                $oauth->oauth_token         = $_SESSION['oauth_token'];
                                $oauth->oauth_token_secret  = $_SESSION['oauth_token_secret'];
                                $db->updateObject('oauth', $oauth, 'ID', 0);
                            }
                            
                            if($oauth->status>0)
                                $model->login('ID='.intval($oauth->userID), 'twitter');
                            
                            return $model->redirect('/');    
                        }
                        
                        
                        //hayır ise profil, user oluştur ve oauth kaydı yap
                        
                        
                        
                        
                        
                        $profile = new stdClass;
                        $profile->name = $name;
                        $profile->motto = $motto;
                        $profile->hometown = $location;
                        $profile->status = 1;
                        
                        
                        if( $db->insertObject('profile', $profile ) ){
                            //echo 'profile ok';
                            
                            $user = new stdClass;
                            //$user->email        = $email;
                            $user->status = 1;
                            $user->ID = $db->insertid();
                            $user->registertime = date('Y-m-d H:i:s');
                            
                            if( $db->insertObject('user', $user ) ){
                                
                                $oauth = new stdClass;
                                $oauth->userID  = $db->insertid();
                                $oauth->oauth_provider  = 'twitter';
                                $oauth->oauth_uid       = $uid;
                                $oauth->username       = $username;
                                $oauth->oauth_token       = $_SESSION['oauth_token'];
                                $oauth->oauth_token_secret       = $_SESSION['oauth_token_secret'];
                                $oauth->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                                $oauth->datetime  = date('Y-m-d H:i:s');
                                $oauth->status    = 1;
                                if( $db->insertObject('oauth', $oauth ) ){
                                    echo '<h2>Yaşasın başardık</h2>';
                                    $model->login('ID='.intval($oauth->userID), 'twitter');
                                    return $model->redirect('/');
                                    
                                } else throw new Exception('oauth insert');
                        } else throw new Exception('user insert');
                    } else throw new Exception('oauth insert');
                    
                } catch (Exception $e){
                    //return $model->redirect('/welcome');
                    die('beklenmedik bi hata oluştu');
                }
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                // Print user's info
                /*
                echo '<pre>';
                print_r($user_info);
                echo '</pre><br/>';
    
    /*
    if (isset($user_info->error)) {
        // Something's wrong, go back to square 1  
        header('Location: login-twitter.php');
    } else {
       $twitter_otoken=$_SESSION['oauth_token'];
       $twitter_otoken_secret=$_SESSION['oauth_token_secret'];
       $email='';
        $uid = $user_info->id;
        $username = $user_info->name;
        $user = new User();
        $userdata = $user->checkUser($uid, 'twitter', $username,$email,$twitter_otoken,$twitter_otoken_secret);
        if(!empty($userdata)){
            session_start();
            $_SESSION['id'] = $userdata['id'];
 $_SESSION['oauth_id'] = $uid;
            $_SESSION['username'] = $userdata['username'];
            $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
            header("Location: home.php");
        }
    }
    
    
    */
} else {
    // Something's missing, go back to square 1
    //header('Location: login-twitter.php');
    
    $model->redirect('/oauth/twitter/');
}
            
            
            
        }
        
        
        public function twitter(){
            global $model, $db;

            
            require_once( $model->pluginpath.'twitter/twitteroauth.php' );
            
            //$facebook = new Facebook(array('appId' => $this->facebook_app_id, 'secret' => $this->facebook_app_secret));
            
            $twitteroauth = new TwitterOAuth($this->twitter_key, $this->twitter_secret);
            // Requesting authentication tokens, the parameter is the URL we will be redirected to
            $request_token = $twitteroauth->getRequestToken('http://democratus.com/oauth/twitter2/');
            
            // Saving them into the session

            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

            // If everything goes well..
            if ($twitteroauth->http_code == 200) {
                // Let's generate the URL and redirect
                $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
                //die($url);
                header('Location: ' . $url);
            } else {
                // It's a bad idea to kill the script, but we've got to know when there's an error.
                die('Twitter cevap vermiyor!');
            } 
        }
        
        
        
        
        public function facebook(){
            global $model, $db;

            
            require_once( $model->pluginpath.'facebook/facebook.php' );
            
            $facebook = new Facebook(array('appId' => $this->facebook_app_id, 'secret' => $this->facebook_app_secret));
            
            $user = $facebook->getUser(); 
            
            
            if(!$user){
                $login_url = $facebook->getLoginUrl(array( 'scope' => 'email'));
                $model->redirect($login_url);
            }
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
                die($e->getMessage());
            }

            try{
                if (empty($user_profile )) throw new Exception('profile is empty');
                    
                    
                    $name         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['name'], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $uid         = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['id'], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $email        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['email'], FILTER_SANITIZE_EMAIL), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    $username        = strip_tags( html_entity_decode( htmlspecialchars_decode( filter_var($user_profile['username'], FILTER_SANITIZE_STRING), ENT_QUOTES), ENT_QUOTES, 'UTF-8') );
                    
                    
                    
                    //oauth kaydı var mı?
                    
                    $db->setQuery("SELECT * FROM oauth WHERE oauth_provider = 'facebook' AND oauth_uid = " . $db->quote($user_profile['id']) . "" );
                    $oauth = null;
                    if($db->loadObject($oauth)){
                        //login ol ve çık
                        //die('oauth var');
                        if($oauth->status>0)
                            $model->login('ID='.intval($oauth->userID),'facebook');
                        if($model->profile->fbID=="")
                        {
                        	$pro=new stdClass();
                        	$pro->ID=intval($oauth->userID);
                        	$pro->fbID=$db->quote($user_profile['id']);
                        	$db->updateObject("profile", $pro, "ID");
                        }
                        return $model->redirect('/');    
                    }
                    
                    $email = strtolower( trim( $user_profile['email'] ) );
                    
                    $db->setQuery("SELECT * FROM user WHERE email = " . $db->quote($email));
                    $user = null;
                    if($db->loadObject($user)){
                        echo '<h3>Bu email adresi ile zaten bir üyelik var.</h3>';
                        //buraya oauthrequest yazalım.
                        
                        $oauth = new stdClass;
                        $oauth->userID  = $user->ID;
                        $oauth->oauth_provider  = 'facebook';
                        $oauth->oauth_uid       = $uid;
                        $oauth->username       = $username;
                        $oauth->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                        $oauth->datetime  = date('Y-m-d H:i:s');
                        $oauth->status    = 0;
                        if( $db->insertObject('oauth', $oauth ) ){
                            
                            $request = new stdClass;
                    
                            $request->oauthID   = $db->insertid();
                            $request->email     = strtolower( trim( $email ) );
                            $request->key       = md5( KEY . time() . uniqid() );
                            $request->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                            $request->datetime  = date('Y-m-d H:i:s');
                            $request->status    = 0;
                            
                            if($db->insertObject('oauthrequest', $request)){
                                $response['status'] = 'success';
                                $model->sendsystemmail($request->email, 'Democratus hesabına facebook ile bağlanma izni', 'Democratus.com\'da var olan hesabına facebook ile bağlanma talebinizi aldık. Eğer facebook hesabınızı kullanmak istiyorsanız şu onay linkine tıklamalı ya da tarayıcınızın adres çubuğuna yapıştırmalısınız: http://democratus.com/oauth/activate/'.$request->key);
                                echo '<p>Size onaylamanız için bir e-posta gönderdik. Lütfen oradaki yönergeleri takip edin</p>';
                                
                            } else {
                                throw new Exception('kayıt hatası');
                            }
                            
                        }
                        
                        
                        
                        
                        
                        
                        
                        
                        return;    
                    }
                    
                    
                    //hayır ise profil, user oluştur ve oauth kaydı yap
                    
                    
                    
                    
                    
                    $profile = new stdClass;
                    $profile->name = $name;
                    $profile->status = 1;
                    $profile->fbID = $uid;
                    
                    
                    if( $db->insertObject('profile', $profile ) ){
                        //echo 'profile ok';
                        
                        $user = new stdClass;
                        $user->email        = $email;
                        $user->status = 1;
                        $user->ID = $db->insertid();
                        $user->registertime = date('Y-m-d H:i:s');
                        
                        if( $db->insertObject('user', $user ) ){
                            
                            $oauth = new stdClass;
                            $oauth->userID  = $db->insertid();
                            $oauth->oauth_provider  = 'facebook';
                            $oauth->oauth_uid       = $uid;
                            $oauth->username       = $username;
                            $oauth->ip        = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING );
                            $oauth->datetime  = date('Y-m-d H:i:s');
                            $oauth->status    = 1;
                            if( $db->insertObject('oauth', $oauth ) ){
                                echo '<h2>Yaşasın başardık</h2>';
                                $model->login('ID='.intval($oauth->userID), 'facebook');
                                return $model->redirect('/');
                                
                            } else throw new Exception('oauth insert');
                    } else throw new Exception('user insert');
                } else throw new Exception('oauth insert');
                
            } catch (Exception $e){
                
            }
            
            
            
            
            /*
            if(0 && $user){
                

                if (!empty($user_profile )) {
                    # User info ok? Let's print it (Here we will be adding the login and registering routines)
                    echo $user_profile['name'];
                    die;
                    
                    $username = $user_profile['name'];
                         $uid = $user_profile['id'];
                     $email = $user_profile['email'];
                    
                    
                    
                    $user = new User();
                    $userdata = $user->checkUser($uid, 'facebook', $username,$email,$twitter_otoken,$twitter_otoken_secret);
                    if(!empty($userdata)){
                        session_start();
                        $_SESSION['id'] = $userdata['id'];
             $_SESSION['oauth_id'] = $uid;

                        $_SESSION['username'] = $userdata['username'];
                        $_SESSION['email'] = $email;
                        $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
                        header("Location: home.php");
                    }
                } else {
                    # For testing purposes, if there was an error, let's kill the script
                    die("There was an error.");
                }
            } 
            */
                        
            

        }
        
    }
    
/*

Array
(
    [id] => 634043807
    [name] => Kadir Yilmaz
    [first_name] => Kadir
    [last_name] => Yilmaz
    [link] => http://www.facebook.com/akadiryilmaz
    [username] => akadiryilmaz
    [location] => Array
        (
            [id] => 106012156106461
            [name] => Istanbul, Turkey
        )

    [bio] => Bir garip kul.
    [quotes] => * Bugünden itibaren daima ayarını doğru yola göre kontrol ve teftiş edip düzenleyeceğiniz, kişisel hayat planınızı yapın
* Analitik ve kritik düşünmeyi öğreten kitaplar alın ve anlayarak okuyup, günlük hayatınızda tatbik edin.
* Çocuklarınızı en önemli yatırım aracı olarak görün. Onların en mükemmel şekilde eğitilmeleri için uğraşın.
* Doğal beslenme uzmanları olun.
* Yiyecek ve içeçeklerinizin içindeki katkı maddelerini çok iyi araştırın.
* Sivil savunma uzmanları olun.
* Kesintisiz ve sağlıklı iletişim yolları üzerinde araştırmalar yapın.
* Kendinizi, genç ya da ihtiyar, kadın ve erkek ayırımı yapmadan, suni mazeretler üretmeden her gün, her an eğitin ve sürekli geliştirin.
    [work] => Array
        (
            [0] => Array
                (
                    [employer] => Array
                        (
                            [id] => 103745799663508
                            [name] => Marmara University
                        )

                    [location] => Array
                        (
                            [id] => 107099815989025
                            [name] => Göztepe, Istanbul, Turkey
                        )

                    [position] => Array
                        (
                            [id] => 143663589046450
                            [name] => Bilişim Merkezi
                        )

                    [start_date] => 2011-11
                )

            [1] => Array
                (
                    [employer] => Array
                        (
                            [id] => 116786235013975
                            [name] => internet
                        )

                    [position] => Array
                        (
                            [id] => 137468219620206
                            [name] => Web Designer & Developer
                        )

                    [start_date] => 2000-10
                )

        )

    [education] => Array
        (
            [0] => Array
                (
                    [school] => Array
                        (
                            [id] => 143365142343447
                            [name] => adana imam hatip lisesi
                        )

                    [type] => High School
                )

            [1] => Array
                (
                    [school] => Array
                        (
                            [id] => 126422820770917
                            [name] => Yüzüncü Yıl Üniversitesi
                        )

                    [type] => College
                )

        )

    [gender] => male
    [email] => kadir@kadir.web.tr
    [timezone] => 2
    [locale] => tr_TR
    [verified] => 1
    [updated_time] => 2011-11-12T11:54:48+0000
)



TWITTER

stdClass Object
(
    [default_profile_image] => 
    [profile_background_tile] => 1
    [time_zone] => Istanbul
    [friends_count] => 150
    [protected] => 
    [follow_request_sent] => 
    [profile_sidebar_fill_color] => efefef
    [name] => A.Kadir YILMAZ
    [is_translator] => 
    [statuses_count] => 254
    [created_at] => Thu Jul 29 13:47:09 +0000 2010
    [followers_count] => 130
    [profile_image_url] => http://a0.twimg.com/profile_images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg
    [verified] => 
    [profile_background_image_url_https] => https://si0.twimg.com/images/themes/theme14/bg.gif
    [utc_offset] => 7200
    [favourites_count] => 2
    [profile_sidebar_border_color] => eeeeee
    [description] => 
    [screen_name] => akadiryilmaz
    [following] => 
    [profile_use_background_image] => 1
    [status] => stdClass Object
        (
            [possibly_sensitive] => 
            [place] => 
            [retweet_count] => 1
            [in_reply_to_screen_name] => 
            [geo] => 
            [coordinates] => 
            [retweeted] => 1
            [created_at] => Wed Nov 09 16:11:42 +0000 2011
            [in_reply_to_status_id_str] => 
            [in_reply_to_user_id_str] => 
            [contributors] => 
            [in_reply_to_status_id] => 
            [id_str] => 134302149835358208
            [retweeted_status] => stdClass Object
                (
                    [possibly_sensitive] => 
                    [place] => 
                    [retweet_count] => 1
                    [in_reply_to_screen_name] => 
                    [geo] => 
                    [coordinates] => 
                    [retweeted] => 1
                    [created_at] => Wed Nov 09 16:04:31 +0000 2011
                    [in_reply_to_status_id_str] => 
                    [in_reply_to_user_id_str] => 
                    [contributors] => 
                    [in_reply_to_status_id] => 
                    [id_str] => 134300344053272577
                    [truncated] => 
                    [source] => Haber Pan
                    [in_reply_to_user_id] => 
                    [favorited] => 
                    [id] => 134300344053272576
                    [text] => Havalar Soğuyor! http://t.co/AgxQWUaQ
                )

            [truncated] => 
            [source] => web
            [in_reply_to_user_id] => 
            [favorited] => 
            [id] => 134302149835358208
            [text] => RT @haberpan: Havalar Soğuyor! http://t.co/AgxQWUaQ
        )

    [notifications] => 
    [profile_text_color] => 333333
    [profile_image_url_https] => https://si0.twimg.com/profile_images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg
    [listed_count] => 1
    [contributors_enabled] => 
    [geo_enabled] => 
    [profile_background_image_url] => http://a1.twimg.com/images/themes/theme14/bg.gif
    [location] => Istanbul
    [id_str] => 172344283
    [default_profile] => 
    [profile_link_color] => 009999
    [show_all_inline_media] => 
    [url] => http://kadir.web.tr
    [id] => 172344283
    [lang] => en
    [profile_background_color] => 131516
)






*/    
    
?>
