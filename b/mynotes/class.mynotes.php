<?php
    class mynotes_block extends control{
        
        public function block(){
            global $model, $db, $l;  
            return;
?>

      <!-- leftbox start --> 
      <div class="leftbox">
        
        <div class="leftbox-head">
          <h3>Ajandam</h3>
        </div>
        <div class="leftbox-body">
          <div class="leftbox-subhead">Günün notları</div>
          <div id="leftdiarybody">İstanbul Üniversitesindeki buluşma, 02:22’de kaydedildi.
            bla bla Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum porttitor ultriceent pharetra neque. Lorem ipsum » Devamı </div>
        </div>
        <div class="leftbox-footer">&nbsp;</div>
      </div>
      <!-- leftbox end -->


<?php
        }
    }
?>