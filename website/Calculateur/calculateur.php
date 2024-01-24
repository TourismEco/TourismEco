<?php
    // Inscription.php
   

    // Générer un nouveau token CSRF si la variable de session n'existe pas
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../formulaires/styles_formulaire.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

</head>
    <body>
        <?php require_once '../navbar.php'?>
        <h1 class="titre">Prévoyez vos prochaines vacances</h1>
        <div class="content-container">

            <form onsubmit="return validateForm()" hx-get= "">

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


                <label for="Transport">Moyen de transport :</label>
                <input type="text" id="Transport" name="Transport" placeholder="Saisissez votre moyen de Transport" required autocomplete="off">
                <div id="TransportOptions" class="options-container"></div>
                <div id="errorTransport" class="error"></div>
                <!-- Ajoutez un champ caché pour stocker la ville sélectionnée -->
                <input type="hidden" id="selectedTransport" name="selectedTransport">
                <span class="validation-message"></span>

                <label for="Sejour">Durée du séjour :</label>
                <input type="text" id="Sejour" name="Sejour" placeholder="Saisissez le nombre de jours de votre séjour" required autocomplete="off">
                

                
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

                       
                        var countryIsValid = validateField('input[name="country"]', isValidCountry, 'Le pays n\'est pas valide, ou n\'existe pas.');
                        var cityIsValid = validateField('input[name="city"]', isValidCity, 'La ville n\'est pas valide, ou n\'existe pas.');
                        return isValid;
                    }

                    $('input[name="country"], input[name="city"]').on('input', function() {
                        validateForm();
                    });



                    // Fonction de validation du formulaire
                    function validateForm() {
                       
                        var confirmPassword = $("#confirmPassword").val();
                        var countryInput = $("#country");

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
                            url: "../formulaires/get_countries.php",
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
                            url: "../formulaires/get_cities.php",
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
                            url: "../formulaires/get_countries.php",
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
                            url: "../formulaires/get_cities.php",
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
                            url: "../formulaires/get_cities.php",
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
                <p>Bienvenue sur notre puissant calculateur de trajet et de séjour, votre compagnon de voyage ultime. Vous êtes en quête de l'expérience de voyage parfaite? Notre outil offre une approche inégalée pour planifier vos aventures.
                <br>Pour chaque destination, choisissez entre train, avion et voiture pour découvrir les itinéraires les plus adaptés. Obtenez des estimations précises des coûts, du temps de trajet et de l'empreinte carbone, vous aidant à prendre des décisions éclairées pour des voyages durables et abordables. De plus, avec la possibilité d'ajouter la durée de votre séjour, vous obtiendrez une estimation du prix total de votre aventure.
                <br>Préparez-vous à explorer le monde en toute confiance, grâce à des informations complètes pour des voyages inoubliables.</p>
            </div>
        </div>
        <?php require_once '../footer.html'?>
    </body>
</html>

