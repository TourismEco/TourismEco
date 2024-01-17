<?php
    // Inscription.php
    session_start();

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
        <h1 class="inscription"> Inscription</h1>
        <div class="content-container">

            <form action="ajouter.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" placeholder="Saisissez votre nom d'utilisateur" required autocomplete="off">
                <div id="errorUsername" class="error"></div>
                <span class="validation-message"></span>


                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" required autocomplete="off">
                <div id="errorPassword" class="error"></div>
                <span class="validation-message"></span>


                <label for="confirmPassword">Confirmer le mot de passe :</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Saisissez à nouveau votre mot de passe" required autocomplete="off">
                <div id="errorConfirmPassword" class="error"></div>
                <span class="validation-message"></span>


                <label for="country">Pays actuel :</label>
                <input type="text" id="country" name="country" placeholder="Saisissez votre pays actuel" required autocomplete="off">
                <div id="countryOptions" class="country-options"></div>
                <div id="errorCountry" class="error"></div>
                <span class="validation-message"></span>


                <label for="city">Ville actuelle :</label>
                <input type="text" id="cityInput" name="cityInput" placeholder="Saisissez votre ville actuelle" required autocomplete="off">
                <div id="cityOptions" class="options-container"></div>
                <div id="errorCity" class="error"></div>
                <!-- Ajoutez un champ caché pour stocker la ville sélectionnée -->
                <input type="hidden" id="selectedCity" name="selectedCity">
                <span class="validation-message"></span>


                <input type="submit" value="S'inscrire" style="background-color: #52796F; color: white; border: 1px solid #52796F; border-radius: 8px; font-size: 16px; width:150px; margin-left:20%">
                <div id="errorMessages" class="error"></div>

            </form>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
                $(document).ready(function () {

                    // Fonction pour mettre à jour le style du champ en fonction de sa validité
                    function updateFieldStatus(field, isValid, message) {
                        var $field = $(field);
                        if (isValid) {
                            $field.css({
                                'border': '2px solid green',
                                'background-color': '#c8e6c9' // Fond vert clair
                            });
                            $field.next('.error-message').text('');
                        } else {
                            $field.css({
                                'border': '2px solid red',
                                'background-color': '#ffcdd2' // Fond rouge clair
                            });
                            $field.next('.error-message').text(message);
                        }
                    }

                    // Fonction pour valider le nom et prénom (lettres, accents et chiffres)
                    function isValidName(name) {
                        var nameRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ0-9\s']+$/;
                        var validName = nameRegex.test(name);
                        if (validName){
                            $.ajax({
                                url:'http://http://localhost/projetL3/website/formulaires/check_username.php',
                                method : 'POST',
                                data: {username : username},
                                dataType: 'json',
                                success:function(data){
                                    var exists = data.exists;
                                    var msg = exists ? 'Nom d\' utilisateur déjà utilisé':'';
                                    updateFieldStatus($('input[name="username"]'), !exists, msg)
                                },
                                error:function(){
                                    console.error("Erreur lors de la vérification de l'unicité du non d'utilisateur");
                                    reject();
                                }
                            });
                        }
                        else{
                            updateFieldStatus($('input[name="username"]'),false,"Nom d'utilisateur non valide")
                        }
                    }


                    function isValidPassword(password) {
                        var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                        return passwordRegex.test(password);
                    }

                    function isValidCountry(country){
                        var countryRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ\s']+$/ ;
                        return countryRegex.test(country);
                    }

                    function isValidCity(city){
                        var cityRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ\s']+$/ ;
                        var cityInput = $("#city");
                        var selectedCity = cityInput.val();
                        var selectedCitySelect = $("#citySelect").val();

                        if (selectedCity === "" && selectedCitySelect === null) {
                            $("#errorCity").html("Veuillez sélectionner ou saisir une ville.");
                            return false;
                        } else {
                            $("#errorCity").html("");
                        }
                        return cityRegex.test(city);
                    }

                    // Fonction pour vérifier tous les champs avant de soumettre le formulaire
                    function validateForm2() {
                        var isValid = true;

                        function validateField(field, validationFunction, errorMessage) {
                            var $field = $(field);
                            var value = $field.val();
                            var fieldIsValid = validationFunction(value);

                            if (fieldIsValid) {
                                updateFieldStatus($field, true, '');
                            } else {
                                updateFieldStatus($field, false, errorMessage);
                                isValid = false;
                            }

                            return fieldIsValid;
                        }

                        var nomIsValid = validateField('input[name="username"]', isValidName, 'Le nom d\'utilisateur doit contenir que des lettres ou des chiffres, ou existe déjà');
                        var passwordIsValid = validateField('input[name="password"]', isValidPassword, 'Le mot de passe doit contenir au moins 8 caractères, une lettre, un chiffre et un caractère spécial.');
                        var confirmPasswordIsValid = validateField('input[name="confirmPassword"]', function(value) {
                            // Ajoutez la logique de validation supplémentaire pour le champ de confirmation du mot de passe
                            return value === $('input[name="password"]').val();
                        }, 'Les mots de passe ne correspondent pas.');
                        var countryIsValid = validateField('input[name="country"]', isValidCountry, 'Le pays n\'est pas valide, ou n\'existe pas.');
                        var cityIsValid = validateField('input[name="city"]', isValidCity, 'La ville n\'est pas valide, ou n\'existe pas.');
                        return isValid;
                    }

                    $('input[name="username"], input[name="password"], input[name="confirmPassword"], input[name="country"], input[name="city"]').on('input', function() {
                        validateForm();
                    });



                    // Fonction de validation du formulaire
                    function validateForm() {
                        var username = $("#username").val();
                        var password = $("#password").val();
                        var confirmPassword = $("#confirmPassword").val();
                        var countryInput = $("#country");

                        // Vérifier que le nom d'utilisateur n'est pas vide
                        if (username.trim() === "") {
                            $("#errorUsername").html("Le nom d'utilisateur ne peut pas être vide.");
                            return false;
                        } else {
                            $("#errorUsername").html("");
                        }

                        // Vérifier que le mot de passe contient au moins une lettre, un chiffre et un caractère spécial
                        if (!/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/.test(password)) {
                            $("#errorPassword").html("Le mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial.");
                            return false;
                        } else {
                            $("#errorPassword").html("");
                        }

                        // Vérifier que le pays est sélectionné
                        if (countryInput.val() === "") {
                            $("#errorCountry").html("Veuillez sélectionner ou saisir un pays.");
                            return false;
                        } else {
                            $("#errorCountry").html("");
                        }

                        // Vérifier que la ville est sélectionnée ou renseignée
                        var cityInput = $("#city");
                        var selectedCity = cityInput.val();
                        var selectedCitySelect = $("#citySelect").val();

                        if (selectedCity === "" && selectedCitySelect === null) {
                            $("#errorCity").html("Veuillez sélectionner ou saisir une ville.");
                            return false;
                        } else {
                            $("#errorCity").html("");
                        }

                        // Si toutes les validations passent, retourner true
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

