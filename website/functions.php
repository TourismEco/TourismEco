
<?php

require_once('config.php');

// Connect to the database
function getDB($hostname=DB_HOSTNAME, $username=DB_USERNAME, $password=DB_PASSWORD, $database=DB_DATABASE) {
    try {
        $conn =  new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_TIMEOUT, 1800);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
}

function getLetter($score) {
    if ($score < 20) {
        return "E";
    } else if ($score < 40) {
        return "D";
    } else if ($score < 60) {
        return "C";
    } else if ($score < 80) {
        return "B";
    } else {
        return "A";
    }
}

function addSlimCountry($id,$nom,$letter,$page) {
    if ($page == "pays") {
        return <<<HTML
            <div class="bandeau-slim" hx-get="pays.php" hx-vals="js:{id_pays:'$id'}" hx-swap="outerHTML swap:0.5s" hx-target="#zones" hx-select="#zones"> 
                <!-- <div class="mini-score-box score-$letter">$letter</div> -->
                <img class="img-slim" src='assets/mini/$id.jpg' alt="Illustration de $nom">
                <img class="flag-slim" src='assets/twemoji/$id.svg' alt="Drapeau de $nom">
                <h2 class="nom-slim">$nom</h2>
            </div>
    HTML;
    } else if ($page == "comparateur") {
        return <<<HTML
            <div class="bandeau-slim" hx-get="scripts/htmx/appendCompare.php" hx-vals="js:{id_pays:'$id',incr:getIncr()}" hx-swap="beforeend"> 
                <!-- <div class="mini-score-box score-$letter">$letter</div> -->
                <img class="img-slim" src='assets/mini/$id.jpg' alt="Illustration de $nom">
                <img class="flag-slim" src='assets/twemoji/$id.svg' alt="Drapeau de $nom">
                <h2 class="nom-slim">$nom</h2>
            </div>
        HTML;
    } else if ($page == "explorer") {
        return <<<HTML
            <div class="bandeau-slim" hx-get="scripts/htmx/getExplore.php" hx-vals="js:{id_pays:'$id'}" hx-swap="beforeend"> 
                <!-- <div class="mini-score-box score-$letter">$letter</div> -->
                <img class="img-slim" src='assets/mini/$id.jpg' alt="Illustration de $nom">
                <img class="flag-slim" src='assets/twemoji/$id.svg' alt="Drapeau de $nom">
                <h2 class="nom-slim">$nom</h2>
            </div>
        HTML;
    } else if ($page == "explorerFav") {
        return <<<HTML
            <div class="bandeau-slim" hx-post="pays.php" hx-vals="js:{id_pays:'$id'}" hx-swap="outerHTML swap:0.5s" hx-target="#zones" hx-select="#zones" hx-push-url="true"> 
                <!-- <div class="mini-score-box score-$letter">$letter</div> -->
                <img class="img-slim" src='assets/mini/$id.jpg' alt="Illustration de $nom">
                <img class="flag-slim" src='assets/twemoji/$id.svg' alt="Drapeau de $nom">
                <h2 class="nom-slim">$nom</h2>
            </div>
        HTML;
    }
    
}

