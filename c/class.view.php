<?php
    class view{
        public $name = 'default';
        public $path, $url;
        
        public function __construct(){
            $this->path = TEMPLATEPATH . $this->name . SLASH;
            $this->url  = TEMPLATEURL . $this->name . '/';
        }
    }
?>