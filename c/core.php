<?php
    require_once(COREPATH."functions.php");
    
    function __autoload($class_name) {
        require_once COREPATH . 'class.' . $class_name . '.php';
    }

    $db = new databasei;
    $db->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PREFIX,1);
    $db->singleserver = 1;
	
    $model = new model;

    $model->main();
	
?>
