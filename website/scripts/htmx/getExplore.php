<?php
    if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
        header("HTTP/1.1 401");
        exit;
    }
    
    if (!isset($_GET["id_pays"])) {
        header("HTTP/1.1 400");
        exit;
    }
    
    require("../../functions.php");
    
    if (!checkHTMX("explorer", $_SERVER["HTTP_HX_CURRENT_URL"])) {
        header("HTTP/1.1 401");
        exit;
    }

    $cur = getDB();

    $id_pays = $_GET["id_pays"];

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
    $anec = explode(" : ",htmlspecialchars($ligne[$a[$sv]]));

    $c = getCities($id_pays, $cur);
    $cities = json_encode($c["cities"]);
    $capitals = json_encode($c["capitals"]);

    echo <<<HTML

    <div id="pays" class="container-explore" hx-swap-oob="outerHTML">

        <div class="container-explore-pays expand-4">
            <div class="bandeau"> 
                <img class="img-bandeau" src='assets/img/$id_pays.jpg' alt="Bandeau">
                <div class="flag-plus-nom">
                    <img class="flag-explore" src='assets/twemoji/$id_pays.svg'>
                    <h2>$nom</h2>
                </div>
            </div>
        </div>

        <div class="container-explore-pays expand-2"></div>

        <div class="container-explore-pays">
            <div class="score-box score-$letter">$letter</div>
        </div>

        <div class="container-explore-pays expand-2 expandrow-2 anecExplore">
            <h2 class="h3 anecTitle">$anec[0]</h2>
            <p class="paragraphe">$anec[1]</p>
        </div>

        <div class="explore-rang" id="rang"></div>

        <div class="explore-more" hx-post="pays.php" hx-vals="js:{id_pays:'$id_pays'}" hx-swap="outerHTML swap:0.5s" hx-target="#zones" hx-select="#zones" hx-push-url="true">
            <img src="assets/icons/plus.svg" alt="icon plus">
            <p class="more-text">Voir plus</p>
        </div>

    </div>

    <script id="scripting" hx-swap-oob="outerHTML">
        map.zoomTo("$id_pays")
        map.addCities($cities)
        map.addCapitals($capitals)
        id_pays = "$id_pays"
        updateRanking(id_pays,typeC,map.data)
    </script>

    HTML;
?>