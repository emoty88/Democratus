<?php
    class search_block extends control{
        
        public function block(){
            global $model, $l;
?>
                <!-- Logo & Search -->
                <div class="search">
                    <form method="get" action="/search" onsubmit="search();">
                        <input type="text" name="q" value="<?=$l['SEARCH']?>" autocomplete="off" id="searchtext" class="" onfocus="if(this.value=='ARAMA')this.value=''" onblur="if(this.value=='')this.value='ARAMA'"  />
                        <button id="searchbutton" onclick="search();"></button>
                    </form>
                </div>
                <!-- Logo & Search [End] -->
<?php
        }
        
        
    }
?>
