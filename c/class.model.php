<?php
    class model{
        public $profile         = null;
        public $profileID       = null;
        public $user            = null;
        public $userID          = null;
        public $session         = null;

        public $template        = null;
        public $templateurl     = null;
        public $templatepath    = null;
        public $mode            = 2;
        public $view            = null;
        
        public $siteurl         = null;
        public $rawURL          = null;
        public $parsedURL       = null;
        public $paths           = array();
        public $urls            = array();
        public $URL             = null;        
        
        public $language        = 'tr';        
        public $languages       = array('tr', 'en', 'ar');        
        public $roles           = array(
                                        'superadmin'=>1,
                                        'admin'=>2,
                                        
                                        'editor'=>4,
                                        'viceeditor'=>8,
                                        
                                        'moderator'=>16,
                                        'vicemoderator'=>32,
                                        
                                        'interpretor'=>64,
                                        'viceinterpretor'=>128
                                        );
        
        public $ID              = null;
        public $page            = null;
        public $pageurl         = '/';
        public $filterenabled   = 1;
        public $plugin          = null;
        public $pluginpath      = null;
        public $pluginurl       = null;
        public $position        = null;
        public $db              = null;
        
        public $uploadpath      = null;
        public $uploadurl       = null;
        
        public $scripts         = array();
        public $styles          = array();
        public $metas           = array();
        public $headers         = array();
        
        public $title           = null;
        public $description     = null;
        public $keywords        = null;
        
        public $pagebuffer      = null;
        public $buffer          = null;
        public $cacheble        = 1;
        public $cachettl        = 600;
        
        public $v               = null;
        public $newDesign 		= true;	
		public $urlsizProfile	= false;
		public $urlsizProfileID = 0;
        public function main(){
            global $db, $L;
            
            $starttime = array_sum( explode(' ', microtime()));
        
            $this->parseURL();
            //session id'nin tüm subdomainlerde aynı olmasını sağlamak için
            ini_set('session.cookie_domain','.' . $this->domain); 
            
            //dil özelliği
            $this->initlanguage();

            if(0 && CACHE ){
                //apc_clear_cache('user');
                $cached = apc_fetch(md5($this->rawURL));
                $headercached = apc_fetch(md5('header'.$this->rawURL));

                if($cached && $headercached) {
                    if(is_array($headercached))
                        foreach($headercached as $h)
                            header($h);
                    
                    echo $cached;
                    echo '<!-- '.sprintf("%01.6f",array_sum( explode(' ', microtime()) ) - $starttime).' -->';
                    exit;
                }
            }
            
                        
            $this->initSession();
            $this->initUser();
            $this->initProfile();
                
            $this->mode = 2;
            $this->load();

            $this->loadview();
            
            if(!headers_sent()){//must revalidate
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Cache-Control: post-check=0, pre-check=0', FALSE);
                header('Pragma: no-cache');
            }
            
            echo $this->buffer;
            
            if($this->mode>0){
                $totaltime = array_sum( explode(' ', microtime()) ) - $starttime;
                
                /*
                echo '<!-- ';
                if(DEBUG) 'query:'.$db->_ticker;
                echo ' total time:'.sprintf("%01.6f",$totaltime).' seconds';
                if(DEBUG) if(isset($this->page)) echo ' id:'.$this->page->ID;
                if(DEBUG) if(isset($this->user)) echo ' user:'.$this->user->ID;
                echo '-->';
                */
                //if(DEBUG) echo implode(";\n", $db->_log);
                
            }
            
            if( 0 && CACHE && $this->cacheble ){
                
                $obj = headers_list();
                apc_store(md5('header'.$this->rawURL), $obj, $this->cachettl );
                apc_store(md5($this->rawURL), $this->buffer, $this->cachettl );
                
                if($this->mode>0) echo '<!-- cached '.$this->cachettl.' -->';
            }            
                        
             
        }
        
        public function initlanguage(){
            global $l; // bu alan veritabanından gelecek şekilde düzeltilecek
            if(in_array($this->paths[0], $this->languages)){
                $this->language = array_shift($this->paths[0]);
            }
			$l= new lang($this->language);
            /*
            $languagefile = COREPATH . 'language' . SLASH . $this->language .'.php';
            // bu alan veritabanından gelecek şekilde düzeltilecek
            if(file_exists($languagefile)) {
                include($languagefile);
            }
            //die($languagefile);
			 * */
        }
        
        public function initSession(){
            //return;//sessionu başlatma gerek yok
            if (session_id() == ""){
                //ini_set('session.gc_maxlifetime', intval( SESSIONTIMEOUT ));
                //ini_set('session.gc_probability', 0);
                session_start();
            }
        }
        
        public function initUser(){
            global $db, $model;
            
            try{
                
                if( isset($_COOKIE['sid']) ){
                    $sid = filter_input(INPUT_COOKIE, 'sid', FILTER_SANITIZE_STRING);
                    
                    $db->setQuery("SELECT s.* FROM session AS s WHERE s.sid=".$db->quote($sid)." LIMIT 1");  
					                   
                    if($db->loadObject($this->session)){
                    	  
                        //die($sid);
                        //check time out
                        if( strtotime($this->session->timeout) < time()) 
                            throw new Exception('session timed out');
                        
                        //check ip
                        if(config::$checkip && !( $this->session->ip ==  $_SERVER['REMOTE_ADDR']))
                            throw new Exception('initUser: ip adresses does not match');
                        
                        //logged in
                        $db->setQuery("SELECT u.* FROM user AS u WHERE u.ID=".$db->quote($this->session->userID)." LIMIT 1");
                        if($db->loadObject($this->user)){
                            
                            //check user status
                            if($this->user->status<=0) 
                                throw new Exception('activation required.');

                            $timeout =  time() + intval(SESSIONTIMEOUT);
                            
                            if( intval( $timeout-strtotime($this->session->timeout) ) > 60 ){
                                if($this->session->remember>0)
                                    $this->session->timeout = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT*30)); //saniye
                                else
                                    $this->session->timeout = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT)); //saniye
                                    
                                    
                                $this->session->endtime = date('Y-m-d H:i:s');
                                $db->updateObject('session', $this->session, 'ID', false) ;
                                setcookie("sid", $this->session->sid, time()+intval(SESSIONTIMEOUT), COOKIEPATH, COOKIEDOMAIN);
                            }
                            
                            $this->userID = $this->user->ID; 
                        }

                    }
                    
                    /*
                    $SELECT = "SELECT u.*";
                    $FROM   = "\n FROM #__user AS u";
                    $WHERE  = "\n WHERE u.sid=".$db->quote($session_key);
                    $LIMIT  = "\n LIMIT 1";
                    
                    $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                    $this->user = null;
                    if($db->loadObject($this->user)){
                        //print_r($this->user);
                        //die('kullanıcı bulundu');

                        if(config::$checkip && !( $this->user->sip == ip2long( $_SERVER['REMOTE_ADDR'])))
                            throw new Exception('initUser: ip adresses does not match');
                        
                        $this->user->stimeout = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT)); //saniye
                        $db->updateObject('#__user', $this->user, 'ID', false) ;
                        setcookie("sid", $this->user->sid, time()+intval(SESSIONTIMEOUT), COOKIEPATH, COOKIEDOMAIN);
                        $this->userID = $this->user->ID;                       
                    } else {
                        setcookie("sid", '', time()-60*60*24*10, COOKIEPATH, COOKIEDOMAIN);
                        throw new Exception('initUser: cookie timed out');
                    }        
                    */
                } else {
                    $model->user = null;
                }            

            } catch (Exception $e) {
                $model->user = null;
                $model->userID = 0;
                //echo $e->getMessage();
                setcookie("sid", null,  null, COOKIEPATH, COOKIEDOMAIN);
                session_unset();
                session_destroy();
                header("Location: /");
                die;
            }
        
        }
        
        public function initProfile(){
            global $db, $model;
            $this->profileimage = '';
            //echo $this->userID; die;
            if($this->user){
                $db->setQuery("SELECT p.* FROM profile AS p WHERE p.ID=".$db->quote($this->user->ID)." LIMIT 1");
                $this->profile = null;
                if($db->loadObject($this->profile)){
                    //profile loaded
                    $this->profileID = $this->profile->ID;
                    $this->profileimage = $this->profile->image;
					if($model->profile->temelPuanHesaplandi==0)
					{
						$puan = new puan;
						$puan->temelPuanIsle($model->profile);
					}
                }
            }
            
            
        }
        
        public function useris($role, $user=false){
            if(false===$user){
                if(is_null($this->user)) 
                    return false;
                else 
                    $user = intval( $this->user->role );
            }
            
            if(array_key_exists($role, $this->roles))
                return intval($this->roles[$role]) & intval($user);
            else
                return 0;
        }
        
        /*
        public function useris($role, $user=null){
            if(is_null($this->user)) return false;
            
            if(isset($this->user->role) && $this->user->role == $yetki) return true;
            else return false;
        }
        
        public function userin(){
            if(is_null($this->user)) return false;
            if(isset($this->user->yetki)){
                $args = func_get_args();
                return in_array($this->user->yetki, $args);
            }  
            else return false;
        }
        */
        
        
        public function load1(){ die;
            if ((count($this->paths)==0) || ($this->paths[0] == "")) {
                echo "burası ana sayfa";
            }else{
                switch ($this->paths[0]) {
                    case 'p': echo 'page block'; break;

                    default:
                        $baslik = new baslik;
                        $baslik->load($this->paths[0]);

                }
            }
            $this->initTemplate();
        }

        public function load_(){
            global $db;
            
            
            if ((count($this->paths)==0) || ($this->paths[0] == "")) {
                //ana sayfa
                $query = "SELECT p.*, t.perms AS tperms, t.plugin AS tplugin, t.template AS ttemplate"
                . "\n FROM #__page AS p"
                . "\n LEFT JOIN #__type AS t ON t.name = p.type"
                . "\n WHERE p.ID=".$db->Quote(DEFAULTPAGEID)
                . "\n AND p.status>0"
                . "\n LIMIT 1"        
                ;
            } else {



                $query = "SELECT p.*, t.perms AS tperms, t.plugin AS tplugin, t.template AS ttemplate"
                . "\n FROM #__page as p"
                . "\n LEFT JOIN #__type AS t ON t.name = p.type"
                . "\n WHERE p.permalink=".$db->Quote( $this->paths[0] )
                //. "\n WHERE p.permalink=".$db->Quote(urldecode( $this->paths[0] ))
                . "\n AND p.status>0"
                . "\n LIMIT 1"        
                ;                
            }
            //die($query);
            $db->setQuery( $query );

            if ($db->loadObject($this->page)){
                
                if(is_null($this->page)) return null;
                
                $this->ID = $this->page->ID;
                
                $perms = is_null($this->page->plugin) ? intval($this->page->tperms)+0 : (intval($this->page->tperms) | intval($this->page->perms))+0;
                
                if($perms && !($perms&$model->user->role)) {    
                    //izin yok
                    if(is_null( $model->user )){
                        $_SESSION['redirectURL'] = $model->URL;
                        $model->redirect('/user/');
                    } else {
                        die( "not allowed" );
                    }                
                } 
                else { 
                    //izin var
                    ob_start();
                    //die($db->_sql);
                    $this->plugin = is_null($this->page->plugin) ? $this->page->tplugin : $this->page->plugin;     
                    $this->pluginurl  = PLUGINURL.$this->plugin.'/';
                    $this->pluginpath = PLUGINPATH.$this->plugin.SLASH;

                    $this->initTemplate();

                    try {
                        $feedback = $this->_includeplugin($this->plugin);
                    }
                    catch (Exception $e) {
                        echo "Plugin Error: " . $this->plugin . ' : ' . $e->getMessage(); 
                        //print_r($this->page);
                        die;                  
                    }            
                    
                    $this->pagebuffer = ob_get_contents();
                
                    ob_end_clean();
                    
                    if($this->pagefilter) $this->pagebuffer = $this->makefilter($this->pagebuffer);

                } // perms

            } else { //load object  die('sayfa bulunamadı');
                ob_start();
                    
                $this->plugin = DEFAULTPLUGIN; //is_null($this->page->plugin) ? $this->page->tplugin : $this->page->plugin;     
                $this->pluginurl  = PLUGINURL.$this->plugin.'/';
                $this->pluginpath = PLUGINPATH.$this->plugin.SLASH;

                $this->initTemplate();

                try {
                    $feedback = $this->_includeplugin($this->plugin);
                }
                catch (Exception $e) {
                    echo "Plugin Error: " . $this->plugin . ':' . $e->getMessage(); die;                  
                }            
                
                $this->pagebuffer = ob_get_contents();
            
                ob_end_clean();
                
                if($this->pagefilter) $this->pagebuffer = $this->makefilter($this->pagebuffer);
                
                
                
            }
            
        }

        public function load(){
            global $db;
            
			
            $permalink  = $this->paths[0];
            $domain     = $this->domain;               
            $query  = "SELECT p.*"
                    . "\n FROM #__page AS p"
                    . "\n WHERE p.permalink=".$db->quote($permalink)." AND ( p.domain IS NULL OR p.domain=".$db->quote($domain)." )"
                    . "\n AND status>0"
                    . "\n LIMIT 1"
                    ;
			       
            $db->setQuery( $query );
            if ($db->loadObject($this->page)){
                $this->ID = $this->page->ID;
                $this->plugin = $this->page->plugin;
                $this->pageurl = $this->siteurl . $this->page->permalink . '/';
			} elseif(strlen( $this->paths[0])==0) {
                $this->ID = 0;
                $this->plugin = DEFAULTPLUGIN;
                
            } else {
            	$query  = "SELECT p.ID, p.type"
                    . "\n FROM profile AS p"
                    . "\n WHERE p.permalink=".$db->quote($permalink)." "
                    . "\n AND p.status>0"
                    . "\n LIMIT 1"
                    ;
					$db->setQuery( $query );
					if($db->loadObject($profileID))
					{
						
						$this->urlsizProfile=true;
						$this->urlsizProfileID=$profileID->ID;
						$page = "profile";
						
						if($profileID->type == "hashTag")
						{
							$page = "hashTag";
						}
						$query  = "SELECT p.*"
			                    . "\n FROM #__page AS p"
			                    . "\n WHERE p.permalink=".$db->quote($page)." AND ( p.domain IS NULL OR p.domain=".$db->quote($domain)." )"
			                    . "\n AND status>0"
			                    . "\n LIMIT 1"
			                    ;
						       
			            $db->setQuery( $query );
			            if ($db->loadObject($this->page)){
			                $this->ID = $this->page->ID;
			                $this->plugin = $this->page->plugin;
			                $this->pageurl = $this->siteurl . $this->page->permalink . '/';
						}
					}else{
						Header( "Location: /search/".$permalink);
						$this->initTemplate();
	                	return $this->notfound()&&die;
	                	die;
					}
					
            }
			
            
            $this->pluginurl  = PLUGINURL.$this->plugin.'/';
            $this->pluginpath = PLUGINPATH.$this->plugin.SLASH;
            
            $filename   = PLUGINPATH.$this->plugin.SLASH.'class.'.$this->plugin.".php";
            $pluginurl  = PLUGINURL.$this->plugin.'/';
            $pluginpath = PLUGINPATH.$this->plugin.SLASH;            
            
            $this->initTemplate();
            
            ob_start();
            try {
                if(file_exists($filename)){
                    include_once($filename);
                    $pluginclass =(string) $this->plugin . '_plugin';
                    $p = new $pluginclass;
                    $feedback = $p->main();
                    $p = null;
                }
                else
				{
					
                	throw new Exception("Bulunamadı");
				}
            }
            catch (Exception $e) {
                //echo "Plugin Error: " . $this->plugin . ':' . $e->getMessage(); die;                  
                header("Error: Plugin Error: " . $this->plugin . ':' . $e->getMessage());
            }            
            
            $this->pagebuffer = ob_get_contents();
        
            ob_end_clean();            
            
            if($this->filterenabled) $this->pagebuffer = $this->makefilter($this->pagebuffer);
            
        }
        
        public function parseURL() {
            
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on")        
                $this->rawURL = "https://".$_SERVER['HTTP_HOST'].urldecode( $_SERVER['REQUEST_URI'] );
            else
                $this->rawURL = "http://".$_SERVER['HTTP_HOST'].urldecode( $_SERVER['REQUEST_URI'] );  

            $this->parsedURL=parse_url($this->rawURL);
            
            $this->parsedURL['host']=strtolower( $this->parsedURL['host'] );
            $this->parsedURL['scheme']=strtolower( $this->parsedURL['scheme'] );

            $this->URL  = $this->parsedURL['scheme'].'://';
            $this->URL .= $this->parsedURL['host'].'';

            $this->siteurl = $this->URL.'/';
            
            if (isset($this->parsedURL['path']))
                $this->URL .= $this->parsedURL['path'].'';
            else
                $this->URL .= '/';

            if (isset($this->parsedURL['query']))
                $this->URL .= '?'.$this->parsedURL['query'].'';
                
            $path = trim($this->parsedURL['path'], '\/');

            $this->paths = explode('/',$path); 
            $this->paths = array_pad($this->paths, 10, null);
            //$this->paths     = $this->paths;

            $this->domain    = $this->parsedURL['host'];
            if(substr_compare($this->domain, 'www.',0,4,0)==0) $this->domain = substr($this->domain, 4);

        }
        
        public function _includetemplate($template){
            global $model, $db;
            
            $templateurl  = TEMPLATEURL.$template.'/';
            $templatepath = TEMPLATEPATH.$template.SLASH; 
            $filename = TEMPLATEPATH.$template.SLASH.$template.".php";
            
            if(file_exists($filename)){
                include($filename);
            }
            else{
                throw new Exception("Bulunamadı");
            }
        }
        
        public function _includeplugin($plugin){
            global $model, $db;
            
            $filename = PLUGINPATH.$plugin.SLASH.'class.'.$plugin.".php";
            $pluginurl  = PLUGINURL.$plugin.'/';
            $pluginpath = PLUGINPATH.$plugin.SLASH;            
            
            if(file_exists($filename)){
                include_once($filename);
            }
            else{
                throw new Exception("Bulunamadı");
            }
        }
        
        public function _runplugin($plugin){
            global $cms, $database;
            
            $filename = PLUGINPATH.$plugin.SLASH.'class.'.$plugin.".php";
            $pluginurl  = PLUGINURL.$plugin.'/';
            $pluginpath = PLUGINPATH.$plugin.SLASH;            
            
            if(file_exists($filename)){
                include_once($filename);
                //$pluginclass = $plugin . '_plugin';
                $pluginclass =(string) $plugin . '_plugin';
                $p = new $pluginclass;
                $p->main();
                $p = null;
            }
            else{
                throw new Exception("Bulunamadı");
            }
        }        
        
        public function initTemplate($name = null, $view = null){
            
            if(is_null($name)){
                
                if(is_null($this->page)){
                    $this->template = 'default';
                } elseif(isset($this->page->template)) {
                    $this->template = $this->page->template;
                } elseif( is_null( $this->page->plugin )) {
                    if(!is_null($this->page->ttemplate)){
                        $this->template = $this->page->template;
                    } else {
                        $this->template = 'default';
                    }
                } else {
                    $this->template = 'default';
                }
            } else {
                $this->template = $name;
            }
            
            $this->templatepath = TEMPLATEPATH . $this->template . SLASH;
            $this->templateurl = TEMPLATEURL . $this->template . '/';
            if(!is_null($view)) $this->view = $view;
            
        }
        
        
        public function loadview(){
            
            $this->templatepath = TEMPLATEPATH . $this->template . SLASH;
            $this->templateurl = TEMPLATEURL . $this->template . '/';

            if($this->mode==0){
                $this->buffer = $this->pagebuffer;
            } elseif($this->mode==1){
               $this->buffer = ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$this->title.'</title> 
<link href="http://democratus.com/images/df.ico" rel="shortcut icon" type="image/x-icon" />
<meta name="description" content="'.$this->description.'" />
<meta name="keywords" content="'.$this->keywords.'" />
'.implode("\n",$this->metas).'
'.implode("\n",$this->styles).'
'.implode("\n",$this->scripts).'
'.implode("\n",$this->headers).'
<link rel="alternate" type="application/rss+xml" title="'.SITENAME.' RSS Feed" href="/rss/" />
</head>
<body>
'.$this->pagebuffer.'
</body>
</html>'; 
            } else {

                ob_start();
                try {
                    
                    $filename = TEMPLATEPATH.$this->template.SLASH.'class.'.$this->template.".php";
                    //$filename = TEMPLATEPATH.$this->view.SLASH.'class.'.$this->view.".php";
                    //echo $filename;die;
                    if(file_exists($filename)){
                        include_once($filename);
                        //$pluginclass = $plugin . '_plugin';
                        $viewclass =(string) $this->template . '_view';
                        //
                        $v = new $viewclass;
                        $feedback = $v->main();
                        $v = null;
                    }
                    else{
                        throw new Exception("view not found: " . $this->template );
                    }

//

                    //$feedback = $this->_includetemplate($this->template);
                }
                catch (Exception $e) {
                    echo "view error: " . $e->getMessage(); die;
                }            
                
                $templatebuffer = ob_get_contents();
            
                ob_end_clean();
                
                preg_match ( "@\<body[^>]*\>.*?\</body\>@is", $templatebuffer, $myBody );
                if(count($myBody)) $myBody = $myBody[0];
                
                //print_r($myBody);
                /**/
                $myBody = str_replace('{{main}}', $this->pagebuffer, $myBody );
                //die($myBody);
                //print_r($myBody);
                preg_match_all( "/(\{\{[a-zA-Z0-9]+\}\})/i", $myBody, $pozs );
                if(count($pozs)) $pozs = $pozs[0];
                if(count($pozs)){
                    
                    $this->blocks = array();//reset blocks
                    foreach($pozs as $poz){
                        $positionName = trim($poz,'{} ');
                        $this->blocks[$positionName]='';
                    }
                
                    $this->loadBlocks();
                
                    foreach($pozs as $poz){
                        $positionName = trim($poz,'{} ');
                        if(isset($this->blocks[$positionName]))
                        $myBody = str_replace($poz, $this->blocks[$positionName], $myBody );
                        else 
                        $myBody = str_replace($poz, '', $myBody );
                    }                
                
                }
                
$this->buffer = ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$this->title.'</title> 
<link href="http://democratus.com/images/df.ico" rel="shortcut icon" type="image/x-icon" />
<meta name="description" content="'.$this->description.'" />
<meta name="keywords" content="'.$this->keywords.'" />
'.implode("\n",$this->metas).'
'.implode("\n",$this->styles).'
'.implode("\n",$this->scripts).'
'.implode("\n",$this->headers).'
<link rel="alternate" type="application/rss+xml" title="'.SITENAME.' RSS Feed" href="/rss/" />
</head>
'.$myBody.'
</html>';                
                
            }
        }
    
        public function loadBlocks(){
            global $model, $db;
            //die('load blocks');
            if(count($this->blocks) < 1) return 0;
            
            $positions = array_keys( $this->blocks );
            
            foreach($positions as $k) {
                    
                    
                    $this->position = $k;
                    
                    $this->plugin = $k;     
                    
                    $filename   = BLOCKPATH.$this->plugin.SLASH.'class.'.$this->plugin.".php";
                    $pluginurl  = BLOCKURL.$this->plugin.'/';
                    $pluginpath = BLOCKPATH.$this->plugin.SLASH;

                    ob_start();
                    try {
                        if(file_exists($filename)){
                            include_once($filename);
                            $pluginclass =(string) $this->plugin . '_block';
                            $p = new $pluginclass;
                            $feedback = $p->block();
                            $p = null;
                        }
                        else throw new Exception("block not found: ");
                        
                    }
                    catch (Exception $e) {
                        //echo "Plugin Error: " . $this->plugin . ':' . $e->getMessage(); //die;                  
                    }            
                    
                    if( isset($this->blocks[$k]) )
                        $this->blocks[$k] .=  ob_get_contents();
                    else
                        $this->blocks[$k] =  ob_get_contents();                                            
                
                    ob_end_clean();
                }
                $this->position = null;
                return 1;
              
            
        }
        
        public function loadBlocksa(){
            global $model, $db;
            //die('load blocks');
            if(count($this->blocks) < 1) return 0;
            
            $positions = array_keys( $this->blocks );
            
            foreach($positions as &$k) $k= $db->Quote($k);
            
            $positions = implode(',',$positions);
            
            $query  = "SELECT b.*"
                    . "\n FROM #__block AS b"
                    . "\n WHERE b.position IN (".$positions.") AND b.status>0"
                    ;
            
            $db->setQuery( $query );
            $result = $db->loadObjectList();
            //print_r($result);die;
            $this->blocks = array();
            if(count($result)){
                foreach($result as $b){
                    
                    
                    $this->position = $b->position;
                    
                    $this->plugin = $b->plugin;     
                    
                    $filename   = PLUGINPATH.$this->plugin.SLASH.'class.'.$this->plugin.".php";
                    $pluginurl  = PLUGINURL.$this->plugin.'/';
                    $pluginpath = PLUGINPATH.$this->plugin.SLASH;

                    ob_start();
                    try {
                        if(file_exists($filename)){
                            include_once($filename);
                            $pluginclass =(string) $this->plugin . '_plugin';
                            $p = new $pluginclass;
                            $feedback = $p->block();
                            $p = null;
                        }
                        else throw new Exception("block not found: ");
                        
                    }
                    catch (Exception $e) {
                        echo "Plugin Error: " . $this->plugin . ':' . $e->getMessage(); die;                  
                    }            
                    
                    if( isset($this->blocks[$b->position]) )
                        $this->blocks[$b->position] .=  ob_get_contents();
                    else
                        $this->blocks[$b->position] =  ob_get_contents();                                            
                
                    ob_end_clean();
                }
                $this->position = null;
                return 1;
            }
            else{
                $this->position = null;
                return 0;
            }            
            
        }
        
        public function addStyle($style,$key=null,$isFile=false,$if=null) {
            if(!is_null($key) && isset($this->styles[$key])){
                //zaten eklenmis
                return 0;
            }
            else if ($isFile){
            	$rand = rand(100,9999);
                $s = '<link href="'.$style.'?v='.$rand.'" rel="stylesheet" type="text/css" />'; 
            }
            else {
                $s = '<style type="text/css">'."\n";
                //$s .= '<!--'."\n";
                $s .= $style."\n";
                //$s .= '-->'."\n";
                $s .= '</style>';
            }
            
            if(!is_null($if)){
                $s = '<!--['.$if.']>'."\n".$s."\n".'<![endif]-->';
            }
            
            if(is_null($key))
                $this->styles[] = $s;
            else 
                $this->styles[$key] = $s;         
            
            return 1;
        }
        
        public function addScript($script,$key=null,$isFile=false,$if=null) {
            if(!is_null($key) && isset($this->scripts[$key])){
                //zaten eklenmis
                return 0;
            }
            else if ($isFile){
            	$rand = rand(100,9999);
                $s = '<script type="text/javascript" src="'.$script.'?v='.$rand.'"></script>'; 
            }
            else {
                $s = '<script type="text/javascript">'."\n";
                $s .= $script."\n";
                $s .= '</script>';
            }
            
            if(!is_null($if)){
                $s = '<!--['.$if.']>'."\n".$s."\n".'<![endif]-->'."\n";
            }
                    
            if(is_null($key))
                $this->scripts[] = $s;
            else 
                $this->scripts[$key] = $s;
                    
            return 1;
        }
        
        public function addHeader($header,$key=null) {
            if(!is_null($key) && isset($this->headers[$key])) return 0;

            if(is_null($key))
                $this->headers[] = $header;
            else 
                $this->headers[$key] = $header;
            
            return 1;
        }
        
        public function addMeta($meta,$key=null) {
            if(!is_null($key) && isset($this->metas[$key])) return 0;

            if(is_null($key))
                $this->metas[] = $meta;
            else 
                $this->metas[$key] = $meta;
            
            return 1;
        }
        
        public function setCache($key, $data, $cachettl){
            return apc_store($key, $data, $cachettl );                        
        }
        
        public function isCache($key){
            return (bool)apc_fetch($key);
        }
        
        public function getCache($key){
            return apc_fetch($key);
        }
        
        public function loadFromCache($key){
            if( CACHE && $cached = apc_fetch(md5($key)) ){
                echo $cached;
                return true;
            }
            else return false;        
        }
        
        public function login($who=null, $mode = 'login'){
            global $db;
            try{
                if(strlen($who)&&is_null($this->user)){
                    
                    $SELECT = "SELECT *";
                    $FROM   = "\n FROM user";
                    $WHERE  = "\n WHERE ".$who;
                    $LIMIT  = "\n LIMIT 1";
                    
                    $db->setQuery( $SELECT . $FROM . $WHERE . $LIMIT );
                    $this->user = null;
                    if($db->loadObject($this->user)){
                        
                        //login ol
                        $session = new stdClass;
                        
                        $session->mode    	= $mode;
                        $session->sid       = md5( KEY . session_id() . uniqid() );
                        $session->ip        =  filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING ) ;
                        $session->timeout   = date('Y-m-d H:i:s', time() + intval(SESSIONTIMEOUT)); //saniye
                        $session->starttime = date('Y-m-d H:i:s');
                        $session->endtime   = date('Y-m-d H:i:s');
                        $session->userID    = $this->user->ID;
                        $session->profileID = $this->user->ID;
                        $session->status    = 1;
                        
                        if($db->insertObject('session', $session)){
                            //logged in
                            
                            $this->userID = $this->user->ID;                        
                            $this->initProfile();
                        
                            
                            //oturum idsini cookieye yaz
                            setcookie("sid", $session->sid, time()+intval(SESSIONTIMEOUT), COOKIEPATH, COOKIEDOMAIN);
                            
                            
                            
                            
                            
                            $response['status'] = 'success';
                            
                        } else {
                            //not logged in
                            throw new Exception('Kullanıcı adı veya şifre hatalı');
                        }

                    } else {
                        throw new Exception('login:user not found');
                    }
                }
            } catch(Exception $e){
                $this->user = null;
                return 0;
            }
        }
        
        public function logout($who=null){
            global $db;
            if(is_null($who)){
                if(!is_null($this->user)) 
                    $who = 'ID='.$db->Quote($this->user->ID);
                elseif( isset($_COOKIE['session_key']) && strlen($_COOKIE['session_key'])>=32)
                    $who = 'session_key='.$db->Quote($_COOKIE['session_key']);
                else 
                    return 0;
            }
            
            $query = "UPDATE #__user"
            . "\n SET session_key = NULL, session_timeout = NULL"
            . "\n WHERE " . $who
            . "\n LIMIT 1"
            ;
            
            $db->setQuery( $query );
            if( $db->uquery() ){
                $this->user = null;
                setcookie("session_key", '', time()-60*60*24*10, "/");
                return 1;
            } else {
                return 0;
            }
        }
        
        public function redirect($url, $usejs=false) {
            if ($usejs || headers_sent()){
               echo '<script type="text/javascript">';
               echo 'window.location.href="'.$url.'";';
               echo '</script>';
               echo '<noscript><meta http-equiv="refresh" content="0;url='.$url.'" /></noscript>';
            }
            else {
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: '.$url);
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Cache-Control: no-store, no-cache, must-revalidate');
                header('Cache-Control: post-check=0, pre-check=0', FALSE);
                header('Pragma: no-cache');
                exit;
            }
            
        }
        
        public function makelink($page){
            if(is_string($page)){
                return $this->siteurl . trim( $page, '/' ) . '/';
            } elseif(is_object($page)){
                return $this->siteurl . trim( $page->permalink, '/') . '/';
            } else {
                return $this->siteurl . '/';
            }
        }
        
        public function getlink(){
            if(isset($this->page)){
                return $this->makelink($this->page);
            }
        }
        
        public function makefilter($buffer){
            //die($buffer);
            //$buffer =' aşlskdfşlaksjf [foto-12-640x480]';
            global $model, $db;
            $matches = array();
            foreach(config::$filters as $filter){
                //if(preg_match($filter['pattern'], $buffer, $match)) 
                if(preg_match_all($filter['pattern'], $buffer, $match)>0) 
                    $matches[$filter['name']] = $match[0];  
            }
            //print_r($matches) ;
            //die($buffer);
            foreach(config::$filters as $filter){
                if(isset($matches[$filter['name']]))
                    foreach($matches[$filter['name']] as $v){
                        try{
                            $plugin     = $filter['plugin'];
                            $filename   = PLUGINPATH.$plugin.SLASH.'class.'.$plugin.".php";
                            $pluginurl  = PLUGINURL.$plugin.'/';
                            $pluginpath = PLUGINPATH.$plugin.SLASH; 
                            
                            //die($v) ;
                            
                            //$filterPath = PLUGINPATH.$filter['plugin'].SLASH.$filter['plugin'].'_filter.php';
                            if(is_file($filename) && file_exists($filename)){
                                include_once($filename);
                                $pluginclass =(string) $plugin . '_plugin';
                                $p = new $pluginclass;
                                $filterbuffer = $p->filter($v);
                                $p = null;
                            }
                            else
                                $filterbuffer = null;
                                                    
                        } catch (Exception $e){
                            $filterbuffer = null;
                        }
                        $buffer = str_replace($v, $filterbuffer, $buffer);
                    }      
            }
            return $buffer;            
        }
        public function getcoverimage($path, $width=640, $height=206, $action = 'cutout'){
			$img = $this->getImage($path, $width, $height, $action);
            if($img!=null) 
                return $img;
            else
                return $this->getImage('coverImage/coverDefault.jpg', $width, $height, $action);
		}
        public function getProfileImage($path, $width=0, $height=0, $action = 'cutout'){
        	
            $img = $this->getImage($path, $width, $height, $action);
			//var_dump($img);
          	if($img!=null) 
                return $img;
            else
                return  $this->getImage('default-image/default-profile-image.png', $width, $height, $action);
        }
        
        public function getImage($path, $width=0, $height=0, $action = 'cutout', $returnPath=false){
            global $model, $db;
			
			$returnPU = UPLOADURL;
			if($returnPath)
			{
				$returnPU = UPLOADPATH;
			}
            if (is_null($path)||strlen($path)==0) return null;
            $key = $width.'x'.$height.$action;
          	
            $pinfo = pathinfo($path);
            $path = $pinfo['dirname'];
            $fullpath = UPLOADPATH.$pinfo['dirname'];
            $ofile = $pinfo['basename'];

            if($width==0 && $height==0){
                return $returnPU.$path.'/'.$ofile; 
            }

            $nfile = substr($ofile,0,strlen(@$pinfo['basename'])-strlen(@$pinfo['extension'])-1).'_'.$key.'.'.@$pinfo['extension'];
			
			
       		if(file_exists(UPLOADPATH.$path.SLASH.$nfile) || is_file(UPLOADPATH.$path.SLASH.$nfile)){
                return $returnPU.$path.'/'.$nfile;
            }
			//var_dump(UPLOADPATH.$path.SLASH.$ofile);
			//var_dump(file_exists(UPLOADPATH.$path.SLASH.$ofile));
			//var_dump(is_file(UPLOADPATH.$path.SLASH.$ofile));
            if(file_exists(UPLOADPATH.$path.SLASH.$ofile) && is_file(UPLOADPATH.$path.SLASH.$ofile)){
                try {
                    $image = new image(UPLOADPATH.$path.SLASH.$ofile);
                    $image->$action($width, $height);
                            
                    //save the image
                    $image->write(UPLOADPATH.$path.SLASH.$nfile);
                    $image = null; unset($image);
                    return $returnPU.$path.'/'.$nfile;                 
                
                } catch (Exception $e) {
                	
                    return null;                
                }
            } else {
                return null;
            }

        }
        
        public function getUploadPath($prefix = null, $timestamp = null, $mkdir = true){
            if(!is_null($prefix)) $prefix = trim($prefix, '\/ .');
            
            $timestamp = is_null($timestamp) || $timestamp<strtotime('1970-01-02 00:00:00') ?time():$timestamp;
            clearstatcache();
            do {
                $upload_sub_folder = date('y', $timestamp).SLASH
                                    .date('m', $timestamp).SLASH
                                    .date('d', $timestamp).SLASH
                                    .date('H', $timestamp).SLASH
                                    .substr(md5(rand(0,99999999)),0,4);
                                    //.uniqid('');
                
                if(!is_null($prefix)) $upload_sub_folder = $prefix.SLASH.$upload_sub_folder;
                
                $save_path = UPLOADPATH.$upload_sub_folder.SLASH;
            } while (is_dir($save_path));
            
            if($mkdir){
                $paths = explode(SLASH,$upload_sub_folder);
                $thispath = UPLOADPATH;
                foreach($paths as $path){
                    $thispath .= $path.SLASH;
                    if(!is_dir($thispath)) mkdir($thispath);
                }        
            }

            return $upload_sub_folder.SLASH;       
        }
        
        public function getSetting( $name ){
            global $db;
            $db->setQuery( "SELECT value FROM #__settings AS s WHERE s.name=".$db->Quote($name)." LIMIT 1" );
            return $db->loadResultArray(0);
        }
        
        public function setSetting( $name, $value ){
            global $db;
            if(is_null($value)){
                $db->setQuery( "DELETE FROM #__settings WHERE name=".$db->Quote($name) );
                return $db->uquery();
            }
            
            $setting['name'] = $name;
            $setting['value'] = $value;
            
            $setting = (object) $setting;
            
            $db->setQuery( "SELECT name FROM #__settings AS s WHERE s.name=".$db->Quote($name) );
            
            if( !is_null($db->loadAssoc('name')))
                return $db->updateObject('#__settings', $setting, 'name', true);
            elseif($db->insertObject('#__settings', $setting, 'name'))
                return $db->insertid();
            else
                return 0;
        }

        
        public function region_to_select($name, $selected=null){
            global $model, $db;
                        
            $db->setQuery('SELECT re.* FROM region AS re ORDER BY re.region;');
            $items = $db->loadObjectList();
            
            
            
            $html = '<select name="'.$name.'" id="'.$name.'">';
            $sel = 0 == $selected?' selected="selected"':'';
            $html.= '<option value="0"'.$sel.'>-</option>';
            foreach($items as $item){
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->region.'</option>';
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
            $html.= '<option value=""'.$sel.'>&nbsp;</option>';
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
            $html.= '<option value=""'.$sel.'>&nbsp;</option>';
            foreach($items as $item){
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->city.'</option>';
            }
            
            $html.='</select>';
            
            return $html;
        }
        
        public function number_to_select($name, $start, $end, $selected=null, $order = 'desc'){
            $html = '<select name="'.$name.'" id="'.$name.'">';
            $sel = null == $selected?' selected="selected"':'';
            $html.= '<option value=""'.$sel.'>&nbsp;</option>';
            if($order=='desc') {
                for($i=$end;$i>=$start;$i--){
                    $sel = $i == $selected?' selected="selected"':'';
                    $html.= '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                }
            } else {
                for($i=$start;$i<=$end;$i++){
                    $sel = $i == $selected?' selected="selected"':'';
                    $html.= '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                }
            
            }
            $html.='</select>';
            
            return $html;
        }
        
        public function array_to_select($array, $name, $selected=null, $null = true ){
            $html = '<select name="'.$name.'" id="'.$name.'">';
            
            if($null){
                $sel = null == $selected?' selected="selected"':'';
                $html.= '<option value=""'.$sel.'>&nbsp;</option>';
            }
            
            foreach($array as $key=>$value){
                $sel = $key == $selected?' selected="selected"':'';
                $html.= '<option value="'.$key.'"'.$sel.'>'.$value.'</option>';
            }
            
            $html.='</select>';
            
            return $html;
        }
        
        public function notfound(){
            global $model,$db,$l;
			if($model->profileID>0)
			{
				header("location: /search/".$this->paths[0]."#kisiler"); 
            	die;
			}
            $expires = 60*60*24*14;
            
            header("Pragma: public");
            header("Cache-Control: maxage=".$expires);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
            die('<h1>Not Found</h1> <p>The requested URL was not found on this server.</p>');
        }
        
        public function sendsystemmail($email, $subject, $message, $sendType="mandrill"){
            
            $mail = new phpmailer();
			
			if($sendType=="mandrill")
			{
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->Host       = "smtp.mandrillapp.com"; 				// SMTP server
				$mail->SMTPDebug  = 0;                     					// enables SMTP debug information (for testing)
				                                           					// 1 = errors and messages
				                                           					// 2 = messages only
				$mail->SMTPAuth   = true;                  					// enable SMTP authentication
				$mail->Host       = "smtp.mandrillapp.com"; 				// sets the SMTP server
				$mail->Port       = 587;                    				// set the SMTP port for the GMAIL server
				$mail->Username   = "caner.turkmen@democratus.com"; 		// SMTP account username
				$mail->Password   = "bC4HRyEm1D4LZTaX7-xvvQ";        		// SMTP account password
			}
            $mail->SetFrom('democratus@democratus.com', 'Democratus');
            $mail->AddAddress($email);
            $mail->Subject = $subject;
            
            $body = '<html lang="en">
						<head>
						<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
						<title>Democratus</title>
						<link href="http://democratus.com/images/df.ico" rel="shortcut icon" type="image/x-icon" />
						<style type="text/css">
						a:hover {
						    text-decoration: none !important;
						}
						.header h1 {
						    color: #47c8db;
						    font: bold 32px Helvetica, Arial, sans-serif;
						    margin: 0;
						    padding: 0;
						    line-height: 40px;
						}
						.header p {
						    color: #c6c6c6;
						    font: normal 12px Helvetica, Arial, sans-serif;
						    margin: 0;
						    padding: 0;
						    line-height: 18px;
						}
						.sidebar table.toc-table {
						    color: #767676;
						    margin: 0;
						    padding: 0;
						    font-size: 12px;
						    font-family: Helvetica, Arial, sans-serif;
						}
						.sidebar table.toc-table td {
						    padding: 0 0 5px;
						    margin: 0;
						}
						.sidebar h4 {
						    color:#eb8484;
						    font-size: 11px;
						    line-height: 16px;
						    font-family: Helvetica, Arial, sans-serif;
						    margin: 0;
						    padding: 0;
						}
						.sidebar p {
						    color: #989898;
						    font-size: 11px;
						    line-height: 16px;
						    font-family: Helvetica, Arial, sans-serif;
						    margin: 0;
						    padding: 0;
						}
						.sidebar p a {
						    color: #0eb6ce;
						    text-decoration: none;
						}
						.content h2 {
						    color:#646464;
						    font-weight: bold;
						    margin: 0;
						    padding: 0;
						    line-height: 26px;
						    font-size: 18px;
						    font-family: Helvetica, Arial, sans-serif;
						}
						.content p {
						    color:#767676;
						    font-weight: normal;
						    margin: 0;
						    padding: 0;
						    line-height: 20px;
						    font-size: 12px;
						    font-family: Helvetica, Arial, sans-serif;
						}
						.content a {
						    color: #0eb6ce;
						    text-decoration: none;
						}
						.footer p {
						    font-size: 11px;
						    color:#7d7a7a;
						    margin: 0;
						    padding: 0;
						    font-family: Helvetica, Arial, sans-serif;
						}
						.footer a {
						    color: #0eb6ce;
						    text-decoration: none;
						}
						</style>
						</head>
						<body style="margin: 0; padding: 0; background: #e3e1dc;" bgcolor="#e3e1dc">
						<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding: 35px 0; background: #e3e1dc;" bgcolor="#e3e1dc">
						  <tr>
						    <td align="center" style="margin: 0; padding: 0;" ><table cellpadding="0" cellspacing="0" border="0" align="center" width="600" height="118" style="font-family: Helvetica, Arial, sans-serif; background-repeat:no-repeat;" class="header">
						        <tr>
						          <td width="600" align="left" style="padding: font-size: 0; line-height: 0; height: 7px;" height="7"><img src="http://democratus.com/images/bgheader.jpg" alt=""></td>
						        </tr>
						        <tr>
						          <td style="font-size: 0px;">&nbsp;</td>
						        </tr>
						      </table>
						      <!-- header-->
						      <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; " bgcolor="#fff">
						        <tr>
						          <td align="left" valign="top" bgcolor="#fff" style="font-family: Helvetica, Arial, sans-serif; padding:20px;">
						            <p>&nbsp;</p>
						            
						            
						            '.$message.'
						            
						            <p>&nbsp;</p>
						            
						          </td>
						        </tr>
						        <tr>
						          <td width="600" align="left" style="padding: font-size: 0; line-height: 0; height: 3px;" height="3"></td>
						        </tr>
						      </table>
						      <!-- body -->
						      <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="font-family: Helvetica, Arial, sans-serif; line-height: 10px;" class="footer">
						        <tr>
						          <td align="center" style="padding: 5px 0 10px; font-size: 11px; color:#7d7a7a; margin: 0; line-height: 1.2;font-family: Helvetica, Arial, sans-serif;" valign="top"><br>
						            <p style="font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">Bu e-postayı hesap ayarlarınıza göre aldınız. Bildirim Ayarlarınız İçin<a href="http://democratus.com/my/account"> Tıklayın.</a></p></td>
						        </tr>
						      </table>
						    <!-- footer--></td>
						  </tr>
						</table>
						</body>
						</html>';
            //$body = str_replace('%name%', $model->profile->name, $body );
            //$body = preg_replace("/[\]/i",'',$body);
            $body = preg_replace("/\\\/",'',$body); 
            
            $mail->MsgHTML($body);
            
            return $mail->Send();
            //return mail($email, $subject, $message);
            
        }
        
        public function shortname($longname, $limit=7){
            $name = trim($longname);
            $space = mb_strpos( $name, ' ' );
            
            if($space!==false) 
                $name = mb_substr($name, 0, $space);
            
            if(mb_strlen($name)>$limit)
                $name = mb_substr($name, 0, $limit - 1) . '..';
                
            return $name;
                                
        }
        
        public function splitword($words, $limit=20){
            if( mb_strlen($words)<$limit ) 
                return $words;
                
            $rarray = preg_split('/ /i', trim($words) );            
            $result = '';
            foreach($rarray as $r){
                $r = trim($r);
                
                if(mb_strlen($r)==0) continue;
                
                if(mb_strlen($r)>$limit)
                    if(preg_match('/http.*/i', $r))
                        $result = $result . ' ' . $r;
                        //$result = $result . ' ' . makeshortlink($r);
                    else
                        for($i=0;$i<mb_strlen($r);$i+=$limit) $result = $result . ' ' . mb_substr($r, $i, $limit);
                else 
                   $result = $result . ' ' . $r;
            }
            return $result;     
        }

        public function notice($profileID, $type, $ID2, $ID3 = null, $subtype = null){
            global $model, $db, $l;
            
            if($profileID == $this->profileID) return;
            //notice kaydı oluştur
            $n = new stdClass;
            $n->profileID = $profileID;
            $n->fromID = $model->profileID;
            $n->type = $type;
            $n->subtype = $subtype;
            $n->ID2 = $ID2;
            $n->ID3 = $ID3;
            
            $n->status      = 1;
            $n->datetime    = date('Y-m-d H:i:s');
            $n->ip          = $_SERVER['REMOTE_ADDR'];
            
            if($db->insertObject('notice', $n)){
                
                //eğer mail göndermek gerekiyorsa
                //noticemail kaydı oluştur
            
            }
        }
		public function get_otherProfile()
		{
			global $model, $db, $l;
			$SELECT	= "SELECT ht.* ";
			$FROM	= "\n FROM profile ht";
			$JOIN	= "\n INNER JOIN hashProfileRelation hrl on hrl.hashtagID=ht.ID";
			$WHERE	= "\n WHERE profileID=".$db->quote($model->profileID);
			$db->setQuery($SELECT.$FROM.$JOIN.$WHERE);  
			$response=new stdClass; 
			$tags=$db->loadObjectList();                 
            if(count($tags)){
				$response->status=true;
				$response->tag=$tags;
				return $response;
			}
			else {
				$response->status=false;
				return $response;
			}
		}
		/**
		* @param $firstTime = integer insert time (time stamp)
		* 
		*/
		public static  function get_beforeTime($firstTime){
		   
		   $difference=time()-$firstTime;
			
			$years = abs(floor($difference / 31536000));
			if($years>0)
				return $years.' yıl önce';
			$days = abs(floor(($difference-($years * 31536000))/86400));
			if($days>0)
				return $days.' gün önce';
			$hours = abs(floor(($difference-($years * 31536000)-($days * 86400))/3600));
			if($hours>0)
				return $hours.' saat önce';
			$diff = abs(floor(($difference-($years * 31536000)-($days * 86400)-($hours * 3600))/60));
			if($diff>0)
				return $diff.' dakika önce';
			
			return $difference.' saniye önce';
		   
		   
		}
		
		public static function int2trMonth($month){
			switch($month){
				case 1:	return 'Ocak';
				case 2: return 'Şubat';
				case 3: return 'Mart';
				case 4: return 'Nisan';
				case 5: return 'Mayıs';
				case 6: return 'Haziran';
				case 7: return 'Temmuz';
				case 8: return 'Ağustos';
				case 9: return 'Eylül';
				case 10: return 'Ekim';
				case 11: return 'Kasım';
				case 12: return 'Aralık';
			}
			return '';
		}
		
		
		
		public static function trMonth2int($month){
			switch($month){
				case 'Ocak' :	return 1;
				case 'Şubat':	return 2;
				case 'Mart': 	return 3;
				case 'Nisan': 	return 4;
				case 'Mayıs': 	return 5;
				case 'Haziran': return 6;
				case 'Temmuz': 	return 7;
				case 'Ağustos': return 8;
				case 'Eylül': 	return 9;
				case 'Ekim': 	return 10;
				case 'Kasım': 	return 11;
				case 'Aralık': 	return 12;
			}
			return 1;
		}
		
		public static function trSex2sex($sex){
			switch ($sex) {
				case 'Erkek': return 'male';
				case 'Kadın': return 'famele';
			}
			return '';
		}
		
		public static function sex2trSex($sex){
			switch ($sex) {
				case 'male': return 'Erkek';
				case 'famele': return 'Kadın';
			}
			return '';
		}
		
		public static function checkPermalink($perma){
			global $model,$db;
			$SELECT = "SELECT count(permalink) ";
			$FROM 	= "\n FROM profile";
			$WHERE 	= "\n WHERE ID <> " . $db->quote ( intval ( $model->profileID ) );
			$WHERE .= "\n AND status>0 ";
			$WHERE .= "\n AND permalink = '$perma'";
			$LIMIT 	= ' LIMIT 1';
			$db->setQuery ( $SELECT . $FROM  . $WHERE  .	$LIMIT );
			$rows = $db->loadResult ();
			return $rows;
		}
		
		public static function checkEmailThere($mail){
			global $model,$db;
			$SELECT = "SELECT count(email) ";
			$FROM 	= "\n FROM user";
			$WHERE 	= "\n WHERE ID <> " . $db->quote ( intval ( $model->profileID ) );
			$WHERE .= "\n AND status>0 ";
			$WHERE .= "\n AND email = '$mail'";
			$LIMIT 	= ' LIMIT 1';
			$db->setQuery ( $SELECT . $FROM  . $WHERE  .	$LIMIT );
			$rows = $db->loadResult ();
			return $rows;
		}
                
        public static function checkLogin($redirect=false,$to='/welcome',$inverse=false){
            global $model;
            if($model->profileID>0 ^ $inverse){
                return TRUE;
            }else{
                if($redirect){
                    $model->redirect($to);
                }
                return FALSE;
            }
        }
		public function addHeaderElement()
		{
			global $model;
			$this->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
			$this->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
			$this->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
			$this->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
			$this->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
			$this->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);
			$this->addScript(TEMPLATEURL."ala/js/jquery.tmpl.js", "jquery.tmpl.js", 1);
			
			$this->addScript(TEMPLATEURL."ala/js/howtouse.js", "howtouse.js", 1);
			$this->addScript(TEMPLATEURL."ala/js/jquery.scrollTo.min.js", "jquery.scrollTo.min.js", 1);
			
			$this->addScript(TEMPLATEURL."ala/fineuploader/jquery.fineuploader-3.0.js", "fileuploader-3.0.js", 1 );
			//$model->addStyle(PLUGINURL . 'lib/fineuploader/fileuploader.css', 'fileuploader.css', 1 );
			
			$model->addScript(TEMPLATEURL."ala/js/fancy/jquery.fancybox.js", "jquery.fancybox.js", 1);
			$model->addStyle(TEMPLATEURL ."ala/js/fancy/jquery.fancybox.css", "jquery.fancybox.css", 1 );
			$model->addMeta('<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>');
		}
             
    }
?>
