<?php
/**
 * Author: Kyle Ossinger
 * Date: 2/2/14
 * Time: 11:57 AM
 */
if(isset($_POST['k']))
{
    include_once("./include/shodan4php.php");
    $key = preg_replace('/[^a-z0-9]/i', '', $_POST['k']);
    $shodan = new shodan4php($key);
    $info = $shodan->info();
    if(is_null($info))
    {
        print json_encode(array("status"=>"fail","desc"=>"No such account for specified API key: $info."));
        return false;
    }
    else
    {
        session_start();
        $_SESSION['key'] = $key;
        print json_encode(array("status"=>"success","desc"=>"Login Successful!"));
        return true;
    }
}
else{
    include_once("./include/header.php");?>

<body>

<div class="container">
    <div class="jumbotron">
        <form class="form-signin" role="form" id="frmSignIn">
            <h1 class="text-center">Neighborly</h1>
            <input type="password" class="form-control" name="k" id="k" placeholder="Shodan API Key" required autofocus>
            <span id="alert-placeholder"></span>
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
            </label>
            <button class="btn btn-lg btn-primary btn-block" type="submit" id="btnSignIn">Sign in</button>
        </form>
    </div>
    <!-- NEED TO MAKE TRANSPARENT AND FIX SIZE, CENTER <img src="img/neighborhood-outline.svg" class="img-responsive"/> -->
</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
</body>
</html>
<?php
} //end if(isset($_POST['k']))
?>