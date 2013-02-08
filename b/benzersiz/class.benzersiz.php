<?php
    class benzersiz_block extends control{
        public function block(){
            global $model, $db, $l; 
			if($model->profile->permalink=="")
			{
            ?>
            	<div class="roundedcontent cast">
            	<h1>
            		<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Benzersiz Kullanıcı Adını Aldınızmı?
            	</h1>
            	<p class="">
            		Democratus kullanıcılarının sistem içerisinde kendi profilleri için tanımlayabilecekleri eşsiz adrese 
            		"Benzersiz Kullanıcı Adı" diyoruz. Bu adres ile profilinize özel bir link kazandırıyorsunuz. 
            		Sistem üzerindeki tüm işlemlerinizde zamanla bu kullanıcı adınız ile var olacaksınız. 
            		Kullanıcı Adınızı hemen tanımlamak için <a href="/my/profile">tıklayınız</a>.
            	</p>
            	</div>
            <?php 
            }
        }
    }
?>
