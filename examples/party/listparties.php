<?php
set_include_path(
    get_include_path() . PATH_SEPARATOR . 
    dirname(dirname(dirname(__FILE__))));
    

require_once 'Services/Capsule.php';
include '../config.php';
try {
    $capsule = new Services_Capsule($config['appName'], $config['token']);
    $res = $capsule->party->getList();
} catch (Services_Capsule_Exception $e) {
    print_r($e);
    die();
}

var_dump($res); die();
