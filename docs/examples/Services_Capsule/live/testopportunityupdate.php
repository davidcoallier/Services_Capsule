<?php
set_include_path(
    get_include_path() . PATH_SEPARATOR . 
    dirname(dirname(dirname(__FILE__))));

require_once 'Services/Capsule.php';

include '../config.php';

$opportunity = '28937';

try {
    $capsule = new Services_Capsule($config['appName'], $config['token']);
    $res = $capsule->opportunity->get($opportunity);
} catch (Services_Capsule_Exception $e) {
    print_r($e);
    die();
}

$person = array(
    'contacts' => array(
        'email' => array(
            'type' => 'Work',
            'emailAddress' => 'david.coallier@gmail.com',
         ),
    ),
    'title' => 'Mr',
    'firstName' => 'David',
    'lastName' => 'Gmail'
);

//$p = $capsule->person->add($person);

$p = $capsule->opportunity->party->add($opportunity, '1933303');
print_r($p);

echo PHP_EOL;
