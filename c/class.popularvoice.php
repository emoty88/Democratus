<?php
class popularvoice extends voice
{
	function popularvoice()
	{
		
	}
	function get_popularVoice($start = 0 , $limit = 7 , $sLimit=0, $onlyProfile = 0, $zeroCond=false)
	{
		global $model, $db;
		//(takdir/ (takdir+saygı)) -(şikayet/2 )
		if($zeroCond)
		{
				$SELECT = "SELECT DISTINCT 	di.*, 
	     								sharer.ID AS sharerID, 
	     								sharer.image AS sharerimage, 
	     								sharer.name AS sharername, 
	     								sharer.deputy AS sharerDeputy, 
	     								redier.name AS rediername, 
	     								sharer.deputy AS deputy,
	        							sharer.permalink as permalink";
				$FROM   = "\n FROM di";
				$JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
	       		$JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";   
				$WHERE  = "\n WHERE di.status>0";
				$GROUP  = "\n"; 
	        	$ORDER  = "\n ORDER BY ID DESC";
	        	$LIMIT  = "\n LIMIT $sLimit, $limit";
		}
		else
		{
			
		
	     	$SELECT = "SELECT DISTINCT 	di.*, 
	     								sharer.ID AS sharerID, 
	     								sharer.image AS sharerimage, 
	     								sharer.name AS sharername, 
	     								sharer.deputy AS sharerDeputy, 
	     								redier.name AS rediername, 
	     								sharer.deputy AS deputy,
	        							sharer.permalink as permalink";
	        $SELECT.= ", count(dilike.ID) AS toplamoy, sum(dilike.dilike1) AS takdir, sum(dilike.dilike2) AS saygi";
	        //$SELECT.= ", (SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID ) AS complaint";
	        //$SELECT.= ",( sum(dilike.dilike1) - sum(dilike.dilike2) - ((SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID )*2))  AS popularite";
	        $SELECT.= ", (sum(dilike.dilike1)*3+sum(dilike.dilike2)*1+(SELECT count(ID) FROM di AS diredi WHERE diredi.redi=di.ID )*10) popularite";
	        $FROM   = "\n FROM dilike, di";
	        $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
	        $JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";            
	        $WHERE = "\n WHERE di.status>0";
	        if($_SERVER['SERVER_NAME']=="democratus.com")
	            {
					$WHERE  .= "\n AND di.datetime > DATE_ADD(NOW(), INTERVAL -1 DAY)"; 
				}  
	        $WHERE .= "\n AND di.ID = dilike.diID"; // AND
	        $WHERE .= "\n AND di.popularstatus>0";
	        if($start>0){
	        	$WHERE .= "\n AND di.ID<" . $db->quote($start);
	        }
			if($onlyProfile==0){
				$WHERE .= "\n AND onlyProfile='0'";
			}
	        	
	       	$GROUP  = "\n GROUP BY dilike.diID";
	        $ORDER  = "\n ORDER BY popularite DESC";
	        $LIMIT  = "\n LIMIT $sLimit, $limit";
		}

        $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
        $rows = $db->loadObjectList();
		if(count($rows))
		{
			
			foreach($rows as $row)
			{
				//if(!profile::isallowed($row->profileID, $row->showdies)) continue; //seslerini gizledi ise bu özellik kaldırıldı 
				$voices[]	= $this->get_return_object($row,22,22);
			}
		}
		else
		{
			$voices = false;
		}
		return $voices;
	}
	function get_popularVoiceHashT($hashTag)
	{
		global $model, $db;
		$SELECT = "SELECT DISTINCT 	di.*, 
     								sharer.ID AS sharerID, 
     								sharer.image AS sharerimage, 
     								sharer.name AS sharername, 
     								sharer.deputy AS sharerDeputy, 
     								redier.name AS rediername, 
     								sharer.deputy AS deputy,
        							sharer.permalink as permalink";
        $SELECT.= ", count(dilike.ID) AS toplamoy, sum(dilike.dilike1) AS takdir, sum(dilike.dilike2) AS saygi";
        //$SELECT.= ", (SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID ) AS complaint";
        //$SELECT.= ",( sum(dilike.dilike1) - sum(dilike.dilike2) - ((SELECT count(ID) FROM dicomplaint AS dc WHERE dc.diID=di.ID )*2))  AS popularite";
        $SELECT.= ", (sum(dilike.dilike1)*3+sum(dilike.dilike2)*1+(SELECT count(ID) FROM di AS diredi WHERE diredi.redi=di.ID )*10) popularite";
        $FROM   = "\n FROM dilike, di";
        $JOIN   = "\n LEFT JOIN profile AS sharer ON sharer.ID = di.profileID";
        $JOIN  .= "\n LEFT JOIN profile AS redier ON redier.ID = di.redi";            
        $WHERE = "\n WHERE di.status>0";
        if($_SERVER['SERVER_NAME']=="democratus.com")
            {
				$WHERE  .= "\n AND di.datetime > DATE_ADD(NOW(), INTERVAL -4 DAY)";  // hastagler için  son 4 gün
			}  
        $WHERE .= "\n AND di.ID = dilike.diID"; // AND
        $WHERE .= "\n AND di.popularstatus>0";
		$WHERE .= "\n AND (di.di  LIKE '%". $db->escape( "#".$hashTag )."%')";
		$GROUP  = "\n GROUP BY dilike.diID";
        $ORDER  = "\n ORDER BY popularite DESC";
        $LIMIT  = "\n LIMIT 7";
        
        $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
        $rows = $db->loadObjectList();
		if(count($rows)){
			foreach($rows as $row){
				//if(!profile::isallowed($row->profileID, $row->showdies)) continue; //seslerini gizledi ise bu özellik kaldırıldı 
				$voices[]	= $this->get_return_object($row,22,22);
			}
		}
		else{
			$voices = false;
		}
		return $voices;
	}
}
?>