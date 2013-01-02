<?php
    class entervoice_plugin extends control{
        
        public function main(){
            global $model;
            
            if($model->userID<1)
                return $model->redirect('/welcome');

            $model->view = 'entervoice';
            $model->initTemplate('v2', 'entervoice');
            
            //$model->title = 'Vekil seçimleri';
            //$model->description = 'Takip ettiklerin arasından vekil oyu ver, gündemi belirlesinler!';
            
            //$model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            //$model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            
            //$model->addScript($model->pluginurl . 'election.js', 'election.js', 1);
            //$model->addScript('$(window).load(function() {
             //   mCustomScrollbars();
            //});
            
         ?>
         <div id="main_wrapper">
         <div class="main" style="width: 450px;">
         <div class="center">
            <div id="share_idea" class="box">
                <span class="title_icon" style="width: 290px; float: left">Fikrini Paylaş</span>
                <span class="character"><span class="number">200</span> Karakter</span>
                
                <div class="clear"></div>
                
                <form method="post" onsubmit="return false;" id="shareDi">
                <input type="hidden" name="sesHakkındaID" value="<?=$model->paths[1]?>" />
                <input type="hidden" name="linkli" value="linkli" />
                    <div class="textarea">
	                    <textarea maxlength="200" id="shareditext" name="shareditext">#buSes sad </textarea>
                    </div>
                    <button type="submit" id="sharedi" onClick="test();">Paylaş</button>
                    <div class="clear"></div>
                </form>
             </div>
            </div>
            </div>
           </div>
            <?php 
        }
    }
?>