<?php
    class agendaadmin_plugin extends control{
        
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            
            if($model->useris('superadmin') || $model->useris('admin')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }
            
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'cp';
            $model->title = 'Agenda Admin | Democratus.com';
            
            $model->addScript('var ajaxurl = "' . $model->pageurl . 'ajax/";');
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/boxy/boxy.js', 'boxy.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/boxy/boxy.css', 'boxy.css', 1 );
            $model->addStyle('body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}' );
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.maskedinput.js', 'jquery.maskedinput.js', 1 );
            
            
            $model->addScript(PLUGINURL . 'lib/jquery-ui/jquery-ui.js', 'jquery-ui.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            
            $model->addScript(PLUGINURL . 'lib/flexigrid/flexigrid.js', 'flexigrid.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/flexigrid/flexigrid.css', 'flexigrid.css', 1 );
            
            $model->addStyle(TEMPLATEURL . 'default/form.css', 'form.css', 1 );
            $model->addScript(PLUGINURL . 'lib/tiny_mce/tiny_mce.js', 'tiny_mce.js', 1 );
            
            //$model->addScript(PLUGINURL . 'lib/ajaxupload/ajaxupload.js', 'ajaxupload.js', 1 );
            $model->addScript(PLUGINURL . 'lib/fileuploader/fileuploader.js', 'fileuploader.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/fileuploader/fileuploader.css', 'fileuploader.css', 1 );
            
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1 );
            $model->addScript($model->pluginurl . 'agendaadmin.js', 'agendaadmin.js', 1 );
  
?>
<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#grid1").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
                {display: "Title", name : "title", width : 330, sortable : true, align: "left"},
                {display: "Start - End Time", name : "time", width : 220, sortable : false, align: "center"},
                {display: "Options", name : "options", width : 70, sortable : false, align: "center"},
                {display: "Status", name : "status", width : 30, sortable : false, align: "center"},
                
                {display: "Translation", name : "translation", width : 200, sortable : false, align: "center"}
                ],
            
            buttons : [
                {name: 'Add', bclass: 'add', onpress : agendaaddclick},
                {separator: true}
                ],                
            searchitems : [
                {display: "Title", name : "title", isdefault: true}
                ],
            sortname: "ID",
            sortorder: "asc",
            usepager: true,
            title: "Agendas",
            useRp: true,
            rp: 20,
            showTableToggleBtn: false,
            onSuccess: agendagetready,
            //onSubmit: agendafilter,
            width: 'auto',
            height: 600
            }
            );
   
   
