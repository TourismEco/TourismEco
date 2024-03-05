<?php
require("../../functions.php");

$cur = getDB();

$id_pays = $_GET["id_pays"];
$_SESSION["pays"][0] = $id_pays;


if (!isset($_SESSION["historique"]) || !is_array($_SESSION["historique"])) {
    $_SESSION["historique"] = array();
}
if (!in_array($id_pays, $_SESSION["historique"])) {
    // Ajoutez le nouvel élément à l'historique
    $_SESSION["historique"][] = $id_pays;
  
    $maxHistoriqueSize = 3;  // Définir la taille maximale de l'historique
    while (count($_SESSION["historique"]) > $maxHistoriqueSize) {
        array_shift($_SESSION["historique"]);
    }
}

if (isset($_GET["map"])) {
    $map = "false";
} else {
    $map = "true";
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

$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataLine = json_encode(dataLine($id_pays, $cur)['data'],JSON_NUMERIC_CHECK);
$dataLineMean = json_encode(dataMean($cur),JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);

//Graphique Line
$covidLine = json_encode(dataLine($id_pays,$cur)['covid']['co2'],JSON_NUMERIC_CHECK);
$rankLine = json_encode(dataLine($id_pays,$cur)['rank']['co2']['rank'],JSON_NUMERIC_CHECK);
$rankLineYear = json_encode(dataLine($id_pays,$cur)['rank']['co2']['year'],JSON_NUMERIC_CHECK);

$minYearLine = json_encode(dataLine($id_pays,$cur)['min']['co2']['year'],JSON_NUMERIC_CHECK);
$minValueLine = json_encode(dataLine($id_pays,$cur)['min']['co2']['val'],JSON_NUMERIC_CHECK);

$maxYearLine = json_encode(dataLine($id_pays,$cur)['max']['co2']['year'],JSON_NUMERIC_CHECK);
$maxValueLine = json_encode(dataLine($id_pays,$cur)['max']['co2']['val'],JSON_NUMERIC_CHECK);

$compareMeanLineVal = json_encode(dataCompareMeanLine($id_pays,$cur)['val'], JSON_NUMERIC_CHECK);
$compareMeanLineType = json_encode(dataCompareMeanLine($id_pays,$cur)['type'], JSON_NUMERIC_CHECK);

//Graphique Barre Line
$dataBarreLine= json_encode(dataBarreLine($id_pays, $cur)['data'],JSON_NUMERIC_CHECK);
$dataBLMinYearPIB= json_encode(dataBarreLine($id_pays, $cur)['minPib']['year'],JSON_NUMERIC_CHECK);
$dataBLMinValPIB= json_encode(dataBarreLine($id_pays, $cur)['minPib']['value'],JSON_NUMERIC_CHECK);
$dataBLMaxYearPIB= json_encode(dataBarreLine($id_pays, $cur)['maxPib']['year'],JSON_NUMERIC_CHECK);
$dataBLMaxValPIB= json_encode(dataBarreLine($id_pays, $cur)['maxPib']['value'],JSON_NUMERIC_CHECK);

$dataBLMinValTourism= json_encode(dataBarreLine($id_pays, $cur)['minTourisme']['value'],JSON_NUMERIC_CHECK);
$dataBLMinYearTourism= json_encode(dataBarreLine($id_pays, $cur)['minTourisme']['year'],JSON_NUMERIC_CHECK);
$dataBLMaxValTourism= json_encode(dataBarreLine($id_pays, $cur)['maxTourisme']['value'],JSON_NUMERIC_CHECK);
$dataBLMaxYearTourism= json_encode(dataBarreLine($id_pays, $cur)['maxTourisme']['year'],JSON_NUMERIC_CHECK);

$dataBLcovidImpactPib= json_encode(dataBarreLine($id_pays, $cur)['covidImpactPib'],JSON_NUMERIC_CHECK);
$dataBLcovidImpactTourisme= json_encode(dataBarreLine($id_pays, $cur)['covidImpactTourisme'],JSON_NUMERIC_CHECK);




//$dataBarPays = json_encode(dataBarPays($id_pays, $cur),JSON_NUMERIC_CHECK);

echo <<<HTML

<div class="bandeau" id="bandeau0" hx-swap-oob="outerHTML">     
    <img class="img" src='assets/img/$id_pays.jpg' alt="Bandeau">
    <img class="flag" src='assets/twemoji/$id_pays.svg'>
    <h1 class="nom">$nom</h1>
    <p class="capital">Capitale : $capitale</p>
    <img id="favorite" src="assets/img/heart.png" hx-get="scripts/htmx/getFavorite.php" hx-trigger="click" hx-swap="outerHTML" hx-vals="js:{id_pays:'{$ligne['id_pays']}'}">
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

<div class="container-even" id="graphLine" hx-swap-oob="outerHTML">
    <div class="container-indic">
        <h3>Impact du covid sur le pays</h3>
        <p>$covidLine</p>
        <h3>Rang du pays en $rankLineYear </h3> 
        <p>$rankLine</p>
        <h3>Minimum atteint en $minYearLine</h3>
        <p> $minValueLine</p>
        <h3>Maximum atteint en $maxYearLine</h3>
        <p> $maxValueLine</p>
        <h3>Le pays pour la dernière années est $compareMeanLineVal fois $compareMeanLineType à la moyenne </h3>

    </div>
</div>

<div class="container-even" id="graphBarLine" hx-swap-oob="outerHTML">
    <div class="container-indic">
        <h3> Impact du covid sur le tourisme :</h3>
        <p> $dataBLcovidImpactTourisme </p>

        <h3> Impact du covid sur le PIB :</h3>
        <p> $dataBLcovidImpactPib </p>

        <h3>Valeurs minimales et maximales</h2>
        <table>
            <tr>
                <th>Indicateur</th>
                <th>Type</th>
                <th>Année</th>
                <th>Valeur</th>
            </tr>
            <tr>
                <td colspan="4"><hr></td>
            </tr>
            <tr>
                <td><strong>PIB</strong></td>
                <td>Minimum</td>
                <td>$dataBLMinYearPIB</td>
                <td>$dataBLMinValPIB</td>
            </tr>
            <tr>
                <td></td>
                <td>Maximum</td>
                <td>$dataBLMaxYearPIB</td>
                <td>$dataBLMaxValPIB</td>
            </tr>
            <tr>
                <td colspan="4"><hr></td>
            </tr>
            <tr>
                <td><strong>Tourisme</strong></td>
                <td>Minimum</td>
                <td>$dataBLMinYearTourism</td>
                <td>$dataBLMinValTourism</td>
            </tr>
            <tr>
                <td></td>
                <td>Maximum</td>
                <td>$dataBLMaxYearTourism</td>
                <td>$dataBLMaxValTourism</td>
            </tr>
        </table>
    </div>
    <br><br>
</div>





HTML;

if ($map) {
    echo <<<HTML
        <script id=scripting hx-swap-oob=outerHTML>
            spiderHTMX( $dataSpider, $dataTab, "$nom")
            barreLineHTMX($dataBarreLine, "$nom")
            linePaysHTMX($dataLine, $dataLineMean, "$nom")
            topHTMX($dataBar, "$nom")
           
            map.zoomTo("$id_pays")
            map.addCapitals($capitals)
            map.addCities($cities)        
        </script>
    HTML;
} else {
    echo <<<HTML
        <script id=scripting hx-swap-oob=outerHTML>
            spiderHTMX( $dataSpider, $dataTab, "$nom")
            barreLineHTMX($dataBarreLine, "$nom")
            linePaysHTMX($dataLine, $dataLineMean, "$nom")
            topHTMX($dataBar, "$nom")

            map.addCapitals($capitals)
            map.addCities($cities)               
        </script>
    HTML;
}

?>