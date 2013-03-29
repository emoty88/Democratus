<?php
    //die('default_view dosyası');
    class mobile_view extends view{
        public $name = 'mobile';
        
        public function main(){
            global $model, $db, $l;
            if(file_exists( $this->path . $model->view . '.php')) 
                include($this->path . $model->view . '.php');
            else
                include($this->path . 'default.php');
        }
        
    }
    
?>