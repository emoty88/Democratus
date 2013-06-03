<?php
    class task_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
            $model->mode=0;   
			
            if($model->paths[1]=="gundem56yn234rty")  
            {
            	$this->agenda();
            	die;
            }
			else if($model->paths[1]=="vekil56yn234rty")
			{
				$this->deputy();
            	die;
			}else if($model->paths[1]=="puanTest")
			{
				$this->deputy_point();
            	die;
			}
			else if($model->paths[1]=="exportCVS")
			{
				$this->ex_cvs();
				die;
			}
			else if($model->paths[1]=="canerMo")
			{
				$this->canerMo();
				die;
			}
			else if($model->paths[1]=="test")
			{
				var_dump($model->sendsystemmail("mstfhrgl@gmail.com", "test mail", "denem maisdal", "mandrill"));

			}
            if($model->paths[2]!='dyHp7ozsNNfxyZCA28cUMtZ8bAsBdD1t1y3LAp0XoylKpGpH') die;
            if($_SERVER['REMOTE_ADDR']!='178.63.46.159') die('pardon izin yok!');
            //if(1!=1) die('pardon izin yok!');
            
            //die('ok: '.$model->paths[1]);
            
            switch($model->paths[1]){
                case 'agenda': return $this->agenda(); break;
                case 'deputy': return $this->deputy(); break;
                case 'test': return $this->test(); break;
            }
        }
        
        private function test(){
            //mail('kyilmazihh@gmail.com', 'test sistemi çalışıyor', 'şu anda test sistemi çalıştı. saat: '.date('Y-m-d H:i:s'));
        	echo "test geldi";
        }
        
        
        private function agenda(){
            global $model, $db, $l;
   		
            ob_start();
			$proposalClass=new proposal;
            $SELECT = "SELECT pp.*, pr.name, pr.image, u.email, pr.emailperms";
            $SELECT.= "\n , sum(ppv.approve) AS approvecount";
            $SELECT.= "\n , sum(ppv.reject) AS rejectcount";
            //$SELECT.= "\n , ( sum(ppv.approve) - sum(ppv.reject)) * ( (sum(ppv.approve) + sum(ppv.reject)) / " . config::$deputylimit ." ) AS points" ;
            $SELECT.= "\n , (( sum(ppv.approve) - sum(ppv.reject)) *  sum(ppv.approve) ) AS points" ;
            //$SELECT.= "\n , (( sum(ppv.approve) - sum(ppv.reject)) *  sum(ppv.approve) ) / (sum(ppv.complaint)+1) AS points" ;
            $FROM   = "\n FROM proposal AS pp, proposalvote AS ppv, profile AS pr, user AS u";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE pp.datetime>=" . $db->quote( date('Y-m-d H:i:s', LASTDAY) );
            $WHERE .= "\n AND pp.status>0";
            $WHERE .= "\n AND pp.used=0"; //kullanılmamışsa
            $WHERE .= "\n AND ppv.proposalID = pp.ID";
            $WHERE .= "\n AND pr.ID = pp.deputyID";
            $WHERE .= "\n AND u.ID = pr.ID";
			$WHERE .= "\n AND pp.st=1";
            $GROUP  = "\n GROUP BY ppv.proposalID";
            $ORDER  = "\n ORDER BY points DESC, approvecount DESC, pp.ID ASC";
            $LIMIT  = "\n LIMIT 7";// . $proposalClass->get_agendaLimit();
            
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
           
            echo '<h3>Seçilen gündem sayısı: '.count($rows).'</h3>';
            //return;
            if(count($rows)){
                $i=0;
                
                $starttime  = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                $endtime    = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));

                foreach($rows as $row){
                    $i++;
                    
                    
                    //add di
                        
                    $di = new stdClass;

                    $di->di          =  $row->title;   
                    $di->datetime    = date('Y-m-d H:i:s');
                    $di->profileID   = intval( $row->deputyID );
                    $di->ip          = $_SERVER['REMOTE_ADDR'];
                    
                    if( $db->insertObject('di', $di) ){
                        echo '<h4>vekilin adına di eklendi</h4>';
                        $diID = intval( $db->insertid() );
                        
                    } else {
                        echo '<h4>di eklenemedi !</h4>';
                        $diID = 0;
                    }
                    
                    
                    
                    
                    $agenda = new stdClass;
                    $agenda->title      = $row->title;
                    $agenda->spot       = '';// $row->spot;
                    $agenda->proposalID = $row->ID;
                    $agenda->deputyID   = $row->deputyID;
					$agenda->mecliseAlan= $row->mecliseAlan;
                    $agenda->starttime  = date('Y-m-d H:i:s', $starttime);
                    $agenda->endtime    = date('Y-m-d H:i:s', $endtime );
                    $agenda->diID       = $diID; //**************/
                    $agenda->status     = 1;
                    
                    if($db->insertObject('agenda', $agenda)){
                        echo '<h3>Yeni gündem oluşturuldu</h3>';
                        $arow = new stdClass;
                        $arow->ID = $row->ID;
                        $arow->used = 1;
                        echo '<p>'.$row->title.'</p>';
                        echo '<p><i>'.$row->name.'</i></p>';
                        //$model->sendsystemmail($row->email, 'Gündem taslağınız onaylandı!', 'Merhabalar, <br /> Democratus millet vekillerinin oylamasına sunduğunuz gündem tasarınız onaylandı. Gündeminiz democratus ana sayfasından tüm üyelerin oylamasına sunuldu. Belki ilk oyu kendiniz vermek isterisiniz diye haber verelim istedik. <br /> <br /> Daha iyi bir dünya için katkılarınızdan dolayı teşekkür ederiz. <br /><br /> <a href="http://democratus.com/">democratus.com</a> ');
                        
                        
                        if($row->emailperms>0)
                            $model->sendsystemmail($row->email, 'Tasarınız meclis gündemine seçildi!', 'Tebrik ederiz, <br /> Mecliste gün boyu süren oylamada sizin tasarınız diğer vekillerin onayları ile ülke gündemine seçildi ve tüm democratus kullanıcılarının oylamasına sunuldu.  Başarılarınızın devamını dileriz. <br /><br /> Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                        
                        echo '<h4>+ mail gönderildi - '.$row->email.'</h4>';
                        
                        if( $db->updateObject('proposal', $arow, 'ID', 0) ) {
                            echo '<h4>+ propostal işaretlendi</h4>';
                        } else {
                            echo '<h4>propostal işaretlenemedidi</h4>';
                        }
                        
                        
                        
                        
                        
                        
                    } else {
                        echo '<h3>Yeni gündem oluşturulamıyor..</h3>';
                    }
                    echo '<hr />';
                }
            } else {
                echo '<h1>hiç oylanmış taslak bulunamadı !</h1>';
            }
            
            
            
            $buffer = ob_get_contents();
            ob_end_flush();
            
           
            
            $model->sendsystemmail('caner.turkmen@democratus.com', 'Gündem seçimi', $buffer);
            $model->sendsystemmail('director@democratus.com', 'Gündem seçimi', $buffer);
            //upload mailleri için otomatik folder create
            $uniqueP = date("y_m_d");
            $upDir="p_image/".$uniqueP;
            $olustur = mkdir(UPLOADPATH.$upDir, 0777);
            chmod(UPLOADPATH.$upDir, 0777);
        }
        
        private function deputy(){
            global $model, $db, $l;
            
            ob_start();
            //tüm vekilleri at
            //$db->setQuery("UPDATE profile SET deputy = 0");
            //$db->uquery();
            //$dbez->query("UPDATE profile SET deputy = 0");
            $db->setQuery("UPDATE profile SET deputy = 0");
            $db->uquery();
			
		/*
            //oyu en yüksek olan ilk 100 kişiyi bul
            $SELECT = "SELECT pr.*, count(md.ID) AS votecount, u.email";
            $FROM   = "\n FROM profile AS pr, mydeputy AS md, user AS u";
            $JOIN   = "\n ";
            //$WHERE  = "\n WHERE md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
            $WHERE  = "\n WHERE md.datetime >= DATE_ADD(NOW(), INTERVAL -30 DAY) ";
            $WHERE .= "\n AND md.status>0";
            $WHERE .= "\n AND pr.status>0";
			$WHERE .= "\n AND pr.notVekil=0";
            $WHERE .= "\n AND md.deputyID = pr.ID";
            $WHERE .= "\n AND u.ID = pr.ID";
            $GROUP  = "\n GROUP BY md.deputyID";            
            $ORDER  = "\n ORDER BY votecount DESC, md.ID ASC";
            $LIMIT  = "\n LIMIT " . config::$deputylimit;
		 */
			$SELECT = "SELECT pr.*,  u.email";
            $FROM   = "\n FROM profile AS pr, user AS u";
            $JOIN   = "\n ";
            //$WHERE  = "\n WHERE md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
            $WHERE  = "\n WHERE pr.status>0";
			$WHERE .= "\n AND pr.notVekil=0";
            $WHERE .= "\n AND u.ID = pr.ID";
            $WHERE .= "\n AND pr.type = 'person'";
            $GROUP  = "";            
            $ORDER  = "\n ORDER BY pr.puan DESC, pr.ID ASC";
            $LIMIT  = "\n LIMIT 50";		
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            $rows = $db->loadObjectList(); 
            
            //echo mail('kyilmazihh@gmail.com', 'deputy', $db->_sql);
            
            //die;
            
            /*
            echo '<h3>SQL sorgusu</h3>';
            echo '<pre>';
            echo $db->_sql;
            echo '</pre>';
            
            echo '<h3>SQL sorgu sonucu</h3>';
            echo '<pre>';
            print_r($rows);
            echo '</pre>';
            //return; 
            */
            echo '<h3>Seçilen vekil sayısı: '.count($rows).'</h3>';
            
            if(count($rows)){
                $i=0;
                
                $starttime  = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                $endtime    =  strtotime("Next Wednesday");

                foreach($rows as $row){
                    $i++;
                    
                    echo '<h2>' . $row->name . ' - Toplam  Puan:  ' .$row->puan. ' </h2>';
                    
                    $delegacy             = new stdClass;
                    $delegacy->profileID  = $row->ID;
                    $delegacy->vote       = $row->votecount;
                    $delegacy->starttime  = date('Y-m-d H:i:s', $starttime);
                    $delegacy->endtime    = date('Y-m-d H:i:s', $endtime );
                    $delegacy->datetime    = date('Y-m-d H:i:s');
                    $delegacy->ip          = ip2long($_SERVER['REMOTE_ADDR']);
                    $ppvote->status      = 1;
                    
                    if($db->insertObject('delegacy', $delegacy)){
                    	$ind = new induction;
						$ind->set_profile_intduction("deputy", $row);
                        echo '<h3>Yeni vekillik oluşturuldu</h3>';
                        $arow = new stdClass;
                        $arow->ID = $row->ID;
                        $arow->deputy = 1;
                        if( $db->updateObject('profile', $arow, 'ID', 0) ) {
                            echo '<h4>+ vekillik yetkisi atandı !</h4>';
                            //$model->sendsystemmail($row->email, 'Milletvekili seçildiniz!', 'Merhabalar, <br /> Democratus arkadaşlarınızın oyları ile milletvekili seçildiniz. Daha iyi bir dünya için değerli düşüncelerinizden oluşan gündem tekliflerinizi tüm Democratus ailesi olarak bekliyor olacağız. <br /><br /> <a href="http://democratus.com/">democratus.com</a> ');
                            
                            if($row->emailperms>0)
                            $model->sendsystemmail($row->email, 'Vekil seçildiniz! ', 'Tebrik ederiz, <br /> democratus’un bu haftaki meclis seçiminin galiplerinden birisi oldunuz ve vekil seçildiniz. <br /><br /> Bir hafta boyunca yapacağınız her paylaşımın tüm kullanıcılara ulaşacağını, Tasarı Odası’nda oluşturacağınız taslaklar ve diğer tasarılara vereceğiniz oylarla meclis gündemini belirleyebileceğinizi hatırlatırız.  <br /><br /> Çalışmalarınızda başarılar diliyoruz. <br /><br />Dünya’yı fikirlerinizle şekillendirmek için democratus!');
                            echo '<h4>+ mail gönderildi:'.$row->email.'</h4>';
                        } else {
                            echo '<h4>vekillik yetkisi atanamadı :(</h4>';
                        }
                    } else {
                        echo '<h3>Yeni vekillik oluşturulamıyor..</h3>';
                    }
                    echo '<hr />';
                }
            } else {
                echo '<h1>hiç vekil bulunamadı !</h1>';
            }
            
            
            
            $buffer = ob_get_contents();
            ob_end_flush();
            
            //mail('kadir@kadir.web.tr', 'deputy task run', $buffer);
            
            //$model->sendsystemmail('kadir@kadir.web.tr', 'Milletvekili seçimi', $buffer);
			//$db->query("update profile set puan='0' , temelPuanHesaplandi='0' ");
			$db->setQuery("update profile set puan='0' , temelPuanHesaplandi='0' ");
            $db->uquery();
            $model->sendsystemmail('caner.turkmen@democratus.com', 'Milletvekili seçimi', $buffer);
            $model->sendsystemmail('director@democratus.com', 'Milletvekili seçimi', $buffer);
            
        }
		private function deputy_point (){
			global $model, $dbez;
			$puan=new puan;
			$puan->puanSifirla();
			
			
			echo "<pre>"; 
			var_dump($puan->get_maxpuanProfile(50));
			echo "</pre>";
		}
        private function ex_cvs()
		{
			global $model, $db;
			if($model->paths[2]!="erw56dsmk")
				die("not safe");
			echo '"email";"language";"cityID";"countryID";"living";"hometown";"sex";"birth";"name"'."\n";
			$adresler=$dbez->get_results('SELECT u.email, p.language, p.cityID, p.countryID, p.living, p.hometown, p.sex, p.birth, p.name FROM profile p left join user u on u.ID=p.ID where u.email is not null and u.email != ""');
			foreach($adresler as $a)
			{
				echo '"'.$a->email.'";"'.$a->language.'";"'.$a->cityID.'";"'.$a->countryID.'";"'.$a->living.'";"'.$a->hometown.'";"'.$a->sex.'";"'.$a->birth.'";"'.$a->name.'"'."\n"; 
			}
		}
		private function canerMo()
		{
			global $model, $db;
			$c_facebook = new facebookClass;
			$c_profile = new profile;
			
			$query = "SELECT ID, name, fbID from profile WHERE permalink IS NULL AND fbID != '0' LIMIT 30";
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			
			foreach ($rows as $r)
			{
				echo"<pre>";
				$perma=$c_profile->normalize_permalink($r->name);
				
				echo $perma."<br>";
				$profile = new stdClass;
				$profile->ID = $r->ID;
				$profile->permalink = $perma;

				var_dump($c_profile->update_profile($profile));
				echo "</pre>";
			}
			die;
			
			//$c_profile->
		}
    }
?>
