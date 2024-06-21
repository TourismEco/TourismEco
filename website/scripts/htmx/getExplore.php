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

    $a = array("sv1","sv2","sv3");
    $sv = array_rand($a);
    $anec = $ligne[$a[$sv]];
    $anec = explode(" : ",htmlspecialchars($ligne[$a[$sv]]));

    $c = getCities($id_pays, $cur);
    $cities = json_encode($c["cities"]);
    $capitals = json_encode($c["capitals"]);

    // Indicateurs
    $queryIndic="SELECT pays_score.labelEcologique, pays_score.labelEconomique, pays_score.labelDecouverte, pays_score.labelGlobal
                FROM `pays_score`
                JOIN pays ON pays_score.id = pays.id
                WHERE pays_score.id = :id_pays;";
    $sth = $cur->prepare($queryIndic);
    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
    $sth->execute();
    $ligne = $sth->fetch(PDO::FETCH_ASSOC);

    $nomsScore = array("labelGlobal" => "Global", "labelDecouverte" => "Tourisme d'exploration", "labelEcologique" => "Tourisme éco-responsable", "labelEconomique" => "Tourisme moderne");
    if (!isset($_SESSION["user"]) || $_SESSION["user"]["score"] == "Global") {
        $random = array("labelEcologique","labelEconomique","labelDecouverte");
        $score = $random[array_rand($random)];
    } else {
        $score = "label".$_SESSION["user"]["score"];
    }

    $letter = $ligne["labelGlobal"];    

    echo <<<HTML

    <div id="pays" class="container-explore" hx-swap-oob="outerHTML">

        <div class="container-presentation expand-3">
            <div class="bandeau"> 
                <img class="img-bandeau" src='assets/img/$id_pays.jpg' alt="Bandeau">
                <div class="flag-plus-nom">
                    <img class="flag-explore" src='assets/twemoji/$id_pays.svg'>
                    <h3>$nom</h3>
                </div>
            </div>
        </div>
    
    HTML;

    if ($letter != null) {
        echo <<<HTML
            <div class="container-presentation expand-2 flex-column">
                <h3>Score Global</h3>
                <div class="score-box score-$letter">$letter</div>
            </div>
        HTML;
    } else {
        echo <<<HTML
            <div class="container-presentation expand-2 flex-column">
                <h3>Score Global</h3>
                <div class="score-box score-NA"><img src='assets/icons/bd.svg'></div>
            </div>
        HTML;
    }

    echo <<<HTML

        <div class="container-presentation flex-column">
            <h3>Score $nomsScore[$score]</h3>
            <div class="score-box score-$ligne[$score]">$ligne[$score]</div>
        </div>

        <div class="container-presentation centered-content" id="rang"></div>

        <div class="explore-more" hx-post="pays.php" hx-vals="js:{id_pays:'$id_pays'}" hx-swap="outerHTML swap:0.5s" hx-target="#zones" hx-select="#zones" hx-push-url="true">
            <img src="assets/icons/plus.svg" alt="icon plus">
            <p class="more-text">Voir plus</p>
        </div>

        <div class="container-presentation expand-2 expandrow-2 flex-column">
            <h2>$anec[0]</h2>
            <p class="paragraphe">$anec[1]</p>
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