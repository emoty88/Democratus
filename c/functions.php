<?php
  /*
  
  */

 
//defined( 'ATOM' ) or die( '' ); 

$dbNameQuote = '`';

function makeshortlink($link, $target='_blank')
{
    global $model;
    if($model->profileID==1001){
        //die($link);
    }
    $p = parse_url( trim( $link ) );
    $r = '<a href="'.$link.'" target="'.$target.'">';
    $r.= $p['host'];
    $q = $p['path'].$p['query'];
    
    if(strlen($q))
        //$r .= substr($q, 0, 5) . '..';
        $r .= '(...)';
        
    $r.= '</a>';
    return $r;
}


function make_clickable($text)
{
    $ret = ' ' . $text;
    $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a target=\"_blank\" href=\"\\2\" >\\2</a>'", $ret);
    $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a target=\"_blank\" href=\"http://\\2\" >\\2</a>'", $ret);
    $ret = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
    $ret = substr($ret, 1);
    return($ret);
}

function resizeembed($embed, $width, $height){
        //$embed = stripslashes( $embed );
        $patterns = array('/(width\s*=\s*["\'])[0-9]+(["\'])/i','/(height\s*=\s*["\'])[0-9]+(["\'])/i');
        $replaces = array('${1}'.$width.'${2}','${1}'.$height.'${2}'); 
        $embed = preg_replace($patterns, $replaces, $embed);
        return $embed;
}

function time_left($original) {
    // array of time period chunks
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'yıl'),
        array(60 * 60 * 24 * 30 , 'ay'),
        array(60 * 60 * 24 * 7, 'hafta'),
        array(60 * 60 * 24 , 'gün'),
        array(60 * 60 , 'saat'),
        array(60 , 'dakika'),
    );

    $today = time(); /* Current unix time  */
    $since = $original - $today;
    $since =$original -   $today ;
    // $j saves performing the count function each time around the loop

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }
    $print = ($count == 1) ? '1 '.$name : "$count {$name}";

    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];

        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}";
        }

    }
    return $print;
}


function time_since($original) {
    // array of time period chunks
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'yıl'),
        array(60 * 60 * 24 * 30 , 'ay'),
        array(60 * 60 * 24 * 7, 'hafta'),
        array(60 * 60 * 24 , 'gün'),
        array(60 * 60 , 'saat'),
        array(60 , 'dakika'),
    );

    $today = time(); /* Current unix time  */
    $since = $today - $original;
    // $j saves performing the count function each time around the loop

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }
    $print = ($count == 1) ? '1 '.$name : "$count {$name}";

    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];

        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}";
        }

    }
    return $print;
} 


  function shuffle_assoc(&$array) {
    if (count($array)>1) { //$keys needs to be an array, no need to shuffle 1 item anyway
      $keys = array_rand($array, count($array));

      foreach($keys as $key)
        $new[$key] = $array[$key];

      $array = $new;
    }
    return true; //because it's a wannabe shuffle(), which returns true
  } 


