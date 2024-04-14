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
                <img class="img img-slim" src='assets/mini/$id.jpg' alt="Illustration de $nom">
                <img class="flag-slim" src='assets/twemoji/$id.svg' alt="Drapeau de $nom">
                <h2 class="nom-slim">$nom</h2>
            </div>
    HTML;
    } else if ($page == "comparateur") {
        return <<<HTML
            <div class="bandeau-slim" hx-get="scripts/htmx/appendCompare.php" hx-vals="js:{id_pays:'$id',incr:getIncr()}" hx-swap="beforeend"> 
                <!-- <div class="mini-score-box score-$letter">$letter</div> -->
                <img class="img img-slim" src='assets/mini/$id.jpg' alt="Illustration de $nom">
                <img class="flag-slim" src='assets/twemoji/$id.svg' alt="Drapeau de $nom">
                <h2 class="nom-slim">$nom</h2>
            </div>
        HTML;
    } else if ($page == "explorer") {
        return <<<HTML
            <div class="bandeau-slim" hx-get="scripts/htmx/getExplore.php" hx-vals="js:{id_pays:'$id'}" hx-swap="beforeend"> 
                <!-- <div class="mini-score-box score-$letter">$letter</div> -->
                <img class="img img-slim" src='assets/mini/$id.jpg' alt="Illustration de $nom">
                <img class="flag-slim" src='assets/twemoji/$id.svg' alt="Drapeau de $nom">
                <h2 class="nom-slim">$nom</h2>
            </div>
        HTML;
    }
    
}

