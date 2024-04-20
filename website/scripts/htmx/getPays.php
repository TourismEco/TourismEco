<?php

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

// Nom
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

// Capitale
$c = getCities($id_pays, $cur);
$cities = json_encode($c["cities"]);
$capitals = json_encode($c["capitals"]);

// Indicateurs
$queryIndic="SELECT * FROM `developpement_humain` WHERE iso_code= :id_pays";
$sth = $cur->prepare($queryIndic);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch(PDO::FETCH_ASSOC);
$rnb=$ligne["RNB par hab"];
$esp=$ligne["Espérance de vie"];
$hdi=$ligne["Value"];

$queryIndicT="SELECT * FROM `alldata` WHERE id_pays= :id_pays ORDER BY annee DESC LIMIT 1";
$sth = $cur->prepare($queryIndicT);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch(PDO::FETCH_ASSOC);
if (is_array($ligne)) {
    $arriveesT = $ligne["arriveesTotal"];
    $anneeT = $ligne["annee"];
    $departT = $ligne["departs"];
} else {
    $arriveesT = null;
    $anneeT = null;
    $departT = null;
}

// Rank
// requête SQL pour récupérer toutes les années pour le pays dans l'ordre décroissant.
$queryRank = "SELECT *
FROM alldata_rank
WHERE id_pays = :id_pays
ORDER BY annee DESC;";
$sth = $cur->prepare($queryRank);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();

// Créez un tableau pour stocker les classements pour chaque variable.
$variables = ["co2", "elecRenew", "pibParHab", "gpi", "arriveesTotal", "departs", "idh", "ges", "safety"];
$rankings = [];

// Parcourez le résultat de la requête.
while ($ligne = $sth->fetch(PDO::FETCH_ASSOC)) {
    // Pour chaque ligne, parcourez les noms des variables.
    foreach ($variables as $variable) {
        // Si la valeur dans la ligne pour cette variable est non nulle et que nous n'avons rien stocké pour celle-ci, stockez le classement et l'année.
        if ($ligne[$variable] !== null && !isset($rankings[$variable])) {
            $rankings[$variable] = ["ranking" => $ligne[$variable], "year" => $ligne["annee"]];
        }
    }

    // Si toutes les variables ont un classement stocké, arrêtez-vous.
    if (count($rankings) == count($variables)) {
        break;
    }
}

$minRanking = min(array_column($rankings, "ranking"));
$keys = array_keys($rankings, min($rankings));
$minVariable = null;
if (!empty($keys)) {
    $minVariable = $keys[0];
}$minYear = $rankings[$minVariable]['year'];

if ($minRanking == 1) {
    $minRanking = "1er";
} else {
    $minRanking = $minRanking . " ème";
}

// On récupère le premier pays, le pays juste avant le $id_pays, notre pays et le pays juste après.
$querySup=" SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom
FROM `alldata_rank`
JOIN pays ON alldata_rank.id_pays = pays.id
where annee = $minYear
AND $minVariable IS NOT NULL
ORDER BY $minVariable ASC
LIMIT 2;";
$sth = $cur->prepare($querySup);
$sth->execute();

// Créez un tableau pour stocker les classements pour chaque pays.
$coutriesClassements = [];

// Parcourez le résultat de la requête.
while ($ligne = $sth->fetch(PDO::FETCH_ASSOC)) {
    $coutriesClassements[] = ["nom" => $ligne["nom"], "ranking" => $ligne[$minVariable], 'flag' => $ligne['id_pays']];
}

// Pays avant $id_pays
$queryPreviousCountry = "
SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom
FROM `alldata_rank`
JOIN pays ON alldata_rank.id_pays = pays.id
WHERE annee = $minYear
AND $minVariable IS NOT NULL
AND $minVariable < :rank
ORDER BY $minVariable DESC
LIMIT 1;
";
$sth = $cur->prepare($queryPreviousCountry);
$sth->bindParam(":rank", $minRanking, PDO::PARAM_INT);
$sth->execute();
$previousCountry = $sth->fetch(PDO::FETCH_ASSOC);
$CountryPrev = [];
if ($previousCountry !== false) {
    $CountryPrev = [
        "nom" => $previousCountry["nom"],
        "ranking" => $previousCountry[$minVariable],
        'flag' => $previousCountry['id_pays']
    ];
}

// Pays après $id_pays
$queryNextCountry = "SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom
FROM `alldata_rank`
JOIN pays ON alldata_rank.id_pays = pays.id
WHERE annee = $minYear
AND $minVariable IS NOT NULL
AND $minVariable > :rank
ORDER BY $minVariable ASC
LIMIT 1;
";
$sth = $cur->prepare($queryNextCountry);
$sth->bindParam(":rank", $minRanking, PDO::PARAM_INT);
$sth->execute();
$nextCountry = $sth->fetch(PDO::FETCH_ASSOC);
$CountryNext = [];
$CountryNext = ["id" => $nextCountry["id_pays"], "nom" => $nextCountry["nom"], "ranking" => $nextCountry[$minVariable], 'flag' => $nextCountry['id_pays']];



