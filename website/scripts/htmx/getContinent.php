<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id_continent"])) {
    header("HTTP/1.1 400");
    exit;
}

require("../../functions.php");

$cur = getDB();

$id_continent = $_GET["id_continent"];
$_SESSION["continent"][0] = $id_continent;


if (!isset($_SESSION["historique"]) || !is_array($_SESSION["historique"])) {
    $_SESSION["historique"] = array();
}
if (!in_array($id_continent, $_SESSION["historique"])) {
    // Ajoutez le nouvel élément à l'historique
    $_SESSION["historique"][] = $id_continent;
  
    $maxHistoriqueSize = 3;  // Définir la taille maximale de l'historique
    while (count($_SESSION["historique"]) > $maxHistoriqueSize) {
        array_shift($_SESSION["historique"]);
    }
}

if (isset($_GET["map"])) {
    $map = false;
} else {
    $map = true;
}
//halfpie
$query = "SELECT score FROM pays WHERE id_continent = :id_continent";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent , PDO::PARAM_INT);
$sth->execute();
$dataHalfPie = array("A" => array("name"=>"A", "value"=>0), "B" => array("name"=>"B", "value"=>0), "C" => array("name"=>"C", "value"=>0), "D" => array("name"=>"D", "value"=>0), "E" => array("name"=>"E", "value"=>0));    
while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
        $letter= getLetter($rs["score"]);
        $dataHalfPie[$letter]["value"]++;
    }
    $i = 0;
    foreach ($dataHalfPie as $key => $value) {
        $dataHalfPie[$i++] = $dataHalfPie[$key];
        unset($dataHalfPie[$key]);
    }

// Nom
$query = "SELECT * FROM continents WHERE id = :id_continent";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();

if ($ligne !== false) {
    $nom = $ligne["nom"];
    $code = $ligne["code"];
} else {
    $nom = null;
    $code = null;
}

//Top 3
$query = "SELECT pays.nom
FROM continents
JOIN pays ON pays.id_continent = continents.id
WHERE continents.id = :id_continent
ORDER BY pays.score DESC
LIMIT 3;";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$top = $ligne["nom"];

//scatter arrivées
$query = "SELECT p.nom AS nom_pays, SUM(t.arriveesTotal) AS somme_arrivees, SUM(e.co2) AS total_co2
        FROM tourisme t
        JOIN pays p ON t.id_pays = p.id
        JOIN continents c ON p.id_continent = c.id
        JOIN ecologie e ON t.annee = e.annee AND t.id_pays = e.id_pays
        WHERE t.annee = 2020
        AND  c.id = :id_continent 
        GROUP BY p.nom;
    ";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();

$dataScatter = array();
    while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
        if (isset($rs["somme_arrivees"]) && isset($rs["total_co2"])){
            
            $dataScatter[] = array("var" => $rs["somme_arrivees"], "value" => $rs["total_co2"], "nom"=>$rs["nom_pays"]);

        }
}

//graph en barre
// $query = "SELECT pays.nom AS nom_pays, economie.pibParHab FROM pays JOIN economie ON pays.id = economie.id_pays WHERE pays.id_continent = :id_continent AND economie.annee = 2020 ORDER BY economie.pibParHab DESC;
// ";
// $sth = $cur->prepare($query);
// $sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
// $sth->execute();
// $dataBar = array();
//     while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
//         foreach (array("nom_pays","pibParHab") as $key => $value) {
//             if (!isset($rs[$value])){
//                 $rs[$value]=null;
//             }
//         }
//         $dataBar[] = array("name" => $rs["nom_pays"], "value" => $rs["pibParHab"]);
// }

//---------- Line chart mean, max and min



// Min
$data_Min = json_encode(dataOptContient($cur,$id_continent,"MIN"),JSON_NUMERIC_CHECK);
$data_Max = json_encode(dataOptContient($cur,$id_continent,"MAX"),JSON_NUMERIC_CHECK);

$data_Mean = json_encode(dataMOYContient($cur,$id_continent),JSON_NUMERIC_CHECK);

$dataScatterContinent = json_encode($dataScatter,JSON_NUMERIC_CHECK );
$dataHalfPie= json_encode($dataHalfPie,JSON_NUMERIC_CHECK);


$dataBarContinent = databarreContinent($id_continent, $cur);
$dataBarContinent = json_encode ($dataBarContinent,JSON_NUMERIC_CHECK);



$query = "SELECT pays.nom, score, pays.id
FROM continents
JOIN pays ON pays.id_continent = continents.id
WHERE continents.id = :id_continent
ORDER BY pays.score DESC
LIMIT 4;";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();

$rank = "";
$i = 0;
$iTAMERE = 1;
$cla = ["first","second","third","fourth"];
$sty = ["premier","deuxieme","troisieme","quatrieme"];
while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
    $rs["score"] = round($rs["score"]);
    $rank .= <<<HTML
        <div class="classement $cla[$i]">
            <div class="$sty[$i]">$iTAMERE</div>
            <div class="classement-pays">$rs[nom]</div>
            <img src="assets/twemoji/$rs[id].svg" alt="$rs[nom]" class="flagClassement">
            <img src="assets/img/$rs[id].jpg" alt="$rs[nom]" class="imgClassement">
            <div class="value">$rs[score]</div>
        </div>
    HTML;
    $i++;
    $iTAMERE++;
}

echo <<<HTML
<div id="rank" hx-swap-oob="outerHTML">
    $rank
</div>
HTML;

$queryRandomCountry = "SELECT id FROM pays WHERE id_continent = :id_continent ORDER BY RAND() LIMIT 1;";
$sth = $cur->prepare($queryRandomCountry);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();
$randomCountryId = $sth->fetchColumn();


//bandeau

echo <<<HTML


<div class="container-presentation expand-3" id="bandeau0" hx-swap-oob="outerHTML">
    <div class="bandeau"> 
        <img class="img-bandeau" src='assets/img/$randomCountryId.jpg' alt="Bandeau">
        <div class="flag-plus-nom">
            <img class="flag-bandeau" src="assets/icons/$nom.svg">
            <h2 class="nom">$nom</h2>
        </div>
        <img class="flag-down" src="assets/twemoji/$randomCountryId.svg">
    </div>
</div>

<div id="catalogue" hx-swap-oob="outerHTML"></div>

<script id=scripting hx-swap-oob=outerHTML>
    halfpieHTMX($dataHalfPie, "data")
    barreContinentHTMX($dataBarContinent, "data")
    lineHTMXContient($data_Mean, $data_Max, $data_Min)
    scatterHTMX($dataScatterContinent, "Emission de CO2 ")
</script>

HTML;

?>