function dataLine($pays, $conn) {
    $query = "SELECT allk.id_pays, allk.annee AS year, co2, elecRenew, pibParHab, idh, gpi, arriveesTotal*1000 AS arriveesTotal, departs*1000 AS departs
    FROM (SELECT id_pays, annee FROM economie UNION 
            SELECT id_pays, annee FROM tourisme UNION
            SELECT id_pays, annee FROM surete UNION
            SELECT id_pays, annee FROM idh UNION
            SELECT id_pays, annee FROM ecologie
            ) allk 
    LEFT OUTER JOIN economie ON allk.id_pays = economie.id_pays AND allk.annee = economie.annee 
    LEFT OUTER JOIN ecologie ON allk.id_pays = ecologie.id_pays AND allk.annee = ecologie.annee 
    LEFT OUTER JOIN surete ON allk.id_pays = surete.id_pays AND allk.annee = surete.annee 
    LEFT OUTER JOIN idh ON allk.id_pays = idh.id_pays AND allk.annee = idh.annee 
    LEFT OUTER JOIN tourisme ON allk.id_pays = tourisme.id_pays AND allk.annee = tourisme.annee
    WHERE allk.id_pays = '$pays'
    ORDER BY allk.annee;";

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

    $tables = array("economie","ecologie","ecologie","tourisme","tourisme","surete","idh");
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

            $query = "SELECT * FROM (SELECT id_pays, $cols[$key], RANK() OVER (ORDER BY $cols[$key] DESC) AS 'rank' FROM $tables[$key] WHERE annee =  $year) AS t WHERE id_pays = '$pays';";
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
    $query = "SELECT allk.id_pays, allk.annee AS annee, co2 AS 'Emissions de CO2', elecRenew AS '% énergies ren.', pibParHab AS 'PIB/Hab', idh AS 'IDH', gpi AS 'Global Peace Index', arriveesTotal AS 'Arrivées touristiques', departs AS 'Départs'
    FROM (SELECT id_pays, annee FROM economie_norm UNION 
            SELECT id_pays, annee FROM tourisme_norm UNION
            SELECT id_pays, annee FROM surete_norm UNION
            SELECT id_pays, annee FROM idh_norm UNION
            SELECT id_pays, annee FROM ecologie_norm
            ) allk 
    LEFT OUTER JOIN economie_norm ON allk.id_pays = economie_norm.id_pays AND allk.annee = economie_norm.annee 
    LEFT OUTER JOIN ecologie_norm ON allk.id_pays = ecologie_norm.id_pays AND allk.annee = ecologie_norm.annee 
    LEFT OUTER JOIN surete_norm ON allk.id_pays = surete_norm.id_pays AND allk.annee = surete_norm.annee 
    LEFT OUTER JOIN idh_norm ON allk.id_pays = idh_norm.id_pays AND allk.annee = idh_norm.annee 
    LEFT OUTER JOIN tourisme_norm ON allk.id_pays = tourisme_norm.id_pays AND allk.annee = tourisme_norm.annee
    WHERE allk.id_pays = '$pays'
    ORDER BY allk.annee;";

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
   
    $query = "SELECT allk.id_pays, allk.annee AS annee, co2, pibParHab, idh, gpi, arriveesTotal
    FROM (SELECT id_pays, annee FROM economie_grow UNION 
            SELECT id_pays, annee FROM tourisme_grow UNION
            SELECT id_pays, annee FROM surete_grow UNION
            SELECT id_pays, annee FROM idh_grow UNION
            SELECT id_pays, annee FROM ecologie_grow
            ) allk 
    LEFT OUTER JOIN economie_grow ON allk.id_pays = economie_grow.id_pays AND allk.annee = economie_grow.annee 
    LEFT OUTER JOIN ecologie_grow ON allk.id_pays = ecologie_grow.id_pays AND allk.annee = ecologie_grow.annee 
    LEFT OUTER JOIN surete_grow ON allk.id_pays = surete_grow.id_pays AND allk.annee = surete_grow.annee 
    LEFT OUTER JOIN idh_grow ON allk.id_pays = idh_grow.id_pays AND allk.annee = idh_grow.annee 
    LEFT OUTER JOIN tourisme_grow ON allk.id_pays = tourisme_grow.id_pays AND allk.annee = tourisme_grow.annee
    WHERE allk.id_pays = '$pays' AND allk.annee >= 1995 AND allk.annee <= 2021
    ORDER BY allk.annee;
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
    $query = "SELECT tourisme.annee as annee, pibParHab, arriveesTotal

    FROM economie, tourisme, pays
    WHERE economie.id_pays = tourisme.id_pays
    AND economie.id_pays = pays.id
    AND pays.id = '$pays'
   
    AND economie.annee = tourisme.annee

    ORDER BY `tourisme`.`annee` ASC;
    ";

    $result = $conn->query($query);

    $data = array();
    $minPib = array();
    $maxPib = array();
    $minTourisme = array();
    $maxTourisme = array();
    $covidImpactPib = 0;
    $covidImpactTourisme = 0;

    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[] = array(
            "year" => $rs['annee'],
            "value" => $rs['pibParHab'],
            "valueLeft" => $rs['arriveesTotal']
        );

        // Min et Max pour les indicateurs
        if (count($minPib) == 0 || $rs['pibParHab'] < $minPib['value']) {
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
        if ($rs['annee'] == 2020 && $rs['arriveesTotal'] != null) {
            $covidImpactPib = 100*($rs['pibParHab'] - $data[count($data)-1]['value']) / $data[count($data)-1]['value'];
            $covidImpactTourisme = 100*($rs['arriveesTotal'] - $data[count($data)-1]['valueLeft']) / $data[count($data)-1]['valueLeft'];
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


function dataTab($pays, $conn) {
    $query = "SELECT allk.id_pays, allk.annee AS annee, co2, elecRenew, pibParHab, gpi, idh,arriveesTotal*1000 AS 'arriveesTotal', departs*1000 AS 'departs'
    FROM (SELECT id_pays, annee FROM economie UNION 
            SELECT id_pays, annee FROM tourisme UNION
            SELECT id_pays, annee FROM surete UNION
            SELECT id_pays, annee FROM idh UNION
            SELECT id_pays, annee FROM ecologie
            ) allk 
    LEFT OUTER JOIN economie ON allk.id_pays = economie.id_pays AND allk.annee = economie.annee 
    LEFT OUTER JOIN ecologie ON allk.id_pays = ecologie.id_pays AND allk.annee = ecologie.annee 
    LEFT OUTER JOIN surete ON allk.id_pays = surete.id_pays AND allk.annee = surete.annee 
    LEFT OUTER JOIN idh ON allk.id_pays = idh.id_pays AND allk.annee = idh.annee 
    LEFT OUTER JOIN tourisme ON allk.id_pays = tourisme.id_pays AND allk.annee = tourisme.annee
    WHERE allk.id_pays = '$pays'
    ORDER BY allk.annee;";

    $result = $conn->query($query);

    $tables = array("economie","ecologie","ecologie","tourisme","tourisme","surete","idh");
    $cols = array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh");

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $year = $rs["annee"];
        $data[$year] = array();
        foreach (array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
                $rank = null;
            } else {
                $queryR = "SELECT * FROM (SELECT id_pays, $cols[$key], RANK() OVER (ORDER BY $cols[$key] DESC) AS 'rank' FROM $tables[$key] WHERE annee =  $year) AS t WHERE id_pays = '$pays';";
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
    $tables = array("economie","ecologie","ecologie","tourisme","tourisme","surete","idh");
    $cols = array("pibParHab","elecRenew","co2","arriveesTotal","departs","gpi","idh");
    $years = array("pibParHab"=>2021,"elecRenew"=>2020,"co2"=>2020,"arriveesTotal"=>2022,"departs"=>2022,"gpi"=>2023,"idh"=>2022);
    $data = array();

    $query = "SELECT id, score, nom, RANK() OVER (ORDER BY score DESC) AS 'scorerank' FROM pays;";
    $result = $conn->query($query);
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[$rs["id"]] = array("id"=>$rs["id"], "score"=>$rs["score"], "scorerank"=>$rs["scorerank"], "nom"=>$rs["nom"],"pibParHab"=>null,"elecRenew"=>null,"co2"=>null,"arriveesTotal"=>null,"departs"=>null,"gpi"=>null,"idh"=>null,"pibParHabrank"=>667,"elecRenewrank"=>667,"co2rank"=>667,"arriveesTotalrank"=>667,"departsrank"=>667,"gpirank"=>667,"idhrank"=>667);
    }

    foreach ($cols as $key => $value) {
        $query = "SELECT id_pays, $value, RANK() OVER (ORDER BY $value DESC) AS 'rank' FROM $tables[$key] WHERE annee = $years[$value];";
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
            <p class="hHome">TourismEco</p>
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
                    <img class="slide-logo" src="assets/twemoji/$image[id].svg" alt="Drapeau de $image[nom]">
                    <p>$image[nom]</p>
                </div>
            </a>
            
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
    $query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
    $sth = $cur->prepare($query);
    $is_capitale = 1;
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->bindParam(":is_capitale", $is_capitale, PDO::PARAM_INT);
    $sth->execute();
    $ligne = $sth->fetch();

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
    if ($hx[2] == "localhost" || $hx[2] == "tourismeco.fr") {
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