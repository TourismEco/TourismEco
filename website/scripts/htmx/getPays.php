<?php

// Sécurité
if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id_pays"])) {
    header("HTTP/1.1 400");
    exit;
}

require("../../functions.php");

if (!checkHTMX("pays", $_SERVER["HTTP_HX_CURRENT_URL"])) {
    header("HTTP/1.1 401");
    exit;
}

$cur = getDB();

// Mise en historique
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

// Informations de base
$query = "SELECT * FROM pays WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch(PDO::FETCH_ASSOC);
$nom = $ligne["nom"];
$description = $ligne["description"];
$sv1 = explode(" : ",htmlspecialchars($ligne["sv1"]));
$sv2 = explode(" : ",htmlspecialchars($ligne["sv2"]));
$sv3 = explode(" : ",htmlspecialchars($ligne["sv3"]));
$sv1Value = isset($sv1[1]) ? $sv1[1] : null;
$sv2Value = isset($sv2[1]) ? $sv2[1] : null;
$sv3Value = isset($sv3[1]) ? $sv3[1] : null;

// Villes pour la carte
$c = getCities($id_pays, $cur);
$cities = json_encode($c["cities"]);
$capitals = json_encode($c["capitals"]);

// Indicateurs
$queryIndic="SELECT * FROM `developpement_humain` WHERE iso_code= :id_pays";
$sth = $cur->prepare($queryIndic);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch(PDO::FETCH_ASSOC);

if ($ligne) {
    $rnb=$ligne["RNB par hab"];
    $esp=$ligne["Espérance de vie"];
    $hdi=$ligne["Value"];
} else {
    $rnb = "NA"; $esp = "NA"; $hdi = "NA";
}


// Rank
$statMaj = getStatMajeure($id_pays, $cur);
$minRanking = $statMaj["rank"];
$minVariable = $statMaj["var"];
$minYear = $statMaj["year"];

$countriesBars = [];
if ($minRanking <= 5) {
    // Si le pays concerné est dans les 5 premiers
    $query = "SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom FROM `alldata_rank`
    JOIN pays ON alldata_rank.id_pays = pays.id
    WHERE annee = $minYear AND $minVariable IS NOT NULL
    ORDER BY $minVariable ASC LIMIT 5;
    ";

    $sth = $cur->prepare($query);
    $sth->execute();

    while ($ligne = $sth->fetch(PDO::FETCH_ASSOC)) {
        $countriesBars[] = ["nom" => $ligne["nom"], "ranking" => $ligne[$minVariable], 'flag' => $ligne['id_pays']];
    }

} else {
    $query = "SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom FROM `alldata_rank`
    JOIN pays ON alldata_rank.id_pays = pays.id 
    WHERE annee = $minYear AND $minVariable IS NOT NULL
    ORDER BY $minVariable ASC LIMIT 2;";
    $sth = $cur->prepare($query);
    $sth->execute();

    while ($ligne = $sth->fetch(PDO::FETCH_ASSOC)) {
        $countriesBars[] = ["nom" => $ligne["nom"], "ranking" => $ligne[$minVariable], 'flag' => $ligne['id_pays']];
    }

    // Pays avant $id_pays
    $query = "SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom FROM `alldata_rank`
    JOIN pays ON alldata_rank.id_pays = pays.id
    WHERE annee = $minYear AND $minVariable IS NOT NULL AND $minVariable < :rank
    ORDER BY $minVariable DESC LIMIT 1;";
    $sth = $cur->prepare($query);
    $sth->bindParam(":rank", $minRanking, PDO::PARAM_INT);
    $sth->execute();
    $previousCountry = $sth->fetch(PDO::FETCH_ASSOC);
    if ($previousCountry !== false) {
        $countriesBars[] = [
            "nom" => $previousCountry["nom"],
            "ranking" => $previousCountry[$minVariable],
            'flag' => $previousCountry['id_pays']
        ];
    }

    $countriesBars[] = ["nom" => $nom, "ranking" => $minRanking, 'flag' => $id_pays];

    // Pays après $id_pays
    $query = "SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom FROM `alldata_rank`
    JOIN pays ON alldata_rank.id_pays = pays.id
    WHERE annee = $minYear AND $minVariable IS NOT NULL AND $minVariable > :rank ORDER BY $minVariable ASC LIMIT 1;
    ";
    $sth = $cur->prepare($query);
    $sth->bindParam(":rank", $minRanking, PDO::PARAM_INT);
    $sth->execute();
    $nextCountry = $sth->fetch(PDO::FETCH_ASSOC);
    $countriesBars[] = ["nom" => $nextCountry["nom"], "ranking" => $nextCountry[$minVariable], 'flag' => $nextCountry['id_pays']];
}

// Evolution du classement
$previousYear = $minYear - 1;
$queryRankingPreviousYear = "SELECT annee, $minVariable FROM `alldata_rank` WHERE annee = :prevYear AND id_pays = :id_pays AND $minVariable IS NOT NULL LIMIT 1;";

$sth2 = $cur->prepare($queryRankingPreviousYear);
$sth2->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth2->bindParam(":prevYear", $previousYear, PDO::PARAM_INT);
$sth2->execute();

