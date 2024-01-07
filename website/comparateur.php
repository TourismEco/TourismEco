<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoTourism - Comparaison</title>
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <!-- Base -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Map -->
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
    

    <!-- Graph -->
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="scripts/graph/lineCompare.js"></script>

    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="scripts/graph/spiderCompare.js"></script>

    <script src="scripts/graph/barCompare.js"></script>

    <script src="scripts/graph/amTools.js"></script>
    <script src="scripts/map/map.js"></script>

</head>

<body>
    <?php
        require("functions.php");

        function getPays($arg) {
            if (isset($_GET[$arg])) {
                return $_GET[$arg];
            } else {
                return "00";
            }
        }

        $cur = getDB();

        $pays1 = getPays("pays0");
        $pays2 = getPays("pays1");
        $pays = array();

        foreach (array($pays1,$pays2) as $key => $id_pays) {
            $query = "SELECT * FROM pays WHERE id = :id_pays";
            $sth = $cur->prepare($query);
            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
            $sth->execute();

            $ligne = $sth->fetch();
            if ($ligne) {
                $pays[] = $id_pays;
            }
        }

        switch (count($pays)) {
            case 2:
                echo <<<HTML
                    <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'$pays1'}" hx-trigger="load"></div>
                    <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:1,id_pays:'$pays2'}" hx-trigger="load delay:.05s"></div>
                HTML;
                break;
            
            case 1:
                echo <<<HTML
                    <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'$pays[0]'}" hx-trigger="load"></div>
                    <div hx-get="catalogue.php" hx-trigger="load" hx-select="#main"></div>
                HTML;
                break;
            
            case 0:
                echo <<<HTML
                    <div hx-get="catalogue.php" hx-trigger="load" hx-select="#main"></div>
                HTML;
                break;
        }

        $pays = json_encode($pays);
    ?>

    <div class="container-map">
        <div id="map"></div>
    </div>

    <div class="grille">
    
        <div class="sidebar">
            <div id="mini0"></div>

            <div id="mini1"></div>

            <div class="container-mini bg-52796F" style="width: 300px" hx-get="catalogue.php" hx-select="#main" hx-trigger="click">
                <div class="mini-bandeau"> 
                    <img id=plus class="flag-small" src='assets/img/plus.svg'>
                    <h2 class="nom-small">Afficher le catalogue</h2>
                </div>

            </div>

        </div>

        <div class="main" id="main" hx-swap="afterbegin">

            <div id="bandeau">
                <div id="bandeau0"></div>
                <div id="bandeau1"></div>
            </div>

            <div class="container-stats bg-52796F">
                <h2 id=t1>Scores EcoTourism</h1>
                <div class=score> 
                    <div class="score-box">A</div>
                    <div class="trait"></div>
                    <div class="score-box" style="background-color:#BB5C00">D</div>
                    
                </div>
            </div>
        

            <div class="container-stats bg-354F52">
                <h2 id=t1>Indicateurs clés</h1>
                <div class= "flex">
                    <div class=graph id="spider"></div>

                    <table>
                        <tr>
                            <td id="cell_6_1">Indicateur</td>
                            <td id="nom_0"></td>
                            <td id="nom_1"></td>
                        </tr>
                        <tr>
                            <td id="td_pib">PIB/Hab</td>
                            <td id="td_pib_0"></td>
                            <td id="td_pib_1"></td>
                        </tr>
                        <tr>
                            <td id="td_enr">% énergies renouvellables</td>
                            <td id="td_Enr_0"></td>
                            <td id="td_Enr_1"></td>
                        </tr>
                        <tr>
                            <td id="td_co2">Émissions de CO2</td>
                            <td id="td_co2_0"></td>
                            <td id="td_co2_1"></td>
                        </tr>
                        <tr>
                            <td id="td_arrivees">Arrivées toursitiques</td>
                            <td id="td_arrivees_0"></td>
                            <td id="td_arrivees_1"></td>
                        </tr>
                        <tr>
                            <td id="td_departs">Départs toursitiques</td>
                            <td id="td_departs_0"></td>
                            <td id="td_departs_1"></td>
                        </tr>
                        <tr>
                            <td id="td_gpi">Indice de paix</td>
                            <td id="td_gpi_0"></td>
                            <td id="td_gpi_1"></td>
                        </tr>
                        <tr>
                            <td id="td_cpi">CPI</td>
                            <td id="td_cpi_0"></td>
                            <td id="td_cpi_1"></td>
                        </tr>
                    </table>

                </div>
            </div>

            <div class="container-stats bg-354F52">
                <h2 id=t1>Comparaison de chaque indicateur</h2>
                <div class=container-buttons>
                    <button onclick="changeVar('co2')" class=button-compare>Émissions de CO2</button>
                    <button onclick="changeVar('pib')" class=button-compare>PIB par habitant</button>
                    <button onclick="changeVar('gpi')" class=button-compare>Indice de paix</button>
                    <button onclick="changeVar('arrivees')" class=button-compare>Arrivées</button>
                    <button onclick="changeVar('departs')" class=button-compare>Départs</button>
                    <button onclick="changeVar('cpi')" class=button-compare>Consumer Price Index</button>
                    <button onclick="changeVar('Enr')" class=button-compare>% énergies renouvellables</button>
                </div>
                <div class= "flex">
                    <div class=p50>
                        
                        <p>Actuellement le [pays 1] est au dessus du [pays 2], montrant que [pays 1] pollue plus que [pays 2]. Au cours du temps on peut voir que le tourisme ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. Curabitur et felis felis. Donec vel nulla malesuada, tempor nisi in, faucibus nulla. Cras at ipsum tempor, rutrum sapien ut, auctor sapien.
                        Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. </p>
                        
                    </div>
                    
                    
                    <div class=graph id="chartdiv"></div>

                    
                </div>

            </div>

            <!-- <div class="trait-hori"></div> -->

            <div class="container-stats bg-354F52">
                <h2 id=t1>Croissance des indicateurs</h2>
                <div class= "flex">
                    <div class=graph id="bar"></div>
                    <p class=p50>Actuellement le [pays 1] est au dessus du [pays 2], montrant que [pays 1] pollue plus que [pays 2]. Au cours du temps on peut voir que le tourisme ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. Curabitur et felis felis. Donec vel nulla malesuada, tempor nisi in, faucibus nulla. Cras at ipsum tempor, rutrum sapien ut, auctor sapien.
                    Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. </p>
                    
                    
                </div>
            

            </div>
            
            <div class='container-double'>
                <div class="container-stats bg-52796F marg-r">
                    <h2 id=t1>Savez-vous ?</h2>
                    <div id="carousel-wrapper">
                
                        <div id="menu">
                            <div id="current-option">
                                <span id="current-option-text1" data-previous-text="" data-next-text=""></span>
                                <span id="current-option-text2" data-previous-text="" data-next-text=""></span>
                            </div>
                            <button id="previous-option"></button>
                            <button id="next-option"></button>
                        </div>
                        <div id="image"></div>
                    </div>
                </div>    

                <div class="container-stats bg-52796F marg-l">
                    <h2 id=t1>Définitions</h2>
                    
                </div>
                </div>    

            </div>
             
            

            <script src="scripts/js/carousel.js"></script>
        </div>
    
    </div>

    <script id=scripting>
        
        spider()
        createGraph()
        graphBar()
        createMapCompare(<?=$pays?>)

    </script>
    
</body>
</html>

