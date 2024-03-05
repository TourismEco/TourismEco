
<?php
switch ($_GET["mode"]) {
    case "PLANE":
        echo <<<HTML
            <div class="container-input">
                <label for="airport_src">Aéroport de départ</label>
                <input type="text" id="airport_src" name="airport_src" placeholder="Saisissez un aéroport" required autocomplete="off"
                hx-get="scripts/htmx/listAirports.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("airport_src"), sens:"src"}'>
                <div id="airport_options_src" class="option-container"></div>
            </div>

            <div class="container-input">
                <label for="airport_dst">Aéroport d'arrivé</label>
                <input type="text" id="airport_dst" name="airport_dst" placeholder="Saisissez un aéroport" required disabled autocomplete="off">
                <div id="airport_options_dst" class="option-container"></div>
            </div>
        HTML;
        break;
    case "TRAIN":
        echo <<<HTML
            <div class="container-input">
                <label for="station_src">Gare de départ</label>
                <input type="text" id="station_src" name="station_src" placeholder="Saisissez une gare" required autocomplete="off"
                hx-get="scripts/htmx/listStations.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("station_src"), sens:"src"}'>
                <div id="station_options_src" class="option-container"></div>
            </div>

            <div class="container-input">
                <label for="station_dst">Gare d'arrivée</label>
                <input type="text" id="station_dst" name="station_dst" placeholder="Saisissez une gare" required disabled autocomplete="off">
                <div id="station_options_dst" class="option-container"></div>
            </div>
        HTML;
        break;
    }