$rankingPrevious = $sth2->fetch(PDO::FETCH_ASSOC);

$rankingPreviousYear = null;
if ($rankingPrevious) {
    $rankingPreviousYear = ["annee" => $rankingPrevious["annee"], "ranking" => $rankingPrevious[$minVariable]];
}

// Données des graphiques
$dataLine = dataLine($id_pays, $cur);
$dataLineMean = dataMean($cur);
$dataLine["comp"] = dataCompareLine($dataLine["data"],$dataLineMean);
$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataLine = json_encode($dataLine,JSON_NUMERIC_CHECK);
$dataLineMean = json_encode($dataLineMean,JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataBarreLine= json_encode(dataBarreLine($id_pays, $cur),JSON_NUMERIC_CHECK);


// Pays favori
if (isset($_SESSION["user"])) {
    $id_client = $_SESSION['user']['username'];
    $id_pays = $_GET['id_pays'];
    $query = "SELECT COUNT(id_client) FROM favoris WHERE id_pays = :id_pays AND id_client = :id_client";
    $sth = $cur->prepare($query);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->bindParam(":id_client", $id_client, PDO::PARAM_STR);
    $sth->execute();

    if ($sth->fetchColumn() > 0) {
        $favorite = "assets/icons/heart_full.png";
    } else {
        $favorite = "assets/icons/heart.png";
    }
} else {
    $favorite = "";
}

// Scores
$query = "SELECT * FROM pays_score WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligneS = $sth->fetch();
foreach (array("pibParHab","elecRenew","arriveesTotal","gpi","idh","gesHab") as $key => $value) {
    echo cardScore($value,$ligneS[$value]);
}

foreach (array("Global","Decouverte","Economique","Ecologique") as $key => $value) {
    echo scoreBox($value,$ligneS["score".$value],$ligneS["label".$value]);
}


if ($ligneS["labelGlobal"] != null) {
    echo <<<HTML
        <div class="container-presentation" id="score0" hx-swap-oob="outerHTML">
            <div class="score-box score-$ligneS[labelGlobal]">$ligneS[labelGlobal]</div>
        </div>
    HTML;
} else {
    echo <<<HTML
        <div class="container-presentation" id="score0" hx-swap-oob="outerHTML">
            <div class="score-box score-NA"><img src='assets/icons/bd.svg'></div>
        </div>
    HTML;
}

$icons = array("pibParHab" =>"dollar", "ges" =>"cloud", "co2" =>"cloud", "arriveesTotal" =>"down", "idh" =>"idh", "gpi" =>"shield", "elecRenew" =>"elec", "safety" =>"shield",);
$texts = array("pibParHab" =>"PIB par Habitant", "gesHab" =>"Émissions de GES par habitant", "arriveesTotal" =>"Arrivées touristiques", "idh" =>"Indice de développement humain", "gpi" => "Global peace index", "elecRenew" =>"Production d'énergies renouvellables", "safety" => "Score de sécurité", "co2" => "Emissions de CO2");
$suffix = ($minRanking == 1) ? 'er' : 'ème';
$suffixPrev = ($rankingPreviousYear['ranking'] == 1) ? 'er' : 'ème';

echo <<<HTML

<div class="container-presentation expand-3" id="bandeau0" hx-swap-oob="outerHTML">
    <div class="bandeau"> 
        <img class="img-bandeau" src='assets/img/$id_pays.jpg' alt="Bandeau">
            <img class="favorite" id="favorite" src="$favorite" hx-get="scripts/htmx/getFavorite.php" hx-trigger="click" hx-swap="outerHTML" hx-vals="js:{id_pays:'$id_pays'}">
        <div class="flag-plus-nom">
            <img class="flag-bandeau" src='assets/twemoji/$id_pays.svg'>
            <h2>$nom</h2>
        </div>
    </div>
</div>

<div class="rank expand-2" id="rankPays"  hx-swap-oob="outerHTML">
    <div class="title-scores">
        <img class="score-NA" src="assets/icons/$icons[$minVariable].svg">
        <p>Statistique majeure : $texts[$minVariable]</p>
    </div>
    
    <div class="container-rank">
    <div>
        <p>Placé $minRanking$suffix mondialement en $minYear.</p>
        <p class="rank-text">En $rankingPreviousYear[annee], le pays était $rankingPreviousYear[ranking]$suffixPrev</p>
HTML;

if ($minVariable == "co2" || $minVariable == "ges") {
    echo '<p class="rank-textRank" style="color:red;"><img class="iconClassement" src="assets/icons/dangerClassement.svg" title="Un rang élevé en émissions signifie que le pays émet beaucoup de gaz polluants pour l\'atmosphère, ce qui contribue au réchauffement climatique.">Cette statistique est négative.</p>';
} else if ($minVariable == "safety" || $minVariable == "gpi") {
    echo '<p class="rank-textRank" style="color:red;"><img class="iconClassement" src="assets/icons/dangerClassement.svg" title="Un rang élevé dans les scores de paix et de sécurité révèlent un pays dangereux et/ou en crise.">Cette statistique est négative.</p>';
}

$rankDifferenceSup = $rankingPreviousYear['ranking'] - $minRanking;
$rankDifferenceInf =  $minRanking - $rankingPreviousYear['ranking'];

if ($minRanking < $rankingPreviousYear['ranking']) {
    echo '<p class="rank-text" style="font-size: 13px; color:green;">Le pays a gagné ' . $rankDifferenceSup . ' places dans le classement</p>';
} elseif ($minRanking > $rankingPreviousYear['ranking']) {
    echo '<p class="rank-text" style="font-size: 13px; color:darkorange;">Le pays a perdu ' . $rankDifferenceInf . ' places dans le classement</p>';
} elseif ($minRanking == $rankingPreviousYear['ranking']){
    echo '<p class="rank-text" style="font-size: 13px; color:darkgrey;">Le pays n\'a pas bougé du classement</p>';
}

echo "</div><div class='trait'></div><div class='chart'>";

    $barHeights2 = [80, 70, 60, 50, 40];
    $barWidths2 = [38, 35,32,30,28];
    foreach ($countriesBars as $index => $country) {
        $backgroundColor = ($country['flag'] == $id_pays) ? 'grey' : 'darkgrey';
        $suffix = ($country['ranking'] == 1) ? 'er' : 'ème';
        if ($index == 2 && $minRanking > 5) {
            echo <<<HTML
                <div class="txt_class"> ... </div>
            HTML;
        }
        echo <<<HTML
            <div class="bar" style="height: {$barHeights2[$index]}%; background-color: $backgroundColor; width:{$barWidths2[$index]}px;">
                <img style="width:{$barWidths2[$index]}px;" src="assets/twemoji/{$country['flag']}.svg" alt="{$country['nom']}" /> {$country['ranking']}$suffix
            </div>
        HTML;
    }

echo <<<HTML
</div>
        </div>
    </div>
</div>

<div class="scroll expand-3" id="description1" hx-swap-oob="outerHTML">
    <div class="scroll-buttons">
        <div class="scroll-dot dot-active" id="scrb0" data-index="0"></div>
        <div class="scroll-dot" id="scrb1" data-index="1"></div>
        <div class="scroll-dot" id="scrb2" data-index="2"></div>
        <div class="scroll-dot" id="scrb3" data-index="3"></div>
    </div>

    <div class="container-scrollable" id="scr" hx-swap-oob="outerHTML">
        <div class="allow-scroll-pays">
            <h3 class="h3-scroll">Espérance de vie moyenne</h3>
            <p class="indic">$esp ans</p>
        </div>
        <div class="allow-scroll-pays">
            <h3 class="h3-scroll">Indice de développement humain</h3>
            <p class="indic">$hdi</p>
        </div>
        
        <div class="allow-scroll-pays">
            <h3 class="h3-scroll">Revenu par habitant / par an</h3>
            <p class="indic">$rnb $</p>
        </div>
    </div>
</div>

<div class="scroll container-presentation expand-3" id="description0" hx-swap-oob="outerHTML">
    <div class="scroll-buttons">
        <div class="scroll-dot dot-active" id="scrb0" data-index="0"></div>
        <div class="scroll-dot" id="scrb1" data-index="1"></div>
        <div class="scroll-dot" id="scrb2" data-index="2"></div>
        <div class="scroll-dot" id="scrb3" data-index="3"></div>
    </div>
    <div class="container-scrollable" id="scrAnec" hx-swap-oob="outerHTML">
        <div class="allow-scroll-pays" id="srcDesc">
            <h3 class="h3-scrollDescrib">Description</h3>
            <p class="anec">$description</p>
        </div>
        <div class="allow-scroll-pays">
            <h3 class="h3-scrollDescrib">$sv1[0]</h3>
            <p class="anec">$sv1Value</p>
        </div>
        <div class="allow-scroll-pays">
            <h3 class="h3-scrollDescrib">$sv2[0]</h3>
            <p class="anec">$sv2Value</p>
        </div>
        <div class="allow-scroll-pays">
            <h3 class="h3-scrollDescrib">$sv3[0]</h3>
            <p class="anec">$sv3Value</p>
        </div>
    </div>
</div>

<p class="name" id="nom0" hx-swap-oob="outerHTML">$nom</p>

<img class="flag-small" id="flag-bot" hx-swap-oob="outerHTML" src='assets/twemoji/$id_pays.svg'>

<h2 id="paysvs" hx-swap-oob="outerHTML">$nom VS Moyenne mondiale</h2>

<script id=orders hx-swap-oob=outerHTML>
    spiderHTMX(0, $dataSpider, $dataTab, "$nom")
    barreLineHTMX($dataBarreLine, "$nom")
    linePaysHTMX($dataLine, $dataLineMean, "$nom")
    changeScore('Global')

    miniMap[0].zoomTo("$id_pays")
    miniMap[0].addCities($cities)
    miniMap[0].addCapitals($capitals)
</script>

HTML;

echo addSafety($cur, $id_pays, "safe0");

?>