<?php
include_once("./include/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Neighborly allows you to search for vulnerable networked devices worldwide.">
    <meta name="author" content="Kyle Ossinger">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>Neighborly</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="js/jquery.min.js"></script>
    <?php //load map functions only if we're showing the map
    if($_SERVER['PHP_SELF'] == '/map.php')
    {
    ?>
        <style type="text/css">
            html { height: 100% }
            body { height: 100%; margin: 0; padding: 0 }
            #map-canvas { height: 100% }
        </style>
        <script type="text/javascript"
                src="js/gmapapi.js">
        </script>
        <script type="text/javascript" src="js/map.js"></script>
    <?php
    }//end if(map.php) block
    else
    { //if not showing map, load login stuff ?>
        <!-- Custom styles for login template -->
        <link href="../css/signin.css" rel="stylesheet">
        <script src="../js/signin.js"></script>
    <?php
    } //end else block
    ?>
</head>