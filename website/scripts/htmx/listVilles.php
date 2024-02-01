<?php

require('../../functions.php');

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $connexion = getDB();

    if ($search === 'All') {
        $stmt = $connexion->prepare("SELECT nom, id, id_pays FROM villes ORDER BY population");
    } else {
        $stmt = $connexion->prepare("SELECT nom, id, id_pays FROM villes WHERE nom LIKE ? ORDER BY population");
        $stmt->execute(["$search%"]);
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($options) == 1 && strtolower($options[0]["nom"]) == strtolower($search)) {
        $id = $options[0]["id_pays"];
        $stmtV = $connexion->prepare("SELECT * FROM pays WHERE id = ?");
        $stmtV->execute(["$id"]);
        $optionsV = $stmtV->fetchAll(PDO::FETCH_ASSOC);
        setListeVilles([], $optionsV[0]["nom"]);
    } else {
        setListeVilles($options, "");
    }
}

?>