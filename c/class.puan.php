<?php 
	class puan {
		 public $puanMik		= array(
                                        '1'=>9,
                                        '2'=>3,
                                        '3'=>1,
                                        '4'=>49,
                                        '5'=>1,
		 								'6'=>-2,
		 								'7'=>-18,
		 								'10'=>1,
		 								'50'=>-1883,
	 									'70'=>362,
	 									'0'=>0
                                        );
   		public $puanAciklama	= array(
                                        '1'=>"Ses Yazdı",
                                        '2'=>"Sesi Taktir Aldı",
                                        '3'=>"Sesi Saygı Aldı",
                                        '4'=>"Sesi Paylaşıldı",
                                        '5'=>"Sesi Cevaplandı ",
   										'6'=>"Kendi Yorumunu sildi",
   										'7'=>"Kendi Sesini sildi",
   										'10'=>"Takipçi kazandı",
   										'50'=>"Etkili Şikayet Aldı",
   										'70'=>"Vekil Oyu Aldı",
   										'0'=>"Temel Puan Hesabı" //temel puan herkes için ayrı bir rakam
                                        );
                                        
		static public function main(){
	        global $model, $db;
	    }
	    /*
	     * Bir profilin Başlangıç puanını hesaplar  yeni üyeler yada her hafta başı yenilenir
	     */
	    public function temelPuanHesapla($profile)
	    {
	    	$profileClass	=new profile();
	    	$takipciPuan	=intval($profileClass->getfollowercount($profile->ID))*1;
	    	$surePuan		=intval($profileClass->getTimeInSite($profile->ID,"W"));
	    	$toplam			=$takipciPuan+$surePuan;
	    	return $toplam;
	    }
		/*
	     * Bir profilin Başlangıç puanını hesaplar  yeni üyeler yada her hafta başı yenilenir
	     */
	    public function temelPuanIsle($profile)
	    {
	    	global $db;
	    	$toplam	=$this->temelPuanHesapla($profile);
	    	
			$ip = $_SERVER['REMOTE_ADDR']; 
			
			$query	="INSERT INTO puanLog";
	    	$query	.="\n SET ";
	    	$query	.="\n puanType='0', ";
	    	$query	.="\n puan='".$toplam."', ";
	    	$query	.="\n puanAlanID='".$profile->ID."', ";
	    	$query	.="\n puanKazandiranID='0', ";
	    	$query	.="\n IP='".$ip."', ";
	    	$query	.="\n date='".date("Y-m-d H:i:s")."' ";
	    	
	    	$db->setQuery($query);
	    	$db->query();
			
			$pro		= new stdClass;
			$pro->ID	= $profile->ID;
			$pro->puan	= $toplam;
			$pro->temelPuanHesaplandi = 1;
			
			return $db->updateObject("profile", $pro, "ID"); 
			
	    }
	    /*
	     * Bir profilin şu anki puanını döndürür
	     */
	    public function get_profilePuan($ID)
	    {
	    	global $db;
			$db->setQuery("SELECT puan from profile where ID='".$ID."'");
	    	return intval($db->loadResult());
	    }
	    /*
	     * Bir Profile Puan ekler 
	     * $ID puan Eklenen Kisinin ID
	     * $puan eklenecek puan
	     * $type yorum paylaşım taktir  gibi hangi aksiyondan puan alındı
	     * $elemen işleme tabi puan alınan islemin elementi SES Yorum Taktir Alan ses
	     */
	    public function puanIslem($ID,$type,$element=null)
	    {
	    	global $db;
	    	if($this->get_oyGecerlimi($ID, $type,$element))
	    	{
	    		
	    		$mevcutPuan=$this->get_profilePuan($ID);
		    	$toplam=0;
		    	
		    	$toplam=$mevcutPuan+$this->puanMik[$type];
		    	
		    	$this->set_log($ID,$type,$element);
				
				$t=new stdClass;
				$t->puan=$toplam;
				$t->ID=$ID;
				
				return $db->updateObject("profile",$t,"ID");
		    	
	    	} 
	    	else
	    	{	
	    		return false;
	    	}
	    	
	    }
	    /*
	     * giriş çıkış yapılan puanların Logları tutuluyor
	     * $type log girilecek işlem tipi
	     * $element işleme tabi puan alınan islemin elementi SES Yorum Taktir Alan ses
	     */
	    public function set_log($ID,$type,$element=null)
	    {
	    	global $model,$db;
	    	if(@$element->ID>0)
	    		$elementID=$element->ID;
	    	else
	    		$elementID=0;
	    	$puanKazandiranID=$model->profileID;
	    	$ip = $_SERVER['REMOTE_ADDR']; 

			$log 			= new stdClass;
			$log->puanType	= $type;
			$log->puan		= $this->puanMik[$type];
			$log->puanAlanID= $ID;
			$log->puanKazandiranID= $puanKazandiranID;
			$log->elementID	= $elementID;
			$log->IP		= $ip;
			$log->date		= date("Y-m-d H:i:s");
			
	    	if($db->insertObject("puanLog",$log))
	    		return true;
	    	else 
	    		return false;
	    	
	    }

	    /*
	     * islem yapılmadan dublicate işlemmi kontrol edilecek
	     */
	    public function get_oyGecerlimi($ID,$type,$element=null)
	    {
			global $model, $db;
			
	    	if($element!=null)
	    	{
		    	if($element->ID>0)
		    		$elementID=$element->ID;
		    	else
		    		$elementID=0;
	    	}
	    	else 
	    	{
	    		$elementID=0; 
	    	}

	    	$puanKazandiranID=$model->profileID;
	    	
	    	$query	="SELECT count(*) FROM puanLog";
	    	$query	.="\n WHERE ";
	    	$query	.="\n puanType='".$type."' AND ";
	    	$query	.="\n puanAlanID='".$ID."' AND ";
	    	$query	.="\n puanKazandiranID='".$puanKazandiranID."' AND ";
	    	$query	.="\n elementID='".$elementID."' ";
			
			$db->setQuery($query);
			$islemvarmi=$db->loadResult();
			
	    	if($islemvarmi>0)
	    		return false;
	    	else
	    		return true;
	    }
	    public function get_maxpuanProfile($profileAdet=50)
		{
			global $db;
			$db->setQuery("SELECT ID From profile where status=1 order by puan DESC limit 0 , ".$profileAdet);
			return $db->loadResult();
		}
		public function puanSifirla()
		{
			global $db;
			$query="update profile set puan=0, temelPuanHesaplandi=0 ";
			$db->setQuery($query);
			if($db->query())
			{
				return true;
			}
			else {
				return false;
			}
			
		}
	}
?>