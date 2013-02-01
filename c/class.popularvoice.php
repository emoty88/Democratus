<?php
class popularvoice extends voice
{
	function popularvoice()
	{
		
	}
	function get_popularVoice($start = 0 , $limit = 7 , $onlyProfile = 0)
	{
		global $model, $db;
		//(takdir/ (takdir+saygı)) -(şikayet/2 )
     	$SELECT = "SELECT DISTINCT 	di.*, 
     								sharer.ID AS sharerID, 
     								sharer.image AS sharerimage, 
     								sharer.name AS sharername, 
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
        $WHERE  = "\n WHERE di.datetime > DATE_ADD(NOW(), INTERVAL -1 DAY)"; // public çalışmalı
        $WHERE .= "\n AND di.ID = dilike.diID"; // AND
        $WHERE .= "\n AND di.status>0";
        $WHERE .= "\n AND di.popularstatus>0";
        if($start>0){
        	$WHERE .= "\n AND di.ID<" . $db->quote($start);
        }
		if($onlyProfile==0){
			$WHERE .= "\n AND onlyProfile='0'";
		}
        	
       	$GROUP  = "\n GROUP BY dilike.diID";
        $ORDER  = "\n ORDER BY popularite DESC";
        $LIMIT  = "\n LIMIT $limit";
        // Online Siteye atma local de çalışması için 
        //echo $SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT;
		//die;
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
}
?>