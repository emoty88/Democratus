<?php
    class profile_plugin extends control{
        public function main(){
			global $model, $db, $l;
			
			$model->template="ala";
			$model->view="profile";
			$model->title = 'Democratus';
			
			$model->addScript(TEMPLATEURL."ala/js/modernizr-2.6.2.min.js", "modernizr-2.6.2.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery-1.8.3.min.js", "jquery-1.8.3.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery-ui-1.9.1.custom.min.js", "jquery-ui-1.9.1.custom.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/jquery.caroufredsel.js", "jquery.caroufredsel.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/bootstrap.min.js", "bootstrap.min.js", 1);
	        $model->addScript(TEMPLATEURL."ala/js/app.js", "app.js", 1);
	        $model->addScript("http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js", "jquery.tmpl.js", 1);
			
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='profile'");
			$userPerma	= $model->paths[0];
			$c_profile 	= new profile($userPerma);
			$model->addScript('profileID='.$c_profile->profile->ID.';');
			$model->addScript('onlyProfile=1;');
			//$model->addScript('');
		}
        public function main_old(){
            global $model, $db, $l;
            //$model->newDesign=false;
            $loggedin = $model->userID>0;
            if($model->newDesign)
            	$model->initTemplate('beta','profile');
            else 
            	$model->initTemplate('v2','profile');
			
			if($model->urlsizProfile)
			{
				$profileID=$model->urlsizProfileID;
			}
			else { 
				if(is_null($model->page->ID2))
	                $profileID = intval($model->paths[1]);
	            else
	                $profileID = intval($model->page->ID2);
				
					//header("location: /".$model->profile->permalink);
					$db->setQuery('SELECT permalink,type FROM profile WHERE ID=' . $profileID );
					
		            if($db->loadObject($permalinkP)) {
		            	if($permalinkP->type=="hashTag")
						{
							header("location: /t/".$permalinkP->permalink);
						}
		            	if($permalinkP->permalink!=""){
		            		header("location: /".$permalinkP->permalink);
		            	}
		            }
				
			}
			//Hashtag sayfalarını yönlendir
			$db->setQuery('SELECT permalink,type FROM profile WHERE ID=' . $profileID );
					
		    if($db->loadObject($permalinkP)) {
		    	if($permalinkP->type=="hashTag")
				{
					header("location: /t/".$permalinkP->permalink);
				}
		    }
			//hashtag sayfalarını yönlendir son
            if($model->newDesign)
            { 
	            $model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
	            //$model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
	            //$model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
	            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
	            
	            
	            
	             
	            $model->addScript($model->pluginurl . 'profile.js', 'profile.js', 1);   
	            $model->addScript("paths=".json_encode($model->paths));
	            
	            $SELECT = "SELECT p.*, f.followerstatus, f.followingstatus";
	            $SELECT.= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
	            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
	            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
	            $FROM   = "\n FROM profile AS p";
	            $JOIN   = "\n LEFT JOIN follow AS f ON f.followingID = p.ID AND f.followerID=" .$db->quote($model->profileID);
	            $WHERE  = "\n WHERE p.ID=".$db->quote($profileID);
	            $WHERE .= "\n AND p.status>0";
	            $ORDER  = "\n ";
	            $LIMIT  = "\n LIMIT 1";
	            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
	          
	            
	            //$db->setQuery('SELECT p.*, u.email FROM profile AS p LEFT JOIN user AS u ON u.profileID = p.ID WHERE p.ID=' . $profileID );
	            if($db->loadObject($profile)) {
	           		
	           	$model->title = $profile->name;
                $model->description = $profile->motto;
	            $profileClass=new profile($profile);
				$model->addScript("var profileID=".$profile->ID);
				if(!profile::isallowed($profile->ID, $profile->showprofile )){                
	                echo '<h3>Profil gizlilik ayarları nedeniyle görüntülenemiyor!</h3>';
	                return;
	            }
	              
	            
	            $SELECT = "SELECT DISTINCT f.followingID, p.*";
	            $FROM   = "\n FROM #__follow AS f";
	            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followerID";
	            $WHERE  = "\n WHERE f.followingID=".$db->quote($profileID);
	            $WHERE .= "\n AND f.status>0";
	            $ORDER  = "\n ORDER BY f.datetime DESC";
	            $LIMIT  = "\n LIMIT 5";
	            
	            $db->setQuery('SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
	            $countTakipci = intval( $db->loadResult() );
	            
	            $SELECT = "SELECT f.followerID, p.*";
	            $SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
	            
	            $FROM   = "\n FROM #__follow AS f";
	            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followingID";
	            $WHERE  = "\n WHERE f.followerID=".$db->quote($profileID);
	            $WHERE .= "\n AND f.status>0";
	            $ORDER  = "\n ORDER BY f.datetime DESC";
	            $LIMIT  = "\n LIMIT 5";
	            
	            
	            $db->setQuery('SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
	            $countTakipedilen = intval( $db->loadResult() );
	            if($profile->followingstatus>0){
                    $follow = 'hide';
                    $unfollow = '';
                } else {
                    $follow = '';
                    $unfollow = 'hide';
                }
                
	            if($profile->ID==$model->profileID){
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
					<div class="usrlist-pic">
						<a href="<?=$model->getProfileImage($profile->image, 500,700, 'scale')?>" class="fnc">
							<img src="<?=$model->getProfileImage($profile->image, 67,67, 'cutout')?>">
						</a>
						<p>
						<?php 
							//$profilePuan = new puan;
							//var_dump($profilePuan->puanIslem($profile->ID,"100","+","asd","ads"));
							echo "<!--PUAN = ".$profile->puan."-->";
						?></p>
					</div>
					<div class="usrlist-info">
						<table class="table-striped" style="width: 100%">
							<tbody><tr>
								<th><span><?=$profile->name." ".$profile->surname?></span></th>
								<th><a href="#"><?=$profile->di_count?> Ses</a></th>
								<th><a href="#"><?=$profile->dilike1_count?> Takdir</a></th>
								<th><a href="#"><?=$profile->dilike2_count?> Saygı</a></th>
							</tr>
							<tr>
								<td colspan="5"><p><?=$profile->motto?></p></td>
							</tr>
							<tr>
								<td colspan="5">
									<?php
									
										
										$tags=$profileClass->get_hastagInterest($profile);
										if(count($tags)>0)
										{
											echo "<b>İlgi Alanları: </b>";
											$i=0;
											foreach($tags as $t)
											{
												if($i!=0)
												echo " - ";
												echo '<a href="/'.$t->permalink.'">#'.$t->permalink.'</a>';
												$i++;
											}
											
										}
									
													?>
								</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="usrlist-set">
						<ul>
							<li><?=$follow_button?></li>
							<li><strong><th><?=$profile->puan?></strong> Puan</li>
							<li><strong><?=$countTakipedilen?></strong> Takip Ettiği</li>
							<li><strong><?=$countTakipci?></strong> Takipçi</li>
							<?php 
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
              	</div>
              	<p></p>
            	<ul class="nav nav-tabs" id="tab" style="margin-bottom: 0;">
					<li class="active">
						<button data-toggle="tab" href="#tab-sesleri" rel="sesleri" class="tabbtn">Sesleri</button>
					</li>
		            <li>
		            	<button data-toggle="tab" href="#tab-takipettikleri" rel="takipettikleri" class="tabbtn">Takip Ettikleri</button>
		            </li>
					<li>
						<button data-toggle="tab" href="#tab-takipcileri" rel="takipcileri" class="tabbtn">Takipçileri</button>
					</li>
					<li>
						<button data-toggle="tab" href="#tab-bilgileri" rel="bilgileri" class="tabbtn last">Bilgileri</button>
					</li>
	          	</ul>
	          	<p></p>
	          	<div id="myTabContent" class="tabuser-content">
            		<div class="tab-pane fade in active" id="tab-sesleri">
	            		<div id="wallcontainerfollow"> 
	            		<?php 
	            			$result= di::getdies($profileID,0,7,"all",1); 
			                if($result['count']>0){
						        echo $result['html'];
						        echo '<input type="hidden" name="wallstartfollow" value="'.$result['start'].'" />';
						    } else {
						        echo $result['html'];
						    }
	            		?>
	            		</div>
	            		<p></p>
	            		<input type="hidden" id="wallstartfollow" value="<?=$result['start']?>" />
	            		<button class="btn100 tabbtn" id="wallmorefollow" rel="<?=$profileID?>" >DAHA FAZLA SES YÜKLE</button>
	            		<p></p>
            		</div>
            		<div class="tab-pane fade in" id="tab-takipettikleri">
	            	<?php 
		            	
		            	//takip ettikleri buraya eklensin get_following
			            
			            //acilen daha fazla  seçeneği eklenmeli
			            
			           $rows=$profileClass->get_following(20,0);
					  
			            //get html
			            echo '<div id="followingContent">';
			            echo $profileClass->get_porfileMiniHtml($rows); 
						echo '</div>';
			            $model->addscript("loadFollowing=1;");
			            echo '<p></p><button id="get_moreFollowing" class="btn100 tabbtn" rel="">DAHA FAZLA </button>';
	            	?>
            		</div>
            		<div class="tab-pane fade in" id="tab-takipcileri">
            			<?php 
		            	

			            
			           
			            $rows = $profileClass->get_follower(20,0);
						echo '<div id="followingContent">';
			            echo $profileClass->get_porfileMiniHtml($rows); 
						echo '</div>';
						$model->addscript("loadFollower=1;");
			            echo '<p></p><button id="get_moreFollower" class="btn100 tabbtn" rel="">DAHA FAZLA </button>';
	            	?>
            		</div>
            		<div class="tab-pane fade in " id="tab-bilgileri">
            			<p></p>
	            		<div class="roundedcontent" style="width:95%;">
	            		<?php
						if(profile::isallowed($profile->ID, $profile->showbirth )){                
						?>                                    	
							<p>Doğum Tarihi :
								<?php 
								  $birthdate = strtotime( $profile->birth );
								  $day = intval( date('d', $birthdate) );
								  $month = intval( date('m', $birthdate) );
								  
								  echo $day . ' ' . $l['months'][$month];
								?>
							</p>
						<?php  
						}
						?>
						<?php
						if(profile::isallowed($profile->ID, $profile->showeducation )){
							$edu->ID="1";
				            $edu->edu="İlkokul";  
				            $items[1]=$edu;
				            $edu=null;
				            $edu->ID="2";
				            $edu->edu="Lise";  
				            $items[2]=$edu;
				            $edu=null;
				            $edu->ID="3";
				            $edu->edu="Önlisans";  
				            $items[3]=$edu;
				            $edu=null;
				            $edu->ID="4";
				            $edu->edu="Üniversite";  
				            $items[4]=$edu;
				            $edu=null;
				            $edu->ID="5";
				            $edu->edu="Master";  
				            $items[5]=$edu;
				            $edu=null;
				            $edu->ID="6";
				            $edu->edu="Doktor";  
				            $items[6]=$edu;
				            $edu=null;
				            $edu->ID="7";
				            $edu->edu="Profesör";  
				            $items[7]=$edu;
				            $edu=null;
						?> 
							<p>Eğitim Durumu : <?=$items[$profile->education]->edu?></p>
						<?php
						}
						?>
						<?php
						if(profile::isallowed($profile->ID, $profile->showhometown )){                
						?>                                        
							<p>Memleketi : <?=$profile->hometown?></p>
						<?php
						}
						?>
						<?php
						if(profile::isallowed($profile->ID, $profile->showlanguages )){                
							
						?>
							<p>Dil : <?=$profile->languages?></p>
						<?php
						}
						?>                                                       
						</div>
            		</div>
            	</div>
            <?php 
	            }
	            else 
	            {
	            	echo "Profile not found";
	            }
            }
            else
            {//old design start
            	
            
                
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            
            $model->addScript(PLUGINURL . 'lib/fancybox/jquery.fancybox-1.3.4.pack.js', 'fancybox.js', 1 );
            $model->addScript('$(function(){$("a[rel=fancybox]").fancybox();});');
            $model->addStyle(PLUGINURL . 'lib/fancybox/jquery.fancybox-1.3.4.css', 'fancybox.css', 1 );
            
             
            $model->addScript($model->pluginurl . 'profile.js', 'profile.js', 1);    
            
            /*
            $SELECT = "SELECT DISTINCT f.followerID, p.*, f1.followerstatus, f1.followingstatus";
            $SELECT.= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
            $FROM   = "\n FROM follow AS f";
            $JOIN   = "\n JOIN profile AS p ON p.ID=f.followerID";
            $JOIN  .= "\n LEFT JOIN follow AS f1 ON f1.followingID=p.ID AND f1.followerID=".intval( $model->profileID );
            $WHERE  = "\n WHERE f.followingID=".$db->quote($myID);
            $WHERE .= "\n AND f.followingstatus>0";
            $WHERE .= "\n AND p.status>0";
            $ORDER  = "\n ORDER BY p.ID DESC";
            //$LIMIT  = "\n LIMIT $start, $limit";
            $LIMIT  = "\n ";
            */
            
            
            $SELECT = "SELECT p.*, f.followerstatus, f.followingstatus";
            $SELECT.= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
            $SELECT.= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
            $FROM   = "\n FROM profile AS p";
            $JOIN   = "\n LEFT JOIN follow AS f ON f.followingID = p.ID AND f.followerID=" .$db->quote($model->profileID);
            $WHERE  = "\n WHERE p.ID=".$db->quote($profileID);
            $WHERE .= "\n AND p.status>0";
            $ORDER  = "\n ";
            $LIMIT  = "\n LIMIT 1";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            
            
            
            
            
            
            
            //$db->setQuery('SELECT p.*, u.email FROM profile AS p LEFT JOIN user AS u ON u.profileID = p.ID WHERE p.ID=' . $profileID );
            if($db->loadObject($profile)) {
            	var_dump($profile);
            	die; 
                $model->title = $profile->name;
                $model->description = $profile->motto;

            if(!profile::isallowed($profile->ID, $profile->showprofile )){                
                echo '<h3>Profil gizlilik ayarları nedeniyle görüntülenemiyor!</h3>';
                return;
            }
            
            if($model->profileID>0){
                if($profile->followingstatus>0){
                    $follow = 'hide';
                    $unfollow = '';
                } else {
                    $follow = '';
                    $unfollow = 'hide';
                }
                
                if($profile->ID==$model->profileID){
                    $follow_button = '<a href="javascript:;" class="you">Sensin!</a>';
                } else {
                    $follow_button = '<a href="javascript:;" id="follow'.$profile->ID.'" class="follow '.$follow.'" rel="'.$profile->ID.'">Takip Et</a>
                                      <a href="javascript:;" id="unfollow'.$profile->ID.'" class="unfollow '.$unfollow.'" rel="'.$profile->ID.'">Takip Etme!</a>
                                      ';
                }
            } else {
                $follow_button = '';
            }
            
                
?>


						<meta property="og:image" content="<?=$model->getProfileImage($profile->image, 300,400, 'scale')?>" />
                         <div class="box" id="follow_user">
                            <div class="follow_left">
                                <div class="image"><a href="<?=$model->getProfileImage($profile->image, 300,400, 'scale')?>" rel="fancybox" target="_blank"><img src="<?=$model->getProfileImage($profile->image, 90,90, 'cutout')?>" /></a></div>
                                <div class="statistic">
                                    <?=$profile->di_count?> Ses<br />
                                    <?=$profile->dilike1_count?> Takdir<br />
                                    <?=$profile->dilike2_count?> Saygı<br />
                                    
                                    <?php
   
   if($profile->ID != $model->profileID){
       echo '<a href="javascript:;" id="profilecomplaint" rel="'.$profile->ID.'" style="text-decoration:none;color:#584C43"> Şikayet Et ! </a>';
   }
                               
?><br /> <br />                                   
                                    
                                    
                                </div>
                            </div>
                            <div class="follow_right">
                                <div class="follow_top">
                                    <b><?=$profile->name?></b>
                                    <div class="follow_button"> <?=$follow_button?></div>
                                </div>
                                <div class="line_center"></div>
                                
                                <div class="clear"></div>
                                
                                <div class="follow_button">
                                    <table cellpadding="0" cellspacing="0" border="0" width="300">
                                      <tr height="17"> 
                                        <td colspan="2" > 
                                    			<a onclick="$('.bahset').toggle('slow');" href="javascript:;">Bu Kişiden'ten Bahset</a>
                                    	</td> 
                                    	</tr>
<?php
    if(profile::isallowed($profile->ID, $profile->showbirth )){                
?>                                    	
                                    	<tr height="17">
                                                           		
                                            <td width="50" >Doğum Tarihi</td>
                                            <td width="100"><?php 
  $birthdate = strtotime( $profile->birth );
  $day = intval( date('d', $birthdate) );
  $month = intval( date('m', $birthdate) );
  
  echo $day . ' ' . $l['months'][$month];
  
  
  
  ?></td>
                                        </tr>
<?php
    }
?>                                
<?php
    if(profile::isallowed($profile->ID, $profile->showeducation )){
?>        
                                        <tr height="17">
                                            <td>Eğitim Durumu</td>
                                            <td><?=$profile->education?></td>
                                        </tr>
<?php
    }
?>
<?php
    if(profile::isallowed($profile->ID, $profile->showhometown )){                
?>                                        
                                        <tr height="17">
                                            <td>Memleketi</td>
                                            <td><?=$profile->hometown?></td>
                                        </tr>
<?php
    }
?>
<?php
    if(profile::isallowed($profile->ID, $profile->showlanguages )){                
?>
                                        <tr height="17">
                                            <td>Dil</td>
                                            <td><?=$profile->languages?></td>
                                        </tr>
<?php
    }
?>                                        
                                    </table>
<?php
    if(profile::isallowed($profile->ID, $profile->showmotto )){                
?>
                                    <p class="about"><?=$model->splitword( $profile->motto )?></p>
<?php
    }
?>                                    
                                    
                                </div>
                            </div>
                        
                        <div class="clear"></div>
                        
                        </div>
	<script type="text/javascript">
		
		
	    function submitBahset()
	    {
		    //console.log($("#shareDi").html());
		    data=$("#shareDi").serialize();
	    	$.ajax({
	    	  type:'POST',
	    	  data:data,
			  url: '/ajax/share',
			  
			  success: function(){
			   //alert('done');
				//location.href=location.href;
				$(".bahsdildi").show();
				$(".bahset").hide();
			  }
			});
	    }
		
		</script>	
			<div id="share_idea" class="box bahset" style="display:none;">
                <span class="title_icon" style="width: 290px; float: left">Fikrini Paylaş</span>
                <span class="character"><span class="number">200</span> Karakter</span>
                
                <div class="clear"></div>
                
                <form method="post" onsubmit="return false;" id="shareDi">
                <input type="hidden" name="profileID" value="<?=$profile->ID?>" />
                <input type="hidden" name="profileName" value="<?=$profile->name?>" />
                <input type="hidden" name="linkli" value="profile" />
				<p>Bu Kişiden bahsettiğinizde bundan haberi olacak ve sesiniz duvarınıza düşecektir.</p>
                    <div class="textarea">
	                    <textarea maxlength="200" id="shareditext" name="di">@<?=$profile->name?></textarea>
                    </div>
                    <button type="button" id="submit" onClick="submitBahset();" >Paylaş</button>
                    <div class="clear"></div>
                </form>
             </div>
			 <div id="share_idea" class="box bahsdildi" style="display:none;">
            	<p style="margin-bottom:10px;">Sesiniz Başarı ile Duvarınıza eklenmiştir.</p>  
			</div> 


                     
                
<?php
    if(profile::isallowed($profile->ID, $profile->showdies )){
?>                
      <div class="middlebox">
        <div class="middlebox-head"></div>
        <div class="middlebox-body">
<?php
    if(0 && $profileID == $model->profileID){
?>          
          <div id="shareitbox">
              <div id="sharestatus">
                <textarea id="shareditext" rows="5" cols="25"></textarea>
                <input type="button" id="sharedi" value="share di" />
              </div>
          </div>
          <br class="clearfix" />
<?php
    }
?>
          
            <div id="wallcontainer">
            <div id="firstwall" class="wall">
<?php
    
    
    $result = di::getdies($profileID,0,7);
    if($result['count']>0){
        echo $result['html'];
        echo '<input type="hidden" name="wallstart" value="'.$result['start'].'" />';
    } else {
        echo $result['html'];
    }
    
    $model->addScript('profileID = ' . $profileID . '; wallstart = ' . intval($result['start']) .';' );
    
?>
            </div><!--firstwall END-->
            </div><!--wallcontainer END-->
<?php
    //if($result['count']>0) echo '<div id="wallmore" rel="'.$profileID.'">daha fazla</div>';
    if($result['count']>0) echo '<span id="wallmore" rel="'.$profileID.'" class="more">&nbsp;</span>';
?>            
            
            
          </div><!--middlebox END-->
        <div class="middlebox-footer">&nbsp;</div>
      </div>
<?php
    } else {
        //echo "<h4>"
    }     
                
                
                
            } else { // profile loadobject
                echo '<strong>profile not found</strong>'; 
            }
            
            }//old design end
        }
    } 
?>