function strip_tags_attributes($sSource, $aAllowedTags = '', $aDisabledAttributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload', 'rel', 'target'))
    {
        if (empty($aDisabledAttributes)) return strip_tags($sSource, $aAllowedTags);

        return preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $aDisabledAttributes) . ")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($sSource, $aAllowedTags));
    }



      function phaseWords($content, $carpan = 1 ){
        $wordLengthMin = 2;
        
        
        //convert all characters to lower case
        $content = mb_strtolower($content);
        //$content = mb_strtolower($content, "UTF-8");
        $content = strip_tags($content);        
        
        //updated in v0.3, 24 May 2009
        $punctuations = array(',', ')', '(', '.', "'", '"','”', '“', '’','‘',
        '<', '>', '!', '?', '/', '-',
        '_', '[', ']', ':', '+', '=', '#', '%', '&','…',
        '$', '&quot;', '&copy;', '&gt;', '&lt;', 
        '&nbsp;', '&trade;', '&reg;', ';', 
        chr(10), chr(13), chr(9));

        $content = str_replace($punctuations, " ", $content);
        // replace multiple gaps
        $content = preg_replace('/ {2,}/si', " ", $content);
        
        $s = split(" ", $content);
        //initialize array
        $k = array();
        //iterate inside the array
        foreach( $s as $key=>$val ) {
            //delete single or two letter words and
            //Add it to the list if the word is not
            //contained in the common words list.
            if(mb_strlen(trim($val)) >= $wordLengthMin)
                $k[] = trim($val);
        }
        //count the words
        $k = array_count_values($k);
        foreach($k as &$kv) $kv = $kv * $carpan;
        
        return array_keys( $k );
    }      
    
    function phaseiWords($content, $carpan = 1 ){
        $wordLengthMin = 2;
        
        
        //convert all characters to lower case
        //$content = mb_strtolower($content);
        //$content = mb_strtolower($content, "UTF-8");
        $content = strip_tags($content);        
        
        //updated in v0.3, 24 May 2009
        $punctuations = array(',', ')', '(', '.', "'", '"','”', '“', '’','‘',
        '<', '>', '!', '?', '/', '-',
        '_', '[', ']', ':', '+', '=', '#', '%', '&','…',
        '$', '&quot;', '&copy;', '&gt;', '&lt;', 
        '&nbsp;', '&trade;', '&reg;', ';', 
        chr(10), chr(13), chr(9));

        $content = str_replace($punctuations, " ", $content);
        // replace multiple gaps
        $content = preg_replace('/ {2,}/si', " ", $content);
        
        $s = split(" ", $content);
        //initialize array
        $k = array();
        //iterate inside the array
        foreach( $s as $key=>$val ) {
            //delete single or two letter words and
            //Add it to the list if the word is not
            //contained in the common words list.
            if(mb_strlen(trim($val)) >= $wordLengthMin)
                $k[] = trim($val);
        }
        //count the words
        $k = array_count_values($k);
        foreach($k as &$kv) $kv = $kv * $carpan;
        
        return array_keys( $k );
    }
    
    
    function cleanHTML($html, $charset='utf8'){

        $config =  array(
        'clean' => 0,
        'merge-divs' => 1,
        'drop-proprietary-attributes' => 1,
        'output-xhtml' => 1,
        'show-body-only' => 0,
        'word-2000' => 1,
        'indent' => 0,
        'marcup' => 0,
        'wrap' => 0,
        'show-errors' => 0,
        'show-warnings' => 0
        );

        // Tidy
        $tidy = new tidy();
        $tidy->parseString($html, $config, $charset);
        $tidy->cleanRepair();
        //echo $html;
        //echo tidy_get_output($tidy);
        return tidy_get_output($tidy);        
    }
    
    function cleanHTMLBody($html, $charset='utf8'){
        
        $config =  array(
        'clean' => 0,
        'merge-divs' => 1,
        'drop-proprietary-attributes' => 1,
        'output-xhtml' => 1,
        'show-body-only' => 1,
        'word-2000' => 1,
        'indent' => 0,
        'marcup' => 0,
        'wrap' => 0,
        'show-errors' => 0,
        'show-warnings' => 0
        ); 

        // Tidy
        $tidy = new tidy();
        $tidy->parseString($html, $config, $charset);
        $tidy->cleanRepair();
        return tidy_get_output($tidy);
    }

    function asdatetime($datestring, $format=null){
        if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/i', $datestring))
            $date = strtotime( $datestring );
        elseif(preg_match('/\d{4}-\d{2}-\d{2}/i', $datestring))
            $date = strtotime( $datestring );
        else 
            return null;
            
        if(is_null($format))
            return $date;
        else
            return date($format, $date);
            
    }
    
