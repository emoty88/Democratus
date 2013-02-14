<?php
    class smsuggestion_block extends control{
        
        public function block(){
        	global $model;
			if($model->checkLogin())
			{
				 
			?>
				<!-- Bileşen -->
				<section class="bilesen beyaz padding_yok" id="whotofollow">
					<header>
						<h1>Arkadaşlarını Davet Et</h1>
					</header>
					<div class="bilesen_icerigi">
                        <address style="padding: 10px;">Arkadaşlarını da fikirleriyle gündemi şekillendirmeye <a href="/my?share#arkadasB" >davet et.</a></address>
					</div>
					
				</section>
			<?
			}
		}
    }
?>