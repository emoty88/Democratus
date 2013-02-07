<?php
    //die('default_view dosyası');
    class ala_view extends view{
        public $name = 'ala';
        
        public function main(){
            global $model, $db, $l;
            if(file_exists( $this->path . $model->view . '.php')) 
                include($this->path . $model->view . '.php');
            else
                include($this->path . 'default.php');
        }
        
    }
    
?>