<?php
    class mobile_plugin extends control{
    	public function main()
		{
			global $model;
			$model->template="mobile";
			$model->view="default";
			$model->title = 'Democratus - Mobile';
			
			if($model->paths[1]!="")
            {
            	$func=$model->paths[1];
            }
            else
            {
            	$func="welcome";
            }
			if($model->paths[2]!="")
            	$this->$func($model->paths[2]);
			else
            	$this->$func();
			
		}
		public function feedback()
		{
			global $model;
			$model->template="mobile";
			$model->view="default";
			$model->title = 'Democratus - Mobile';
			?>
			 	<label for="text-1"> Adınız:</label>
			     <input name="name" id="name" value="" type="text">
				 <label for="text-1">Mail Adresiniz:</label>
			     <input name="mail" id="mail" value="" type="text">
			     <label for="text-3">Şikayet ve Öneriniz</label>
			     <textarea cols="40" rows="8" name="mesaj" id="mesaj"></textarea>
			     <input value="Gönder" type="button" onclick="send_feedback();">
			<?
			return false;
		}
        public function main_old(){
        	global $model;// $db, $l;
            //die;
            $model->initTemplate('v2','mobile'); 
            
			//$model->addScript($model->pluginurl . 'di.js', 'di.js', 1);
        	if($model->profileID<1 && $model->paths[1]!="welcome"){
                //$model->mode = 0;
                return $model->redirect('/mobile/welcome', 1);
            }
            if($model->profileID>0 && $model->paths[1]=="welcome"){
            	return $model->redirect('/mobile/home', 1);
            }
			$model->addScript(" var profileID='".$model->profileID."';");
            if($model->paths[1]!="")
            {
            	$func=$model->paths[1];
            }
            else
            {
            	$func="welcome";
            }
			if($model->paths[2]!="")
            $this->$func($model->paths[2]);
			else
            $this->$func();
        }
        
        public function welcome(){
        	global $model;
        	$model->addStyle(" 
        		#faceicon
				  {
					width:138px;
					height:23px;
					background-image:url(http://ofistesenlik.com/t/v2/static/images/faceIcon.png);
					cursor:pointer;
					float:left;
				  }
		         #faceicon:hover
				  {
					background-image:url(http://ofistesenlik.com/t/v2/static/images/faceIcon.png);
					background-position:0 -26px; 
				  }
				  #twittericon
				  {
					width:138px;
					height:23px;
					background-image:url(http://ofistesenlik.com/t/v2/static/images/twitterIcon.png);
					cursor:pointer;
					float:right;
				  }
				  #twittericon:hover
				  {
					background-image:url(http://ofistesenlik.com/t/v2/static/images/twitterIcon.png);
					background-position:0 -26px; 
				  }
			  ");
            ?>
			    <div data-role="content" style="background-color: #962B2B;">
					<img src="/t/v2/static/images/logo.png" width="100%" />
					<img src="/t/v2/static/images/motto.png" width="100%" />
					<br/><br/>
					<div id="welcomeSocialBtn" style=""> 
						<div id="faceicon" onclick="javascript:location.href='/oauth/facebook';"></div>
						<div id="twittericon" onclick="javascript:location.href='/oauth/twitter';"></div>
					</div>
					<br/>
				    <input type="text" name="mail" id="mail" value="E-posta" onFocus="if(this.value==this.defaultValue) this.value=''" onBlur="if(this.value=='')this.value=this.defaultValue;" style="margin-top: 30px;"/>
				    <input type="text" name="viewPass" id="viewPass" value="Parola" style="margin-top: 10px;" onFocus="$(this).hide(); $('#pass').show().focus();"/>
				    <input type="password" name="pass" id="pass" value=""  style="margin-top: 10px; display:none;" onblur="$(this).hide; $('viewPass').show();"/>
					<a data-role="button" href="javascript:;" data-theme="a" class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-c" onclick="login()">
						<span class="ui-btn-inner ui-btn-corner-all" aria-hidden="true">
							<span class="ui-btn-text">Giris</span>
						</span>
					</a>
			    </div><!-- /content -->
            <?php 
        }
		public function wall($type="follow")
        { 
			global $model;
			//$model->addScript("$('.pageLoadEventCls').live('pageshow', function (event, ui) {initWall();});");
        	$result = di::getdiesMobile(0,0,7,$type);
			
		?>
			<div data-role="content" >
				<div id="wallcontainer">
				<? echo $result["html"]; ?>
				</div>
				<a href="javascript:;" id="wallmore" data-role="button" >Daha Fazla</a>
				<input type="hidden" name="walltype" id="walltype" value="<?=$type?>" />
				<input type="hidden" name="wallstart" id="wallstart" value="<?=$result['start']?>" />
				
			</div>
		<?php
		}
        public function home()
        {
        	global $db, $model;
        	//$model->addScript("$('.pageLoadEventCls').live('pageshow', function (event, ui) {initHome();});");
        	$SELECT = "\n SELECT a.*, av.vote AS myvote, p.image AS deputyimage, p.name AS deputyname";
            $FROM   = "\n FROM agenda AS a";
            $JOIN   = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote($model->profileID);
            $JOIN  .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
            $WHERE  = "\n WHERE ".$db->quote(date('Y-m-d H:i:s'))." BETWEEN a.starttime AND a.endtime";                    
            $WHERE .= "\n AND a.status>0";            
            $GROUP  = "\n ";
            $ORDER  = "\n ORDER BY a.ID desc";
            $ORDER  = "\n ORDER BY a.point DESC";
            $LIMIT  = " LIMIT 7\n ";
            
            $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$GROUP.$ORDER.$LIMIT);
            $agendas = $db->loadObjectList();
            //echo "<pre>";
            //var_dump($agendas);
            //echo "</pre>";
        	?>
				<div data-role="content" >
				<?php 
				$i=0;
				foreach($agendas as $a)
				{
					$i++;
					$checked[2] ='';
					$checked[3] ='';
					$checked[4] ='';
					$checked[$a->myvote] =' checked="checked"';
				?>
				<div class="ui-body ui-body-a proposalContent" style="display:none;" id="pro-<?=$i?>">
					<h4 style="margin:.5em 0"><?=$a->deputyname ?></h4>
			        <p height="250">
			        	<?=$a->title ?>
			        </p>
			        
						<div class="choose">
							<label for="parliament_choose_<?=$a->ID?>_2">Katılıyorum</label>
							<input type="radio" class="parliamentoption" id="parliament_choose_<?=$a->ID?>_2" name="poll_choose_<?=$a->proposalID?>" style="display: none;" <?=$checked[2]?>>
							<div class="clear"></div>
						</div>
						<div class="choose">
							<label for="parliament_choose_<?=$a->ID?>_3">Kararsızım</label>
							<input type="radio" class="parliamentoption" id="parliament_choose_<?=$a->ID?>_3" name="poll_choose_<?=$a->proposalID?>" style="display: none;" <?=$checked[3]?>>
							<div class="clear"></div>
						</div>
						<div class="choose">
							<label for="parliament_choose_<?=$a->ID?>_4">Katılmıyorum</label>
							<input type="radio" class="parliamentoption" id="parliament_choose_<?=$a->ID?>_4" name="poll_choose_<?=$a->proposalID?>" style="display: none;" <?=$checked[4]?>>
							<div class="clear"></div>
						</div>
						<a href="/mobile/di/<?=$a->diID?>" data-role="button" data-icon="star">Söyleş</a> 
				</div>
				<?php } ?>
				<style>
					.ui-btn-inner{
						padding: 0.6em 15px;
					}
				</style>
				<center>
				<div data-role="controlgroup" data-type="horizontal" id="proNavContent">
				  <a id="proNav-1" href="javascript:;" data-role="button" onclick="changeProposal(1);">1</a>
				  <a id="proNav-2" href="javascript:;" data-role="button" onclick="changeProposal(2);">2</a>
				  <a id="proNav-3" href="javascript:;" data-role="button" onclick="changeProposal(3);">3</a>
				  <a id="proNav-4" href="javascript:;" data-role="button" onclick="changeProposal(4);">4</a>
				  <a id="proNav-5" href="javascript:;" data-role="button" onclick="changeProposal(5);">5</a>
				  <a id="proNav-6" href="javascript:;" data-role="button" onclick="changeProposal(6);">6</a>
				  <a id="proNav-7" href="javascript:;" data-role="button" onclick="changeProposal(7);">7</a>
				</div>
				</center>				
			    </div><!-- /content -->



		<?php 
        }
		   public function di($diID)
        {	
        	global $model;
        	//$model->addScript('alert("test misali");');
        	
        	?>
			<div data-role="content" >
				<?php 
					$di = new di;
					$result = $di->getdiMobile($diID);
					if(intval($result['count'])<1) $model->redirect('/'); //die('1'); 
					if(!profile::isallowed($result['row']->profileID, $result['row']->showdies)){
						echo '<h4>gizlilik ayarları nedeniyle görüntülenemiyor!</h4>';
						return;
					}
					echo $result['html'];
					echo "<hr />";
					$commentresult = $di->getdicommentsMobile($diID);
					//echo "<pre>";
					echo $commentresult['html'];
					//echo "</pre>";
					echo "<hr />";	
					echo $result['commentit'];
				?>
			</div>
			<?
		}
		public function profile($profileID)
		{
			global $model,$db;
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
            if($db->loadObject($profile)) {
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
            }
			?>
			<div data-role="content" >
				<div style="float: left;">
				<img src="<?=$model->getProfileImage($profile->image, 90,90, 'cutout')?>" />
				</div>
				<div style="float: left; margin-left: 10px;">
					<h3 style="margin: 0;"><b><?=$profile->name?></b></h3>
					<?php
						if(profile::isallowed($profile->ID, $profile->showmotto )){                
					?>
						<p class="about" style="margin-top: 0;"><?=$model->splitword( $profile->motto )?></p>
					<?php
					    }
					?>  
				</div>
				<div style="clear: both;"></div>
				<div class="follow_button"> <?=$follow_button?></div>
				<div class="statistic">
				<br />
					<?=$profile->di_count?> Ses | 
                    <?=$profile->dilike1_count?> Takdir |
                    <?=$profile->dilike2_count?> Saygı           
					
				</div>
				<?php
				    if(profile::isallowed($profile->ID, $profile->showdies )){
					    $result = di::getdiesMobile($profileID,0,7,"all");
					    if($result['count']>0){
					    	?>
					    	<div id="wallcontainer">
						    	<?php 
						        echo $result['html'];
						        
						        echo '<input type="hidden" name="wallstart" value="'.$result['start'].'" />';
						        echo '<input type="hidden" name="walltype" id="walltype" value="profile" rel="'.$profileID.'" />';
						        ?> 
					        </div>
					        <a href="javascript:;" id="wallmore" data-role="button" >Daha Fazla</a>
					        <?php 
					    } else {
					        echo $result['html'];
					    }
				    } else {
				        //echo "<h4>"
				    }   
				    ?>
			</div>
			<?
		}
		static function notice()
		{
			global $model,$db;
	
			$tr_gun=array('','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar');
			$tr_ay=array('','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');
			       		
			?>
			<div data-role="content" >
			<?php
				$SELECT = "SELECT n.*, p.name, p.image, p.ID AS pID";
	            $FROM   = "\n FROM notice AS n";
	            $JOIN   = "\n JOIN profile AS p ON p.ID=n.fromID";
	            $WHERE  = "\n WHERE n.profileID=".$db->quote(intval( $model->profileID ));
	            $WHERE  .= "\n and datetime>= DATE_ADD(NOW(), INTERVAL -10 DAY) ";
	            $ORDER  = "\n ORDER BY n.ID DESC";
	            $LIMIT  = "\n LIMIT 21";
	            $LIMIT  = "\n ";
	            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
	            $rows = $db->loadObjectList();
	            if(count($rows))
				{
					$tarihFormati=array ("Bugün","Dün","İki Gün Önce","date");
					$bg=0;
					$dn=0;
					$go2=0;
					$nTime="";
					$gosterme["dicomment"]=array();
					$gosterme["dicommentcomment"]=array();
					foreach($rows as $row)
					{
						switch ($row->type) 
						{
		                    case 'dilike':
		                        if($row->subtype == 'dilike1') 
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID2.'"> paylaşımınızı takdir etti.</a>';
		                        elseif($row->subtype == 'dilike2') 
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID2.'"> paylaşımınıza saygı duydu.</a>';
		                        else
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID2.'"> paylaşımınızı oyladı.</a>';
		                    
		                    break;
		                    case 'redi':
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID2.'"> paylaşımınızı yeniden paylaştı.</a>';
		                    break;
		                    case 'dicomment':
		                    	$db->setQuery("SELECT DISTINCT fromID FROM notice WHERE TYPE = 'dicomment' AND ID3 = '".$row->ID3."'");
			            		$kackisi = count($db->loadObjectList());
			            		if($kackisi>1)
		                        $message = $row->name.' ve '.($kackisi-1).' kişi daha  sizin bir <a href="/mobile/di/'.$row->ID3.'#'.$row->ID2.'"> paylaşımınıza yorum yaptı.</a>';
		                    	else 
		                    	$message = $row->name.' sizin bir <a href="/mobile/di/'.$row->ID3.'#'.$row->ID2.'"> paylaşımınıza yorum yaptı.</a>';
		                    	break; 
		                    case 'dicommentcomment':
		                    	$db->setQuery("SELECT DISTINCT fromID FROM notice WHERE TYPE = 'dicommentcomment' AND ID3 = '".$row->ID3."'");
			            		$kackisi = count($db->loadObjectList());
			            		if($kackisi>1)
		                        $message = $row->name . ' ve '.($kackisi-1).' kişi daha sizin yorum yaptığınız ses için  <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> yorum yaptı.</a>';
		                    	else
		                    	$message = $row->name . ' sizin yorum yaptığınız ses için  <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> yorum yaptı.</a>';
		                  	break;
		                    case 'dicommentlike':
		                        //$message = $row->name . ' isimli kullanıcı sizin bir <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> di yorumunuzu oyladı.</a>';                    
		                        
		                        if($row->subtype == 'dilike1') 
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID3.'#'.$row->ID2.'"> yorumunuzu takdir etti.</a>';
		                        elseif($row->subtype == 'dilike2') 
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID3.'#'.$row->ID2.'"> yorumunuza saygı duydu.</a>';
		                        else
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID3.'#'.$row->ID2.'"> yorumunuzu oyladı.</a>';
		                    break;
		                    case 'follow':
		                        $message = $row->name . ' sizi takip etmeye başladı.';                    
		                    break;
		                    case 'deputy':
		                        $message = $row->name . ' size milletvekili oyu verdi.';                    
		                    break;
		                    case 'proposalvote':
		                        $message = $row->name . ' sizin bir<a href="/proposal#pp'.$row->ID2.'"> tasarınıza </a> oy verdi.';                    
		                    break;
		                    case 'mentionDi':
		                        $message = $row->name . ' sizin bir <a href="/mobile/di/'.$row->ID3.'"> Sesiniz </a>\'den bahseti. Görmek için <a href="/mobile/di/'.$row->ID2.'"> Tıklayınız </a>.';                    
		                    break;
		                    case 'mentionProfile':
		                        $message = $row->name . ' bir <a href="/mobile/di/'.$row->ID2.'"> Sesinde </a> Sizden bahseti.';                    
		                    break;
		                    default:
		                        $message = $row->name . ' birşeyler yaptı.';                    
						}
						
						$dateArr=explode(" ", $row->datetime);
						$dateArr=explode("-", $dateArr[0]);
	       				$da=$dateArr;
						$gosterDate="";
		                if($da[1]==date("m") && $da[2]==date("d"))
		                {
		                	if($bg==0)
		                    $gosterDate=$tarihFormati[0];
		                    $bg=1;
		              	}
		                else if($da[1]==date("m") && $da[2]==date("d")-1)
		                {
		                	if($dn==0)
		                    $gosterDate= $tarihFormati[1];
		                    $dn=1;
						}
		         		else if($da[1]==date("m") && $da[2]==date("d")-2)
		                {
		                	if($go2==0)
		                    $gosterDate= $tarihFormati[2];
		                    $go2=1;
		             	}
		                else {
		                	if($nTime!=$da[1]."-".$da[2])
							{
		                    //$gosterDate= $this->trTime($row->datetime);
								$tr_gun=array('','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar');
								$tr_ay=array('','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');

								$timeSt=strtotime($row->datetime);
								$gosterDate= date("d",$timeSt)." ".$tr_ay[date("n",$timeSt)]." ".$tr_gun[date("N",$timeSt)];
							
								$nTime=$da[1]."-".$da[2];
							}
		             	}
		             	
			       		//$dateArr=explode(" ", $date);
			       		//$dateArr=explode("-", $dateArr[0]);
			       		$timeSt=strtotime($row->datetime);
			       		$trTimeTxt= date("d",$timeSt)." ".$tr_ay[date("n",$timeSt)]." ".$tr_gun[date("N",$timeSt)];
						$html = '<div class="ui-body ui-body-a" style="margin-top:10px;">
		                			<p>'.$message.'</p> 
									</div>
									';
									
		                    		if($row->type=="dicomment" || $row->type=="dicommentcomment"){
										if(!in_array($row->ID3, $gosterme[$row->type]))
										{
											echo "<span class='noticeTime'>".$gosterDate."</span>".$html;
										}
										$gosterme[$row->type][]=$row->ID3;
		                    		}
		                    		else 
		                    		{
		                    			echo "<span class='noticeTime'>".$gosterDate."</span>".$html;
		                    		}
						
					}
				} else {
                	echo '<p>hiç yok!</p>';
	            }
	            $pr = new stdClass;
	            $pr->ID = $model->profileID;
	            $pr->noticetime = date('Y-m-d H:i:s');
	            
	            $db->updateObject('profile', $pr, 'ID');
			?>
			</div>
			<?php 
			}	
			public function newvoice()
			{
				global $model, $db;
				?>
				<div data-role="content" >
				<textarea ></textarea>
				</div>
			<?
			} // newvoice end
			
	       	public function trtime($date)
	       	{
	       		$tr_gun=array('','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar');
	       		$tr_ay=array('','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');
	       		
	       		//$dateArr=explode(" ", $date);
	       		//$dateArr=explode("-", $dateArr[0]);
	       		$timeSt=strtotime($date);
	       		return date("d",$timeSt)." ".$tr_ay[date("n",$timeSt)]." ".$tr_gun[date("N",$timeSt)];
	       	}
    }
?>
