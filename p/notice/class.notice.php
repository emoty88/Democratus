<?php
    class notice_plugin extends control{
        public function main()
		{
			global $model, $db;
			//notice  yapılacak
			$model->template="ala";
			$model->view="home";
			$model->title = 'Democratus';
			$model->mode = 0;
                        //main_old kullanılıyor. ya o fonksiyondan sökülecek ya da yeniden yazılacak
                        if($model->paths["1"]=="mini"):
                            return $this->main_old();
                        endif; 
		}
                //main_old kullanılıyor silme!!
        public function main_old(){
            global $model, $db, $l;
            if($model->profileID<1){
            	$model->mode = 0;
            	return $model->redirect('/welcome', 1);
            }
            if($model->userID<1) return;
            
            if($model->paths[1] == 'ajax') return $this->ajax();
            if($model->newDesign)
            $model->initTemplate('beta', 'default');
            else
            $model->initTemplate('v2', 'notice');
            
            $model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
             
            //$model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            //$model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
            //$model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);      
            
            $noticetime = asdatetime( $model->profile->noticetime,'Y-m-d');
            $now=date('Y-m-d');
            $farkGun=$this->fark($now,$noticetime,"-");
            $noticeFark=20;
            if($noticeFark<$farkGun)
            	$noticeFark=$farkGun;
            //echo $noticeFark;
            
            $SELECT = "SELECT n.*, p.name, p.image, p.ID AS pID, permalink";
            $FROM   = "\n FROM notice AS n";
            $JOIN   = "\n JOIN profile AS p ON p.ID=n.fromID";
            $WHERE  = "\n WHERE n.profileID=".$db->quote(intval( $model->profileID ));
            $WHERE  .= "\n and datetime>= DATE_ADD(NOW(), INTERVAL -".$noticeFark." DAY) ";
            $ORDER  = "\n ORDER BY n.ID DESC";
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            
            $rows = $db->loadObjectList();
            $tarihFormati=array ("Bugün","Dün","İki Gün Önce","date");
			
            if(count($rows)){
            	$bg=0;
				$dn=0;
				$go2=0;
				$nTime="";
				$gosterme["dicomment"]=array();
				$gosterme["dicommentcomment"]=array();
				
            	if($model->newDesign)
            	{
            		$yaz="";
            		foreach($rows as $row){
	            	 switch ($row->type) {
	                    case 'dilike':
	                        if($row->subtype == 'dilike1') 
	                        $message = '<i class="icon-thumbs-up"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID2.'"> sesinizi takdir etti.</a>';
	                        elseif($row->subtype == 'dilike2') 
	                        $message = '<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID2.'"> sesinizi saygı duydu.</a>';
	                        else
	                        $message ='<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID2.'"> sesinizi oyladı.</a>';
	                    
	                    break;
	                    case 'redi':
							$message ='<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID2.'"> sesinizi yeniden paylaştı.</a>';
	                    break;
	                    case 'dicomment':
	                    	$db->setQuery("SELECT DISTINCT fromID FROM notice WHERE TYPE = 'dicomment' AND ID3 = '".$row->ID3."'");
		            		$kackisi = count($db->loadObjectList());
		            		if($kackisi>1)
	                        $message = '<i class="icon-comment"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> ve '.($kackisi-1).' kişi daha  sizin bir <a href="/voice/'.$row->ID3.'#'.$row->ID2.'"> paylaşımınıza yorum yaptı.</a>';
	                    	else 
	                    	$message = '<i class="icon-comment"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID3.'#'.$row->ID2.'"> paylaşımınıza yorum yaptı.</a>';
	                    	break; 
	                    case 'dicommentcomment':
	                    	$db->setQuery("SELECT DISTINCT fromID FROM notice WHERE TYPE = 'dicommentcomment' AND ID3 = '".$row->ID3."'");
		            		$kackisi = count($db->loadObjectList());
		            		if($kackisi>1)
	                        $message = '<i class="icon-comment"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> ve '.($kackisi-1).' kişi daha sizin yorum yaptığınız ses için  <a href="/voice/'.$row->ID3.'#'.$row->ID2.'"> yorum yaptı.</a>';
	                    	else
	                    	$message = '<i class="icon-comment"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin yorum yaptığınız ses için  <a href="/voice/'.$row->ID3.'#'.$row->ID2.'"> yorum yaptı.</a>';
	                  	break;
	                    case 'dicommentlike':
	                        //$message = $row->name . ' isimli kullanıcı sizin bir <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> di yorumunuzu oyladı.</a>';                    
	                        
	                        if($row->subtype == 'dilike1') 
	                        $message = '<i class="icon-thumbs-up"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID3.'#'.$row->ID2.'"> yorumunuzu takdir etti.</a>';
	                        elseif($row->subtype == 'dilike2') 
	                        $message = '<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID3.'#'.$row->ID2.'"> yorumunuza saygı duydu.</a>';
	                        else
	                        $message ='<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID3.'#'.$row->ID2.'"> yorumunuzu oyladı.</a>';
	                    break;
	                    case 'follow':
	                        $message = '<i class="icon-eye-open"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizi takip etmeye başladı.';                    
	                    break;
	                    case 'deputy':
	                        $message = '<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> size milletvekili oyu verdi.';                    
	                    break;
	                    case 'proposalvote':
	                        $message = '<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir<a href="/proposal#pp'.$row->ID2.'"> tasarınıza </a> oy verdi.';                    
	                    break;
	                    case 'mentionDi':
	                        $message = '<i class="icon-tag"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin bir <a href="/voice/'.$row->ID3.'"> Sesiniz </a>\'den bahseti.';                    
	                    break;
	                    case 'mentiontoReplied':
	                        $message = '<i class="icon-tag"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> sizin cevapladığınız bir <a href="/di/'.$row->ID3.'"> Ses </a>\'ten bahseti.';                    
	                    break;
	                    case 'mentionProfile':
	                        $message = '<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> bir <a href="/voice/'.$row->ID2.'"> Sesinde </a> Sizden bahseti.';                    
	                    break;
	                    default:
	                        $message = '<i class="icon-user"></i> <a href="/'.$row->permalink.'">'.$row->name.'</a> birşeyler yaptı.';                    
	                  }
	                  $db->setQuery("SELECT ID FROM follow WHERE followerID = ".$model->profileID." AND followingID ='".$row->pID."' and followerstatus=1 and status=1");
	                  $takipediliyormu = count($db->loadObjectList());
	                  //var_dump($takipediliyormu);
	                  if(!($takipediliyormu>0))
	                  {
	                  		$message.='<span id="follow'.$row->pID.'" >Bu kişiyi <a href="javascript:;" rel="'.$row->pID.'" onclick="follow('.$row->pID.');" class="follow-'.$row->pID.'" >Takip Edin</a></span>';
	                  		$message.='<span id="unfollow'.$row->pID.'" class="unfollow-'.$row->pID.'" style="display:none;"> "Artık Takip ediyorsunuz."</span>';
	                  		
	                  }
            		$da=$this->dateParcala($row->datetime);
                    
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
                    	$gosterDate= $this->trTime($row->datetime);
                    	$nTime=$da[1]."-".$da[2];
                    }
                    $html = '
                    		<li>
                    			'.$message.'
        					</li>
                            ';   
                    
                    if($gosterDate!="")
                    $dateLi='<li class="noticeTime" style="color:#962B2B; font-weight:bold;"><i class="icon-calendar"></i> '.$gosterDate.'</li>';
                    else
                    $dateLi="";
            		if($row->type=="dicomment" || $row->type=="dicommentcomment"){
                    	if(!in_array($row->ID3, $gosterme[$row->type]))
                            $yaz.= $dateLi.$html;
                            $gosterme[$row->type][]=$row->ID3;
                    }
                    else 
                    {
                    	$yaz.= $dateLi.$html;
                    }
            	} //foreach($rows as $row){ END
            		if($model->paths["1"]=="mini")
					{
                                                ?>
                                                <style>
                                                   .noticeMini  a {
                                                        color: #555555 !important;
                                                    }
                                                </style>
                                                <?php
						$model->mode=0;
						echo '
							<div class="noticeMini" style="">
								<div class="" style="display:block ; max-height: 400px;  overflow: auto;" >
									'.$yaz.'
								</div>
							</div>';
					}
					else {
						echo '<div class="roundedcontent lastactionscontent">
							<ul class="">
								'.$yaz.'												
							</ul>
						</div>';
					}
            	}// if($model->newDesgin) END
            	else 
            	{
?>
                        <div id="follow_result" class="box">
                            <span class="title_icon">Olaylar</span>    
                            <div class="line_center"></div>
<?php

		
        foreach($rows as $row){
   
                    switch ($row->type) {
                    case 'dilike':
                        if($row->subtype == 'dilike1') 
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID2.'"> paylaşımınızı takdir etti.</a>';
                        elseif($row->subtype == 'dilike2') 
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID2.'"> paylaşımınıza saygı duydu.</a>';
                        else
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID2.'"> paylaşımınızı oyladı.</a>';
                    
                    break;
                    case 'redi':
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID2.'"> paylaşımınızı yeniden paylaştı.</a>';
                    break;
                    case 'dicomment':
                    	$db->setQuery("SELECT DISTINCT fromID FROM notice WHERE TYPE = 'dicomment' AND ID3 = '".$row->ID3."'");
	            		$kackisi = count($db->loadObjectList());
	            		if($kackisi>1)
                        $message = $row->name.' ve '.($kackisi-1).' kişi daha  sizin bir <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> paylaşımınıza yorum yaptı.</a>';
                    	else 
                    	$message = $row->name.' sizin bir <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> paylaşımınıza yorum yaptı.</a>';
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
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> yorumunuzu takdir etti.</a>';
                        elseif($row->subtype == 'dilike2') 
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> yorumunuza saygı duydu.</a>';
                        else
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID3.'#'.$row->ID2.'"> yorumunuzu oyladı.</a>';
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
                        $message = $row->name . ' sizin bir <a href="/di/'.$row->ID3.'"> Sesiniz </a>\'den bahseti. Görmek için <a href="/di/'.$row->ID2.'"> Tıklayınız </a>.';                    
                    break;
                    case 'mentionProfile':
                        $message = $row->name . ' bir <a href="/di/'.$row->ID2.'"> Sesinde </a> Sizden bahseti.';                    
                    break;
                    default:
                        $message = $row->name . ' birşeyler yaptı.';                    
                    }
                    
                    
                    $da=$this->dateParcala($row->datetime);
                    /*
                    echo $da[1];
                    echo "<br/>";
                    echo $da[2];
                    echo "<br/> --";
					echo date("m");
					echo "<br/>";
					echo date("d");
					*/
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
                    	$gosterDate= $this->trTime($row->datetime);
                    	$nTime=$da[1]."-".$da[2];
                    }
                    //var_dump($nTime!=$row->datetime);
                    //echo "<br>";
                    //echo $nTime;
                    //echo "<br>";
                    //echo $row->datetime;
                    $html = '
                              <div class="result" id="profile'.$row->pID.'">
                                <div class="image"><img src="'.$model->getProfileImage($row->image, 50, 50, 'cutout').'" style="width: 50px" /></div>
                                <div class="content">
                                    <div class="head">
                                        <span class="username"><a href="/'.$row->permalink.'">'.$row->name.'</a></span>
                                        <span class="statistic">
                                            <span class="time">'.$this->trTime($row->datetime).'</span>
                                        </span>
                                    </div>
                                    <p>'.$message.'</p>
                                    <span class="mini_about">'.'</span>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                            ';
                    		if($row->type=="dicomment" || $row->type=="dicommentcomment"){
                    		if(!in_array($row->ID3, $gosterme[$row->type]))
                            echo "<span class='noticeTime'>".$gosterDate."</span>".$html;
                            $gosterme[$row->type][]=$row->ID3;
                    		}
                    		else 
                    		{
                    			echo "<span class='noticeTime'>".$gosterDate."</span>".$html;
                    		}

                } //foreach($rows as $row) END
?>                    
                        </div>
<?php
            	}//if($model->newDesign) else End
            } //if(count($rows)) END 
            else {
                echo '<p>hiç yok!</p>';
            }
            
        
            $pr = new stdClass;
            $pr->ID = $model->profileID;
            $pr->noticetime = date('Y-m-d H:i:s');
            
            $db->updateObject('profile', $pr, 'ID');
            
        } // function main () END
        
        function fark($tarih1,$tarih2,$ayrac){
        	$result=0;
        	list($y1,$a1,$g1) = explode($ayrac,$tarih1);
        	list($y2,$a2,$g2) = explode($ayrac,$tarih2);
        	$t1_timestamp = mktime('0','0','0',$a1,$g1,$y1);
        	$t2_timestamp = mktime('0','0','0',$a2,$g2,$y2);
        	if ($t1_timestamp > $t2_timestamp)
        	{
        		$result = ($t1_timestamp - $t2_timestamp) / 86400;
        	}
        	else if ($t2_timestamp > $t1_timestamp)
        	{
        		$result = ($t2_timestamp - $t1_timestamp) / 86400;
        	}
        
        	return $result;
        
        }
       function dateParcala($date)
       {
       		$dateArr=explode(" ", $date);
       		$dateArr=explode("-", $dateArr[0]);
       		return $dateArr;
       }
       function trTime($date)
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
