<?php

require('../../functions.php');

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $connexion = getDB();

    if ($search === 'Tous les pays') {
        $stmt = $connexion->prepare("SELECT nom, id FROM pays ORDER BY nom");
    } else {
        $stmt = $connexion->prepare("SELECT nom, id FROM pays WHERE nom LIKE ?");
        $stmt->execute(["$search%"]);
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo <<<HTML
        <input type="text" id="city_src" name="city_src" placeholder="Sélectionnez une ville" required disabled autocomplete="off" hx-swap-oob="outerHTML">
        <div id="cityOptions" class="option-container" hx-swap-oob="outerHTML"></div>
        <div id="countryOptions" class="option-container" hx-swap-oob="outerHTML">
    HTML;

    if (!empty($options)) {
        foreach ($options as $option) {
            echo <<<HTML
                <option value=$option[id] hx-get="scripts/htmx/selectPays.php" hx-trigger="click" hx-vals="js:{id_pays:'$option[id]',nom:'$option[nom]'}">$option[nom]</option>
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