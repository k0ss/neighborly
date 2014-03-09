<?php
include_once("./include/header.php");
?>

<body>
<span id="control_panel">
    <form class="form-inline" role="form" id="frmLocation">
        <div class="form-group">
            <label class="sr-only" for="customAddress"></label>
            <input type="text" class="form-control" id="customAddress" placeholder="Enter address manually...">
        </div>
        <button type="submit" class="btn btn-default">Adjust Location</button>
    </form>
</span>
<div id="map-canvas"/>
</body>
</html>
