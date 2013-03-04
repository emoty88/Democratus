<?php
    class message_plugin extends control{
    	public $msg;
		public function main(){
			model::checkLogin(1);
			global $model, $db, $l;
			
			$model->template="ala";
			$model->view="message";
			$model->title = 'Democratus';
			
			$this->msg = new messageClass;

			$model->addHeaderElement();
			
			$model->addScript("paths=".json_encode($model->paths));
			$model->addScript("plugin='message'");
			
			if($model->paths[1] == "dialog") {return $this->page_dialog();}
			//$model->addScript('');
			?>
			<section class="satir" id="mesajlar">
				<ul class="mesajlar_listesi" id="dialog_list_ul">
					
				</ul>
			</section>
			<?
		}

		public function page_dialog()
		{
			global $model;
			$model->addScript("plugin='message-dialog'");
			$pPerma=$model->paths[2];
			$model->addScript("var friendPerma='".$pPerma."';");
			$pID = profile::change_perma2ID($pPerma);
			$model->addScript("var fID=".$pID);
			$mesajlar=$this->msg->getDialog($model->profileID,$pID,null,20);
			$model->addScript(TEMPLATEURL."ala/js/jquery.nicescroll.min.js", "jquery.nicescroll.min.js", 1);
            
			//$model->addScript(TEMPLATEURL."ala/js/iscroll.js", "iscroll.js", 1);
			//$model->addStyle(TEMPLATEURL."ala/css/antiscroll.css", "antiscroll.css", 1);


			?>
	
                        
			<section class="satir padding20lr antiscroll-inner box-inner" id="onceki_mesajlar" style="max-height:  500px;">
                            	
			</section>
				
			
			<div class="satir">
				<div class="karakter_sayaci_tutucu" id="yeni_yazi_yaz">
					<textarea rows="2" placeholder="Mesaj yaz..." class="karakteri_sayilacak_alan" name="yeni_yazi" id="new_message"></textarea>
					<div class="kalan_karakter_mesaji"><span class="karakter_sayaci">200</span> karakter</div>
					
					<div class="kontroller">
						
						<button class="btn btn-danger" id="message_send">Gönder</button>
						
						
					</div>
					<div class="clearfix"></div>
				</div>
				<input type="hidden" value="0" name="initem" id="initem">
			    <input type="hidden" value="0" name="initem-name" id="initem-name">
			</div>
			<?
			//$profileClass = new profile;
			//$profile[$mesajlar[0]['toID']] = $profileClass->get_porfileObject($mesajlar[0]['toID']);
			//$profile[$mesajlar[0]['fromID']] = $profileClass->get_porfileObject($mesajlar[0]['fromID']);
		} 
    	public function main_old()
		{
			global $model;
			$this->msg=new messageClass;
			if($model->paths[1] == 'ajax') return $this->ajax();
			if($model->paths[1] == "dialog") return $this->page_dialog();
		}
		public function ajax()
		{
			global $model;
			$model->mode = 0;
          	$method = (string) 'ajax_' . $model->paths[2];
            if(method_exists($this, $method )){
            	$this->$method();
          	}
		}
		public function ajax_get_dialog()
		{
			
			global $model;
			$profileClass = new profile;
			
			$model->mode=0;
			$list = $this->msg->getDialogList($model->profileID);
			
			echo '<div style="max-height:450px; overflow:auto; margin:-14px;">
							<div class="roundedcontent lastactionscontent" style="width:">
								<ul class="">';
			foreach($list as $l){
				if($model->profileID == $l['toID']){
					$id = $l['fromID'];
				}else{
					$id = $l['toID'];
				}
				
				$profile = $profileClass->get_porfileObject($id);
				
				
				
				echo '<a href="/message/dialog/'.$id.'"> <li style="height:70px;">';?>
					<div style=" margin-bottom: 10px">
						<div style="float: left; width: 50px;height: 50px ; margin: 3px; margin-left: 5px;">
							<img src="<?php echo $model->getProfileImage($profile->image, 50,50, $action = 'cutout') ?>" />
						</div>
						<div style="float: left; width: 350px; margin-left: 16px">
							<div style="font-weight: bold; ">
								<?php echo $profile->name ?>
								<span style="float: right; font-weight: normal;"><?php echo $this->trTime($l['insertTime']); ?></span>
							</div>
							<div style="color:black">
								<?php if($model->profileID == $l['fromID'])  echo '>'; ?>
								<?php echo $l['message']; ?>
							</div>
							<div style="clear: both"></div>
						</div>
						
					</div>
				<?php echo '</li></a>';
			}
			echo '			</ul>
						</div>
					</div>';
		}

		public function ajax_send()	{
			global $model;
			$message=strip_tags( html_entity_decode( htmlspecialchars_decode(filter_input(INPUT_POST, 'msg', FILTER_SANITIZE_STRING), ENT_QUOTES ), ENT_QUOTES, 'utf-8' ) );
			$to = intval(filter_input(INPUT_POST, 'to'));
			//echo $message."\n";
			//echo $to;
			$profileClass = new profile;
			$test = $profileClass->get_porfileObject($to);
			if(!isset($test->ID)){
				echo '{"result": "error"}';
				return;
			}
			
			if(empty($message)){
				echo '{"result": "empty"}';
				return;
			}
			if($this->msg->insertMessage($model->profileID, $to, $message))
				echo '{"result": "success"}';
		}
		
		
		public function ajax_delete(){
			echo filter_input(INPUT_POST, 'id');
		}
		public function page_dialog_old()
		{
			global $model;
			$model->initTemplate('beta', 'default');
			$model->addScript(TEMPLATEURL."beta/docs/assets/js/jquery.js","jquery.js",1);
            $model->addScript($model->pluginurl . 'message.js', 'message.js', 1);  
			$model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
			$pID=$model->paths[2];
			$mesajlar=$this->msg->getDialog($model->profileID,$pID,null,20);
			$profileClass = new profile;
			$profile[$mesajlar[0]['toID']] = $profileClass->get_porfileObject($mesajlar[0]['toID']);
			$profile[$mesajlar[0]['fromID']] = $profileClass->get_porfileObject($mesajlar[0]['fromID']);
			?>
			
			<div class="popover-title">
				<h4>Mesajlar - <?php echo $profile[$pID]->name; ?> 
				<button onclick="deleteMessage(<?php echo  $pID ; ?>,0)" style="margin-left: 10px" class="button">Hepsini Sil</button>
				<button onclick="deleteMessage(<?php echo  $pID ; ?>,1)" style="" class="button">Sil</button>
				<h4>
			</div>
			
			<div class="scroll" style="margin-top: 0px; margin-left: 5px; max-height: 450px; overflow-y: auto; overflow-x:visible; background-color: #fbfbfb; padding: 8px;" >
			<?php
			
			
			
			if(!isset($profile[$mesajlar[0]['toID']]->ID) or !isset($profile[$mesajlar[0]['fromID']]->ID)){
				echo "</div>";
				return;
			}
			if(sizeof($mesajlar)<1){
				echo '<span style="margin:0 auto;">Hiç mesaj yok</span>';
			}
			foreach($mesajlar as $m){
				if($model->profileID==$m['fromID']){
					echo '<div style="max-height:450px; max-width:425px; margin-right:20px; float:right">';
					echo '<div class="roundedcontent lastactionscontent" style="min-width:200px ;">'.
									'<div style="float:right; width:50px">
										<img src="'.$model->getProfileImage($profile[$m['fromID']]->image, 50,50, $action = 'cutout').'" />
										
									</div>'.
									'<span class="x " rel="43082" data-toggle="" style="float:left; display:none;"></span><div style="float:right; max-width:300px; margin-right:10px;">'.
										'<a href="/profile/'.$m['fromID'].'">
											<span style="font-weight: bold; float:right; ">'.$profile[$m['fromID']]->name.'</span>
										</a> 
										<div  style="clear:both"></div>
										'.
										'<ul style="float:right;  word-wrap: break-word; white-space: pre-wrap; text-align:right ">'.
												$m["message"].
										'</ul>
										
									</div>
									
								
							  </div>
						</div>';
				}else{
					echo '<div style="max-height:450px; max-width:425px; float:left">';
				
						echo '<div class="roundedcontent lastactionscontent" style="min-width:200px ;">'.
									'<div style="float:left;width:50px; margin-right:5px;">'.
										'<img src="'.$model->getProfileImage($profile[$m['fromID']]->image, 50,50, $action = 'cutout').'" />'.
									'</div>
									
									'.
									'<div>'.
										'<span class="x" rel="43082" data-toggle="" style="float:right; display:none"></span><a href="/profile/'.$m['fromID'].'">
											<span style="font-weight: bold; ">'.$profile[$m['fromID']]->name."</span>
										</a>".
										'<ul style="word-wrap:break-word;">'.
												$m["message"].
										'</ul>
									</div>
							  </div>
						</div>';
				}
				echo '<div  style="height:15px; clear:both"></div>';
				
			}
				echo '<span id="focus"></span>';
			echo "</div>";
			
			?>
			<div style="padding-left: 5px" class="repled-box" > <p></p>
				<form id="messageForm" onsubmit="return false;" method="post">
					<textarea id="msg"  style="width: 400px; height: 32px; resize:none; overflow: auto;" class="input-xlarge numberSay tooltip-top" placeholder="Mesaj" name="msg" rows="3" data-original-title=""></textarea>
					<div style="display: none;">
								<input type="hidden" name="to" value="<?php echo $pID; ?>"/>								
					</div>
					<ul style="float: right; list-style: none; margin: 0;">
								<li style="float: left;">
				            		<button onclick="message_submit(43081);" id="msgButton" data-original-title="" class="btn btn-gonder tooltip-top">Cevapla</button>
				            	</li>
				            	<li class="hideArea-shareditext-43081" style="display:">
				            		<span style="color: #9B9B9B; font-size:10pt; margin-right: 10px; margin-top: 10px;">
				            			<span style="float: none;font-size:10pt;" id="msgNumber">200</span> Karakter
				            		</span>		
								
				            	</li>
					</ul>
				</form>
			</div>
			<?php
		}
		private function trTime($timeSt) {
       		$tr_gun=array('','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar');
       		$tr_ay=array('','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');
       		
       		
       		return date("d",$timeSt)." ".$tr_ay[date("n",$timeSt)]." ".$tr_gun[date("N",$timeSt)];
       }
		
		public function perma(){
    
        global $model, $db;
        $QUERY = "SELECT permalink, name,ID FROM profile WHERE permalink IS NULL
        ";
       
        echo $QUERY;
        echo '<pre>';
        $db->setQuery($QUERY);
        
        $result = $db->loadObjectList();
        //print_r($result);
       // die;
        $perma = new stdClass();
        $buffer = array();
        $i = 0;
        foreach ($result as $r){
            $i++;
            $new = $this->new_perma($r->name);

            if(in_array($new, $buffer)){
                $new = $new.rand(100, 999);
            }

            $perma->permalink = (string) $new;
            $perma->ID = (int)$r->ID;

            $buffer[$r->ID] = $new;
            print_r($perma);
            
             $QUERY = "UPDATE profile SET permalink = '$perma->permalink' WHERE ID = $perma->ID";
             $db->setQuery($QUERY);
             echo $QUERY;
             $result = $db->query();
            
        }
        echo '<br>'.$i;
        
    }
    
function new_perma($r){
        $new = '';
        $r = mb_strtolower($r,'UTF-8');
        $search = array('ğ','ü','ş','ı','ö','ç',' ');
        $replace = array('g','u','s','i','o','c','_');
        $r= str_replace($search, $replace, $r);
        foreach (str_split($r) as $char){
            if(ctype_alnum($char) or $char == '_' or $char == '.'){
                $new .= $char;
                $new = (string)$new;
            }
            
            $new = urlencode($new);
            $new = addslashes($new);
        }
        return $new;
}
function Y(){
    global $model, $db;
    
    $QUERY = "
        SELECT ID FROM di WHERE status = 1 ORDER BY ID DESC
";
    
    $db->setQuery($QUERY);
    
    $result = $db->loadResultArray();
    
    foreach ($result as $r){
        
        $QUERY = "SELECT COUNT(*) FROM di WHERE di.rediID = $r AND status=1";
        
        $db->setQuery($QUERY);
        
        $dis = $db->loadResult();
        
        if($dis == 0)
            continue;
        $a = new stdClass();
        
        $a->ID = $r;
        $a->count_reShare = $dis;
        
        $db->updateObject('di',$a,'ID');
        var_dump($a);
    }
    //print_r($result);
}


function Z(){
    global $model, $db;
    
    $QUERY = "
        SELECT ID FROM di WHERE status = 1 ORDER BY ID DESC
";
    
    $db->setQuery($QUERY);
    
    $result = $db->loadResultArray();
    
    foreach ($result as $r){
        
        $QUERY = "SELECT COUNT(*) FROM di WHERE di.replyID = $r AND status=1";
        
        $db->setQuery($QUERY);
        
        $dis = $db->loadResult();
        
        if($dis == 0)
            continue;
        $a = new stdClass();
        
        $a->ID = $r;
        $a->count_reply = $dis;
        
        $db->updateObject('di',$a,'ID');
        var_dump($a);
    }
    //print_r($result);
}
public function perma_fix()
		{
			global $model, $db;
			
			
	        //$QUERY = "SELECT permalink, name,ID FROM profile WHERE permalink IS NULL";
	        
	        //$QUERY = "select ID, permalink,count(permalink) from profile group by permalink having count(permalink)>1";
	        
	        $QUERY = "select ID, permalink from profile 
	        	where ID NOT IN (5931,5757,5864,6383,6019,6677,5789,6197,5816,6202,5780,5628,6380,5822,5571,6755,5715,5708,5506,5544,5492,2807,5860,5793,1998,6098,5977,6674,6495,1571,6466,6647,6465,6463,3512,6026,5592,6644,5653,5679,5498,5549,6097,6092,5711,6116,1071,5813,5565,4696)
	        	AND permalink IN ('begum', 'behzat_h', 'berk', 'betul', 'busra', 'caglar', 'can', 'cenk', 'cigdem', 'deneme', 'deniz', 'dilan', 'dilek', 'elif', 'emin', 'emir', 'emre327', 'esra', 'faruk', 'fasttren', 'fatih174', 'fethi', 'furkan', 'gulcin', 'halil', 'hasan', 'ismail', 'kaan', 'kadir', 'kadriye', 'kubra', 'latife', 'mahmut', 'mehmet', 'muhammed', 'mustafa', 'nesrin', 'nur', 'osman', 'pinar', 'reyyan', 'serdar', 'seyma', 'tuba', 'tuna', 'yasin', 'yavuz', 'yildirim', 'yusuf', 'zehra', 'ZepAltinbas', 'zeynep')";
			
			$db->setQuery($QUERY);
        
       	 	$result = $db->loadObjectList();
			foreach($result as $r)
			{
				echo "'".$r->permalink."', ";
			}
			
		}
    }


?>
