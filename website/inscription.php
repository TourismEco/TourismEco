<?php require_once 'head.php'?>

<body>

    <div class="flex">

        <?php
            // Générer un nouveau token CSRF si la variable de session n'existe pas
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        ?>
        <div class="zone-totale" id="zones">
            <div class="left-section">
                <form action="scripts/login/ajouter.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Saisissez votre nom d'utilisateur" required autocomplete="off">
                    <div id="errorUsername" class="error"></div>
    
    
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" required autocomplete="off">
                    <div id="errorPassword" class="error"></div>
    
    
                    <label for="confirmPassword">Confirmer le mot de passe</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Saisissez à nouveau votre mot de passe" required autocomplete="off">
                    <div id="errorConfirmPassword" class="error"></div>
    
    
                    <label for="country_src">Votre pays</label>
                    <input type="text" id="country_register" name="country" placeholder="Saisissez un pays" required autocomplete="off"
                    hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("country_register"), sens:"register"}'>
                    <div id="country_options_register" class="option-container"></div>
                    <div id="errorCountry" class="error"></div>
    
                    <label for="country_src">Votre ville</label>
                    <input type="text" id="city_register" name="city" placeholder="Saisissez une ville" required autocomplete="off">
                    <div id="city_options_register" class="option-container"></div>
                    <div id="errorCity" class="error"></div>
    
                    <input type="submit" value="S'inscrire" class="submit">
    
                </form>
            </div>
            <script>
                $(document).ready(function () {
                    var count = 0
                    // Fonction pour afficher une alerte et mettre en surbrillance le champ en erreur
                    function showErrorAlert(message, field) {
                        $(field).html(message)
                        count++
                    }
    
                    function clearError(field) {
                        $(field).empty()
                    }
    
                    // Fonction pour valider le nom et prénom (lettres, accents et chiffres)
                    function isValidName(name, field) {
                        var nameRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ0-9\s']+$/;
                        var validName = nameRegex.test(name);
                        if (validName) {
                            $.ajax({
                                url: 'scripts/login/check_username.php',
                                method: 'POST',
                                data: { username: name },
                                dataType: 'json',
                                success: function (data) {
                                    var exists = data.exists;
                                    if (exists) {
                                        showErrorAlert("Nom d'utilisateur déjà utilisé", $("#errorUsername"));
                                    } else {
                                        clearError($("#errorUsername"))
                                    }
                                },
                                error: function () {
                                    console.error("Erreur lors de la vérification de l'unicité du nom d'utilisateur");
                                }
                            });
                        } else {
                            showErrorAlert("Nom d'utilisateur non valide", field);
                        }
                    }
    
                    // Fonction pour mettre à jour le statut du champ (valide/invalide)
                    function updateFieldStatus(field, isValid, errorMessage) {
                        if (!isValid) {
                            showErrorAlert(errorMessage, field);
                        }
                    }
    
                    // Fonction pour valider le formulaire
                    function validateForm() {
                        count = 0
                        var usernameField = $('input[name="username"]');
                        var passwordField = $('input[name="password"]');
                        var confirmPasswordField = $('input[name="confirmPassword"]');
                        var countryField = $('input[name="country_register"]');
                        var cityInputField = $('input[name="city_register"]');
    
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
                                showErrorAlert("Le mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial.", $("#errorPassword"));
                            }
                        }
    
                        // Validation de la confirmation du mot de passe
                        var confirmPassword = confirmPasswordField.val();
                        if (confirmPassword !== password) {
                            showErrorAlert("Les mots de passe ne correspondent pas.", $("#errorPasswordConfirm"));
                        } else {
                            clearError($("#errorPasswordConfirm"))
                        }
    
                        // Validation du pays
                        var country = countryField.val();
                        if (country.trim() === "") {
                            showErrorAlert("Veuillez sélectionner ou saisir un pays.", $("#errorCountry"));
                        } else {
                            clearError($("#errorCountry"))
                        }
    
                        // Validation de la ville
                        var cityInput = cityInputField.val();
                        var selectedCity = $("#selectedCity").val();
                        if (cityInput.trim() === "" && selectedCity === "") {
                            showErrorAlert("Veuillez sélectionner ou saisir une ville.", $("#errorCity"));
                        } else {
                            clearError($("#errorCity"))
                        }
                        // Si au moins un champ a une erreur, empêcher la soumission du formulaire
                        if (count > 0) {
                            return false;
                        }
    
                        return true;
                    }
    
                    // Gestionnaire de soumission du formulaire
                    $('form').submit(function () {
                        return validateForm();
                    });
                });
            </script>
    
            <div class="big-trait"></div>
    
            <div class="right-section">
                <img class="logo" src="assets/icons/avionProfil.png" alt="LogoAvion">
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

        <div class="zone mask"></div>

        <div id="nav-bot" hx-swap-oob="outerHTML"></div>

        <script id="scripting" hx-swap-oob="outerHTML"></script>
        <script id="orders" hx-swap-oob="outerHTML"></script>
        <script id="behave" hx-swap-oob="outerHTML"></script>
        <div id="htmxing" hx-swap-oob="outerHTML"></div>

    </div>    
    
</body>