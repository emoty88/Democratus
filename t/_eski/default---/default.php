<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="reset.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/easySlider1.5.js"></script>
<script type="text/javascript">
        $(document).ready(function(){    
            $("#slider").easySlider({
                auto: true,
                continuous: true,
                speed: 500,
                pause: 4000,
                prevText: '',
                nextText: ''

            });
        });    
    </script>
</head>

<?php
    global $model;
    
    $model->addStyle($this->url . 'reset.css', 'reset.css', 1 );
    $model->addStyle($this->url . 'style.css', 'style.css', 1 );
    
    /*
    $model->addScript($this->url . 'js/jquery.js', 'jquery.js', 1 );
    $model->addScript($this->url . 'js/easySlider1.5.js', 'easySlider1.5.js', 1 );
    $model->addScript('$(document).ready(function(){    
            $("#slider").easySlider({
                auto: true,
                continuous: true,
                speed: 500,
                pause: 4000,
                prevText: "",
                nextText: ""
            });
        }); ');
        
      */
    
    
?>
<body>
  <div id="container">  
  <div id="header">
<?php
    if($model->userID>0){
        echo 'ID:' . $model->userID . ' email: ' .  $model->user->email;
        echo '<span style="float:right"><a href="/user/logout/">logout</a></span>';
    } else { 
        echo 'not logged in';
        echo '<span style="float:right"><a href="/user/login/">login</a></span>';
    }
    echo $l['home'];
?>  
  &nbsp;
  </div>
  
  <div id="left">{{left}}&nbsp;</div>
  <div id="middle">{{main}}&nbsp;</div>
  <div id="right">{{right}}&nbsp;</div>
  <div id="footer">
<?php
    echo 'querycount:'.$db->_ticker;
?>  
  &nbsp;
  </div>
  </div>
</body>
</html>
