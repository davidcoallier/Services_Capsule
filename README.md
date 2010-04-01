## Introduction
This is the PHP API Wrapper that allows developers to access the Capsule CRM api using PHP

## Examples

Get a **party** by party id:

    require_once 'Services/Capsule.php';
    
    try {
        $capsule = new Services_Capsule('appName', 'token');
        $party   = $capsule->party->get('partyId');
    } catch (Services_Capsule_Exception $e) {
        print_r($e);
    }
    
    print_r($party);

Get all **party**:

    require_once 'Services/Capsule.php';
    
    try {
        $capsule = new Services_Capsule('appName', 'token');
        $parties = $capsule->party->getList();
    } catch (Services_Capsule_Exception $e) {
        print_r($e);
    }
    
    print_r($parties);
    
Get a list of people in a **party**:

    require_once 'Services/Capsule.php';
    
    try {
        $capsule = new Services_Capsule('appName', 'token');
        $people  = $capsule->party->people->getAll('partyId');
    } catch (Services_Capsule_Exception $e) {
        print_r($e);
    }

    print_r($people);


Add a new history note to a **party**:

    require_once 'Services/Capsule.php';

    try {
        $capsule = new Services_Capsule('appName', 'token');
        $note  = $capsule->party->history->addNote(
            'partyId', 'This is a test note.'
        );
    } catch (Services_Capsule_Exception $e) {
        print_r($e);
    }

    var_dump($note); // This will be true if success
    
Get a list of **opportunity**:

    require_once 'Services/Capsule.php';

    try {
        $capsule = new Services_Capsule('appName', 'token');
        $opps  = $capsule->opportunity->getList();
    } catch (Services_Capsule_Exception $e) {
        print_r($e);
    }

    print_r($opps);