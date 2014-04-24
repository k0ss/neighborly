<?php
include_once("./include/header.php");
?>

<body>
<h1 class="text-center" id="decoration">
    <span class="glyphicon glyphicon-eye-open"></span>
    <br/>
    Welcome to Neighborly
</h1>
<form class="form-inline text-center" role="form" id="frmLocation">
    <div class="form-group form-group-lg">
        <label class="sr-only" for="customAddress">Enter address here.</label>
        <input type="text" class="form-control" id="customAddress" placeholder="Enter address manually...">
        <button type="submit" class="btn btn-primary">Go</button>
    </div>
</form>
<div id="map-canvas"/>
</body>
</html>