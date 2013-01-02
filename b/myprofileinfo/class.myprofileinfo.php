<?php
    class myprofileinfo_block extends control{
        
        public function block(){
            global $model, $db, $l;
            
?>
    <!-- myprofileinfo start -->
    
    <div id="profileimg"><a href="/my/photos"><img src="<?=$model->getProfileImage($model->profile->image, 120,120, 'cutout')?>" width="120" height="120" alt="" /></a></div>
        <div style="float:left; width:217px;">
          <h3 id="profilename"><?=$model->profile->name?></h3>
          <ul id="profilemenu">
            <li><a href="/my/profile">Profilim</a></li>
            <li><a href="/my/privacy">Genel Ayarlar</a></li>
            <li><a href="/user/logout">Çıkış</a></li>
          </ul>
        </div>
      <!-- myprofileinfo end -->
<?php
        }
    }
?>