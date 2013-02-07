<?php

class language implements ArrayAccess {
      
    private $aValue;
    
    public function offsetSet ($p_key, $p_value) {
        if (is_null($p_key)) {
            $this->aValue[] = $p_value;
        }
        else {
            $this->aValue[$p_key] = $p_value;
        }
    }

    // ...
}
?>
