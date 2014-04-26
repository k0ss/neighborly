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
?>
