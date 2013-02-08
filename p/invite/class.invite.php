<?php

    class invite_plugin extends control{
            /////////////////////////////////////////////////////////
            function facebook() {
            global $model,$facebook;
               
                    ?>
                <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="http://www.democratus.com" send="true" layout="button_count" width="450" show_faces="true" font=""></fb:like>  
                     
                                  
            
             <?php
            
            }
            /////////////////////////////////////////////////////////
            function i_mail(){
                
               if(!strcasecmp($_SERVER['REQUEST_METHOD'],'POST')){
                 $this->invite_post();                              } 
                 ?>
<style type="text/css">
#invitemail {
    background-color: #d7d4cb;
    background-image: url(images/share-bg.png);
    background-repeat: repeat-x;
    width:470px;
    height:80px;
    border:none;
    -moz-border-radius:7px;
    float:left;
    padding:5px;
    font-family:Arial, Helvetica, sans-serif;
    font-size:12px;
}

#invitemailsubmit {
    background-color: #833432;
    border:none;
    color: white;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 16px;
    font-weight: 700;
    height: 30px;
    left: 362px;
    background-image: url(../t/default/images/invitemailsend-bg.png);
    text-align: center;
    top: 19px;
    width: 92px;
    margin-top: 2px;
}


</style>
<h4>Enter some email addresses:</h4>
                    <form action="#" method="post" >
                    <textarea id="invitemail" name="mail" cols="20" rows="15"></textarea> 
                    <input id="invitemailsubmit"  value="Davet Et" type="submit">
                    </form>


               
               <?php
                                 }
             /////////////////////////////////////////////////////////
            function gmail() {}
             /////////////////////////////////////////////////////////
            function twitter() {
                            ?>
                    <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
                    <div>
                      <a href="http://twitter.com/share" class="twitter-share-button"
                         data-url="http://democratus.com/"
                         data-text="Democratusa davetlisiniz..."
                         data-related="democratuscom:The Javascript API"
                         data-counturl="http://democratus.com/"
                         data-count="vertical">Tweet</a>
                    </div>

                          <?php }
             /////////////////////////////////////////////////////////
            function mail_send($mailto,$frommaill){
               // require_once('../class.phpmailer.php');
                          global $model;
                
if(!$this->ismailregistered($mailto)){
    
$mail             = new phpmailer(); // defaults to using php "mail()" 
$body             = <<<HTML
<center>
<table border="0" width="555">
  <tr>
    <td bgcolor="#922A29" colspan="2"><img src="http://democratus.com/t/default/images/mailminilogo.jpg" width="108" height="29" /></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" colspan="2"><font face="Verdana, Geneva, sans-serif" size="4" color="#8E2A29">Democratus'a davetlisiniz...
    </font></td>
    </tr>
  <tr>
    <td colspan="2" valign="top" ><p><font face="Verdana, Geneva, sans-serif" size="2"> %name%
      sizi democratus'a davet etti. Katılmak için </font><a href="http://democratus.com/"><font face="Verdana, Geneva, sans-serif" size="2" color="#990033">tıklayın</font></a>.
      </p>
    </p></td>
    </tr>
  <tr>
    <td width="176"><a href="http://democratus.com/"><font color="#FFFFFF"><img src="http://democratus.com/t/default/images/footer-logo.png" alt="" width="175" border="0" height="46" /></font></font></a></td>
    <td width="434"><center><font face="Verdana, Geneva, sans-serif" size="1">
      Democratus ile yeni arkadaşlar edinin.<br />
Düşüncelerinizi tüm dünya ile paylaşın.<br />
Gündemi siz belirleyin.</font>
    </center>
    </td>
    </tr>
</table>
<p>&nbsp;</p>
</center>
HTML;

$body = str_replace('%name%', $model->profile->name, $body );

$body             = eregi_replace("[\]",'',$body);

//$mail->AddReplyTo("democratus@democratus.com","First Last");

$mail-> SetFrom('democratus@democratus.com', 'Democratus');
$address = $mailto;
$mail->AddAddress($address, "Davet");
$mail->Subject    = "Democratus.com a davetlisiniz.";
$mail->MsgHTML($body);
if(!$mail->Send()) {
  echo $mailto . 'Davet gönderilemedi.<br />';
} else {
  echo $mailto.'  Davet gönderildi.<br /> '; 
}                       
               } else {
                    echo $mailto.' zaten bir democratus kullanıcısı.<br />';
                }  
            }  
            /////////////////////////////////////////////////////////
            function invite_post() { 
                     global $model;
                     $frommail=$model->profile->name;
                      //$frommail='aa dd';  
                $rawdata = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_STRING);
                
                $result = preg_match_all('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $rawdata, $match);
                if($result)
                foreach($match[0] as $maill){
                    
                $this->mail_send($maill,$frommail);
                
                
                
            }
            }  
        public function main(){
            try{ 
                
            global $model, $db;
               if (!$model->user) throw new Exception('Üye girişi yapınız');
               ?>

             
<div class="middlebox">
<div class="middlebox-head"><h3>Başkalarını Democratus'a davet et!</h3></div>
<div class="middlebox-body"> <?php
               
            echo '<a href="http://democratus.com/invite/mail"> <img src="http://democratus.com/t/default/images/m.jpg"> </a>';
            echo '<a href="http://democratus.com/invite/facebook"> <img src="http://democratus.com/t/default/images/F.png">  </a>'; 
            
            echo '<a href="http://democratus.com/invite/twitter"> <img src="http://democratus.com/t/default/images/t.png"> </a>' ;
            echo '<a href="http://democratus.com/invite/gmail"> <img src="http://democratus.com/t/default/images/G.png"> </a> <br /><br />';
 
            switch($model->paths[1])
            {
                
                case 'mail' : $this->i_mail(); break;
                case 'facebook' : $this->facebook(); break;
                case 'gmail' : $this->gmail(); break;
                case 'twitter' : $this->twitter(); break;
                default : $this->i_mail(); break;
                
            }
            ?>
            <br class="clearfix"></div>
<div class="middlebox-footer">&nbsp;</div></div>
          <?php
            }
            catch (exeption $e){
                echo 'hata var'.$e->getMessage();
            }
            return;
            
            if($model->userID<1)
                return $this->notloggedin();
            
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            
            $share = array();
            $share ['title'] = $title;
            
            $share ['datetime'] = date('Y-m-d H:i:s');
            $share ['profileID'] = intval( $model->user->profileID );
            $share ['sharerID'] = intval( $model->user->profileID );
            $share ['userID'] = intval( $model->user->ID );
            $share ['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
            
            $share = (object) $share;
            
            if( $db->insertObject('#__share', $share) ){
                echo '<strong>success</strong>';
            } else {
                echo '<strong>error</strong>';
            }
        }
            /////////////////////////////////////////////////////////
        public function ismailregistered($mail){
            global $model, $db;
            
            $db->setQuery("SELECT ID FROM user WHERE email=" . $db->quote($mail) );
            $result = null;
            if($db->loadObject($result)){
                return $result->ID;
            } else {
                return false;
            }
        }
             /////////////////////////////////////////////////////////
    
    }
?>
