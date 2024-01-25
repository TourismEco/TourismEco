<?php
session_start();

require('../functions.php');

try {
    // Vérifier le token CSRF
    if (!isset($_SESSION['csrf_token'], $_POST['csrf_token'])) {
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

    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['mdp'])) {
        $_SESSION['client'] = $user;
        echo json_encode(['success' => true, 'message' => 'Connexion réussie']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Identifiants incorrects']);
    }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Données du formulaire manquantes.']);
        exit();

    }

?>
