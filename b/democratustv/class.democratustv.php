<?php
    class democratustv_block extends control{
        public function block(){
            global $model, $db, $l; 
            if($model->profileID=="4575")
            {?>
            	<div class="roundedcontent cast">
            	<h1><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" />  Democratus TV
	            	<div class="btn-group">
	            		<button class="btn btn-warning" onclick="alert('Canlı Yayın Hizmetimiz Yakın Zamanta Hizmete Girecektir.')">Canlı Yayını İzle</button>
	            	</div>
            	</h1>
            	<img src="img/yayin.jpg" class="livecast">
            	<p class="livecast">
            		<strong>Bugünkü Konuşmacı</strong>
            		<br>
            		<strong>16:00</strong> Oğuz Kaan Salıcı<br>
            		<i>CHP İstanbul İl Başkanı</i>
            	</p>
            	</div>
            <?php 
            }
        }
    }
?>