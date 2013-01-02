<?php
    class nextdeputies_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
       		$model->initTemplate('beta','default'); 
            //$model->mode=0;       
            if($model->paths[1]=='EfQGvWrwtKAHfWjf4Eds8usdfQvWr6f4j8tRkfKT') {$this->vekilPoint();}
            if($model->paths[1]=='EfQGvWr66f4j8tRkfKT4XklytXhbX0wtKAHfWjf4') {$this->vekilOy();}
			//die;
		}
		public function vekilOy(){
			  global $model, $db, $l;
            if($model->useris('superadmin')||$model->useris('admin')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }            
            
            //if($_SERVER['REMOTE_ADDR']!='178.63.46.159') die('pardon izin yok!');
            
            //oyu en yüksek olan ilk 100 kişiyi bul
            $SELECT = "SELECT pr.*, count(md.ID) AS votecount, u.email";
            $FROM   = "\n FROM profile AS pr, mydeputy AS md, user AS u";
            $JOIN   = "\n ";
            //$WHERE  = "\n WHERE md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
            $WHERE  = "\n WHERE md.datetime >= DATE_ADD(NOW(), INTERVAL -30 DAY) ";
            $WHERE .= "\n AND md.status>0";
            $WHERE .= "\n AND pr.status>0";
			$WHERE .= "\n AND pr.notVekil=0";
            $WHERE .= "\n AND md.deputyID = pr.ID";
            $WHERE .= "\n AND u.ID = pr.ID";
            $GROUP  = "\n GROUP BY md.deputyID";            
            $ORDER  = "\n ORDER BY votecount DESC, md.ID ASC";
            $LIMIT  = "\n LIMIT 80 " ; 
			
			//config::$deputylimit;

            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            if(count($rows)){
?>
                        <div id="follow_result" class="box">
                            <span class="title_icon">Vekil oy durumu</span>    
                            <div class="line_center"></div>
<?php
        $i = 0;
        foreach($rows as $row){
                    $i++;     
     
				$html = '<div class="roundedcontent">
					<div class="usrlist-pic">
						<a href="/profile/'.$row->ID.'"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'"  /></a>
					</div>
					<div class="usrlist-info">
						<table class="table-striped" style="width: 100%">
							<tbody><tr>
								<th> <a href="/profile/'.$row->ID.'">'.$i.' - '.$row->name.'</a> ('.$row->votecount.' oy )</th>
							</tr>
							<tr>
								<td colspan="5"><p>'.$row->motto.'</p></td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="usrlist-set">
				
					</div>
              	</div><p></p>';
                            echo $html;
							if($i==50)
							echo "<br /> <hr /> <br />";

                }
?>                    
                        </div>
<?php

            } else {
                echo '<p>hiç bulunamadı</p>';
            } 
            
            
           
        }        
       public function vekilPoint(){
            global $model, $db, $l;
            //$model->mode=0;            
            if($model->paths[1]!='EfQGvWrwtKAHfWjf4Eds8usdfQvWr6f4j8tRkfKT') die;
            
            if($model->useris('superadmin')||$model->useris('admin')){
                
            } else {
                $model->mode = 0;
                return print('<h3>bu sayfayı görüntülemeye yetkiniz yok!</h3>');
            }            
            
            //if($_SERVER['REMOTE_ADDR']!='178.63.46.159') die('pardon izin yok!');
            
            //oyu en yüksek olan ilk 100 kişiyi bul
            $SELECT = "SELECT pr.*,  u.email";
            $FROM   = "\n FROM profile AS pr, user AS u";
            $JOIN   = "\n ";
            //$WHERE  = "\n WHERE md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
            $WHERE  = "\n WHERE pr.status>0";
			$WHERE .= "\n AND pr.notVekil=0";
            $WHERE .= "\n AND u.ID = pr.ID";
			$WHERE .= "\n AND pr.type = 'person'";
            $GROUP  = "";            
            $ORDER  = "\n ORDER BY pr.puan DESC, pr.ID ASC";
            $LIMIT  = "\n LIMIT 80 " ; 
			
			//config::$deputylimit;

            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            if(count($rows)){
?>
                        <div id="follow_result" class="box">
                            <span class="title_icon">Vekil Puan durumu</span>    
                            <div class="line_center"></div>
<?php
        $i = 0;
        foreach($rows as $row){
                    $i++;     
					
                    $html = '<div class="roundedcontent">
					<div class="usrlist-pic">
						<a href="/profile/'.$row->ID.'"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'"  /></a>
					</div>
					<div class="usrlist-info">
						<table class="table-striped" style="width: 100%">
							<tbody><tr>
								<th> <a href="/profile/'.$row->ID.'">'.$i.' - '.$row->name.'</a> ('.$row->puan.'puan )</th>
							</tr>
							<tr>
								<td colspan="5"><p>'.$row->motto.'</p></td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="usrlist-set">
				
					</div>
              	</div><p></p>';
				/*
                              <div class="result" id="profile'.$row->ID.'">
                                <div class="image"><a href="/profile/'.$row->ID.'"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'" style="width: 50px" /></a></div>
                                <div class="content">
                                    <div class="head">
                                        <a href="/profile/'.$row->ID.'"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'" style="width: 50px" /></a>
                                    </div>
                                    <p>'.$row->motto.'</p>
                                    <span class="mini_about">'.$row->hometown.'</span>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                            ';*/
                            echo $html;
							if($i==50)
							echo "<br /> <hr /> <br />";

                }
?>                    
                        </div>
<?php

            } else {
                echo '<p>hiç bulunamadı</p>';
            } 
            
            //die;
           
        }    
    }
?>
