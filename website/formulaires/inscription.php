<?php
    // Inscription.php
    require('../functions.php');

    // Générer un nouveau token CSRF si la variable de session n'existe pas
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
    <title>Inscription</title>

</head>


    <body>
        <div class="content-container">

        <div class="left-section">
                <h1 class="titre">Inscription</h1>
                <form action="ajouter.php" method="post" onsubmit="return validateForm()">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Saisissez votre nom d'utilisateur" required autocomplete="off">
                    <div id="errorUsername" class="error"></div>
                    <span class="validation-message"></span>


                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" required autocomplete="off">
                    <div id="errorPassword" class="error"></div>
                    <span class="validation-message"></span>


                    <label for="confirmPassword">Confirmer le mot de passe</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Saisissez à nouveau votre mot de passe" required autocomplete="off">
                    <div id="errorConfirmPassword" class="error"></div>
                    <span class="validation-message"></span>


                    <label for="country">Votre pays</label>
                    <input type="text" id="country" name="country" placeholder="Saisissez votre pays actuel" required autocomplete="off">
                    <div id="countryOptions" class="option-container"></div>
                    <div id="errorCountry" class="error"></div>
                    <span class="validation-message"></span>

                    <label for="city">Votre ville</label>
                    <input type="text" id="cityInput" name="cityInput" placeholder="Saisissez votre ville actuelle" required autocomplete="off">
                    <div id="cityOptions" class="option-container"></div>
                    <div id="errorCity" class="error"></div>
                    <!-- Ajoutez un champ caché pour stocker la ville sélectionnée -->
                    <input type="hidden" id="selectedCity" name="selectedCity">
                    <span class="validation-message"></span>


                    <input type="submit" value="S'inscrire" class="submit">
                    <div id="errorMessages" class="error"></div>

                </form>
            </div>

            <script>
                $(document).ready(function () {

                    // Fonction pour afficher une alerte et mettre en surbrillance le champ en erreur
                    function showErrorAlert(message, field) {
                        alert(message);
                        field.addClass('error-field');
                    }

                    // Fonction pour valider le nom et prénom (lettres, accents et chiffres)
                    function isValidName(name, field) {
                        var nameRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ0-9\s']+$/;
                        var validName = nameRegex.test(name);
                        if (validName) {
                            $.ajax({
                                url: 'http://localhost/projetL3/website/formulaires/check_username.php',
                                method: 'POST',
                                data: { username: name },
                                dataType: 'json',
                                success: function (data) {
                                    var exists = data.exists;
                                    var msg = exists ? 'Nom d\'utilisateur déjà utilisé' : '';
                                    updateFieldStatus(field, !exists, msg);
                                },
                                error: function () {
                                    console.error("Erreur lors de la vérification de l'unicité du nom d'utilisateur");
                                    reject();
                                }
                            });
                        } else {
                            // Afficher une alerte et mettre en surbrillance le champ en erreur
                            showErrorAlert("Nom d'utilisateur non valide", field);
                        }
                    }

                    // Fonction pour mettre à jour le statut du champ (valide/invalide)
                    function updateFieldStatus(field, isValid, errorMessage) {
                        if (isValid) {
                            field.removeClass('error-field');
                        } else {
                            showErrorAlert(errorMessage, field);
                        }
                    }

                    // Fonction pour valider le formulaire
                    function validateForm() {
                        var usernameField = $('input[name="username"]');
                        var passwordField = $('input[name="password"]');
                        var confirmPasswordField = $('input[name="confirmPassword"]');
                        var countryField = $('input[name="country"]');
                        var cityInputField = $('input[name="cityInput"]');

                        // Validation du nom d'utilisateur
                        var username = usernameField.val();
                        if (username.trim() === "") {
                            showErrorAlert("Le nom d'utilisateur ne peut pas être vide.", usernameField);
                        } else {
                            isValidName(username, usernameField);
                        }

                        // Validation du mot de passe
                        var password = passwordField.val();
                        if (password.trim() === "") {
                            // Si le champ du mot de passe est vide, réinitialiser le champ de confirmation du mot de passe
                            confirmPasswordField.val("");
                        } else {
                            // Validation du mot de passe non vide
                            if (!/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/.test(password)) {
                                showErrorAlert("Le mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial.", passwordField);
                            } else {
                                passwordField.removeClass('error-field');
                            }
                        }

                        // Validation de la confirmation du mot de passe
                        var confirmPassword = confirmPasswordField.val();
                        if (confirmPassword !== password) {
                            showErrorAlert("Les mots de passe ne correspondent pas.", confirmPasswordField);
                        } else {
                            confirmPasswordField.removeClass('error-field');
                        }

                        // Validation du pays
                        var country = countryField.val();
                        if (country.trim() === "") {
                            showErrorAlert("Veuillez sélectionner ou saisir un pays.", countryField);
                        } else {
                            countryField.removeClass('error-field');
                        }

                        // Validation de la ville
                        var cityInput = cityInputField.val();
                        var selectedCity = $("#selectedCity").val();
                        if (cityInput.trim() === "" && selectedCity === "") {
                            showErrorAlert("Veuillez sélectionner ou saisir une ville.", cityInputField);
                        } else {
                            cityInputField.removeClass('error-field');
                        }

                        // Si au moins un champ a une erreur, empêcher la soumission du formulaire
                        if ($('.error-field').length > 0) {
                            alert("Veuillez corriger les erreurs avant de soumettre le formulaire.");
                            return false;
                        }

                        return true;
                    }

                    // AJAX pour charger dynamiquement les options des pays
                    $("#country").on("input", function () {
                        var inputText = $(this).val();
                        $.ajax({
                            type: "POST",
                            url: "get_countries.php",
                            data: { inputText: inputText },
                            success: function (response) {
                                $("#countryOptions").html(response);
                            }
                        });
                    });

                    // Gestionnaire de clic sur les options de la liste des pays
                    $("#countryOptions").on("click", "option", function () {
                        var selectedCountry = $(this).text();
                        $("#country").val(selectedCountry);
                        $("#countryOptions").html("");
                        // Charger les options de la ville en fonction du pays sélectionné
                        $.ajax({
                            type: "POST",
                            url: "get_cities.php",
                            data: { selectedCountry: selectedCountry },
                            success: function (response) {
                                $("#cityOptions").html(response);
                                $("#citySelect").show();
                                $("#city").hide();
                            }
                        });
                    });

                    // Gestionnaire d'entrée pour le champ d'entrée du pays
                    $("#country").on("input", function () {
                        $.ajax({
                            type: "POST",
                            url: "get_countries.php",
                            data: { inputText: $(this).val() },
                            success: function (response) {
                                $("#countryOptions").html(response);
                            }
                        });
                    });

                    ///Ajoute ville en liste dynamique
                    $("#cityInput").on("input", function () {
                        var selectedCountry = $("#country").val();
                        var selectedCity = $(this).val();
                        // Charger les options de la ville en fonction du pays sélectionné et de l'input actuelle
                        $.ajax({
                            type: "POST",
                            url: "get_cities.php",
                            data: { selectedCountry: selectedCountry, inputText: selectedCity },
                            success: function (response) {
                                $("#cityOptions").html(response);
                                $("#citySelect").show();
                                $("#city").hide();
                            },
                            error: function (xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });

                        // Gestionnaire de clic sur les options de la liste des villes
                    $("#cityOptions").on("click", "option", function () {
                        var selectedCity = $(this).text();
                        $("#cityInput").val(selectedCity);
                        $("#selectedCity").val(selectedCity); // Stockez la ville sélectionnée dans le champ caché
                        $("#cityOptions").html(""); // Masquer les options après la sélection
                        
                    });

                    // Gestionnaire d'entrée pour le champ d'entrée de la ville
                    $("#cityInput").on("input", function () {
                        // Ne faites rien ici, empêchez l'utilisateur de saisir des lettres
                        $("#cityOptions").html(""); // Masquer les options
                    });

                    // Gestionnaire d'événements dblclick pour le champ d'entrée du pays
                    $("#country").on("dblclick", function () {
                        $("#countryOptions").html("");
                    });

                    // Gestionnaire d'événements dblclick pour le champ d'entrée de la ville
                    $("#cityInput").on("dblclick", function () {
                        $("#cityOptions").html("");
                    });

                    // Restaurer les valeurs des champs après le chargement de la page
                    if (sessionStorage.getItem('selectedCountry')) {
                        $('#country').val(sessionStorage.getItem('selectedCountry'));
                        $.ajax({
                            type: "POST",
                            url: "get_cities.php",
                            data: { selectedCountry: $('#country').val() },
                            success: function (response) {
                                $("#cityOptions").html(response);
                                $("#citySelect").show();
                                $("#city").hide();
                            }
                        });
                    }

                    if (sessionStorage.getItem('selectedCity')) {
                        $('#cityInput').val(sessionStorage.getItem('selectedCity'));
                    }

                    // Gestionnaire de soumission du formulaire
                    $('form').submit(function () {
                        // Stocker les valeurs des champs dans sessionStorage avant la soumission du formulaire
                        sessionStorage.setItem('selectedCountry', $('#country').val());
                        sessionStorage.setItem('selectedCity', $('#cityInput').val());
                        return validateForm();
                    });
                });
            </script>

            <div class="big-trait"></div>

            <div class="right-section">
            <img class="logo" src="img/avion.png" alt="LogoAvion">
            <h2>Pourquoi s’inscrire à EcoTourisme ?</h2>
                <p>
                    Avoir un compte EcoTourisme vous permet d’étendre votre expérience avec des fonctionnalités personnalisées :
                </p>
                <ul>
                    <li>Calculez le coût écologique de vos trajets depuis votre lieu de résidence plus rapidement</li>
                    <li>Consultez l’historique de vos recherches</li>
                    <li>Désignez des pays favoris, et comparez-les entre eux</li>
                    <li>Téléchargez les données et les graphiques</li>
                </ul>
            </div>
        </div>
    </body>
</html>

