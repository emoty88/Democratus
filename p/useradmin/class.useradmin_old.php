<?php
    class useradmin_plugin extends control{
        
        public function main(){ //return print( $this->location_to_select('loca', 89) );
            global $model, $db;
            
            
            
            
            
            $model->template = 'cp';
            $model->title = 'User Admin | Democratus.com';
            
            if($model->useris('superadmin')||$model->useris('admin')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }
            
            if($model->paths[1] == 'ajax')
                return $this->ajax();
            
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
            $model->addScript($model->pluginurl . 'useradmin.js', 'useradmin.js', 1 );            
?>
<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
<script type="text/javascript">
   $("#grid1").flexigrid({
            url: "<?php echo $model->pageurl; ?>ajax/rows/",
            dataType: "json",
            colModel : [
                {display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
                {display: "Name", name : "name", width : 150, sortable : true, align: "left"},
                {display: "Email", name : "email", width : 150, sortable : true, align: "left"},
                {display: "Role", name : "role", width : 300, sortable : false, align: "center"},
                {display: "Status", name : "status", width : 30, sortable : false, align: "center"},                
                {display: "Action", name : "action", width : 200, sortable : false, align: "center"}
                ],                
            searchitems : [
                {display: "Name", name : "name", isdefault: true},
                {display: "Email", name : "email", isdefault: false},
                {display: "ID", name : "ID", isdefault: false}
                
                ],
            sortname: "status",
            sortorder: "desc",
            usepager: true,
            title: "Users",
            useRp: true,
            rp: 15,
            showTableToggleBtn: false,
            onSuccess: usergridready,
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
                    $disabled = ($model->useris('superadmin') )?'':'disabled="disabled"';
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
            $db->setQuery("SELECT u.ID, u.status, u.role FROM user AS u WHERE u.ID = " . $db->quote($ID)." LIMIT 1");
            $row = null;
            if($db->loadObject($row)){
                if($model->useris('superadmin') || ( $model->useris('admin') && !$model->useris('superadmin', $row->role))){
                    if($row->status == 0 ) $row->status = -1;
                    elseif($row->status < 0 ) $row->status = 1;
                    elseif($row->status > 0 ) $row->status = 0;
                    $db->updateObject('user', $row, 'ID' );
                    
                    $p = new stdClass;
                    $p->ID = $row->ID;
                    $p->status = $row->status;
                    $db->updateObject('profile', $p, 'ID' );
                    
                    
                                                            
                }

            } else {
                //not found
            }
        }        
        
        public function ajax_save(){
            global $model, $db;
            $model->mode = 0;
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                $db->setQuery("SELECT u.* FROM user AS u WHERE u.ID = " . $db->quote($ID)." LIMIT 1");
                $row = null;
                if($db->loadObject($row)){
                    
                    if($model->useris('superadmin') || ($model->useris('admin') && !$model->useris('superadmin', $row->role)))
                        $row->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                    
                    if($model->useris('superadmin') )
                        $row->role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_NUMBER_INT);
                    
                    if($db->updateObject('user', $row, 'ID')){
                        
                        echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>Update success</p>').'").dialog({
                            modal: true,
                            title: "Success",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 2000 );
                        ';
                        
                    } else throw new Exception('kayıt hatası oluştu');
                } else throw new Exception('bulunamadı');
                
            } catch (Exception $e){
                echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p>'.$e->getMessage().'</p>').'").dialog({
                            modal: true,
                            title: "Error",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 2000 );
                        ';                
            }
        }
        
        
        public function ajax_changepass(){
            global $model, $db;
            $model->mode = 0;
            try{
                $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
                
                $db->setQuery("SELECT ID, pass FROM user WHERE ID = " . $db->quote($ID)." LIMIT 1");
                $row = null;
                if($db->loadObject($row)){
                    
                    if($model->useris('superadmin') || ($model->useris('admin') && !$model->useris('superadmin', $row->role))){
                        $password = trim( filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) );
                        $password2 = trim( filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING));
                    } else 
                        throw new Exception('yetkiniz yok');
                    
                    
                    if($password!=$password2) throw new Exception('Şifreler aynı değil');
                    
                    $row->pass = md5(KEY . trim( $password ) );
                    
                    if($db->updateObject('user', $row, 'ID')){
                        
                        echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>Şifre güncellendi</p>').'").dialog({
                            modal: true,
                            title: "Success",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 2000 );
                        ';
                        
                    } else throw new Exception('kayıt hatası oluştu');
                } else throw new Exception('bulunamadı');
                
            } catch (Exception $e){
                echo 'var message = $("<div id=\"dialog-message\"></div>").html("'.addslashes('<p>'.$e->getMessage().'</p>').'").dialog({
                            modal: true,
                            title: "Error",
                            buttons: {Ok: function() {$( this ).dialog( "close" );}}
                        });
                        setTimeout(function(){ message.dialog( "close" ); }, 2000 );
                        ';                
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
            
            $SELECT = "SELECT u.*, p.name";
            $FROM   = "\n FROM user AS u";
            $JOIN   = "\n LEFT JOIN profile AS p ON p.ID = u.ID";
            if($query){
                
                if($qtype == 'email'){
                    $WHERE = "\n WHERE u.email LIKE '%".$db->escape($query)."%'";
                }elseif($qtype == 'name'){
                    $WHERE = "\n WHERE p.name LIKE '%".$db->escape($query)."%'";
                }else{
                    $WHERE = "\n WHERE p.ID = '".intval($query)."'";
                }
                /*
                if(in_array($qtype,array( 'name', 'email', 'ID')))
                    $WHERE = "\n WHERE u.$qtype LIKE '%".$db->escape($query)."%'";
                else 
                    $WHERE = "\n WHERE `name` LIKE '%".$db->escape($query)."%'";
                */
            } else {
                $WHERE = "\n ";
            }
            
            if(in_array($sortname, array( 'ID', 'email', 'status')) && in_array($sortorder, array( 'asc', 'desc')))
                $ORDER  = "\n ORDER BY $sortname $sortorder";
            else
                $ORDER = "\n ORDER BY ID DESC";
                
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
                    
                    if($model->useris('superadmin') || ( $model->useris('admin') && !$model->useris('superadmin', $row->role))){
                        $buttons.= ' <a href="#" rel="'.$row->ID.'" class="rowedit" title="edit"><img src="'.PLUGINURL.'lib/icons/comment_edit.png" alt="edit" border="0" /></a>';
                        $buttons.= ' <a href="#" rel="'.$row->ID.'" class="rowchangepass" title="change password"><img src="'.PLUGINURL.'lib/icons/key.png" alt="change password" border="0" /></a> ';
                    }
                    
                    
                    
                    if($row->status>0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="active"><img src="'.PLUGINURL.'lib/icons/accept.png" alt="active" border="0" /></a> ';
                    elseif($row->status==0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="suspend"><img src="'.PLUGINURL.'lib/icons/time.png" alt="draft" border="0" /></a> ';
                    else
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="block"><img src="'.PLUGINURL.'lib/icons/stop.png" alt="deleted" border="0" /></a> ';
                    
                    $role = array();
                    foreach($model->roles as $key=>$val){
                        if($val & $row->role) $role[] =  $key;
                    }
                    $role = implode(' + ', $role);
                    
                    
                    
                    $action = '-';
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        $row->name, 
                                        $row->email,
                                        $role . $buttons ,
                                        $status,
                                        $action
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