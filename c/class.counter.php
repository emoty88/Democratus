<?php
class counter {
	public function counter()
	{
		
	}
	public function set_profileCount($profileID, $countT, $operation="+")
	{
		global $model, $db;
		$cType="count_".$countT;
		$db->setQuery("SELECT ".$cType." FROM profile WHERE ID='".$profileID."' ");
		$count=$db->loadResult();
		//var_dump($count);
		
		$prf		=new stdClass;
		$prf->ID	=$profileID;
		if($operation=="-")
			$prf->$cType = $count-1;
		else
			$prf->$cType = $count+1;

		return $db->updateObject("profile", $prf, "ID");
	}
	public function set_voiceCount($voiceID, $countT, $operation="+")
	{
		global $model, $db;
		$cType="count_".$countT;
		$db->setQuery("SELECT ".$cType." FROM di WHERE ID='".$voiceID."' ");
		$count=$db->loadResult();
		
		
		$di	= new stdClass;
		$di->ID	= $voiceID;
		if($operation=="-")
			$di->$cType = $count-1;
		else
			$di->$cType = $count+1;
		
		return $db->updateObject("di",  $di, "ID");
	}
	public function set_proposalCount($ppID=0, $choise="", $operation="+")
	{ 
		global $model, $db;

		if($ppID==0 || $choise=="")
		{
			return false;
		}
		$cType="count_".$choise;
		$db->setQuery("SELECT ".$cType." FROM proposal WHERE ID='".$ppID."' ");
		$count=$db->loadResult();
		
		
		$ppO		= new stdClass;
		$ppO->ID	= $ppID;

		if($operation=="-")
			$ppO->$cType = $count-1;
		else
			$ppO->$cType = $count+1;

		return $db->updateObject("proposal",  $ppO, "ID");
	}
}
?>