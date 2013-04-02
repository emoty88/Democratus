<?php 
error_reporting(1);// sonra kapat
class messageClass {
	public $connection;
	public $database;
	public $collection;

	function messageClass() {
		$this -> connection = new Mongo();
		
		//$this->db = $this->connection->selectDB(messageDB);
		//$this->connection->authenticate("mongomessage","70gAh5LC21");
		$this->database = $this->connection->selectDB(messageDB);
		$this->database->authenticate(mongoMessageUser,mongoMessagePass);
		$this -> collection = $this->database-> selectCollection(messageCollection);
	}

	function insertMessage($fromID, $toID, $message) {
		$fromID = intval($fromID);
		$toID = intval($toID);
		$data = array(
			 'toID' => $toID,
			 'fromID' => $fromID,
			 'insertTime' => time(),
			 'statusTo' => TRUE,
			 'statusFrom' => TRUE, 
			 'read' => FALSE, 
			 'readTime' => time(), 
			 'message' => mb_substr($message, 0, messageSize,'UTF-8') // Caner 18.12.12 substr son on karakterde türkçe karakter olunca sorun çıkartıyordu
		);

		return $this -> collection -> insert($data);
	}

	function getMessageByID($ID, $limit = 1) {
		$cursor = $this -> collection -> find(array('_id' => new MongoId($ID))) -> limit($limit);
		$messages = array();
		
		while ($cursor -> hasNext()) {
			$messages[] = $cursor -> getNext();
		}
		
		return $messages;
	}

	/*
	 * @params userID : kullanıcımızın, user2ID konuştuğu kişinin ID si
	 */
	function getDialog($userID, $user2ID , $before = NULL, $limit = getMesssageLimit, $order = array('insertTime' => mongoDESC , '_id'=> mongoASC )) {
			
		$userID = intval($userID);
		$user2ID = intval($user2ID);
		$result = array();
		$query = array(
			'$or' => array(
						array(
							'$and' => array(
								array("toID" => $userID),
								array("fromID" => $user2ID),
								array("statusTo" => TRUE)
							)	
						),
						array(
							'$and' => array(
								array("toID" => $user2ID),
								array("fromID" => $userID),
								array("statusFrom" => TRUE)
							)	
						)						
			)	
		);
		
		if($before)
			$query['_id'] = array('$lt' => new MongoId($before));
		
		//print_r($query);
		
		$q = $this -> collection ->find($query)->sort($order)->limit($limit);
		$readded = 0;
		
		while ($q -> hasNext()) {
			$result[] = $q -> getNext();			
		}
		
		if(sizeof($result)>0){
			$query = array();
			$query = array('toID'=>$userID,'read'=>FALSE);
			$set =  array(
						'$set'=>array(
							'read'=>TRUE
						)
					);
					
					
			$this->collection->update($query,$set,array('multiple'=>TRUE));
		}
		
		return array_reverse($result);
	}
	
	
	//okunmamış mesajlar
	function getCount($userID){
		//sleep(2);	
		$userID = intval($userID);
		$query = array(
			'read' => FALSE,
			'toID' => $userID,
			'statusTo' => TRUE
		);	
                
                $cmd =  array(
                            "distinct" => "message",
                            "key" => "fromID", 
                            "query" => $query
                        );
		$result = $this->database->command($cmd);
		return sizeof($result['values']);
                
	}
	
