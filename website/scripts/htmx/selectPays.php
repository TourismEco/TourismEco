<?php

require('../../functions.php');

if (isset($_GET["id_pays"])) {
    $id_pays = $_GET["id_pays"];
    $nom = $_GET["nom"];
    $connexion = getDB();

    $stmt = $connexion->prepare("SELECT * FROM villes WHERE id_pays = ? ORDER BY population DESC");
    $stmt->execute(["$id_pays"]);

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    setListeVilles($options, $nom);
}

?>