function dataLine($pays, $conn) {
    $query = "SELECT annee AS year, co2, elecRenew, pibParHab, idh, gpi, arriveesTotal, departs FROM alldata WHERE id_pays = '$pays' ORDER BY annee;";

    $result = $conn->query($query);

    $data = array();
    $covid = array();
    $minAnnee = array();
    $maxAnnee = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        foreach (array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
                if ($rs["year"] == 2020) {
                    $covid[$value] = "N/A";
                }
            } else {
                if ($rs["year"] == 2020 && count($data) != 0) {
                    $covid[$value] = round(100*($rs[$value] - $data[count($data)-1][$value]) / $data[count($data)-1][$value],2);
                }
                if ($rs["year"] == 2020 && count($data) > 1) {
                    if ($data[count($data)-1][$value] == 0) {
                        $covid[$value] = "N/A";
                    } else {
                        $covid[$value] = round(100*($rs[$value] - $data[count($data)-1][$value]) / $data[count($data)-1][$value],2);
                    }
                }
                if (!isset($minAnnee[$value]) || $rs[$value] < $minAnnee[$value]["val"]) {
                    $minAnnee[$value] = array("val"=>$rs[$value], "year"=> $rs["year"]);
                }
                if (!isset($maxAnnee[$value]) || $rs[$value] > $maxAnnee[$value]["val"]) {
                    $maxAnnee[$value] = array("val"=>$rs[$value], "year"=> $rs["year"]);
                }
            }
        }

        $data[] = $rs;
    }

    $evol = array();
    $rank = array();

    $cols = array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh");
    foreach ($cols as $key => $value) {
        $start = 0;
        while ($start < count($data) && $data[$start][$value] == null) {
            $start++;
        }

        $end = count($data)-1;
        while ($end > 0 && $data[$end][$value] == null) {
            $end--;
        }

        if ($start == count($data) || $end == 0) {
            $evol[$value] = "N/A";
            $rank[$value] = "N/A";
        } else {
            $year = $data[$end]['year'];
            $evol[$value] = array("val" => round(100*($data[$end][$value] - $data[$start][$value]) / $data[$start][$value], 2), "start" => $data[$start]['year'], "end" => $year);

            $query = "SELECT $value AS `rank` FROM alldata_rank WHERE id_pays = '$pays' AND annee = $year;";
            if ($data[$start][$value] == 0) {
                $evol[$value] = "N/A";
            } else {
                $evol[$value] = array("val" => round(100*($data[$end][$value] - $data[$start][$value]) / $data[$start][$value], 2), "start" => $data[$start]['year'], "end" => $year);
            }
            $result = $conn->query($query);
            $rs = $result->fetch(PDO::FETCH_ASSOC);
            $rank[$value] = array("rank"=>$rs["rank"],"year"=>$year);
        }
        
    }

    return array("data"=>$data, "covid"=>$covid, "evol"=> $evol, "rank"=>$rank, 'min'=>$minAnnee, 'max'=>$maxAnnee);
}



function dataMean($conn) {
    $query = "SELECT * FROM moyennes ORDER BY year ASC";

    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        foreach (array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            }
        }

        $data[] = $rs;
    }

    return $data;
}

function dataCompareLine($data1, $data2) {
    $comparaison = array();
    foreach (array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh") as $value) {
        $end = count($data1)-1;
        while ($end >= 0 && $data1[$end][$value] == null) {
            $end--;
        }

        if ($end == -1) {
            $comparaison[$value] = array("year" => null, "val" => null);
        } else {
            $year = $data1[$end]["year"];
            $i = count($data2)-1;
            while ($i >= 0 && $data2[$i]["year"] != $year) {
                $i--;
            }

            if ($i == -1) {
                $comparaison[$value] = array("year" => null, "val" => null);
            } else {
                $comparaison[$value] = array("year" => $year, "val" => $data1[$end][$value]/$data2[$i][$value]);
            }   
        } 
    }
    return $comparaison;
}




function dataSpider($pays, $conn) {
    $query = "SELECT annee, co2 AS 'Emissions de CO2', elecRenew AS '% énergies ren.', pibParHab AS 'PIB/Hab', idh AS 'IDH', gpi AS 'Global Peace Index', arriveesTotal AS 'Arrivées touristiques', departs AS 'Départs' FROM alldata_norm WHERE id_pays = '$pays' ORDER BY annee;";

    $result = $conn->query($query);
    
    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $year = $rs["annee"];
        $data[$year] = array();
        foreach (array("PIB/Hab","% énergies ren.","Emissions de CO2","Arrivées touristiques","Départs","Global Peace Index","IDH") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            }
            $data[$year][] = array("var" => $value, "value" => $rs[$value]);
        }
    }

    return $data;
}


function dataBar($pays, $conn) {
   
    $query = "SELECT annee, co2, elecRenew, pibParHab, idh, gpi, arriveesTotal, departs FROM alldata_grow WHERE id_pays = '$pays' ORDER BY annee;
    ";

    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[$rs["annee"]] = array();
        foreach (array("pibParHab","co2","arriveesTotal","gpi","idh") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            } 
            $data[$rs["annee"]][] = array("var" => $value, "value" => $rs[$value]);
        }
    }

    return $data;
}