/**
* converts given date string to given formatted date string
* 
* @param string $dt
* @param mixed $format
* @return string
*/
function asDate($dt, $format='Y-m-d H:i:s'){
         
         if(is_null($dt)) return null;
         $dt = trim($dt);
         if(strlen($dt)<4) return null;
         
         $dtt = explode('/',trim($dt));
         
         if(count($dtt)==3){
             $date = new DateTime;
             
             $date->setDate($dtt[2],$dtt[1],$dtt[0]);
             
             if( strtotime( $date->format('Y-m-d H:i:s') )<time() && strtotime( $date->format('Y-m-d H:i:s') )>mktime(0,0,0,1,1,1950))
                return $date->format($format);
             else
                return null;
         }
         else{
         
             preg_match_all('/[0-9]+/i',$dt,$a);
             $asnumber = intval(implode('',$a[0]));
             
             if(intval($asnumber)<=intval(date('Y')) && intval($asnumber)>1950)
                 return date($format, mktime(0,0,0,1,1, intval($asnumber) ));
             elseif(strtotime($dt) < time() && strtotime($dt)>mktime(0,0,0,1,1,1950) )
                 return date($format, strtotime($dt));
             elseif(strtotime($dt) != strtotime(str_replace('.','/',$dt)))
                 return strtotime(str_replace('.','/',$dt));
             else{
                 return null;
             }  
         }
     }

function limit_words($str, $length){
  $str = strip_tags($str);
  $str = explode(" ", $str);
  return implode(" " , array_slice($str, 0, $length));
}


if(!function_exists('seola')) {
    function seola($s) {
        $tr = array('ş','Ş','ı','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç');
        $eng = array('s','s','i','i','g','g','u','u','o','o','c','c');
        $s = str_replace($tr,$eng,$s);
        $s = strtolower($s);
        $s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
        $s = preg_replace('/[^%a-z0-9 _-]/', '', $s);
        $s = preg_replace('/\s+/', '-', $s);
        $s = preg_replace('|-+|', '-', $s);
        $s = trim($s, '-');
        return $s;
    }
}

function strtouppertr($s){
    $tr = array('ş','ı','i','ğ','ü','ö','ç');
    $up = array('Ş','I','İ','Ğ','Ü','Ö','Ç');
    return strtoupper( str_replace($tr,$up,$s) );
}

/**
* obj2array: converts objects to array
*  
* @param array $data
* @return bool
*/
function obj2array ( $data ){
    if (is_object($data)) $data = get_object_vars($data);
    return (is_array($data)) ? array_map(__FUNCTION__,$data) : $data;
}

/**
* xml2array: converts xml strings to array
* 
* @param mixed $originalXML
* @param mixed $attributes
*/
function xml2array($originalXML, $attributes=true)
{
        $xmlArray = array();
        $search = $attributes ? '|<((\S+)(.*))\s*>(.*)</\2>|Ums' : '|<((\S+)()).*>(.*)</\2>|Ums';
       
        // normalize data
        $xml = preg_replace('|>\s*<|', ">\n<", $originalXML); // one tag per line
        $xml = preg_replace('|<\?.*\?>|', '', $xml);            // remove XML declarations
        $xml = preg_replace('|<(\S+?)(.*)/>|U', '<$1$2></$1>', $xml); //Expand singletons
       
        if (! preg_match_all($search, $xml, $xmlMatches))
                return trim($originalXML);      // bail out - no XML found
               
        foreach ($xmlMatches[1] as $index => $key)
        {
                if (! isset($xmlArray[$key])) $xmlArray[$key] = array();       
                $xmlArray[$key][] = xml2array($xmlMatches[4][$index], $attributes);
        }
        return $xmlArray;
}

    
function array2xml($array, $rootName, $encoding='utf-8'){
    function addArray(&$doc, $arr, &$n, $name=""){
        //global $doc;
        foreach ($arr as $key => $val){
            if (is_int($key)){
                if (strlen($name)>1){
                    $newKey = substr($name, 0, strlen($name)-1);
                    }else{
                    $newKey="item";
                }
                }else{
                $newKey = $key;
            }
            $node = $doc->createElement($newKey);
            if (is_array($val)){
                addArray($doc, $arr[$key], $node, $key);
                }else{
                $nodeText = $doc->createTextNode($val);
                $node->appendChild($nodeText);
            }
            $n->appendChild($node);
        }
    }
    if (!is_array($array) || count($array) == 0){
        return null;
    }
    $doc = new domdocument("1.0", $encoding);
    $arr = array();
    if (count($array) > 1){
        if ($rootName != ""){
            $root = $doc->createElement($rootName);
            }else{
            $root = $doc->createElement("root");
            $rootName = "root";
        }
        $arr = $array;
        }else{
        $key = key($array);
        $val = $array[$key];
        if (!is_int($key)){
            $root = $doc->createElement($key);
            $rootName = $key;
            }else{
            if ($rootName != ""){
                $root = $doc->createElement($rootName);
                }else{
                $root = $doc->createElement("root");
                $rootName = "root";
            }
        }
        $arr = $array[$key];
    }
    $root = $doc->appendchild($root);
    addArray($doc,$arr, $root, $rootName);
    return $doc->saveHTML();
}

