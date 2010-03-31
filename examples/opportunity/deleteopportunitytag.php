<?php
set_include_path(
    get_include_path() . PATH_SEPARATOR . 
    dirname(dirname(dirname(__FILE__))));
    

require_once 'Services/Capsule.php';

include '../config.php';

$tagName = 'test-154b1872883ba71ccdab2f11788c958283636a09';

try {
    $capsule = new Services_Capsule($config['appName'], $config['token']);
    $res = $capsule->opportunity->tag->delete($config['opportunityId'], $tagName);
} catch (Services_Capsule_Exception $e) {
    print_r($e);
    die();
}

echo 'Tag deleted: ' . $tagName . PHP_EOL;
var_dump($res); die();
