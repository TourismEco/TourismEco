<?php
require("../../functions.php");

$cur = getDB();

$id_pays = $_GET["id_pays"];
$_SESSION["pays"][0] = $id_pays;

if (isset($_GET["map"])) {
    $map = false;
} else {
    $map = true;
}

// Nom
$query = "SELECT * FROM pays WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];
$description = $ligne["description"];
$letter = getLetter($ligne["score"]);

// Capitale
$query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
$sth = $cur->prepare($query);
$is_capitale = 1;
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":is_capitale", $is_capitale, PDO::PARAM_INT);
$sth->execute();
$ligne = $sth->fetch();
$capitale = $ligne["nom"];


$query = "SELECT * FROM economie WHERE id_pays = :id_pays and annee = :annee";
$sth = $cur->prepare($query);
$annee = 2021;
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":annee", $annee, PDO::PARAM_INT);
$sth->execute();
$ligne = $sth->fetch();
$pib = $ligne["pibParHab"];
      
$query = "SELECT * FROM ecologie WHERE id_pays = :id_pays and annee = :annee";
$sth = $cur->prepare($query);
$annee = 2020;
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":annee", $annee, PDO::PARAM_INT);
$sth->execute();
$ligne = $sth->fetch();
$co2 = $ligne["co2"];

$query = "SELECT * FROM tourisme WHERE id_pays = :id_pays and annee = :annee";
$sth = $cur->prepare($query);
$annee = 2021;
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":annee", $annee, PDO::PARAM_INT);
$sth->execute();
$ligne = $sth->fetch();
$arrivees = $ligne["arriveesTotal"];

$query = "SELECT * FROM surete WHERE id_pays = :id_pays and annee = :annee";
$sth = $cur->prepare($query);
$annee = 2023;
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":annee", $annee, PDO::PARAM_INT);
$sth->execute();
$ligne = $sth->fetch();
$gpi = $ligne["gpi"];

$query = "SELECT * FROM villes WHERE id_pays = :id_pays";
$id_pays = $_GET["id_pays"];
$sth = $cur -> prepare($query);
$sth -> bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth -> execute();

$cities = array();
$capitals = array();
while ($rs = $sth->fetch()) {
    if (!$rs["capitale"]) {
        $cities[] = array(
            "id"=>$rs["id"], 
            "title"=>$rs["nom"], 
            "geometry"=>array(
                "type"=>"Point",
                "coordinates"=>array($rs["lon"],$rs["lat"])
            )
        );
    } else {
        $capitals[] = array(
            "id"=>$rs["id"], 
            "title"=>$rs["nom"], 
            "geometry"=>array(
                "type"=>"Point",
                "coordinates"=>array($rs["lon"],$rs["lat"])
            )
        );
    }
}

$cities = json_encode($cities);
$capitals = json_encode($capitals);


echo <<<HTML

<div class="bandeau" id="bandeau0" hx-swap-oob="outerHTML">     
    <img class="img" src='assets/img/$id_pays.jpg' alt="Bandeau">
    <img class="flag" src='assets/twemoji/$id_pays.svg'>
    <h1 class="nom">$nom</h1>
    <p class="capital">Capitale : $capitale</p>
    <img id="favorite" src="assets/img/heart.png" hx-get="scripts/htmx/getFavorite.php" hx-trigger="click" hx-swap="outerHTML">
    <!-- <img id="favorite" src="assets/img/heart_full.png"> -->

</div>

<div class="container-side bg-354F52" id="mini0" hx-swap-oob="outerHTML">
    <div class="bandeau-side"> 
        <img class="img img-side" src='assets/img/$id_pays.jpg' alt="Bandeau">
        <img class="flag-small" src='assets/twemoji/$id_pays.svg'>
        <h2 class="nom-small">$nom</h2>
        <div class="close-compare">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
        <g>
            <path d="M358.4,133.1v71.7h-256v46.1L0,169l102.4-87v51.2H358.4 M512,348.2l-102.4,81.9V384h-256v-71.7h256v-51.2L512,348.2"/>
        </g>
        </svg>                 
        </div>
    </div>
</div>

<div id="score" class="score-box score-$letter" hx-swap-oob="outerHTML">$letter</div>

<div class="container-even" id="indicateurs" hx-swap-oob="outerHTML">
    <div class="container-indic">
        <h3>Émissions de CO2</h3>
        <p>$co2</p>
    </div>
    <div class="container-indic">
        <h3>PIB/habitant</h3>
        <p>$pib</p>
    </div>
    <div class="container-indic">
        <h3>Indice de sûreté</h3>
        <p>$gpi</p>
    </div>
    <div class="container-indic">
        <h3>Arrivées</h3>
        <p>$arrivees</p>
    </div>
</div>

<div id="descip" hx-swap-oob="outerHTML">
    <p class="text-full">$description</p>
</div>

<div id="catalogue" hx-swap-oob="outerHTML"></div>

HTML;

if ($map) {
    echo <<<HTML
        <script id=scripting hx-swap-oob=outerHTML>
            map.zoomTo("$id_pays")
            map.addCapitals($capitals)
            map.addCities($cities)
        </script>
    HTML;
} else {
    echo <<<HTML
        <script id=scripting hx-swap-oob=outerHTML>
            map.addCapitals($capitals)
            map.addCities($cities)
        </script>
    HTML;
}

?>