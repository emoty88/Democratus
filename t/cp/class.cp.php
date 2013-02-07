<?php
    //die('default_view dosyası');
    class cp_view extends view{
        public $name = 'cp';
        
        
        public function main(){
            global $model;           
            $model->addStyle($this->url . 'style.css', null, 1 );
            include($this->path . 'index.php');
            
        }
        
    }
?>