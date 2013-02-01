<?php
    class di_plugin extends control{ 
        public function main(){
            global $model, $db, $l;
			header ('HTTP/1.1 301 Moved Permanently');
  			header ('Location: /voice/'.$model->paths[1]);
			//die;
            //$model->newDesign=false;
            $model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
            //$model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
            //$model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            $model->addScript($model->pluginurl . 'di.js', 'di.js', 1);
			

            	
			$model->view = 'di';	
			$model->initTemplate('beta', 'di');
			/*
				$model->addScript('
					   function submitBahset()
					    {
						    //console.log($("#shareDi").html());
						    data=$("#shareDi").serialize();
					    	$.ajax({
					    	  type:\'POST\',
					    	  data:data,
							  url: \'/ajax/share\',
							  
							  success: function(){
							    //alert(\'done\');
								location.href=location.href;
								//$(".bahsdildi").show();
								//$(".bahset").hide();
							  }
							});
					    }
				');
			 */ 
				
				$diid = intval($model->paths[1]);
				$di = new di($diid);
    			if($diid<1) $model->redirect('/');
    			//$result = $di->getdi($diid);
				$result = $di->get_singleDi($diid,1,0);
	            if(intval($result['count'])<1) $model->redirect('/'); //die('1'); 
			    if(!profile::isallowed($result['row']->profileID, $result['row']->showdies)){
			        echo '<h4>gizlilik ayarları nedeniyle görüntülenemiyor!</h4>';
			        return;
			    }
    			$model->title = strip_tags($result['row']->di);
    			$model->description = strip_tags($result['row']->di);
				$replyArea=1;
    			if($di->_voice->isReply==1)
				{
					$replyArea=0;
					$repliedVoice=$di->get_singleDi($di->_voice->replyID,0,0);
					
					echo '<div style="margin:12px 12px 22px 12px;">';
						echo $repliedVoice["html"];
					echo '</div>';
					
				}
				if($result['count']>0){
					echo $result['html'];
				} else {
					echo $result['html'];
				}
				$model->addScript('profileID = ' . $model->profileID );
				$model->addScript('diID = ' . $diid ); 
				$model->addScript("paths=".json_encode($model->paths));
				?>
				<hr/>
				<?php 
					//echo $diid; 
					$repylCount=$di->getdicomment_count($diid);
					$model->addscript("var totalReplyCount=".$repylCount.";");
					if($repylCount>3)
					{
						echo '<div id="get_moreReplyBox" class="roundedcontentsub" id="" style="">';
							echo '<div id="load_moreComment" style="width:500px; text-align:center; cursor:pointer;">';
							echo "Önceki cevapları gör <span id='count_span'>".($repylCount-3)."</spam> tane daha.";
							echo '</div>';
		        			//echo $di->get_voiceMentionTextarea($model->paths[1]);
		        		echo '</div>';
					}
					//$v=new voice($diid);
					//$v->get_voiceObjec();
				?>
				<div id="dicomments" class="">
					<?php
				
					   $commentresult = $di->get_voiceReply($diid,0,3,$replyArea);
					   $model->addscript("var commentFID=".$commentresult["start"]."; var loadVoiceCount=".$commentresult["count"].";");
					   ;
					   if($commentresult['count']>0){
					        echo $commentresult['html'];
					    } else {
					        echo $commentresult['html'];
					    } 
					?>        
				</div><!--dicomments END-->
				<p></p>
				<?php
				echo '<div class="roundedcontentsub" id="voice-43046-319" style="">';
        			echo $di->get_voiceMentionTextarea($model->paths[1]);
        		echo '</div>';
				//$di->getdicomment_count($diid);
        }
        
        public function getdilikeinfo__($ID){
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
        
        public function getsharelikeinfo__($ID){
            global $model, $db;
            
            $db->setQuery('SELECT SUM(regard) AS regard, SUM(appreciate) AS appreciate FROM sharelike WHERE shareID = ' . $db->quote($ID) );
            if( $db->loadObject($result) )
                return $result;
            else {
                return (object) array('regard'=>0, 'appreciate'=>0 );
            }
        }
        
        public function getsharecommentlikeinfo__($ID){
            global $model, $db;
            
            $db->setQuery('SELECT SUM(regard) AS regard, SUM(appreciate) AS appreciate FROM sharecommentlike WHERE commentID = ' . $db->quote($ID) );
            if( $db->loadObject($result) )
                return $result;
            else {
                return (object) array('regard'=>0, 'appreciate'=>0 );
            }
        }
    }
?>
