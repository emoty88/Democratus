<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<?php
    global $model;
    //$model->addStyle($this->url . 'reset.css', 'reset.css', 1 );
    $model->addStyle($this->url . 'style.css', 'style.css', 1 );
    
?>
</head>
<body>
<div id="wrapper">
  <div id="container">
    <div id="left">
      <div id="logo"><img src="<?=$this->url?>images/logo.png" width="370" height="94" alt="" /></div>
      <div id="profileinfo">
        <p>deneme deneme deneme </p>
        <p>asdfaf</p>
<p>asdfas</p>
        <p>dfasdf</p>
        <p>asdf<img src="<?=$this->url?>images/profile_footer.png" width="360" height="10" alt="" /></p>
      </div>
      <div id="leftbody">
      
      {{left}}
      
      </div>
    </div>
    <div id="middle">
      <div id="middletop">menu | menu | menu</div>
      
      {{agenda}}
      
      <hr />
      
      {{main}}
      
    </div><div class="clearfix">&nbsp;</div>
  </div>
  
</div>
<div id="footerwrapper">
  <div id="footer"><img src="<?=$this->url?>images/footer-logo-vs.png" width="746" height="60" alt="" /></div>
</div>
</body>
</html>