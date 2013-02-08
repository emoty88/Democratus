<?php
    class propostal{
        
        static public function getbuttons($ID){
            global $model, $db, $l;
            
            //myvote'yi bul
            $db->setQuery('SELECT approve, reject, complaint FROM propostalvote WHERE deputyID='.$db->quote($model->profileID).' AND propostalID = ' . $db->quote($ID) );
            $myvote = null;
            if($db->loadObject($myvote)){
                
            } else {
                $myvote = null;  //die('null');
            }
            
            
            
            $db->setQuery( 'SELECT SUM(approve) AS approve, SUM(reject) AS reject, SUM(complaint) AS complaint FROM propostalvote WHERE propostalID = ' . $db->quote($ID));
            if( $db->loadObject($counts) ){
                
            } else {
                $counts = stdClass;
                $counts->approve = 0;
                $counts->reject = 0;
                $counts->complaint = 0;
                $counts = null;
            }
            
            $result = '';
            $response['ID'] = $ID;
            foreach(config::$propostalvotetypes as $votetype){
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
                        
                    //benim seçimim değil ise    
                    } else {
                        if(intval( $counts->$votetype )>0)
                            //$votecount = '( <span class="ppvotecount">'.$counts->$votetype.'</span> ) ';
                            $votecount = ' ('.$counts->$votetype.') ';
                        else
                            $votecount = '';
                        
                        $result .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote" onclick="javascript:ppvote('.$ID.',\''.$votetype.'\')">'.$l[$votetype].' '.$votecount.'</span> ';
                        
                    }

  
                } else {
                    $result .= '<span id="pp'.$votetype.'_'.$ID.'" class="ppvote" onclick="javascript:ppvote('.$ID.',\''.$votetype.'\')">'.$l[$votetype].'</span> ';
                    
                }
                
            }
            $response['result'] = 'success';
            $response['html'] = $result;
            
            return $response;
            
        }
    }
?>