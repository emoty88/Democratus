<?php
    class democratustv_block extends control{
        public function block(){
            global $model, $db, $l; 
			if($model->paths[0]=="democratus")
			{
            ?>
            <section class="bilesen beyaz padding_yok hidden-phone" id="whotofollow">
				<header>
					<h1><?=$model->paths[0]?> Tv</h1>
				</header>
				<div class="bilesen_icerigi">
                    <iframe width="262" height="170" src="http://www.youtube.com/embed/hiocNmbFllQ" frameborder="0" allowfullscreen></iframe>
				</div>
				
			</section>
            <?php 
			}
			else
			{
				return false;
			}
    	}
    }
?>