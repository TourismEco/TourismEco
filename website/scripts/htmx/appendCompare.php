<?php
session_start();

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

if (!isset($_GET["id_pays"]) || !isset($_GET["incr"])) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

$id_pays = $_GET["id_pays"];
$incr = $_GET["incr"];

if (in_array($id_pays,$_SESSION["pays"])) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

$_SESSION["pays"][$incr] = $id_pays;
$nextIncr = ($incr+1)%2;

echo <<<HTML
    <img class="flag-small switch-compare" id="flag-bot$incr" src='assets/twemoji/$id_pays.svg' data-incr="$incr" hx-swap-oob="outerHTML" >

    <script id="scripting" hx-swap-oob="outerHTML">
        $(".switch-compare").removeClass("active")
        $("#fb$nextIncr").addClass("active")
        nb = $nextIncr*53
        $("#trans-compare").css("transform","translateX("+nb+"px)")
        incr = $nextIncr
    </script>
HTML;

?>