<?php
// Start the session
session_start();

// Set up a new CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require('../functions.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_formulaire.css" type="text/css" media="screen" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        .logoProfil {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <h1 class="titre">Connexion</h1>
    <img class="logo" src="img/profil.png" alt="LogoProfil">

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

            <input type="submit" value="Se connecter" style="background-color: #52796F; color: white; border: 1px solid #52796F; border-radius: 8px; font-size: 16px; width:150px; display: block; margin: 0 auto;">
            <div id="errorMessages" class="error"></div>
        </form>

        <script>
            function validateForm() {
                var username = $("#username").val();
                var password = $("#password").val();

                if (username === '' || password === '') {
                    $("#errorMessages").html("Veuillez remplir tous les champs.");
                    return false;
                }

                return true;
            }

            $(document).ready(function () {
                $("#loginForm").on('submit', function(e) {
                    e.preventDefault(); // Empêcher le formulaire de se soumettre normalement
                    var formData = $(this).serialize(); // Sérialiser les données du formulaire
                    // Envoyer la demande Ajax pour traiter la connexion
                    $.ajax({
                        method: 'POST',
                        url: "connecter.php",
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                            // Traiter la réponse du serveur ici
                            $("#errorMessages").html(response.message);
                            if (response.success) {
                                window.location.href = 'ok.php';
                            } else {
                                alert('Erreur de connexion : ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Erreur lors de la requête Ajax vers connecter.php:", error);
                            $("#errorMessages").html("Une erreur s'est produite lors de la connexion.");
                        }
                    });
                });
            });
        </script>
    </div>
</body>
</html>
