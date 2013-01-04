<?php
    class sidebarTop_block extends control{
        public function block()
		{
			?>
			<div class="satir duvar_kontrolleri_tutucu">
				<div class="duvar_kontrolleri">
					<a href="javascript:;" onclick="$('#quick-editor').toggle('fast');"><i class="atolye15-ikon-yeni-yazi atolye15-ikon-48"></i></a>
					<a id="duvara_git" href="profil.html" class="">
						<span class="baslik">Duvarım</span> <i class="atolye15-ikon-ok atolye15-ikon-24"></i>
						<span class="sag_tarafi_sil"></span>
					</a>
				</div>
			</div>
			
			<?
		}
	}
?>