<?php
/**
 * Created by Kyle Ossinger
 */
include_once("./include/config.php");
include_once("./include/shodan4php.php");

/**
 * This function is deprecated, but used to auto-increase the radius by 1km if not enough devices in a certain area
 * function findDevices($coords)
{
    global $shodan;
    $radius = 0;
    $count = 0;
    while($count < 1)
    {
        $radius += 1;
        $nearbyDevices = "geo:$coords,$radius $q"; //to_s used as superstition...shodan_api is buggy
        $count = $shodan->count($nearbyDevices)['total'];
    }

    return [$count,$radius]; #return number of devices and radius that found them
}
 */

global $shodan;
//$shodan = new shodan4php($_SESSION['key']);
if(!isset($_GET['op']) || !isset($_GET['loc']) || !isset($_GET['sig']))
    exit(-1);
else{
    $coords = preg_replace('/[^0-9 ,\.-]/i', '', $_GET['loc']);
    $sig = $_GET['sig'];
    $query = "$sig geo:$coords,1";
    switch($_GET['op']){
        case 1: //only find count for devices
            $hitCount = $shodan->count($query);
            break;
        case 2: //search for devices
            $hitCount = $shodan->search($query);
    }
    exit(json_encode($hitCount));
}

?>