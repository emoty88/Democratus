<?php
    //die('default_view dosyası');
    class simple_view extends view{
        public $name = 'simple';
        
        public function main(){
            global $model, $db, $l;
            
            echo '<html lang="en">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Democratus</title>
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
            
            
            {{main}}
            
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
            <p style="font-size: 11px; color:#7d7a7a; margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">&nbsp;</td>
        </tr>
      </table>
    <!-- footer--></td>
  </tr>
</table>
</body>
</html>';
        }
        
    }
?>