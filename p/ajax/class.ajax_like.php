<?php
    class ajax_like {
        
        public function share($ID, $like){            
            global $model, $db;
            
            $profileID = $model->profileID;
            
            $db->setQuery("SELECT sl.* FROM sharelike AS sl WHERE sl.shareID=" . $db->quote($ID) . " AND sl.profileID=" . $db->quote($profileID) . " LIMIT 1");
            $sharelike = null;
            if($db->loadObject($sharelike)){
                switch($like){
                    case 1: $sharelike->regard=1; $sharelike->appreciate=0; break;
                    case 2: $sharelike->regard=0; $sharelike->appreciate=1; break;
                    default: $sharelike->regard=0; $sharelike->appreciate=0;
                }
                if( $db->updateObject('sharelike', $sharelike, 'ID') ){
                    echo 'success';
                } else {
                    echo 'error';
                }
                    
            } else {
                $sharelike = new stdClass;
                
                switch($like){
                    case 1: $sharelike->regard=1; $sharelike->appreciate=0; break;
                    case 2: $sharelike->regard=0; $sharelike->appreciate=1; break;
                    default: $sharelike->regard=0; $sharelike->appreciate=0;
                }
                
                $sharelike->shareID     = intval($ID);
                $sharelike->datetime    = date('Y-m-d H:i:s');
                $sharelike->profileID   = intval( $profileID );
                $sharelike->userID      = intval( $model->user->ID );
                $sharelike->ip          = $_SERVER['REMOTE_ADDR'];                
                
                if( $db->insertObject('sharelike', $sharelike) ){
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
        }
        public function diLikeCancel($ID)
        {
        	global $model, $db, $LIKETYPES;
        	$profileID = $model->profileID;
        	$db->setQuery("SELECT dl.* FROM dilike AS dl WHERE dl.diID=" . $db->quote($ID) . " AND dl.profileID=" . $db->quote($profileID) . " LIMIT 1");
            $dilike = null;
            if($db->loadObject($dilike)){
            	
				$dilike->canceldate    = date('Y-m-d H:i:s');
	            $dilike->cancelIP      = $_SERVER['REMOTE_ADDR'];                
	            $dilike->dilike1=0;
	            $dilike->dilike2=0;
	            
	            if( $db->updateObject('dilike', $dilike, 'ID') ){
	            	$response['result'] = 'success';
	            	$di = new di;
	            
	            	$response['likeinfo'] = $di->getlikeinfo($ID);
	            
	            } else {
	            	$response['result'] = 'error';
	            }
            }
            else
            {
            	$response['result'] = 'error';
            }
            echo json_encode($response);
        }
        public function di($ID, $like){            
            global $model, $db, $dbez, $LIKETYPES;
            
           
           
            $response = array();
            
            $profileID = $model->profileID;
            
            $db->setQuery("SELECT dl.* FROM dilike AS dl WHERE dl.diID=" . $db->quote($ID) . " AND dl.profileID=" . $db->quote($profileID) . " LIMIT 1");
            $dilike = null;
            
            if($db->loadObject($dilike)){
                
                foreach($LIKETYPES as $liketype){
                    if($liketype == $like )
                        $dilike->$liketype = 1;
                    else
                        $dilike->$liketype = 0;
                }
                
                if( $db->updateObject('dilike', $dilike, 'ID') ){
                    $response['result'] = 'success';
                    $di = new di;
                    
                    $response['likeinfo'] = $di->getlikeinfo($ID);
                    
                    //notice
                    $db->setQuery("SELECT profileID FROM di WHERE ID=" . $db->quote($dilike->diID));
                    $profileID2 = intval( $db->loadResult() );
                    $model->notice($profileID2, 'dilike', $ID, null, $like);
                    
                } else {
                    $response['result'] = 'error';
                }
                    
            } else {
                $dilike = new stdClass;
                
                foreach($LIKETYPES as $liketype){
                    if($liketype == $like )
                        $dilike->$liketype = 1;
                    else
                        $dilike->$liketype = 0;
                }
                
                $dilike->diID     = intval($ID);
                $dilike->datetime    = date('Y-m-d H:i:s');
                $dilike->profileID   = intval( $profileID );
                //$dilike->userID      = intval( $model->user->ID );
                $dilike->ip          = $_SERVER['REMOTE_ADDR'];                
                
                if( $db->insertObject('dilike', $dilike) ){
                    $response['result'] = 'success';
                    $di = new di;
                    
                    $response['likeinfo'] = $di->getlikeinfo($ID);
                    
                    //notice
                    $db->setQuery("SELECT profileID FROM di WHERE ID=" . $db->quote($dilike->diID));
                    $profileID2 = intval( $db->loadResult() );
                    //$model->notice($profileID2, 'dilike', $ID);
                    $model->notice($profileID2, 'dilike', $ID, null, $like);
                    
                } else {
                    $response['result'] = 'error';
                }
            }
            $di=$dbez->get_row("SELECT ID, profileID FROM di WHERE ID=" . $db->quote($dilike->diID));
            
            $puanClass=new puan;
        	if($like=="dilike1")
            {
            	$puanClass->puanIslem($di->profileID,"2",$di);
				KM::identify($model->user->email);
				KM::record('likevoice');
            }
            else 
            {
            	$puanClass->puanIslem($di->profileID,"3",$di);
				KM::identify($model->user->email);
				KM::record('dislikevoice');
            }
            echo json_encode($response);
            
        }
        public function diCommentLikeCancel($ID)
        {
        	global $model, $db, $LIKETYPES;
        	$profileID = $model->profileID;
        	$db->setQuery("SELECT dcl.* FROM dicommentlike AS dcl WHERE dcl.dicID=" . $db->quote($ID) . " AND dcl.profileID=" . $db->quote($profileID) . " LIMIT 1");
            $dilike = null;
        	
            if($db->loadObject($dilike)){
            
            	$dilike->canceldate    = date('Y-m-d H:i:s');
            	$dilike->cancelIP          = $_SERVER['REMOTE_ADDR'];
            	$dilike->dilike1=0;
            	$dilike->dilike2=0;
            	 
        		if( $db->updateObject('dicommentlike', $dilike, 'ID') ){
                    $response['result'] = 'success';
                    $di = new di;
                    
                    $response['likeinfo'] = $di->getcommentlikeinfo($ID);


                } else {
                    $response['result'] = 'error';
                }
               
             }
             else
             {
                $response['result'] = 'error';
            }
        	echo json_encode($response);
        }
        public function dic($ID, $like){            
            global $model, $db, $LIKETYPES;
            
            $response = array();
            
            $profileID = $model->profileID;
            
            $db->setQuery("SELECT dcl.* FROM dicommentlike AS dcl WHERE dcl.dicID=" . $db->quote($ID) . " AND dcl.profileID=" . $db->quote($profileID) . " LIMIT 1");
            $dilike = null;
            if($db->loadObject($dilike)){
                
                foreach($LIKETYPES as $liketype){
                    if($liketype == $like )
                        $dilike->$liketype = 1;
                    else
                        $dilike->$liketype = 0;
                }
                
                if( $db->updateObject('dicommentlike', $dilike, 'ID') ){
                    $response['result'] = 'success';
                    $di = new di;
                    
                    $response['likeinfo'] = $di->getcommentlikeinfo($ID);
                    
                    //notice
                    $db->setQuery("SELECT profileID, diID FROM `dicomment` WHERE dicomment.ID = " . $db->quote($dilike->dicID));
                    //$profileID2 = intval( $db->loadResult() );
                    if($db->loadObject($dc))
                    //$model->notice($dc->profileID, 'dicommentlike', $dc->diID);
                    $model->notice($dc->profileID, 'dicommentlike',$dilike->dicID, $dc->diID, $like);

                } else {
                    $response['result'] = 'error';
                }
                    
            } else {
                $dilike = new stdClass;
                
                foreach($LIKETYPES as $liketype){
                    if($liketype == $like )
                        $dilike->$liketype = 1;
                    else
                        $dilike->$liketype = 0;
                }
                
                $dilike->dicID     = intval($ID);
                $dilike->datetime    = date('Y-m-d H:i:s');
                $dilike->profileID   = intval( $profileID );
                //$dilike->userID      = intval( $model->user->ID );
                $dilike->ip          = $_SERVER['REMOTE_ADDR'];                
                
                if( $db->insertObject('dicommentlike', $dilike) ){
                    $response['result'] = 'success';
                    $di = new di;
                    
                    $response['likeinfo'] = $di->getcommentlikeinfo($ID);
                    //notice
                    $db->setQuery("SELECT profileID, diID FROM `dicomment` WHERE dicomment.ID = " . $db->quote($dilike->dicID));
                    //$profileID2 = intval( $db->loadResult() );
                    if($db->loadObject($dc))
                    //$model->notice($dc->profileID, 'dicommentlike',$dilike->dicID, $dc->diID);
                    $model->notice($dc->profileID, 'dicommentlike',$dilike->dicID, $dc->diID,$like);
                    
                } else {
                    $response['result'] = 'error';
                }
            }
            echo json_encode($response);
            
        }
        
        public function sharecomment($ID, $like){            
            global $model, $db;
            //die('sharecomment' . $ID . ' - ' . $like);
            $profileID = $model->profileID;
            
            $db->setQuery("SELECT scl.* FROM sharecommentlike AS scl WHERE scl.commentID=" . $db->quote($ID) . " AND scl.profileID=" . $db->quote($profileID) . " LIMIT 1" . "\n #".__FILE__." - ".__LINE__);
            $sharecommentlike = null;
            if($db->loadObject($sharecommentlike)){
                
                switch($like){
                    case 1: $sharecommentlike->regard=1; $sharecommentlike->appreciate=0; break;
                    case 2: $sharecommentlike->regard=0; $sharecommentlike->appreciate=1; break;
                    default: $sharecommentlike->regard=0; $sharecommentlike->appreciate=0;
                }
                if($db->updateObject('sharecommentlike', $sharecommentlike, 'ID')) 
                    echo 'success';
                else 
                    echo 'error';
            } else {
                $sharecommentlike = new stdClass;
                
                switch($like){
                    case 1: $sharecommentlike->regard=1; $sharecommentlike->appreciate=0; break;
                    case 2: $sharecommentlike->regard=0; $sharecommentlike->appreciate=1; break;
                    default: $sharecommentlike->regard=0; $sharecommentlike->appreciate=0;
                }
                
                $sharecommentlike->commentID   = intval($ID);
                $sharecommentlike->datetime    = date('Y-m-d H:i:s');
                $sharecommentlike->profileID   = intval( $profileID );
                $sharecommentlike->userID      = intval( $model->user->ID );
                $sharecommentlike->ip          = $_SERVER['REMOTE_ADDR'];                
                
                if($db->insertObject('sharecommentlike', $sharecommentlike))
                    echo 'success';
                else 
                    echo 'error';                
            }
            
            //echo $db->_errorMsg;
        }        
    }
?>
