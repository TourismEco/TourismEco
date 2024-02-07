<?php
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

// Nom
$query = "SELECT * FROM continents WHERE code = :id_continent";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];
$code = $ligne["code"];

//Top 3
$query = "SELECT pays.nom
FROM continents
JOIN pays ON pays.id_continent = continents.id
WHERE continents.code = :id_continent
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
        AND  c.code = :id_continent
        GROUP BY p.nom;
    ";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();

$dataScatter = array();
    while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
        foreach (array("nom_pays","somme_arrivees","total_co2") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            }
        }
        $dataScatter[] = array("var" => $rs["somme_arrivees"], "value" => $rs["total_co2"], "nom"=>$rs["nom_pays"]);
}

//graph en barre
$query = "SELECT pays.nom AS nom_pays, economie.pibParHab
        FROM pays
        JOIN continents ON pays.id_continent = continents.id
        JOIN economie ON pays.id = economie.id_pays
        WHERE continents.code = :id_continent
        AND economie.annee = 2020
        ORDER BY economie.pibParHab DESC;
";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();
$dataBar = array();
    while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
        foreach (array("nom_pays","pibParHab") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            }
        }
        $dataBar[] = array("name" => $rs["nom_pays"], "value" => $rs["pibParHab"]);
}

//---------- Line chart mean, max and min

$query = "SELECT economie.annee, AVG(economie.pibParHab) as moyenne
        FROM economie
        JOIN pays p ON economie.id_pays = p.id
        JOIN continents c ON p.id_continent = c.id
        WHERE c.code = :id_continent
        AND economie.annee <2021
        GROUP BY economie.annee, c.nom;
    ";
$sth = $cur->prepare($query);
$sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
$sth->execute();
$data_Mean = array();
    while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
        foreach (array("annee","moyenne") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            }
        }
        $data_Mean[] = array("year" => $rs["annee"], "value" => $rs["moyenne"]);
}

// Min
$queryMin = "SELECT economie.annee, p.nom AS nom_pays, economie.pibParHab
FROM economie
JOIN pays p ON economie.id_pays = p.id
JOIN continents c ON p.id_continent = c.id
WHERE c.code =  :id_continent
  AND economie.pibParHab IS NOT NULL
  AND (economie.annee, economie.pibParHab) IN (
    SELECT economie.annee, MIN(economie.pibParHab) AS min_pib
    FROM economie
    JOIN pays p ON economie.id_pays = p.id
    JOIN continents c ON p.id_continent = c.id
    WHERE c.code =  :id_continent
      AND economie.pibParHab IS NOT NULL
    GROUP BY economie.annee
  )
  ORDER BY economie.annee ASC;
";
    $sth = $cur->prepare($queryMin);
    $sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
    $sth->execute();
    $data_Min = array();
        while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
            foreach (array("pibParHab") as $key => $value) {
                if (!isset($rs[$value])){
                    $rs[$value]=null;
                }
            }
            $data_Min[] = array("year" => $rs["annee"], "nom"=>$rs["nom_pays"], "value" => $rs["pibParHab"],);
    }


// Max
    $queryMax = "SELECT economie.annee, p.nom AS nom_pays, economie.pibParHab
    FROM economie
    JOIN pays p ON economie.id_pays = p.id
    JOIN continents c ON p.id_continent = c.id
    WHERE c.code =  :id_continent
      AND economie.pibParHab IS NOT NULL
      AND (economie.annee, economie.pibParHab) IN (
        SELECT economie.annee, MAX(economie.pibParHab) AS max_pib
        FROM economie
        JOIN pays p ON economie.id_pays = p.id
        JOIN continents c ON p.id_continent = c.id
        WHERE c.code =  :id_continent
        AND economie.pibParHab IS NOT NULL
        GROUP BY economie.annee
    )
    ORDER BY economie.annee ASC
;
";    
    $sth = $cur->prepare($queryMax);
    $sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
    $sth->execute();
    $data_Max = array();
        while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
            foreach (array("pibParHab") as $key => $value) {
                if (!isset($rs[$value])){
                    $rs[$value]=null;
                }
            }
            $data_Max[] = array("year" => $rs["annee"],  "nom"=>$rs["nom_pays"], "value" => $rs["pibParHab"]);
    }

$dataScatterContinent = json_encode($dataScatter,JSON_NUMERIC_CHECK );
$dataBarContinent = json_encode($dataBar,JSON_NUMERIC_CHECK );

$data_Mean = json_encode($data_Mean,JSON_NUMERIC_CHECK);
$data_Min = json_encode($data_Min,JSON_NUMERIC_CHECK);
$data_Max = json_encode($data_Max,JSON_NUMERIC_CHECK);


//graph comparer
// $query = "SELECT pays,continents,score
//         FROM (
//             SELECT p.nom AS pays,c.nom AS continents,p.score,
//             ROW_NUMBER() OVER (PARTITION BY c.id ORDER BY p.score DESC) AS row_num
//             FROM pays p
//             JOIN continents c ON p.id_continent = :id_continent
//         ) AS ranked_pays
//         WHERE row_num <= 3;";
// $sth = $cur->prepare($query);
// $sth->bindParam(":id_continent", $id_continent, PDO::PARAM_STR);
// $sth->execute();
// $ligne = $sth->fetch();
// $country = $ligne["pays"];
// $mecontinent = $ligne["continents"];
// $score = $ligne["score"];



//bandeau

echo <<<HTML

<div class="bandeau" id="bandeau" hx-swap-oob="outerHTML">     
    <img class="img" src='assets/img/$code.jpg' alt="Bandeau">
    <h1 class="nom">$nom</h1>

</div>

<div id="catalogue" hx-swap-oob="outerHTML"></div>

<script id=scripting hx-swap-oob=outerHTML>
    barreContinentHTMX($dataBarContinent, "data")
    lineHTMX($data_Mean, $data_Max, $data_Min)
</script>

HTML;

?>