/****************************************************************/

/****************************************************************/

if(!function_exists('write_ini_file')) { 
  function write_ini_file($path, $assoc_array) {

   foreach($assoc_array as $key => $item) {
     if(is_array($item)) {
       $content .= "\n[{$key}]\n";
       foreach ($item as $key2 => $item2) {
         if(is_numeric($item2) || is_bool($item2))
           $content .= "{$key2} = {$item2}\n";
         else
           $content .= "{$key2} = \"{$item2}\"\n";
       }       
     } else {
       if(is_numeric($item) || is_bool($item))
         $content .= "{$key} = {$item}\n";
       else
         $content .= "{$key} = \"{$item}\"\n";
     }
   }       

   if(!$handle = fopen($path, 'w')) {
     return false;
   }

   if(!fwrite($handle, $content)) {
     return false;
   }

   fclose($handle);
   return true;

  }

}

if(!function_exists('write_ini_string')) {
  function write_ini_string($assoc_array) {
   $content='';
   foreach($assoc_array as $key => $item) {
     if(is_array($item)) {
       $content .= "\n[{$key}]\n";
       foreach ($item as $key2 => $item2) {
         if(is_numeric($item2) || is_bool($item2))
           $content .= "{$key2} = {$item2}\n";
         else
           $content .= "{$key2} = \"{$item2}\"\n";
       }       
     } else {
       if(is_numeric($item) || is_bool($item))
         $content .= "{$key} = {$item}\n";
       else
         $content .= "{$key} = \"{$item}\"\n";
     }
   }       

   return $content;

  }

}

if(!function_exists('parse_ini_string'))
{
  function parse_ini_string($ini, $process_sections = false, $scanner_mode = null)
  {
    # Generate a temporary file.
    //$tempname = COREPATH.'tmp/tmp.txt'; //tempnam( '/tmp', 'ini');
    $tempname = tempnam( '/tmp', 'ini');
    $fp = fopen($tempname, 'w');
    fwrite($fp, $ini);
    $ini = parse_ini_file($tempname, !empty($process_sections));
    fclose($fp);
    @unlink($tempname);
    return $ini;
  }
}


/**
 * returns output of print_r as string
 * @package net.nemein.opendeploydumper
 */
if (!function_exists('sprint_r')) {
    function sprint_r($var) {
        ob_start();
        print_r($var);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }
}

function debug( $param=null ) {
    if (DEBUG) echo $param."<br/>\n";
} 

