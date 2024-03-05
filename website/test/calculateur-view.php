<?php 
require_once "../functions.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ecotourisme</title>
    <base href="../">
	<link rel="stylesheet" href="assets/css/styles.css">

	<!-- Stack -->
	<script src="https://unpkg.com/htmx.org"></script>
	<script src="https://unpkg.com/jquery.min.js"></script>

	<!-- Base -->
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

	<!-- Map -->
	<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>

	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>

	<script src="scripts/graph/amTools.js"></script>
	<script src="scripts/graph/amTools20.js"></script>
    <script src="scripts/map/map.js"></script>

	<script src="scripts/graph/lineCompare.js"></script>
	<script src="scripts/graph/spiderCompare.js"></script>

	<script src="scripts/graph/spider.js"></script>
	
	<script src="scripts/graph/barCompare.js"></script>
	<script src="scripts/graph/barreLine.js"></script>
	<script src="scripts/graph/jauge.js"></script>

	<script src="scripts/graph/barre.js"></script>
	<script src="scripts/graph/linePays.js"></script>

	<script src="scripts/js/functions.js"></script>

</head>
<body>
<?php require_once '../navbar.php' ?>

<body>
    <div class="container-map" id="container-map">
        <div id="map"></div>
    </div>
    
    <main class="grille" id="grille">

        <div class="left-section">
            <h1 class="titre">Prévoyez vos prochaines vacances</h1>
            <form name="Calculateur" hx-get="calcul.php" hx-target="#calculateur-right-section">
                <!-- <form action="test/calcul_test.php" method="get">git  -->

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

                <div class="dual-input" id="travel-options"></div>

                <div class="dual-input">
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
            <div class="result">
                <div class="result-header">
                    <div class="result-header-left">
                        <h2 class="result-header-left-title">Résultats</h2>
                        <p class="result-header-left-subtitle">Vos résultats pour un trajet de Paris, France à Barcelone, Espagne</p>
                    </div>
                    <div class="result-header-right">
                        <div class="result-header-right-mode">
                            <!-- <img src="assets/img/plane.svg"> -->
                            <p class="result-header-right-mode-text"><?=$mode?></p>
                        </div>
                        <!-- <div class="result-header-right-mode">
                            <img src="assets/img/car.svg">
                            <p class="result-header-right-mode-text">Voiture</p>
                        </div>
                        <div class="result-header-right-mode">
                            <img src="assets/img/train.svg">
                            <p class="result-header-right-mode-text">Train</p>
                        </div> -->
                    </div>
                </div>
                <div class="result-content">
                    <div class="result-content-left">
                        <div class="result-content-left-item">
                            <h3 class="result-content-left-item-title">Distance</h3>
                            <p class="result-content-left-item-value">578km</p>
                        </div>
                        <div class="result-content-left-item">
                            <h3 class="result-content-left-item-title">Durée</h3>
                            <p class="result-content-left-item-value">2h 7minn</p>
                        </div>
                        <div class="result-content-left-item">
                            <h3 class="result-content-left-item-title">Coût du trajet par passager</h3>
                            <p class="result-content-left-item-value">22 €</p>
                        </div>
                    </div>
                    <div class="result-content-right">
                        <div class="result-content-right-item">
                            <h3 class="result-content-right-item-title">Consommation de carburant</h3>
                            <p class="result-content-right-item-value">68L</p>
                        </div>
                        <div class="result-content-right-item">
                            <h3 class="result-content-right-item-title">Empreinte carbone par passager</h3>
                            <p class="result-content-right-item-value">7 kg</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <script id=scripting>
                    hideMap()
                </script>
            </main>

            <?php require_once '../footer.html'?>
        </body>
    </html>