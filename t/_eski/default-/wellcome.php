<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<?php
    global $model;
    $model->addStyle($this->url . 'style.css', 'style.css', 1 );  
?>
</head>
<body>
<div id="wrapper">
  <div id="container" class="wellcome">
        
    {{main}}
    
    <div class="clearfix">&nbsp;</div>
  </div>
</div>
<div id="footerwrapper">
  <div id="footer">
    {{footer}}
  </div></div>
</body>
</html>