function isEmail( $email ) {
    $valid = preg_match( '/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email );

    return $valid;
}  

function parseURL(&$param=null)
{
   
    $url=$param;
    $parsedURL=parse_url($url);
    //echo "<url>".$url."</url>";
   
    
    
    if (substr_count($url,'/?')>0 && (isset($parsedURL['query']) && substr_count($parsedURL['query'],'=')==0)) 
    {
        $url=substr($url,0,strpos($url,'/?')).'/'.substr($url,strpos($url,'/?')+2); 
        //echo "<url>".$url."</url>"; 
    }
    elseif (substr_count($url,'?')>=2) 
    {
        if (substr_count($url,'/?')>0)
            $url=$url=substr($url,0,strpos($url,'/?')).'/'.substr($url,strpos($url,'/?')+2);
        else
            $url=substr($url,0,strpos($url,'?')).'/'.substr($url,strpos($url,'?')+1);
        //echo "<url2>".$url."</url2>"; 
    } 
    $parsedURL=parse_url($url);
    
    $parsedURL['host']=strtolower( $parsedURL['host'] );
    $parsedURL['scheme']=strtolower( $parsedURL['scheme'] );
    //$param=$url;
    
    return $parsedURL;
} //    parseURL 
    
      
  //_GET değişkeninden veri almak için
    function gG($var) {
        if(!isset($_GET[$var])) return null;
        $search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'"); 
        $replace = array ("","","",);  
        $searcha = array ("\"","\\","\/","<",">","--",") or","%","'","\"","\'",'"'); 
        $replacea = array ("","","","","","","","","","","","");  
        global $_GET;
        $deger=preg_replace ($search, $replace, $_GET[$var]);
        return str_replace($searcha,$replacea,$deger);
    }
             
    function gP($var) {
      if(!isset($_POST[$var])) return null;
        $search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'"); 
        $replace = array ("","","",);  
        $searcha = array ("\"","\\","\/","<",">","--",") or","%","'","\"","\'",'"'); 
        $replacea = array ("","","","","","","","","","","","");  
        global $_POST;
        $deger=preg_replace ($search, $replace, $_POST[$var]);
        return str_replace($searcha,$replacea,$deger);
    }

/**
* Cleans text of all formating and scripting code
*/
function cleanText ( &$text ) {
        $text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
        $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
        $text = preg_replace( '/<!--.+?-->/', '', $text );
        $text = preg_replace( '/{.+?}/', '', $text );
        $text = preg_replace( '/&nbsp;/', ' ', $text );
        $text = preg_replace( '/&amp;/', ' ', $text );
        $text = preg_replace( '/&quot;/', ' ', $text );
        $text = strip_tags( $text );
        $text = htmlspecialchars( $text );

        return $text;
}

function C($var,$val=NULL,$days=90){
    if(!headers_sent() && isset($val)){
        $time = (!empty($val)) ? time()+($days*60*60*24) : time()-2000;
        setcookie($var,$val,$time,"/");
    }
    return $_COOKIE[$var];
}

function G($var){
    return (isset($_GET[$var])) ? $_GET[$var] : null;
}

function P($var){
    return (isset($_POST[$var])) ? $_POST[$var] : null;
}

function S($var,$val=NULL){
    if($val) { $_SESSION[$var] = $val; }
    return $_SESSION[$var];
}

function stripslashes_if_gpc( $string ) {
    if(get_magic_quotes_gpc()) {
        return stripslashes($string);
    } else {
        return $string;
    }
}
function addslashes_if_gpc( $string ) {
    if(!get_magic_quotes_gpc()) {
        return addslashes($string);
    } else {
        return $string;
    }
}
/**
* verilen tamsayiyi key olarak uretir
* Ornek: 
* createKeyID(100,0); // sonuc: bM
* createKeyID(100,7); // sonuc: 64 
* createKeyID(100,0,'123'); // sonuc: 21312
* 
* @param mixed $intValue
* @param mixed $dictionaryID
* @param mixed $myDictionary
*/
function createKeyID($intValue, $dictionaryID = 1, $myDictionary = '') {
    $intValue = (int) $intValue;
    
    $dic[0] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $dic[1] = 'iylNJjxzU4OrS0ZsdRq78P1g5G2ufDoHcnpt69QAmEaChYbBv3eLMXWkTIwKVF'; //a-zA-Z0-9
    $dic[2] = 'xsrdmzkpebglctaqiunfyjvwoh';//a-z
    $dic[4] = 'xs64uiav9lyrmpgh73tzef8dk1j20n5bwqoc';//a-z0-9
    $dic[5] = 'QEVRISNAWLUOTJBGPFKDHYMCZX';//A-Z 
    $dic[7] = 'TRU7I9JM4PSF6ZV8Q2GWKYBXLN15D0H3AOCE';//A-Z0-9
    $dic[6] = "0123456789ABCDEF";// hexadecimal
    
    $dictionary = $myDictionary==''?$dic[$dictionaryID]:$myDictionary;

    $dc = strlen($dictionary);
    $iv = $intValue;
    
    $key = '';
    
    do {
        $id = $iv % $dc;
        $key = $dictionary[$id].$key;
        $iv = floor($iv / $dc);
    }while($iv>0);
    
    return $key;
}

function getEscaped( $text ) {
    /*
    * Use the appropriate escape string depending upon which version of php
    * you are running
    */
    if (version_compare(phpversion(), '4.3.0', '<')) {
        $string = mysql_escape_string($text);
    } else     {
        $string = mysql_real_escape_string($text);
    }

    return $string;
}

function getEscaped_sil( $text, $extra = false ) {
    $string = mysql_real_escape_string( $text );
    if ($extra) {
        $string = addcslashes( $string, '%_' );
    }
    return $string;
}

function Quote( $text, $escaped = true )
{
    return '\''.$text.'\'';
}

function QuoteName( $text, $escaped = true )
{
    return '`'.$text.'`';
}

function dbNumRows($query){
    //$query="select * from cms_session where sesID=".session_id()."limit 1";
    $result = mysql_query($query);
    //if($result) 
        $count= mysql_num_rows($result); 
    //else
      //  $count= 0;
    //mysql_freeresult($result);
    
    return $count;
}

function ErrorAlert( $text, $action='window.history.go(-1);', $mode=1 ) {
    $text = nl2br( $text );
    $text = addslashes( $text );
    $text = strip_tags( $text );

    switch ( $mode ) {
        case 2:
            echo "<script>$action</script> \n";
            break;

        case 1:
        default:
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html; "._ISO."\" />";
            echo "<script>alert('$text'); $action</script> \n";
            //echo '<noscript>';
            //mosRedirect( @$_SERVER['HTTP_REFERER'], $text );
            //echo '</noscript>';
            break;
    }

    exit;
} 

function CreateGUID(){
    srand((double)microtime()*1000000);
    $r = rand();
    $u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
    $m = md5 ($u);
    return($m);
}

function CompressID( $ID ){
    return(Base64_encode(pack("H*",$ID)));
}

function ExpandID( $ID ) {
    return ( implode(unpack("H*",Base64_decode($ID)), '') );
}

function makePassword($length=8) {
    $salt         = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";//echo strlen($salt); //62
    $makepass    = '';
    mt_srand(10000000*(double)microtime());
    for ($i = 0; $i < $length; $i++)
        $makepass .= $salt[mt_rand(0,61)];
    return $makepass;
}

function makeLPassword($length=8) {
    $salt         = "abcdefghijklmnopqrstuvwxyz0123456789";//echo strlen($salt); //62
    $makepass    = '';
    mt_srand(10000000*(double)microtime());
    for ($i = 0; $i < $length; $i++)
        $makepass .= $salt[mt_rand(0,35)];
    return $makepass;
}

function ampReplace( $text ) {
    $text = str_replace( '&&', '*--*', $text );
    $text = str_replace( '&#', '*-*', $text );
    $text = str_replace( '&amp;', '&', $text );
    $text = preg_replace( '|&(?![\w]+;)|', '&amp;', $text );
    $text = str_replace( '*-*', '&#', $text );
    $text = str_replace( '*--*', '&&', $text );

    return $text;
}

function FormatDate( $date, $format="", $offset=NULL ){
    global $mosConfig_offset;
    if ( $format == '' ) {
        // %Y-%m-%d %H:%M:%S
        $format = _DATE_FORMAT_LC;
    }
    if ( is_null($offset) ) {
        $offset = $mosConfig_offset;
    }
    if ( $date && ereg( "([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})", $date, $regs ) ) {
        $date = mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );
        $date = $date > -1 ? strftime( $format, $date + ($offset*60*60) ) : '-';
    }
    return $date;
}  


