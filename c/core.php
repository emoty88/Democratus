<?php
    require_once(COREPATH."functions.php");
    
    function __autoload($class_name) {
        require_once COREPATH . 'class.' . $class_name . '.php';
    }

    $db = new databasei;
    $db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PREFIX,1);
    $db->singleserver = 1;
	
	//ezSQL database sınıfı yapılandırması
	//$dbez = new ezSQL_mysqli(DB_USER,DB_PASSWORD,DB_NAME,DB_HOST); 
	//$dbez->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
	//$dbez->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
	//$dbez->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
	//ezSQL database sınıfı yapılandırması Sonu 
	
	//$db = new dbi2dbez;
	
	//KM::init("0628f27a8295634329f648360a42174f8b1dcac0");
    //$l = array();
    $model = new model;

    $model->main();
	
?>
