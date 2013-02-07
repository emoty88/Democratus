<?php
    class di extends voice {
        static public function getdi($ID){
            global $model, $db, $l, $dbez, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, sharer.showdies, sharer.dicomment";
            $FROM   = "\n FROM di";
            $JOIN  = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
            $JOIN .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";
            $WHERE  = "\n WHERE di.ID = " . $db->quote(intval( $ID ));
            
            $WHERE .= "\n AND di.status>0";
            
            $ORDER  = "\n ";
            $LIMIT  = "\n LIMIT 1";
            
           
            $row=$dbez->get_row($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $html = '';
            $htmlNew= '';
			
            if($row){
            	
                    if($row->profileID!=$model->profileID){ //Yazan Kendisi Değil ise Son
                        //if(!profile::isallowed($row->profileID, $row->showdies)) continue;
						if($row->profileID==999 || $row->profileID==988)
						{
							
							$result=1;
						}
						else
						{
							
							$result=$dbez->get_var("select count(*) from follow where followerID='".$row->profileID."'  and followingID='".$model->profileID."' and status='1' ");
							//echo "select * from follow where followerID='1092' and followingID='".$model->profileID."' ";
							//$result = $db->loadResult();
							//var_dump($result);
							//die;
							$SELECT = "\n SELECT count(a.ID)";
							$FROM   = "\n FROM agenda AS a";
							$JOIN   = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote($model->profileID);
							$JOIN  .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
							$WHERE  = "\n WHERE ".$db->quote(date('Y-m-d H:i:s'))." BETWEEN a.starttime AND a.endtime";            
							$WHERE .= "\n AND a.status>0"; 
							$WHERE .= "\n AND a.diID=".$db->quote(intval( $ID ))." ";           

							
							$result2=$dbez->get_var($SELECT.$FROM.$JOIN.$WHERE);	
							
							$result=$result+$result2;
						}
                        $dicompliant = '<span id="dicompliant'.$row->ID.'" onclick="javascript:dicompliant('.$row->ID.')"> Şikayet </span>';
                        $dicompliant = '';
						if($result>0 || $model->profileID=="4575"){
                        $commentit = '<div class="other">
                                <div class="image" style="background: url(\''.$model->getProfileImage( $model->profileimage, 50, 50, 'cutout' ).'\') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name">bir şeyler yaz</span>
										<span style="float:right; margin-left:20px; cursor:pointer;" onclick="notsendnotice('.$model->profileID.','.$ID.');">Bildirim Yapma</span>
                                    </div>
                                    <div class="comment_center">
                                        <textarea id="dicommenttext" rows="5" cols="25"></textarea>
                                        <input type="button" id="dicommentita" value="Yorumla !" />
                                        <p class="character" style=""><span class="number">200</span> Karakter</p>
                                    </div>
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>';
                        	$commentitNew = '<div class=" shareidea">
											<h1><img src="'.TEMPLATEURL.'beta/img/democratus_icon.png"> Cevap Yaz 
											<span>
												<span style="float:right; margin-left:20px; cursor:pointer;" onclick="notsendnotice('.$model->profileID.','.$ID.');">Bildirim Yapma</span>
												<span id="dicommenttextNumber" style="float:none;">200</span> Karakter</span>
											</h1>
											<textarea class="input-xlarge numberSay" id="dicommenttext" rows="3" style="width: 400px; height: 15px;" onfocus="yorumGenis(\'dicommenttext\')" onBlur="yorumDar(\'dicommenttext\')" ></textarea>
								            <button class="btn btn-gonder" id="dicommentita">GÖNDER</button>
										</div>';
                            }
							else
							{
                        		$commentit = '<div class="other">
                                <div class="image" style="background: url(\''.$model->getProfileImage( $model->profileimage, 50, 50, 'cutout' ).'\') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name">Bi saniye!</span>
                                    </div>
                                    <div class="comment_center">
										Söyleşebilmeniz için bu kişi tarafından takip ediliyor olmanız lazım.
                                    </div>
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>';
                        	$commentitNew = '<div class="shareidea">
											<h1><img src="'.TEMPLATEURL.'beta/img/democratus_icon.png"> Bi Saniye</h1>
											<div class="comment_center">
												Söyleşebilmeniz için bu kişi tarafından takip ediliyor olmanız lazım. 
												<br />
												Bunun yerine <a style="" onclick="$(\'.bahset\').toggle(\'fast\');$(\'#shareditext\').focus();" href="javascript:;">Bu Ses\'ten Bahset</a>
		                                    </div>
										</div>';
                        	}
                            if(!profile::isallowed($row->profileID, $row->dicomment)) {
                                $commentit = '';
                            }
                            
                    } else { // Yazan Kendisi ise Son
                        
                        $dicompliant = '';
                        $commentit = '<div class="me">
                                <div class="image" style="background: url(\''.$model->getProfileImage( $model->profileimage, 50, 50, 'cutout' ).'\') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name">bir şeyler yaz</span>
										<span style="float:right; margin-left:20px; cursor:pointer;" onclick="notsendnotice('.$model->profileID.','.$ID.');">Bildirim Yapma</span>
                                    </div>
                                    <div class="comment_center">
                                        <textarea id="dicommenttext" rows="5" cols="25"></textarea>
                                        <input type="button" id="dicommentita" value="Yorumla !" />
                                        <p class="character" style=""><span class="number">200</span> Karakter</p>
                                    </div>
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>';
                        	$commentitNew = '<div class="shareidea">
											<h1><img src="'.TEMPLATEURL.'beta/img/democratus_icon.png"> Cevap Yaz 
											<span>
												<span style="float:none; margin-left:20px; cursor:pointer;" onclick="notsendnotice('.$model->profileID.','.$ID.');">Bildirim Yapma</span>
												<span id="dicommenttextNumber" style="float:none;">200</span> Karakter
											</span>
											</h1>
											<textarea class="input-xlarge numberSay" id="dicommenttext" rows="3" style="width: 400px; height: 15px;" onfocus="yorumGenis(\'dicommenttext\')" onBlur="yorumDar(\'dicommenttext\')" ></textarea>
								            <button class="btn btn-gonder" id="dicommentita">GÖNDER</button>
										</div>';
                    
                    }
                    $likeinfo = di::getlikeinfo( $row->ID );
                    
                    
                    
                    $html .= '
                            <div class="me">
                                <div class="image" style="background: url(\''.$model->getProfileImage( $row->sharerimage, 50, 50, 'cutout' ).'\') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name"><a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a></span>
                                        <span class="time">'.time_since( strtotime( $row->datetime ) ).' önce</span>
                                        '.$dicompliant.'
                                    </div>
                                    <div class="comment_center">'.make_clickable( $row->di ).'</div>
                                    <div class="comment_center" id="dilikeinfo'.$row->ID.'">
                                    <div class="hover">
                                    '.$likeinfo['html'].'
                                    <span title="kaldır / şikayet et" rel="'.$row->ID.'" class="xx">&nbsp;</span>
                                    </div>
                                    
                                    </div>
                                    
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>';
            		if($row->initem=="1"){
						$initElement='<a href="javascript:voiceDetail('.$row->ID.')"><img src="/images/iPhoto.png" /> Resmi Göster</a>';
					}
					else{
						$initElement='';
					}   
              $htmlNew .= '<div class="forum-item">
							<div class="forum-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
							<div class="forum-ears">
								<div class="forum-roundedcontent">
								<div class="forum-info">
									<table class="table-striped" style="width:100%">
										<tbody><tr>
											<th><h1><span><a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a></span> <a href="#">'.time_since( strtotime( $row->datetime ) ).' Önce</a> 
											<span title="kaldır / şikayet et" rel="'.$row->ID.'" class="x diSikayet" style="float:right;">&nbsp;</span>
											<div id="dilikeinfo'.$row->ID.'" class="dilikeinfo">
		                                    '.$likeinfo['html'].'
		                                    </div>
		                                    </h1>
											'.$dicompliant.'
											</th>
										</tr>
										<tr>
											<td><p>'.make_clickable( $row->di ).'</p></td>
										</tr>
									</tbody></table>
										<input type="hidden" id="openStatus-'.$row->ID.'-1212" name="openStatus-'.$row->ID.'" value="0" />
        								<input type="hidden" id="initem-'.$row->ID.'-1212" name="initem-'.$row->ID.'" value="'.$row->initem.'" />
        								<input type="hidden" id="itemLoaded-'.$row->ID.'-1212" name="itemLoaded-'.$row->ID.'" value="0" />
									'.$initElement.'
									<div id="di_subArea-'.$row->ID.'-1212" style="display:none;">
										<div id="di_subAreaConten-'.$row->ID.'-1212"></div>
									</div>
								</div>
								</div>
							</div>
						</div>';
                if($model->newDesign)
                {
                	$response['html'] = $htmlNew;
                	$response['commentit'] = $commentitNew;
                }
                else
                {
                	$response['html'] = $html;
                	$response['commentit'] = $commentit;
                }
                
                $response['count'] = 1;
                $response['start'] = $row->ID;
                $response['row'] = $row;
                
            } else {
                
                $response['html'] = 'başka yok!';
                $response['commentit'] = '';
                $response['count'] = 0;
                $response['start'] = '"none"';
                $response['row'] = '';
                
            }
            
            
            
            
            
            
            return $response;
        }  
        public function get_singleDi($ID,$inRounded=0,$replyArea=1){
            global $model, $db, $l, $dbez, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, sharer.showdies, sharer.dicomment";
            $FROM   = "\n FROM di";
            $JOIN  = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
            $JOIN .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";
            $WHERE  = "\n WHERE di.ID = " . $db->quote(intval( $ID ));
            
            $WHERE .= "\n AND di.status>0";
            
            $ORDER  = "\n ";
            $LIMIT  = "\n LIMIT 1";
            
           
            $row=$dbez->get_row($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $html = '';
            $htmlNew= '';
			
            if($row){
           		$likeinfo = di::getlikeinfo( $row->ID );
                
				$initElement="";
				//($row=null,$deputyinfo="",$redier="",$genelID=0,$dicomment_count=0,$initElement="",$likeinfo,$inRounded=1)
				$htmlNew.=self::get_voiceHtmlNew($row,"","",0,"",$initElement,$likeinfo,$inRounded,$replyArea);
   
                $response['html'] = $htmlNew;
                $response['count'] = 1;
                $response['start'] = $row->ID;
                $response['row'] = $row;
                
            } else {
                
                $response['html'] = 'başka yok!';
                $response['commentit'] = '';
                $response['count'] = 0;
                $response['start'] = '"none"';
                $response['row'] = '';
                
            }
            
            
            
            
            
            
            return $response;
        }  
		static public function getdiMobile($ID){
            global $model, $db,$dbez, $l, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, sharer.showdies, sharer.dicomment";
            $FROM   = "\n FROM di";
            $JOIN  = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
            $JOIN .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
            
            $WHERE  = "\n WHERE di.ID = " . $db->quote(intval( $ID ));
            
            $WHERE .= "\n AND di.status>0";
            
            $ORDER  = "\n ";
            $LIMIT  = "\n LIMIT 1";
            
            $row=$dbez->get_row($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            
            $html = '';
            if($row){

                    if($row->profileID!=$model->profileID){
                        //if(!profile::isallowed($row->profileID, $row->showdies)) continue;
						if($row->profileID==999 || $row->profileID==988)
						{
							
							$result=1;
						}
						else
						{
							$db->setQuery("select count(*) from follow where followerID='".$row->profileID."'  and followingID='".$model->profileID."' and status='1' ");
							//echo "select * from follow where followerID='1092' and followingID='".$model->profileID."' ";
							$result = $db->loadResult();
							$SELECT = "\n SELECT count(a.ID)";
							$FROM   = "\n FROM agenda AS a";
							$JOIN   = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote($model->profileID);
							$JOIN  .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
							$WHERE  = "\n WHERE ".$db->quote(date('Y-m-d H:i:s'))." BETWEEN a.starttime AND a.endtime";            
							$WHERE .= "\n AND a.status>0"; 
							$WHERE .= "\n AND a.diID=".$db->quote(intval( $ID ))." ";           

							
							$db->setQuery($SELECT.$FROM.$JOIN.$WHERE);	
							//echo ($SELECT.$FROM.$JOIN.$WHERE);	
							$result2 = $db->loadResult();
							$result=$result+$result2;
						}
                        $dicompliant = '<span id="dicompliant'.$row->ID.'" onclick="javascript:dicompliant('.$row->ID.')"> Şikayet </span>';
                        $dicompliant = '';
						if($result>0){
                        $commentit = ' <div class="comment">
                                    <div class="comment_center">
                                    	<span class="character" style="float:right; padding:0; margin:0;"><span class="number">200</span> Karakter</span>
                                    	<div style="clear:both;"></div>
                                        <textarea id="dicommenttext" rows="5" cols="25" ></textarea>
                                        <input type="button" id="dicommentita" value="Yorumla !" onclick="comment2Di('.$row->ID.');" />
                                    </div>
                                </div>';
                            }
							else
							{
                        		$commentit = '
                                    <div class="comment_center">
										Söyleşebilmeniz için bu kişi tarafından takip ediliyor olmanız lazım.
                                    </div>';
                        	}
                            if(!profile::isallowed($row->profileID, $row->dicomment)) {
                                $commentit = '';
                            }
                            
                    } else {
                        
                        $dicompliant = '';
                        $commentit = '
                                <div class="comment">
                                    <div class="comment_center">
                                    	<span class="character" style="float:right; padding:0; margin:0;"><span class="number">200</span> Karakter</span>
                                    	<div style="clear:both;"></div>
                                        <textarea id="dicommenttext" rows="5" cols="25" ></textarea>
                                        <input type="button" id="dicommentita" value="Yorumla !" onclick="comment2Di('.$row->ID.');" />
                                    </div>
                                </div>
                                ';
                    
                    }
                    $likeinfo = di::getlikeinfo( $row->ID );
                    $html .= '
                                <div class="ui-body ui-body-a" style="margin-top:10px; background-color:#ff0000;">
                                        <h4 style="margin:.5em 0"><a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a>
                                        <span class="time">'.time_since( strtotime( $row->datetime ) ).' önce</span></h4>
                                        '.$dicompliant.'
                                    <p>'.make_clickable( $row->di ).'</p>
								</div>';
                                  
                $response['html'] = $html;
                $response['commentit'] = $commentit;
                $response['count'] = 1;
                $response['start'] = $row->ID;
                $response['row'] = $row;
            } else {
                $response['html'] = 'başka yok!';
                $response['commentit'] = '';
                $response['count'] = 0;
                $response['start'] = '"none"';
                $response['row'] = '';
                
            }
            return $response;
        }  
        static public function getNewdies($profileID=0, $first = 0, $type="follow",$onlyProfile=0)
        {
        	global $model, $db, $l, $LIKETYPES;
        	$SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
            $FROM   = "\n FROM di";
            $JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
            
            if(intval($profileID)<1){
                $JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";                
                $WHERE  = "\n WHERE  ( ";
                $WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
				if($type=="follow" || $type=="all")
				$WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
				elseif($type=="deputy"  || $type=="all")
				$WHERE .= "\n OR ( sharer.deputy>0)"; //millet vekilleri
                $WHERE .= "\n OR ( di.profileID<1000 ))"; //democratus profili
                //$WHERE .= "\n AND f.status>0";
            } else {
                $WHERE  = "\n WHERE di.profileID = " . $db->quote(intval( $profileID ));
            }
            
            $WHERE .= "\n AND di.ID>" . $db->quote($first);
            
            
            $WHERE .= "\n AND di.status>0";
            if($onlyProfile==0)
        		$WHERE .= "\n AND onlyProfile='0'";
            $ORDER  = "\n ORDER BY di.ID DESC";
            $LIMIT  = "\n ";
           
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            $html = '';
            $htmlNew='';
            if(count($rows)){
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
						$initElement='&nbsp;&nbsp;&nbsp;<img src="/images/iPhoto.png" />';
					}
					else{
						$initElement='';
					}
                   $htmlNew.=self::get_voiceHtmlNew($row,$deputyinfo,$redier,$genelID,$dicomment_count,$initElement,$likeinfo);
                    
                }
                if($model->newDesign)
                $html = '<div id="wall' . $row->ID . '">' . $htmlNew . '</div>';
                else
                $html = '<div id="wall' . $row->ID . '">' . $html . '</div>';
                
                $response['html'] = $html;
                $response['count'] = count($rows);
                $response['first'] = $rows[0]->ID;
                
            } else {
                
                $response['html'] = '<a href="javascript:;"> başka yok!</a>';
                $response['count'] = 0;
                $response['start'] = 'none';
                
            }

            return $response;
        }
        static public function getdies($profileID=0, $start = 0, $limit = config::dilimit , $type="follow",$onlyProfile=0){
        	global $model, $db, $l, $LIKETYPES;
        	
        	$SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
        	$FROM   = "\n FROM di";
        	$JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
        	$JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
        
        	if(intval($profileID)<1){
        		$JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";
        		$WHERE  = "\n WHERE  ( ";
        		$WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
        		if($type=="follow" || $type=="all")
        			$WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
        		elseif($type=="deputy"  || $type=="all")
        		$WHERE .= "\n OR ( sharer.deputy>0)"; //millet vekilleri
        		$WHERE .= "\n OR ( di.profileID<1000 ))"; //democratus profili
        		
        		//$WHERE .= "\n AND f.status>0";
        	} else {
        	$WHERE  = "\n WHERE di.profileID = " . $db->quote(intval( $profileID ));
        	}
        
        	if($start>0){
        	$WHERE .= "\n AND di.ID<" . $db->quote($start);
        }
        
        $WHERE .= "\n AND di.status>0";
        if($onlyProfile==0)
        		$WHERE .= "\n AND onlyProfile='0'";
        $ORDER  = "\n ORDER BY di.ID DESC";
        $LIMIT  = "\n LIMIT $limit";
        
        $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
        
        //echo $db->_sql;
        
        $rows = $db->loadObjectList();
        $html = '';
        $htmlNew='';
        if(count($rows)){
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
						$initElement='&nbsp;&nbsp;&nbsp;<img src="/images/iPhoto.png" />';
					}
					else{
						$initElement='';
					}
					
        			$htmlNew.=self::get_voiceHtmlNew($row,$deputyinfo,$redier,$genelID,$dicomment_count,$initElement,$likeinfo);
        			
        }

        									$html = '<div id="wall' . $row->ID . '">' . $htmlNew . '</div>';

        
        									$response['html'] = $html;
        									$response['count'] = count($rows);
        									$response['start'] = $row->ID;
        									$response['first'] = $rows[0]->ID;
        
        } else {
        
        									$response['html'] = '<a href="javascript:;"> başka yok!</a>';
        									$response['count'] = 0;
        									$response['start'] = 'none';
        
        }
        
        									return $response;
        }
        static public function getCagrilarDies($profileID=0, $start = 0, $limit = config::dilimit){
            global $model, $db, $l, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
            $FROM   = "\n FROM di";
            $JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
            $JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID"; 
            $WHERE  = "\n WHERE  di.di like '%href=\"/profile/".$profileID."\"%' ";
            if($start>0){
                $WHERE .= "\n AND di.ID<" . $db->quote($start);
            }
            $WHERE .= "\n AND di.status>0";
            $ORDER  = "\n ORDER BY di.ID DESC";
            $LIMIT  = "\n LIMIT $limit";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            
            //echo $db->_sql;
            
            $rows = $db->loadObjectList();
            $html = '';
            $htmlNew='';
            if(count($rows)){
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
                    
     
                    $htmlNew.=self::get_voiceHtmlNew($row,$deputyinfo,$redier,$genelID,$dicomment_count,$initElement,$likeinfo);
                }
              
                $html = '<div id="wall' . $row->ID . '">' . $htmlNew . '</div>';
              
                $response['html'] = $html;
                $response['count'] = count($rows);
                $response['start'] = $row->ID;
                $response['first'] = $rows[0]->ID;
                
            } else {
                if($start==0)
                	$response['html'] = '<p></p><a href="javascript:;"> Henüz bir çağrınız yok.</a>';
                else
                	$response['html'] = '<p></p><a href="javascript:;"> Başka çağrınız yok.</a>';
                $response['count'] = 0;
                $response['start'] = 'none';
                
            }
            
            return $response;
        }
		static public function getdiesMobile($profileID=0, $start = 0, $limit = config::dilimit, $type="follow"){
            global $model, $db, $l, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
            $FROM   = "\n FROM di";
            $JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
            
            if(intval($profileID)<1){
                $JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";                
                $WHERE  = "\n WHERE  ( ";
                $WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
				if($type=="follow" || $type=="all")
				$WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
				elseif($type=="deputy" || $type=="all")
				$WHERE .= "\n OR ( sharer.deputy>0)"; //millet vekilleri
                $WHERE .= "\n OR ( di.profileID<1000 ))"; //democratus profili
                //$WHERE .= "\n AND f.status>0";
            } else {
                $WHERE  = "\n WHERE di.profileID = " . $db->quote(intval( $profileID ));
            }
            
            if($start>0){
                $WHERE .= "\n AND di.ID<" . $db->quote($start);
            }
            
            $WHERE .= "\n AND di.status>0";
            
            $ORDER  = "\n ORDER BY di.ID DESC";
            $LIMIT  = "\n LIMIT $limit";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            
            //echo $db->_sql;
            
            $rows = $db->loadObjectList();
            $html = '';
            if(count($rows)){
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
                    $likeinfo = di::getlikeinfoMobile($genelID);
                    
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
                    			
				
	
                    $html .= '
                    
                         <div class="ui-body ui-body-a" style="margin-top:10px;">
                            <h4 style="margin:.5em 0">'.$deputyinfo.' <a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a> '.$redier.' </h4>
                               <p>'.make_clickable( $model->splitword(  $row->di , 48) ).'</p>

								
								<span>'.$likeinfo['html'].'</span>
								<span style="float:right;">
								<a id="soyles" href="/mobile/di/'.$row->ID.'" data-role=""  >Söyleş</a> 
								</span>
								
								
                        </div>                    
                    
                    ';
                }
                
                $html = '<div id="wall' . $row->ID . '">' . $html . '</div>';
                
                $response['html'] = $html;
                $response['count'] = count($rows);
                $response['start'] = $row->ID;
                
            } else {
                
                $response['html'] = '<a href="javascript:;"> başka yok!</a>';
                $response['count'] = 0;
                $response['start'] = 'none';
                
            }
            
            return $response;
        }
        static public function getdiNewUser(){
            global $model, $db, $l, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
            $FROM   = "\n FROM di";
            $JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
                
            $WHERE = "\n where di.profileID in (998)";
            $ORDER  = "\n ORDER BY di.ID ASC";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER );
            
            //echo $db->_sql;
            
            $rows = $db->loadObjectList();
            $html = '';
            if(count($rows)){
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
                    
                    $html .= '
                    
                         <div class="box">
                            
                            <div class="idea di '.$isdeputy.' '.$redi.'" id="di'.$row->ID.'">
                                <img src="'.$model->getProfileImage( $row->sharerimage, 50, 50, 'cutout' ).'" class="image" alt="" />
                                <div class="content">
                                    <div class="top">
                                        <span class="name">'.$deputyinfo.' <a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a> '.$redier.' </span>
                                        
                                        <span class="statistic_tip">
                                            <span id="dilikeinfo'.$genelID.'"> '.$likeinfo['html'].' </span>
                                            '.$delete.'
                                        </span>
                                        <div class="clear"></div>
                                    </div>
                                    
                                    <div class="line_center"></div>
                                    <div class="bottom">
                                        <p>'.make_clickable( $model->splitword(  $row->di , 48) ).'</p>
                                        <div class="time_comment">
                                            <span class="time">'.time_since( strtotime( $row->datetime ) ).' önce</span>
                                            <span class="comment"><a href="/di/'.$genelID.'">Söyleş'.$dicomment_count.'</a></span>
                                            <span class="share"><a href="javascript:redi('.$genelID.')">Paylaş</a></span>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="clear"></div>
                            
                        </div>                    
                    
                    ';
                }
                
                $html = '<div id="wall' . $row->ID . '">' . $html . '</div>';
                
                $response['html'] = $html;
                $response['count'] = count($rows);
                $response['start'] = $row->ID;
                
            } else {
                
                $response['html'] = '<a href="javascript:;"> başka yok!</a>';
                $response['count'] = 0;
                $response['start'] = 'none';
                
            }
            
            return $response;
        }
        static public function getlikeinfo($ID){
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
                            $dilikecount = ' ('.$like->$liketype.')';
                        else
                            $dilikecount = '';

                        $result .= '<span id="'.$liketype.'_'.$ID.'" class="dilike '.$liketype.'" onclick="javascript:dilikeCancel('.$ID.')">'.$l[$liketype.'liked'].$dilikecount.'</span> '.'';
                        
                    //benim seçimim değil ise    
                    } else {
                        if(intval( $like->$liketype )>0)
                            $dilikecount = ' ('.$like->$liketype.') ';
                        else
                            $dilikecount = '';
                        
                        $result .= '<span id="'.$liketype.'_'.$ID.'" class="dilike '.$liketype.'" onclick="javascript:dilike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].$dilikecount.'</span> '.'';
                        
                    }

  
                } else {
                    $result .= '<span id="'.$liketype.'_'.$ID.'" class="dilike '.$liketype.'" onclick="javascript:dilike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].'</span> ';
                    
                }
                
            }
            $response['result'] = 'success';
            $response['html'] = $result;
            
            return $response;
        }
      static public function getlikeinfoMobile($ID){
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
            $sep=true;
            foreach($LIKETYPES as $liketype){
                if(!is_null( $like )){//her hangi bir like bulunamadı ise
                    //Takdir et vs'yi yaz
                    
                    
                    //benim seçimim ise
                    if( !is_null($mylike) && intval( $mylike->$liketype ) > 0){
                        if(intval( $like->$liketype )>1)
                            $dilikecount = ' ('.$like->$liketype.')';
                        else
                            $dilikecount = '';

                        $result .= '<a id="'.$liketype.'_'.$ID.'" class="dilike '.$liketype.'">'.$l[$liketype.'liked'].$dilikecount.'</a> '.'';
                        
                        if($sep)
                        {
                        	$result.=" | ";
                        	$sep=false;
                        }
                    //benim seçimim değil ise    
                    } else {
                        if(intval( $like->$liketype )>0)
                            $dilikecount = ' ('.$like->$liketype.') ';
                        else
                            $dilikecount = '';
                        
                        $result .= '<a id="'.$liketype.'_'.$ID.'" class="dilike '.$liketype.'" onclick="javascript:dilike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].$dilikecount.'</a> '.'';
                    	if($sep)
                        {
                        	$result.=" | ";
                        	$sep=false;
                        }
                    }

  
                } else {
                    $result .= '<a id="'.$liketype.'_'.$ID.'" class="dilike '.$liketype.'" onclick="javascript:dilike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].'</a> ';
                	if($sep)
                        {
                        	$result.=" | ";
                        	$sep=false;
                        }
                }
                
            }
            $response['result'] = 'success';
            $response['html'] = $result;
            
            return $response;
        }
        static public function getcommentlikeinfo($ID){
            global $model, $db, $l, $LIKETYPES;
            
            //mylike'yi bul
            $db->setQuery('SELECT * FROM dicommentlike  WHERE profileID='.$db->quote($model->profileID).' AND dicID = ' . $db->quote($ID) );
            $mylike = null;
            if($db->loadObject($mylike)){
                
            } else {
                $mylike = null;
            }
           
            //like'yi bul
            foreach($LIKETYPES as $liketype)
                $q[] = ' SUM('.$liketype.') AS '.$liketype;
            
            $q = implode(',', $q);
            
            
            $db->setQuery( 'SELECT ' . $q . ' FROM dicommentlike  WHERE dicID = ' . $db->quote($ID));
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
                            $diclikecount = ' ('.$like->$liketype.')';
                        else
                            $diclikecount = '';

                        $result .= '<span id="dc'.$liketype.'_'.$ID.'" class="diclike" onclick="javascript:dilikeCommentCancel('.$ID.')">'.$l[$liketype.'liked'].$diclikecount.'</span> '.'';
                        
                    //benim seçimim değil ise    
                    } else {
                        if(intval( $like->$liketype )>0)
                            $diclikecount = ' ('.$like->$liketype.') ';
                        else
                            $diclikecount = '';
                        
                        $result .= '<span id="dc'.$liketype.'_'.$ID.'" class="diclike" onclick="javascript:diclike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].$diclikecount.'</span> '.'';
                        
                    }

  
                } else {
                    $result .= '<span id="dc'.$liketype.'_'.$ID.'" class="diclike" onclick="javascript:diclike('.$ID.',\''.$liketype.'\')">'.$l[$liketype].'</span> ';
                    
                }
                
            }
            $response['result'] = 'success';
            $response['html'] = $result;
            
            return $response;
        }
        
        static public function getdicomments($ID=0, $start = 0, $limit = 7){
            global $model, $db, $dbez, $l, $LIKETYPES;

            $db->setQuery('SELECT * FROM di WHERE ID=' . $db->quote(intval($ID)). ' AND status>0');
            $dirow=null;
            if($db->loadObject($dirow)){
            
                $SELECT = "SELECT DISTINCT dc.*, sharer.image AS sharerimage, sharer.name AS sharername, sharer.ID AS sharerID";
                $FROM   = "\n FROM dicomment AS dc";
                $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = dc.profileID";
                
                $WHERE  = "\n WHERE dc.diID = " . $db->quote($ID);
                $WHERE .= "\n AND dc.status>0";

                if($start>0){
                    $WHERE .= "\n AND dc.ID > " . $db->quote($start);
                }
                
                $ORDER  = "\n ORDER BY dc.ID ASC";
                $LIMIT  = "\n ";
                
                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
                $rows = $db->loadObjectList();
                $html = '';
                $htmlNew='';
                if(count($rows)){
                    foreach($rows as $row){
                    	
                        if($row->profileID==$dirow->profileID) 
                            $owner = 'me';
                        else
                            $owner = 'other';
                        
                        $likeinfo = di::getcommentlikeinfo($row->ID);
                        
                        $hover = '<span class="hover">';
                        $hover.= '<span id="diclikeinfo'.$row->ID.'">'.$likeinfo['html'].'</span>';
                        $hover.= '<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et">&nbsp;</span>';
                        $hover.= '</span>';
 						
                        
                        $hoverNew=$likeinfo['html'];
                        $hoverNew.='';
                        $html .= '
                                <div class="'.$owner.'">
                                <div class="image" style="background: url(\''.$model->getProfileImage( $row->sharerimage, 50, 50, 'cutout' ).'\') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name"><a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a><a name="'.$row->ID.'">&nbsp;</a></span>
                                        <span class="time">'.time_since( strtotime( $row->datetime ) ).' önce</span>
                                        
                                    </div>
                                    <div class="comment_center"><div style="margin:0 10px; overflow:hidden;">'.make_clickable( $row->comment ).'</div></div>
                                    <div class="comment_submenu">'.$hover.'</div>
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>';
                        if($owner=="me")
                        {
                        	$htmlNew .= '
		                        <div class="forum-response_me">
		                        <a name="'.$row->ID.'"></a>
		                        <div class="forum-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
									<div class="forum-ears">
										<div class="forum-roundedcontent">
										<div class="forum-info">
											<table class="table-striped" style="width:100%" >
												<tbody>
												<tr>
													<th>
														<h1>
															<span>
																<a href="/profile/'.$row->profileID.'">'.$row->sharername.' </a>
															</span> 
															<a href="#">'.time_since( strtotime( $row->datetime ) ).' Önce</a> 
															<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et" style="float:right;">&nbsp;</span>
															<div id="diclikeinfo'.$row->ID.'" class="dilikeinfo">'.$hoverNew.'</div>
														</h1>
													</th>
												</tr>
												<tr>
													<td><p>'.make_clickable( $row->comment ).'</p></td>
												</tr>
											</tbody></table>
										</div>
										</div>
									</div>
								</div>';
                        }
                        else 
                        {
	                        $htmlNew .= '
		                        <div class="forum-response">
		                        <a name="'.$row->ID.'"></a>
									<div class="forum-ears">
										<div class="forum-roundedcontent">
										<div class="forum-info">
											<table class="table-striped" style="width:100%" >
												<tbody><tr>
													<th>
														<h1>
															<span>
																<a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a>
															</span> 
															<a href="#">'.time_since( strtotime( $row->datetime ) ).' Önce</a> 
															<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et" style="float:right;">&nbsp;</span>
															<div id="diclikeinfo'.$row->ID.'" class="dilikeinfo">'.$hoverNew.'</div>
														</h1>
													</th>
												</tr>
												<tr>
													<td><p>'.make_clickable( $row->comment ).'</p></td>
												</tr>
											</tbody></table>
										</div>
										</div>
									</div>
									<div class="forum-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
								</div>';
                        }
                        
                    }
                    
                    if($model->newDesign)
                    $response['html'] = $htmlNew;
                    else
                    $response['html'] = $html;
                    $response['count'] = count($rows);
                    $response['start'] = $row->ID;
                    
                } else {
                    
                    $response['html'] = '';
                    $response['count'] = 0;
                    $response['start'] = 'none';
                    
                }
            } else {
                $response['html'] = '';
                $response['count'] = 0;
                $response['start'] = 'none';
            }
            
            
            
            
            
            
            return $response;
        }
        static public function get_voiceReply($ID=0, $start = 0, $limit = 3,$replyArea=1){
            global $model, $db, $dbez, $l, $LIKETYPES;

            $db->setQuery('SELECT * FROM di WHERE ID=' . $db->quote(intval($ID)). ' AND status>0');
            $dirow=null;
			$html="";
            if($db->loadObject($dirow)){
            
                $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, sharer.ID AS sharerID";
                $FROM   = "\n FROM di AS di";
                $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
                
                $WHERE  = "\n WHERE di.replyID = " . $db->quote($ID);
                $WHERE .= "\n AND di.status>0";

                if($start>0){
                    $WHERE .= "\n AND di.ID < " . $db->quote($start);
                }
                
                $ORDER  = "\n ORDER BY di.ID desc";
                $LIMIT  = "\n LIMIT $limit";
                
                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
                $rows = $db->loadObjectList();
				
					
                //$html=self::get_voiceCommentHtml($rows,$dirow);
				//yapıştır
				$rows = array_reverse($rows);
				 foreach($rows as $row){
	        		$isdeputy = '';
	        		$deputyinfo = '';
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
	         		
	        		$delete = '<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et"></span>';
	        
	        		$dicomment_count =  di::getdicomment_count($genelID);
	        		if($dicomment_count>0)
	        			$dicomment_count = ' ('.$dicomment_count.') ';
	        		else
	        			$dicomment_count = '';
	        			
					if($row->initem=="1"){
						$initElement='&nbsp;&nbsp;&nbsp;<img src="/images/iPhoto.png" />';
					}
					else{
						$initElement='';
					}
					
	        		$html.=self::get_voiceHtmlNew($row,$deputyinfo,$redier,$genelID,$dicomment_count,$initElement,$likeinfo,1,$replyArea);
	        			
	        }
				//yapıştır sonu 
				$response['html'] = $html;
                $response['count'] = count($rows);
                $response['stop'] = count($rows)>0 ? $rows[count($rows)-1]->ID : 0 ;
                $response['start'] = count($rows)>0 ? $rows[0]->ID : 0 ;
				
            } else {
                $response['html'] = '';
                $response['count'] = 0;
                $response['start'] = 'none';
            }
            return $response;
        }
    static public function getdicommentsMobile($ID=0, $start = 0, $limit = 7){
            global $model, $db, $l, $LIKETYPES;

            $db->setQuery('SELECT * FROM di WHERE ID=' . $db->quote(intval($ID)). ' AND status>0');
            $dirow=null;
            if($db->loadObject($dirow)){
            
                $SELECT = "SELECT DISTINCT dc.*, sharer.image AS sharerimage, sharer.name AS sharername, sharer.ID AS sharerID";
                $FROM   = "\n FROM dicomment AS dc";
                $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = dc.profileID";
                
                $WHERE  = "\n WHERE dc.diID = " . $db->quote($ID);
                $WHERE .= "\n AND dc.status>0";

                if($start>0){
                    $WHERE .= "\n AND dc.ID > " . $db->quote($start);
                }
                
                $ORDER  = "\n ORDER BY dc.ID ASC";
                $LIMIT  = "\n ";
                
                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
                $rows = $db->loadObjectList();
                $html = '';
                if(count($rows)){
                    foreach($rows as $row){
                        if($row->profileID==$dirow->profileID) 
                            $owner = 'me';
                        else
                            $owner = 'other';
                        
                        $likeinfo = di::getcommentlikeinfo($row->ID);
                        
                        $hover = '<span class="hover">';
                        $hover.= '<span id="diclikeinfo'.$row->ID.'">'.$likeinfo['html'].'</span>';
                        $hover.= '<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et">&nbsp;</span>';
                        $hover.= '</span>';
 						 
                        $html .= '
                        <div class="ui-body ui-body-a" style="margin-top:10px; background-color:#ff0000;">
                                        <h4 style="margin:.5em 0"><a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a>
                                        <span class="time">'.time_since( strtotime( $row->datetime ) ).' önce</span></h4>
                                    <p>'.make_clickable( $row->comment ).'</p>
								</div>';
                    }
                    
                    
                    $response['html'] = $html;
                    $response['count'] = count($rows);
                    $response['start'] = $row->ID;
                    
                } else {
                    
                    $response['html'] = '';
                    $response['count'] = 0;
                    $response['start'] = 'none';
                    
                }
            } else {
                $response['html'] = '';
                $response['count'] = 0;
                $response['start'] = 'none';
            }
            
            
            
            
            
            
            return $response;
        }
        
        static public function getdicomments_old($ID=0, $start = 0, $limit = 7){
            global $model, $db, $l, $LIKETYPES;

            $db->setQuery('SELECT * FROM di WHERE ID=' . $db->quote(intval($ID)). ' AND status>0');
            $dirow=null;
            if($db->loadObject($dirow)){
            
                $SELECT = "SELECT DISTINCT dc.*, sharer.image AS sharerimage, sharer.name AS sharername, sharer.ID AS sharerID";
                $FROM   = "\n FROM dicomment AS dc";
                $JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = dc.profileID";
                
                $WHERE  = "\n WHERE dc.diID = " . $db->quote($ID);
                $WHERE .= "\n AND dc.status>0";

                if($start>0){
                    $WHERE .= "\n AND dc.ID > " . $db->quote($start);
                }
                
                $ORDER  = "\n ORDER BY dc.ID ASC";
                $LIMIT  = "\n ";
                
                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
                $rows = $db->loadObjectList();
                $html = '';
                if(count($rows)){
                    foreach($rows as $row){
                        if($row->profileID==$dirow->profileID) 
                            $owner = 'owner';
                        else
                            $owner = 'notowner';
                        
                        
                        $html .= '<div id="dicomment'.$row->ID.'" class="dicomment '.$owner.'">';
                        $html .= $row->ID;
                        
                        
                        $html .= '<img src="'.$model->getProfileImage( $row->sharerimage, 32, 32, 'cutout' ).'" alt="" width="32" height="32" align="middle" />';
                        
                        $html .= '<div id="dicommenttext'.$row->ID.'" class="dicommenttext">';
                        $html .= $row->comment;
                        $html .= '</div><!-- dicommenttext END-->';
                        
                        $html .= '<div id="dicommentinfo'.$row->ID.'" class="dicommentinfo">';
                        
                        $html .= '<strong>'.$row->sharername.'</strong> tarafından <strong>'.$row->datetime.'</strong> tarihinde ekledi';
                        
                        $html .= '</div><!--dicommentinfo END-->';
                        
                        $html .= '</div><!-- diomment END-->';
                    }
                    
                    
                    $response['html'] = $html;
                    $response['count'] = count($rows);
                    $response['start'] = $row->ID;
                    
                } else {
                    
                    $response['html'] = 'Gösterilecek yorum bulunamadı';
                    $response['count'] = 0;
                    $response['start'] = 'none';
                    
                }
            } else {
                $response['html'] = 'Gösterilecek yorum bulunamadı';
                $response['count'] = 0;
                $response['start'] = 'none';
            }
            
            
            
            
            
            
            return $response;
        }
        
        static public function getdicomment($ID){
            global $model, $db, $l, $LIKETYPES;
            
                $SELECT = "SELECT DISTINCT dc.*, sharer.image AS sharerimage, sharer.name AS sharername, sharer.ID AS sharerID";
                $FROM   = "\n FROM dicomment AS dc";
                $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = dc.profileID";
                
                $WHERE  = "\n WHERE dc.ID = " . $db->quote($ID);
                $WHERE .= "\n AND dc.status>0";
                
                $ORDER  = "\n ";
                $LIMIT  = "\n LIMIT 1";
                
                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
                $rows = $db->loadObjectList();
                $html = '';
                $htmlNew="";
                if($db->loadObject($row)){
                    //foreach($rows as $row){
                        
                        $db->setQuery('SELECT * FROM di WHERE ID=' . $db->quote(intval($row->diID)). ' AND status>0');
                        $dirow=null;
                        if($db->loadObject($dirow)){
                            if($row->profileID==$dirow->profileID) 
                                $owner = 'me';
                            else
                                $owner = 'other';
                            
                            //die($owner);
                            /*
                            $html .= '<div id="dicomment'.$row->ID.'" class="dicomment '.$owner.'">';
                            $html .= $row->ID;
                            
                            
                            $html .= '<img src="'.$model->getProfileImage( $row->sharerimage, 32, 32, 'cutout' ).'" alt="" width="32" height="32" align="middle" />';
                            
                            $html .= '<div id="dicommenttext'.$row->ID.'" class="dicommenttext">';
                            $html .= $row->comment;
                            $html .= '</div><!-- dicommenttext END-->';
                            
                            $html .= '<div id="dicommentinfo'.$row->ID.'" class="dicommentinfo">';
                            
                            $html .= '<strong>'.$row->sharername.'</strong> tarafından <strong>'.$row->datetime.'</strong> tarihinde ekledi';
                            
                            $html .= '</div><!--dicommentinfo END-->';
                            
                            $html .= '</div><!-- diomment END-->';
                            */
                            $html .= '
                                <div class="'.$owner.'">
                                <div class="image" style="background: url('.$model->getProfileImage( $row->sharerimage, 50, 50, 'cutout' ).') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name">'.$row->sharername.'</span>
                                        <span class="time"><strong>'.$row->datetime.'</strong> tarihinde ekledi</span>
                                        
                                    </div>
                                    <div class="comment_center">'.$row->comment.'</div>
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>';
                            
                        if($owner=="me")
                        {
                        	$htmlNew .= '
		                        <div class="forum-response_me">
		                        <div class="forum-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
									<div class="forum-ears">
										<div class="forum-roundedcontent">
										<div class="forum-info">
											<table class="table-striped" style="width:100%" >
												<tbody><tr>
													<th><h1><span><a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a></span> <a href="#">'.time_since( strtotime( $row->datetime ) ).' Önce</a> <!-- İstanbul, 35 --></h1></th>
												</tr>
												<tr>
													<td><p>'.make_clickable( $row->comment ).'</p></td>
												</tr>
											</tbody></table>
										</div>
										</div>
									</div>
								</div>';
                        }
                        else 
                        {
	                        $htmlNew .= '
		                        <div class="forum-response">
									<div class="forum-ears">
										<div class="forum-roundedcontent">
										<div class="forum-info">
											<table class="table-striped" style="width:100%" >
												<tbody><tr>
													<th><h1><span><a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a></span> <a href="#">'.time_since( strtotime( $row->datetime ) ).' Önce</a> <!-- İstanbul, 35 --></h1></th>
												</tr>
												<tr>
													<td><p>'.make_clickable( $row->comment ).'</p></td>
												</tr>
											</tbody></table>
										</div>
										</div>
									</div>
									<div class="forum-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
								</div>';
                        }
                            
                            
                        } else {
                    
                            $response['html'] = 'Gösterilecek yorum bulunamadı';
                            $response['count'] = 0;
                            $response['start'] = 'none';
                            
                            //break;
                            
                        }
                    //}
                    
                    if($model->newDesign)
                    $response['html'] = $htmlNew;
                    else 
                    $response['html'] = $html;
                    $response['count'] = count($rows);
                    $response['start'] = $row->ID;
                    $response['rows'] = $row;
                    
                } else {
                    
                    $response['html'] = 'Gösterilecek yorum bulunamadı';
                    $response['count'] = 0;
                    $response['start'] = 'none';
                    
                }
        /*    
        } else {
                $response['html'] = 'Gösterilecek yorum bulunamadı';
                $response['count'] = 0;
                $response['start'] = 'none';
            }
            */

            return $response;
        }
        
        
        static public function getdicomment_count($ID){
            global $model, $db, $l, $LIKETYPES;
                
            $SELECT = "SELECT COUNT(*)";
            $FROM   = "\n FROM di AS di";
            $JOIN   = "\n ";
            
            $WHERE  = "\n WHERE di.replyID = " . $db->quote($ID);
            $WHERE .= "\n AND di.status>0";
            
            $ORDER  = "\n ";
            $LIMIT  = "\n ";
            
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            return intval( $db->loadResult() );
        }
        static  public  function getpopularDi()
        {
        	global $db, $model;
        	$SELECT = "SELECT DISTINCT di.*, sharer.ID AS sharerID, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, sharer.deputy AS deputy";
            $SELECT.= ", count(dilike.ID) AS toplamoy, sum(dilike.dilike1) AS takdir, sum(dilike.dilike2) AS saygi";
            $SELECT.= ", (SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID ) AS complaint";
            $SELECT.= ",( sum(dilike.dilike1) - sum(dilike.dilike2) - ((SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID )*2))  AS popularite";
            $FROM   = "\n FROM dilike, di";
            $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";            
            $WHERE  = "\n WHERE di.datetime > DATE_ADD(NOW(), INTERVAL -1 DAY)";
            $WHERE .= "\n and di.ID = dilike.diID"; // AND
            $WHERE .= "\n AND di.status>0";
            $WHERE .= "\n AND di.popularstatus>0";
            $GROUP  = "\n GROUP BY dilike.diID";
            $ORDER  = "\n ORDER BY popularite DESC";
            $LIMIT  = "\n LIMIT 10";
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            $html="";
            $htmlNew="";
            if(count($rows)){
            foreach($rows as $row){
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
						$initElement='&nbsp;&nbsp;&nbsp;<img src="/images/iPhoto.png" />';
					}
					else{
						$initElement='';
					}
					$htmlNew.=self::get_voiceHtmlNew($row,$deputyinfo,$redier,$genelID,$dicomment_count,$initElement,$likeinfo);
                    
                }// foreach($rows as $row) END

                $html = '<div id="wall' . $row->ID . '">' . $htmlNew . '</div>';
             
                
                $response['html'] = $html;
                $response['count'] = count($rows);
                $response['start'] = $row->ID;
                $response['first'] = $rows[0]->ID;
	     	}// if(count($rows)) END
	     	else {
	     		$response['html'] = "Başka Yok";
                $response['count'] = 0;
                $response['start'] = 0;
                $response['first'] = 0;
	     	}
	     	return $response;
        }
		public static function get_voiceCommentHtml($rows=null,$dirow=null)
		{
			global $model, $db, $dbez;
			$htmlNew="";
			if(count($rows)){
            	foreach($rows as $row){  	
                	if($row->profileID==$dirow->profileID) 
                    	$owner = 'me';
                 	else
                    	$owner = 'other';
                        $likeinfo = di::getlikeinfo($row->ID);
      									
                        
                        $hoverNew=$likeinfo['html'];
                        $hoverNew.='';
                       
                        if($owner=="me")
                        {
                        	$htmlNew .= '
		                        <div class="forum-response_me">
		                        <a name="'.$row->ID.'"></a>
		                        <div class="forum-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
									<div class="forum-ears">
										<div class="forum-roundedcontent">
										<div class="forum-info">
											<table class="table-striped" style="width:100%" >
												<tbody>
												<tr>
													<th>
														<h1>
															<span>
																<a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a>
															</span> 
															<a href="#">'.time_since( strtotime( $row->datetime ) ).' Önce</a> 
															<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et" style="float:right;">&nbsp;</span>
															<div id="diclikeinfo'.$row->ID.'" class="dilikeinfo">'.$hoverNew.'</div>
														</h1>
													</th>
												</tr>
												<tr>
													<td><p>'.make_clickable( $row->di ).'</p></td>
												</tr>
											</tbody></table>
										</div>
										</div>
									</div>
								</div>';
                        }
                        else 
                        {
	                        $htmlNew .= '
		                        <div class="forum-response">
		                        <a name="'.$row->ID.'"></a>
									<div class="forum-ears">
										<div class="forum-roundedcontent">
										<div class="forum-info">
											<table class="table-striped" style="width:100%" >
												<tbody><tr>
													<th>
														<h1>
															<span>
																<a href="/profile/'.$row->profileID.'">'.$row->sharername.'</a>
															</span> 
															<a href="#">'.time_since( strtotime( $row->datetime ) ).' Önce</a> 
															<span class="x diCommentSikayet" rel="'.$row->ID.'" title="kaldır / şikayet et" style="float:right;">&nbsp;</span>
															<div id="diclikeinfo'.$row->ID.'" class="dilikeinfo">'.$hoverNew.'</div>
														</h1>
													</th>
												</tr>
												<tr>
													<td><p>'.make_clickable( $row->di ).'</p></td>
												</tr>
											</tbody></table>
										</div>
										</div>
									</div>
									<div class="forum-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
								</div>';
                        }
                        
                    }
                   return $htmlNew;                    
                }
				else {
					return $htmlNew;			
				}
		}	
		public static function get_voiceHtmlNew($row=null,$deputyinfo="",$redier="",$genelID=0,$dicomment_count=0,$initElement="",$likeinfo,$inRounded=1,$replyArea=1)
		{
			// seslerin htmlnew eklentileri bu fonksiyondan gelecek.
			$uniqueKey= rand(0,1000);
			global $model;
			$rData="";
			$voiceDetailFunc="";
			if($genelID==0)
			$genelID=$row->ID;
			if($inRounded==1)
			{
				$rData.='
        			<p></p>
        			<div class="roundedcontentsub" id="voice-'.$row->ID.'-'.$uniqueKey.'" style="cursor:pointer;"  >
        				<div id="di_topArea-'.$row->ID.'-'.$uniqueKey.'" style="display:none; margin-top:2px; ">
        					<input type="hidden" id="isReply-'.$row->ID.'-'.$uniqueKey.'" name="isReply-'.$row->ID.'" value="'.$row->isReply.'" />
        					<input type="hidden" id="replyID-'.$row->ID.'-'.$uniqueKey.'" name="replyID-'.$row->ID.'" value="'.$row->replyID.'" />
        					<div id="di_topAreaConten-'.$row->ID.'-'.$uniqueKey.'"></div>
        					<hr />
        				</div>
        				<div style="clear:both;"></div>';
				$voiceDetailFunc='onclick="voiceDetail('.$genelID.','.$uniqueKey.');"';
			}
			if($inRounded!=1)
        	$rData.='<div class="tekDivTest">';
        	$rData.='<div class="usrlist-other-pic"><img src="'.$model->getProfileImage( $row->sharerimage, 67, 67, 'cutout' ).'"></div>
        				<div class="usrlist-other-info" '.$voiceDetailFunc.'>
	        				<table class="table-striped" style="width:100%;">
		        				<tr>
			        				<th><a href="/profile/'.$row->profileID.'"><span>'.$deputyinfo.$row->sharername.' </span></a>
			        						<span>'.$redier.'</span> <i>'.time_since( strtotime( $row->datetime )).'</i>
			        						<div class="btn-group dropup usrlist-other-cnfgr">
			        						<!--<span class="x" data-toggle="dropdown" style="float:right;"></span>-->
			        						<span class="x diSikayet" rel="'.$row->ID.'" data-toggle="" style="float:right;"></span>
			        						<ul class="dropdown-menu pull-right">
			        						<li><a href="#">Kişiyi Engelleee</a></li>
			        						<li><a href="#">Şikayet Et</a></li>
			        						<li><a href="#">Gizle</a></li>
			        						</ul>
			        						 
			        						</div>
			        				</th>
		        				</tr>
		        				<tr>
		        					<td><p>'.self::hashTag(make_clickable( $model->splitword(  $row->di , 48) )).'</p></td>
		        				</tr>
		        				<tr>
		        					<td>
		        						';
										if($replyArea==1)
										{
											$rData.='<span class="share"><a href="javascript:;" onclick="$(\'#repled-box-'.$row->ID.'-'.$uniqueKey.'\').toggle(); notVoiceDetail=1;" rel="notVoiceDetail"> <i class="icon-pencil"></i> Yanıtla </a></span>';  						
										}
										$rData.='
										<span class="share"><a href="/di/'.$genelID.'" onclick=""> <i class="icon-comment"></i> Tümü Gör '.$dicomment_count.'</a></span>
		        						<span class="share"><a href="javascript:redi('.$genelID.')"><i class="icon-share"></i> Paylaş</a></span>
		        						<span id="dilikeinfo'.$genelID.'" style="float:right;"> '.$likeinfo['html'].' </span>
		        						'.$initElement.'
		        					</td>
		        				</tr>
	        				</table>
        				</div>
        				<div style="clear:both;"></div>';
						if($replyArea==1)
						$rData.='<div id="repled-box-'.$genelID.'-'.$uniqueKey.'" class="repled-box" style="display:none;"> '.self::get_voiceMentionTextarea($row->ID).'</div>';
						if($inRounded!=1)
        					$rData.='</div>';
						
				if($inRounded==1)
				{	
        		$rData.='
        				<div id="di_subArea-'.$genelID.'-'.$uniqueKey.'" style="display:none;">
        					<input type="hidden" id="openStatus-'.$genelID.'-'.$uniqueKey.'" name="openStatus-'.$genelID.'" value="0" />
        					<input type="hidden" id="initem-'.$genelID.'-'.$uniqueKey.'" name="initem-'.$genelID.'" value="'.$row->initem.'" />
        					<input type="hidden" id="itemLoaded-'.$genelID.'-'.$uniqueKey.'" name="itemLoaded-'.$genelID.'" value="0" />
        					<hr / style="margin-top:10;">
        					<div id="di_subAreaContenReplideList-'.$genelID.'-'.$uniqueKey.'"></div>
        					<div id="di_subAreaConten-'.$genelID.'-'.$uniqueKey.'"></div>
        			</div>
        		</div>';
        		}
			return $rData;
		}
        public static function hashTag($voice)
        {
        	
        	$re ="\#(.*?)\ "; //$ satır sonu demek satır hastag ten sonra eğerki boşlık karakteri eklenmesse  sorun oluyoru üzerine düşün regular expression or  
        	
        	if(preg_match_all("#".$re."#" ,$voice." " , $matches))
        	{
        		
        		foreach($matches[1] as $m)
        		{
        			$voice=str_replace("#$m", '<a href="/search/'."$m".'#sesler">'."#$m".'</a>', $voice);
        		}
        		
        	}
        	return $voice;
        	
        }
		public function get_voiceMentionTextarea($voiceID)
		{
			global $model;
			$notSendNoticeSend="";
			if($model->paths[0]=="di")
			{
				if($model->paths[1]==$voiceID)
				{
					$notSendNoticeSend='<span style="float:right; margin-left:20px; cursor:pointer;" onclick="notsendnotice('.$model->profileID.','.$voiceID.');">Bildirim Yapma</span>';
				}
			}
			$returnHtml	='<p></p><form method="post" onsubmit="return false;" id="voiceMentionForm-'.$voiceID.'">';
			$returnHtml	.= '<textarea rows="3" id="shareditext-'.$voiceID.'" name="di" placeholder="Fikrini Paylaş" class="input-xlarge numberSay tooltip-top"   style="width: 400px; height: 32px; resize:none; overflow: auto;" >+voice </textarea>';
        	$returnHtml	.='<div style="display: none;" id="degerler">
							<input type="hidden" value="voice" id="linkli" name="linkli">
							<input type="hidden" value="'.$voiceID.'" id="sesHakkındaID" name="sesHakkındaID">
							<input type="hidden" name="otherPID" id="otherPID" value="default" />
						   </div>';
						   
			$returnHtml	.='<ul style="float: right; list-style: none; margin: 0;">
							<li style="float: left;">
			            		<button class="btn btn-gonder tooltip-top" data-original-title="" id="shareditext-'.$voiceID.'Button" onClick="voice_mention_submit('.$voiceID.');">Cevapla</button>
			            	</li>
			            	<li style="display:" class="hideArea-shareditext-'.$voiceID.'">
			            		<span style="color: #9B9B9B; font-size:10pt; margin-right: 10px; margin-top: 10px;">
			            			<span id="shareditext-'.$voiceID.'Number" style="float: none;font-size:10pt;">200</span> Karakter
			            		</span>';
			            
			            	$returnHtml	.='		
							
			            	</li>
			            </ul>';
			$returnHtml	.=$notSendNoticeSend;
			$returnHtml	.='</form>';
        	return $returnHtml;					
		}
		public function get_replyVoice($voiceID)
		{
			global $model,$dbez;
			$returnData=array ();
			$replyVoices=$dbez->get_col("SELECT ID from di WHERE replyID='".$voiceID."' and isReply='1' and status='1' order by ID  DESC ");
			

			if(count($replyVoices)>0)
			{
				$returnData["success"]="succes";
				$returnData["count"]=count($replyVoices);
				$returnData["html"]=self::get_replyVoiceHtml($replyVoices);
			}
			else {
				$returnData["success"]="succes";
				$returnData["count"]=0;
				$returnData["html"]="";
			}
			return $returnData;
		}
		public function get_replyVoiceHtml($voiceListArr,$returnVoiceCount=-1)
		{
			global $model;
			$returnHtml ="";
			if($returnVoiceCount!=-1)//All Voice Return
			{
				array_slice($voiceListArr, $returnVoiceCount);
			}
			foreach($voiceListArr as $vID)
			{
				$voiceDt=self::get_singleDi($vID);
				if($voiceDt["count"]>0)
				{
					$returnHtml.=$voiceDt["html"];
					$returnHtml.="<p></p>";
				}
			}
			return $returnHtml;
		}
    }
?>
