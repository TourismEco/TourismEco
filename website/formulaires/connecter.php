<?php
session_start();
require('../functions.php');

try {
    // Vérifier le token CSRF
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Token CSRF invalide.";
        exit;
    }

    // Récupérer les données du formulaire
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Obtenir une connexion à la base de données
    $connexion = getDB();

    // Vérifier l'existence de l'utilisateur
    $stmt = $connexion->prepare("SELECT * FROM client WHERE nom = ?");
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Nom d'utilisateur incorrect.";
        exit;
    }

    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['mdp'])) {
        $_SESSION['client'] = $user;
        header("Location: ../profil/profil.php");
        exit;
    } else {
        echo "Mot de passe incorrect.";
    }
} catch (Exception $e) {
    echo "Une erreur inattendue s'est produite : " . $e->getMessage();
}
?>
