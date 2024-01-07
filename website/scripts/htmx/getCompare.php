<?php
require("../../functions.php");

$cur = getDB();

$id_pays = $_GET["id_pays"];
$incr = $_GET["incr"];

// Nom
$query = "SELECT * FROM pays WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];

// Capitale
$query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
$sth = $cur->prepare($query);
$is_capitale = 1;
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":is_capitale", $is_capitale, PDO::PARAM_INT);
$sth->execute();
$ligne = $sth->fetch();
$capitale = $ligne["nom"];

// Spider
$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataLine = json_encode(dataLine($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);

echo <<<HTML

<div class="bandeau-container" id="bandeau$incr" hx-swap-oob="outerHTML">     
    <img class="img" src='assets/img/$id_pays.jpg' alt="Bandeau">
    <img class="flag" src='assets/twemoji/$id_pays.svg'>
    <h1 class="nom">$nom</h1>
    <p class="capital">Capitale : $capitale</p>
</div>

<div class="container-mini bg-354F52" id="mini$incr" hx-swap-oob="outerHTML">
    <div class="mini-bandeau"> 
        <img class="img-small" src='assets/img/$id_pays.jpg' alt="Bandeau">
        <img class="flag-small" src='assets/twemoji/$id_pays.svg'>
        <h2 class="nom-small">$nom</h2>
    </div>
</div>

<table id="tabtemp">
    <tr><td id="nom_$incr" hx-swap-oob=outerHTML>$nom</td></tr>
</table>

<script id=scripting hx-swap-oob=outerHTML>
    spiderAjax($incr, $dataSpider, $dataTab, "$nom")
    lineAjax($incr, $dataLine, "$nom")
    barAjax($incr, $dataBar, "$nom")
    $("#tabtemp").remove()
    $("#scripting").empty()
</script>



HTML;

?>