// Si le pays concerné est dans les 5 premiers
$queryCountriesTop = " SELECT alldata_rank.id_pays, $minYear, $minVariable, pays.nom AS nom
    FROM `alldata_rank`
    JOIN pays ON alldata_rank.id_pays = pays.id
    WHERE annee = $minYear
    AND $minVariable IS NOT NULL
    ORDER BY $minVariable ASC
    LIMIT 5;
";

$sth = $cur->prepare($queryCountriesTop);
$sth->execute();

// Créez un tableau pour stocker les classements pour chaque pays.
$CountriesTop = [];

// Parcourez le résultat de la requête.
while ($ligne = $sth->fetch(PDO::FETCH_ASSOC)) {
    $CountriesTop[] = ["id" => $ligne["id_pays"], "nom" => $ligne["nom"], "ranking" => $ligne[$minVariable], 'flag' => $ligne['id_pays']];
}

//Evolution du classement
$previousYear = $minYear - 1;
$selectedCountryId = $id_pays;
$queryRankingPreviousYear = "SELECT alldata_rank.id_pays as id, annee, $minVariable, pays.nom AS nom
FROM `alldata_rank`
JOIN pays ON alldata_rank.id_pays = pays.id
WHERE annee = :prevYear
AND id_pays = :id_pays
AND $minVariable IS NOT NULL
ORDER BY $minVariable ASC
LIMIT 1;
";

$sth2 = $cur->prepare($queryRankingPreviousYear);
$sth2->bindParam(":id_pays", $selectedCountryId, PDO::PARAM_STR);
$sth2->bindParam(":prevYear", $previousYear, PDO::PARAM_INT);
$sth2->execute();

$rankingPrevious = $sth2->fetch(PDO::FETCH_ASSOC);

if ($rankingPrevious) {
    $rankingPreviousYear = ["id" => $rankingPrevious["id"], "annee" => $rankingPrevious["annee"], "nom" => $rankingPrevious["nom"], "ranking" => $rankingPrevious[$minVariable], 'flag' => $rankingPrevious['id']];
} else {
    echo "No data for previous year";
}


// Graphiques
$dataLine = dataLine($id_pays, $cur);
$dataLineMean = dataMean($cur);
$dataLine["comp"] = dataCompareLine($dataLine["data"],$dataLineMean);

$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataLine = json_encode($dataLine,JSON_NUMERIC_CHECK);
$dataLineMean = json_encode($dataLineMean,JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);

//Graphique Barre Line
$allBareLine = dataBarreLine($id_pays, $cur);

$dataBarreLine= json_encode($allBareLine['data'],JSON_NUMERIC_CHECK);
$dataBLMinYearPIB= json_encode($allBareLine['minPib']['year'],JSON_NUMERIC_CHECK);
$dataBLMinValPIB= json_encode($allBareLine['minPib']['value'],JSON_NUMERIC_CHECK);
$dataBLMaxYearPIB= json_encode($allBareLine['maxPib']['year'],JSON_NUMERIC_CHECK);
$dataBLMaxValPIB= json_encode($allBareLine['maxPib']['value'],JSON_NUMERIC_CHECK);

$dataBLMinValTourism= json_encode($allBareLine['minTourisme']['value'],JSON_NUMERIC_CHECK);
$dataBLMinYearTourism= json_encode($allBareLine['minTourisme']['year'],JSON_NUMERIC_CHECK);
$dataBLMaxValTourism= json_encode($allBareLine['maxTourisme']['value'],JSON_NUMERIC_CHECK);
$dataBLMaxYearTourism= json_encode($allBareLine['maxTourisme']['year'],JSON_NUMERIC_CHECK);

$dataBLcovidImpactPib= json_encode($allBareLine['covidImpactPib'],JSON_NUMERIC_CHECK);
$dataBLcovidImpactTourisme= json_encode($allBareLine['covidImpactTourisme'],JSON_NUMERIC_CHECK);

//$dataBarPays = json_encode(dataBarPays($id_pays, $cur),JSON_NUMERIC_CHECK);

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

echo addSafety($cur, $id_pays, "safe0");

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

$letter = $ligneS["labelGlobal"];

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

<div class="container-presentation" id="score0" hx-swap-oob="outerHTML">
    <div class="score-box score-$letter">$letter</div>
</div>

<div class="rankPays expand-2 rank" id="rankPays"  hx-swap-oob="outerHTML">
    <div class="left-column">
