<?php
    class welcomeagenda_block extends control{
        
        public function block(){
        	$model;
			$c_parliament = new parliament;
			$agendas = $c_parliament->get_agenda();
			$active = "active";
			foreach ($agendas as $a)
			{
				
			?>
			<div class="item <?=$active?>">
              <div class="row-fluid">
                <div class="span12" style="cursor:pointer;">
                  <blockquote>
                    <div>
                      <i class="icon-quote-left"></i>
                      <p><?=$a->title?></p>
                      <small><a href="/<?=$a->deputyPerma?>"><?=$a->deputyname?></a></small>
                    </div>
                  </blockquote>
                </div>
              </div>
            </div>
			<?
			$active = "";
			}
		}
	}
?>