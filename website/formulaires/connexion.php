<?php
// Start the session
session_start();

// Set up a new CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_formulaire.css" type="text/css" media="screen" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1 class="titre">Connexion</h1>
    <img class="logo" src="img/profil.png" alt="LogoProfil" style="align-items:center; ">

    <div class="connexion-container">
        <form id="loginForm" onsubmit="return validateForm()">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" placeholder="Saisissez votre nom d'utilisateur" required autocomplete="off">
            <div id="errorUsername" class="error"></div>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" required autocomplete="off">
            <div id="errorPassword" class="error"></div>

            <!-- Ajouter un champ pour le token CSRF -->
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <input type="submit" value="Se connecter" style="background-color: #52796F; color: white; border: 1px solid #52796F; border-radius: 8px; font-size: 16px; width:150px; align-items:center;">
            <div id="errorMessages" class="error"></div>
        </form>

        <script>
            function validateForm() {
                // Ajouter des validations si nécessaire
                return true;
            }

            $(document).ready(function () {
                $("#loginForm").submit(function (e) {
                    e.preventDefault(); // Empêcher le formulaire de se soumettre normalement
                    var formData = $(this).serialize(); // Sérialiser les données du formulaire
                    // Envoyer la demande Ajax pour traiter la connexion
                    $.ajax({
                        type: "POST",
                        url: "connecter.php",
                        data: formData,
                        success: function (response) {
                            // Traiter la réponse du serveur ici
                            $("#errorMessages").html(response);
                        },
                        error: function() {
                            console.error("Erreur lors de la requête Ajax vers connecter.php.");
                        }
                    });
                });
            });
        </script>
    </div>
</body>
</html>
