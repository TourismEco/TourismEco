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
    <link rel="stylesheet" href="assets/css/style_calculateur.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculateur</title>

    <script src="https://unpkg.com/htmx.org"></script>

    <script>
        function getSearchValue() {
            var s = document.getElementById("country_src")
            return s.value
        }
    </script>

</head>
    <body>
        <h1 class="titre">Prévoyez vos prochaines vacances</h1>
        <div class="content-container">

        <div class="left-section">
            <form action="ajouter.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="dual-input">
                    <div class="container-input">
                        <label for="country_src">Pays de départ</label>
                        <input type="text" id="country_src" name="country_src" placeholder="Saisissez un pays" required autocomplete="off"
                        hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue()}'>
                        <div id="countryOptions" class="option-container"></div>
                        <div id="errorCountry" class="error"></div>
                        <span class="validation-message"></span>
                    </div>

                    <div class="container-input">
                        <label for="password">Ville de départ</label>
                        <input type="password" id="password" name="password" placeholder="Sélectionnez une ville" required disabled autocomplete="off">
                        <div id="cityOptions" class="option-container"></div>
                        <div id="errorCity" class="error"></div>
                        <!-- Ajoutez un champ caché pour stocker la ville sélectionnée -->
                        <input type="hidden" id="selectedCity" name="selectedCity">
                        <span class="validation-message"></span>
                    </div>
                </div>

                <p class="text">Moyen de transport</p>
                <div class="liste-mode">
                    <input type="radio" name="mode" id="plane" class="input-hidden" disabled>
                    <label for="plane">
                        <img src="assets/img/plane.svg">
                    </label>

                    <input type="radio" name="mode" id="train" class="input-hidden">
                    <label for="train">
                        <img src="assets/img/train.svg">
                    </label>

                    <input type="radio" name="mode" id="car" class="input-hidden">
                    <label for="car">
                        <img src="assets/img/car.svg">
                    </label>
                </div>

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

            <div class="big-trait"></div>
            <div class="right-section">
                <p>Bienvenue sur notre puissant calculateur de trajet et de séjour, votre compagnon de voyage ultime. Vous êtes en quête de l'expérience de voyage parfaite? Notre outil offre une approche inégalée pour planifier vos aventures.
                <br>Pour chaque destination, choisissez entre train, avion et voiture pour découvrir les itinéraires les plus adaptés. Obtenez des estimations précises des coûts, du temps de trajet et de l'empreinte carbone, vous aidant à prendre des décisions éclairées pour des voyages durables et abordables. De plus, avec la possibilité d'ajouter la durée de votre séjour, vous obtiendrez une estimation du prix total de votre aventure.
                <br>Préparez-vous à explorer le monde en toute confiance, grâce à des informations complètes pour des voyages inoubliables.</p>
            </div>
        </div>
    </body>
</html>