function CurrentDate( $format="" ) {
    global $mosConfig_offset;
    if ($format=="") {
        $format = _DATE_FORMAT_LC;
    }
    $date = strftime( $format, time() + ($mosConfig_offset*60*60) );
    return $date;
}

function clean_url( $title ) {
    $title = strip_tags($title);
    // Preserve escaped octets.
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    $title = str_replace('%‘’’’‘', '', $title);
    // Restore octets.
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    $title = remove_accents($title);
    if (seems_utf8($title)) {
        if (function_exists('mb_strtolower')) {
            $title = mb_strtolower($title, 'UTF-8');
        }
        $title = utf8_uri_encode($title, 200);
    }

    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = preg_replace('@[^A-Za-z0-9\-_.]+@i', "-", $title);
    
    $search = array('e2-80-9c', 'e2-80-99', 'e2-80-9d');
    $title = str_replace($search, '', $title);
    $title = trim($title, '-');    
    
    return $title;
}

function utf8_uri_encode( $utf8_string, $length = 0 ) {
    $unicode = '';
    $values = array();
    $num_octets = 1;

    for ($i = 0; $i < strlen( $utf8_string ); $i++ ) {

        $value = ord( $utf8_string[ $i ] );

        if ( $value < 128 ) {
            if ( $length && ( strlen($unicode) + 1 > $length ) )
                break; 
            $unicode .= chr($value);
        } else {
            if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

            $values[] = $value;

            if ( $length && ( (strlen($unicode) + ($num_octets * 3)) > $length ) )
                break;
            if ( count( $values ) == $num_octets ) {
                if ($num_octets == 3) {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
                } else {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
                }

                $values = array();
                $num_octets = 1;
            }
        }
    }

    return $unicode;
}