function dataBarreLine($pays, $conn) {
    $query = "SELECT annee, pibParHab, arriveesTotal FROM alldata WHERE id_pays = '$pays' ORDER BY annee;";

    $result = $conn->query($query);

    $data = array();
    $minPib = array();
    $maxPib = array();
    $minTourisme = array();
    $maxTourisme = array();
    $covidImpactPib = 0;
    $covidImpactTourisme = 0;

    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        if ( $rs['pibParHab']!=null && $rs['arriveesTotal']!=null && $rs['annee']!=null){
            $data[] = array(
                "year" => $rs['annee'],
                "value" => $rs['pibParHab'],
                "valueLeft" => $rs['arriveesTotal']
            );
        }
        // Min et Max pour les indicateurs
        if ((count($minPib) == 0 || $rs['pibParHab'] < $minPib['value']) && $rs['pibParHab']!=null) {
            $minPib = array("year" => $rs["annee"], "value" => $rs['pibParHab']);
        }
        if (count($maxPib) == 0 || $rs['pibParHab'] > $maxPib['value']) {
            $maxPib = array("year" => $rs["annee"], "value" => $rs['pibParHab']);
        }
        if (count($minTourisme) == 0 || $rs['arriveesTotal'] < $minTourisme['value']) {
            $minTourisme = array("year" => $rs["annee"], "value" => $rs['arriveesTotal']);
        }
        if (count($maxTourisme) == 0 || $rs['arriveesTotal'] > $maxTourisme['value']) {
            $maxTourisme = array("year" => $rs["annee"], "value" => $rs['arriveesTotal']);
        }

        // Impact du covid
        if ($rs["annee"] == 2020 && $rs["arriveesTotal"] != null) {
            $covidImpactPib =
                (100 * ($rs["pibParHab"] - $data[count($data) - 1]["value"])) /
                $data[count($data) - 1]["value"];
            $covidImpactTourisme =
                (100 *
                    ($rs["arriveesTotal"] -
                        $data[count($data) - 1]["valueLeft"])) /
                $data[count($data) - 1]["valueLeft"];
            if ($data[count($data) - 1]["value"] != 0) {
                $covidImpactPib =
                    (100 *
                        ($rs["pibParHab"] - $data[count($data) - 1]["value"])) /
                    $data[count($data) - 1]["value"];
            }
            if ($data[count($data) - 1]["valueLeft"] != null) {
                $covidImpactTourisme =
                    (100 *
                        ($rs["arriveesTotal"] -
                            $data[count($data) - 1]["valueLeft"])) /
                    $data[count($data) - 1]["valueLeft"];
            }
        }
    }

    return array(
        "data" => $data,
        "minPib" => $minPib,
        "maxPib" => $maxPib,
        "minTourisme" => $minTourisme,
        "maxTourisme" => $maxTourisme,
        "covidImpactPib" => $covidImpactPib,
        "covidImpactTourisme" => $covidImpactTourisme
    );
}


function dataBarreContinent($id_continent, $conn) {
    $query = "SELECT pays.nom AS `name`, id_pays, annee, co2, elecRenew, pibParHab, gpi, idh, arriveesTotal, departs FROM alldata, pays WHERE id_pays = pays.id AND id_continent = $id_continent AND annee = 2020 ORDER BY annee;";
    $result = $conn->query($query);

    $data = array();
    $maxAnnee = array();
    $minAnnee = array();
    $medianPays = array();
    $averageValues = array(
        "pibParHab" => array("sum" => 0, "count" => 0),
        "elecRenew" => array("sum" => 0, "count" => 0),
        "co2" => array("sum" => 0, "count" => 0),
        "arriveesTotal" => array("sum" => 0, "count" => 0),
        "departs" => array("sum" => 0, "count" => 0),
        "gpi" => array("sum" => 0, "count" => 0),
        "idh" => array("sum" => 0, "count" => 0)
    );


    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $rs;

        foreach (array("pibParHab", "elecRenew", "co2", "arriveesTotal", "departs", "gpi", "idh") as $value) {
            if ((!isset($minAnnee[$value]) || $rs[$value] < $minAnnee[$value]["val"]) && $rs[$value] != null) {
                $minAnnee[$value] = array("val" => $rs[$value], "name" => $rs["name"]);
            }
            if (!isset($maxAnnee[$value]) || $rs[$value] > $maxAnnee[$value]["val"]) {
                $maxAnnee[$value] = array("val" => $rs[$value], "name" => $rs["name"]);
            }

            if ($rs[$value] != null) {
                $averageValues[$value]["sum"] += $rs[$value];
                $averageValues[$value]["count"]++;

                // Ajouter le pays à la liste pour calculer la médiane
                $medianPays[$value][] = array("name" => $rs["name"], "value" => $rs[$value]);
            }
        }
    
    }
    // Calculer les moyennes finales
    $averages = array();
    foreach ($averageValues as $value => $stats) {
        if ($stats["count"] > 0) {
            $averages[$value] = $stats["sum"] / $stats["count"];
        } else {
            $averages[$value] = null; // Éviter la division par zéro si aucun élément n'a été trouvé
        }

        // Trier et obtenir le pays médian pour chaque indicateur
        if (isset($medianPays[$value]) && count($medianPays[$value]) > 0) {
            // Trier la liste des pays par valeur croissante
            usort($medianPays[$value], function ($a, $b) {
                return $a["value"] <=> $b["value"];
            });

            // Obtenir l'élément médian (milieu de la liste triée)
            $medianIndex = floor(count($medianPays[$value]) / 2);
            $medianPays[$value] = $medianPays[$value][$medianIndex];
        } else {
            $medianPays[$value] = null; // Aucun pays trouvé pour cet indicateur
        }
    }
    
    return array("data" => $data, 'min' => $minAnnee, 'max' => $maxAnnee, 'avg' => $averages, 'median' => $medianPays);
}


