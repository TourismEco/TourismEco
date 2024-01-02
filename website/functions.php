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
                    <button class=button-catalogue id=v-$id>Consulter</button>
                    <button class=button-catalogue id=c-$id>Comparer</button>
                </div>
            </div>
        </div>
    HTML;
}

function addCardContinent($id,$nom) {
    return <<<HTML
        <div class="container-mini bg-354F52">
            <div class="mini-bandeau"> 
                <img class="img-small" src='assets/img/$id.png' alt="Bandeau">
                <h2 class="nom-region">$nom</h2>
                <div class="catalogue-buttons">
                    <button class=button-catalogue id=v-$id>Consulter</button>
                </div>
            </div>
        </div>
    HTML;
}
