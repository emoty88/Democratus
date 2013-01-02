<?php
    class clueadmin_plugin extends control{
        
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            
            if($model->useris('superadmin')||$model->useris('editor')||$model->useris('viceeditor')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'cp';
            $model->title = 'Clue Admin | Democratus.com';
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/boxy/boxy.js', 'boxy.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/boxy/boxy.css', 'boxy.css', 1 );
            $model->addStyle('body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}' );
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.maskedinput.js', 'jquery.maskedinput.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/jquery-ui/jquery-ui.js', 'jquery-ui.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            
            $model->addScript(PLUGINURL . 'lib/flexigrid/flexigrid.js', 'flexigrid.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/flexigrid/flexigrid.css', 'flexigrid.css', 1 );
            
            //$model->addStyle(TEMPLATEURL . 'default/dialogform.css', 'dialogform.css', 1 );
            //$model->addScript(PLUGINURL . 'lib/tiny_mce/tiny_mce.js', 'tiny_mce.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1 );
            $model->addScript($model->pluginurl . 'clueadmin.js', 'clueadmin.js', 1 );            
?>
<table id="cluegrid" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#cluegrid").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
                {display: "clue", name : "clue", width : 800, sortable : true, align: "left"},
                {display: "status", name : "status", width : 30, sortable : false, align: "center"},                
                {display: "action", name : "action", width : 30, sortable : false, align: "center"}
                ],
            buttons : [
                {name: 'Add Clue', bclass: 'add', onpress : add},
                {separator: true}
                ],
                                
            searchitems : [
                {display: "clue", name : "clue", isdefault: true}
                ],
            sortname: "ID",
            sortorder: "DESC",
            usepager: true,
            title: "Clues",
            useRp: true,
            rp: 15,
            showTableToggleBtn: false,
            onSuccess: cluegridready,
            //onSubmit: agendafilter,
            width: 'auto',
            height: 410
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
        
       /* 
        public function ajax_edit(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT u.* FROM user AS u WHERE u.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                $res = array();
                foreach($row as $key=>$val)
                    $res['user'][$key] = $val;
                    
                $roles = '';
                foreach($model->roles as $key=>$val){
                    $checked = ($val & $row->role)? 'checked="checked"':'';
                    $roles .= '<label>&nbsp;</label><input type="checkbox" name="role" value="'.$val.'" '.$checked.' />' . $key . '<br />';
                }
                $res['roles'] = $roles;
                echo json_encode($res);
            }
            return;
                
        }        
        */
                
        public function ajax_toggle(){
            global $model, $db;
            $model->mode = 0;

            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT c.ID, c.status FROM clue AS c WHERE c.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->status > 0 ) $row->status = 0;
                else $row->status = 1;
                $db->updateObject('clue', $row, 'ID' );
            } else {
                //not found
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
            
            $SELECT = "SELECT c.*";
            $FROM   = "\n FROM clue AS c";
            //$JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID=dcc.profileID";
            //$JOIN  .= "\n LEFT JOIN di AS di ON di.ID=dcc.diID";
            //$JOIN  .= "\n LEFT JOIN dicomment AS dic ON dic.ID=dcc.dicID";
            $JOIN   = "\n ";
            if($query){
                if(in_array($qtype,array( 'clue' )))
                    $WHERE = "\n WHERE $qtype LIKE '%".$db->escape($query)."%'";
                else 
                    $WHERE = "\n WHERE `clue` LIKE '%".$db->escape($query)."%'";
            } else {
                $WHERE = "\n ";
            }
            
            //$WHERE = "\n AND pr.ID = prc.profileID";
            
            if(in_array($sortname, array( 'ID','clue', 'datetime')) && in_array($sortorder, array( 'asc', 'desc')))
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
                    $buttons = '';
                    $buttons = ' <a href="#" rel="'.$row->ID.'" class="rowedit" title="edit"><img src="'.PLUGINURL.'lib/icons/comment_edit.png" alt="edit" border="0" /></a>';
     
                    if($row->status>0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="active"><img src="'.PLUGINURL.'lib/icons/accept.png" alt="active" border="0" /></a> ';
                    else
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="block"><img src="'.PLUGINURL.'lib/icons/stop.png" alt="deleted" border="0" /></a> ';
                    
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        $row->clue, 
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
        
        
        public function ajax_add(){
            global $model, $db;
            $response = array();
            try{
                /*
                $ID   = intval(filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT));
                if($ID<1) throw new Exception('ID error');
                
                $db->setQuery("SELECT * FROM clue WHERE ID = ".$db->quote($ID));
                $row = null;
                if(!$db->loadObject($row)) throw new Exception('not found');
                */
                $html = '<form action="" class="form" method="post" id="addform">';

                $html.= '<p>';
                $html.= '<label>Clue</label>';
                $html.= '<textarea name="clue"></textarea>';
                $html.= '</p>';                
                $html.= '<p><input type="button" class="indent" name="save" value="Save" id="save" /></p>';
                $html.= '<input type="hidden" name="ID" value="0" />';
                $html.= '</form>';    

                $response['result'] = 'success';
                $response['html'] = $html;
                
                
            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            
            echo json_encode($response);
            
        }
        
        public function ajax_edit(){
            global $model, $db;
            $response = array();
            try{
                
                $ID   = intval(filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT));
                if($ID<1) throw new Exception('ID error');
                
                $db->setQuery("SELECT * FROM clue WHERE ID = ".$db->quote($ID));
                $row = null;
                if(!$db->loadObject($row)) throw new Exception('not found');

                $html = '<form action="" class="form" method="post" id="editform">';

                $html.= '<p>';
                $html.= '<label>Clue</label>';
                $html.= '<textarea name="clue">'.$row->clue.'</textarea>';
                $html.= '</p>';                
                $html.= '<p><input type="button" class="indent" name="save" value="Save" id="save" /></p>';
                $html.= '<input type="hidden" name="ID" value="'.$row->ID.'" />';
                $html.= '</form>';    

                $response['result'] = 'success';
                $response['html'] = $html;
                
                
            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            
            echo json_encode($response);
            
        }
        
        public function ajax_save(){
            global $model, $db;
            $response = array();
            try{
                $form = new stdClass;
                $form->ID       = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $form->clue   = filter_input(INPUT_POST, 'clue', FILTER_SANITIZE_STRING);

                 
                if(intval($form->ID)>0){
                    $db->setQuery("SELECT * FROM clue WHERE ID = " . $db->quote($form->ID));
                    $row = null;
                    if($db->loadObject($row)){
                        $row->clue     = $form->clue;
                          
                        $db->updateObject('clue', $row, 'ID', true);
                        
                    } else {
                        throw new Exception('not found');
                    }
                    
                } else {
                    
                    $row = new stdClass;
                    $row->clue     = $form->clue;
                    
                    if( $db->insertObject('clue', $row ) ){
                        $response['newid'] = $db->insertid();
                    }
                    
                }
                
                
                $response['result'] = 'success';
                //$response['html'] = $html;
                $response['message'] = 'success';
                
                
            } catch (Exception $e){
                $response['result'] = 'error';
                $response['message'] = $e->getMessage();
            }
            
            
            echo json_encode($response);
            
        }
        
    }
?>