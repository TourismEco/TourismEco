<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id_pays"]) || !isset($_GET["incr"])) {
    header("HTTP/1.1 400");
    exit;
}

require("../../functions.php");

if (!checkHTMX("comparateur", $_SERVER["HTTP_HX_CURRENT_URL"])) {
    header("HTTP/1.1 401");
    exit;
}

$cur = getDB();

$id_pays = $_GET["id_pays"];
$incr = $_GET["incr"];

$old = "00";
if (!in_array($id_pays,$_SESSION["pays"])) {
    if (count($_SESSION["pays"]) != $incr) {
        $old = $_SESSION["pays"][$incr];
    }
    $_SESSION["pays"][$incr] = $id_pays;
}

// Nom
$query = "SELECT * FROM pays WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];
$sv1 = explode(" : ",htmlspecialchars($ligne["sv1"]));
$sv2 = explode(" : ",htmlspecialchars($ligne["sv2"]));
$sv3 = explode(" : ",htmlspecialchars($ligne["sv3"]));

$letter = getLetter($ligne["score"]);

$statMaj = getStatMajeure($id_pays, $cur);
$minRanking = $statMaj["rank"];
$minVariable = $statMaj["var"];
$minYear = $statMaj["year"];

$queryVal = "SELECT p.id, t.annee,t.departs, t.arriveesTotal, e.pib, e.pibParHab, s.gpi, s.safety, i.idh, ec.co2, ec.ges, ec.elecRenew
    FROM alldata, pays
    WHERE id_pays = :id_pays
    AND t.annee = :yearCountry
    GROUP BY t.annee;
";
$sth = $cur->prepare($queryVal); 
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":yearCountry", $yearCountry, PDO::PARAM_INT);
$sth->execute();
$country = $sth->fetch(PDO::FETCH_ASSOC);
$valCountry = ["nom" => $country["id"], "val" => $country[$variableCountry]];

$queryRankCountryCompare="SELECT $variableCountry, annee
FROM alldata_rank
WHERE id_pays = :id_pays_compare
AND annee = :yearCountry
ORDER BY annee DESC;";
$sth = $cur->prepare($queryRankCountryCompare);
$sth->bindParam(":id_pays_compare", $_SESSION["pays"][($incr+1)%2], PDO::PARAM_STR);
$sth->bindParam(":yearCountry", $yearCountry, PDO::PARAM_INT);
$sth->execute();
$ligneCompare = $sth->fetch(PDO::FETCH_ASSOC);
$rankCountryCompare =  $ligneCompare[$variableCountry];

if ($rankCountryCompare == 1) {
    $rankCountryCompare = "1er";
} else {
    $rankCountryCompare = $rankCountryCompare . " ème";
}

$queryValCompare = "SELECT p.id, t.annee,t.departs, t.arriveesTotal, e.pib, e.pibParHab, s.gpi, s.safety, i.idh, ec.co2, ec.ges, ec.elecRenew
    FROM pays p
    JOIN tourisme t ON t.id_pays = p.id
    JOIN economie e ON e.id_pays = p.id
    JOIN ecologie ec ON ec.id_pays = p.id
    JOIN surete s ON s.id_pays = p.id
    JOIN idh i ON i.id_pays = p.id
    WHERE p.id = :id_pays
    AND t.annee = :yearCountry
    GROUP BY t.annee;
";
$sth = $cur->prepare($queryValCompare); 
$sth->bindParam(":id_pays", $_SESSION["pays"][($incr+1)%2], PDO::PARAM_STR);
$sth->bindParam(":yearCountry", $yearCountry, PDO::PARAM_INT);
$sth->execute();
$country = $sth->fetch(PDO::FETCH_ASSOC);
$valCountryCompare = ["nom" => $country["id"], "val" => $country[$variableCountry]];

$dataLine = json_encode(dataLine($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);

$c = getCities($id_pays, $cur);
$cities = json_encode($c["cities"]);
$capitals = json_encode($c["capitals"]);

$incrP = $incr+1;


function majeurCompare($id_pays, $id_pays_compare, $cur){
    // faire le nécessaire pour récupérer la stat majeur de $id_pays = $id_pays
    // faire le nécessaire pour récupérer la stat majeur de $id_pays_compare = $_SESSION["pays"][($incr+1)%2]

    // trouver un moyen de ne pas avoir deux fois la meme stat

    //pour notre $id_pays, faire requêtes pour avoir les valeurs pour les deux stats majeures
    //les mettre correctement dans du HTML avec HTMX, et les bons ID

}

echo <<<HTML

<div class="container-presentation expand-2" id="bandeau$incr" hx-swap-oob="outerHTML">
    <div class="bandeau">
        <img class="img-bandeau" src='assets/img/$id_pays.jpg' alt="Bandeau">
        <div class="flag-plus-nom">
            <img class="flag-bandeau" src='assets/twemoji/$id_pays.svg'>
            <h2>$nom</h2>
        </div>
    </div>
</div>

<div class="container-presentation" id="score$incr" hx-swap-oob="outerHTML">
    <div class="score-box score-$letter">$letter</div>
</div>


<div class="container-presentation expand-2" id="bestRank$incr" hx-swap-oob="outerHTML">
    <div class="compareRank">
        <h3>Statistique majeure <img class="flag-tiny" src='assets/twemoji/$id_pays.svg'></h3>
        <p class="rank-textRank"> $rankCountry</p>
        <p class="rank-textRank">{$valCountry["val"]}</p> </p>
        <p class="rank-text">pour $variableCountry en $yearCountry </p>
    </div>
    <div class="trait"></div>
    <div class="compareRank">
        <h3><img class="flag-tiny" src='assets/twemoji/$id_pays.svg'> VS. <img class="flag-tiny" src='assets/twemoji/{$_SESSION["pays"][($incr+1)%2]}.svg'></h3>
        <p class="rank-textRank"> $rankCountryCompare</p>
        <p class="rank-textRank">{$valCountryCompare["val"]}</p> </p>
        <p class="rank-text">pour $variableCountry en $yearCountry </p>
    </div>
</div>


<p class="name" id="nom$incr" hx-swap-oob="outerHTML">$nom</p>
<span id="paysvs$incr" hx-swap-oob="outerHTML">$nom</span>
<img class="flag-tiny" src="assets/twemoji/$id_pays.svg" id="flag$incr" hx-swap-oob="outerHTML">
<img class="flag-small" id="flag-bot$incr" hx-swap-oob="outerHTML" src='assets/twemoji/$id_pays.svg'>

<script id="orders" hx-swap-oob="outerHTML">
    spiderHTMX($incr, $dataSpider, $dataTab, "$nom")
    lineHTMX($incr, $dataLine, "$nom")
    barHTMX($incr, $dataBar, "$nom")

    miniMap[$incr].addCities($cities)
    miniMap[$incr].addCapitals($capitals)
    miniMap[$incr].zoomTo("$id_pays")
</script>

HTML;

?>