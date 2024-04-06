<?php require_once 'head.php' ?>

<body>

    <div class="flex">
        <div class="zone-totale" id="zones">
            <div class="left-section">

            <?php
                if (isset($_SESSION['user'])) {
                    $prenom = htmlspecialchars($_SESSION['user']['username']);
                    echo "<h1 class='titre'> Bonjour $prenom <br></h1>";
                } else {
                    header("Location: connexion.php");
                    exit();
                }
            ?>
            <img class="logo" src="assets/icons/profil.png" alt="LogoProfil">

            <h2 style="font-size:20px;">Modifier votre profil</h2>

            <div class="connexion-container">
                    <form id="loginForm" action="Profil/modif.php" method="post" onsubmit="return validateForm()">
                        <label for="country">Pays actuel</label>
                        <input type="text" id="country_register" name="country" placeholder=<?php echo $_SESSION['user']['country'] ?> required autocomplete="off"
                            hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("country_register"), sens:"register"}'>
                            <div id="country_options_register" class="option-container"></div>
                            <div id="errorCountry" class="error"></div>
                        <br>

                        <label for="cityInput">Ville actuelle</label>
                        <input type="text" id="city_register" name="city" placeholder=<?php echo $_SESSION['user']['city'] ?> required autocomplete="off">
                            <div id="city_options_register" class="option-container"></div>
                            <div id="errorCity" class="error"></div>
                        <br>

                        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                        <input type="submit" value="Modifier" style="background-color: #52796F; color: white; border: 1px solid #52796F; border-radius: 8px; font-size: 16px; width:150px; display: block; margin: 0 auto;">
                        <div id="errorMessages" class="error"></div>
                    </form>
                

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
                                
            
                            // Fonction pour mettre à jour le statut du champ (valide/invalide)
                            function updateFieldStatus(field, isValid, errorMessage) {
                                if (!isValid) {
                                    showErrorAlert(errorMessage, field);
                                }
                            }
            
                            // Fonction pour valider le formulaire
                            function validateForm() {
                                count = 0
                                var countryField = $('input[name="country_register"]');
                                var cityInputField = $('input[name="city_register"]');
            
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
            </div>
        </div>

        <div class="right-section">
            <h2 style="font-size:20px;">Modifier vos préférences</h2>
            <div class="connexion-container">
                <form id="preference">
                </form>
            </div>
        </div>

        <div class="zone mask"></div>

        <div id="nav-bot" hx-swap-oob="outerHTML"></div>

        <script id="scripting" hx-swap-oob="outerHTML"></script>
        <script id="behave" hx-swap-oob="outerHTML"></script>
    </div>

</body>
</html>