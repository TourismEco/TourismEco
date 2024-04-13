<?php

require('../../functions.php');

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmPassword']) && isset($_POST['country_register']) && isset($_POST['city_register'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $country = htmlspecialchars($_POST['country_register']);
    $city = htmlspecialchars($_POST['city_register']);

    if (empty($username) || empty($password) || empty($country) || empty($city) || empty($confirmPassword)) {
        echo <<<HTML
            <div id="error" class="form-warning" hx-swap-oob="outerHTML">Vous n'avez pas tout rempli !</div>
        HTML;
    } else if ($password != $confirmPassword) {
        echo <<<HTML
            <div id="error" class="form-warning" hx-swap-oob="outerHTML">Les mots de passes ne sont pas identiques.</div>
        HTML;
    } else {
        $connexion = getDB();

        // Vérifier l'unicité du nom d'utilisateur
        $stmtCheckUsername = $connexion->prepare("SELECT COUNT(*) FROM client WHERE nom = ?");
        $stmtCheckUsername->bindParam(1, $username);
        $stmtCheckUsername->execute();
        $count = $stmtCheckUsername->fetchColumn();

        // Si le nom est déjà utilisé:
        if ($count > 0) {
            echo <<<HTML
                <div id="error" class="form-warning" hx-swap-oob="outerHTML">Ce nom d'utilisateur existe déjà !</div>
            HTML;
            exit;
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
            echo <<<HTML
                <div id="error" class="form-warning" hx-swap-oob="outerHTML">Il y a une incohérence dans la ville entrée. Elle semble ne pas appartenir au pays choisi...</div>
            HTML;
            exit;
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
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['country'] = $country;
            $_SESSION['user']['city'] = $city;


            echo <<<HTML
                <div id="htmxing" hx-swap-oob="true">
                    <div hx-get="profil.php" hx-target="#zones" hx-select="#zones" hx-swap="outerHTML swap:0.5s show:window:top" hx-push-url="true" hx-trigger="load"></div>
                </div>
                <a href="profil.php" aria-label="Profil" id="n1" hx-swap-oob="outerHTML">Profil</a>
                <a href="deconnexion.php" aria-label="Déconnexion" id="n2" hx-swap-oob="outerHTML">Déconnexion</a>
            HTML;
        } else {
            echo <<<HTML
                <div id="error" class="form-warning" hx-swap-oob="outerHTML">Une erreur innatendue s'est produite.</div>
            HTML;
        }
    }
} else {
    // Retourne une réponse JSON indiquant que le formulaire n'est pas complet
    echo <<<HTML
        <div id="error" class="form-warning" hx-swap-oob="outerHTML">Une erreur inconnue a eu lieu.</div>
    HTML;
}