HTML;
        if($minVariable == "co2"){
            echo <<<HTML
                <p class="rank-text"> Le rang du pays: </p>
                <p class="rank-textRank" style="color:red;">$minRanking <img class="iconClassement" src="assets/icons/dangerClassement.svg" title="Un rang élevé en émissions de CO2 signifie que le pays émet beaucoup de CO2, ce qui est mauvais pour l'environnement."></p>
                <p class="rank-text">pour les émissions de CO2 en $minYear </p>
            HTML;
        }elseif($minVariable == "elecRenew"){
            echo <<<HTML
                <p class="rank-text"> Le meilleur rang du pays: </p>
                <p class="rank-textRank" style="color:green;">$minRanking </p>
                <p class="rank-text">pour les énergies renouvelables en $minYear</p>
            HTML;
        }elseif($minVariable == "pibParHab"){
            echo <<<HTML
                <p class="rank-text"> Le meilleur rang du pays: </p>
                <p class="rank-textRank" style="color:green;">$minRanking </p>
                <p class="rank-text">pour le PIB par habitant en $minYear</p>
            HTML;
        }elseif($minVariable == "gpi"){
            echo <<<HTML
                <p class="rank-text"> Le meilleur rang du pays: </p>
                <p class="rank-textRank" style="color:green;">$minRanking </p>
                <p class="rank-text">pour l'indice de paix global en $minYear</p>
            HTML;
        }elseif($minVariable == "arriveesTotal"){
            echo <<<HTML
                <p class="rank-text"> Le meilleur rang du pays: </p>
                <p class="rank-textRank" style="color:green;">$minRanking </p>
                <p class="rank-text">pour le total d'arrivées sur le territoire en $minYear</p>
            HTML;
        }elseif($minVariable == "departs"){
            echo <<<HTML
                <p class="rank-text"> Le meilleur rang du pays: </p>
                <p class="rank-textRank" style="color:green;">$minRanking </p>
                <p class="rank-text">pour le total de départ du territoire en $minYear</p>
            HTML;
        }elseif($minVariable == "idh"){
            echo <<<HTML
                <p class="rank-text"> Le meilleur rang du pays: </p>
                <p class="rank-textRank" style="color:green;">$minRanking </p>
                <p class="rank-text">pour l'indice de développement humain en $minYear</p>
            HTML;
        }elseif($minVariable == "ges"){
            echo <<<HTML
                <p class="rank-text"> Le rang du pays: </p>
                <p class="rank-textRank" style="color:red;">$minRanking <img class="iconClassement" src="assets/icons/dangerClassement.svg" title="Un rang élevé en émissions de gaz à effet de serre signifie que le pays émet beaucoup de ces gaz, ce qui contribue au réchauffement climatique."></p>
                <p class="rank-text">pour les gaz à effet de serre en $minYear </p>
            HTML;
        }elseif($minVariable == "safety"){
            echo <<<HTML
                <p class="rank-text"> Le meilleur rang du pays: </p>
                <p class="rank-textRank" style="color:green;" >$minRanking </p>
                <p class="rank-text">pour l'indice de sureté en $minYear</p>
            HTML;
        }
echo <<<HTML
        </div>
    <div class="center-column">
        <div class="ranking-evolution">
HTML;
        if ($rankingPreviousYear) {
            $minRanking2 = intval($minRanking);
            $rankDifferenceSup = $rankingPreviousYear['ranking'] - $minRanking2;
            $rankDifferenceInf =  $minRanking2 - $rankingPreviousYear['ranking'];

            $suffix = ($rankingPreviousYear['ranking'] == 1) ? 'er' : 'ème';
            echo <<<HTML
            <p class="rank-text">En {$rankingPreviousYear['annee']}, le pays était {$rankingPreviousYear['ranking']}$suffix</p>
            HTML;
            if ($minRanking2 < $rankingPreviousYear['ranking']) {
                echo '<p class="rank-text" style="font-size: 13px; color:green;">Le pays a gagné ' . $rankDifferenceSup . ' places dans le classement</p>';
            } elseif ($minRanking2 > $rankingPreviousYear['ranking']) {
                echo '<p class="rank-text" style="font-size: 13px; color:darkorange;">Le pays a perdu ' . $rankDifferenceInf . ' places dans le classement</p>';
            } elseif ($minRanking2 == $rankingPreviousYear['ranking']){
                echo '<p class="rank-text" style="font-size: 13px; color:darkgrey;">Le pays n\'a pas bougé du classement</p>';
            }
        }
echo <<<HTML

    </div>
        
    </div>
    <div class="right-column">
        <div class="chart">
