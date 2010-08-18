<?php
set_include_path(
    get_include_path() . PATH_SEPARATOR . 
    dirname(dirname(dirname(__FILE__))));
    

require_once 'Services/Capsule.php';
include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'config.php';

// Fetch "Any" by tag
$getAnyParameters = array(
    'tag' => 'sales',
);

try {
    $capsule = new Services_Capsule($config['appName'], $config['token']);
    $res = $capsule->party->getAny($getAnyParameters);
} catch (Services_Capsule_Exception $e) {
}


$salespeople = array();

if (isset($res->parties) && isset($res->parties->person)) {
    foreach ($res->parties->person as $person => $details) {
        $salespeople[$details->id]['info'] = $details;
        
        $salespeople[$details->id]['opportunities'] = 
            $capsule->opportunity->getAny(
                array('owner' => $details->firstname . $details->lastname)
            );
    }
}

print_r($salespeople);
