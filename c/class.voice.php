<?php
   	class voice  {
	   	public $_ID=0;
		public $_voice=0;
		public $_cons=0;
		public function __construct($voice=null)
		{
			if($voice==null)
			{
					
			}
			else if(is_object($voice))
			{
				$this->_ID=$voice->ID;
				$this->_cons=1;
				$this->_voice=$this->get_voiceObjec();
			}
			else {
				$this->_ID=$voice;
				$this->_cons=1;
				$this->_voice=$this->get_voiceObjec();
			}
		}
		public function get_voiceObjec($voiceID=null)
		{
			global $model,$db;
			if($voiceID==null && $this->_cons==0)
			{
				return false;
			}
			else if($this->_cons==1)
			{
				$voiceID=$this->_ID;
			}
			$SELECT = "SELECT DISTINCT 	di.*, 
										sharer.image AS sharerimage, 
										sharer.name AS sharername, 
										redier.name AS rediername, 
										redier.image AS redierimage, 
										sharer.deputy AS deputy, 
										sharer.showdies, 
										sharer.permalink as permalink";
        	$FROM   = "\n FROM di as di";
        	$JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
        	$JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";
			$WHERE	="\n WHERE di.status=1 and di.ID='".$voiceID."' ";
			$LIMIT	="\n limit 1";
			
			$db->setQuery($SELECT.$FROM.$JOIN.$WHERE);
			$db->loadObject($result);
			return $result;
		}
		public function get_voices_for_wall($profileID = 0, $start = 0 , $limit = 7 , $onlyProfile = 0, $hashTag = 0,$keyword="")
		{
			global $model, $db, $l, $LIKETYPES;
			if($model->profileID < 1)
			{
				//return FALSE;
			}
			if($model->profileID == "10000" || true)
			{
				$db->setQuery("SELECT followingID from follow where followerID='".$model->profileID."' AND status=1");
				$followin=$db->loadResultArray();
				
				//echo "<pre>";
				//	var_dump($followin);
				//echo "</pre>";
				$SELECT = "SELECT DISTINCT 	di.*, 
	        								sharer.image AS sharerimage, 
	        								sharer.name AS sharername, 
	        								redier.name AS rediername, 
	        								redier.image AS redierimage, 
	        								sharer.deputy AS deputy, 
	        								sharer.showdies,
	        								sharer.permalink as permalink";
	        	$FROM   = "\n FROM di";
	        	$JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
	        	$JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
	        	if(intval($profileID)<1){
	        		//$JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";
	        		$WHERE  = "\n WHERE  ( ";
	        		$WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
	        		//$WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
	        		$WHERE .= "\n OR profileID IN (".implode(",", $followin).")";
	        		$WHERE .= "\n OR ( di.profileID<1000 ))"; //democratus profili
	        	} else {
	        		$WHERE  = "\n WHERE di.profileID = " . $db->quote(intval( $profileID ));
	        	}
				if($start>0){
	        		$WHERE .= "\n AND di.ID<" . $db->quote($start);
	        	}  
				if($hashTag != 0)
				{
					$WHERE .= "\n  OR (di.di  LIKE '%". $db->escape( "#".$hashTag )."%')";
				}
	        	
	        	$WHERE .= "\n AND di.status>0";
	        	if($onlyProfile==0)
	        		$WHERE .= "\n AND onlyProfile='0'";
				
	        	$ORDER  = "\n ORDER BY di.ID DESC";
	        	$LIMIT  = "\n LIMIT $limit";
			}
			else {
	        	$SELECT = "SELECT DISTINCT 	di.*, 
	        								sharer.image AS sharerimage, 
	        								sharer.name AS sharername, 
	        								redier.name AS rediername, 
	        								redier.image AS redierimage, 
	        								sharer.deputy AS deputy, 
	        								sharer.showdies,
	        								sharer.permalink as permalink";
	        	$FROM   = "\n FROM di";
	        	$JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
	        	$JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
	        	if(intval($profileID)<1){
	        		$JOIN  .= "\n LEFT JOIN #__follow AS f ON f.followingID = di.profileID";
	        		$WHERE  = "\n WHERE  ( ";
	        		$WHERE .= "\n (di.profileID = " . $db->quote(intval( $model->profileID )) . ")";  //kendi profilinde yayınlananlar
	        		$WHERE .= "\n OR (f.followerID=".$db->quote(intval( $model->profileID ))." AND f.status>0 )"; //takip ettikleri
	        		$WHERE .= "\n OR ( di.profileID<1000 ))"; //democratus profili
	        	} else {
	        		$WHERE  = "\n WHERE di.profileID = " . $db->quote(intval( $profileID ));
	        	}
				if($start>0){
	        		$WHERE .= "\n AND di.ID<" . $db->quote($start);
	        	}  
				if($hashTag != 0)
				{
					$WHERE .= "\n  OR (di.di  LIKE '%". $db->escape( "#".$hashTag )."%')";
				}
	        	
	        	$WHERE .= "\n AND di.status>0";
	        	if($onlyProfile==0)
	        		$WHERE .= "\n AND onlyProfile='0'";
				
	        	$ORDER  = "\n ORDER BY di.ID DESC";
	        	$LIMIT  = "\n LIMIT $limit";
        	}
			if($model->profileID == "10000")
        		echo $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT;
   
        	$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
		
			$rows = $db->loadObjectList();
			$voices	=array();
			if(count($rows)>0)
			{
				//return $rows;
				foreach($rows as $row)
				{
					if(!profile::isallowed($row->profileID, $row->showdies)) continue; //seslerini gizledi ise bu özellik kaldırıldı 
					//var_dump($row->ID);
					
					$voices[]	= $this->get_return_object($row);
				}
				return $voices;
			}
			else
			{
				return FALSE;
			}
		}
		public function get_voiceSearch($keyword, $limit=20, $start=0,$onlyProfile=0)
		{
			global $model, $db, $l, $LIKETYPES;
			if($model->profileID < 1)
			{
				//return FALSE;
			}
        	$SELECT = "SELECT DISTINCT 	di.*, 
        								sharer.image AS sharerimage, 
        								sharer.name AS sharername, 
        								redier.name AS rediername, 
        								redier.image AS redierimage, 
        								sharer.deputy AS deputy, 
        								sharer.showdies,
        								sharer.permalink as permalink";
        	$FROM   = "\n FROM di";
        	$JOIN   = "\n LEFT JOIN #__profile AS sharer ON sharer.ID = di.profileID";
        	$JOIN  .= "\n LEFT JOIN #__profile AS redier ON redier.ID = di.redi";
			$WHERE = "\n WHERE di.status>0";
			if($start>0){
        		$WHERE .= "\n AND di.ID<" . $db->quote($start);
        	}  
			$WHERE .= "\n  AND (di.di  LIKE '%". $db->escape( $keyword )."%')";
        	
        	if($onlyProfile==0)
        	{
        		$WHERE .= "\n AND onlyProfile='0'";
			}
			
        	$ORDER  = "\n ORDER BY di.ID DESC";
        	$LIMIT  = "\n LIMIT $limit";
        	//echo $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT;
 			//die;
        	$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
		
			$rows = $db->loadObjectList();
			$voices	=array();
			if(count($rows)>0)
			{
				//return $rows;
				foreach($rows as $row)
				{
					if(!profile::isallowed($row->profileID, $row->showdies)) continue; //seslerini gizledi ise bu özellik kaldırıldı 
					//var_dump($row->ID);
					if($row == null)
						continue;
					$voices[]	= $this->get_return_object($row, 48 , 48);
				}
				return $voices;
			}
			else
			{
				return FALSE;
			}
		}
		public function get_return_object($v_obj, $iW=48, $iH=48)
		{
			global $model;
			$v	= new stdClass;
			if($v_obj->rediID>0)// redi yniden modellensin 
			{
				$v->redierName	= $v_obj->sharername;
				$v->redierPerma	= $v_obj->permalink;
				
				$v->redierImage	= $model->getProfileImage($v_obj->sharerimage, $iW,$iH, 'cutout');
				$v_obj=$this->get_voiceObjec($v_obj->rediID);
			}
			
        	$v->ID		= $v_obj->ID;
			if($v_obj->profileID == $model->profileID)
			{
				$v->isMine = true;
			}
			else
			{
				$v->isMine = false;
			}
			$v->sName	= $v_obj->sharername;
			$v->sPerma	= $v_obj->permalink;
			$v->sImage	= $model->getProfileImage($v_obj->sharerimage, $iW,$iH, 'cutout');
        	$v->voice	= make_clickable($v_obj->di);
			$v->sTime	= time_since( strtotime( $v_obj->datetime ));
			if($v_obj->initem=="1")
			{
				$v->initem		= 1;
				$v->initemName	= '<img src="/images/iPhoto.png" />';
			}
			else
			{
				$v->initem= 0;
			}
			$v->replyCount	= $v_obj->count_reply;
			$v->replyID		= $v_obj->replyID;
			$v->randNum		= rand(1000,9999);
			return $v;
		}
		/**
		 * Voice'a verilen cevapları (+voice) döndürür
		 * @param $voiceID = cevapları istenen voice ID si
		 * @param $start = hangin cevaptan sonrası isteniyorusa o voice ID si
		 * @param $limit = kaç cevap bekleniyorsa 
		 */
		public function get_reply($voiceID=null, $start=0, $limit=3)
		{
			global $model, $db;
			if($voiceID==null && $this->_cons==0)
			{
				return false;
			}
			else if($this->_cons==1)
			{
 				$voiceID=$this->_ID;
 			}

			$SELECT = "SELECT DISTINCT 	di.*, 
										sharer.image AS sharerimage, 
										sharer.name AS sharername, 
										redier.name AS rediername, 
										redier.image AS redierimage, 
										sharer.deputy AS deputy, 
										sharer.showdies, 
										sharer.permalink as permalink";
        	$FROM   = "\n FROM di as di";
        	$JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
        	$JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";
			$WHERE	="\n WHERE di.status=1 and di.replyID='".$voiceID."' ";
			if($start>0){
        		$WHERE .= "\n AND di.ID<" . $db->quote($start);
        	}  
			$ORDER  = "\n ORDER BY di.ID DESC";
			if($limit>0)
        		$LIMIT  = "\n LIMIT $limit";
			else
				$LIMIT = "";
			
			
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $voices = $db->loadObjectList();
			return $voices;
		}
		public function get_parent($voiceID=null)
		{
			global $model, $db;
			if($voiceID==null && $this->_cons==0)
			{
				return false;
			}
			else if($this->_cons==1)
			{
				$voiceID=$this->_ID;
			}
			$SELECT = "SELECT DISTINCT 	di.*, 
										sharer.image AS sharerimage, 
										sharer.name AS sharername, 
										redier.name AS rediername, 
										redier.image AS redierimage, 
										sharer.deputy AS deputy, 
										sharer.showdies, 
										sharer.permalink as permalink";
        	$FROM   = "\n FROM di as di";
        	$JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
        	$JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";
			$WHERE	="\n WHERE di.status=1 and di.ID='".$voiceID."' ";
			$ORDER  = "\n ORDER BY di.ID DESC";
        	$LIMIT  = "\n LIMIT 1";
        	$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $db->loadObject($voices);
			return $voices;
		}
		public function get_replyCount($voiceID)
		{
            global $model, $db;
			if($voiceID==null && $this->_cons==0)
			{
				return false;
			}
			else if($this->_cons==1)
			{
				$voiceID=$this->_ID;
			}
			
            //kendi tablosundan gelsin	
            $SELECT = "SELECT count_(*)";
            $FROM   = "\n FROM di AS di";
            $JOIN   = "\n ";
            
            $WHERE  = "\n WHERE di.replyID = " . $db->quote($voiceID);
            $WHERE .= "\n AND di.status>0";
            
            $ORDER  = "\n ";
            $LIMIT  = "\n ";
            
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            return intval( $db->loadResult() );
        }
		public function get_ismyRedi($voiceID)
		{
			global $model, $db;
			$SELECT	= "SELECT count(ID) ";
			$FROM	= "\n FROM di";
			$WHERE	= "\n WHERE rediID='".$voiceID."' and profileID='".$model->profileID."' ";
			$db->setQuery($SELECT.$FROM.$WHERE);
			if( $db->loadResult()  > 0)
			{
				return true;
			}
			else{
				return false;
			}
		}
		public function get_ismyLike($voiceID)
		{
			global $model, $db;
			$SELECT	= "SELECT dilike1, dilike2 ";
			$FROM	= "\n FROM dilike";
			$WHERE	= "\n WHERE diID='".$voiceID."' and profileID='".$model->profileID."' ";
			$db->setQuery($SELECT.$FROM.$WHERE);
			$likeType=$db->loadObjectList();
			
			if(count($likeType)>0)
			{
				if($likeType[0]->dilike1=="1"){
					return "like1";
				}
				else {
					return "like2";
				}
			}
			else {
				return false;
			}
		}
		public function delete($voiceID=null)
		{
			global $model, $db;
			if($voiceID==null && $this->_cons==0)
			{
				return false;
			}
			else if($this->_cons==1)
			{
				$voiceID=$this->_ID;
			}
			if($model->profileID != $this->_voice->profileID)
			{
				return false;
			}
            $dVoice = new stdClass;
            $dVoice->ID =  $voiceID;
			$dVoice->status = 0;
			return $db->updateObject("di", $dVoice, "ID");
			
       	}
		
	}
?>