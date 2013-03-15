<?php
    class pagecontent_plugin extends control{
        
        public function main(){
        global $model, $db, $l;
			$c_profile = new profile();
	                   
			$model->template="ala";
			$model->view="default";
			$model->title = 'Democratus';
			
			$model->addHeaderElement();
			
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='pagecontent'");
      

            $SELECT = "SELECT pc.*";
            $FROM   = "\n FROM pagecontent AS pc";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE pc.pageID=" . $db->quote($model->page->ID) ;
            //$WHERE .= "\n AND pc.status>0";
            $WHERE .= "\n AND pc.language=" . $db->quote($model->language);
            $ORDER  = "\n ";
            $LIMIT  = "\n LIMIT 1";
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $row = null;
            if( $db->loadObject($row) ){
				?>
					
	              	<div style="margin: 15px;">
	              		<h1><?=$row->title?></h1>
	              		<?=$row->content?>
	              	</div>
				<?php 

			}
           
            
        }
    }
?>
