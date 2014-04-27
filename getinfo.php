<?php
if(!isset($_GET['vid']))
    exit(-1);
else{
    $vid = intval($_GET['vid']);
    include_once("./include/config.php");
    //Grabs signatures from database
    global $db;
    $mysql = mysqli_connect($db['host'],$db['user'],$db['pass'],$db['schm']);
    if(!mysqli_connect_errno())
    {
        $result = mysqli_query($mysql,"SELECT * FROM vulndb WHERE vulnid = $vid");
        if($result != FALSE)
        {
            $row = mysqli_fetch_array($result);
            exit(json_encode(array("name"=>$row['devicename'],"desc"=>$row['description'],
                            "creds"=>$row['defaultcreds'],"adv"=>$row['advisorylink'])));
        }
        else{
            exit(-1);
        }
    }
}
?>