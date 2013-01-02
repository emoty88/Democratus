<?php
    class proposal{
        static public function getbuttons($ID){
            global $model, $db, $l;
        
            //myvote'yi bul
            $db->setQuery('SELECT approve, reject, complaint FROM proposalvote WHERE deputyID='.$db->quote($model->profileID).' AND proposalID = ' . $db->quote($ID) );
            $myvote = null;
            if($db->loadObject($myvote)){
                
            } else {
                $myvote = null;  //die('null');
            }
            
            
            
            $db->setQuery( 'SELECT SUM(approve) AS approve, SUM(reject) AS reject, SUM(complaint) AS complaint FROM proposalvote WHERE proposalID = ' . $db->quote($ID));
            if( $db->loadObject($counts) ){
                
            } else {
                $counts = stdClass;
                $counts->approve = 0;
                $counts->reject = 0;
                $counts->complaint = 0;
                $counts = null;
            }
            
            $result = '';
            $resultNew = '';
            $response['ID'] = $ID;
            foreach(config::$proposalvotetypes as $votetype){
                if(!is_null( $counts )){//her hangi bir like bulunamadı ise
                    //Takdir et vs'yi yaz
                    
                    
                    //benim seçimim ise
                    if( !is_null($myvote) && intval( $myvote->$votetype ) > 0){
                        if(intval( $counts->$votetype )>1)
                            //$votecount = '( <span class="ppvotecount">'.$counts->$votetype.'</span> ) ';
                            $votecount = ' ('.$counts->$votetype.') ';
                        else
                            $votecount = '';

                        $result .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote">'.$l[$votetype.'voted'].' '.$votecount.'</span> ';
                        $resultNew .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote">'.$l[$votetype.'voted'].' '.$votecount.'</span> | ';
                        
                    //benim seçimim değil ise    
                    } else {
                        if(intval( $counts->$votetype )>0)
                            //$votecount = '( <span class="ppvotecount">'.$counts->$votetype.'</span> ) ';
                            $votecount = ' ('.$counts->$votetype.') ';
                        else
                            $votecount = '';
                        
                        $result .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote" onclick="javascript:ppvote('.$ID.',\''.$votetype.'\')">'.$l[$votetype].' '.$votecount.'</span> ';
                        $resultNew .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote" onclick="javascript:ppvote('.$ID.',\''.$votetype.'\')">'.$l[$votetype].' '.$votecount.'</span> | ';
                        
                    }

  
                } else {
                    $result .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote" onclick="javascript:ppvote('.$ID.',\''.$votetype.'\')">'.$l[$votetype].'</span> ';
                    $resultNew .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote" onclick="javascript:ppvote('.$ID.',\''.$votetype.'\')">'.$l[$votetype].'</span> | ';
                    
                }
                
            }
            $response['result'] = 'success';
            if($model->newDesign)
            $response['html'] = $resultNew;
   			else 
   			$response["html"] = $result;
            return $response;
            
        }
		public static function check_flashProposal($ID=0)
		{
			global $model,$dbez;
			$returnValue="";
			if($ID>!0)
			{
				$returnValue=false;
			}
		
			$todayUsedCount=self::get_flashInAgendaCount();
			if($todayUsedCount>3)
			{
				$returnValue=false;
			}
			else {
				$query="SELECT * FROM proposalvote where status=1 and proposalID='".intval($ID)."'";
				$votes=$dbez->get_results($query);
				$approveVoteCount=0;
				if(count($votes)>10)
				{
					foreach($votes as $v)
					{
						if($v->approve==1)
						$approveVoteCount++;
					}
					$oran=intval((count($votes)/10)*6);
					if($approveVoteCount>$oran)
					$returnValue = true;
				}else
				{
					$returnValue=false;
				}
			}
			return $returnValue;
		}
		public static function set_proposalToAgenda($ID)
		{
			global $model, $dbez, $db;
			
			$starttime  = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
            $endtime    = mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
				
			$SELECT = "SELECT pp.*, pr.name, pr.image, u.email, pr.emailperms";
            $FROM   = "\n FROM proposal AS pp,  profile AS pr, user AS u";
            $WHERE  = "\n WHERE pp.status>0";
            $WHERE .= "\n AND pp.used=0"; //kullanılmamışsa
            $WHERE .= "\n AND pr.ID = pp.deputyID";
            $WHERE .= "\n AND u.ID = pr.ID";
			$WHERE .= "\n AND pp.st=1";
			$WHERE .= "\n AND pp.ID='".$ID."'";
		
            $proposal=$dbez->get_row($SELECT . $FROM  . $WHERE );
			if($proposal)
			{
				
				// Ses olarak Ekliyoruz
				$di = new stdClass;
          		$di->di          = $proposal->title;   
        		$di->datetime    = date('Y-m-d H:i:s');
         		$di->profileID   = intval( $proposal->deputyID );
     			$di->ip          = $_SERVER['REMOTE_ADDR'];
             	
             	if( $db->insertObject('di', $di) ){
                  	$diID = intval( $db->insertid() );    
             	} else {
                    $diID = 0;
            	}
			
				//gündem olarak ekliyoruz
				$agenda = new stdClass;
                $agenda->title      = $proposal->title;
                $agenda->spot       = '';// $row->spot;
                $agenda->proposalID = $proposal->ID;
                $agenda->deputyID   = $proposal->deputyID;
				$agenda->mecliseAlan= $proposal->mecliseAlan;
                $agenda->starttime  = date('Y-m-d H:i:s', $starttime);
                $agenda->endtime    = date('Y-m-d H:i:s', $endtime );
                $agenda->diID       = $diID; //**************/
                $agenda->status     = 1;
				 
				 if($db->insertObject('agenda', $agenda)){
				 	if($proposal->emailperms>0)
                    	$model->sendsystemmail($proposal->email, 'Tasarınız meclis gündemine seçildi!', 'Tebrik ederiz, <br /> Mecliste gün boyu süren oylamada sizin tasarınız diğer vekillerin onayları ile ülke gündemine seçildi ve tüm democratus kullanıcılarının oylamasına sunuldu.  Başarılarınızın devamını dileriz. <br /><br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                   	
					$sucPro = new stdClass;
                    $sucPro->ID = $proposal->ID;
                    $sucPro->used = 1; 
					$db->updateObject('proposal', $sucPro, 'ID', 0);
					return true;
				}
			}
			else
			{
				return false;
			}
			//Gündmi girecek
			
		}
		public static function set_popularDitoProposal($popularDiID)
		{
			global $model, $dbez,$db;
			
			if($model->profile->deputy<1)
			{
				return false;
			}
			if(self::get_p2PoroposalCount()>2)
			{
				return false;
			}
			$popularDi=$dbez->get_row("SELECT * FROM di WHERE ID='".$popularDiID."'");
			if($popularDi)
			{
				$pp = new stdClass;
				
				$pp->title       = $popularDi->di;
                $pp->spot        = $popularDi->di;
                $pp->deputyID    = $popularDi->profileID;
				$pp->mecliseAlan = $model->profileID;
				$pp->popularDiID = $popularDi->ID;
				$pp->status      = 1;
                $pp->datetime    = date('Y-m-d H:i:s');
                $pp->ip          = $_SERVER['REMOTE_ADDR'];
                
                if($db->insertObject('proposal', $pp)) {
                   	return true;
                } 
                else  
				{
					return false;
				}
			}
		}
		public static function get_p2PoroposalCount()
		{
			global $model, $dbez;
			return $dbez->get_var("SELECT count(*) FROM proposal WHERE status=1 and st=1 and used=0 and mecliseAlan='".$model->profileID."' and datetime>='".date('Y-m-d 00:00:00')."'");//bu gün
		}
		public static function get_poroposalCount()
		{
			global $model, $dbez;
			return $dbez->get_var("SELECT count(*) FROM proposal WHERE status=1 and st=1 and used=0 and deputyID='".$model->profileID."' and datetime>='".date('Y-m-d 00:00:00')."'");//bu gün
		}
		public static function get_agendaLimit()
		{
			global $dbez;
			$limit=config::$agendaelectionlimit-self::get_flashInAgendaCount();
			return $limit;
		}
		public static function get_flashInAgendaCount()
		{
			global $dbez;
			$queryUsed="SELECT count(*) FROM  proposal where used=1 and datetime>='".date('Y-m-d 00:00:00')."'";
			return $dbez->get_var($queryUsed);	
		}
		public static function check_popular2proposal($popularID)
		{
			global $dbez;
			$varmi=$dbez->get_var("SELECT count(ID) from proposal WHERE status=1 and popularDiID='".$popularID."'");
			if($varmi>0)
			{
				return false;
			}
			else {
				return true;
			}
		}
    }
?>
