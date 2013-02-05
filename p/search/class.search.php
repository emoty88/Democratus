<?php
    class search_plugin extends control{
        public $limit = 14;
        
        public function main(){
			global $model, $db, $l;
			$model->template="ala";
			$model->view="default";
			$model->title = 'Democratus';
			
			$model->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
            $model->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);

            $model->addScript(TEMPLATEURL."ala/js/jquery.tmpl.js", "jquery.tmpl.js", 1);
			
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='search'");
			$model->addScript("keyword='".$model->paths[1]."'");
			//echo $model->paths[1]." arandı <br />";
			//echo "hazırlanıyor";
			?>
			<section class="banner">
				<header>
					<h1>ARAMA SONUÇLARI</h1>
				</header>
				<nav>
					<ul class="alt_menu visible-desktop" id="tab-container" >
						<li class="active"><a href="#tab-kisiler" rel="kisiler" data-toggle="tab" >KİŞİLER</a></li>
						<li><a href="#tab-sesler" rel="sesler" data-toggle="tab" >SESLER</a></li>
						<li><a href="#tab-arsivler" rel="arsivler" data-toggle="tab" >ARŞİVLER</a></li>
					</ul>
					<select class="mobil_menu hidden-desktop" id="alt_menu_mobil">
						<option value="">KİŞİLER</option>
						<option value="">SESLER</option>
						<option value="">ARŞİVLER</option>
					</select>
				</nav>
				<div class="clearfix"></div>
			</section>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="tab-kisiler">
					<!-- Referandumlar Tab -->
					<section id="kisiler-container" class="duvar_yazilari">
						
					</section>
					<!-- Referandumlar Tab Son -->
				</div>
				<div class="tab-pane fade in" id="tab-sesler">
					<!-- Kişiler Tab -->
					<section id="sesler-container" class="duvar_yazilari">
						
					</section>
					<!-- Kişiler Tab Son -->
				</div>
				<div class="tab-pane fade in" id="tab-arsivler">
					<!-- Arşiv Tab -->
					<section id="arsivler-container" class="duvar_yazilari">
							
					</section>
					<!-- Arşiv Tab Son -->
				</div>
			</div>
			<?
		}
		public function main_old()
		{
            global $model, $db, $l;
            
            if($model->paths[1] == 'ajax') return $this->ajax();
            if($model->newDesign)   
            $model->initTemplate('beta', 'default');
            else
            $model->initTemplate('v2', 'search');
            //$limit = 2;
            
            $keyword = filter_var($model->paths[1], FILTER_SANITIZE_STRING);
            $keyword2 = filter_input(INPUT_GET,'q', FILTER_SANITIZE_STRING);
        
            if( strlen($keyword2) ) $keyword = $keyword2;
            
            
			//header("location: /".$model->profile->permalink);
			$db->setQuery('SELECT permalink,type FROM profile WHERE permalink like ' .$db->quote($keyword)." AND status='1'");
			
		    if($db->loadObject($permalinkP)) {
		    	if($permalinkP->type=="hashTag")
				{
					header("location: /t/".$permalinkP->permalink);
				}
		        if($permalinkP->permalink!=""){
		        	header("location: /".$permalinkP->permalink);
		     	}
			}
            
            //$range = filter_var($model->paths[1], FILTER_SANITIZE_STRING);
            
            $followerID = intval( $model->profileID );            
            if($model->newDesign)
            { //new  design start
            	$model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
	            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
	            //$model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1);
	            $model->addScript($model->pluginurl . 'search.js', 'search.js', 1);
	            $model->addScript('keyword="'.$keyword.'";');
	            $model->addScript ( "var activeStatistic=0;" );
				$model->addScript("paths=".json_encode($model->paths));
            ?>
           
            <ul class="nav nav-tabs" id="tab">
				<li class="active">
					<button data-toggle="tab" href="#tab-kisiler" rel="kisiler" class="tabbtn tooltip-top" data-original-title="Oylarınızla belirlenen ve her hafta yenilenen vekil listesi.">Kişiler</button>
				</li>
				<li class="">
					<button data-toggle="tab" href="#tab-sesler" rel="sesler" class="tabbtn tooltip-top" data-original-title="Gelecek hafta meclise girmesini istediğiniz 10 kişiye oylarınızı veriniz.">Sesler</button>
				</li>
				<li class="">
					<button data-toggle="tab" href="#tab-arsivler" rel="arsivler" class="tabbtn tooltip-top" data-original-title="Şimdiye kadar oylamaya sunulmuş Meclis gündemleri.">Arşivler</button>
				</li>
				<li class="">
					<button data-toggle="tab" href="#tab-etiketler" rel="etiketler" class="tabbtn last tooltip-top" data-original-title="Mecliste tartışılması ve oylanması için tasarılarınızı yazınız.">Etiketler</button>
				</li>
			</ul> 
			<div id="myTabContent" class="tabuser-content">
				<div class="tab-pane fade in active" id="tab-kisiler">
					<div class="roundedcontent" style="width: 500px;">
		            	<?php 
			            $found = $this->find($keyword);
			            if($found['count']>0){
			                echo $found['html'];
			            } else {
			                //echo 'hiç yok!';
			            	echo "Üzgünüz, aradığınız içeriği bulamadık.";
			            }
			            ?>
		            </div>
		    	</div>
		    	<div class="tab-pane fade in " id="tab-sesler">
		    		
		    		<?php 
			            $found = $this->findVoice($keyword);
			            if($found['count']>0){
			                echo $found['html'];
			                
			            } else {
			            	echo "Üzgünüz, aradığınız içeriği bulamadık.";
			            }
			         ?>
		    		
		    	</div>
		    	<div class="tab-pane fade in " id="tab-arsivler">
		    		<div class="roundedcontent" style="width: 500px;">
		    		
		    		<?php 
			            $found = $this->findArchive($keyword);
			            if($found['count']>0){
			                echo $found['html'];
			                
			            } else {
			                //echo 'hiç yok!';
			            	echo "Üzgünüz, aradığınız içeriği bulamadık.";
			            }
			         ?>
		    		
		    		</div>
		    	</div>
		    	<div class="tab-pane fade in " id="tab-etiketler">
		    		<div class="roundedcontent shareidea">
								<h1>
									<img src="http://ofistesenlik.com/t/beta/img/democratus_icon.png"> 
									Bu Modül Yapım Aşamasındadır.
								</h1>
								<div class="clear"></div>
								<p>Bu modül henüz hazır değil. Kullanıcılarımıza kusursuz bir deneyim sunmak için test etmeye devam ediyoruz.</p>
							</div>
		    	</div>
		    </div>
            <?php 
            } //new design end
            else 
            { // old design start
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            $model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1);
            $model->addScript($model->pluginurl . 'search.js', 'search.js', 1);
            $model->addScript('keyword="'.$keyword.'";');
            
            
?>
                        <div id="search_result" class="box">
                            <span class="title_icon">Ara bul!</span>    
                            <div class="line_center"></div>
                            <div id="search_result_first">
<?php       
            $found = $this->find($keyword);
            if($found['count']>0){
                echo $found['html'];
                
            } else {
                //echo 'hiç yok!';
            }
?>
            </div>            
    
      </div>
<?php
           
            if($found['count']>0){
                
                //echo '<input type="button" id="searchmore" value="more" rel="'.$found['nextstart'].'" />';
                echo '<span class="more" rel="'.$found['nextstart'].'" id="searchmore">&nbsp;</span>';
            } else {
                echo '<a href="javascript:;"> hiç yok!</a>';
            }
        
            }// old design end
            
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
        
        public function ajax_more(){
            global $model, $db;
            
            $followerID = intval( $model->profileID );            
            

            $keyword    = filter_input(INPUT_POST, 'keyword', FILTER_SANITIZE_STRING);
            $start      = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
            //$limit      = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);

            $found = $this->find($keyword, $start);
            if($found['count']>0){
                //$found['html'].='<input type="button" id="searchmore" value="more" rel="'.$found['nextstart'].'" />';
                
            } else {
                
            }
            
                $response['result'] = 'success';
                $response['ids'] = $found['ids'];
                $response['html'] = $found['html'];
                $response['count'] = $found['count'];
                $response['nextstart'] = $found['nextstart'];
            
            echo json_encode($response);
        }        
        
        public function find($keyword, $start=0, $limit=14){
            global $model, $db, $l;
            $keyword = strip_tags( filter_var($keyword, FILTER_SANITIZE_STRING) );
            $start = intval($start);
            if($start<0) $start = 0;
            
            $limit = intval($limit);
            if($limit<1) $limit = 14;
            
            $followerID = intval( $model->profileID );            
            
            $model->title = 'Arama sonuçları : ' . $keyword . ' | ' . SITENAME;
            
            
            $SELECT = "SELECT p.*, f.followerstatus, f.followingstatus";
            $SELECT.= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
            $FROM   = "\n FROM profile AS p";
            $JOIN   = "\n LEFT JOIN follow AS f ON f.followingID = p.ID AND f.followerID=" .$db->quote($followerID);
            //$WHERE  = "\n WHERE p.name LIKE '%". $db->escape( $keyword )."%'";
            //$WHERE  = "\n WHERE (MATCH (p.name) AGAINST (".$db->quote( $keyword )." IN BOOLEAN MODE) OR p.name LIKE '%". $db->escape( $keyword )."%')";
            $WHERE  = "\n WHERE p.name LIKE '%". $db->escape( $keyword )."%'";
            $WHERE .= "\n AND p.status>0";
            //$ORDER  = "\n ORDER BY MATCH (p.name) AGAINST (".$db->quote( $keyword )." IN BOOLEAN MODE) DESC, p.name ASC";
            $ORDER  = "\n ORDER BY p.name ASC";
            $ORDER  = "\n ORDER BY RAND()";
            $LIMIT  = "\n LIMIT $start, $limit";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            //echo $db->_sql;
            //die;
            
            $rows = $db->loadObjectList();

            if(count($rows)){
                $i=0;
                $ids = array();
                $html = '<div id="search_result_'.$start.'">';
                $htmlNew="";
                foreach($rows as $row){
                    $i++;
                    $ids[] = $row->ID;
                    
                    $SELECT = "SELECT DISTINCT f.followingID, p.*";
					$FROM   = "\n FROM #__follow AS f";
					$JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followerID";
					$WHERE  = "\n WHERE f.followingID=".$db->quote($row->ID);
					$WHERE .= "\n AND f.status>0";
					$ORDER  = "\n ORDER BY f.datetime DESC";
					$LIMIT  = "\n LIMIT 5";
					            
					$db->setQuery('SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
					$countTakipci = intval( $db->loadResult() );
					            
					$SELECT = "SELECT f.followerID, p.*";
					$SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
					            
					$FROM   = "\n FROM #__follow AS f";
					$JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followingID";
					$WHERE  = "\n WHERE f.followerID=".$db->quote($row->ID);
					$WHERE .= "\n AND f.status>0";
					$ORDER  = "\n ORDER BY f.datetime DESC";
					$LIMIT  = "\n LIMIT 5";
					            
					$db->setQuery('SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
					$countTakipedilen = intval( $db->loadResult() );
                    //$profileinfo = profile::getinfobyrow($row);
                    
                    if($model->profileID>0){
                    	if($model->newDesign)
                    	{
                    		
			                        if($row->followingstatus>0){
			                            $follow = 'hide';
			                            $unfollow = '';
			                        } else {
			                            $follow = '';
			                            $unfollow = 'hide';
			                        }
			                        
			                        if($row->ID==$model->profileID){
			                            $follow_button = '<button class="btn btn-vekil">SENSİN</button>';
			                        } else {
			                             $follow_button = '<button id="follow'.$row->ID.'"  class="btn btn-vekil follow '.$follow.'" rel="'.$row->ID.'">Takip Et</button>
						                    				  <button id="unfollow'.$row->ID.'"  class="btn btn-takipetme unfollow '.$unfollow.'" rel="'.$row->ID.'">Takip Etme</button>
						                                      ';
			                        }
			                 
                    	}
                    	else
                    	{
		                        if($row->followingstatus>0){
		                            $follow = 'hide';
		                            $unfollow = '';
		                        } else {
		                            $follow = '';
		                            $unfollow = 'hide';
		                        }
		                        
		                        if($row->ID==$model->profileID){
		                            $follow_button = '<span class="you">Sensin!</span>';
		                        } else {
		                            $follow_button = '<span id="follow'.$row->ID.'" class="follow '.$follow.'" rel="'.$row->ID.'">Takip Et</span>
		                                              <span id="unfollow'.$row->ID.'" class="unfollow '.$unfollow.'" rel="'.$row->ID.'">Takip Etme!</span>
		                                              ';
		                        }
                    	}
                    } else {
                        $follow_button = '';
                    }
                    
                   $htmlNew .= '
                    	<div id="findUser-'.$row->ID.'">
		                	<div class="usrlist-pic"><a href="/profile/'.$row->ID.'" ><img src="'.$model->getProfileImage($row->image, 67, 67, 'cutout').'"></a></div>
								<div class="usrlist-info">
									<table class="table-striped" style="width:100%">
										<tbody><tr>
											<th><span><a href="/profile/'.$row->ID.'" >'.$row->name." ".$row->surname.'</a></span></th>
											<th><a href="#">'.$row->di_count.' Ses</a></th>
											<th><a href="#">'.$row->dilike1_count.' Takdir</a></th>
											<th><a href="#">'.$row->dilike2_count.' Saygı</a></th>
											
										</tr>
										<tr>
											<td colspan="5"><p>'.$row->motto.'</p></td>
										</tr>
									</tbody></table>
								</div>
								<div class="usrlist-set">
									<ul>
										<li>'.$follow_button.'</li>
										<li><strong>'.$countTakipedilen.'</strong> Takip Ettiği</li>
										<li><strong>'.$countTakipci.'</strong> Takipçi</li>
									</ul>
								</div>
				             <hr class="rounded_hr">
				             </div>
				             ';
                    
                }
                
                
                
                //die('--------------'.$html.'--------------');
                
                $response['html'] = $htmlNew;//.'<input type="button" id="searchmore" value="more" rel="'.$start + $i.'" />';
               
                $response['count'] = count($rows);
                $response['start'] = $row->ID;
                $response['ids'] = $ids;
                $response['nextstart'] = $start + $i;
                
            } else {
            	if($model->newDesign)
            	{
            		if($start==0)
                	$response['html'] =  'Üzgünüz, aradığınız içeriği bulamadık.';//.'<input type="button" id="searchmore" value="more" rel="'.$start + $i.'" />';
            		else
            		$response['html'] =  '<a href="javascript:;"> Gösterilecek başka yok.</a>';
            	}
                else
                $response['html'] = '<a href="javascript:;">Gösterilecek başka yok.</a>';
                $response['count'] = 1;
                $response['start'] = 0;
                $response['ids'] = array();
                $response['nextstart'] = $start;
            }            
            return $response;
        }
        public function findVoice($keyword, $start=0, $limit=14){
        	global $model, $db, $l;
        	$keyword = strip_tags( filter_var($keyword, FILTER_SANITIZE_STRING) );
        	$start = intval($start);
        	if($start<0) $start = 0;
        
        	$limit = intval($limit);
        	if($limit<1) $limit = 14;
        
        	$followerID = intval( $model->profileID );
        
        	//$model->title = 'Arama sonuçları : ' . $keyword . ' | ' . SITENAME;
        	$SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername,sharer.surname AS sharersurname, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
        	$FROM   = "\n FROM di";
        	$JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
        	$JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
			$JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";
        	$WHERE  = "\n WHERE   ";
        	//$WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
        	//$WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
        	//$WHERE .= "\n OR ( sharer.deputy>0)"; //millet vekilleri
        	//$WHERE .= "\n OR ( di.profileID<1000 ))"; //democratus profili
        	$WHERE .= "\n  (di.di  LIKE '%". $db->escape( $keyword )."%')";
        		//$WHERE .= "\n AND f.status>0";

        	
        	if($start>0){
        	$WHERE .= "\n AND di.ID<" . $db->quote($start);
        	}
        	
        	$WHERE .= "\n AND di.status>0";
        	
        	$ORDER  = "\n ORDER BY di.ID DESC";
        	$LIMIT  = "\n LIMIT $limit";
        	
        	$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
        	$rows = $db->loadObjectList();
        	$html = '';
        	if(count($rows)>0)
        	{ // aranan ses  bulundu
	        	foreach($rows as $row){
	        		if(!profile::isallowed($row->profileID, $row->showdies)) continue;
	        		if($row->deputy>0){
	        			$isdeputy = 'deputiydi';
	        			$deputyinfo = '<span>Vekil</span>';
	        			$deputyinfo = '<img title="vekil" src="/p/lib/icons/award_star_gold_2.png" style="width:16px;margin:0;padding:0;">';
	        		} else {
	        			$isdeputy = '';
	        			$deputyinfo = '';
	        		}
	        	
	        		if($row->redi>0)
	        			$redi = 'redi';
	        		else
	        			$redi = '';
	        	
	        		if($row->redi>0)
	        			$redier = ', <a href="/profile/'.$row->redi.'">'.$row->rediername.'</a> kaynağından alıntı yaptı ';
	        		else
	        			$redier = '';
	        		if($row->rediID>0)
	        			$genelID=$row->rediID;
	        		else
	        			$genelID=$row->ID;
	        		$likeinfo = di::getlikeinfo($genelID);
	        	
	        		if($row->profileID == $model->profileID)
	        			$delete = '<span class="x diCommentSikayet" title="kaldır / şikayet et"></span>';
	        		else
	        			$delete = '';
	        	
	        		$delete = '<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et"></span>';
	        	
	        		$dicomment_count =  di::getdicomment_count($genelID);
	        		if($dicomment_count>0)
	        			$dicomment_count = ' ('.$dicomment_count.') ';
	        		else
	        			$dicomment_count = '';
					if($row->initem=="1"){
						$initElement='&nbsp;&nbsp;<i class="icon-picture"></i>';
					}
					else{
						$initElement='';
					}
					
	        		 $html.=di::get_voiceHtmlNew($row,$deputyinfo,$redier,$genelID,$dicomment_count,$initElement,$likeinfo);
                   
	        	}
	        	$response['html'] = $html;
	        	$response['count'] = count($rows);
	        	$response['start'] = $row->ID;
        	}// aranan ses bulundu sonu	
        	else
        	{ //aranan ses Bulunamadı
        		
        		$html='<div class="roundedcontent" style="width:500px">
		    				Üzgünüz, aradığınız içeriği bulamadık.		
		    			</div>';
        		
        		$response['html'] = $html;
        		$response['count'] = 1;
        		$response['start'] = 0;
        	}//aranan ses bulunamadı
        	return $response; 
        }
        public function findArchive($keyword, $start=0, $limit=7){
        	global $model,$db;
        	$ocolors = array (
        			1 => '#88b131',
        			2 => 'progress-success',
        			3 => 'progress-warning',
        			4 => 'progress-danger',
        			5 => '#ff6f32'
        	);
        	$SELECT = "\n SELECT a.*, av.vote AS myvote, p.image AS deputyimage, p.name AS deputyname, p.surname AS deputysurname";
			$FROM = "\n FROM agenda AS a";
			$JOIN = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote ( $model->profileID );
			$JOIN .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
			$WHERE = "\n WHERE " . $db->quote ( date ( 'Y-m-d H:i:s' ) ) . " > a.endtime";
			$WHERE .= "\n AND a.status>0";
			$WHERE .= "\n AND a.title like '%".$keyword."%'";
			if ($start > 0)
				$WHERE .= "\n AND a.ID<" . intval ( $start );
			$GROUP = "\n ";
			$ORDER = "\n ORDER BY a.ID desc";
			$LIMIT = "\n LIMIT $limit";
			
			$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT );
        	
			$agendas = $db->loadObjectList ();
			$response ['count']=count($agendas); 
        	
        if (count ( $agendas )) {
				$i = 0;
				ob_start ();
				foreach ( $agendas as $agenda ) {
					
					$db->setQuery ( 'SELECT av.vote, COUNT(*) AS votecount FROM agendavote AS av WHERE av.agendaID=' . $db->quote ( $agenda->ID ) . ' GROUP BY av.vote ORDER BY av.vote' );
					$voted = $db->loadObjectList ( 'vote' );
					$totalvote = 0;
					$win = 0;
					$winoption = '';
					if (count ( $voted ))
						foreach ( $voted as $v ) {
							$totalvote += $v->votecount;
							if ($v->votecount > $win) {
								$win = $v->votecount;
								$winoption = $v->vote;
							}
						}
					
					$winpercent = floor ( ($win * 100) / $totalvote );
					
					$optioans = array (
							1 => 'Kesinlikle Katılıyorum',
							2 => 'Katılıyorum',
							3 => 'Kararsızım',
							4 => 'Katılmıyorum',
							5 => 'Kesinlikle Katılmıyorum' 
					);
					
					$statistic_left = '';
					$statistic_right = '';
					$statistic_Line = '';
					foreach ( config::$votetypes as $key => $option ) {
						
						// oy oranini hesapla
						if (array_key_exists ( $key, $voted ))
							$percent = floor ( ($voted [$key]->votecount * 100) / $totalvote );
						else
							$percent = 0;
						
						$checked = $agenda->myvote == $key ? '<i title="senin seçimin">*</i>' : '';
						
						$statistic_left .= '<div class="choose">' . $option . ' ' . $checked . '<span>%' . $percent . '</span></div>';
						
						$statistic_right .= '<div class="choose" style="width: ' . $percent . '%; background-color: ' . $ocolors [$key] . '">' . $percent . '%</div>';
						$statistic_Line .= '
							<tr>
								<td class="quest" style="width:70px;">
									<label class="checkbox" for="parliament_choose_' . $agenda->ID . '_' . $key . '">' . $option . '
									</label>
								</td>
								<td style="width:30px;">%' . $percent . '</td>
								<td class="bars"><div style="margin-bottom: 5px;" class="progress  ' . $ocolors [$key] . '"><div style="width: ' . $percent . '%" class="bar"></div></div></td>
							</tr>
						';
					}
					if ($model->newDesign) {
						$dicomment_count = di::getdicomment_count ( $agenda->diID );
						if ($dicomment_count > 0)
							$dicomment_count = ' (' . $dicomment_count . ') ';
						else
							$dicomment_count = '';
							// new design getarchive start
						?>
<div class="roundedcontent"
	onclick="statisticLineChange(<?=$agenda->ID?>);"
	style="background-color: #FDFBFB; cursor:pointer;">
	<div class="usrlist-other-pic">
		<img
			src="<?=$model->getProfileImage( $agenda->deputyimage, 67, 67, 'cutout' )?>">
	</div>
	<div class="usrlist-infowide">
		<table class="table-striped" style="width: 100%">
			<tbody>
				<tr>
					<th style="width: 150px;"><span><?=$agenda->deputyname." ".$agenda->deputysurname?></span></th>
					<th><a href="javascript:;" style="width: 90px;"><?=time_since(strtotime( $agenda->endtime))?> önce</a></th>
					<th><a href="/di/<?=$agenda->diID?>"><i class="icon-comment"></i> Söyleş <?=$dicomment_count?></a></th>
					<th><a href="javascript:redi(<?=$agenda->diID?>)"><i
							class="icon-share"></i> Paylaş</a></th>
				</tr>
				<tr>
					<td colspan="4">
						<p><?=$agenda->title?></p>
						<br />
						<p>
							<span>Sonuç: %<?php echo $winpercent?>  <strong><?php echo config::$votetypesss[$winoption]; ?></strong></span>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="statisticLine-<?=$agenda->ID?>" class="statisticLine-all"
		style="display: none;">
		<hr class="rounded_hr">
		<div style="clear: both;"></div>
		<table class="polltable" style="margin-left: 20px; width: 95%;">
								<?=$statistic_Line?>
							</table>
	</div>
</div>
<p></p>
<?php
					} 					// new design getarchive end
					else { // old design getarchive start
						?>
<!-- POST -->
<div class="post">

	<!-- Post Head [Begin] -->
	<div class="head">
		<div class="image">
			<img
				src="<?=$model->getProfileImage( $agenda->deputyimage, 40, 40, 'cutout' )?>" />
		</div>
		<div class="content">
			<div class="top">
				<span class="name"><?=$agenda->deputyname?></span> <span
					class="date"><?=time_since(strtotime( $agenda->endtime))?> önce</span>
                                                <?php if(0){?>
                                                <span class="post_right">
					<span class="comment">&nbsp;</span> <span class="share">&nbsp;</span>
				</span>
                                                <?php } ?>
                                            </div>
			<div class="line_center"></div>
			<div class="bottom">
				<p>
					<strong><?=$agenda->title?></strong><br /><?=$agenda->spot?></p>
				<span>Sonuç: %<?php echo $winpercent?> oy oranı ile <strong><?php echo config::$votetypesss[$winoption]; ?></strong></span>
			</div>
		</div>
	</div>
	<!-- Post Head [End] -->


	<div class="clear"></div>
	<div class="line_center"></div>

	<!-- Post Statistic [Begin] -->
	<div class="statistic">

		<div class="left"><?php echo $statistic_left;?></div>

		<div class="vertical_line"></div>

		<div class="right"><?php echo $statistic_right;?></div>

		<div class="clear"></div>

	</div>
	<div class="clear"></div>
                                    <?php if(0){?><div class="result">Sonuç: %<?php echo $percent?> oy oranı ile <strong><?php echo config::$votetypesss[$winoption]; ?></strong>
	</div><?php } ?>
                                    <div class="clear"></div>
	<!-- Post Statistic [End] -->
</div>
<!-- POST -->






<?php
					
} // old design getarchive end
				
				}
				
				$response ['html'] = '<div id="archive' . $start . '">' . ob_get_contents () . '</div>';
				ob_end_clean ();
				$response ['nextstart'] = $agenda->ID;
			
			} else {
				// not found
				$response ['html'] = '';
				$response ['count'] = 0;
			
			}
        	return $response;
        }
    }
?>