function dataOptContient($conn, $id_continent, $option) {
    $cols = array("pibParHab", "elecRenew", "co2", "arriveesTotal", "departs", "gpi", "idh");

    $data = array();
    $minAnnee = array();
    $maxAnnee = array();
    $evol = array();
    // Boucle sur les colonnes
    foreach ($cols as $key => $value) {
        $table = "alldata";

        $query = "SELECT annee, p.nom AS nom_pays, $value
        FROM $table
        JOIN pays p ON id_pays = p.id
        JOIN continents c ON p.id_continent = c.id
        WHERE c.id =  :id_continent
        AND $value IS NOT NULL
        AND (annee, $value) IN (
            SELECT annee, $option($value) AS min_pib
            FROM $table
            JOIN pays p ON id_pays = p.id
            JOIN continents c ON p.id_continent = c.id
            WHERE c.id =  :id_continent
            AND $value IS NOT NULL
            GROUP BY annee
        )
        ORDER BY annee ASC;
        ";

        $start = 0;
        $end = 0;

        $sth = $conn->prepare($query);
        $sth->bindParam(":id_continent", $id_continent, PDO::PARAM_INT);
        $sth->execute();

        while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            } else {
                if ($start == 0) {
                    $start = $rs["annee"];
                }
                $end = $rs["annee"];
            }

            if (!isset($data[$rs["annee"]])) {
                $data[$rs["annee"]] = array("year" => $rs["annee"]);
            }

            $data[$rs["annee"]][$value] = $rs[$value];
            $data[$rs["annee"]][$value."nom"] = $rs["nom_pays"];
 
            if ((!isset($minAnnee[$value]) || $rs[$value] < $minAnnee[$value]["val"]) && $rs[$value] != null) {
                $minAnnee[$value] = array("val" => $rs[$value], "year" => $rs["annee"], "nom" => $rs["nom_pays"]);
            }
            if (!isset($maxAnnee[$value]) || $rs[$value] > $maxAnnee[$value]["val"]) {
                $maxAnnee[$value] = array("val" => $rs[$value], "year" => $rs["annee"], "nom" => $rs["nom_pays"]);
            }
            

        }

        if ($start == 0 || $end == 0 || $data[$start][$value] == 0) {
            $evol[$value] = "N/A";
        } else {
            $year = $data[$end]['year'];
            $evol[$value] = array("val" => round(100*($data[$end][$value] - $data[$start][$value]) / $data[$start][$value], 2), "start" => $data[$start]['year'], "end" => $year);
            
        }
    }

    ksort($data);
 
    $i = 0;
    foreach ($data as $key => $value) {
        $data[$i++] = $data[$key];
        unset($data[$key]);
    }
    
    return array("data" => $data, 'min' => $minAnnee, 'max' => $maxAnnee, 'evol' => $evol);
}

function dataMOYContient($conn, $id_continent) {
    $query = "SELECT annee AS year, AVG(co2) AS co2, AVG(pibParHab) AS pibParHab, AVG(idh) AS idh, AVG(gpi) AS gpi, AVG(arriveesTotal) AS arriveesTotal, AVG(departs) AS departs FROM alldata, pays WHERE pays.id = id_pays AND id_continent = :id_continent GROUP BY annee
    ORDER BY annee ASC;";

    $sth = $conn->prepare($query);
    $sth->bindParam(":id_continent", $id_continent, PDO::PARAM_INT);
    $sth->execute();

    $data = array();
    while ($rs = $sth->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $rs;
    }

    return $data;
}

