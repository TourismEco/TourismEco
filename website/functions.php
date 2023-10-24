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
