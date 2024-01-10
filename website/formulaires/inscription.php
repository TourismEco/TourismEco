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
        <h1> Inscription</h1>
        <div class="content-container">

            <form action="ajouter.php" method="post" onsubmit="return validateForm()">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" placeholder="Saisissez votre nom d'utilisateur" required autocomplete="off">
                    <div id="errorUsername" class="error"></div>

                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" required autocomplete="off">
                    <div id="errorPassword" class="error"></div>

                    <label for="confirmPassword">Confirmer le mot de passe :</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Saisissez à nouveau votre mot de passe" required autocomplete="off">
                    <div id="errorConfirmPassword" class="error"></div>

                    <label for="country">Pays actuel :</label>
                    <input type="text" id="country" name="country" placeholder="Saisissez votre pays actuel" required autocomplete="off">
                    <div id="countryOptions" class="country-options"></div>
                    <div id="errorCountry" class="error"></div>

                    <label for="city">Ville actuelle :</label>
                    <input type="text" id="cityInput" name="cityInput" placeholder="Saisissez votre ville actuelle" required autocomplete="off">
                    <div id="cityOptions" class="options-container"></div>
                    <div id="errorCity" class="error"></div>
                    <!-- Ajoutez un champ caché pour stocker la ville sélectionnée -->
                    <input type="hidden" id="selectedCity" name="selectedCity">

                    <input type="submit" value="S'inscrire" style="background-color: #52796F; color: white; border: 1px solid #52796F; border-radius: 8px; font-size: 16px; width:150px; margin-left:20%">
                    <div id="errorMessages" class="error"></div>

                </form>
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
                $(document).ready(function () {
                    function loadOptions(inputElement, selectElement, optionsUrl, targetElement, additionalData) {
                        var inputText = inputElement.val();
                        var requestData = { selectedCountry: inputText, ...additionalData }; // Ajouter la première lettre ici
                        $.ajax({
                            type: "POST",
                            url: optionsUrl,
                            data: requestData,
                            success: function (response) {
                                selectElement.html(response);
                                // Mettez à jour le champ d'entrée des villes
                                targetElement.html(response);
                            }
                        });
                    }

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
                            $("#errorCountry").html("Veuillez sélectionner un pays.");
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
                        loadOptions($(this), $("#countryOptions"), "get_countries.php", $("#city"));
                    });

                    // AJAX pour charger dynamiquement les options des villes
                    $("#cityInput").on("input", function () {
                        loadOptions($(this), $("#cityOptions"), "get_cities.php", $("#cityInput"));
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
                    
                    // Restaurer les valeurs des champs après le chargement de la page
                    if (sessionStorage.getItem('selectedCountry')) {
                        $('#country').val(sessionStorage.getItem('selectedCountry'));
                        loadOptions($('#country'), $('#countryOptions'), 'get_countries.php', $('#city'), { selectedLetter: $('#cityInput').val().charAt(0).toUpperCase() });
                    }

                    if (sessionStorage.getItem('selectedCity')) {
                        $('#cityInput').val(sessionStorage.getItem('selectedCity'));
                        loadOptions($('#cityInput'), $('#cityOptions'), 'get_cities.php', $('#cityInput'));
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