function dataTab($pays, $conn) {
    $query = "SELECT id_pays, annee, co2, elecRenew, pibParHab, gpi, idh, arriveesTotal, departs FROM alldata WHERE id_pays = '$pays' ORDER BY annee;";
    $result = $conn->query($query);
    $cols = array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh");

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $year = $rs["annee"];
        $data[$year] = array();
        foreach ($cols as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
                $rank = null;
            } else {
                $queryR = "SELECT $value AS `rank` FROM alldata_rank WHERE id_pays = '$pays' AND annee = $year;";
                $resRank = $conn->query($queryR);
                $rsR = $resRank->fetch(PDO::FETCH_ASSOC);
                $rank = $rsR["rank"];
            }
            $data[$rs["annee"]][] = array("var" => $value, "value" => $rs[$value],"rank" => $rank);
        }
    }

    return $data;
}

function dataExplorer($conn) {
    $cols = array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh");
    $years = array("pibParHab"=>2021,"elecRenew"=>2020,"co2"=>2020,"arriveesTotal"=>2022,"departs"=>2022,"gpi"=>2023,"idh"=>2022);
    $data = array();

    $query = "SELECT id, score, nom, RANK() OVER (ORDER BY score DESC) AS 'scorerank' FROM pays;";
    $result = $conn->query($query);
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[$rs["id"]] = array("id"=>$rs["id"], "score"=>$rs["score"], "scorerank"=>$rs["scorerank"], "nom"=>$rs["nom"],"pibParHab"=>null,"elecRenew"=>null,"co2"=>null,"arriveesTotal"=>null,"departs"=>null,"gpi"=>null,"idh"=>null,"pibParHabrank"=>667,"elecRenewrank"=>667,"co2rank"=>667,"arriveesTotalrank"=>667,"departsrank"=>667,"gpirank"=>667,"idhrank"=>667);
    }

    foreach ($cols as $key => $value) {
        $query = "SELECT id_pays, $value, RANK() OVER (ORDER BY $value DESC) AS 'rank' FROM alldata WHERE annee = $years[$value];";
        $result = $conn->query($query);
        while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
            if (isset($rs[$value])){
                if ($value == "arriveesTotal" || $value == "departs") {
                    $rs[$value]*=1000;
                }
                $data[$rs["id_pays"]][$value] = $rs[$value];
                $data[$rs["id_pays"]][$value.'rank'] = $rs["rank"];
            }
        }
    }

    $i = 0;
    foreach ($data as $key => $value) {
        $data[$i++] = $data[$key];
        unset($data[$key]);
    }

    return $data;
}

function carousel($conn) {
    $query = "SELECT id, nom FROM pays
            ORDER BY RAND()
            LIMIT 10";
    $result = $conn->prepare($query);
    $result->execute();
    $images = $result->fetchAll(PDO::FETCH_ASSOC);

    $conn = null;

    echo <<<HTML
        <div class="slide-container">

        <a class="prev" onclick="plusSlides(-1)">❮</a>

        <div class="text-center">
            <img id="logo-carousel" src="assets/icons/eco.png" alt="Logo TourismEco">
            <h2>Partez à la découverte du monde</h2>
        </div>
        
        <a class="next" onclick="plusSlides(1)">❯</a>

        
    HTML;

    foreach ($images as $image):
        echo <<<HTML
            <div class="custom-slider">
                <img class="slide-img" src="assets/img/$image[id].jpg" alt="Illustration de $image[nom]">
                <a class="slide-link" hx-post="pays.php" hx-push-url="true" hx-target="#zones" hx-select="#zones" hx-swap="outerHTML swap:0.5s" hx-vals="js:{id_pays:'$image[id]'}">
                    <div class="slide-text">
                        <p>$image[nom]</p>
                    </div>
        HTML;
        
    endforeach;
    echo <<<HTML
      </div>
    HTML;
}

function inputPays($value, $sens) {
    echo <<<HTML
        <input type="text" id="country_$sens" name="country_$sens" placeholder="Saisissez un pays" required value="$value" hx-swap="none"
        hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("country_$sens"), sens:"$sens"}' hx-swap-oob="outerHTML">
    HTML;
}