//      var ajaxurl = '<?php echo $model->pageurl; ?>ajax/';
</script>
<?php
              
        }
        
        public function ajax(){
            global $model;
            $model->mode = 0;
            $method = (string) 'ajax_' . $model->paths[2];
            if(method_exists($this, $method )){
                $this->$method();
            } else {
                
            }  
        }
        
        public function ajax_translate(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $language = strtolower( filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING));
            
            $db->setQuery("SELECT a.*, at.transtitle, at.transspot, at.transcontent FROM agenda AS a LEFT JOIN agendatrans AS at ON at.agendaID = a.ID AND at.language = ".$db->quote($language)." WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                $res = array();
                $res['orj']['ID'] = $row->ID;
                $res['orj']['title'] = (string)$row->title;
                $res['orj']['spot'] = (string)$row->spot;
                $res['orj']['content'] = (string)htmlspecialchars( stripcslashes( $row->content ),ENT_QUOTES,'UTF-8');
                
                $res['trans']['title'] = (string)$row->transtitle;
                $res['trans']['spot'] = (string)$row->transspot;
                $res['trans']['content'] = (string)htmlspecialchars( stripcslashes( $row->transcontent ),ENT_QUOTES,'UTF-8');
                
                $db->setQuery("SELECT ao.ID, ao.title, aot.transtitle  FROM agendaoption AS ao LEFT JOIN agendaoptiontrans AS aot ON aot.optionID = ao.ID AND aot.language=".$db->quote($language)." WHERE agendaID=" . $db->quote($ID). " AND status>0");
                $options = $db->loadAssocList('ID');
                
                $res['opt'] = $options;

                echo json_encode($res);
                
                return;
            }
        }
        
        public function ajax_edit(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){    
                
                $res = array();
                $res['ID'] = $row->ID;
                $res['title'] = htmlspecialchars( $row->title );
                $res['spot'] = (string)$row->spot;
                $res['content'] = (string)htmlspecialchars( stripcslashes( $row->content ),ENT_QUOTES,'UTF-8');
                $res['permalink'] = (string) $row->permalink ;
                $res['starttime'] = (string) $row->starttime;
                $res['endtime'] = (string) $row->endtime;
                $res['class'] = $this->array_to_select('class', array(1=>'World', 2=>'Region', 3=>'Country', 4=>'City', 10=>'forYou'), $row->class);
                $res['regionselect'] = (string) $model->region_to_select('region', $row->regionID);
                $res['countryselect'] = (string) $model->country_to_select('country', $row->countryID);
                $res['cityselect'] = (string) $model->city_to_select('city', $row->countryID , $row->cityID);
                
                echo json_encode($res);
                
            } elseif($ID==0){
                
                $res = array();
                $res['ID'] = '0';
                $res['title'] = '';
                $res['spot'] = '';
                $res['content'] = '';
                $res['permalink'] = '';
                $res['starttime'] = date('Y-m-d H:i:s');
                $res['endtime'] = date('Y-m-d H:i:s', time() + 60 * 60 * 1);
                $res['class'] = $this->array_to_select('class', array(1=>'World', 2=>'Region', 3=>'Country', 4=>'City', 10=>'forYou'), 1);
                $res['regionselect'] = (string) $model->region_to_select('region');
                $res['countryselect'] = (string) $model->country_to_select('country');
                $res['cityselect'] = (string) $model->city_to_select('city', 0);
                
                echo json_encode($res);
            }
        }        
        
        
        
        public function ajax_options(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            
            $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
            $agenda = null;
            if($db->loadObject($agenda)){
                
            } else return;
            
            
            $db->setQuery("SELECT ao.* FROM agendaoption AS ao WHERE ao.agendaID = " . $db->quote($ID) . " AND ao.status>=0");
            $rows = $db->loadObjectList();
            if(count($rows)){
                foreach($rows as $row){
                    echo '<p class="option">';
                    echo '<input type"text" name="option" value="'.$row->title.'" rel="'.$row->ID.'" style="width:360px" />';
                    echo '<input type="button" value="X" class="removebutton" style="float:right;" rel="'.$row->ID.'" />';
                    echo '</p>';
                }
            } else {
                //echo '<em>no image found</em>';
            }
        }
        
        public function ajax_optionsave(){
            global $model, $db;
            $model->mode = 0;
            $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
            $optionID = filter_input(INPUT_POST, 'optionID', FILTER_SANITIZE_NUMBER_INT);
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            
            $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($agendaID)." LIMIT 1");
            $agenda = null;
            if(!$db->loadObject($agenda)) return;
            
            if($optionID >0){
                $db->setQuery("SELECT ao.* FROM agendaoption AS ao WHERE ao.ID = " . $db->quote($optionID)." LIMIT 1");
                $agendaoption = null;
                if($db->loadObject($agendaoption)){
                    $agendaoption->title = $title;
                    if($db->updateObject('agendaoption', $agendaoption, 'ID' )){
                        echo $title;
                    } else echo 'error';
                } else return;
                
            } elseif($optionID==0){
                $agendaoption =(object) array('agendaID'=>$agendaID, 'title'=>$title, 'status'=>1);
                $db->insertObject('agendaoption', $agendaoption);
            }
        }
        
        public function ajax_optiontranssave(){
            global $model, $db;
            $model->mode = 0;
            $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
            $optionID = filter_input(INPUT_POST, 'optionID', FILTER_SANITIZE_NUMBER_INT);
            $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING);
            $transtitle = htmlspecialchars_decode( filter_input(INPUT_POST, 'transtitle', FILTER_SANITIZE_STRING), ENT_QUOTES);
            
            $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($agendaID)." LIMIT 1");
            $agenda = null;
            if(!$db->loadObject($agenda)) return;
                        
            $db->setQuery("SELECT ao.* FROM agendaoption AS ao WHERE ao.ID = " . $db->quote($optionID)." LIMIT 1");
            $agendaoption = null;
            if(!$db->loadObject($agendaoption)) return;
            
            
            $db->setQuery("SELECT aot.* FROM agendaoptiontrans AS aot WHERE aot.optionID = " . $db->quote($optionID)." AND aot.language = " . $db->quote($language)." LIMIT 1");
            $agendaoptiontrans = null;
            if($db->loadObject($agendaoptiontrans)){
                $agendaoptiontrans->transtitle = $transtitle;
                if($db->updateObject('agendaoptiontrans', $agendaoptiontrans, 'ID' )){
                    echo 'success';
                } else echo 'error';
            } else {
                $agendaoptiontrans = new stdClass;
                $agendaoptiontrans->transtitle = $transtitle;
                $agendaoptiontrans->optionID = $optionID;
                $agendaoptiontrans->language = $language;
                
                if($db->insertObject('agendaoptiontrans', $agendaoptiontrans)){
                    echo 'success';
                } else echo 'error';
            }
        }
        
        
        public function ajax_optiondelete(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
            
            
            $db->setQuery("SELECT ao.ID, ao.status FROM agendaoption AS ao WHERE ao.ID = " . $db->quote($ID)." LIMIT 1");
            $agendaoption = null;
            if($db->loadObject($agendaoption)){
                $agendaoption->status = -1;
                if($db->updateObject('agendaoption', $agendaoption, 'ID' )){
                    echo 'success';
                } else echo 'error';
            } else echo 'not found';
        }
        
        public function ajax_images(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            
            $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
            $agenda = null;
            if($db->loadObject($agenda)){
                
            } else return;
            
            
            $db->setQuery("SELECT ai.* FROM agendaimage AS ai WHERE ai.agendaID = " . $db->quote($ID) . " AND ai.status>0");
            $rows = $db->loadObjectList();
            if(count($rows)){
                foreach($rows as $row){
                    echo '<div class="image">';
                    echo '<input type="button" value="X" class="removebutton" style="float:right;" rel="'.$row->ID.'" />';
                    echo '<img src="'.$model->getImage($row->imagepath, 100, 100, 'scale').'" width="100" height="100" />';
                    if($agenda->imagepath == $row->imagepath)
                        echo '<input type="radio" name="selected" value="'.$row->ID.'" checked="checked" />';
                    else
                        echo '<input type="radio" name="selected" value="'.$row->ID.'" />';
                    echo '</div>';
                }
            } else {
                echo '<em>no image found</em>';
            }
        }
        
                
        public function ajax_imagesave(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $imageID = filter_input(INPUT_POST, 'imageID', FILTER_SANITIZE_NUMBER_INT);
            
            $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
            $agenda = null;
            if(!$db->loadObject($agenda)) return;
            
            $db->setQuery("SELECT ai.* FROM agendaimage AS ai WHERE ai.ID = " . $db->quote($imageID)." LIMIT 1");
            $agendaimage = null;
            if($db->loadObject($agendaimage)){
                $agenda->imagepath = $agendaimage->imagepath;
                if($db->updateObject('agenda', $agenda, 'ID' )){
                //echo $agendaimage->imagepath;
                } else echo 'error';
            } else return;
        }
        
        public function ajax_imagedelete(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            
            $db->setQuery("SELECT ai.* FROM agendaimage AS ai WHERE ai.ID = " . $db->quote($ID)." LIMIT 1");
            $agendaimage = null;
            if($db->loadObject($agendaimage)){
                $agendaimage->status = -1;
                if($db->updateObject('agendaimage', $agendaimage, 'ID' )){
                    echo 'success';
                } else echo 'error';
            } else echo 'not found';
        }
        
        
        public function ajax_imageupload(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $agendaID = filter_input(INPUT_GET, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
            
            include_once(PLUGINPATH . 'lib/fileuploader/fileuploader.php');
            
            $uploader = new fileuploader(array('jpg', 'gif', 'png'), 10 * 1024 * 1024);
            //die( $uploader->fullpath );
            if($uploader->save()){
                echo htmlspecialchars(json_encode($uploader->result), ENT_NOQUOTES); // to pass data through iframe you will need to encode all html tags
                $row = array();
                $row['agendaID'] = $agendaID;
                $row['imagepath'] = $uploader->subpath . $uploader->filename . '.' . $uploader->fileext;
                $row['status'] = 1;
                $row = (object) $row;
                
                $db->insertObject('agendaimage', $row);                
            } else {
                echo htmlspecialchars(json_encode($uploader->result), ENT_NOQUOTES);
            }
        }
        
        public function ajax_save(){
            global $model, $db;
            $model->mode = 0;
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
                $row = null;
                if($db->loadObject($row)){
                    $row->title = htmlspecialchars_decode( filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING), ENT_QUOTES);
                    $row->spot = htmlspecialchars_decode( filter_input(INPUT_POST, 'spot', FILTER_SANITIZE_STRING), ENT_QUOTES);
                    $row->content = htmlspecialchars_decode( filter_input(INPUT_POST, 'content'), ENT_QUOTES);
                    $row->permalink = clean_url( htmlspecialchars_decode( filter_input(INPUT_POST, 'permalink', FILTER_SANITIZE_STRING) ,ENT_QUOTES));
                    $row->class = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_NUMBER_INT);
                    $row->regionID = filter_input(INPUT_POST, 'region', FILTER_SANITIZE_NUMBER_INT);
                    $row->countryID = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_NUMBER_INT);
                    $row->cityID = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_NUMBER_INT);
                    $row->starttime = asdatetime( filter_input(INPUT_POST, 'starttime', FILTER_SANITIZE_STRING), 'Y-m-d H:i:s' );
                    $row->endtime = asdatetime( filter_input(INPUT_POST, 'endtime', FILTER_SANITIZE_STRING), 'Y-m-d H:i:s' );
                    
                    if($db->updateObject('agenda', $row, 'ID')){
                        
                        echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>Update success</p>').'").dialog({
                            modal: true,
                            title: "Success",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 1000 );
                        ';
                        
                    } else throw new Exception('kayıt hatası oluştu');
                } elseif($ID==0){
                    $row = array();
                    $row['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                    $row['spot'] = filter_input(INPUT_POST, 'spot', FILTER_SANITIZE_STRING);
                    $row['content'] = filter_input(INPUT_POST, 'content');
                    $row['permalink'] = filter_input(INPUT_POST, 'permalink', FILTER_SANITIZE_STRING);
                    $row['locationID'] = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_NUMBER_INT);
                    $row['starttime'] = asdatetime(filter_input(INPUT_POST, 'starttime', FILTER_SANITIZE_STRING), 'Y-m-d H:i:s');
                    $row['endtime'] = asdatetime( filter_input(INPUT_POST, 'endtime', FILTER_SANITIZE_STRING), 'Y-m-d H:i:s');
                    $row['status'] = 1;
                    
                    $row = (object) $row;
                    if($db->insertObject('agenda', $row, 'ID')){
                        echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p>New record success</p>').'").dialog({
                            modal: true,
                            title: "Success",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 3000 );
                        ';                    
                    } else throw new Exception('ekleme hatası oluştu');
                    
                } else throw new Exception('bulunamadı');
                
            } catch (Exception $e){
                echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p>'.$e->getMessage().'</p>').'").dialog({
                            modal: true,
                            title: "Success",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 3000 );
                        ';                
            }
        }
        
        public function ajax_transsave(){
            global $model, $db;
            $model->mode = 0;
            try{
                $ID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
                $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING);
                
                $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
                $agenda = null;
                if(!$db->loadObject($agenda)) throw new Exception('not found'); 
                               
                $db->setQuery("SELECT at.* FROM agendatrans AS at WHERE at.agendaID = " . $db->quote($ID)." AND at.language = " . $db->quote($language)." LIMIT 1");
                
                $agendatrans = null;
                if($db->loadObject($agendatrans)){
                    $agendatrans->transtitle = htmlspecialchars_decode( filter_input(INPUT_POST, 'transtitle', FILTER_SANITIZE_STRING), ENT_QUOTES);
                    $agendatrans->transspot = htmlspecialchars_decode( filter_input(INPUT_POST, 'transspot', FILTER_SANITIZE_STRING), ENT_QUOTES);
                    $agendatrans->transcontent = htmlspecialchars_decode( filter_input(INPUT_POST, 'transcontent'), ENT_QUOTES);
                    
                    $db->updateObject('agendatrans', $agendatrans, 'ID');
                } else {
                    $agendatrans = new stdClass;
                    $agendatrans->agendaID = $ID;
                    $agendatrans->language = $language;
                    $agendatrans->transtitle = htmlspecialchars_decode( filter_input(INPUT_POST, 'transtitle', FILTER_SANITIZE_STRING), ENT_QUOTES);
                    $agendatrans->transspot = htmlspecialchars_decode( filter_input(INPUT_POST, 'transspot', FILTER_SANITIZE_STRING), ENT_QUOTES);
                    $agendatrans->transcontent = htmlspecialchars_decode( filter_input(INPUT_POST, 'transcontent'), ENT_QUOTES);
                    
                    $db->insertObject('agendatrans', $agendatrans);
                    
                }

                echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p>Translation success</p>').'").dialog({
                    modal: true,
                    title: "Success",
                    buttons: {Ok: function() {$( this ).dialog( "close" );}}
                });
                setTimeout(function(){ message.dialog( "close" ); }, 3000 );
                ';                    
                
            } catch (Exception $e){
                echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p>'.$e->getMessage().'</p>').'").dialog({
                            modal: true,
                            title: "Success",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 3000 );
                        ';                
            }
        }        
        
        public function ajax_delete(){
            global $model, $db;
            $model->mode = 0;
            try {
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $db->setQuery("DELETE FROM agenda WHERE ID = " . $db->quote($ID)." LIMIT 1");

                if($db->uquery()){
                    echo 'var dialog = new Boxy("<div class=\"info\"><p>silindi</p></div>", {modal: false,closeable: false});';
                    echo 'setTimeout(function(){ dialog.hide(); }, 1000 );';                    
                } else throw new Exception('bulunamadı');
                
            } catch (Exception $e){
                echo 'var dialog = new Boxy("<div class=\"info\"><p>'.$e->getMessage().'</p></div>", {modal: false,closeable: false});';
                echo 'setTimeout(function(){ dialog.hide(); }, 1000 );'; 
            }
        }
                
        public function ajax_toggle(){
            global $model, $db;
            $model->mode = 0;

            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT a.ID, a.status FROM agenda AS a WHERE a.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->status == 0 ) $row->status = -1;
                elseif($row->status < 0 ) $row->status = 1;
                elseif($row->status > 0 ) $row->status = 0;
                $db->updateObject('agenda', $row, 'ID' );
            } else {
                //not found
            }
        }
                    
        public function ajax_rows(){
            global $model, $db;
            $model->mode = 0;
            
            $db->setQuery('SELECT * FROM agenda');
            $rows = $db->loadObjectList();
            $row = 0; 
            foreach($rows as $row){
                $row->title = html_entity_decode( htmlspecialchars_decode($row->title,ENT_QUOTES), ENT_QUOTES, 'UTF-8');
                $row->spot = html_entity_decode( htmlspecialchars_decode($row->spot,ENT_QUOTES), ENT_QUOTES, 'UTF-8');
                $row->content = html_entity_decode( htmlspecialchars_decode($row->content,ENT_QUOTES ), ENT_QUOTES, 'UTF-8');
                $db->updateObject('agenda', $row, 'ID');
            }
            
            $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT);
            $rp = filter_input(INPUT_POST, 'rp', FILTER_SANITIZE_NUMBER_INT);
            
            $sortname = filter_input(INPUT_POST, 'sortname', FILTER_SANITIZE_STRING); 
            $sortorder = filter_input(INPUT_POST, 'sortorder', FILTER_SANITIZE_STRING);
            
            $query = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING);
            $qtype = filter_input(INPUT_POST, 'qtype', FILTER_SANITIZE_STRING);
            
            if (!$sortname) $sortname = 'ID';
            if (!$sortorder) $sortorder = 'desc';
            
            if (!$page) $page = 1;
            if (!$rp) $rp = 10;

            $start = (($page-1) * $rp);
            
            $SELECT = "SELECT a.*, (SELECT count(ao.ID) FROM agendaoption AS ao WHERE ao.agendaID = a.ID AND ao.status>0) AS options";
            $FROM   = "\n FROM agenda AS a";
            $JOIN   = "\n ";
            if($query){
                if(in_array($qtype,array( 'title', 'permalink', 'parent')))
                    $WHERE = "\n WHERE $qtype LIKE '%".$db->escape($query)."%'";
                else 
                    $WHERE = "\n WHERE `title` LIKE '%".$db->escape($query)."%'";
            } else {
                $WHERE = "\n ";
            }
            
            if(in_array($sortname, array( 'ID','title', 'parent', 'permalink')) && in_array($sortorder, array( 'asc', 'desc')))
                $ORDER  = "\n ORDER BY $sortname $sortorder";
            else
                $ORDER = "\n ORDER BY ID";
                
            $LIMIT  = "\n LIMIT $start, $rp";
            
            $db->setQuery('SELECT COUNT(*)'.$FROM.$JOIN.$WHERE);
            $total = $db->loadResult();
            
            $data['page'] = $page;
            $data['total'] = $total;
            
            $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$ORDER.$LIMIT); 
            $rows = $db->loadObjectList();
            
            if(count($rows)){
                foreach($rows as $row){
                    //$buttons = '<a href="#" rel="'.$row->ID.'" class="rowedit" title="edit"><img src="'.PLUGINURL.'lib/icons/comment_edit.png" alt="edit" border="0" /></a> ';
                    //$buttons.= '<a href="#" rel="'.$row->ID.'" class="rowdelete" title="delete"><img src="'.PLUGINURL.'lib/icons/comment_delete.png" alt="delete" border="0" /></a> ';
                    $buttons = '<a href="#" rel="'.$row->ID.'" class="rowimageedit" title="imageedit"><img src="'.PLUGINURL.'lib/icons/image_edit.png" alt="imageedit" border="0" /></a> ';
                    
                    if($row->status>0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="active"><img src="'.PLUGINURL.'lib/icons/accept.png" alt="active" border="0" /></a> ';
                    elseif($row->status==0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="draft"><img src="'.PLUGINURL.'lib/icons/time.png" alt="draft" border="0" /></a> ';
                    else
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="deleted"><img src="'.PLUGINURL.'lib/icons/stop.png" alt="deleted" border="0" /></a> ';
                    
                    $trans = '<span rel="'.$row->ID.'" language="tr" class="rowtrans">Türkçe</span> | ';
                    $trans.= '<span rel="'.$row->ID.'" language="ar" class="rowtrans">Arabic</span> | ';
                    $trans.= '<span rel="'.$row->ID.'" language="de" class="rowtrans">Deutch</span> | ';
                    $trans.= '<span rel="'.$row->ID.'" language="ru" class="rowtrans">Russian</span>';
                    
                    $opt = '';
                    
                    
                    $db->setQuery('SELECT av.vote, COUNT(*) AS votecount FROM agendavote AS av WHERE av.agendaID='.$db->quote($row->ID).' GROUP BY av.vote ORDER BY av.vote');
                    $voted = $db->loadObjectList('vote');
                    $totalvote = 0;
                    if(count($voted)) foreach($voted as $v) $totalvote += $v->votecount;
                    

                    $optioans = array(1=>'Kesinlikle Katılıyorum',
                                     2=>'Katılıyorum',
                                     3=>'Kararsızım',
                                     4=>'Katılmıyorum',
                                     5=>'Kesinlikle Katılmıyorum');
                    
                    foreach(config::$votetypes as $key=>$option){
                        
                        //oy oranini hesapla
                        if(array_key_exists($key, $voted)){
                            $votecount = intval($voted[$key]->votecount) ;
                            $percent = floor( ($voted[$key]->votecount * 100) / $totalvote );
                        } else {
                            $votecount = 0;
                            $percent = 0;
                        }
                        
                        $opt .= '<p>' . $option . ' - ' . $votecount . ' - ' . $percent . '%</p>';
                            
                    }
                    
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        '<h3>' . $row->title . '</h3> <a href="#" rel="'.$row->ID.'" class="rowedit" title="edit"><img src="'.PLUGINURL.'lib/icons/comment_edit.png" alt="edit" border="0" /></a> ' . $buttons . $opt, 
                                        $row->starttime . ' - ' . $row->endtime,
                                        $row->options . ' <a href="#" rel="'.$row->ID.'" class="rowoptionedit" title="edit options"><img src="'.PLUGINURL.'lib/icons/comment_edit.png" alt="edit" border="0" /></a> ',
                                        $status,
                                        
                                        $trans
                                        )
                    );
                }
            } else {
                $datarows = array();
            }
            
            $data['rows'] = $datarows;
            
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
            header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
            header("Cache-Control: no-cache, must-revalidate" ); 
            header("Pragma: no-cache" );
            header("Content-type: text/x-json");
            
            echo json_encode($data);         
        }
        
        public function ajax_getcities(){
            global $model, $db;            
            $countryID = intval( filter_input(INPUT_POST, 'countryID', FILTER_SANITIZE_NUMBER_INT) );
            $db->setQuery('SELECT ct.* FROM city AS ct WHERE ct.countryID='.$db->quote($countryID).' ORDER BY ct.city;');
            $items = $db->loadAssocList();
            echo json_encode($items);
        }        
        
        public function array_to_select($name, $array, $selected=null){
            $html = '<select name="'.$name.'" id="'.$name.'">';
            foreach($array as $key=>$value){
                $sel = (!is_null($selected) && $selected == $key)?' selected="selected"':'';
                $html.= '<option value="'.$key.'"'.$sel.'>'.$value.'</option>';
            }
            $html.='</select>';
            return $html;
        }
        
    }
?>
