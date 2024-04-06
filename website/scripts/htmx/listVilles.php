<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["search"]) || !isset($_GET["sens"]) || !isset($_GET["id_pays"])) {
    header("HTTP/1.1 400");
    exit;
}

require('../../functions.php');

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sens = $_GET["sens"];
    $connexion = getDB();

    if (isset($_GET["id_pays"])) {
        $stmt = $connexion->prepare("SELECT nom, id, id_pays FROM villes WHERE nom LIKE ?  AND id_pays = ? ORDER BY population");
        $stmt->execute(["$search%",$_GET["id_pays"]]);
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($options) == 1 && strtolower($options[0]["nom"]) == strtolower($search)) {
        $id = $options[0]["id_pays"];
        $stmtV = $connexion->prepare("SELECT * FROM pays WHERE id = ?");
        $stmtV->execute(["$id"]);
        $optionsV = $stmtV->fetchAll(PDO::FETCH_ASSOC);
        
        inputVilles($id, $options[0]["nom"], $sens);
        emptyOptions("city_options_$sens");
    } else {
        iterOptions($options,"city_options_$sens", $sens, "city");
    }
}

?>