function inputVilles($id_pays, $value, $sens) {
    if ($id_pays != "") {
        echo <<<HTML
        <input type="text" id="city_$sens" name="city_$sens" placeholder="Sélectionnez une ville" required autocomplete="off" value="$value"
            hx-swap-oob="outerHTML" hx-get="scripts/htmx/listVilles.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("city_$sens"), id_pays:"$id_pays", sens:"$sens"}' hx-swap="none">
        HTML;
    } else {
        echo <<<HTML
        <input type="text" id="city_$sens" name="city_$sens" placeholder="Sélectionnez une ville" hx-swap-oob="outerHTML" required disabled>
        HTML;
    }
}

function emptyOptions($id) {
    echo <<<HTML
        <div id="$id" class="option-container" hx-swap-oob="outerHTML"></div>
    HTML;
}

function iterOptions($options, $id, $sens, $type) {
    echo <<<HTML
        <div id="$id" class="option-container" hx-swap-oob="outerHTML">
    HTML;

    if (!empty($options)) {
        foreach ($options as $option) {
            if ($type == "country") {
                echo <<<HTML
                    <option value=$option[id] hx-get="scripts/htmx/selectPays.php" hx-trigger="click" hx-vals="js:{id:'$option[id]',nom:'$option[nom]',sens:'$sens'}">$option[nom]</option>
                HTML;
            } else {
                echo <<<HTML
                    <option value=$option[id] hx-get="scripts/htmx/selectVille.php" hx-trigger="click" hx-vals="js:{id:'$option[id]', id_pays:'$option[id_pays]', nom:'$option[nom]',sens:'$sens'}">$option[nom]</option>
                HTML;
            }
        }
    }

    echo <<<HTML
        </div>
    HTML;
}

// get the id of a country from its name
function getCountryId($country): string {
    $conn = getDB();
    $sql = "SELECT id FROM pays WHERE nom = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$country]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result["id"];
}

function formatTime($seconds) {
    $hours = floor($seconds / 3600) > 0 ? sprintf("%02d", (floor($seconds / 3600))) . " h " : "";
    $minutes = intval(floor(($seconds / 60)) % 60) > 0 ? sprintf("%02d",(floor(($seconds / 60)) % 60)) . " min " : "";
    return $hours . $minutes;
}


function getCities($id_pays, $cur) {
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

    return array("cities"=>$cities, "capitals"=>$capitals);
}


function checkHTMX($page, $hx_page) {
    $hx = explode("/",$hx_page);
    if ($hx[2] == "localhost" || $hx[2] == "localhost:8080" || $hx[2] == "tourismeco.fr") {
        return $hx[count($hx)-1] == $page.".php";
    }
    return false;
}

function distanceMatrixRequestBuilder($origins, $destinations, $mode, $transit_mode=null) {
    $url =
        "https://maps.googleapis.com/maps/api/distancematrix/json?" .
        "origins=" . urlencode($origins["city"] . "," . $origins["country"]) .
        "&destinations=" . urlencode($destinations["city"] . "," . $destinations["country"]) .
        "&mode=" . $mode .
        "&key=" . MAPS_API_KEY;
    if ($mode == "transit") {
        $url .= "&transit_mode=" . $transit_mode;
    }
    return $url;
}

