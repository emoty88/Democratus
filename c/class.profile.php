<?php
    class profile{
    	public $profile;
		public $profileID;
		public $_isAdmin = false;
        public function __construct($profile=null)
		{
			global $model;
			
			if(is_object($profile))
          	{
            	$this->profile=$profile;
				$this->profileID=$profile->ID;
            }
			else if($profile!=null){
				$this->profile	= $this->get_porfileObject($profile);
				$this->profileID= $this->profile->ID;
			}
			else {
				$this->profile	= $model->profile;
				$this->profileID= $model->profileID;
			}
			if($this->profileID == $model->profileID)
				$this->_isAdmin = true;
		}
		public function get_porfileObject($KEY)
		{
			global $model, $db;
			
			if(!is_object($KEY) && !is_array($KEY))
			{
				$SELECT = "SELECT *";
		        $FROM   = "\n FROM profile ";
				$WHERE  = "\n WHERE ID=".$db->quote($KEY)." OR permalink like '".$KEY."'";
			    $WHERE .= "\n AND status>0";
		        $ORDER  = "\n ";
		        $LIMIT  = "\n LIMIT 1";
				$db->setQuery($SELECT . $FROM  . $WHERE . $ORDER . $LIMIT);
		        if($db->loadObject($profile)) {
		        	return $profile;
		        }
				else {
					return FALSE;
				}
			}
			else {
				
				$SELECT = "SELECT *";
		        $FROM   = "\n FROM profile ";
				$WHERE  = "\n WHERE ID in (".implode(",", $KEY).")";
			    $WHERE .= "\n AND status>0";
		        $ORDER  = "\n ";
		        $LIMIT  = "\n ";
				$db->setQuery($SELECT . $FROM  . $WHERE . $ORDER . $LIMIT);
				return $db->loadObjectList();
			}
		}
		public function isFollow($followingID=0, $followerID=0)
		{
			global $model, $db;
			
			if($followingID==0)
			{
				return false;
			}
			if($followerID==0)
			{
				if($this->profileID==0)
				{
					return FALSE;
				}
				$followerID=$this->profileID;
			}
			$db->setQuery("SELECT ID FROM follow WHERE followerID=".$followerID." AND followingID=".$followingID." AND status>0");
         	$follow = null;
            if($db->loadObject($follow)){
            	return true;
          	} else {
            	return false;
            }
		}
        static public function isallowed( $profileID, $privacy ){
            global $model, $db, $dbez;
            /*$a = array(
                    0=>'Kimse',
                    1=>'Beni Takip Edenler',
                    2=>'Takip Ettiklerim',
                    5=>'Herkes'
                    );*/
            //echo "<h4>$profileID - $privacy</h4>";
            
            if($profileID == $model->profileID) return 1;
            
            switch ($privacy) {
                case 0: //kimse
                    return $profileID==$model->profileID?1:0;
                break;
                case 1: //onu takip edenler
                    $db->setQuery("SELECT ID FROM follow WHERE followerID=".$db->quote($model->profileID)." AND followingID=".$db->quote($profileID)." AND followerstatus>0 AND followingstatus>0 AND status>0");
                    $follow = null;
                    if($db->loadObject($follow)){
                        return 1;
                    } else {
                        return 0;
                    }
                break;
                case 2: //onun takip ettikleri
                    $db->setQuery("SELECT * FROM follow WHERE followerID=".$db->quote($profileID)." AND followingID=".$db->quote($model->profileID)." AND followerstatus>0 AND followingstatus>0 AND status>0");
                    $follow = null;
                    if($db->loadObject($follow)){
                        return 1;
                    } else {
                        return 0;
                    }
                break;
                case 5: //herkes
                    return 1;
                break;
                default: return 0;
            }
        }
        
        static public function getinfobyrow($row){
            global $model, $db, $l;
            
            if($row->followingstatus<=0){
                $followingstatus = '<span class="follow" rel="'.$row->ID.'">Takip Et</span>';
            } else {
                $followingstatus = '<span class="unfollow" rel="'.$row->ID.'">Takip Etme</span> ';
            }
            
            $html = '
            
                    <div class="head" class="profileinfo">
                        <span class="username">'.$row->name.'</span>
                        <span class="statistic">
                            <span>'.$row->di_count.' Di</span>
                            <span>'.$row->dilike1_count.' Takdir</span>
                            <span>'.$row->dilike2_count.' Red</span>
                        </span>
                        <span class="showprofile" rel="'.$row->ID.'">Profili</span>
                        <a href="/profile/'.$row->ID.'/">Profile Bak</a> '.$followingstatus.'
                    </div>
                    ';
            
            
            
            $response['ID'] = $row->ID;
            $response['result'] = 'success';
            $response['html'] = $html;
            
            return $response;
            
        }
        
        static public function istwinfollowers($id1, $id2){
            global $model, $db;
            
            if($id1>$id2) {
                $sw = $id1;
                $id1 = $id2;
                $id2 = $sw;
            }
            
            
            
            $db->setQuery("SELECT COUNT(*) FROM follow WHERE ((followerID=$id1 AND followingID=$id2) OR (followerID=$id2 AND followingID=$id1)) AND status>0");
            $count = $db->loadResult();
            if($count>=2) return true;
            else return false;
        }
        
        static public function getfollowercount($ID){
            global $model, $db;
            $db->setQuery("SELECT COUNT(*) FROM follow WHERE followingID=".$db->quote($ID)." AND status>0");
            return intval($db->loadResult());
        }
        /*
         * Kullanıcının Sitede Geçirdiği Süre
         * $ID profile Id si
         * timeType zaman dilimi 
         * W=Hafta, M=AY, Y=Yıl, D=Gün
         */
        static public function getTimeInSite($ID,$timeType="W")
        {
        	global $db;
        	$sonuc=0;
			$db->setQuery("SELECT registertime from user WHERE ID='".$ID."'");
        	$kayitTime=$db->loadResult();
			if($kayitTime!=null)
			{
				$fark=time()-strtotime($kayitTime);
	        	switch($timeType)
	        	{
	        		case "W":$sonuc=intval($fark/60/60/24/7);break;
	        		case "M":$sonuc=intval($fark/60/60/24/30);break;
	        		case "Y":$sonuc=intval($fark/60/60/24/365);break;
	        		case "D":$sonuc=intval($fark/60/60/24);break;
	        	}
	        	return  $sonuc;
			}
			else {
				return 0;
			}
        }
		public function set_follow($followingID=0, $followerID=0)
		{
			global $model, $db;
			
			if($followingID==0)
			{
				return false;
			}
			if($followerID==0)
			{
				if($this->profileID==0)
				{
					return FALSE;
				}
				$followerID=$this->profileID;
			}
			try {
				$db->setQuery("SELECT p.*, u.email AS email FROM profile AS p, user AS u WHERE p.ID=".$db->quote($followingID)." AND u.ID=p.ID");
			    $profile = null;
		        if(!$db->loadObject($profile)) throw new Exception('Profil Bulunamadı');
		        
				$db->setQuery("SELECT f.* FROM follow AS f WHERE f.followingID=".$db->quote($followingID)." AND f.followerID=".$db->quote($followerID));
		        $follow = null;
		        if(!$db->loadObject($follow)){
		            //first contact start
		            $follow = new stdClass;
		            $follow->followerID = $followerID;
		            $follow->followerstatus = 1;
		            
		            $follow->followingID = $followingID;
		            $follow->followingstatus = 1;
		            $follow->datetime = date('Y-m-d H:i:s');
		            $follow->status = 1;
		            
		            if($db->insertObject('follow', $follow)){
		                $follow->ID = $db->insertid();
		                $response['status'] = 'success'; 
		            } else {
		                $response['status'] = 'error';
		            }
		            //first contact end
		        } else {
		            //daha önce kontakt var, duruma 
		           	$follow->followingID = $followingID; 
		           	$follow->followerID = $followerID;
		            if($follow->status == 1)
					{
						//Takip ettiği kişiyi engellemişse ekleyemesin
						$follow->followerstatus = 0;
						$follow->followingstatus = 0;
						$follow->status = 0;
					}
					else {
						$follow->followerstatus = 1;
						$follow->followingstatus = 1;
						$follow->status = 1;
					}
		            $follow->datetime = date('Y-m-d H:i:s');
		            if($db->updateObject('follow', $follow, 'ID')){
		                $response['status'] = 'success';
		            } else {
		                $response['status'] = 'error';
		            }                    
		        }
	        }
			catch (exception $e){
				$response['status'] = 'error';
	        	$response['message'] = $e->getMessage();
			}
			
			if($response["status"]=="success")
			{
				$c_induction = new induction;
				if($follow->status==1)
				{
					$c_induction->set_profile_intduction("follow",  $profile, $follow->ID);
				}
				else
				{
					$c_induction->set_profile_intduction("unfollow",  $profile, $follow->ID);
				}
			}
			return $response;
		}

		public static function get_name($userID=null)
		{
			global $db,$model;

			if($userID==null)
				$userID=$model->profileID; 
			
			$db->setQuery("SELECT name FROM profile WHERE ID='".$userID."'");
			return $db->loadResult();
		}
		public static function get_profilePopover($userID=null)
		{
			global $model, $dbez;
			if($userID==null)
				$userID=$this->profileID;
			
			$profile=$dbez->get_row("SELECT * FROM profile WHERE ID='".$userID."'");
			$html='<table>
				<tr>
					<td>
					<div class="usrlist-pic" style="float:left;">
						<a href="'.$model->getProfileImage($profile->image, 300,400, 'scale').'" class="fnc">
							<img src="'.$model->getProfileImage($profile->image, 67,67, 'cutout').'">
						</a>
					</div>
					</td>
					<td valign="top">
					<div class="usrlist-info" style="float:left;">
						<table style="width: 100%" class="table-striped">
							<tr>
								<th><span>'.$profile->name.'</span></th>
							</tr>
							<tr>
								<td colspan=""><p>'.$profile->motto.'</p></td>
							</tr>
						</table>
					</div>
					</td>
				</tr>
			   </table>
			<div style="clear:both;"></div>';
			return $html;
		}
       /**
       * PARAM @profil profil nesnesi
       * bir kişinin profille  ilişkisini getiririr
       */
        public static function get_hastagInterest($profile=-1)
        {
                global $db;
                if(!is_object($profile))
                {
                        $profile=$this->profile;
                }
                $db->setQuery("SELECT ht.name, ht.permalink, ht.ID FROM profile ht, follow f WHERE ht.ID=f.followingID and f.followerID='".$profile->ID."' and f.status=1 and ht.status=1 and ht.type='hashTag'  ORDER BY ID desc LIMIT 10 ");
                
                return $db->loadObjectList();
       	}
       	public static function change_perma2ID($permalink)
       	{
       		global $model, $db;
       		$db->setQuery("SELECT ID FROM profile WHERE permalink='".$permalink."'");
			return $db->loadResult();
       	}
		public static function change_ID2perma($ID)
       	{
       		global $model, $db;
       		$db->setQuery("SELECT permalink FROM profile WHERE ID='".$ID."'");
			return  $db->loadResult();
       	}
		/**
		 * @PARAM $limit = Takip edilen kişi sayısı
		 * @PARAM $start = kaçıncı takip edilenden itibaren gelecek
		 * @PARAM $profile = kimin takip ettikleri gelecek profile objesi default değer class set edilirken girilen
		 * 
		 * @return $rows = Takipçilerin olduğu objeleri array dizi halinde döner
		 */
		public function get_following($limit=20,$start=0,$profile=-1, $keyword="")
		{
			global $model, $db;
        	if(!is_object($profile))
            {
            	$profile=$this->profile;
            }
			
			$SELECT = "SELECT DISTINCT f.followerID, p.*";
			$FROM   = "\n FROM follow AS f";
			$JOIN   = "\n JOIN profile AS p ON p.ID=f.followingID";
			$WHERE  = "\n WHERE f.followerID=".$db->quote($profile->ID);
			$WHERE .= "\n AND f.followerstatus>0";
			$WHERE .= "\n AND p.status>0";
			if($keyword!="")
			{
				$WHERE .= "\n AND (p.name like '".$keyword."%' or p.permalink like '".$keyword."%') ";
			}
			$ORDER  = "\n ORDER BY p.ID DESC";
			$LIMIT  = "\n LIMIT $start, $limit";
			
			$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
			$rows = $db->loadObjectList();
			return $rows;
		}
		/**
		 * @PARAM $limit = Takipçi sayısı
		 * @PARAM $start = kaçıncı takipçiden itibaren gelecek
		 * @PARAM $profile = kimin takipçileri gelecek profile objesi default değer class set edilirken girilen
		 * 
		 * @return $rows = Takipçilerin olduğu objeleri array dizi halinde döner
		 */
		public function get_follower($limit=20,$start=0,$profile=-1, $keyword="")
		{
			global $db;
        	if(!is_object($profile))
            {
            	$profile=$this->profile;
            }
			$SELECT = "SELECT DISTINCT f.followerID, p.*";
			$FROM   = "\n FROM follow AS f";
			$JOIN   = "\n JOIN profile AS p ON p.ID=f.followerID";
			$WHERE  = "\n WHERE f.followingID=".$db->quote($profile->ID);
			$WHERE .= "\n AND f.followingstatus>0";
			$WHERE .= "\n AND p.status>0";
			if($keyword!="")
			{
				$WHERE .= "\n AND (p.name like '".$keyword."%' or p.permalink like '".$keyword."%') ";
			}
			$ORDER  = "\n ORDER BY p.ID DESC";
			$LIMIT  = "\n LIMIT $start, $limit";
			
			$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
			$rows = $db->loadObjectList();
			return $rows;
		}
		public function get_porfileMiniHtml($rows)
		{
			global $model;
			$html_content="";
			if(count($rows)){ 
				foreach($rows as $row){
			    	if($model->profileID>0){
			        	if($row->followingstatus>0){
			            	$follow = 'hide';
			                $unfollow = '';
			         	} else {
			            	$follow = '';
			                $unfollow = 'hide';
			        	}
			            if($row->ID==$model->profileID){
			            	$follow_button = '<button class="btn btn-vekil">SENSİN</button>';
			         	} else {
			            	$follow_button = '<button id="follow'.$row->ID.'"  class="btn btn-vekil follow '.$follow.'" rel="'.$row->ID.'">Takip Et</button>
						    				  <button id="unfollow'.$row->ID.'"  class="btn btn-takipetme unfollow '.$unfollow.'" rel="'.$row->ID.'">Takip Etme</button>
						                      ';
			       		}
			     	} else {
			        	$follow_button = '';
			     	}
					$html_content .=
					'<p></p>
			        	<div class="roundedcontent" id="profile'.$row->ID.'">
							<div class="usrlist-pic">
								<a href="/profile/'.$row->ID.'" >
									<img src="'.$model->getProfileImage($row->image, 67, 67, 'cutout').'"  />
								</a>
							</div>
							<div class="usrlist-info">
								<table class="table-striped" style="width: 100%;">
									<tr>
										<th><span><a href="/profile/'.$row->ID.'" >'.$row->name." ".$row->surname.'</a></span></th>
										<th><a href="#">'.$row->di_count.' Ses</a></th>
										<th><a href="#">'.$row->dilike1_count.' Takdir</a></th>
										<th><a href="#">'.$row->dilike2_count.' Saygı</a></th>
									</tr>
									<tr>
										<td colspan="5"><p>'.$row->motto.'<br />'.$row->hometown.'</p></td>
									</tr>
								</table>
							</div>
							<div class="usrlist-set">
								<ul>
									<li>'.$follow_button.'</li>
								</ul>
							</div>
						</div>';
	             	
	             	
			    }//foreach sonu	     
			} // if count sonu
			return $html_content;
		}
		public function get_deputyList()
		{
			global $model, $db;
			$SELECT = "SELECT * ";
			$FROM   = "\n FROM profile ";
			$WHERE = "\n WHERE status>0";
			$WHERE .= "\n and deputy>0";
			
			$db->setQuery($SELECT . $FROM . $WHERE );
			$rows = $db->loadObjectList();
			$returnObj=array();
			foreach($rows as $r)
			{
				$ro=new stdClass;
				$ro->ID		= $r->ID;
				$ro->name	= $r->name;
				$ro->perma	= $r->permalink;
				$ro->image	= $model->getProfileImage($r->image, 90,90, 'cutout');
				$ro->motto	= $r->motto;
				$ro->isfollow	= $this->isFollow($r->ID);
				$ro->count_like	= $r->count_like; 
				$ro->count_dislike	= $r->count_dislike; 
				$ro->count_voice	= $r->count_voice; 
				$ro->count_follower	= $r->count_follower; 
				$ro->count_following	= $r->count_following; 
				$returnObj[]=$ro;
			}
			return $returnObj;
		}
		public function get_myDeputy()
		{
			global $model, $db;
			
			$SELECT = "SELECT DISTINCT md.*,pr.ID profileID, pr.image, pr.name,  pr.motto, pr.permalink permalink";
			$FROM 	= "\n FROM mydeputy AS md";
			$JOIN 	= "\n LEFT JOIN profile AS pr ON pr.ID = md.deputyID";
			$WHERE 	= "\n WHERE md.profileID = " . $db->quote ( intval ( $model->profileID ) );
			$WHERE 	.= "\n AND md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
			$WHERE 	.= "\n AND md.status>0";
			$ORDER 	= "\n ORDER BY md.datetime DESC";
			$LIMIT 	= "\n LIMIT 10" ;
	
			$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT );
			$rows = $db->loadObjectList ();
			return $rows;
		}
		public function get_myDeputyReturnObj($deputyies)
		{
			global $model;
			$returnObj=array();

			foreach($deputyies as $d)
			{
				 $ro	= new stdClass;
				 $ro->ID		= $d->ID;
				 $ro->deputyID	= $d->profileID;
				 $ro->dPerma	= $d->permalink;
				 $ro->dImage	= $model->getProfileImage($d->image, 45,45, 'cutout');
				 $ro->dName		= $d->name;
				 $ro->dMotto	= $d->motto;
				 $returnObj[]	= $ro;	
			}
			if(count($returnObj)<10)
			{
				
				$don = (10-count($returnObj));
				for($i=1; $i <= $don; $i++)
				{
					$ro	= new stdClass;
					$ro->ID			= 0;
					$returnObj[]	= $ro;	
				}
			}
			return $returnObj;
		}
		public function get_myDuputy_count()
		{
			global $model, $db;
			
			$SELECT = "SELECT count(ID) ";
			$FROM 	= "\n FROM mydeputy";
			$WHERE 	= "\n WHERE profileID = " . $db->quote ( intval ( $model->profileID ) );
			$WHERE 	.= "\n AND datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
			$WHERE 	.= "\n AND status>0";

			$db->setQuery ( $SELECT . $FROM  . $WHERE  );
			$rows = $db->loadResult ();

			return $rows;
		}
		function get_profileMultiReturtnObj($profiles)
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
		function get_profileImage($profileID=null, $iW=48, $iH=48){
			global $model, $db;
			if($profileID==null || $profileID==0)
				$userID=$this->profileID;
			$db->setQuery("SELECT image FROM profile WHERE ID='".$profileID."'");
			$image = $db->loadResult();
			return $model->getProfileImage($image, $iW,$iW, 'cutout');
		}
		function get_faceID($profile=-1)
		{
			global $model, $db;
			
			if(!is_object($profile))
            {
            	$profile=$this->profile;
            }
            
			$SELECT = "SELECT fbID ";
			$FROM 	= "\n FROM profile";
			$WHERE 	= "\n WHERE ID = " . $db->quote ( intval ( $profile->ID ) );
			$WHERE 	.= "\n AND status>0";

			$db->setQuery ( $SELECT . $FROM  . $WHERE  );
			$return = $db->loadResult ();
			return $return;
			//return $rows;
		}
		public function update_profile($uProfile)
		{
			global $model, $db;
			//$update->ID kontrol edilsin eğer yoksa  init ile  gelen profil set edilsin ($this->ID)
            return $db->updateObject('profile', $uProfile, 'ID', 0);
		}
        public function get_FollowingHashtags($profile = -1, $limit = 4){
            global $model, $db;
        
            if(!is_object($profile)){
                    $profile=$model->profile;
            }
            //print_r($profile);
            $query = "SELECT ht.* ". 
                      "FROM profile ht, follow f ".
                      "WHERE ht.ID=f.followingID ".
                          "AND f.followerID='".$profile->ID."' ".
                          "AND f.status=1 ". 
                          "AND ht.status=1 ".
                          "AND ht.type='hashTag' ".
                      "ORDER BY ID desc ". 
                      "LIMIT $limit ";
            $db->setQuery($query);
            return $rows = $db->loadObjectList();                    
        }
		public function get_imageGalery($profile=-1)
		{
			global $model,$db;
			if(!is_object($profile))
            {
            	$profile=$this->profile;
            }
			
			$response=false;
			$SELECT	=  "SELECT * ";
			$FROM	=  "FROM tagimage ";
			$WHERE	=  "WHERE tagID=".$db->quote($profile->ID)." and status='1' ";
			$ORDER	=  "ORDER BY ID DESC ";
			$LIMIT	=  "LIMIT 6";
			$db->setQuery($SELECT.$FROM.$WHERE.$ORDER.$LIMIT);
			$images = $db->loadObjectList();
			$i_list= array();
			if(count($images)>0)
			{
				//var_dump($images);
				$response["status"]="success";
				//$model->getProfileImage($i->image, 600,400, 'scale')
				foreach ($images as $i) {
					$i_list["small"] = $model->getProfileImage($i->image, 85,50, 'cutout');
					$i_list["big"] = $model->getProfileImage($i->image, 600,400, 'cutout');
					$response["images"][]=$i_list;
				}
				return $response;
			}
			else
			{
				$response["status"]="error";
				return $response;
			}
	
		
		}
		public function get_userSearch($keyword, $limit=20, $start=0)
		{
			global $model, $db;
			$SELECT = "SELECT * ";
        	$FROM   = "\n FROM profile";
			$WHERE = "\n WHERE status>0";
			if($start>0){
        		$WHERE .= "\n AND ID<" . $db->quote($start);
        	}  
			$WHERE .= "\n  AND (name LIKE '%". $db->escape( $keyword )."%' OR permalink  LIKE '%". $db->escape( $keyword )."%')";
   		
        	$ORDER  = "\n ORDER BY ID DESC";
        	$LIMIT  = "\n LIMIT $limit";
        	//echo $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT;
 			//die;
        	$db->setQuery($SELECT . $FROM .  $WHERE . $ORDER . $LIMIT);
		
			$rows = $db->loadObjectList();
			return $rows;
		}
		public function get_who2follow()
		{
			global $model, $db;
			
			$SELECT = "SELECT followerID, count( followerID ) AS derece";
			$FROM	= "\n FROM follow";
			$WHERE	= "\n WHERE followingID IN (SELECT followingID FROM follow WHERE followerID = ".$db->quote($this->profileID)." AND status=1) ";
			$WHERE	.= "\n AND followerID NOT IN (SELECT followingID FROM follow WHERE followerID = ".$db->quote($this->profileID)." AND status=1) ";
			//$WHERE .= "\n AND not exist (SELECT followingID FROM follow WHERE followerID = ".$db->quote($this->profileID).") AND followerID = ".$db->quote($this->profileID).")";
			$WHERE .= "\n AND status>0";
			$WHERE .= "\n AND followerID != ".$db->quote($model->profileID);
			$GROUP 	= "\n GROUP BY followerID";
			$ORDER	= "\n ORDER BY derece desc"; // randomize yapılmalı
			$LIMIT	= "\n limit 30";
			
			
			$db->setQuery($SELECT . $FROM .  $WHERE . $GROUP. $ORDER . $LIMIT);
		
			$rows = $db->loadObjectList();
	
			$secilenler = array ();
			$don = 4;
			if(count($rows)<4)
				$don = count($rows);
		
			if($don<1)
			{
				return false;
			}
			for($i=0; $i<$don; $i++)
			{
				$secim = array_rand($rows);
				$secilenler[]=$rows[$secim]->followerID;
				unset($rows[$secim]);
			}
			return $secilenler;
		}
                
        public static function change_password($inputArray){
            global $model,$db;
            $returnArray = array();
            $returnArray['status'] = 'success';
            extract($inputArray);
            
            try{
                $QUERY = "SELECT count(ID) FROM user WHERE ID =".$db->quote($model->profileID)."AND pass='".md5(KEY . trim( $password ))."'";
                $db->setQuery($QUERY);
                
                if($db->loadResult()<1){
                    $returnArray['element']='password';
                    throw new Exception('Şifre yanlış. ');
                }
                if(strlen(trim($password_new))<6){
                    $returnArray['element']='password_new';
                    throw new Exception('Şifre kısa. ');
                }
                if($password_new != $password_new2){
                    $returnArray['element']='password_new2';
                    throw new Exception('Şifreler uyuşmuyor');
                }

                
                $QUERY = "UPDATE user SET pass='".md5(KEY . trim( $password_new ))."' WHERE ID =".$db->quote($model->profileID)." AND pass='".md5(KEY . trim( $password ))."'";
                $db->setQuery($QUERY);
                if(!$db->query()){
                    throw new Exception('Bir sorun oluştu');
                }
            }catch(Exception $e){
                $returnArray['status'] = 'error';
                $returnArray['message'] = $e->getMessage();
            }
            
            return $returnArray;
        }
		function check_userMin()
		{
			global $model, $db;
			
			if(!$this->valid_perma($model->profile->permalink) && $model->profile->type=="profile")
			{
				$eL[] = "permalink";
			}
			if(!filter_var($model->user->email, FILTER_VALIDATE_EMAIL))
			{
				$eL[] = "email";	
			}
			if(isset($eL))
			{
				$r["success"] = false;
				$r["errors"] = $eL;
			}
			else
			{
				$r["success"] = true;
			}
			return $r;
		}
		function valid_perma($perma)
		{
			if(preg_match("/^[a-zA-Z0-9\-\_\.]{5,15}$/", $perma)){
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}

		function normalize_permalink($permalink=null, $element="profile")
		{
			global $model, $db;
			if($permalink==null)
			{
				return false;
			}
			$query = "SELECT count(permalink) FROM $element WHERE permalink= '$permalink'";
			$db->setQuery($query);
			$sonuc = $db->loadResult();
			if($sonuc>0)
			{
				$permalink = $permalink."_".rand(2,99);
				return $this->normalize_permalink($permalink, $element);
			}
			else 
			{
				return $permalink;

			}
		}
    }
?>
