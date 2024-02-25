<?php require_once 'head.php'?>

<body>
    <div class="container-map" id="container-map">
        <div id="map"></div>
    </div>
    
    <div class="grille" id="grille">

        <div class="left-section">
            <h1 class="titre">Prévoyez vos prochaines vacances</h1>
            <form name="Calculateur" hx-get="calcul.php" hx-target="#calculateur-right-section">
                <!-- <form action="test/calcul_test.php" method="get"> -->

                <div class="dual-input">
                    <div class="container-input">
                        <label for="country_src">Pays de départ</label>
                        <input type="text" id="country_src" name="country_src" placeholder="Saisissez un pays" required autocomplete="off"
                        hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("country_src"), sens:"src"}'>
                        <div id="country_options_src" class="option-container"></div>
                    </div>

                    <div class="container-input">
                        <label for="city_src">Ville de départ</label>
                        <input type="text" id="city_src" name="city_src" placeholder="Saisissez une ville" required disabled autocomplete="off">
                        <div id="city_options_src" class="option-container"></div>
                    </div>
                </div>

                <div class="dual-input">
                    <div class="container-input">
                        <label for="country_dst">Pays d'arrivée</label>
                        <input type="text" id="country_dst" name="country_dst" placeholder="Saisissez un pays" required autocomplete="off"
                        hx-get="scripts/htmx/listPays.php" hx-swap="none" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("country_dst"), sens:"dst"}'>
                        <div id="country_options_dst" class="option-container"></div>
                    </div>

                    <div class="container-input">
                        <label for="city_dst">Ville d'arrivée</label>
                        <input type="text" id="city_dst" name="city_dst" placeholder="Saisissez une ville" required autocomplete="off" disabled>
                        <div id="city_options_dst" class="option-container"></div>
                    </div>
                </div>

                <p class="fake-label">Moyen de transport</p>
                <!-- <div class="liste-mode" hx-get="scripts/htmx/getTravelOptions.php" hx-target="#travel-options" hx-vals='js:{mode: document.forms["Calculateur"]["mode"].value}'> -->
                    <div class="liste-mode">
                    <input type="radio" name="mode" id="plane" class="radio-mode" value="PLANE">
                    <label for="plane">
                        <img src="assets/img/plane.svg" title="Avion">
                    </label>

                    <input type="radio" name="mode" id="train" class="radio-mode" value="TRAIN">
                    <label for="train">
                        <img src="assets/img/train.svg" title="Train">
                    </label>

                    <input type="radio" name="mode" id="driving" class="radio-mode" value="DRIVING">
                    <label for="driving">
                        <img src="assets/img/car.svg" title="Voiture">
                    </label>
                </div>

                <!-- Travel options (Airports if plane, train stations...) -->
                <div class="dual-input" id="travel-options"></div>

                <div class="dual-input">
                <!--     <div class="container-input">
                        <label for="country">Durée du séjour (en jours)</label>
                        <input type="number" id="country" name="country" placeholder="Saisissez un nombre" required autocomplete="off" value=7 min=1 max=30>
                    </div> -->

                    <div class="container-input">
                        <label for="country">Nombre de voyageurs</label>
                        <input type="number" id="passengers" name="passengers" placeholder="Saisissez un nombre" required autocomplete="off" value=2 min=1 max=69>
                    </div>
                </div>
                <!-- <input type="hidden" id="CSRF" name="CSRF" value="<?=$_SESSION["CSRF"]?>"> -->

                <input type="submit" value="Calculer" class="submit">

            </form>
        </div>

        <div class="big-trait"></div>
        <div class="right-section" id="calculateur-right-section">
            <p>Bienvenue sur notre puissant calculateur de trajet et de séjour, votre compagnon de voyage ultime. Vous êtes en quête de l'expérience de voyage parfaite? Notre outil offre une approche inégalée pour planifier vos aventures.</p>
            <p>Pour chaque destination, choisissez entre train, avion et voiture pour découvrir les itinéraires les plus adaptés. Obtenez des estimations précises des coûts, du temps de trajet et de l'empreinte carbone, vous aidant à prendre des décisions éclairées pour des voyages durables et abordables. De plus, avec la possibilité d'ajouter la durée de votre séjour, vous obtiendrez une estimation du prix total de votre aventure.</p>
            <p>Préparez-vous à explorer le monde en toute confiance, grâce à des informations complètes pour des voyages inoubliables.</p>
        </div>

        <script id=scripting>
            hideMap()
        </script>
    </div>

    <?php require_once 'footer.html'?>
</body>
