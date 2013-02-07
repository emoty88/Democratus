<?php
    class pagecontent_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
   
            //die;
            //if($model->userID<1)
            //    return $model->redirect('/wellcome');
			
		
            if($model->newDesign)
          	$model->initTemplate('beta', 'pagecontent');
            else 
            $model->initTemplate('v2', 'pagecontent');
            
            $di = new di;

            //$model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            //$model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            //$model->addScript($model->pluginurl . 'di.js', 'di.js', 1);
            
            //$model->addScript('profileID = ' . $model->profileID );
            
            //if(count($rows)){
?>
                        <!-- Big About [Begin] -->
                        <div class="box" id="big_about">
                            
<?php          
            
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
				
				if($model->newDesign)//yeni tasarım 
				{ ?>
				<h1><img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> <?=$row->title?></h1>
	              <p><?=$row->content?></p>
				<?php 
				}//yeni tasarım Son
				else {// eski tasarım 
			?>
			
			<span class="title"><?=$row->title?></span><div class="line_center"></div>
			<div id="pagecontent"><?=$row->content?></div>
			<?php    
				} // eski tasarım Son
			}
?>
                        </div>
                        <!-- Big About [End] -->                
<?php            
            
        }
    }
?>
