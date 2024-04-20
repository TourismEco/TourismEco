<?php require_once "head.php"; ?>

<body>

    <div class="window">

        <?php
        if (isset($_SESSION["user"])) {
            header("Location: profil.php");
            exit();
        }
        // Générer un nouveau token CSRF si la variable de session n'existe pas
        if (!isset($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }
        ?>
        <div class="zone-totale" id="zones">
            <div class="left-section">
                <form hx-post="scripts/login/ajouter.php" hx-swap="beforeend">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[
                        "csrf_token"
                    ]; ?>">
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
                    <input type="text" id="country_register" name="country" placeholder="Saisissez un pays" required autocomplete="off" hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("country_register"), sens:"register"}'>
                    <div id="country_options_register" class="option-container"></div>
                    <div id="errorCountry" class="error"></div>

                    <label for="country_src">Votre ville</label>
                    <input type="text" id="city_register" name="city" placeholder="Saisissez une ville" required autocomplete="off">
                    <div id="city_options_register" class="option-container"></div>
                    <div id="errorCity" class="error"></div>

                    <input type="submit" value="S'inscrire" class="submit">
                    <div id="error" class="form-warning"></div>

                </form>
            </div>

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