function seems_utf8($Str) { # by bmorel at ssi dot fr
    for ($i=0; $i<strlen($Str); $i++) {
        if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
        elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
            return false;
        }
    }
    return true;
}

function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    if (seems_utf8($string)) {
        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
        // Euro Sign
        chr(226).chr(130).chr(172) => 'E',
        // GBP (Pound) Sign
        chr(194).chr(163) => '');

        $string = strtr($string, $chars);
    } else {
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
            .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
            .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
            .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
            .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
            .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
            .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
            .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
            .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
            .chr(252).chr(253).chr(255);

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
        $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

function trtoeng($metin)
{
    $trharf = array("ü", "ğ", "ı", "ş", "ç", "ö", "Ü", "Ğ", "İ", "Ş", "Ç", "Ö");
    $enharf = array("u", "g", "i", "s", "c", "o", "U", "G", "I", "S", "C", "O");
    return str_replace($trharf, $enharf, $metin);
}
function redirect($url) {
    die('aaa');
    //$_SESSION['cmsRedirect']=null;
    if (!headers_sent())
        header('Location: '.$url);
    else {
       echo '<script type="text/javascript">';
       echo 'window.location.href="'.$url.'";';
       echo '</script>';
       echo '<noscript>';
       echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
       echo '</noscript>';
    }
    exit;
}
// ??
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
/*****************************************************************************************************/             
function file_extension($filename)
{
    $path_info = pathinfo($filename);
    return $path_info['extension'];
}
/*****************************************************************************************************/             
function resize_image_sil ($inputFilename, $outputFilename, $width, $height, $type='image/jpeg'){
    $imagedata = getimagesize($inputFilename);
    
    $w = $imagedata[0];
    $h = $imagedata[1];
    
    $a=($w * $height)/$h;
    if ($a<=$width) {
        $new_w = ($w/$h)*$height;
        $new_h = $height;
    } 
    else {
        $new_w = $width; //($w/$h)*$height;
        $new_h = ($h/$w)*$width;//  $height;        
    }
        
    $im2 = null;//ImageCreateTrueColor($new_w, $new_h);
    switch ($type){ 
    case 'image/gif':
        $image = imagecreatefromgif($inputFilename);
        imagecopyresampled($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
        imagegif( $im2, $outputFilename);
    break;
    case 'image/jpeg':
        $image = imagecreatefromjpeg($inputFilename);
        imagecopyresampled($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
        imagejpeg( $im2, $outputFilename );
    break;
    case 'image/pjpeg':
        $image = imagecreatefromjpeg($inputFilename);
        imagecopyresampled($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
        imagejpeg( $im2, $outputFilename);
    break;    
    case 'image/png':
        $image = imagecreatefrompng($inputFilename);
        imagecopyresampled($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
        imagepng( $im2, $outputFilename );
    break;
    default:
        //$image = imagecreatefromjpeg($inputFilename);
        return null;
    }               
    return 1;
}  
?>