<?php
    class deleteditemadmin_plugin extends control{
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            
            if($model->useris('admin')||$model->useris('admin')||$model->useris('moderator')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
            $model->template = 'cp';
            $model->title = 'Deleted Item Admin | Democratus.com';
            
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
            $model->addScript($model->pluginurl . 'deleteditemadmin.js', 'deleteditemadmin.js', 1 );            
?>
<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#grid1").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
                {display: "Name", name : "name", width : 100, sortable : true, align: "left"},
                {display: "Di", name : "di", width : 350, sortable : true, align: "left"},
                {display: "Status", name : "status", width : 30, sortable : false, align: "center"},                
                {display: "Deletes", name : "deletes", width : 150, sortable : false, align: "center"},
                {display: "Deletes IP", name : "deletesip", width : 100, sortable : false, align: "center"},
                {display: "Deletes Time", name : "deleterime", width : 125, sortable : false, align: "center"}
                ],                
            searchitems : [
                {display: "Name", name : "name", isdefault: true}
                ],
            sortname: "ID",
            sortorder: "desc",
            usepager: true,
            title: "Deleted Items",
            useRp: true,
            rp: 15,
            showTableToggleBtn: false,
            onSuccess: deleteditemgridready,
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
            $db->setQuery("SELECT dcc.ID, dcc.status FROM dicommentcomplaint AS dcc WHERE dcc.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($row->status > 0 ) $row->status = 0;
                else $row->status = 1;
                $db->updateObject('dicomment', $row, 'ID' );
            } else {
                //not found
            }
        }
       
        public function ajax_toggledic(){
            global $model, $db;
            $model->mode = 0;
            $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
            /*
            $db->setQuery("SELECT dicID FROM dicommentcomplaint AS dc WHERE dc.ID = " . $db->quote($ID)." LIMIT 1");
            $dc = null;
            if(!$db->loadObject($dc)) throw new Exception('bulunamadı');
            */
            
            
            $db->setQuery("SELECT dic.ID, dic.status FROM dicomment AS dic WHERE dic.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                
                //print_r($row);die;
                
                if($row->status == 0 ) $row->status = 1;
                else 
                $row->status = 0;
                //$row->status = 0;
                if($db->updateObject('dicomment', $row, 'ID' )){
                    echo 'success';
                } else {
                    echo 'error';
                }
                
                
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
            
            $SELECT = "SELECT di.*, pr.name as name, pr.status AS prstatus";
            $FROM   = "\n FROM di AS di";
            $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID=di.profileID";
            if($query){
                if(in_array($qtype,array( 'name' )))
                    $WHERE = "\n WHERE di.status=0 and $qtype LIKE '%".$db->escape($query)."%'";
                else 
                    $WHERE = "\n WHERE di.status=0 and `title` LIKE '%".$db->escape($query)."%'";
            } else {
                $WHERE = "\n where di.status=0  ";
            }
            
            //$WHERE = "\n AND pr.ID = prc.profileID";
            
            if(in_array($sortname, array( 'ID','name', 'email', 'permalink')) && in_array($sortorder, array( 'asc', 'desc')))
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
                    //profili durdur/ aç
                    
                    //önemsiz
                    
                    $buttons = ' <a href="#" rel="'.$row->ID.'" class="rowedit" title="edit"><img src="'.PLUGINURL.'lib/icons/comment_edit.png" alt="edit" border="0" /></a>';
                    $buttons.= ' <a href="#" rel="'.$row->ID.'" class="rowchangepass" title="change password"><img src="'.PLUGINURL.'lib/icons/key.png" alt="change password" border="0" /></a> ';

                    $buttons = '';
                    
                    if($row->status>0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="active"><img src="'.PLUGINURL.'lib/icons/accept.png" alt="active" border="0" /></a> ';
                    elseif($row->status==0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="suspend"><img src="'.PLUGINURL.'lib/icons/time.png" alt="draft" border="0" /></a> ';
                    else
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="block"><img src="'.PLUGINURL.'lib/icons/stop.png" alt="deleted" border="0" /></a> ';

                    $di = '<a href="/di/'.$row->ID.'" target="_blank">'.$row->di.'</a>';
                    //$dic = '<a href="/di/'.$row->diID.'#'.$row->dicID.'" target="_blank">'.$row->comment.'</a>';
                    
                    $SELECT="SELECT lg.*, pr.name ";
                    $FROM   = "\n FROM log AS lg";
            		$JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID=lg.userID";
            		$WHERE  ="\n where event='diDelete' and itemID='".$row->ID."'";
            		$db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$ORDER.$LIMIT); 
               		$silen = $db->loadObjectList();
               		if(count($silen)>0)
               		{
               			$silen=$silen[0];
               			$deletesName=$silen->name;
               			$deletesIP=$silen->ip;
               			$deteteTime=$silen->time;
               		}
               		else {
               			$deletesName="log Tutulamadı";
               			$deletesIP="0";
               			$deteteTime="0";
               		}
               		
           			
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        $row->name, 
                                        $di,
                                        $status,
                                        $deletesName,
                                        $deletesIP,
                                        $deteteTime
			
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