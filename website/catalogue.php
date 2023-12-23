<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoTourism - Comparaison</title>
    <link rel="stylesheet" href="comparaison_v1/styles-bandeau.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Base -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Map -->
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>

    <script src="assets/js/ajax.js"></script>
    <script src="assets/js/map.js"></script>

    <?php
        require("functions.php");
        $cur = getDB();
    ?>

</head>

<body>
    <div class="container-map">
        <div id="map"></div>
    </div>

    <div id="bandeau">

    </div>

    <div class="grille">

        <div class="main">
            <div class="container-stats bg-52796F">
                <h2 id="t1">Régions</h2>
                <div class="container-catalogue">
                    
                    <?php
                        foreach (array("WW"=>"Monde","EU"=>"Europe","NA"=>"Amérique du Nord","SA"=>"Amérique du Sud","AS"=>"Asie","AF"=>"Afrique","OC"=>"Océanie") as $key => $value) {
                            echo <<<HTML
                                <div class="container-mini bg-354F52">
                                    <div class="mini-bandeau"> 
                                        <img class="img-small" src='assets/img/$key.png' alt="Bandeau">
                                        <h2 class="nom-region">$value</h2>
                                    </div>
                                </div>
                            HTML;
                        }
                    ?>
                </div>
            
                <h2 id="t1">Pays</h2>
                <div class="container-catalogue">
                    <?php

                        $query = "SELECT * FROM pays ORDER BY id_continent, nom ASC";
                        $result = $cur->query($query);

                        while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo <<<HTML
                                <div class="container-mini bg-354F52">
                                    <div class="mini-bandeau"> 
                                        <div class="mini-score-box">A</div>
                                        <img class="img-small" src='assets/img/$rs[id].jpg' alt="Bandeau">
                                        <img class="flag-small" src='assets/twemoji/$rs[id].svg'>
                                        <h2 class="nom-small">$rs[nom]</h2>
                                    </div>
                                </div>
                            HTML;
                        }
                    ?>
                </div>
            </div>
    </div>

    <script>
        
        createMap()

    </script>
    
</body>
</html>

