<?php require_once "head.php"; ?>

<body>

    <div class="window">
        <div class="zone zone-totale" id="zones">
            <div class="left-section">

            <?php if (isset($_SESSION["user"])) {
                $prenom = htmlspecialchars($_SESSION["user"]["username"]);
                echo "<h1 class='titre'> Bonjour $prenom <br></h1>";
            } else {
                header("Location: connexion.php");
                exit();
            } ?>
            <img class="logo" src="assets/icons/profil.png" alt="LogoProfil">

            <h2 style="font-size:20px;">Modifier votre profil</h2>

            <div class="connexion-container">
                <form id="loginForm" hx-post="scripts/login/modif.php" hx-swap="beforeend">
                    <label for="country">Pays actuel</label>
                    <input type="text" id="country_register" name="country" placeholder=<?php echo $_SESSION[
                        "user"
                    ]["country"]; ?> required autocomplete="off"
                        hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("country_register"), sens:"register"}'>
                        <div id="country_options_register" class="option-container"></div>
                    <br>

                    <label for="cityInput">Ville actuelle</label>
                    <input type="text" id="city_register" name="city" disabled placeholder=<?php echo $_SESSION[
                        "user"
                    ]["city"]; ?> required autocomplete="off">
                        <div id="city_options_register" class="option-container"></div>
                    <br>

                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION[
                        "csrf_token"
                    ]; ?>">

                    <input type="submit" value="Modifier" style="background-color: #52796F; color: white; border: 1px solid #52796F; border-radius: 8px; font-size: 16px; width:150px; display: block; margin: 0 auto;">
                    <div id="error" class="form-warning"></div>
                </form>
            </div>
        </div>

        <div class="right-section">
            <h2 style="font-size:20px;">Modifier vos préférences</h2>
            <div class="connexion-container">
                <form id="preference" hx-post="scripts/login/ajouter.php" hx-swap="beforeend">
                    <div class="preference-item">
                        <label for="tourisme_moderne">Tourisme Moderne (economique)</label>
                        <input type="radio" id="tourisme_moderne" name="tourisme_moderne" value="Tourisme Moderne (economique)">
                    </div>

                    <div class="preference-item">
                        <label for="tourisme_decouverte">Tourisme d'Exploration et de Découverte</label>
                        <input type="radio" id="tourisme_decouverte" name="tourisme_decouverte" value="Tourisme d'Exploration et de Découverte">
                    </div>

                    <div class="preference-item">
                        <label for="tourisme_eco">Tourisme Éco-responsable</label>
                        <input type="radio" id="tourisme_eco" name="tourisme_eco" value="Tourisme Éco-responsable">
                    </div>

                    <div class="preference-item">
                        <label for="pas_preference">Pas de préference particulière</label>
                        <input type="radio" id="pas_preference" name="pas_preference" value="Pas de préference particuliere">
                    </div>

                    <input type="submit" value="Mettre à jour" style="background-color: #52796F; color: white; border: 1px solid #52796F; border-radius: 8px; font-size: 16px; width:150px; display: block; margin: 0 auto;">
                </form>
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
</html>
