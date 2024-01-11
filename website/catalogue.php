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

</head>

<body>
    <div class="container-map">
        <div id="map"></div>
    </div>

    <div class="grille">

        <div class="sidebar">

            <div class="container-side bg-354F52">
                <div class="mini-bandeau"> 
                    <h2 class="nom-region">Choisissez une région ou un pays pour commencer la découverte</h2>
                </div>

            </div>
        </div>

        <div class="main" id="main">
            
            <div class="container-stats bg-354F52" id="catalogue" hx-swap="outerHTML focus-scroll:true">

                <script>
                    function getSearchValue() {
                        var s = document.getElementById("txt")
                        return s.value
                    }
                </script>

                <div class="title-catalogue">
                    <h2 id="t1">Catalogue</h2>

                    <?php
                        if (isset($_SERVER["HTTP_HX_REQUEST"])) {
                            echo <<<HTML
                                <div id="close-catalogue">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                                    <path d="M 7.71875 6.28125 L 6.28125 7.71875 L 23.5625 25 L 6.28125 42.28125 L 7.71875 43.71875 L 25 26.4375 L 42.28125 43.71875 L 43.71875 42.28125 L 26.4375 25 L 43.71875 7.71875 L 42.28125 6.28125 L 25 23.5625 Z"></path>
                                    </svg>                        
                                </div>
                                <script>
                                    $("#close-catalogue").on("click", function() {
                                        $("#catalogue").empty()
                                        $("#catalogue").removeClass()
                                    })
                                </script>
                            HTML;
                        }
                    ?>
                    
                </div>
                
                <h3 id="t1">Recherche</h3>
                <div class="container-catalogue">
                    <input type="text" class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue()}' hx-target="#search" hx-swap="outerHTML">
                </div>

                <div id=search>
                    
                </div>

                <h3 id="t1">Vos favoris</h3>
                <div class="container-catalogue">
                    
                </div>

                <h3 id="t1">10 meilleurs scores</h3>
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
                    
                <h3 id="t1">Selection par continent</h3>
                
                <div class='container-continents'>
                    <div class="sub-continents">
                        <?php

                        $queryCont = "SELECT * FROM continents";
                        $resultCont = $cur->query($queryCont);
                        $i = 0;

                        while ($rsCont = $resultCont->fetch(PDO::FETCH_ASSOC)) {
                            $i++;
                            echo <<<HTML
                                <div class="container-catalogue">
                            HTML;

                            echo addCardContinent($rsCont["code"],$rsCont["nom"]);

                            $queryPays = "SELECT * FROM pays WHERE id_continent = ".$rsCont["id"]." ORDER BY score DESC LIMIT 4";
                            $resultPays = $cur->query($queryPays);

                            while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter);
                            }

                            echo <<<HTML
                                <div class="container-slim bg-52796F cursor" hx-get="scripts/htmx/more.php?continent=$rsCont[id]&more=1" hx-swap="outerHTML">
                                    <div class="bandeau-slim"> 
                                        <h2 class="nom-region">Voir plus</h2>
                                    </div>
                                </div>
                                </div>
                            HTML;   

                            if ($i == 3) {
                                echo <<<HTML
                                    </div>
                                    <div class="sub-continents">
                                HTML;   
                            }
                        }
                        ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
