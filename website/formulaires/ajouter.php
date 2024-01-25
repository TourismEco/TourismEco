<?php

require('../functions.php');

// Récupérer les données du formulaire
if (
    isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['confirmPassword']) &&
    isset($_POST['country']) &&
    isset($_POST['cityInput'])
) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $country = htmlspecialchars($_POST['country']);
    $city = htmlspecialchars($_POST['cityInput']);

    if (empty($username) || empty($password) || empty($country) || empty($city) || empty($confirmPassword) || $password != $confirmPassword) {
        // Retourne une réponse JSON indiquant une erreur de validation
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir correctement tous les champs.']);
        exit;
    }

    // Enregistre les données dans la base de données
    $response = ajouter($username, $password, $country, $city);

    // Retourne la réponse JSON obtenue de la fonction d'enregistrement
    echo $response;
} else {
    // Retourne une réponse JSON indiquant que le formulaire n'est pas complet
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir le formulaire.']);
}

function ajouter($username, $password, $country, $city)
{
    try {
        $connexion = getDB();

        // Vérifier l'unicité du nom d'utilisateur
        $stmtCheckUsername = $connexion->prepare("SELECT COUNT(*) FROM client WHERE nom = ?");
        $stmtCheckUsername->bindParam(1, $username);
        $stmtCheckUsername->execute();
        $count = $stmtCheckUsername->fetchColumn();

        // Si le nom est déjà utilisé:
        if ($count > 0) {
            return json_encode(['success' => false, 'message' => "Le nom d'utilisateur existe déjà."]);
        }

        //// Continuer le processus d'enregistrement puisque le nom d'utilisateur n'existe pas encore
        // Vérifier que la ville appartient au pays
        $stmtCheckCityCountry = $connexion->prepare("SELECT COUNT(*) FROM villes v
        JOIN pays p ON v.id_pays = p.id
        WHERE v.nom = ? AND p.nom = ?");
        $stmtCheckCityCountry->bindParam(1, $city);
        $stmtCheckCityCountry->bindParam(2, $country);
        $stmtCheckCityCountry->execute();
        $cityCountryCount = $stmtCheckCityCountry->fetchColumn();

        if ($cityCountryCount == 0) {
            return json_encode(['success' => false, 'message' => "La ville sélectionnée n'appartient pas au pays renseigné."]);
        }

        // Hasher le mot de passe
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        // Préparer et exécuter la requête SQL pour insérer les données dans la base de données
        $stmt = $connexion->prepare("INSERT INTO client (nom, mdp, pays, ville) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $passwordHashed);
        $stmt->bindParam(3, $country);
        $stmt->bindParam(4, $city);

        if ($stmt->execute()) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['username'] = $username;
            $_SESSION['country'] = $country;
            $_SESSION['city'] = $city;

            return json_encode(['success' => true, 'message' => 'Inscription réussie !']);
        } else {
            return json_encode(['success' => false, 'message' => 'Erreur lors de l\'inscription : ' . $stmt->errorInfo()[2]]);
        }
    } catch (Exception $e) {
        return json_encode(['success' => false, 'message' => 'Une erreur inattendue s\'est produite : ' . $e->getMessage()]);
    }
}
?>
