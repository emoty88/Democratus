<?php
    class headermenu_block extends control{
        
        public function block(){
            global $model;
?>
                <!-- Navigation [Begin] -->
                <div class="navigation">
                    <ul>
                        <a href="/"><li><div class="center">ANASAYFA</div></li></a>
                        <a href="/profile/<?=$model->profileID;?>"><li><div class="center">PROFİL</div></li></a>
                        <a href="/archive"><li><div class="center">MECLİS</div></li></a>
                    </ul>
                </div>       
                <!-- Navigation [End] -->
<?php
        }
        
        
    }
?>
