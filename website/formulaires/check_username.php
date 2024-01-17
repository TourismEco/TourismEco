<?php
require('../functions.php');

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Connexion à la base de données
    $connexion = getDB();

    // Vérifier si le nom d'utilisateur existe déjà dans la base de données
    $stmtCheckUsername = $connexion->prepare("SELECT COUNT(*) FROM client WHERE nom = ?");
    $stmtCheckUsername->bindParam(1, $username);
    $stmtCheckUsername->execute();
    $result = $stmtCheckUsername->fetchColumn();

    // Si le nom est déjà utilisé:
    if ($count > 0) {
        return json_encode(['exists' => $result['count'] > 0]);
    }
}

?>