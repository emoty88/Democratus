<?php
    class home_plugin extends control{
    	
        public function main(){
          
        	global $model, $db, $l;
			$model->checkLogin(1);
			$c_profile = new profile();
			
			
			$rt=$c_profile->check_userMin();
			if(!$rt["success"])
			{
				$model->redirect("/my#profilA");
			}
                        
			$model->template="ala";
			$model->view="home";
			$model->title = 'Democratus';
			
			$model->addHeaderElement();
			
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='home'");
                        if(isset($_SESSION['from_sm']) and !empty($_SESSION['from_sm'])){
                            $model->addScript("from_sm();");
                        }
			if($model->profile->show_tour==0 || $model->paths[1]=="tour")
			{
				$uP	= new stdClass;
				$uP->ID = $model->profileID;
				$uP->show_tour = 1;
				profile::update_profile($uP);
				$model->addScript("$(document).ready(function () { show_step(0); });");
			}

			
			//var_dump($model);
		}
		public function main_old(){ 
          	global $model, $db, $l;
			//$model->newDesign=false;
			//var_dump($model->profile);
			//deneme klavuz
			//pagecontent
			if($model->paths[1]=="userguide")
			{
				$this->kklavuz();
			}
			//pagecontent
			
            if($model->profileID<1){
                $model->mode = 0;
                return $model->redirect('/welcome', 1);
            }
            
            $model->template = 'ala';
            $model->view = 'default';

            $model->title = 'Democratus';
            if($model->newDesign)
            {// Yeni Tasarım Başlangıç
            	$model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
            	$model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            	$model->addScript(PLUGINURL . 'lib/selectableFunction.js', 'selectableFunction.js', 1);
            	$model->addScript($model->pluginurl . 'home.js', 'home.js', 1);
				
            	$model->addScript("
            		$(document).ready(function(){
            			homeDiLoaderFollow();
            			homeDiLoaderDeputy();
            			
            		});
            	");
            	$model->addStyle(TEMPLATEURL."v2/static/style/popup.css","popup.css",1);
            	$model->addScript(TEMPLATEURL. 'v2/static/javascript/popup.js', 'popup.js', 1);
            	$model->addStyle(PLUGINURL."lib/jquery-ui/jquery-ui.css","jquery-ui.css",1);
            	$model->addStyle("
            		#selectable{
            			list-style:none;
            			margin:0;
            			}
            	");
				
				$model->addScript("paths=".json_encode($model->paths));
				$profileClass= new profile;
				$takipci=$profileClass->getfollowercount($model->profileID);
				
				if($takipci<5 && $model->session->showUG==0)
				{?>
					<script>
					$(document).ready(function (){
						$("#guideBtn").fancybox({
					            'autoScale': true,
					            'transitionIn': 'elastic',
					            'transitionOut': 'elastic',
					            'speedIn': 500,
					            'speedOut': 300,
					            'autoDimensions': true,
					            'centerOnScroll': true // remove the trailing comma!!
					        }).click();
						
					});
					</script>
					<?
					$model->session->showUG=1;
					$db->updateObject('session', $model->session, 'ID') ;	
						
				}
            	?>
            	
				
            	{{shareditextbox}}
				<p></p>
				
				<script id="department-tmpl" type="text/x-jquery-tmpl">// <![CDATA[
				                <strong>${id}</strong>
				// ]]></script>
				<div id="members-list">
					
				</div>
				
				<ul id="tab" class="nav nav-tabs" style="margin-bottom: 0;">
					<li class="active">
						<button class="tabbtn tooltip-top" data-original-title="Takip ettiğiniz kişilerin ve sizin sesleriniz." href="#tab-duvar" rel="duvar" data-toggle="tab">Duvarım</button>
					</li>
		            <li>
		            	<button class="tabbtn tooltip-top" data-original-title="Bu haftanın vekillerin tüm sesleri." href="#tab-vekiller" rel="vekiller" data-toggle="tab">Vekil Sesleri</button>
		            </li>
					<li>
						<button class="tabbtn tooltip-top" data-original-title="Gün boyu öne çıkan sesler." href="#tab-sesgetirenler" rel="sesgetirenler" data-toggle="tab">Ses Getirenler</button>
					</li>
					<li>
						<button class="tabbtn last tooltip-top" data-original-title="İçeriğinde sizden bahsedilen sesler." href="#tab-cagrilar" rel="cagrilar" data-toggle="tab">Çağrılar</button>
					</li>
		        </ul>
		        
				<div id="myTabContent" class="tabuser-content">
            		<div class="tab-pane fade in active" id="tab-duvar">
				   	<div id="wallcontainerfollow" > 
            		<p></p>
            		<div id="getNewDiesFollow" class="infoBox" style="display: none; cursor: pointer;"></div>
            		<?php 
            			$resultTakipEttiklerim = di::getdies(0,0,7,"follow"); 
		                if($resultTakipEttiklerim['count']>0){
					        echo $resultTakipEttiklerim['html'];
					        echo '<input type="hidden" id="wallstartfollow" name="wallstartfollow" value="'.$resultTakipEttiklerim['start'].'" />';
					        echo '<input type="hidden" id="wallfirstfollow" name="wallfirstfollow" value="'.$resultTakipEttiklerim['first'].'" />';
					    } else {
					        echo $resultTakipEttiklerim['html'];
					    }
            		?>
            		</div>
            		<p></p>
            		<?php $model->addScript("var startFollowPid=0;");?>
            		
            		<button class="btn100 tabbtn" id="wallmorefollow" rel="0" >DAHA FAZLA SES YÜKLE</button>
            		
            		<p></p>
            		</div>
            		<div class="tab-pane fade in" id="tab-vekiller">
            		<div id="wallcontainerdeputy"> 
            		<p></p>
            		<div id="getNewDiesDeputy" class="infoBox" style="display: none; cursor: pointer;"></div>
            		<?php 
            			$resultVekiller = di::getdies(0,0,7,"deputy"); 
		                if($resultVekiller['count']>0){
					        echo $resultVekiller['html'];
					        echo '<input type="hidden" id="wallstartdeputy" name="wallstartdeputy" value="'.$resultVekiller['start'].'" />';
					        echo '<input type="hidden" id="wallfirstdeputy" name="wallfirstdeputy" value="'.$resultVekiller['first'].'" />';
					    } else {
					        echo $resultVekiller['html'];
					    }
            		?>
            		</div>
            		<p></p>
            		<input type="hidden" id="wallstartdeputy" value="<?=$resultTakipEttiklerim['start']?>" />
            		<button class="btn100 tabbtn" id="wallmoredeputy" rel="0" >DAHA FAZLA SES YÜKLE</button>
            		<p></p>
            		</div>
            		<div class="tab-pane fade in" id="tab-sesgetirenler">
            		<!-- Sesgetirenler Tab Start -->
            			<div id="wallcontainerfollow" > 
		            		<p></p>
		            		<div id="getNewDiesFollow" class="infoBox" style="display: none; cursor: pointer;"></div>
		            		<?php 
		            		
		            			$resultTakipEttiklerim = di::getpopularDi(); 
				                if($resultTakipEttiklerim['count']>0){
							        echo $resultTakipEttiklerim['html'];
							        echo '<input type="hidden" id="wallstartfollow" name="wallstartfollow" value="'.$resultTakipEttiklerim['start'].'" />';
							        echo '<input type="hidden" id="wallfirstfollow" name="wallfirstfollow" value="'.$resultTakipEttiklerim['first'].'" />';
							    } else {
							        echo $resultTakipEttiklerim['html'];
							    }
							    
		            		?>
	            		</div>
	            		<p></p>
            		<!-- Sesgetirenler Tab End -->
            		</div>
            		<div class="tab-pane fade in" id="tab-cagrilar">
            			<?php 
            			if($model->profileID=="4575" || 1==1) //Geri bildirim kullanıcısı
            			{
            			?>
            				<div id="wallcagrilarContent"> 
            			<?php 
            				$cagrilar=di::getCagrilarDies($model->profileID,0,7);
            				echo $cagrilar["html"];
            				//var_dump($cagrilar["html"]);
            			?>
            				</div>
            				<p></p>
            				<?php if($cagrilar["count"]>0){?>
            				<input type="hidden" id="cagrilarstart" value="<?=$cagrilar['start']?>" />
            				
            				<button class="btn100 tabbtn" id="wallmorecagrilar" rel="<?=$model->profileID?>" >DAHA FAZLA YÜKLE</button>
            				<?php }?>
            				<p></p>
            			<?php
            			}//geribildirim son
            			else
            			{// geribildirim değil ise
            				?>
            				<p></p>
	            			<div class="roundedcontent shareidea">
								<h1>
									<img src="http://ofistesenlik.com/t/beta/img/democratus_icon.png"> 
									Bu Modül Test Aşamasındadır.
								</h1>
								<div class="clear"></div>
								<p>Kullanıcılarımıza kusursuz bir deneyim sunmak için bu modülü kapalı olarak test ediyoruz.</p>
								<br>
							</div>
            				<?php 
            			}// geribildirim değil ise
            			?>
            		</div>
            	</div>
				<?php 
            }// Yeni Tasarım Sonu 
            else
            {// eski Tasarım Başlangıç
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addStyle("
				#selectable .ui-selecting { background: #FECA40; }
				#selectable .ui-selected { background: #F39814; color: white; }
				#selectable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
				#selectable li { margin: 3px; padding: 0.4em; font-size: 1.4em; height: 18px; }
			");
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            
            $model->addScript($model->pluginurl . 'home.js', 'home.js', 1);
            
?>

            <div id="share_idea" class="box">
                <span class="title_icon" style="width: 290px; float: left">Fikrini Paylaş</span>
                <span class="character"><span class="number">200</span> Karakter</span>
                
                <div class="clear"></div>
                
                <form method="post" onsubmit="return false;">
                    <div class="textarea"><textarea maxlength="200" id="shareditext"></textarea></div>
                    <div id="mentionDisplay" style="position: absolute; z-index: 999;"></div>
                    <div id="degerler" style="display: none;">
                    	<input type="hidden" id="linkli" value="0" />
                    	<input type="hidden" id="profileName" value="" />
                    	<input type="hidden" id="profileID" value="" />
                    </div>
                    <button type="submit" id="sharedi">Paylaş</button>
                    <div class="clear"></div>
                </form>
            </div>
          
          	<div class="box" id="share_idea">
	          	<table width="100%" style="margin-bottom: 5px;">
	          		<tr>
	          			<td width="50%" align="center" style="text-align: center;">
	          				<?php 
	          				if($model->paths[1]=="deputy"){ 
	          				?>
	          				Vekil Paylaşımları
	          				<?php } else {?>
	          				<a href="/home/deputy">Vekil Paylaşımları</a>
	          				<?php }?>
	          			</td>
	          			<td>|</td>
	          			<td width="50%" align="center" style="text-align: center;">
	          				<?php 
	          				if($model->paths[1]=="deputy"){ 
	          				?>
	          				<a href="/">Takip Ettiklerim</a>
	          				<?php } else {?>
	          				Takip Ettiklerim
	          				<?php }?>
	          			</td>
	          		</tr>
	          	</table>
            </div>
            <div id="wallcontainer">
                <div id="firstwall" class="wall">
                    <span id="all_idea">
<?php
	$db->setQuery("select * from follow where followerID='".$model->profileID."' and status='1'");
	$db->query();
	$num_rows = $db->getNumRows();
	if($num_rows<4)
	{
		$result = di::getdiNewUser();
		echo $result['html'];
	}
	if($model->paths[1]=="deputy"){ 
	    $result = di::getdies(0,0,7,"deputy"); 
		echo '<input type="hidden" value="deputy" name="walltype" id="walltype"/>';
	}
    else 
    {
    	$result = di::getdies(0,0,7);
    	echo '<input type="hidden" value="follow" name="walltype" id="walltype"/>';
    }
    if($result['count']>0){
        echo $result['html'];
        echo '<input type="hidden" name="wallstart" value="'.$result['start'].'" />';
    } else {
        echo $result['html'];
    }
    
    $model->addScript(
                        'profileID = ' . $model->profileID .';'
                        .' wallID = 0;'
                        .' wallstart = ' . $result['start'] .';'
                       );
    //$model->addScript('wallID = 0');
    //$model->addScript('wallstart = ' . $result['start'] );
    
?>
                    </span>
                </div><!--firstwall END-->
            </div><!--wallcontainer END-->
            
            
            <span id="wallmore" rel="0" class="more">&nbsp;</span>
            

          
<?php                

        	}//Eski Tasarım Sonu
            
        }
        

        public function getdilikeinfo($ID){
            global $model, $db, $l, $LIKETYPES;
            
            //mylike'yi bul
            $db->setQuery('SELECT * FROM dilike  WHERE profileID='.$db->quote($model->profileID).' AND diID = ' . $db->quote($ID) );
            $mylike = null;
            if($db->loadObject($mylike)){
                
            } else {
                $mylike = null;
            }
           
            //like'yi bul
            foreach($LIKETYPES as $liketype)
                $q[] = ' SUM('.$liketype.') AS '.$liketype;
            
            $q = implode(',', $q);
            
            
            $db->setQuery( 'SELECT ' . $q . ' FROM dilike  WHERE diID = ' . $db->quote($ID));
            if( $db->loadObject($like) ){
                
            } else {
                $like = null;
            }
           


            $result = '';
            foreach($LIKETYPES as $liketype){
                if(!is_null( $like )){//her hangi bir like bulunamadı ise
                    //Takdir et vs'yi yaz
                    
                    
                    //benim seçimim ise
                    if( !is_null($mylike) && intval( $mylike->$liketype ) > 0){
                        if(intval( $like->$liketype )>1)
                            $dilikecount = '( <span class="dilikecount" onclick="javascript:dilikers('.$ID.',\''.$liketype.'\')">'.$like->$liketype.'</span> ) ';
                        else
                            $dilikecount = '';

                        $result .= '<span id="'.$liketype.'_'.$ID.'" class="dilike">'.$l[$liketype.'liked'].'</span> '.$dilikecount.'';
                        
                    //benim seçimim değil ise    
                    } else {
                        if(intval( $like->$liketype )>0)
                            $dilikecount = '( <span class="dilikecount" onclick="javascript:dilikers('.$ID.',\''.$liketype.'\')">'.$like->$liketype.'</span> ) ';
                        else
                            $dilikecount = '';
                        
                        $result .= '<span id="'.$liketype.'_'.$ID.'" class="dilike" onclick="javascript:dilike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].'</span> '.$dilikecount.'';
                        
                    }
                    
                    
                    
                    
                } else {
                    $result .= '<span id="'.$liketype.'_'.$ID.'" class="dilike" onclick="javascript:dilike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].'</span> ';
                    
                }
                
            }
            return $result;
            
/*            
            $db->setQuery('SELECT SUM(dilike1) AS dilike1, SUM(dilike2) AS dilike2 FROM dilike  WHERE diID = ' . $db->quote($ID) );
            if( $db->loadObject($like) ){
                $db->setQuery('SELECT * FROM dilike  WHERE profileID='.$db->quote($model->profileID).' AND diID = ' . $db->quote($ID) );
                if( $db->loadObject($mylike) ){
                    if($mylike->dilike1==1) $mylike = 'dilike1';
                    elseif($mylike->dilike2==1) $mylike = 'dilike2';
                    else $mylike = null;
                } else $mylike = null;
                
            } else {
                $like = (object) array('dilike1'=>0, 'dilike2'=>0 );
                $mylike = null;
            }
            
            
            if($dilike['mylike']=='dilike1'){//takdir ettinse
                if(intval($dilike['like']->dilike1)>1) 
                    $dilikecount = '( <span class="dilikecount" onclick="javascript:dilikers('.$row->ID.',1)">'.$dilike['like']->dilike1.'</span> )'; 
                else 
                    $dilikecount = '';    
                
                echo '<span id="dilike1_'.$row->ID.'" class="dilike">'.'Takdir Ettin</span> '.$dilikecount.'';
            } else { //takdir etmedinse
                if(intval($dilike['like']->dilike1)>0) 
                    $dilikecount = '( <span class="dilikecount" onclick="javascript:dilikers('.$row->ID.',1)">'.$dilike['like']->dilike1.'</span> )'; 
                else 
                    $dilikecount = '';
                    
                echo '<span id="dilike1_'.$row->ID.'" class="dilike" onclick="javascript:dilike('.$row->ID.',1)">'.'Takdir Et</span> '.$dilikecount.'';
                            
            }            
            
            
            
            
            return array('like'=> $like, 'mylike'=>$mylike );
            */
        }        
        
        
        public function getsharelikeinfo($ID){
            global $model, $db;
            
            $db->setQuery('SELECT SUM(regard) AS regard, SUM(appreciate) AS appreciate FROM sharelike WHERE shareID = ' . $db->quote($ID) );
            if( $db->loadObject($result) )
                return $result;
            else {
                return (object) array('regard'=>0, 'appreciate'=>0 );
            }
        }
        
        public function getsharecommentlikeinfo($ID){
            global $model, $db;
            
            $db->setQuery('SELECT SUM(regard) AS regard, SUM(appreciate) AS appreciate FROM sharecommentlike WHERE commentID = ' . $db->quote($ID) );
            if( $db->loadObject($result) )
                return $result;
            else {
                return (object) array('regard'=>0, 'appreciate'=>0 );
            }
        }
		//pagecontente taşınıcak
		public function kklavuz()
		{
			global $model;
			$model->mode=0;
			?>
	
				
            <div data-spy="scroll" data-target="#navbarExample" data-offset="0" class="scrollspy" style="width:800px; height:400px;">
             <h1>Kullanım Klavuzu:</h1>
			<hr/>
				<p>
				Burada fikrini yazıp takipçilerinle paylaştığın yazılı mesajlara “ses” diyoruz.
				-“Fikrini Paylaş” kutusuna 200 karakterlik ''ses''ler yazarak fikrini takipçilerine
				duyurabilirsin.
				</p>
				<p>
				Takip ettiğin kişilerin seslerini de kendi duvarından takip edebilirsin.
				</p>
				<p>
				Diğer kullanıcılarla etkileşime girmen ve takipçilerini arttırman vekil olmanı
				sağlayan vekil puanına da katkıda bulunacak.
				</p>
				
				<h3>Takdir et- Saygı duy</h3>
				<p>
				Bir sesi beğendiysen ''takdir et'', beğenmediysen ''saygı duy'' butonlarını
				kullanabilirsin.
				</p>
				
				
				<h3>Yanıtla</h3>
				<p>
				Senin de konuyla ilgili söyleyeceklerin varsa “Yanıtla” butonunu kullanarak
				kendi fikrini yazabilirsin.
				</p>
				
				
				<h3>Paylai</h3>				
				<p>
				Beğendiğin bir sesi duvarında takipçilerinle ''paylaş''abilirsin.
				</p>
				
				<h3>Vekil puanı</h3>
				<p>
				Kazandığın her takipçi, yazdığın her ses, yazdığın ses'in oluşturacağı her tepki
				sana değişen oranlarda puanlar kazandıracak. En büyük puan artışını diğer
				kullanıcılardan alacağın vekillik oyu ile yaşayacaksın.
				</p>
				<p>
				Sildiğin her ses'ten puan yitirmektesin, en büyük puan kaybını ise Kullanım ve
				Gizlilik Sözleşmesi'ne aykırı tutumlar yüzünden yaşarsın. Her hafta yenilenen
				vekil puanını yüksek tutmalısın.
				</p>
				
				
				<h3>Meclis</h3>
				<p>
				Ülke gündemi her gün senin oyların ile şekillenecek, Türkiye Meclisi'ndeki 7
				gündemi oylamayı unutma.
				</p>
				<p>
				Ülke gündemlerini yazanlar sanal ülke meclisinin vekilleri. Vekiller her
				haftasonu başında en iyi puana sahip 50 kişiden belirleniyor.
				</p>
				<p>
				Haftanın vekillerinden biriysen hemen meclise girip yarının ülke gündemini
				belirlemek için teklifler sunabilirsin. Bu teklifleri kendin yazabileceğin gibi ses
				getirenler arasından da seçebilirsin.
				</p>
				<h3>Ses Getirenler</h3>
				<p>
				Paylaştığın bir ses çok reaksiyon alırsa 24 saatliğine ''ses getirenler'' alanından
				tüm ülkeye görünecektir.
				</p>
				<p>
				Vekil olmasan bile “Ses getirenler” köşesine düşen sesin başka bir vekil
				tarafından meclis gündemine taşınabilir.
				</p>
				<h3>Arama</h3>
				<p>
				Arama kutucuğundan kişileri, sayfaları, içinde aradığın anahtar kelimeyi
				barındıran tüm sesleri ve meclis arşivlerini görüntüleyebilirsin.
				</p>
				<h3>Hashtag sayfaları (Kurum-Konu Sayfaları)</h3>
				<p>
				İlgi duyduğun kurum ve konulara ait sayfaları takip edebilirsin. Bu sayfaları
				takip ettiğin zaman profilinde bu sayfaların isimleri görünecek.
				</p>
				<p>
				Bir kurum-konu sayfası kendisiyle alakalı tüm içeriklerin tek elden görüldüğü
				alandır. Sayfa editörünün girdiği en son içerik ise en tepede asılı durmaktadır.
				</p>
				<p>
				Kurum-konu sayfası editörleri sayfa gündemindeki birinci içeriği belirlerler,
				ikinci gündem ise kullanıcıların konuyla ilgili son 24 saatte yazdıkları en vurucu
				ses'tir.
				</p>
				<p>
				Kurum-konu sayfalarının editörleri diledikleri zaman canlı yayın
				düzenleyebilirler. Bu canlı yayını izlemek için sayfayı takip etmen yeterli.
				</p>
				<p>
				Bir sayfanın ilgili olduğu kişiler de o sayfanın biyografisinde görünür.
            	</p>
            </div>
	
			<?php
			die; 
		}//pagecontente taşınıcak 
    }
?>
