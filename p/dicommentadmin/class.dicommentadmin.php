<?php
    class dicommentadmin_plugin extends control{
        
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            
            if($model->useris('superadmin')||$model->useris('admin')||$model->useris('moderator')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'cp';
            $model->title = 'Di Comment Admin | Democratus.com';
            
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
            $model->addScript($model->pluginurl . 'dicommentadmin.js', 'dicommentadmin.js', 1 );            
?>
<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#grid1").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
                {display: "di", name : "di", width : 150, sortable : true, align: "left"},
                {display: "comment", name : "comment", width : 250, sortable : true, align: "left"},
                {display: "Status", name : "status", width : 30, sortable : false, align: "center"},                
                {display: "Datetime", name : "datetime", width : 150, sortable : false, align: "center"},                
                {display: "Action", name : "action", width : 200, sortable : false, align: "center"}
                ],                
            searchitems : [
                {display: "comment", name : "comment", isdefault: true}
                ],
            sortname: "ID",
            sortorder: "desc",
            usepager: true,
            title: "Comments",
            useRp: true,
            rp: 15,
            showTableToggleBtn: false,
            onSuccess: dicommentgridready,
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
      
        public function ajax_toggle(){
            global $model, $db;
            $model->mode = 0;

            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            $db->setQuery("SELECT ID, status FROM dicomment  WHERE ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->status > 0 ) $row->status = 0;
                else $row->status = 1;
                $db->updateObject('dicomment', $row, 'ID' );
            } else {
                //not found
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
            /*
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
            */
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
            
            $SELECT = "SELECT dic.*, pr.name, pr.status AS prstatus";
            $FROM   = "\n FROM dicomment AS dic";
            $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID=dic.profileID";
            //$JOIN   = "\n ";
            
            $WHERE  = "\n WHERE 1";
            //$WHERE .= "\n AND ppv.proposalID = pp.ID";
            
            if($query){
                switch($qtype){
                    case 'name': $WHERE .= "\n AND name LIKE '%".$db->escape($query)."%'"; break;
                    case 'comment': $WHERE .= "\n AND comment LIKE '%".$db->escape($query)."%'"; break;
                    default: $WHERE .= "\n AND comment LIKE '%".$db->escape($query)."%'";
                }
            } else {
                //$WHERE .= "\n ";
            }
            
            $GROUP = "\n ";
            
            if(in_array($sortname, array( 'ID', 'name' )) && in_array($sortorder, array( 'asc', 'desc')))
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
                    
                    if($row->status>0)
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="toggle" title="edit"> Yankıyı kaldır</a>';
                    else
                        $buttons .= ' <a href="#" rel="'.$row->ID.'" class="toggle" title="edit"> Yankıyı göster </a>';

                    if($row->prstatus>0)
                        $buttons .= ' <a href="#" rel="'.$row->profileID.'" class="blockprofile" title="edit"> Profili Engelle </a>';
                    else
                        $buttons .= ' <a href="#" rel="'.$row->profileID.'" class="blockprofile" title="edit"> Profil Engelini Kaldır </a>';
                    
                    
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
                                        $row->comment,
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
    }
?>