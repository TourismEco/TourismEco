<?php

// Connect to the database
function getDB($hostname="localhost", $username="root", $password="root", $database="ecotourisme") {
    try {
        $conn =  new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_TIMEOUT, 1800);
        return $conn;
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
}

function getArrivals(string $id_pays, PDO $conn): int {
    $query = <<<SQL
    SELECT arriveesTotal
    FROM Tourisme
    WHERE id_pays = :id_pays AND annee = 2021
SQL;
    $sth = $conn->prepare($query);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->execute();
    $row = $sth->fetch();
    var_dump($row);
    return $row["arriveesTotal"];
}

function getCO2(string $id_pays, PDO $conn): float {
    $query = <<<SQL
    SELECT co2
    FROM Ecologie
    WHERE id_pays = :id_pays AND annee = 2020
SQL;
    $sth = $conn->prepare($query);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->execute();
    $row = $sth->fetch();
    var_dump($row);
    return $row["co2"];
}

function getPIB(string $id_pays, PDO $conn): float {
    $query = <<<SQL
    SELECT pibParHab
    FROM Economie
    WHERE id_pays = :id_pays AND annee = 2021 /* Normalement 2022 mais c'est NULL pour la france il faudra que je fasse une fonction qui check*/
SQL;
    $sth = $conn->prepare($query);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->execute();
    $row = $sth->fetch();
    var_dump($row);
    return $row["pibParHab"];
}

function getGPI(string $id_pays, PDO $conn): float {
    $query = <<<SQL
    SELECT gpi
    FROM Surete
    WHERE id_pays = :id_pays AND annee = 2023
SQL;
    $sth = $conn->prepare($query);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->execute();
    $row = $sth->fetch();
    return $row["gpi"];
}
function getStats(string $id_pays, PDO $conn): array {
    return array(
        "arrivees" => getArrivals($id_pays, $conn),
        "co2" => getCO2($id_pays, $conn),
        "pib" => getPIB($id_pays, $conn),
        "gpi" => getGPI($id_pays, $conn));
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

function addCardCountry($id,$nom,$letter) {
    return <<<HTML
        <div class="container-mini bg-354F52">
            <div class="mini-bandeau"> 
                <div class="mini-score-box score-$letter">$letter</div>
                <img class="img-small" src='assets/img/$id.jpg' alt="Bandeau">
                <img class="flag-small" src='assets/twemoji/$id.svg'>
                <h2 class="nom-small">$nom</h2>
                <div class="catalogue-buttons">
                    <button class=button-catalogue id=v-$id>Visiter</button>
                </div>
            </div>
        </div>
    HTML;
}

function addSlimCountry($id,$nom,$letter) {
    return <<<HTML
        <div class="container-slim bg-354F52">
            <div class="bandeau-slim"> 
                <!-- <div class="mini-score-box score-$letter">$letter</div> -->
                <img class="img-slim" src='assets/img/$id.jpg' alt="Bandeau">
                <img class="flag-slim" src='assets/twemoji/$id.svg'>
                <h2 class="nom-slim">$nom</h2>
                <div class="buttons-slim">
                    <button class=button-catalogue id=v-$id>Visiter</button>
                </div>
            </div>
        </div>
    HTML;
}

function addCardContinent($id,$nom) {
    return <<<HTML
        <div class="container-slim bg-52796F">
            <div class="bandeau-slim"> 
                <img class="img-slim" src='assets/img/$id.png' alt="Bandeau">
                <h2 class="nom-region">$nom</h2>
                <div class="catalogue-buttons">
                    <button class=button-catalogue id=v-$id>Consulter</button>
                </div>
            </div>
        </div>
    HTML;
}

function dataLine($pays, $conn) {
    $query = "SELECT allk.id_pays, allk.annee AS year, co2, elecRenew AS Enr, pibParHab AS pib, cpi, gpi, arriveesTotal*1000 AS arrivees, departs*1000 AS departs
    FROM (SELECT id_pays, annee FROM economie UNION 
            SELECT id_pays, annee FROM tourisme UNION
            SELECT id_pays, annee FROM surete UNION
            SELECT id_pays, annee FROM ecologie
            ) allk 
    LEFT OUTER JOIN economie ON allk.id_pays = economie.id_pays AND allk.annee = economie.annee 
    LEFT OUTER JOIN ecologie ON allk.id_pays = ecologie.id_pays AND allk.annee = ecologie.annee 
    LEFT OUTER JOIN surete ON allk.id_pays = surete.id_pays AND allk.annee = surete.annee 
    LEFT OUTER JOIN tourisme ON allk.id_pays = tourisme.id_pays AND allk.annee = tourisme.annee
    WHERE allk.id_pays = '$pays'
    ORDER BY allk.annee;";

    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        foreach (array("pib","Enr","co2","arrivees","departs","gpi","cpi") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            } 
        }

        $data[] = $rs;
    }

    return $data;
}

