<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoTourism - Comparaison</title>
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Base -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Map -->
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>

    <script src="scripts/map/map.js"></script>
    
    <?php
        require("functions.php");
        $cur = getDB();
    ?>

    <script>
        function getSearchValue() {
            var s = document.getElementById("txt")
            return s.value
        }
    </script>

</head>

<body>
    <div class="container-map">
        <div id="map"></div>
    </div>

    <div id="bandeau">

    </div>

    <div class="grille">

        <div class="sidebar">

            <div class="container-mini bg-52796F">
                <div class="mini-bandeau"> 
                    <h2 class="nom-region">Choisissez une région ou un pays pour commencer la découverte</h2>
                </div>

                <div class=mini-stats> 
                </div>

            </div>
        </div>

        <div class="main" id="main" hx-swap-oob="afterbegin">
            
            <div class="container-stats bg-52796F">

                <h2 id="t1">Recherche</h2>
                <div class="container-catalogue">
                    <input type="text" class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue()}' hx-target="#search" hx-swap="outerHTML">
                </div>

                <div id=search>
                    
                </div>

                <div class=container-double>
                    <div class="container-stats">
                        <h2 id="t1">Vos favoris</h2>

                        
                    </div>

                    <div class="container-stats" >
                        <h2 id="t1">Dernières recherches</h2>
                    </div>

                </div>

                <h2 id="t1">10 meilleurs scores</h2>
                <div class="container-catalogue">
                    <?php
                        $queryPays = "SELECT * FROM pays ORDER BY score DESC LIMIT 10";
                        $resultPays = $cur->query($queryPays);

                        while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                            $letter = getLetter($rsPays["score"]);
                            echo addCardCountry($rsPays["id"],$rsPays["nom"],$letter);
                        }
                    ?>

                </div>

                <?php

                $queryCont = "SELECT * FROM continents";
                $resultCont = $cur->query($queryCont);

                while ($rsCont = $resultCont->fetch(PDO::FETCH_ASSOC)) {
                    echo <<<HTML
                        <h2 id="t1">$rsCont[nom]</h2>
                        <div class="container-catalogue">
                    HTML;

                    echo addCardContinent($rsCont["code"],$rsCont["nom"]);

                    $queryPays = "SELECT * FROM pays WHERE id_continent = ".$rsCont["id"]." ORDER BY score DESC LIMIT 7";
                    $resultPays = $cur->query($queryPays);

                    while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                        $letter = getLetter($rsPays["score"]);
                        echo addCardCountry($rsPays["id"],$rsPays["nom"],$letter);
                    }

                    echo <<<HTML
                        <div class="container-mini bg-354F52" hx-get="scripts/htmx/more.php?continent=$rsCont[id]&more=1" hx-swap="outerHTML">
                            <div class="mini-bandeau"> 
                                <h2 class="nom-region">Voir plus</h2>
                            </div>
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

        $(".button-catalogue").on("click", function() {
            var id = this.id
            var id_pays = id.substring(2,4)
            if (id[0] == "v") {
                window.location.href = "Pays_V1/pays.php?id_pays="+id_pays;
            } else {

            }
        })

    </script>
    
</body>
</html>

