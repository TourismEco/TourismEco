<?php
require('../functions.php');

try {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $country = $_POST['country'];
    $city = $_POST['cityInput']; // Utilisez le bon nom de la clé pour la ville

    // Vérifier que le mot de passe contient au moins une lettre, un chiffre et un caractère spécial
    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $password)) {
        echo "Le mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial.";
        exit;
    }

    // Vérifier que la ville et le pays ne contiennent que des lettres majuscules, minuscules ou des espaces
    if (!ctype_alpha(str_replace(' ', '', $country)) || !ctype_alpha(str_replace(' ', '', $city))) {
        echo "Le pays et la ville ne doivent contenir que des lettres et des espaces.";
        exit;
    }

    // Vérifier que les mots de passe correspondent
    if ($password !== $confirmPassword) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Obtenir une connexion à la base de données
    $connexion = getDB();

    // Hasher le mot de passe
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    // Préparer et exécuter la requête SQL pour insérer les données dans la base de données
    $stmt = $connexion->prepare("INSERT INTO client (nom, mdp, pays, ville) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $passwordHashed);
    $stmt->bindParam(3, $country);
    $stmt->bindParam(4, $city);

    if ($stmt->execute()) {
        echo "Inscription réussie !";
    } else {
        echo "Erreur lors de l'inscription : " . $stmt->errorInfo()[2];
    }
} catch (Exception $e) {
    echo "Une erreur inattendue s'est produite : " . $e->getMessage();
}
?>
