<?php
	class tag extends profile{
		public $tagID;
		public $tagName;
		public $permalink;
		public $_tag;
		public $_admins;
		public $_cons=0;
		public function __construct($tag=0)
		{
			global $model;
			if(is_object($tag))
			{
				$this->_tag=$tag;
				$this->tagID=$tag->ID;
				$this->tagName=$tag->name;
				$this->permalink=$tag->permalink;
				
				$this->set_admins();
				$this->_cons=1;
			}
			else if($tag!=0){
				$this->__constructPerma($tag);
			}
		}
		public function __constructPerma($perma)
		{
			$this->__construct($this->get_porfileObject($perma));
		}
		public function set_admins()
		{
			global $db;
			$SELECT	= "SELECT p.* ";
			$FROM	= "\n FROM profile as p";
			$JOIN	= "\n LEFT JOIN hashProfileRelation as rel on p.ID=rel.profileID";
			$WHERE	= "\n WHERE p.status='1' and rel.hashtagID='".$this->tagID."' and rel.status='1' ";
			
			$db->setQuery($SELECT.$FROM.$JOIN.$WHERE);
			$this->_admins=$db->loadObjectList("ID");
		}
		public function add_tagProposal($permaLink="", $proposalText)
		{
			global $model, $db;
			$tagID=$this->change_perma2ID($permaLink);
			

			
			$urlS=new urlshorter;
			$share=new stdClass;
			
			$share->di=strip_tags( html_entity_decode( htmlspecialchars_decode($proposalText, ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) );
			$share->di=$urlS->changeUrlShort($share->di); 
            $share->di=  mb_substr($share->di , 0, 200 ) ; 
            $share->onlyProfile=0;
            
            $share->datetime    = date('Y-m-d H:i:s');
            $share->rediID   	= 0;
            $share->ip          = $_SERVER['REMOTE_ADDR'];
			$share->initem		= @$_POST["initem"];
			$share->profileID	= $tagID;
			$share->profileType	= "tagPage";
			
			if($db->insertObject('di', $share,"ID"))
			{
				$share->ID=$db->insertid();
				$agenda=new stdClass;
				
				$agenda->title=$share->di;
				$agenda->diID=$share->ID;
				$agenda->hastagID=$tagID;
				$agenda->deputyID=$tagID;
				$agenda->status=1;
				$db->insertObject('agenda', $agenda,"ID");
				//BURada yarım kaldı devam et
			}
			return $tagID ;
		}
		public function get_tagAgenda()
		{
			global $model,$db;
			$SELECT	= "SELECT * ";
			$FROM	= "\n FROM agenda ";
			$WHERE	= "\n WHERE hastagID='".$this->_tag->ID."' and status='1'  ";
			$ORDER	= "\n ORDER BY ID desc ";
			$db->setQuery($SELECT.$FROM.$WHERE.$ORDER);
			return $db->loadObjectList();
		}
		public function remove_tagProposal($permaLink="", $ID)
		{
			global $model, $db;
			//$tagID=$this->change_perma2ID($permaLink);
			
			if($this->_cons==0)
			{
				$this->__constructPerma($permaLink);
			}
			if($this->is_admin())
			{
				$prop=new stdClass;
				$prop->ID=$ID;
				$prop->status=0;
				return $db->updateObject("agenda",$prop,"ID");
			}
			else {
				return 3;
			}
			//return $this->_tag;
		}
		public function is_admin($profileID=0,$perma=0)
		{
			global $model;
			
			if($profileID==0)
			{
				$profileID=$model->profileID;
			}
			if($perma!=0)
			{
				$this->__constructPerma($perma);
			}
			return array_key_exists($profileID, $this->_admins);
		}
		public function get_hashtagSugg($profileID=0)
		{
			global $model, $db;
			if($profileID==0)
			{
				$profileID=$model->profileID;
			}
			
			$SELECT = "SELECT ID, permalink, image, name, motto ";
			$FROM 	= "\n FROM profile";
			$WHERE	= "\n WHERE type='hashTag' ";
			$ORDER	= "\n ORDER BY count_voice" ;
			$LIMIT	= "\n LIMIT 10";
			
			$db->setQuery($SELECT.$FROM.$WHERE.$ORDER.$LIMIT);
			$hashtags=$db->loadObjectList();
			return $this->get_MultiReturtnObj($hashtags);
		}
		function get_MultiReturtnObj($profiles)
		{
			global $model;
			$returnObj=array();
			foreach($profiles as $p)
			{
				 $ro	= new stdClass;
				 $ro->ID		= $p->ID;
				 $ro->pPerma	= $p->permalink;
				 $ro->pImage	= $model->getProfileImage($p->image, 45,45, 'cutout');
				 $ro->pName		= $p->name;
				 $ro->pMotto	= $p->motto;
				 $ro->ismyFollow= $this->isFollow($ro->ID);
				 $returnObj[]	= $ro;
			}
			return $returnObj;
		}
	}
?>
