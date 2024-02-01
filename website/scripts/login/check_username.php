<?php
require('../../functions.php');

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Connexion à la base de données
    $connexion = getDB();

    // Vérifier si le nom d'utilisateur existe déjà dans la base de données
    $stmtCheckUsername = $connexion->prepare("SELECT * FROM client WHERE nom = ?");
    $stmtCheckUsername->bindParam(1, $username);
    $stmtCheckUsername->execute();
    $result = $stmtCheckUsername->fetch();

    // Si le nom est déjà utilisé:
    if ($result) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
}

?>