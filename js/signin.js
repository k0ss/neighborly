/**
 * Created by Kyle Ossinger on 2/6/14.
 */
$(document).ready(function(){
    $("#frmSignIn").on('submit', function(event){
        event.preventDefault();
        $(".alert").hide();
        var k = $("#frmSignIn").serialize();
        $.post("../index.php",k,function(data){
            if(data.status=="success")
                document.location = "map.php";
            else
            {
                $("#alert-placeholder").html(
                    '<div class="alert alert-danger fade in"><strong>Invalid API Key</strong></div>'
                );
            }
        },"json");
    })
    $("#k").on("input",function(event){$(".alert").hide();});
});