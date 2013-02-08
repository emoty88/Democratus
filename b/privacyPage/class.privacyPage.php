<?php
    class privacyPage_block extends control{
        
        public function block(){
        	global $model, $db, $l; 
        	
            $SELECT = "SELECT pc.*";
            $FROM   = "\n FROM pagecontent AS pc";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE pc.pageID=101" ;
            //$WHERE .= "\n AND pc.status>0";
            $WHERE .= "\n AND pc.language=" . $db->quote($model->language);
            $ORDER  = "\n ";
            $LIMIT  = "\n LIMIT 1";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $row = null;
            if( $db->loadObject($row) ){

				?>
				<h1><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> <?=$row->title?></h1>
	              <p><?=$row->content?></p>
				<?php 

            }
        }
    }
?>