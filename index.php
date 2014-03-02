<?php if(isset($_GET['file']) == false){
    $rand = substr(base64_encode(sha1(time() . rand())), 0,8);
    header("Location: index.php?file=" . $rand);
}?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title> Shared Proxy </title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.1/jquery.min.js" type="text/javascript"></script>


</head>

<body>

<h1>Shared Proxy</h1>

<div>
    <input type="text" id="urlpath" name="urlpath" size="100">
    <button id="Go"> Go </button>
</div>
    

<div id="sharedBoard" width="1024" height="768">
</div>

<script type="text/javascript">

$(document).ready(function(){

    $("#Go").on("click", function(){
        updateURL($("#urlpath").val());
    });

});


var updateURL = function (URLconnector){
    var params = window.location.search.substr(1).split('=');
    var filepath = "";
    for(var i = 0; i < params.length; i++){
        if(params[i] === "file") {
            filepath = params[i + 1];
            break;
        }
    }

    $.ajax('api/proxy.php', {
        type: 'GET',
        dataType : 'html',
        data : {
          url : URLconnector,
          filepath : filepath
        },
        success: function (data, textStatus, jqXHR){
            $('#sharedBoard').html(data);
        },
        error: function (jqXHR, textStatus, errorThown){
            alert('Load failed');
        },
        cache:false
    });
};
</script>


</body>
</html>


<html>
<body>