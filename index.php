<?php
    define('ONLINE', 1);
    define('CORE', 1);
    
    @date_default_timezone_set('Europe/Istanbul');
    
    //die('test');
    
    define('LASTELECTION', strtotime("Last Saturday"));
    define('NEXTELECTION', strtotime("Next Saturday"));
    define('LASTPROPOSAL', mktime(23,59,59,date('m'), date('d')-1, date('Y')));
    define('LASTDAY', mktime(0,0,0,date('m'), date('d')-1, date('Y')));
    define('NEXTPROPOSAL', mktime(0,0,0,date('m'), date('d')+1, date('Y')));
    define('NEXTPROPOSTAL', mktime(0,0,0,date('m'), date('d')+1, date('Y')));
    /*
    if(in_array( $_SERVER['REMOTE_ADDR'], array('193.255.28.251 ' ))){
        
        mail('kyilmazihh@gmail.com','baktı','baktı') ;
        die('<h1>Mustafa, Ayıp oluyor bak !!</h1>');
    }
    */
    if(1){
        define('DEBUG', 1);
        error_reporting(E_ALL);
        ini_set('display_errors', '1');    
    } elseif(1) {
        define('DEBUG', 0);
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', '0');         
    } else {
        define('DEBUG', 0);
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', '0');        
    }
    
    //die('working');


    

    mb_language('en');
    mb_internal_encoding("UTF-8");
    mb_regex_encoding("UTF-8");
      
    if(1 and FALSE){    
   		define('DB_NAME', 'demo');
   	 	define('DB_USER', 'demo');
    	define('DB_PASSWORD', "oF9mppHPiHbOkaZkC47roxYLFyNNxNu2");
    	//define('DB_PASSWORD', '');
    	define('DB_HOST', '178.63.46.159');
    } elseif ($_SERVER['SERVER_NAME']=="democratusala.org" or TRUE) {
        define('DB_NAME', 'demo');
        define('DB_USER', 'root');
        define('DB_PASSWORD', "56yn234rty");
        //define('DB_PASSWORD', '');
      	define('DB_HOST', 'LOCALHOST');
    }
    /* 
    define('DB_NAME', $_SERVER['DB_NAME']);
    define('DB_USER', $_SERVER['DB_USER']);
    define('DB_PASSWORD', $_SERVER['DB_PASSWORD']);     
    define('DB_HOST', 'localhost');
    */
    define('DB_PREFIX', '');
    define('DB_CHARSET', 'utf8');
    define('DB_COLLATE', '');     

    define('SLASH', '/');
    //define('SLASH', $_SERVER['SLASH']);
    
    define('SITEPATH', dirname(__FILE__) . SLASH);
    //define('SITEPATH', $_SERVER['SITEPATH']);
    
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on") 
        define('SCHEME', 'https://');
    else
        define('SCHEME', 'http://');
    
    define('SITEURL', SCHEME.$_SERVER['HTTP_HOST'].'/');
    define('SITENAME', 'DEMOCRATUS');
    
    define('PLUGINPATH', SITEPATH . "p" . SLASH);
    define('PLUGINURL', SITEURL . "p/");    
    
    define('BLOCKPATH', SITEPATH . "b" . SLASH);
    define('BLOCKURL', SITEURL . "b/");
    
    define('VIEWPATH', SITEPATH . "v" . SLASH);
    define('VIEWURL', SITEURL . "v/");
        
    define('TEMPLATEPATH', SITEPATH . "t" . SLASH);
    define('TEMPLATEURL', SITEURL . "t/");

    define('UPLOADPATH', SITEPATH . "u" . SLASH);
    define('UPLOADURL', SITEURL . "u/");
	
	define('CLASSPATH', SITEPATH . "c" . SLASH);
    define('CLASSURL', SITEURL . "c/");
        
    define('SESSIONTIMEOUT', (24 * 60 * 60)); //1 saat
    define('KEY', 'heTeb24sestAyumaXayethevayeBupaG');
    //define('KEY', '');
    
    define('DEFAULTPAGEID', '1');
    define('DEFAULTPLUGIN', 'home');    
    define('COREPATH', SITEPATH . 'c' . SLASH);
    //define('COREPATH', $_SERVER['COREPATH']);
    
    define('COOKIEPATH', '/');  
    define('COOKIEDOMAIN', $_SERVER['SERVER_NAME']);
    
    $LIKETYPES = array('dilike1','dilike2');
	//message Global Variable
	define('messageDB', "message");
	define('messageCollection', 'message');
	define('messageSize', 200);
	define('mongoASC', 1);
	define('mongoDESC', -1);
	define('getMesssageLimit', 10);
	define('mongoMessageUser','mongomessage');
	define('mongoMessagePass','70gAh5LC21');
	//message 
    require(COREPATH.'core.php');
	
	// sdasd as
	//wdas
?>
