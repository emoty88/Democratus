<?php
    class userdetailadmin_plugin extends control{
        
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
            $model->title = 'Di Comment Complaint Admin | Democratus.com';
            
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
            $model->addScript($model->pluginurl . 'userdetailadmin.js', 'userdetailadmin.js', 1 );            

			?>
		<?php 
			$SELECT="SELECT p.*, c.country as countryName, ct.city as cityName ";
			$FROM="\n FROM profile p ";
			$JOIN="\n LEFT JOIN country   AS c ON c.ID = p.countryID";
			$JOIN.="\n LEFT JOIN city  AS ct ON ct.ID = p.cityID";
			$WHERE="\n WHERE p.ID='".$model->paths[1]."' ";
			$db->setQuery( $SELECT.$FROM.$JOIN.$WHERE );
			$db->loadObject($user);
			//echo "<pre>";
			//var_dump($user);
			//echo "</pre>";
		?>
<table style="width:100%">
	<tr>
		<td width="200">
			<img src="<?=$model->getProfileImage($user->image, 150,200, 'cutout')?>" />
		</td>
		<td valign="top">
			Name: <?=$user->name?> <br/>
			Birth Day: <?=$user->birth?><br/>
			Hometown: <?=$user->hometown?><br/>
			Country: <?=$user->countryName?><br/>
			City: <?=$user->cityName?><br/>
			Last Login IP: <?=$user->lastLoginIP ?><br/>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%">
				<tr>
					<td width="50%">
						<table id="grid1" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
						<script type="text/javascript">
						   $("#grid1").flexigrid({
									url: "<?php echo $model->pageurl; ?>ajax/rows_di/<?=$model->paths[1]?>",
									dataType: "json",
									colModel : [
										{display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
										{display: "di", name : "di", width : 400, sortable : true, align: "left"}
										],                
									sortname: "ID",
									sortorder: "desc",
									usepager: true,
									title: "Voice",
									useRp: true,
									rp: 15,
									showTableToggleBtn: false,
									onSuccess: userdigridready,
									//onSubmit: agendafilter,
									width: 480,
									height: 210
									}
									);
						   
						   
							  var ajaxurl = '<?php echo $model->pageurl; ?>ajax/'
							  
						</script>
					</td>
					<td width="50%">
					<table id="grid2" style="display: none;" class="flexigrid"><tr><td>&nbsp;</td></tr></table>
						<script type="text/javascript">
						   $("#grid2").flexigrid({
									url: "<?php echo $model->pageurl; ?>ajax/rows_comment/<?=$model->paths[1]?>",
									dataType: "json",
									colModel : [
										{display: "ID", name : "ID", width : 30, sortable : true, align: "center"},
										{display: "Voice", name : "Voice", width : 200, sortable : true, align: "left"},
										{display: "Commentt", name : "commentt", width : 200, sortable : true, align: "left"}
										],                
									sortname: "ID",
									sortorder: "desc",
									usepager: true,
									title: "Comments",
									useRp: true,
									rp: 15,
									showTableToggleBtn: false,
									onSuccess: userdigridready,
									//onSubmit: agendafilter,
									width: 480,
									height: 210
									}
									);
						   
						   
							  var ajaxurl = '<?php echo $model->pageurl; ?>ajax/'
							  
						</script>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
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
                    
        public function ajax_rows_comment(){
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
            
           $SELECT = "SELECT dc.*,.d.di di ";
           $FROM   = "\n FROM dicomment dc LEFT JOIN di d on d.ID=dc.diID";
           $WHERE = "\n where dc.profileID='".$model->paths[3]."' ";
            
			$ORDER = "\n ORDER BY ID desc";    
            $LIMIT  = "\n LIMIT $start, $rp";
            
            $db->setQuery('SELECT COUNT(*)'.$FROM.$WHERE);
            $total = $db->loadResult();
            
            $data['page'] = $page;
            $data['total'] = $total;
            
            $db->setQuery($SELECT.$FROM.$WHERE.$ORDER.$LIMIT); 
            $rows = $db->loadObjectList();
            
            if(count($rows)){
                foreach($rows as $row){
                    //profili durdur/ aç
                    
                    //önemsiz
                    
         
                    
      
                    
                    if($row->status>0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="active"><img src="'.PLUGINURL.'lib/icons/accept.png" alt="active" border="0" /></a> ';
                    elseif($row->status==0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="suspend"><img src="'.PLUGINURL.'lib/icons/time.png" alt="draft" border="0" /></a> ';
                    else
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="block"><img src="'.PLUGINURL.'lib/icons/stop.png" alt="deleted" border="0" /></a> ';

                    $dicomment = '<a href="/di/'.$row->diID.'" target="_blank">'.$row->comment.'</a>';
                    $di = '<a href="/di/'.$row->diID.'" target="_blank">'.$row->di.'</a>';
                    
                
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        $di,
                                        $dicomment
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
            public function ajax_rows_di(){
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
            
           $SELECT = "SELECT * ";
           $FROM   = "\n FROM di";
           $WHERE = "\n where profileID='".$model->paths[3]."' ";
            
			$ORDER = "\n ORDER BY ID desc";    
            $LIMIT  = "\n LIMIT $start, $rp";
            
            $db->setQuery('SELECT COUNT(*)'.$FROM.$WHERE);
            $total = $db->loadResult();
            
            $data['page'] = $page;
            $data['total'] = $total;
            
            $db->setQuery($SELECT.$FROM.$WHERE.$ORDER.$LIMIT); 
            $rows = $db->loadObjectList();
            
            if(count($rows)){
                foreach($rows as $row){
                    //profili durdur/ aç
                    
                    //önemsiz
                    
         
                    
      
                    
                    if($row->status>0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="active"><img src="'.PLUGINURL.'lib/icons/accept.png" alt="active" border="0" /></a> ';
                    elseif($row->status==0)
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="suspend"><img src="'.PLUGINURL.'lib/icons/time.png" alt="draft" border="0" /></a> ';
                    else
                        $status = '<a href="#" rel="'.$row->ID.'" class="rowtoggle" title="block"><img src="'.PLUGINURL.'lib/icons/stop.png" alt="deleted" border="0" /></a> ';

                    $di = '<a href="/di/'.$row->ID.'" target="_blank">'.$row->di.'</a>';
                    
                
                    $datarows[] = array(
                        "ID" => $row->ID,
                        "cell" => array(
                                        $row->ID,
                                        $di
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