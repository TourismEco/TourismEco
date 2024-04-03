<?php

require('../../functions.php');

try {
    if (!isset($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo "Token CSRF invalide.";
        exit;
    }

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $connexion = getDB();

    $stmt = $connexion->prepare("SELECT * FROM client WHERE nom = ?");
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mdp'])) {
        
        $_SESSION['user']['username'] = $user['nom'];
        $_SESSION['user']['country'] = $user['pays'];
        $_SESSION['user']['city'] = $user['ville'];
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
