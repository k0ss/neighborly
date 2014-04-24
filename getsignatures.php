<?php

include_once("./include/config.php");

//Grabs signatures from database
global $db;
$mysql = mysqli_connect($db['host'],$db['user'],$db['pass'],$db['schm']);
$sigs = array();
if(!mysqli_connect_errno())
{
    $result = mysqli_query($mysql,'SELECT vulnid,signature FROM signatures');
    while($row = mysqli_fetch_array($result))
    {
        array($sigs[$row['signature']]=intval($row['vulnid']));
    }
    exit(json_encode($sigs));
}
    /*
    //Now query for each signature using either a multi-curl_exec or separate requests.
    //list($count,$radius) = findDevices($coords,$query);
    //puts "Found $count devices in a $radius km radius"
    // Search Shodan
    puts "[DEBUG]\tapi.search(#{search})"
    results = api.search(search)

    //Show the results
    puts "Of those devices, #{results['total']} match your query..."

    results['matches'].each { |result|
            puts "IP: #{result['ip']}"
            puts result['data']
    }
    rescue Exception => e
            puts "Error: #{e.to_s}"
    end
    */
?>
