<?php

require('../../functions.php');

if (isset($_GET["id_pays"])) {
    $id_pays = $_GET["id_pays"];
    $nom = $_GET["nom"];
    $connexion = getDB();

    $stmt = $connexion->prepare("SELECT * FROM villes WHERE id_pays = ?");
    $stmt->execute(["$id_pays"]);

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo <<<HTML
        <input type="text" id="country_src" name="country_src" placeholder="Saisissez un pays" required value="$nom"
            hx-get="scripts/htmx/listPays.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue()}' hx-swap-oob="outerHTML">
        <input type="text" id="city_src" name="city_src" placeholder="Sélectionnez une ville" required autocomplete="off" hx-swap-oob="outerHTML">
        <div id="countryOptions" class="option-container" hx-swap-oob="outerHTML"></div>
        <div id="cityOptions" class="option-container" hx-swap-oob="outerHTML">
    HTML;

    if (!empty($options)) {
        foreach ($options as $option) {
            echo <<<HTML
                <option value=$option[id]>$option[nom]</option>
            HTML;
        }
    } else {
        echo <<<HTML
            <option value=\"\">Aucun résultat</option>
        HTML;
    }

    echo <<<HTML
        </div>
    HTML; 
}

?>