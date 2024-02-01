<?php

require('../../functions.php');

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sens = $_GET["sens"];
    $connexion = getDB();

    if ($search === 'All') {
        $stmt = $connexion->prepare("SELECT nom, id FROM pays ORDER BY nom");
    } else {
        $stmt = $connexion->prepare("SELECT nom, id FROM pays WHERE nom LIKE ?");
        $stmt->execute(["$search%"]);
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($options) == 1 && strtolower($options[0]["nom"]) == strtolower($search)) {
        $id = $options[0]["id"];
        $stmtV = $connexion->prepare("SELECT * FROM villes WHERE id_pays = ? ORDER BY population DESC");
        $stmtV->execute(["$id"]);
        $optionsV = $stmtV->fetchAll(PDO::FETCH_ASSOC);
        
        inputPays($options[0]["nom"], $sens);
        emptyOptions("country_options_$sens");
        inputVilles($id, "", $sens);
        iterOptions($optionsV, "city_options_$sens", $sens, "city");
    } else {
        inputVilles("", "", $sens);
        emptyOptions("city_options_$sens");
        iterOptions($options, "country_options_$sens", $sens, "country");
    }
}

?>