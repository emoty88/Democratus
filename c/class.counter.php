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
}
?>