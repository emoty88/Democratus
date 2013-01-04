<?php
    class quickEditor_block extends control{
        public function block()
		{
			?>
			<!-- Quick Editor -->
			<section id="quick-editor" class="dm-menu" style="display: none;">
				<div class="dm-menu-content karakter_sayaci_tutucu">
					<textarea name="quick-text" placeholder="Fikrini Paylaş..." class="karakteri_sayilacak_alan"></textarea>
					<div class="textarea-controller">
						<div class="kalan_karakter_mesaji"><span class="karakter_sayaci" data-limit="200">200</span> karakter</div>
						<div class="kontroller pull-right">
							<a href="javascript:void(0)"><i class="atolye15-ikon-gorsel atolye15-ikon-24"></i></a>
							<a href="javascript:void(0)"><i class="atolye15-ikon-atac atolye15-ikon-24"></i></a>
							<button class="btn btn-danger">Paylaş</button>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="path-to-up"></div>
			</section>
			<?
		}
	}
?>

