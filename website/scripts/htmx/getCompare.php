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
$query = "SELECT * FROM pays, pays_score WHERE pays.id = pays_score.id AND pays.id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];
$sv1 = explode(" : ",htmlspecialchars($ligne["sv1"]));
$sv2 = explode(" : ",htmlspecialchars($ligne["sv2"]));
$sv3 = explode(" : ",htmlspecialchars($ligne["sv3"]));

$dataLine = json_encode(dataLine($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);

$c = getCities($id_pays, $cur);
$cities = json_encode($c["cities"]);
$capitals = json_encode($c["capitals"]);

$incrP = $incr+1;

$id = $_SESSION["pays"][0];


// Effectuer une requête pour récupérer le nom du pays correspondant à l'identifiant
$query_nom0 = "SELECT nom FROM pays WHERE id = :id";
$sth_nom0= $cur->prepare($query_nom0);
$sth_nom0->bindParam(":id", $id, PDO::PARAM_STR);
$sth_nom0->execute();
$ligne_nom0 = $sth_nom0->fetch();
$nom0 = $ligne_nom0["nom"];



if ((isset($_GET["load"]) && $incr == 1) || !isset($_GET["load"])) {
    $icons = array("pibParHab" =>"dollar", "ges" =>"cloud", "co2" =>"cloud", "arriveesTotal" =>"down", "idh" =>"idh", "gpi" =>"shield", "elecRenew" =>"elec", "safety" =>"shield",);
    $texts = array("pibParHab" =>"PIB par Habitant", "ges" =>"Émissions de gaz à effet de serre par habitant", "arriveesTotal" =>"Arrivées touristiques", "idh" =>"Indice de développement humain", "gpi" => "Global peace index", "elecRenew" =>"Production d'énergies renouvellables", "safety" => "Score de sécurité", "co2" => "Emissions de CO2");

    $maj0 = getStatMajeure($_SESSION["pays"][0], $cur);
    $maj1 = getStatMajeure($_SESSION["pays"][1], $cur);
    
    $maj0["val0"] = getSpecific($_SESSION["pays"][0],$maj0["year"],$maj0["var"],$cur)["val"];
    $val1 = getSpecific($_SESSION["pays"][1],$maj0["year"],$maj0["var"],$cur);
    $maj0["val1"] = $val1["val"];
    $maj0["rank1"] = $val1["rank"];

    if ($maj0["var"] == $maj1["var"]) {
        $maj1["var"] = $maj1["second"];
        $maj1["rank"] = $maj1["rankSecond"];
        $maj1["year"] = $maj1["yearSecond"];
    }
    
    $maj1["val0"] = getSpecific($_SESSION["pays"][1],$maj1["year"],$maj1["var"],$cur)["val"];
    $val0 = getSpecific($_SESSION["pays"][0],$maj1["year"],$maj1["var"],$cur);
    $maj1["val1"] = $val0["val"];
    $maj1["rank1"] = $val0["rank"];

    $suffix0 = ($maj0['rank'] == 1) ? 'er' : 'ème';
    $suffix01= ($maj0['rank1'] == 1) ? 'er' : 'ème';
    $suffix1 = ($maj1['rank'] == 1) ? 'er' : 'ème';
    $suffix11 = ($maj1['rank1'] == 1) ? 'er' : 'ème';

    $text0 =  $texts[$maj0['var']];
    $text1 =  $texts[$maj1['var']];

    if ($maj0["val1"] != 0) {
        $div0 = number_format($maj0["val0"] / $maj0["val1"], 2);
        if ($maj0["val0"] < $maj0["val1"]){
            $txtdiv0 = "plus grand";
            $icon0 = "up.svg";
        } else {
            $txtdiv0 = "plus petit";
            $icon0 = "down.svg";
        }
    } else {
        $div0 = "-";
        $txtdiv0 = "-";
        $icon0 = "";
    }

    if ($maj1["val1"] != 0) {
        $div1 = number_format($maj1["val0"] / $maj1["val1"], 2);
        if ($maj1["val0"] < $maj1["val1"]){
            $txtdiv1 = "plus grand";
            $icon1 = "up";
        }
        else {
            $txtdiv1 = "plus petit";
            $icon1 = "down.svg";
        }
    } else {
        $div1 = "-";
        $txtdiv1 = "-";
        $icon1 = "";
    }
    
    $maj0["val0"]= formatNumber($maj0["val0"], $maj0["var"]);
    $maj0["val1"]= formatNumber($maj0["val1"], $maj0["var"]);

    $maj1["val0"]= formatNumber($maj1["val0"], $maj1["var"]);
    $maj1["val1"]= formatNumber($maj1["val1"], $maj1["var"]);
    
    echo <<<HTML

        <div class="container-presentation expand-2" id="bestRank0" hx-swap-oob="outerHTML">
            <div class="compareRank">
                <div class="majstat-compare"><h3>Statistique majeure</h3><img style="margin:0 5px" class="flag-compare" src='assets/twemoji/$id.svg'></div>
                <p class="rank-text">pour <strong style="color:#862213;"> $text0 </strong> en $maj0[year] </p>
                <p class="rank-textRank"> $maj0[rank]$suffix0</p>
                <p class="rank-textRank">$maj0[val0]  </p>
            </div>
            <div class="trait"></div>
            <div class="compareRank">
                <div class="majstat-compare"><h3>Comparaison avec $nom</h3><img style="margin:0 5px" class="flag-compare" src='assets/twemoji/$id_pays.svg'></div>
                <p class="rank-textRank">$maj0[rank1]$suffix01, $maj0[val1]</p>
                <p class="rank-textRank"><img class="small-icon" src="assets/icons/mini$icon0">$div0 fois</p>
                <p class="rank-text">$txtdiv0 que $nom0</p>
            </div>
        </div>

        <div class="container-presentation expand-2" id="bestRank1" hx-swap-oob="outerHTML">
            <div class="compareRank">
                <div class="majstat-compare"><h3>Statistique majeure</h3><img style="margin:0 5px" class="flag-compare" src='assets/twemoji/$id_pays.svg'></div>
                <p class="rank-text">pour <strong style="color:#862213;"> $text1 </strong> en $maj1[year] </p>
                <p class="rank-textRank"> $maj1[rank]$suffix1</p>
                <p class="rank-textRank">$maj1[val0]</p> </p>
            </div>
            <div class="trait"></div>
            <div class="compareRank">
                <div class="majstat-compare"><h3>Comparaison avec $nom0</h3><img style="margin:0 5px" class="flag-compare" src='assets/twemoji/{$_SESSION["pays"][($incr+1)%2]}.svg'></div>
                <p class="rank-textRank">$maj1[rank1]$suffix11, $maj1[val1]</p>
                <p class="rank-textRank"><img class="small-icon"  src="assets/icons/mini$icon1">$div1 fois</p>
                <p class="rank-text">$txtdiv1 que $nom</p>
            </div>
        </div>

    HTML;
}

$nomsScore = array("labelGlobal" => "Global", "labelDecouverte" => "Tourisme d'exploration", "labelEcologique" => "Tourisme éco-responsable", "labelEconomique" => "Tourisme moderne");
if (isset($_SESSION["user"])) {
    $score = "label".$_SESSION["user"]["score"];
} else {
    $score = "labelGlobal";
}

if ($ligne[$score] != null) {
    echo <<<HTML
        <div class="container-presentation score-home" id="score$incr" hx-swap-oob="outerHTML">
            <div class="score-box score-$ligne[$score]">$ligne[$score]</div>
            <div>Score $nomsScore[$score]</div>
        </div>
    HTML;
} else {
    echo <<<HTML
        <div class="container-presentation" id="score$incr" hx-swap-oob="outerHTML">
            <div class="score-box score-NA"><img src='assets/icons/bd.svg'></div>
            <div>Score $nomsScore[$score] - données manquantes</div>
        </div>
    HTML;
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


<p class="name" id="nom$incr" hx-swap-oob="outerHTML">$nom</p>
<t id="nomversus$incr" hx-swap-oob="outerHTML">$nom</t>
<img class="flag-compare" src="assets/twemoji/$id_pays.svg" id="flag$incr" hx-swap-oob="outerHTML">
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