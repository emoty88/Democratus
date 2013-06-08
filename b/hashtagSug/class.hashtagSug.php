<?php
    class hashtagSug_block extends control{
        
        public function block(){
        	global $model, $db; 
			$db->setQuery("SELECT * FROM hashRelated WHERE htPerma='".$model->page->permalink."' and status=1 LIMIT 7");
			$relatedH = $db->loadObjectList();
			if(count($relatedH)>0)
			{
			?>
				<!-- Bileşen -->
				<section class="bilesen beyaz padding_yok hidden-phone" id="whotofollow">
					<header>
						<h1>İlgili Başlıklar</h1>
					</header>
					<div class="bilesen_icerigi">
						<ul id="gaget_hashtagSug" class="">
							<?
							foreach ($relatedH as $r) {
								?>
								<li><a href="/<?=$r->perma?>"><?=$r->name?></a></li>
								<?
							}
							?>
						</ul>
					</div>
					
				</section>
			<?
			}
		}
    }
?>