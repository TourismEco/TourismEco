<?php

require('../../functions.php');

if (isset($_GET["id"])) {
    $id_pays = $_GET["id"];
    $nom = $_GET["nom"];
    $sens = $_GET["sens"];
    $connexion = getDB();

    $stmt = $connexion->prepare("SELECT * FROM villes WHERE id_pays = ? ORDER BY population DESC");
    $stmt->execute(["$id_pays"]);

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    inputPays($nom, $sens);
    inputVilles($id_pays, "", $sens);
    iterOptions($options, "city_options_$sens", $sens, "city");
    emptyOptions("country_options_$sens");
}

?>