function dataSpider($pays, $conn) {
    $query = "SELECT ecologie.annee as annee,
    pibParHab AS pib, elecRenew AS Enr, co2, arriveesTotal AS arrivees, departs, gpi, cpi

    FROM ecologie_norm AS ecologie, economie_norm AS economie, tourisme_norm AS tourisme, surete_norm AS surete
    WHERE ecologie.id_pays = economie.id_pays
    AND economie.id_pays = tourisme.id_pays
    AND tourisme.id_pays = surete.id_pays
    AND surete.id_pays = '$pays'

    AND ecologie.annee = economie.annee
    AND economie.annee = tourisme.annee
    AND tourisme.annee = surete.annee  
    ORDER BY `ecologie`.`annee` DESC;
    ";

    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[$rs["annee"]] = array();
        foreach (array("pib","Enr","co2","arrivees","departs","gpi","cpi") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            } 
            $data[$rs["annee"]][] = array("var" => $value, "value" => $rs[$value]);
        }
    }

    return $data;
}

function dataBar($pays, $conn) {
    $query = "SELECT ecologie.annee as annee,
    pibParHab AS pib, co2, arriveesTotal AS arrivees, gpi, cpi

    FROM ecologie_grow AS ecologie, economie_grow AS economie, tourisme_grow AS tourisme, surete_grow AS surete
    WHERE ecologie.id_pays = economie.id_pays
    AND economie.id_pays = tourisme.id_pays
    AND tourisme.id_pays = surete.id_pays
    AND surete.id_pays = '$pays'

    AND ecologie.annee = economie.annee
    AND economie.annee = tourisme.annee
    AND tourisme.annee = surete.annee  
    ORDER BY `ecologie`.`annee` DESC;
    ";


    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[$rs["annee"]] = array();
        foreach (array("pib","co2","arrivees","gpi","cpi") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            } 
            $data[$rs["annee"]][] = array("var" => $value, "value" => $rs[$value]);
        }
    }

    return $data;
}

function dataTab($pays, $conn) {
    $query = "SELECT ecologie.annee as annee,
    pibParHab AS pib, elecRenew AS Enr, co2, arriveesTotal AS arrivees, departs, gpi, cpi

    FROM ecologie, economie, tourisme, surete 
    WHERE ecologie.id_pays = economie.id_pays
    AND economie.id_pays = tourisme.id_pays
    AND tourisme.id_pays = surete.id_pays
    AND surete.id_pays = '$pays'

    AND ecologie.annee = economie.annee
    AND economie.annee = tourisme.annee
    AND tourisme.annee = surete.annee  
    ORDER BY `ecologie`.`annee` DESC;
    ";

    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[$rs["annee"]] = array();
        foreach (array("pib","Enr","co2","arrivees","departs","gpi","cpi") as $key => $value) {
            if (!isset($rs[$value])){
                $rs[$value]=null;
            } 
            $data[$rs["annee"]][] = array("var" => $value, "value" => $rs[$value]);
        }
    }

    return $data;
}