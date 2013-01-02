<?php
    class locationadmin_plugin extends control{
        
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            $model->template = 'cp';
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'cp';
            $model->title = 'Location Admin | Democratus.com';
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/boxy/boxy.js', 'boxy.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/boxy/boxy.css', 'boxy.css', 1 );
            $model->addStyle('body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}' );
            
            $model->addScript(PLUGINURL . 'lib/jquery-ui/jquery-ui.js', 'jquery-ui.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            
            $model->addScript(PLUGINURL . 'lib/flexigrid/flexigrid.js', 'flexigrid.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/flexigrid/flexigrid.css', 'flexigrid.css', 1 );
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1 );
            $model->addScript($model->pluginurl . 'locationadmin.js', 'locationadmin.js', 1 );
            
            /*
            <form action="">
<input type="checkbox" value="10" id="fiterstatus" name="fiterstatus" onchange="$('#grid1').flexReload();" /> 
<input type="button" onclick="$('#grid1').flexReload();" />
</form>
            
            */
?>
<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#grid1").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "left"},
                {display: "Title", name : "title", width : 180, sortable : true, align: "left"},
                {display: "Parent", name : "parent", width : 180, sortable : true, align: "left"},
                {display: "ParentID", name : "parentID", width : 130, sortable : true, align: "left"},
                {display: "Status", name : "status", width : 30, sortable : false, align: "center"},
                {display: "Buttons", name : "buttons", width : 150, sortable : false, align: "center"}
                ],
            
            buttons : [
                {name: 'Add', bclass: 'add', onpress : locationaddclick},
                {separator: true}
                ],                
            searchitems : [
                {display: "Title", name : "title", isdefault: true},
                ],
            sortname: "ID",
            sortorder: "asc",
            usepager: true,
            title: "Locations",
            useRp: true,
            rp: 20,
            showTableToggleBtn: true,
            onSuccess: locationgetready,
            //onSubmit: locationfilter,
            width: '100%',
            height: 500
            }
            );
   
   
      var ajaxurl = '<?php echo $model->pageurl; ?>ajax/'
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
        
        
        public function ajax_edit(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            //$ID = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT);
            //die($ID);
            $db->setQuery("SELECT l.* FROM location AS l WHERE ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                $form = '<form name="editdialog" id="editdialog" onsubmit="return false;">';
                $form .= '<input type="text" name="title" id="title" value="' . $row->title . '" />';
                $form .= $this->location_to_select('parentID', $row->parentID, $row->ID);
                $form .= '<input type="submit" value="Save" onclick="locationadmin_edit_save();" class="submit" />';
                $form .= '<input type="button" value="Cancel" onclick="Boxy.get(this).hide();Boxy.get(this).unload();" />';
                $form .= '<input type="hidden" name="ID" id="ID" value="' . $row->ID . '" />';
                $form .= '<input type="hidden" name="pID" id="ID" value="' . $row->parentID . '" />';
                $form .= '</form>';
                
                echo 'dialog = new Boxy("<div class=\"info\">'. addslashes($form).'</div>", {modal: true,closeable: true});';
            } elseif($ID==0){
                
                $form = '<form name="editdialog" id="editdialog" onsubmit="return false;">';
                $form .= '<input type="text" name="title" id="title" value="" />';
                $form .= $this->location_to_select('parentID');
                
                $form .= '<input type="submit" value="Save" onclick="locationadmin_edit_save();" class="submit" />';
                $form .= '<input type="button" value="Cancel" onclick="Boxy.get(this).hide();Boxy.get(this).unload();" />';
                $form .= '<input type="hidden" name="ID" id="ID" value="0" />';
                $form .= '</form>';
                
                echo 'dialog = new Boxy("<div class=\"info\">'.addslashes($form).'</div>", {modal: true,closeable: true});';
            }
        }        
        public function ajax_save(){
            global $model, $db;
            $model->mode = 0;
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                $parentID = filter_input(INPUT_POST, 'parentID', FILTER_SANITIZE_NUMBER_INT);
                
                $db->setQuery("SELECT l.* FROM location AS l WHERE ID = " . $db->quote($ID)." LIMIT 1");
                $row = null;
                if($db->loadObject($row)){
                    $row->title = $title;
                    $row->parentID = $parentID;
                    if($db->updateObject('location', $row, 'ID')){
                        echo 'var dialog = new Boxy("<div class=\"info\"><p>Güncellendi</p></div>", {modal: false,closeable: false});';
                        echo 'setTimeout(function(){ dialog.hide(); }, 1000 );';                    
                    } else throw new Exception('kayıt hatası oluştu');
                } elseif($ID==0){
                    $row = array();
                    $row['title'] = $title;
                    $row['parentID'] = $parentID;
                    $row = (object) $row;
                    if($db->insertObject('location', $row, 'ID')){
                        echo 'var dialog = new Boxy("<div class=\"info\"><p>Yeni Kayıt Eklendi</p></div>", {modal: false,closeable: false});';
                        echo 'setTimeout(function(){ dialog.hide(); }, 1000 );';                    
                    } else throw new Exception('ekleme hatası oluştu');
                    
                } else throw new Exception('bulunamadı');
                
            } catch (Exception $e){
                echo 'var dialog = new Boxy("<div class=\"info\"><p>'.$e->getMessage().'</p></div>", {modal: false,closeable: false});';
                echo 'setTimeout(function(){ dialog.hide(); }, 1000 );'; 
            }
        }        
        
        public function ajax_delete(){
            global $model, $db;
            $model->mode = 0;
            try {
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $db->setQuery("DELETE FROM location WHERE ID = " . $db->quote($ID)." LIMIT 1");

                if($db->uquery()){
                    echo 'var dialog = new Boxy("<div class=\"info\"><p>silindi</p></div>", {modal: false,closeable: false});';
                    echo 'setTimeout(function(){ dialog.hide(); }, 1000 );';                    
                } else throw new Exception('bulunamadı');
                
            } catch (Exception $e){
                echo 'var dialog = new Boxy("<div class=\"info\"><p>'.$e->getMessage().'</p></div>", {modal: false,closeable: false});';
                echo 'setTimeout(function(){ dialog.hide(); }, 1000 );'; 
            }
        }
                    
        public function ajax_rows(){
            global $model, $db;
            $model->mode = 0;
            
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
            
            $SELECT = "SELECT l.*, lp.title AS parent";
            $FROM   = "\n FROM location AS l";
            $JOIN   = "\n LEFT JOIN location AS lp ON lp.ID = l.parentID";
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
                    $buttons = '<a href="#" rel="'.$row->ID.'" class="rowedit"><img src="'.PLUGINURL.'lib/icons/map_edit.png" alt="edit" border="0" /></a> ';
                    $buttons.= '<a href="#" rel="'.$row->ID.'" class="rowdelete"><img src="'.PLUGINURL.'lib/icons/map_delete.png" alt="delete" border="0" /></a> ';
                    $status = $row->ID;
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        $row->title, 
                                        $row->parent,
                                        $row->parentID,
                                        $status,
                                        $buttons
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
        
        
        public function location_to_select($name, $selected=null, $disabled = null){
            global $model, $db;
            $model->mode = 0;
            
            $db->setQuery('SELECT ID, parentID, title, permalink FROM location ORDER BY parentID, title;');
            $items = $db->loadObjectList();
            
            
            
            $html = '<select name="'.$name.'" id="'.$name.'">';
            $sel = 0 == $selected?' selected="selected"':'';
            $html.= '<option value="0"'.$sel.'>-</option>';
            foreach($items as $item){
                if($item->ID==$disabled) continue;
                $sel = $item->ID == $selected?' selected="selected"':'';
                $html.= '<option value="'.$item->ID.'"'.$sel.'>'.$item->title.'</option>';
            }
            
            $html.='</select>';
            //echo $html;
            
            return $html;
            
            $html = '';
            $parent = 0;
            $parent_stack = array();

            // $items contains the results of the SQL query
            $children = array();
            foreach ( $items as $item )
                $children[$item['parentID']][] = $item;

            foreach ( $children as $child )
            {
                if ( !empty( $child ) )
                {
                    // 1) The item contains children:
                    // store current parent in the stack, and update current parent
                    if ( !empty( $children[$option['value']['ID']] ) )
                    {
                        $html .= '<li>' . $option['value']['title'] . '</li>';
                        $html .= '<ul>'; 
                        array_push( $parent_stack, $parent );
                        $parent = $option['value']['ID'];
                    }
                    // 2) The item does not contain children
                    else
                        $html .= '<li>' . $option['value']['title'] . '</li>';
                }
                // 3) Current parent has no more children:
                // jump back to the previous menu level
                else
                {
                    $html .= '</ul>';
                    $parent = array_pop( $parent_stack );
                }
            }

            // At this point, the HTML is already built
            echo $html;
                        
            
            
            
            
            //print_r($items);
        }
    }
?>