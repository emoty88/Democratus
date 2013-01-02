<?php
    class miniaboutus_block extends control{
        
        public function block(){
            global $model;
            
            if(1){
                
                
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1 );
            //$model->addScript(PLUGINURL . 'lib/fancybox/jquery.easing-1.4.pack.js', 'jquery.easing.js', 1 );
            
            //$model->addScript(PLUGINURL . 'lib/boxy/boxy.js', 'boxy.js', 1 );
            //$model->addStyle(PLUGINURL . 'lib/boxy/boxy.css', 'boxy.css', 1 );
            
                         
            $model->addScript(PLUGINURL . 'lib/fancybox/jquery.fancybox-1.3.4.pack.js', 'fancybox.js', 1 );
            $model->addScript('$(function(){$("a.various").fancybox();});');
            $model->addStyle(PLUGINURL . 'lib/fancybox/jquery.fancybox-1.3.4.css', 'fancybox.css', 1 );                

?>
                        <!-- Mini About Democratus [Begin] -->
                        <div class="box" id="mini_about">
                            <span class="title">Nasıl işler?</span>
                            <div class="line"></div>
                            <div>
                               <a class="various iframe" href="http://player.vimeo.com/video/33419052?title=0&amp;byline=0&amp;portrait=0"><img src="/images/howtouse.jpg" alt="" width="240" /></a>
                               <br /><br />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- Mini About Democratus [End] -->
<?php                


                
            } else {
                
            
            
?>
                        <!-- Mini About Democratus [Begin] -->
                        <div class="box" id="mini_about">
                            <span class="title">Nasıl işler?</span>
                            <div class="line"></div>
                            <div>
    <p>
        Fikir ve paylaşımlarına ilgi duyduğunuz kişileri takip edin
    </p>
    <p>
        Takipçilerinize paylaşımlarda bulunun
    </p>
    <p>
        Gündeme oylarınızla etki edin
    </p>
    <p>
        Görüşlerine güvendiğiniz kişileri vekil seçin
    </p>
    <p>
        Vekil seçilerek gündeme gelebilecek tasarılar sunun
    </p>
</div>

                        </div>
                        <!-- Mini About Democratus [End] -->
<?php
            }
        }
    }
?>