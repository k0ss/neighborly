/**
 * Created by Kyle Ossinger on 2/6/14.
 */
$(document).ready(function(){
    $("#frmSignIn").on('submit', function(event){
        event.preventDefault();
        var k = $("#frmSignIn").serialize();
        $.post("../index.php",k,function(data){
            if(data.status=="success")
            {
                document.location = "map.php";
            }
            else
            {
            }
        },"json");
    })
});