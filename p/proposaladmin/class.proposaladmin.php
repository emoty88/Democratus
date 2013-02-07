<?php
    class proposaladmin_plugin extends control{
        
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            
            if($model->useris('superadmin')||$model->useris('admin')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'cp';
            $model->title = 'Propoal Admin | Democratus.com';
            
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
            $model->addScript(PLUGINURL . 'lib/tiny_mce/tiny_mce.js', 'tiny_mce.js', 1 );
            
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1 );
            $model->addScript($model->pluginurl . 'proposaladmin.js', 'proposaladmin.js', 1 );            
?>
<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#grid1").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
                {display: "isim", name : "name", width : 100, sortable : true, align: "left"},
                {display: "Title", name : "title", width : 300, sortable : true, align: "left"},
                {display: "Approve", name : "approve", width : 30, sortable : true, align: "center"},
                {display: "Reject", name : "reject", width : 30, sortable : true, align: "center"},
                {display: "Complaint", name : "complaint", width : 30, sortable : true, align: "center"},
                {display: "Status", name : "status", width : 30, sortable : false, align: "center"},                
                {display: "Datetime", name : "datetime", width : 100, sortable : false, align: "center"},                
                {display: "Action", name : "action", width : 200, sortable : false, align: "center"}
                ],                
            searchitems : [
                {display: "proposal", name : "proposal", isdefault: true}
                ],
            sortname: "ID",
            sortorder: "desc",
            usepager: true,
            title: "proposals",
            useRp: true,
            rp: 15,
            showTableToggleBtn: false,
            onSuccess: proposalgridready,
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
        
        
        public function ajax_edit__eski_sil(){
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
            $db->setQuery("SELECT pp.ID, pp.status FROM proposal AS pp WHERE pp.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->status > 0 ) $row->status = 0;
                else $row->status = 1;
                $db->updateObject('proposal', $row, 'ID' );
            } else {
                //not found
            }
        }
       
        public function ajax_toggledeputy(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            
            //$db->setQuery("SELECT * FROM proposal AS pp WHERE pp.ID = " . $db->quote($ID)." LIMIT 1");
            //$pp = null;
            //if(!$db->loadObject($pp)) throw new Exception('bulunamadı');
            
            
            
            $db->setQuery("SELECT pr.ID, pr.status, pr.deputy FROM profile AS pr WHERE pr.ID = " . $db->quote($ID)." LIMIT 1");
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
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                $db->setQuery("SELECT * FROM profile WHERE ID = " . $db->quote($ID)." LIMIT 1");
                $p = null;
                if(!$db->loadObject($p)) throw new Exception('profil bulunamadı');
                
                $db->setQuery("SELECT * FROM user WHERE ID = " . $db->quote($ID)." LIMIT 1");
                $u = null;
                if(!$db->loadObject($u)) throw new Exception('kullanıcı bulunamadı');
                
                if($model->useris('superadmin',$u->role)||$model->useris('admin',$u->role)||$model->useris('moderator',$u->role)){
                    throw new Exception('yetkiniz yok!');
                }
                
                if($p->status>0){
                    $p->status = 0;
                    $u->status = 0;
                } else {
                    $p->status = 1;
                    $u->status = 1;
                }
                $db->updateObject('user', $u, 'ID' );
                $db->updateObject('profile', $p, 'ID' );
                
                
            
            } catch (Exception $e){
                echo $e->getMessage();
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
            $SELECT.= ", ( SELECT sum(approve) FROM proposalvote WHERE proposalID=pp.ID) AS approve";
            $SELECT.= ", ( SELECT sum(reject) FROM proposalvote WHERE proposalID=pp.ID) AS reject";
            $SELECT.= ", ( SELECT sum(complaint) FROM proposalvote WHERE proposalID=pp.ID) AS complaint";
            $FROM   = "\n FROM proposal AS pp";
            $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID=pp.deputyID";
            //$JOIN   = "\n ";
            
            $WHERE  = "\n WHERE 1";
            //$WHERE .= "\n AND ppv.proposalID = pp.ID";
            
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
                    
                    $buttons = ' <a href="#" rel="'.$row->ID.'" class="rowedit" title="edit"><img src="'.PLUGINURL.'lib/icons/comment_edit.png" alt="edit" border="0" /></a>';
                    
                    
                    if($row->prstatus>0)
                        $buttons .= ' <a href="#" rel="'.$row->deputyID.'" class="blockprofile" title="edit"> Profili Engelle </a>';
                    else
                        $buttons .= ' <a href="#" rel="'.$row->deputyID.'" class="blockprofile" title="edit"> Profil Engelini Kaldır </a>';
                        
                    if($row->prdeputy>0)
                        $buttons .= ' <a href="#" rel="'.$row->deputyID.'" class="toggledeputy" title="edit"> Vekilliğini Düşür </a>';
                    else
                        $buttons .= ' <a href="#" rel="'.$row->deputyID.'" class="toggledeputy" title="edit"> Vekilliğini Geri ver </a>';
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
                                        asdatetime( $row->datetime,'Y-m-d H:i:s' ),
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
        
        public function ajax_edit(){
            global $model, $db;
            $response = array();
            try{
                
                $ID   = intval(filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT));
                if($ID<1) throw new Exception('ID error');
                
                $db->setQuery("SELECT * FROM proposal WHERE ID = ".$db->quote($ID));
                $row = null;
                if(!$db->loadObject($row)) throw new Exception('not found');

                $html = '<form action="" class="form" method="post" id="editform">';

                $html.= '<p>';
                $html.= '<label>Proposal</label>';
                $html.= '<textarea name="proposal">'.$row->title.'</textarea>';
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
                $form->proposal   = filter_input(INPUT_POST, 'proposal', FILTER_SANITIZE_STRING);

                 
                if(intval($form->ID)>0){
                    $db->setQuery("SELECT * FROM proposal WHERE ID = " . $db->quote($form->ID));
                    $row = null;
                    if($db->loadObject($row)){
                        $row->title     = $form->proposal;
                        $row->spot     = $form->proposal;
                          
                        $db->updateObject('proposal', $row, 'ID', true);
                        
                    } else {
                        throw new Exception('not found');
                    }
                    
                } else {
                    
                      throw new Exception('proposal not found!');
                    
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