HTML;
        if($minRanking == "1er" || $minRanking == "2 ème" || $minRanking == "3 ème" || $minRanking == "4 ème" || $minRanking == "5 ème"){
            $selectedCountryId = $id_pays;
            $barHeights2 = [80, 70, 60, 50, 40];
            $barWidths2=[38, 35,32,30,28];
            foreach ($CountriesTop as $index => $country) {
                $backgroundColor = ($country['id'] == $selectedCountryId) ? 'grey' : 'darkgrey';
                $suffix = ($index == 0) ? 'er' : 'ème';
                echo <<<HTML
                <div class="bar" style="height: {$barHeights2[$index]}%; background-color: $backgroundColor; margin-bottom:5%; width:{$barWidths2[$index]}px;">
                    <img style="width:{$barWidths2[$index]}px;" src="assets/twemoji/{$country['flag']}.svg" alt="{$country['nom']}" /> {$country['ranking']} $suffix
                </div>
                HTML;
            }
        } else {
            $barHeights2 = [80, 70];
            $barWidths=[38, 35];
            foreach ($coutriesClassements as $index => $country) {
                $suffix = ($index == 0) ? 'er' : 'ème';
                echo <<<HTML
                <div class="bar" style="height: {$barHeights2[$index]}%; background-color: darkgrey; margin-bottom:5%; width:{$barWidths[$index]}px;">
                    <img style="width:{$barWidths[$index]}px;" src="assets/twemoji/{$country['flag']}.svg" alt="{$country['nom']}" /> {$country['ranking']} $suffix
                </div>
                HTML;
            }
            echo <<<HTML
            <div class="txt_class"> ... </div>
            <div class="bar" style="height: 60%; background-color: darkgrey;margin-bottom:5%; width:32px;">
                <img src="assets/twemoji/{$CountryPrev['flag']}.svg" alt="{$CountryPrev['nom']}" /> {$CountryPrev['ranking']} ème
            </div>            
            <div class="bar" style="height: 50%; background-color: grey; margin-bottom:5%; width:30px;">
                <img src="assets/twemoji/$id_pays.svg" alt="{$nom}" /> $minRanking
            </div>
            <div class="bar" style="height: 40%; background-color: darkgrey; margin-bottom:5%; width:28px;">
                <img src="assets/twemoji/{$CountryNext['flag']}.svg" alt="{$CountryNext['nom']}" /> {$CountryNext['ranking']} ème
            </div>  
            HTML;
        }

echo <<<HTML
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
        <div class="allow-scroll-pays" id="src2">
            <h3 class="h3-scroll" id="src1">Espérance de vie moyenne</h3>
            <p class="indic">$esp ans</p>
        </div>
        <div class="allow-scroll-pays">
            <h3 class="h3-scroll" id="src1">Indice de développement humain</h3>
            <p class="indic">$hdi</p>
        </div>
        
        <div class="allow-scroll-pays" id="src3">
            <h3 class="h3-scroll" id="src1">Revenu par habitant / par an</h3>
            <p class="indic">$rnb</p>
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
            <h3 class="h3-scrollDescrib" id="srcDesc">Description</h3>
            <p class="anec">$description</p>
        </div>
        <div class="allow-scroll-pays">
            <h3 class="h3-scrollDescrib" id="src1Anec">$sv1[0]</h3>
            <p class="anec">$sv1Value</p>
        </div>
        <div class="allow-scroll-pays" id="src2Anec">
            <h3 class="h3-scrollDescrib" id="src1Anec">$sv2[0]</h3>
            <p class="anec">$sv2Value</p>
        </div>
        <div class="allow-scroll-pays" id="src3Anec">
            <h3 class="h3-scrollDescrib" id="src1Anec">$sv3[0]</h3>
            <p class="anec">$sv3Value</p>
        </div>
    </div>
</div>

<div class="container-presentation" id="score0" hx-swap-oob="outerHTML">
    <div class="score-box score-$letter">$letter</div>
</div>

<p class="name" id="nom0" hx-swap-oob="outerHTML">$nom</p>

<img class="flag-small" id="flag-bot" hx-swap-oob="outerHTML" src='assets/twemoji/$id_pays.svg'>

<h2 id="paysvs" hx-swap-oob="outerHTML">$nom VS Moyenne mondiale</h2>

<script id=orders hx-swap-oob=outerHTML>
    spiderHTMX(0, $dataSpider, $dataTab, "$nom")
    barreLineHTMX($dataBarreLine, "$nom")
    linePaysHTMX($dataLine, $dataLineMean, "$nom")
    changeScore('Global')
    // topHTMX($dataBar, "$nom")

    miniMap[0].zoomTo("$id_pays")
    miniMap[0].addCities($cities)
    miniMap[0].addCapitals($capitals)
</script>

HTML;

?>