function getAirportCoordinates($airport_iata) {
    // $airport is the id of the airport
    $conn = getDB();
    $sql = "SELECT name, lat, lon FROM airports WHERE iata = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$airport_iata]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function cardScore($option, $value) {
    $arMin = array("pibParHab" =>0.12, "gesHab" =>0.12, "arriveesTotal" =>0.12, "idh" =>0.12, "gpi" =>0.12, "elecRenew" =>0.12);
    $arMax = array("pibParHab" =>0.988, "gesHab" =>0.988, "arriveesTotal" =>0.988, "idh" =>0.988, "gpi" =>0.988, "elecRenew" =>0.988);
    $icons = array("pibParHab" =>"dollar", "gesHab" =>"cloud", "arriveesTotal" =>"down", "idh" =>"idh", "gpi" =>"shield", "elecRenew" =>"elec");
    $texts = array("pibParHab" =>"PIB par Habitant", "gesHab" =>"Émissions de GES par habitant", "arriveesTotal" =>"Arrivées touristiques", "idh" =>"Indice de développement humain", "gpi" => "Global peace index", "elecRenew" =>"Production d'énergies renouvellables");

    $min = $arMin[$option];
    $max = $arMax[$option];
    $icon = $icons[$option];
    $text = $texts[$option];

    $letter = getLetter($value*100);

    if ($value == null) {
        return <<<HTML
            <div class="container-scores border-NA" id="sco-$option" hx-swap-oob="outerHTML">
                <div class="title-scores">
                    <img src="assets/icons/$icon.svg" class="score-NA">
                    <p>$text</p>
                </div>

                <div class="stats-scores">
                    <img src="assets/icons/bd.svg">
                    <p>Données manquantes</p>
                </div>
            </div>
        HTML;
    } else {
        $value = round($value, 2);
        return <<<HTML
            <div class="container-scores border-$letter" id="sco-$option" hx-swap-oob="outerHTML">
                <div class="title-scores">
                    <img src="assets/icons/$icon.svg" class="score-$letter">
                    <p>$text</p>
                </div>

                <div class="stats-scores">
                    <p>$value</p>
                    <!-- <div class="stats-scores-minmax">
                        <p>Min<br>$min</p>
                        <div class="trait-small"></div>
                        <p>Max<br>$max</p>
                    </div> -->
                </div>

                <div>
                    <p class="small-text" id="textp-$option"></p>
                    <div class="poids-scores" id="poids-$option">
                        <div class="el-poids"></div>
                        <div class="el-poids"></div>
                        <div class="el-poids"></div>
                        <div class="el-poids"></div>
                        <div class="el-poids"></div>
                        <div class="el-poids"></div>
                    </div>
                </div>
            </div>
        HTML;
        }
    }

function scoreBox($option, $value, $letter) {
    if ($value != null) {
        return <<<HTML
            <div class="border-scores border-$letter" onclick="changeScore('$option')" data-value=$value data-letter="$letter" id="score$option" hx-swap-oob="outerHTML">
                <div class="score-box">$letter</div>
            </div>
        HTML;
    } else {
        return <<<HTML
            <div class="border-scores border-NA" onclick="changeScore('$option')" data-value="NA" data-letter="NA" id="score$option" hx-swap-oob="outerHTML">
                <div class="score-box"><img src="assets/icons/bd.svg"></div>
            </div>
        HTML;
    }
}

function addSafety($cur, $id_pays, $id_html) {
    $query = "SELECT id_pays, safety, id_guerre FROM alldata, pays WHERE pays.id = id_pays AND annee = 2023 AND id_pays = :id_pays;";
    $id_pays = $_GET["id_pays"];
    $sth = $cur -> prepare($query);
    $sth -> bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth -> execute();
    $ligne = $sth->fetch();

    $more = "";
    if ($ligne == null) {
        $img = "safeempty";
        $text = "Il n'y a pas de données sur la sureté de ce pays.";
    } else {
        if ($ligne["id_guerre"] == 1) {
            $img = "danger";
            $text = "Ce pays est actuellement en guerre.<br>Faites attention si vous vous y rendez.
            <br>Obtenez plus d'informations auprès de <a class='lien' href='https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/'>France Diplomacie</a> pour voyager en sécurité.";
            $more = "danger";
        } else if ($ligne["id_guerre"] != 0) {
            $img = "danger";
            $text = "Ce pays est actuellement en conflit interne.<br>Faites attention si vous vous y rendez.
            <br>Obtenez plus d'informations auprès de <a class='lien' href='https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/'>France Diplomacie</a> pour voyager en sécurité.";
            $more = "warn";
        } else {
            if ($ligne["safety"] >= 3) {
                $img = "safedanger";
                $text = "Ce pays n'est pas sûr. Voyagez prudamment.";
                $more = "warn";
            } else if ($ligne["safety"] >= 2.38) {
                $img = "safebad";
                $text = "Ce pays est moyennement sûr.";
            } else if ($ligne["safety"] >= 2) {
                $img = "safegood";
                $text = "Ce pays est sûr.";
            } else if ($ligne["safety"] >= 1.4) {
                $img = "safesafe";
                $text = "Ce pays est très sûr !";
            } else {
                $img = "safebest";
                $text = "Ce pays fait partie des plus sûrs !";
            }
        }
    }

    return <<<HTML
        <div class="container-presentation" id="$id_html" hx-swap-oob="outerHTML">
            <div class="container-safe $more">
                <img src="assets/icons/$img.svg">
                <p>$text</p>
            </div>
        </div>
    HTML;
}
