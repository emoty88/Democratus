<?php
    class di{
        
        static public function getdi($ID){
            global $model, $db, $l, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, sharer.showdies, sharer.dicomment";
            $FROM   = "\n FROM di";
            $JOIN  = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
            $JOIN .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
            
            $WHERE  = "\n WHERE di.ID = " . $db->quote(intval( $ID ));
            
            $WHERE .= "\n AND di.status>0";
            
            $ORDER  = "\n ";
            $LIMIT  = "\n LIMIT 1";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            
            $html = '';
            if($db->loadObject($row)){

                    if($row->profileID!=$model->profileID){
                        //if(!profile::isallowed($row->profileID, $row->showdies)) continue;
                        $dicompliant = '<span id="dicompliant'.$row->ID.'" onclick="javascript:dicompliant('.$row->ID.')"> Şikayet </span>';
                        $dicompliant = '';
                        $commentit = '<div class="other">
                                <div class="image" style="background: url(\''.$model->getProfileImage( $model->profileimage, 50, 50, 'cutout' ).'\') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name">bir şeyler yaz</span>
										<span style="float:right; margin-right:20px; cursor:pointer;" onclick="notsendnotice('.$model->profileID.','.$ID.');">bildirim yapma</span>
                                    </div>
                                    <div class="comment_center">
                                        <textarea id="dicommenttext" rows="5" cols="25" maxlength="200"></textarea>
                                        <input type="button" id="dicommentita" value="Yorumla !" />
                                        <p class="character" style=""><span class="number">200</span> Karakter</p>
                                    </div>
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>';
                            
                            if(!profile::isallowed($row->profileID, $row->dicomment)) {
                                $commentit = '';
                            }
                            
                    } else {
                        
                        $dicompliant = '';
                        $commentit = '<div class="me">
                                <div class="image" style="background: url(\''.$model->getProfileImage( $model->profileimage, 50, 50, 'cutout' ).'\') no-repeat"></div>
                                <div class="comment">
                                    <div class="comment_top">
                                        <span class="name">bir şeyler yaz</span>
										<span style="float:right; margin-right:20px; cursor:pointer;" onclick="notsendnotice('.$model->profileID.','.$ID.');">bildirim yapma</span>
                                    </div>
                                    <div class="comment_center">
                                        <textarea id="dicommenttext" rows="5" cols="25" maxlength="200"></textarea>
                                        <input type="button" id="dicommentita" value="Yorumla !" />
                                        <p class="character" style=""><span class="number">200</span> Karakter</p>
                                    </div>
                                    <div class="comment_bottom"></div>
                                </div>
                                
                                <div class="clear"></div>
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
        
        static public function getdies($profileID=0, $start = 0, $limit = config::dilimit){
            global $model, $db, $l, $LIKETYPES;

            $SELECT = "SELECT DISTINCT di.*, sharer.image AS sharerimage, sharer.name AS sharername, redier.name AS rediername, redier.image AS redierimage,redier.image AS redierimage, sharer.deputy AS deputy, sharer.showdies";
            $FROM   = "\n FROM di";
            $JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
            $JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
            
            if(intval($profileID)<1){
                $JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";                
                $WHERE  = "\n WHERE  ( ";
                $WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
                $WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
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
                    $likeinfo = di::getlikeinfo($genelID);
                    
                    if($row->profileID == $model->profileID)
                        $delete = '<span class="x" title="kaldır / şikayet et"></span>';
                    else                        
                        $delete = '';
                        
                    $delete = '<span class="x" rel="'.$row->ID.'" title="kaldır / şikayet et"></span>';
                    
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

                        $result .= '<span id="'.$liketype.'_'.$ID.'" class="dilike '.$liketype.'">'.$l[$liketype.'liked'].$dilikecount.'</span> '.'';
                        
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

                        $result .= '<span id="dc'.$liketype.'_'.$ID.'" class="diclike">'.$l[$liketype.'liked'].$diclikecount.'</span> '.'';
                        
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
                        $hover.= '<span class="x" rel="'.$row->ID.'" title="kaldır / şikayet et">&nbsp;</span>';
                        $hover.= '</span>';
 
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
                            
                            
                            
                        } else {
                    
                            $response['html'] = 'Gösterilecek yorum bulunamadı';
                            $response['count'] = 0;
                            $response['start'] = 'none';
                            
                            //break;
                            
                        }
                    //}
                    
                    
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
            $FROM   = "\n FROM dicomment AS dc";
            $JOIN   = "\n ";
            
            $WHERE  = "\n WHERE dc.diID = " . $db->quote($ID);
            $WHERE .= "\n AND dc.status>0";
            
            $ORDER  = "\n ";
            $LIMIT  = "\n ";
            
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            return intval( $db->loadResult() );
        }
        
        
    }
?>
