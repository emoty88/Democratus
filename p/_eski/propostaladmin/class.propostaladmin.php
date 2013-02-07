<?php
    class propostaladmin_plugin extends control{
        
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'cp';
            $model->title = 'Di Complaint Admin | Democratus.com';
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/boxy/boxy.js', 'boxy.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/boxy/boxy.css', 'boxy.css', 1 );
            $model->addStyle('body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}' );
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.maskedinput.js', 'jquery.maskedinput.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/jquery-ui/jquery-ui.js', 'jquery-ui.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            
            $model->addScript(PLUGINURL . 'lib/flexigrid/flexigrid.js', 'flexigrid.js', 1 );
            $model->addStyle(PLUGINURL . 'lib/flexigrid/flexigrid.css', 'flexigrid.css', 1 );
            
            $model->addStyle(TEMPLATEURL . 'default/dialogform.css', 'dialogform.css', 1 );
            $model->addScript(PLUGINURL . 'lib/tiny_mce/tiny_mce.js', 'tiny_mce.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1 );
            $model->addScript($model->pluginurl . 'propostaladmin.js', 'propostaladmin.js', 1 );            
?>
<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#grid1").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
                {display: "Name", name : "name", width : 150, sortable : true, align: "left"},
                {display: "Title", name : "title", width : 150, sortable : true, align: "left"},
                {display: "Approve", name : "approve", width : 50, sortable : true, align: "center"},
                {display: "Reject", name : "reject", width : 50, sortable : true, align: "center"},
                {display: "Complaint", name : "complaint", width : 50, sortable : true, align: "center"},
                {display: "Status", name : "status", width : 30, sortable : false, align: "center"},                
                {display: "Action", name : "action", width : 200, sortable : false, align: "center"}
                ],                
            searchitems : [
                {display: "Propostal", name : "propostal", isdefault: true}
                ],
            sortname: "ID",
            sortorder: "asc",
            usepager: true,
            title: "Propostals",
            useRp: true,
            rp: 20,
            showTableToggleBtn: false,
            onSuccess: propostalgridready,
            //onSubmit: agendafilter,
            width: 'auto',
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
        
                
        public function ajax_toggle(){
            global $model, $db;
            $model->mode = 0;

            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT pp.ID, pp.status FROM propostal AS pp WHERE pp.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->status > 0 ) $row->status = 0;
                else $row->status = 1;
                $db->updateObject('propostal', $row, 'ID' );
            } else {
                //not found
            }
        }
       
        public function ajax_toggledeputy(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT * FROM propostal AS pp WHERE pp.ID = " . $db->quote($ID)." LIMIT 1");
            $pp = null;
            if(!$db->loadObject($pp)) throw new Exception('bulunamadı');
            
            
            
            $db->setQuery("SELECT pr.ID, pr.status, pr.deputy FROM profile AS pr WHERE pr.ID = " . $db->quote($pp->deputyID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->deputy == 0 ) $row->deputy = 1;
                else $row->deputy = 0;
                $db->updateObject('profile', $row, 'ID' );
            } else {
                echo 'not found';
            }
        }        
                
        public function ajax_blockprofile(){ 
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT deputyID FROM propostal AS pp WHERE pp.ID = " . $db->quote($ID)." LIMIT 1");
            $pp = null;
            if(!$db->loadObject($pp)) throw new Exception('bulunamadı');
            
            
            $db->setQuery("SELECT p.ID, p.userID, p.status FROM profile AS p WHERE p.ID = " . $db->quote($pp->deputyID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->status == 0 ) $row->status = 1;
                else $row->status = 0;
                $db->updateObject('profile', $row, 'ID' );
                
                $prow = new stdClass;
                $prow->ID = $row->userID;
                $prow->status = $row->status;
                
                $db->updateObject('user', $prow, 'ID' );
                echo $ID;
            } else {
                echo 'not found';
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
            
            $SELECT = "SELECT pp.*, pr.name, pr.status AS prstatus, pr.deputy AS prdeputy";
            $SELECT.= ", ( SELECT sum(approve) FROM propostalvote WHERE propostalID=pp.ID) AS approve";
            $SELECT.= ", ( SELECT sum(reject) FROM propostalvote WHERE propostalID=pp.ID) AS reject";
            $SELECT.= ", ( SELECT sum(complaint) FROM propostalvote WHERE propostalID=pp.ID) AS complaint";
            $FROM   = "\n FROM propostal AS pp";
            $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID=pp.deputyID";
            //$JOIN   = "\n ";
            
            $WHERE  = "\n WHERE 1";
            //$WHERE .= "\n AND ppv.propostalID = pp.ID";
            
            if($query){
                if(in_array($qtype,array( 'name' )))
                    $WHERE .= "\n AND $qtype LIKE '%".$db->escape($query)."%'";
                else 
                    $WHERE .= "\n AND `title` LIKE '%".$db->escape($query)."%'";
            } else {
                //$WHERE .= "\n ";
            }
            
            $GROUP = "\n ";
            
            if(in_array($sortname, array( 'ID','name', 'name', 'approve', 'reject', 'complaint')) && in_array($sortorder, array( 'asc', 'desc')))
                $ORDER  = "\n ORDER BY $sortname $sortorder";
            else
                $ORDER = "\n ORDER BY ID";
                
            $LIMIT  = "\n LIMIT $start, $rp";
            
            $db->setQuery('SELECT COUNT(*)'.$FROM.$JOIN.$WHERE.$GROUP);
            $total = $db->loadResult();
            
            $data['page'] = $page;
            $data['total'] = $total;
            
            $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$GROUP.$ORDER.$LIMIT); 
            $rows = $db->loadObjectList();
            
            if(count($rows)){
                foreach($rows as $row){
                    //profili durdur/ aç
                    
                    //önemsiz  
                    
                    $buttons = '';
                    
                    if($row->prstatus>0)
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="blockprofile" title="edit"> Profili Engelle </a>';
                    else
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="blockprofile" title="edit"> Profil Engelini Kaldır </a>';
                        
                    if($row->prdeputy>0)
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="toggledeputy" title="edit"> Vekilliğini Düşür </a>';
                    else
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="toggledeputy" title="edit"> Vekilliğini Geri ver </a>';
                        /*
                    $buttons .= ' | ';
                        
                    if($row->distatus>0)
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="togglepp" title="edit"> Taslağı kaldır </a>';
                    else
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="togglepp" title="edit"> Taslağı geri al </a>';                        
                        */
                    
                    if($row->status>0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="active"><img src="'.PLUGINURL.'lib/icons/accept.png" alt="active" border="0" /></a> ';
                    elseif($row->status==0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="suspend"><img src="'.PLUGINURL.'lib/icons/time.png" alt="draft" border="0" /></a> ';
                    else
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="block"><img src="'.PLUGINURL.'lib/icons/stop.png" alt="deleted" border="0" /></a> ';

                        
                    
                    $action = '-';
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        $row->name, 
                                        $row->title,
                                        $row->approve,
                                        $row->reject,
                                        $row->complaint,
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