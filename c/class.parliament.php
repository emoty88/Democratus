<?php
	class parliament{
		static public function main()
		{
			global $model, $db;
			
		}
		static function get_agenda($type=0,$parentID=0)
		{
			global $model,$db;
			$SELECT = "\n SELECT a.* , av.vote AS myvote, p.image AS deputyimage, p.name AS deputyname, p.permalink AS deputyPerma, p.ID as deputyID";
            $FROM   = "\n FROM agenda AS a";
            $JOIN   = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote($model->profileID);
            $JOIN  .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
            //$WHERE  = "\n WHERE ".$db->quote(date('Y-m-d H:i:s'))." BETWEEN a.starttime AND a.endtime";            
            $WHERE = "\n  WHERE a.status>0"; 
            
            if($type!="0")
			{
				$WHERE .= "\n  AND (a.".$type."='0' or a.".$type."='".$parentID."')";	
			}
			else
			{
				$WHERE .= "\n  AND ( a.regionID IS NULL AND a.countryID IS NULL AND a.cityID IS NULL AND a.hastagID =0 )";	
			}   
             //hastag sayfalarının gündemi yok ve gündemi yoksa  alan boş geliyor tagin gündemi yoksa gerçek meclis gelsin 
            $GROUP  = "\n ";
      
			if($type!="0")
			{
				$ORDER  = "\n ORDER BY a.$type DESC";
				$ORDER  .= "\n , a.ID DESC";
			}else
			{
				$ORDER  = "\n ORDER BY a.ID DESC";
			} 
            $LIMIT  = "\n  LIMIT 7";
            // Bu alanı sunucuya gönderme 
            
            
            $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$GROUP.$ORDER.$LIMIT);
            //echo $db->_sql;
            
            //$db->setQuery('SELECT a.* FROM agenda AS a WHERE '.$db->quote(date('Y-m-d H:i:s')).' BETWEEN a.starttime AND a.endtime ORDER BY ID desc');
            $agendas = $db->loadObjectList();

			return $agendas;
		}
		public function short_agandaNew($agendas)
		{
			$ilk =  array();
			$son =  array();
			foreach($agendas as $a)
			{
				if($a->myvote==null)
				{
					$ilk[] = $a;
				}
				else{
					$son[] = $a;
				} 

			}
			return array_merge($ilk, $son);
		}
		public function get_agendaPercent($agendaID)
		{
			global $model, $db;
			
			$db->setQuery('SELECT av.vote, COUNT(*) AS votecount FROM agendavote AS av WHERE av.agendaID='.$db->quote($agendaID).' GROUP BY av.vote ORDER BY av.vote');
            $voted = $db->loadObjectList('vote');
			$totalvote = 0;
            if(count($voted)) foreach($voted as $v) $totalvote += $v->votecount;
			                                                           
            $percents=array();
     		for($key=2; $key<5; $key++){// bu alanda kaldı
     			
             	if(array_key_exists($key, $voted))
                	$percent = floor( ($voted[$key]->votecount * 100) / $totalvote );
              	else 
                	$percent = 0;
                if($key==2)
					$kKey="olumlu";
				if($key==3)
					$kKey="fikiryok";
				if($key==4)
					$kKey="olumsuz";
            	$percents[$kKey]=$percent;
        	}
			$max=0;
			$sonuc="";
			foreach($percents as $k=>$v)
			{
				if($v>$max)
				{
					$max=$v;
					$sonuc=$k;
				}
			}
			$percents["max"]=$max;
			$percents["sonuc"]=$sonuc;
			return $percents; 
		}
		public function get_agendaReturnObject($agendas)
		{
			global $model;
			
			$return		= array();
			foreach($agendas as $a)
			{
				$r_obj= new stdClass;
				$r_obj->ID		= $a->ID;
				$r_obj->dImage	= $model->getProfileImage($a->deputyimage, 48,48, 'cutout');
				$r_obj->dName	= $a->deputyname;
				$r_obj->dPerma	= $a->deputyPerma;
				$r_obj->agendaT	= $a->title;
				$r_obj->myVote	= $a->myvote;
				$r_obj->percent	= $this->get_agendaPercent($a->ID);
				
				$return[]=$r_obj;
			}
			return $return;
		}
		public function get_oldAgenda($start=0, $keyword="")
		{
			global $model,$db;
			$SELECT = "\n SELECT a.*, av.vote AS myvote, p.image AS deputyimage, p.name AS deputyname, p.permalink AS deputyPerma";
			$FROM = "\n FROM agenda AS a";
			$JOIN  = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote($model->profileID);
			$JOIN .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
			$WHERE = "\n WHERE " . $db->quote ( date ( 'Y-m-d H:i:s' ) ) . " > a.endtime";
			$WHERE .= "\n AND a.status>0";
			if ($start > 0)
				$WHERE .= "\n AND a.ID<" . intval ( $start );
			if($keyword!="")
			{
				$WHERE .= "\n AND a.title LIKE '%". $db->escape( $keyword )."%' ";
			}
			$GROUP = "\n ";
			$ORDER = "\n ORDER BY a.ID desc";
			$LIMIT = "\n LIMIT 7";
			
			$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT );
			$agendas = $db->loadObjectList ();
			return $agendas;
		}
		public function set_vekilOyu($deputyID)
		{
			global $model, $db;
			$isOk =	 $this->check_vekilOyu($deputyID);
			if($isOk)
			{
				$vekilOy = new stdClass;
				$vekilOy->profileID = $model->profileID;
				$vekilOy->deputyID = $deputyID;
				$vekilOy->status = 1;
				$vekilOy->datetime = date('Y-m-d H:i:s');
				$vekilOy->ip = $_SERVER['REMOTE_ADDR'];
				
				if ($db->insertObject( 'mydeputy', $vekilOy )) {
					$vekilOy->ID = $db->insertid() ;
					$c_puan = new puan;
					$c_puan->puanIslem($deputyID,"70",$vekilOy);
				   	$return["status"]="success";
				}
				else{
					$return["status"]="error";
					$return["errorMsg"]="Veritabanı Hatası";
				}

			}
			else
			{
				$return["status"]="error";
				$return["errorMsg"]="Bu Kişiye daha önce oy verdiniz.";
			}
			return $return;
		}
		public function check_vekilOyu($deputyID){
			global $model, $db;
			
			$SELECT = "SELECT count(ID) ";
			$FROM 	= "\n FROM mydeputy";
			$WHERE 	= "\n WHERE profileID = " . $db->quote ( intval ( $model->profileID ) );
			$WHERE 	.= "\n AND datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
			$WHERE 	.= "\n AND deputyID = " . $db->quote( intval ( $deputyID ) );
			$WHERE 	.= "\n AND status>0";
			
			$db->setQuery ( $SELECT . $FROM  . $WHERE  );
			
			$oyVerildimi=$db->loadResult ();
			if($oyVerildimi>0){
				return false;
			}
			else {
				return true;
			}
		}
		public function set_proposal($porposalT=""){
			global $model, $db;
        	$return = array();
        	try{
              	if(strlen($porposalT)>200)
				{
					 throw new Exception('Tasarılar enfazla 200 karakter olmalı!');
				}
                
               	//millet vekili mi?
               	if($model->profile->deputy<1) throw new Exception('Tasarı yazmak için Vekil olmak gerek!');                
               		
               	$ppCount=$this->count_poroposal();
				if($ppCount>2){throw new Exception('Bir günde enfazla 3 tasarı gönderebilirsiniz.');};
                
               	$pp = new stdClass;
               	$pp->title       = $porposalT;
               	$pp->spot        = $porposalT;
               	$pp->deputyID    = $model->profileID;
               	$pp->status      = 1;
               	$pp->datetime    = date('Y-m-d H:i:s');
               	$pp->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('proposal', $pp)) {
                    $return['status'] = 'success';
                } else {
                    throw new Exception('kayıt hatası oluştu');
				}

            } catch (Exception $e){
                $return['status'] = 'error';
                $return['message'] = $e->getMessage();
            }
            
            return $return ;
		}
		public function get_proposal()
		{
			global $model, $db;
                        $retunObj=array();
			try { 
                            if($model->profile->deputy<1) throw new Exception('Bu bölümü sadece vekiller görebilir.');  
                            $SELECT = "SELECT pp.*, pr.name, pr.image, pr.permalink";

                            $SELECT .= "\n , (( pp.count_approve  - pp.count_reject) *  pp.count_approve ) AS points";
                            $FROM = "\n FROM proposal AS pp";
                            $JOIN = "\n LEFT JOIN profile AS pr ON pr.ID = pp.deputyID";
                            $WHERE = "\n WHERE pp.datetime>" . $db->quote ( date ( 'Y-m-d H:i:s', LASTPROPOSAL ) );
                            $WHERE .= "\n AND pp.status>0";
                            $WHERE .= "\n AND pp.used<1";
                            $WHERE .= "\n AND pp.st=1";
                                    // $GROUP = "\n GROUP BY ppv.proposalID";
                            $GROUP = "\n GROUP BY pp.ID";
                            $ORDER = "\n ORDER BY points DESC, pp.count_approve DESC, pp.ID ASC";

                            $LIMIT = "\n ";
                            $db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT );

                            $proposals = $db->loadObjectList ();
                            $ids = array();
                            foreach($proposals as $p){
                                    $ro	= new stdClass;
                                    $ro->ID = $p->ID;
                                    $ro->text = $p->spot;
                                    $ro->deputyID = $p->deputyID;
                                    $ro->mecliseAlan = $p->mecliseAlan;
                                    $ro->count_approve = $p->count_approve;
                                    $ro->count_reject = $p->count_reject;
                                    $ro->dName = $p->name;
                                    $ro->dImage = $model->getProfileImage($p->image, 48,48, 'cutout');
                                    $ro->dPerma = $p->permalink;
                                    $ro->time = model::get_beforeTime( strtotime($p->datetime));
                                    $ro->approve =0;
                                    $ro->reject=0;
                                    if($p->deputyID == $model->profileID)
                                        $ro->isMine = TRUE;
                                    else
                                        $ro->isMine = FALSE;
                                    $retunObj['proposal'][]=$ro;
                                    $ids[] = $ro->ID;
                            }
                            
                            $query='SELECT proposalID, approve, reject from proposalvote WHERE proposalID IN ('.implode(' , ', $ids).')';
                            $db->setQuery($query);
                            $votes = $db->loadObjectList ();
                           
                            foreach ($votes as $vote){
                                foreach ($retunObj['proposal'] as $key=>$v){
                                    if($v->ID == $vote->proposalID){
                                        $retunObj['proposal'][$key]->approve = $vote->approve;
                                        $retunObj['proposal'][$key]->reject = $vote->reject;
                                    }
                                }
                            }
                                                        
                            $retunObj['result'] = 'success';
                        }  catch (Exception $e){
                                $retunObj['result'] = 'error';
                                $retunObj['message'] = $e->getMessage();
                        }
			return $retunObj;
		} 

		public static function count_poroposal()
		{
			global $model, $db;
			$db->setQuery("SELECT count(*) FROM proposal WHERE status=1 and st=1 and used=0 and deputyID='".$model->profileID."' and datetime>='".date('Y-m-d 00:00:00')."'");
			return $db->loadResult();
		}
		
		public function count_agenda($type=0,$parentID=0)
		{
			global $model, $db;

			$SELECT = "\n SELECT count(*) ";
            $FROM   = "\n FROM agenda AS a";
            $JOIN   = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote($model->profileID);
            $JOIN  .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
            //$WHERE  = "\n WHERE ".$db->quote(date('Y-m-d H:i:s'))." BETWEEN a.starttime AND a.endtime";            
            $WHERE = "\n  WHERE a.status>0"; 
            $WHERE .= "\n AND av.profileID = " . $db->quote($model->profileID);
            if($type!="0")
			{
				$WHERE .= "\n  AND (a.".$type."='0' or a.".$type."='".$parentID."')";	
			}
			else
			{
				$WHERE .= "\n  AND ( a.regionID IS NULL AND a.countryID IS NULL AND a.cityID IS NULL AND a.hastagID =0 )";	
			}   
             //hastag sayfalarının gündemi yok ve gündemi yoksa  alan boş geliyor tagin gündemi yoksa gerçek meclis gelsin 
            $GROUP  = "\n ";
      
			if($type!="0")
			{
				$ORDER  = "\n ORDER BY a.$type DESC";
				$ORDER  .= "\n , a.ID DESC";
			}else
			{
				$ORDER  = "\n ORDER BY a.ID DESC";
			} 
            $LIMIT  = "\n  LIMIT 7";
            // Bu alanı sunucuya gönderme  // doğru zaman kriteri eklenmeli 
            
            
            $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$GROUP.$ORDER.$LIMIT);
            //echo $db->_sql;
            
            //$db->setQuery('SELECT a.* FROM agenda AS a WHERE '.$db->quote(date('Y-m-d H:i:s')).' BETWEEN a.starttime AND a.endtime ORDER BY ID desc');
           

			return 7-$db->loadResult();
		}
                
                public static function set_proposal_vote($id,$value){
                    global $model, $db;
                    try{
                        if($model->profile->deputy!=1)
                            throw  new Exception('verkil degil');
                        
                        $id = intval($id);
                        $value = intval($value);
                        $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING ) ;
                        $QUERY  = 'SELECT count(ID) '. 
                                  'FROM proposalvote '.
                                  'WHERE proposalID='.$id.' AND '.
                                  'deputyID='.$model->profileID.' AND '.
                                  'status = 1  '.
                                  '';
                        $db->setQuery($QUERY);
                        $count = $db->loadResult();
                        if($value>0){
                            $approve = 1;
                            $reject = 0;
                            $q = ' approve=1, reject=0 ';
                        }else{
                            $approve = 0;
                            $reject = 1;
                            $q = ' approve=0, reject=1  ';
                        }
                        if($count<1){
                            //insert
                            $QUERY = 'INSERT INTO proposalvote (proposalID,deputyID,approve,reject,status, datetime, ip) VALUES'.
                                "($id,$model->profileID,$approve,$reject,1,NOW(),'$ip')";
                            $db->setQuery($QUERY);
                            if(!$db->query()) throw new Exception('db error2');
                            
                        }else{
                            //update
                            $QUERY = 'UPDATE proposalvote SET '.$q.', datetime = NOW(), '." ip = '$ip' ".
                                  'WHERE proposalID='.$id.' AND '.
                                  'deputyID='.$model->profileID.' AND '.
                                  'status = 1  '.
                                  '';
                            //echo $QUERY;
                            $db->setQuery($QUERY);
                            if(!$db->query()) 
                                throw new Exception('db error2');
                        }
                    }  catch (Exception $e){
                        //echo $e->getMessage();
                        return false;
                    }
                    return true;
                }
                
                public static function proposal_delete($ID){
                    global $model,$db;
                    try {
                        $uID = $model->profileID;
                        $QUERY = "UPDATE proposal SET status = 0 WHERE ID=$ID AND deputyID = $uID AND status>0";
                        $db->setQuery($QUERY);
                        if(!$db->query())
                            throw new Exception('hata');
                    }  catch (Exception $e){
                        return FALSE;
                    }
                    
                    return TRUE;
                }
	}
?>
