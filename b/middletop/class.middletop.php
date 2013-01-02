<?php
    class middletop_block extends control{
        
        public function block(){
            global $model, $db, $l;
            
            if($model->userID<1){
?>
      <!--middle top-->
      <div id="middletop">
        <ul>
          <li><a href="/">Anasayfa</a></li>
          <li><a href="/archive">Arşiv</a></li>
          <li><a href="/user/login">Giriş</a></li>
          <li><a href="/user/new">Üye ol</a></li>
        </ul>
      </div>
      <!--middle top END-->
<?php

            } else {
?>
      <!--middle top-->
      <div id="middletop">
        <ul>
          <li><a href="/">Anasayfa</a></li>
          <li><a href="/profile/<?=$model->profileID;?>">Profil</a></li>
          <li><a href="/my/photos">Fotograflar</a></li>
          <li><a href="/my/settings">Ayarlar</a></li>
          <li><a href="/search/user/">Ara</a></li>
          <li><a href="/election/">Vekillerim</a></li>
          <li><a href="/user/logout">Çıkış</a></li>
        </ul>
      </div>
      <!--middle top END-->
<?php                
            }
        }
    }
?>