	//dialogda $before dan önceki mesajların sayısı (daha fazla yükle olayı)
	function getCountHistory($userID, $user2ID, $before = NULL){
		$userID = intval($userID);
		$user2ID = intval($user2ID);
		$query = array(
			'$or' => array(
						array(
							'$and' => array(
								array("toID" => $userID),
								array("fromID" => $user2ID),
								array("statusTo" => TRUE)
							)	
						),
						array(
							'$and' => array(
								array("toID" => $user2ID),
								array("fromID" => $userID),
								array("statusFrom" => TRUE)
							)	
						)						
			)	
		);
		
		if($before)
			$query['_id'] = array('$lt' => new MongoId($before));
		
		return $this->collection->find($query)->count();
	}
	
	
	function subval_sort($a,$subkey,$limit=NULL) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		arsort($b);
		$i=$limit;
		foreach($b as $key=>$val) {
			if($limit){
				if($i==0)	break;
				$i--;
			}
			$c[] = $a[$key];
		}
		return $c;
	}
	function getDialogList($userID,$limit=NULL){
		global $model;
		$userID = intval($userID);
		$ids =array();
		$dialogs = array();
		$query = array(
			
				'$or' => array(
					array('toID'=>$userID,'statusTo'=>TRUE),
					array('fromID'=>$userID, 'statusFrom'=>TRUE)
				)
		);

		//status ekle

		$result = $this->collection->group(
			array(
				//'_id'=>FALSE,
				'toID'=>TRUE,
				'fromID'=>TRUE,
				/*'message'=>FALSE,
				'statusFrom'=>FALSE,
				'statusTo'=>FALSE,
				'read'=>FALSE,*/
				//'insertTime'=>FALSE/*,
				//'readTime'=>FALSE*/
			),
			new stdClass,
			"function(){}",
			$query
		);
		
		$i = $limit;
		
		
		foreach ($result['retval'] as $key => $r) {
			if(!in_array($r['toID'], $ids) and $userID != $r['toID']){
				array_push($ids,$r['toID']);
				$dialogs[]= $r;
			} elseif(!in_array($r['fromID'], $ids) and $userID != $r['fromID']){
				array_push($ids,$r['fromID']);
				$dialogs[] = $r;
			}
			
			
		}
		
		$dialogsLastMessages = array();
		//$dialogs = $this->subval_sort($dialogs, 'insertTime') ;
		$id = intval($userID);
		
		//$dialogs = $this->subval_sort($dialogs,'inserTime');
		foreach ($dialogs as $key => $dialog) {
			
			$query = array( 
				
					'$or' => array(
							array(
								'$and' => array(
									array("toID" => $id),
									array("fromID" => $dialog['fromID']),
									array("statusTo" => TRUE)
								)	
							),
							array(
								'$and' => array(
									array("toID" => $dialog['fromID']),
									array("fromID" => $id),
									array("statusFrom" => TRUE)
								)	
							),
							array(
								'$and' => array(
									array("toID" => $dialog['toID']),
									array("fromID" => $id),
									array("statusFrom" => TRUE)
								)	
							),
							array(
								'$and' => array(
									array("toID" => $id),
									array("fromID" => $dialog['toID']),
									array("statusTo" => TRUE)
								)	
							)						
					)
				
			);
			
			$cursor = $this->collection->find($query)->sort(array('_id'=>-1))->limit(1);
			
			$a_dialog = $cursor->getNext();
			if(is_array($a_dialog))
			$dialogsLastMessages[]= $a_dialog;
		}
		/*
		echo "<pre>";
		print_r($dialogsLastMessages);
		echo "</pre>";
		*/
		
		$dialogsLastMessages = $this->subval_sort($dialogsLastMessages, 'insertTime',$limit) ;
		return $dialogsLastMessages;
	}
	function getDialogDetailRObj($dialog, $profileID=null)
	{
		global $model;
		$returnA = array();
		$c_profile = new profile;
		
		if($profileID==null)
		{
			$profileID = $model->profileID;
		}
		foreach ($dialog as $d) {
			$ro = new stdClass;
			$ro->ID = (string)  $d["_id"];
			if($d["fromID"] == $profileID)
			{
				$ro->me = TRUE;
			}
			else
			{
				$ro->me = FALSE;
			}
			$ro->fID = $d["fromID"];
			$ro->fName = $c_profile->get_name($ro->fID);
			$ro->fPerma = $c_profile->change_ID2perma($ro->fID);
			$ro->fImage = $c_profile->get_profileImage($ro->fID,48,48);
			$ro->message = $d["message"];
			$ro->mTime = $model->get_beforeTime($d["insertTime"]);
			$ro->mTimeFull = date("d-m-Y H:i:s",$d["insertTime"]);
			$returnA[] = $ro;
		}
		return $returnA;
	}
	function getDialogListRObj($dialogs, $profileID=null)
	{
		global $model;
		$returnA = array();
		$c_profile = new profile;
		if($profileID==null)
		{
			$profileID = $model->profileID;
		}
		
		foreach($dialogs as $d)
		{
			$ro = new stdClass;
			$ro->ID = (STRING) $d["_id"];
			if($d["fromID"] == $profileID){
				$ro->fID = $d["toID"];
			}
			else {
				$ro->fID = $d["fromID"];
			}
			if($model->profileID=="1734")
			{
				$ro->toID = $d["toID"];
				$ro->fromID = $d["fromID"];
			}
			$ro->fName = $c_profile->get_name($ro->fID);
			$ro->fPerma = $c_profile->change_ID2perma($ro->fID);
			$ro->fImage = $c_profile->get_profileImage($ro->fID,32,32);
			$ro->message = $d["message"];
			$ro->mTime = $model->get_beforeTime($d["insertTime"]);
			$ro->mTimeFull = date("d-m-Y H:i:s",$d["insertTime"]);
			if($ro->fID == $model->profileID)
				$ro->me = 1;
			else
				$ro->me = 0;
			$ro->read = $d['read'];
			$returnA[] = $ro;
		}
		return $returnA;
	}
	function delete($userID, $user2ID, $IDs, $all = FALSE){
		$userID = intval($userID);
		$user2ID = intval($user2ID);
		
		//echo $user2ID.$userID;
		if(is_array($IDs)  and !$all){
			foreach($IDs as $value){
				$_IDs[] = new MongoId($value);
			}
			
			
				$query = array(
					//'$or' => array(
							//	array(
									'$and' => array(
										array("toID" => $userID),
										array("fromID" => $user2ID),
										array("statusTo" => TRUE)
									)	
					//			)					
					//)
				);
				
				//$query['_id'] = array('$in' => $_IDs);
				
				$q = $this->collection->update($query,array('$set'=>array('statusTo' => FALSE)),array('multiple'=>TRUE));
				
				//////////////////////////
				
				$query = array(
					//'$or' => array(
							//	array(
									'$and' => array(
										array("toID" => $user2ID),
										array("fromID" => $userID),
										array("statusFrom" => TRUE)
									)	
					//			)					
					//)
				);
				//$query['_id'] = array('$in' => $_IDs);
				
				$q = $this->collection->update($query,array('$set'=>array('statusFrom' => FALSE)),array('multiple'=>TRUE));
				
		}elseif($all){
			
			$query = array(

									'$and' => array(
										array("toID" => $userID),
										array("fromID" => $user2ID),
										array("statusTo" => TRUE)
									)	

				);

				
				$q = $this->collection->update($query,array('$set'=>array('statusTo' => FALSE)),array('multiple'=>TRUE));
				//////////////////////////
				$query = array(
									'$and' => array(
										array("toID" => $user2ID),
										array("fromID" => $userID),
										array("statusFrom" => TRUE)
									)	
				);
				$q = $this->collection->update($query,array('$set'=>array('statusFrom' => FALSE)),array('multiple'=>TRUE));		
			return $q;
		}
		print_r($result);	
	}
}
?>