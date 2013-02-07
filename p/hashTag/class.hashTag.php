<?php
    class hashTag_plugin extends control{//HashTag Sayfası
        public $limit = 14;
        public function main(){
			global $model;
			$model->template="ala";
			$model->view="hashTag";
			$model->title = 'Democratus';

			$model->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery.tmpl.js", "jquery.tmpl.js", 1);
			
			$model->addScript(TEMPLATEURL."ala/js/howtouse.js", "howtouse.js", 1);
			$model->addScript(TEMPLATEURL."ala/js/jquery.scrollTo.min.js", "jquery.scrollTo.min.js", 1);
				
			$model->addScript(PLUGINURL . 'lib/fineuploader/jquery.fineuploader-3.0.js', 'fileuploader-3.0.js', 1 );
			//$model->addStyle(PLUGINURL . 'lib/fineuploader/fileuploader.css', 'fileuploader.css', 1 );
			
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='hashTag'");
			$userPerma	= $model->paths[0];
			$c_profile 	= new profile($userPerma);
			$model->addScript('profileID='.$c_profile->profile->ID.'; profilePerma="'.$userPerma.'";');
			$model->addScript('onlyProfile=1;');
			
			
			if($model->paths[1]=="startTour")
			{
				
				$model->addScript("$(document).ready(function (){ show_step(0)});");
			}
		}
		public function main_old(){
            global $model, $db,$dbez, $l;
            
            if($model->paths[1] == 'ajax') return $this->ajax();
            $model->initTemplate('beta', 'default');
            //$limit = 2;
            
            $keyword = filter_var($model->paths[1], FILTER_SANITIZE_STRING);
            $keyword2 = filter_input(INPUT_GET,'q', FILTER_SANITIZE_STRING);
        
            if( strlen($keyword2) ) $keyword = $keyword2;
            $model->addScript("paths=".json_encode($model->paths));
            
			
            $SELECT	=  "Select * ";
			$FROM	=  "From profile ";
			$WHERE	=  "Where permalink like ".$db->quote($keyword)." and status='1' and type='hashTag'";
			$db->setQuery( $SELECT . $FROM . $WHERE );
			
			if(!$db->loadObject($tag))
			{
				
			?>
				<script>
					location.href="/search/<?=$keyword?>";
				</script>
			<?php
			
				die;			
			}
			$c_tag=new tag($tag);
			$isAdmin=FALSE;
            $check_admin=$dbez->get_var("SELECT count(*) FROM hashProfileRelation WHERE profileID='".$model->profileID."' and hashtagID='".$tag->ID."'  ");
            if($check_admin>0)
			{
				$isAdmin=TRUE;
			}
            //$range = filter_var($model->paths[1], FILTER_SANITIZE_STRING);
            
            $followerID = intval( $model->profileID );            

            	$model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
	            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
				$model->addScript($model->pluginurl . 't.js', 't.js', 1);
	            //$model->addScript(PLUGINURL . 'lib/democratus.js', 'democratus.js', 1);
	            //$model->addScript($model->pluginurl . 'search.js', 'search.js', 1);
	            $model->addScript('hasTag="#'.$keyword.'";');
				$model->addScript('
						$(document).ready(function(){
								var dataS={uploadType:"hasTag",imageID:"1",hastag:paths[1]}
								createDinamicUploader("hastagImageUpload",dataS);
							}
						);
				');
				
				
            // profil sayfasından eklendi
             	$SELECT = "SELECT p.*, f.followerstatus, f.followingstatus";
	            $SELECT.= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
	            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
	            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
	            $FROM   = "\n FROM profile AS p";
	            $JOIN   = "\n LEFT JOIN follow AS f ON f.followingID = p.ID AND f.followerID=" .$db->quote($model->profileID);
	            $WHERE  = "\n WHERE p.ID=".$db->quote($tag->ID); 
	            $WHERE .= "\n AND p.status>0";
	            $ORDER  = "\n ";
	            $LIMIT  = "\n LIMIT 1";
	            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
	          
	            
	            //$db->setQuery('SELECT p.*, u.email FROM profile AS p LEFT JOIN user AS u ON u.profileID = p.ID WHERE p.ID=' . $profileID );
	            if($db->loadObject($profile)) {
				if(!profile::isallowed($profile->ID, $profile->showprofile )){                
	                echo '<h3>Profil gizlilik ayarları nedeniyle görüntülenemiyor!</h3>';
	                return;
	            }
	            
            	$SELECT = "SELECT DISTINCT f.followingID, p.*";
	            $FROM   = "\n FROM #__follow AS f";
	            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followerID";
	            $WHERE  = "\n WHERE f.followingID=".$db->quote($tag->ID);
	            $WHERE .= "\n AND f.status>0";
	            $ORDER  = "\n ORDER BY f.datetime DESC";
	            $LIMIT  = "\n LIMIT 5";
	            
	          
	            
	            $SELECT = "SELECT f.followerID, p.*";
	            $SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
	            
	            $FROM   = "\n FROM #__follow AS f";
	            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followingID";
	            $WHERE  = "\n WHERE f.followerID=".$db->quote($tag->ID);
	            $WHERE .= "\n AND f.status>0";
	            $ORDER  = "\n ORDER BY f.datetime DESC";
	            $LIMIT  = "\n LIMIT 5";
	            
	            
	           
	            if($profile->followingstatus>0){
                    $follow = 'hide';
                    $unfollow = '';
                } else {
                    $follow = '';
                    $unfollow = 'hide';
                }
                
	            if($tag->ID==$model->profileID){
                    $follow_button = '<button class="btn btn-vekil">SENSİN</button>';
                } else {
                    $follow_button = '<button id="follow'.$profile->ID.'"  class="btn btn-vekil follow '.$follow.'" rel="'.$profile->ID.'">Takip Et</button>
                    				  <button id="unfollow'.$profile->ID.'"  class="btn btn-takipetme unfollow '.$unfollow.'" rel="'.$profile->ID.'">Takip Etme</button>
                                      ';
                }
                
            ?>
            	<meta property="og:image" content="<?=$model->getProfileImage($profile->image, 300,400, 'scale')?>" />
            	<style>
					.dilike{
						margin-left:15px;
						cursor:pointer;
						color:#962b2b;
					}
					.dilike:hover{color:#d83a3a}
					.share{margin-left:20px;}
				</style>
            	<div class="roundedcontent">
					<div class="usrlist-pic"><a href="<?=$model->getProfileImage($profile->image, 300,400, 'scale')?>" class="fnc"><img src="<?=$model->getProfileImage($profile->image, 67,67, 'cutout')?>"></a></div>
					<div class="usrlist-info">
						<table class="table-striped" style="width: 100%">
							<tbody><tr>
								<th><span><a href="/t/<?=$tag->permalink?>">#<?=$tag->permalink?></a></span></span></th>
								<th><a href="javascript:;"><?=$profile->di_count?> Ses</a></th>
								<th><a href="javascript:;"><?=$profile->dilike1_count?> Takdir</a></th>
								<th><a href="javascript:;"><?=$profile->dilike2_count?> Saygı</a></th>
							</tr>
							<tr id="htMotto-static">
								<td colspan="5"><p><?=$profile->motto?></p></td>
							</tr>
						
						</tbody>
						</table>
					</div>
					<div class="usrlist-set">
						<ul>
							<li id="htFollowBtn"><?=$follow_button?></li>
							<?if($isAdmin){?>
							<li id="htSaveBtn" style="display:none;">
								<button onclick="htSave();" class="btn btn-vekil">Kaydet</button>
							</li>
							<!--<a class="" data-original-title="İçeriğinde sizden bahsedilen sesler." href="#tab-ayarlar" rel="ayarlar" data-toggle="tab">ayarlar</a>-->
							<?}?>
							<li></li>
							
							<?php 
							if($isAdmin)
							{
								echo '<li>
										<strong>
											<a id="htEditNo" data-original-title="İçeriğinde sizden bahsedilen sesler." href="#tab-ayarlar" rel="ayarlar" data-toggle="tab"> Düzenle</a>
										</strong>
									</li>';
								echo '<li>
										<div id="hastagImageUpload">		
											<noscript>			
												<p>Resim Yükleyebilmek için Javascript lerin aktif olması gerekli.</p>
												<!-- or put a simple form for upload here -->
											</noscript>         
										</div> 
									</li>';
										
					
					
							}
							if($profile->ID != $model->profileID){
							       echo '
							       	<li>
							       	<strong>
							       	<a href="javascript:;" id="profilecomplaint" rel="'.$profile->ID.'" style="text-decoration:none;color:#584C43"> Şikayet Et ! </a>
							       	</strong>
							       </li>';
							   }
							   ?>
						</ul>
					</div>
					<div style="clear:both;"></div>	
					<?php 
						$r=$this->getTagImage($tag);
						if($r)
						{
							echo '<div id="image-Content">';
								echo $r["html"];
							echo "</div>";
						}
					?>
					<div style="clear:both;"></div>	
              	</div>
              	<? }// profil sayfasından eklendi sonu ?>
              	
				
				
				
			<div id="myTabContent" class="tabuser-content">
				<div class="tab-pane fade in" id="tab-ayarlar">
					<p></p>
					<div class="roundedcontent" style="width: 500px;">
						<!-- -------------------- -->
							<h1>
								<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> Ayarlar 
								<span><button onclick="htSave();" class="btn btn-danger" id="myprofilesave" type="button">Kaydet</button></span>
							</h1>
							<div class="" id="myprofileResponse" style="display:none;"></div>
				            <div class="form-left">
					            <p>İstenen alanları doldurun.</p>
					            <!--<input type="text" class="input-large" name="name" placeholder="Tag Name" id="name" value="<?=$profile->name?>" style="width: 100%;" />-->
					            <textarea class="input-large" placeholder="Profil Bio" name="motto" id="motto-txt" maxlength="200" rows="3" style="width: 100%;" ><?=$tag->motto?></textarea>
				            </div>
				            
				            <div class="form-right">
					            <p>Profil fotografı yükleyin.</p>
					            <form name="form" action="" method="POST" enctype="multipart/form-data">
					            	<input class="input-file" id="imageFileinput" name="imageFileinput" title="YÜKLE" type="file" style="display: none;"/>
					            </form>
					            <div class="roundedcontentwhite" style="width:215px;"> 
						            <div id="imageSliderContent" >
							            <ul id="imageSliderUl"  style="list-style: none; margin:0;">
							            	<li id="imageUploadLi">
								            	<div style="float: left; width: 100%;">
								            		
									            	<div class="inputpics"  style="cursor:pointer; margin-top:0;" onclick="$('#imageFileinput').show().focus().click().hide();">
											          <div style="clear:both;"></div>
											           <div style="margin-top:45px; color:#fff;">YÜKLE</div>
										            </div>
									           	</div>
							            	</li>
							            </ul>
						            </div>
					            </div>
				            </div>
				            <input type="hidden" name="profileID" value="<?=$profileID?>" />
						<!-- -------------------- -->
					</div>
					<p></p>
					<div class="roundedcontent" style="width: 500px;">
						<h1>
							<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png" /> <?=$tag->name?> Gündemleri 
						</h1>
						<textarea id="newtagProposal" style="width:385px; float: left; margin-right: 15px;"></textarea>
						<span style="float: left;"><button onclick="add_tagProposal();" class="btn btn-danger" id="myprofilesave" type="button">Meclise Ekle</button></span>
						<div style="clear:both"></div>
						<?php 
							$agendas=$c_tag->get_tagAgenda();
							$i=1;
							foreach($agendas as $a)
							{
								echo '<p id="agendaAd-'.$a->ID.'">';
									echo $i.'. '.$a->title;
									echo "<br />";
									echo '<a href="javascript:;" class="removeAgenda" rel="'.$a->ID.'" >Meclisten Kaldır</a>';
								echo '</p>';
								$i++;
							}
						?>
					</div>
					
				</div>
		    	<div class="tab-pane fade in active" id="tab-sesler">
		    		<p></p>
              		{{shareditextbox}}
              		<p></p>
		    		<?php
						$promote=$this->getPromotedVoice($tag);
						echo ($promote["html"]);
					?>
	        		<p></p>
		    		<?php 
			            $found = $this->findVoice("#".$keyword);
			            if($found['count']>0){
			                echo $found['html'];
			                ?>
			                <p></p>
		    				<button rel="<?=$found["start"]?>" id="wallmoreHashT" class="btn100 tabbtn">DAHA FAZLA SES YÜKLE</button>
			            	<?
			            } else {
			            	echo "Üzgünüz, aradığınız içeriği bulamadık.";
			            }
			         ?>
		    		
		    	</div>
		    	<?php /*
		    	<div class="tab-pane fade in " id="tab-kisiler">
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
									<img src="/t/beta/img/democratus_icon.png"> 
									Bu Modül Yapım Aşamasındadır.
								</h1>
								<div class="clear"></div>
								<p>Bu modül henüz hazır değil. Kullanıcılarımıza kusursuz bir deneyim sunmak için test etmeye devam ediyoruz.</p>
							</div>
		    	</div>
		   
				 * 
				 */?> 
			</div>
            <?php 

            
        }
        public function getTagImage($tag)
		{
			global $model,$db;
			$response=false;
			$SELECT	=  "SELECT * ";
			$FROM	=  "FROM tagimage ";
			$WHERE	=  "WHERE tagID=".$db->quote($tag->ID)." and status='1' ";
			$ORDER	=  "ORDER BY ID DESC ";
			$LIMIT	=  "LIMIT 6";
			$db->setQuery($SELECT.$FROM.$WHERE.$ORDER.$LIMIT);
			$images = $db->loadObjectList();
			if(count($images)>0)
			{
				$response["status"]="success";
				$response["html"]='<hr />
					
				<div class="hashImageKabuk">
					<ul class="hashImageList">';
					foreach($images as $i)
					{
						$response["html"].='<li><a href="'.$model->getProfileImage($i->image, 600,400, 'scale').'" class="fnc" rel="fGrup" target="_blank"><img src="'.$model->getProfileImage($i->image, 72,72, 'cutout').'"></a></li>';
					}
				$response["html"].='	</ul>
				</div>';
				return $response;
			}
			else
			{
				$response=false;
				return $response;
			}
	
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
        public function ajax_get_onlyPhotoHtml()
		{
			global $model,$db;
			$hasTag=$_REQUEST["hastag"];
			$SELECT	=  "Select * ";
			$FROM	=  "From profile ";
			$WHERE	=  "Where permalink like ".$db->quote($hasTag)." and status='1' and type='hashTag'";
			$db->setQuery( $SELECT . $FROM . $WHERE );
			
			if($db->loadObject($tag)){
				$sonuc=$this->getTagImage($tag);
				$response["success"]="success";
				$response["tagImage"]=$sonuc;
			}
			echo json_encode($response);
		}
        public function ajax_changeImage()
        {
        	global $model, $dbez;
			//kullanıcı yetkisi varmı kontrol et
		
			$hastagID=$dbez->get_var("SELECT ID FROM profile WHERE permalink='".$_REQUEST["hastag"]."'");
			//echo ("insert into tagimage set image='".$_REQUEST["uploadDir"]."/".$_REQUEST["imageName"]."' , tagID='".$hastagID."' , status='1' ");
			
			$guncellendi=$dbez->query("insert into tagimage set image='".$_REQUEST["uploadDir"]."/".$_REQUEST["imageName"]."' , tagID='".$hastagID."' , status='1' ");
			if($guncellendi)
			{
				$response["success"]="success";
			}
			else
			{
				$response["error"]="error";
			}
			echo json_encode($response);
        }
        public function ajax_changeProfile()
		{
			global $model,$dbez;
			$response=array();
			$permaLink=filter_input(INPUT_POST, 'permaLink', FILTER_SANITIZE_STRING);
			$motto=filter_input(INPUT_POST, 'motto', FILTER_SANITIZE_STRING);
			$update=$dbez->query("update profile set motto='".$motto."' WHERE permalink='".$permaLink."' and type='hashTag' ");
			if($update)
			{
				$response["status"]="success";
			}
			else {
				$response["status"]="error";
				$response["error"]="Kayıt başarılı olmadı tekrar  deneyiniz";
			}
			echo json_encode($response);
		}
		public function ajax_add_tagProposal()
		{
			global $model,$dbez;
			
			$response=array();
			$permaLink=filter_input(INPUT_POST, 'permaLink', FILTER_SANITIZE_STRING);
			$proposalText=filter_input(INPUT_POST, 'proposalText', FILTER_SANITIZE_STRING);
			
			$c_hashtag=new tag;
			$is_add=$c_hashtag->add_tagProposal($permaLink, $proposalText);
			echo $is_add;
		}
		public function ajax_remove_tagAgenda()
		{
			global $model,$dbez;
			$response=array();
			$permaLink=filter_input(INPUT_POST, 'permaLink', FILTER_SANITIZE_STRING);
			$ID=filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_STRING);
			
			$c_hashtag=new tag();
			$is_remove=$c_hashtag->remove_tagProposal($permaLink, $ID);
			if($is_remove==1)
			{
				$response["status"]="success";
				$response["agendaID"]=$ID;
			}
			else if($is_remove)
			{
				$response["status"]="error";
				$response["errormsg"]="Bunun işlem için yetkiniz yok.";
			}
			echo json_encode($response);
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
            
            $model->title = '' . $keyword . ' | ' . SITENAME;
            
            
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
                    
                    $html .= '
                              <div class="result" id="profile'.$row->ID.'">
                                <div class="image"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'" style="width: 50px" /></div>
                                <div class="content">
                                    <div class="head">
                                        <span class="username"><a href="/profile/'.$row->ID.'">'.$row->name.'</a></span>
                                        '.$follow_button.'
                                    </div>
                                    <p>'.$model->splitword( $row->motto, 48 ).'</p>
                                    <span class="mini_about">'.$row->hometown.'</span>
                                    
                                    <span class="statistic">
                                            <span>'.$row->di_count.' Ses</span>
                                            <span>'.$row->dilike1_count.' Takdir</span>
                                            <span>'.$row->dilike2_count.' Saygı</span>
                                            
                                        </span>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                            ';
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
                
                $html.= '</div>';
                
                //die('--------------'.$html.'--------------');
                if($model->newDesign)
                $response['html'] = $htmlNew;//.'<input type="button" id="searchmore" value="more" rel="'.$start + $i.'" />';
                else
                $response['html'] = $html;
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
		public function getPromotedVoice($tag)
		{
			
			global $model, $db, $l;

        	//$model->title = 'Arama sonuçları : ' . $keyword . ' | ' . SITENAME;
        	$SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
        	$FROM   = "\n FROM di";
        	$JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
        	$JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
			$JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";
        	$WHERE  = "\n WHERE   ";
        	//$WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
        	//$WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
        	//$WHERE .= "\n OR ( sharer.deputy>0)"; //millet vekilleri
        	//$WHERE .= "\n OR ( di.profileID<1000 ))"; //democratus profili
        	$WHERE .= "\n  (di.profileID=".$db->quote($tag->ID).") ";
        	$WHERE .= "\n AND di.status>0";
        	
        	$ORDER  = "\n ORDER BY di.ID DESC";
        	$LIMIT  = "\n LIMIT 1";
        	
        	$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
			
        	//$rows = $db->loadObjectList();
        	
			if($db->loadObject($row))
			{
				$html = '';
				
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
					
	        		$html='
        			<div class="roundedcontentsub" id="voice-'.$row->ID.'" onclick="voiceDetail('.$row->ID.');" style="background-color: #fff5f5;">
        			<div class="usrlist-other-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
        					<div class="usrlist-other-info">
        					<table class="table-striped" style="width:100%;">
        					<tbody><tr>
        					<th><a href="/profile/'.$row->profileID.'"><span>'.$deputyinfo.$row->sharername.'</span></a>
        							<span>'.$redier.'</span> <i>'.time_since( strtotime( $row->datetime )).'</i>
        							<div class="btn-group dropup usrlist-other-cnfgr">
        							<!--<span class="x" data-toggle="dropdown" style="float:right;"></span>-->
        							
        							 
        							</div></th>
        							</tr>
        							<tr>
        							<td><p>'.di::hashTag(make_clickable( $model->splitword(  $row->di , 48) )).'</p></td>
        							</tr>
        							<tr>
        							<td>
        							<span class="comment"><a href="/di/'.$genelID.'"><a href="/di/'.$genelID.'"> <i class="icon-comment"></i>  Söyleş'.$dicomment_count.'</a></span>
        							<span class="share"><a href="javascript:redi('.$genelID.')"><i class="icon-share"></i> Paylaş</a></span>
        							<span id="dilikeinfo'.$genelID.'" style="float:right;"> '.$likeinfo['html'].' </span>
        							'.$initElement.'
        							</td>
        							</tr>
        							</tbody></table>
        							</div>
        							<div style="clear:both;"></div>
        							<div id="di_subArea-'.$row->ID.'" style="display:none;">
        								<input type="hidden" id="openStatus-'.$row->ID.'" name="openStatus-'.$row->ID.'" value="0" />
        								<input type="hidden" id="initem-'.$row->ID.'" name="initem-'.$row->ID.'" value="'.$row->initem.'" />
        								<input type="hidden" id="itemLoaded-'.$row->ID.'" name="itemLoaded-'.$row->ID.'" value="0" />
        								<hr />
        								<div id="di_subAreaConten-'.$row->ID.'"></div>
        							</div>
        							</div>
        							';
                   
			}else
			{
				$html=""; 
			}
			$response['html'] = $html;
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
<div class="roundedcontent"	onclick="statisticLineChange(<?=$agenda->ID?>);"
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
