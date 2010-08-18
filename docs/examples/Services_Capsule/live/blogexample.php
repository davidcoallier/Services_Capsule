<?php
set_include_path(
    get_include_path() . PATH_SEPARATOR . 
    dirname(dirname(dirname(__FILE__))));

require_once 'Services/Capsule.php';
include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'config.php';

$appName = $config['appName'];
$token   = $config['token']; // Get that from the website
$oppId   = $config['opportunityId'];

// 1. Let's create a new person in our service
// 2. Fetch this user using party search
// 3. Update this user
// 4. Add him to an opportunity
// 5. Get a drink.

try {
    $capsule = new Services_Capsule($appName, $token);
    
    // #1: Let's create a new person in our service
    $personInfo = array(
        'contacts' => array(
            'email' => array(
                'type' => 'Work',
                'emailAddress' => 'david@echolibre.com',
             ),
        ),
        'title' => 'Mr',
        'firstName' => 'David',
        'lastName' => 'Gallchobair'
    );
    
    $personAdded = $capsule->person->add($personInfo);
    
    if ($personAdded !== true) {
        die('Oh noes could not create person');
    }
    
    // #2: Fetch this user using party search
    $search = $capsule->party->getAny(array(
        'email' => 'david@echolibre.com'
    ));
    
    if (!isset($search->person->id)) {
        die('Oh noes could not find the person');
    }
    
    // #3: Update this user
    $personUpdate  = array('lastName' => 'Coallier');
    $personUpdated = $capsule->person->update(
        $search->person->id, $personUpdate
    );
    
    if ($personUpdated !== true) {
        //die('Oh noes could not update the person');
    }
    
    // #4: Add him to an opportunity
    $added = $capsule->opportunity->party->add(
        $oppId, $search->person->id
    );
    
    // #5: Get a drink.
    
} catch (Services_Capsule_Exception $e) { 
    print_r($e); die();
}