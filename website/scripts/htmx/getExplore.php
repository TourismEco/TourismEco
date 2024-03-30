<?php
    require("../../functions.php");

    $cur = getDB();

    $id_pays = $_GET["id_pays"];
    $_SESSION["pays"][0] = $id_pays;

    if (!isset($_SESSION["historique"]) || !is_array($_SESSION["historique"])) {
        $_SESSION["historique"] = array();
    }
    if (!in_array($id_pays, $_SESSION["historique"])) {
        // Ajoutez le nouvel élément à l'historique
        $_SESSION["historique"][] = $id_pays;
    
        $maxHistoriqueSize = 3;  // Définir la taille maximale de l'historique
        while (count($_SESSION["historique"]) > $maxHistoriqueSize) {
            array_shift($_SESSION["historique"]);
        }
    }

    if (isset($_GET["map"])) {
        $map = "false";
    } else {
        $map = "true";
    }

    // Nom
    $query = "SELECT * FROM pays WHERE id = :id_pays";
    $sth = $cur->prepare($query);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->execute();
    $ligne = $sth->fetch();
    $nom = $ligne["nom"];
    $description = $ligne["description"];
    $letter = getLetter($ligne["score"]);

    $a = array("sv1","sv2","sv3");
    $sv = array_rand($a);
    $anec = $ligne[$a[$sv]];

    $c = getCities($id_pays, $cur);
    $cities = json_encode($c["cities"]);
    $capitals = json_encode($c["capitals"]);

    echo <<<HTML

    <div class="explore-bandeau" id="bandeau" hx-swap-oob="outerHTML">
        <div class="bandeau"> 
            <img class="img-side img" src='assets/img/$id_pays.jpg' alt="Bandeau">
            <div class="flag-plus-nom">
                <img class="flag-explore" src='assets/twemoji/$id_pays.svg'>
                <h2 class="nom">$nom</h2>
            </div>
        </div>
    </div>

    <div class="explore-score" id="score" hx-swap-oob="outerHTML">
        <div class="score-box score-$letter">$letter</div>
    </div>

    <div class="explore-describ" id="describ" hx-swap-oob="outerHTML">
        <p class="paragraphe">$anec</p>
    </div>

    <p class="name" id="nom" hx-swap-oob="outerHTML">$nom</p>

    <div id="more" class="explore-more" hx-swap-oob="outerHTML" hx-get="UI3_pays.php" hx-vals="js:{id_pays:'$id_pays'}" hx-swap="outerHTML swap:0.5s" hx-target="#grid" hx-select="#grid">
        <img src="assets/icons/plus.svg" alt="icon plus">
        <p class="more">Voir plus</p>
    </div>

    <script id="scripting" hx-swap-oob="outerHTML">
        map.addCities($cities)
        map.addCapitals($capitals)
    </script>

    HTML;
?>