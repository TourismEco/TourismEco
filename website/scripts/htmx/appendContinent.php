<?php
    // if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    //     header("HTTP/1.1 401");
    //     exit;
    // }
    
    if (!isset($_GET["id_pays"])) {
        header("HTTP/1.1 400");
        exit;
    }
    
    require("../../functions.php");
    
    // if (!checkHTMX("continent", $_SERVER["HTTP_HX_CURRENT_URL"])) {
    //     header("HTTP/1.1 401");
    //     exit;
    // }

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

    $c = getCities($id_pays, $cur);
    $cities = json_encode($c["cities"]);
    $capitals = json_encode($c["capitals"]);

    echo <<<HTML

    <div id="pays" class="container-explore-continent" hx-swap-oob="outerHTML">

        
        <div class="bandeau col1"> 
            <img class="img-bandeau" src='assets/img/$id_pays.jpg' alt="Bandeau">
            <div class="flag-plus-nom">
                <img class="flag-explore" src='assets/twemoji/$id_pays.svg'>
                <h2>$nom</h2>
            </div>
        </div>
        
        <div class="container-explore-pays col2">
            <div class="score-box score-$letter" id="mar0">$letter</div>
        </div>
        
        <div class="explore-more col3" hx-post="pays.php" hx-vals="js:{id_pays:'$id_pays'}" hx-swap="outerHTML swap:0.5s" hx-target="#zones" hx-select="#zones" hx-push-url="true">
            <img src="assets/icons/plus.svg" alt="icon plus">
            <p class="more-text">Voir plus</p>
        </div>

    </div>

    HTML;
?>