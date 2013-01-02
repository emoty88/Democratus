<?php
    class config{
        static public  $checkip         = 0;
        static public  $dilimit         = 7;
        
        static public  $mydeputylimit   = 10;
        static public  $deputylimit     = 50;
        static public  $agendaelectionlimit = 7;
        
        static public  $diliketypes     = array('dilike1','dilike2');        
        
        static public  $proposalvotetypes = array('approve','reject','complaint');
        
        static public $filters          = array();
        static public $privacies        = array(
                                                0=>'Kimse',
                                                1=>'Beni Takip Edenler',
                                                2=>'Takip Ettiklerim',
                                                5=>'Herkes'
                                                );
        
        static public $votetypesss        = array(
                                                1=>'Kesinlikle Katılıyorum',
                                                2=>'Katılıyorum',
                                                3=>'Kararsızım',
                                                4=>'Katılmıyorum',
                                                5=>'Kesinlikle Katılmıyorum'
                                                );        
        static public $votetypes        = array(
                                                //1=>'Kesinlikle Katılıyorum',
                                                2=>'Katılıyorum',
                                                3=>'Kararsızım',
                                                4=>'Katılmıyorum',
                                                //5=>'Kesinlikle Katılmıyorum'
                                                );
                           
                                                
        static public $direasons        = array(
                                                1=>'müstehcen paylaşım',
                                                2=>'hakaret içeren paylaşım',
                                                3=>'diğer'
                                                );
                                                
        static public $dicommentreasons = array(
                                                1=>'müstehcen paylaşım',
                                                2=>'hakaret içeren paylaşım',
                                                3=>'diğer'
                                                );
        static public $prreasons        = array(
                                                1=>'sahte profil',
                                                2=>'uygunsuz fotoğraf',
                                                3=>'argo kullanım'
                                                );                                                
        static public $ppreasons        = array(
                                                1=>'müstehcen',
                                                2=>'hakaret',
                                                3=>'şiddet',
                                                4=>'ırkçı',
                                                5=>'antidemokratik'
                                                );                                                
    }
?>