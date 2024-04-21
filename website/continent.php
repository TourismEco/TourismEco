<?php require_once 'head.php'?>

<body>
    <div class="window">

        <div id="zones">
            <div class="zone zone-presentation display" id="home">
                <div class="container-presentation expand-3" id="bandeau0"></div>
                <div class="expand-2 expandrow-2" id="map"></div>
                <div class="graph" id="graph_pie"></div>
                <div class="container-presentation expandrow-2">
                    <div class='container-explore-continent' id="explore">
                        <p class="info-explore">Selectionnez un pays sur la carte ou depuis la barre de recherche pour consulter ses informations</p>
                    </div>
                </div>
                <div class='container-cartePays expandrow-2'  id="podium">
                    <div class="container-classement" id="rank">
                        
                    </div>
                </div>            
            </div>

            <div class="zone zone-basic display" id="key" style="display: none;">
                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/bar.svg'>
                    <div>
                        <h2 id="paysvs"></h2>
                        <p>Ce graphique compare l'évolution du maximum et minimum du continent et sa moyenne au fil des années sur la statistiques que vous souhaitez.</p>
                    </div>
                </div>
                <div class="graph" id="barreContinent"></div>

                <div class=container-buttons>
                    <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVarContinent('co2')" data-name="Émissions de CO2">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVarContinent('pibParHab')" data-name="PIB/Habitant">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVarContinent('gpi')" data-name="Global Peace Index">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVarContinent('arriveesTotal')" data-name="Arrivées touristiques">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVarContinent('departs')" data-name="Départs">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVarContinent('cpi')" data-name="CPI">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVarContinent('elecRenew')" data-name="% d'énergies renouvellables">
                </div>

                <div class="table">
                    <div class="legende">
                        <div class="icon_name legende-element">
                            <img src="assets/icons/courbe.svg" class="square">
                            <p class="name" id="icon_name">Émissions de CO2</p>
                        </div>
                    </div>

                    <div class="container-info">
                        <p>La moyenne</p>
                        <p id="avg0" class="big">-</p>
                        <p id="avg_detail">-</p>
                    </div>

                    <div class="container-info">
                        <p>Le pays médian médiane</p>
                        <p id="med0" class="big">-</p>
                        <p id="med_detail">-</p>
                    </div>
                    <div class="container-info">
                        <p>Minimum atteint en</p>
                        <p id="min0" class="big">-</p>
                        <p id="min_detail">-</p>
                    </div>
                    <div class="container-info">
                        <p>Maximum atteint en</p>
                        <p id="max0" class="big">-</p>
                        <p id="max_detail">-</p>
                    </div>
                    
                    
                </div>
            </div>

            <div class="zone zone-basic display" id="courbe" style="display: none;">
                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/scatter-white.svg'>
                    <div>
                        <h2 id="paysvs"></h2>
                        <p>Ce graphique compare l'évolution du maximum et minimum du continent et sa moyenne au fil des années sur la statistiques que vous souhaitez.</p>
                    </div>
                </div>
                <div class="graph" id="scatter"></div>
            </div>

            <div class="zone  zone-basic display" id="barl" style="display:none">
                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/sort.svg'>
                    <div>
                        <h2 id="paysvs"></h2>
                        <p>Ce graphique compare l'évolution du maximum et minimum du continent et sa moyenne au fil des années sur la statistiques que vous souhaitez.</p>
                    </div>
                </div>
                
                <div class="graph" id="line"></div>

                <div class=container-buttons>
                    <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVarL('co2')" data-name="Émissions de CO2">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVarL('pibParHab')" data-name="PIB/Habitant">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVarL('gpi')" data-name="Global Peace Index">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVarL('arriveesTotal')" data-name="Arrivées touristiques">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVarL('departs')" data-name="Départs">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVarL('cpi')" data-name="CPI">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVarL('elecRenew')" data-name="% d'énergies renouvellables">
                </div>

                <div class="table">
                    <div class="legende">
                        <div class="icon_name legende-element">
                            <img src="assets/icons/courbe.svg" class="square">
                            <p class="name" id="icon_name2">Émissions de CO2</p>
                        </div>
                        <div class="legende-element">
                            <div class="square bg-52796F"></div>
                            <p class ="name">Moyenne</p>
                            
                        </div>

                        <div class="legende-element">
                            <div class="square bg-83A88B"></div>
                            <p class="name" id="nom0"></p>
                        </div>
                    </div>
                    <div class="container-info">
                        <p>Maximum atteint pour</p>
                        <p id="maxLine" class="big">-</p>
                        <p id="maxLine_detail">-</p>
                    </div>

                    <div class="container-info">
                        <p>Evolution du Maximum</p>
                        <p id="maxEvol" class="big">-</p>
                        <p id="maxEvol_detail">-</p>
                    </div>

                    <div class="container-info">
                        <p>Minimum atteint pour</p>
                        <p id="minLine" class="big">-</p>
                        <p id="minLine_detail">-</p>
                    </div>

                    <div class="container-info">
                        <p>Evolution du Minimum</p>
                        <p id="minEvol" class="big">-</p>
                        <p id="minEvol_detail">-</p>
                    </div>
                    
                </div>
            </div>

            <?php
                $cur = getDB();

                if (!isset($_SESSION["pays"])) {
                    $_SESSION["pays"] = array();
                }

                if (isset($_GET["id_pays"])) {
                    $_SESSION["pays"][0] = $_GET["id_pays"];
                }

                $pays = "";
                if (count($_SESSION["pays"]) != 0) {
                    $query = "SELECT * FROM pays WHERE id = :id_pays";
                    $sth = $cur->prepare($query);
                    $sth->bindParam(":id_pays", $_SESSION["pays"][0], PDO::PARAM_STR);
                    $sth->execute();

                    $ligne = $sth->fetch();
                    if ($ligne) {
                        $pays = $_SESSION["pays"][0];
                    }
                }

            ?>
        

        </div>

        <div class="zone mask"></div>

        <div class="nav-bottom" id="nav-bot" hx-swap-oob="outerHTML">
            <div class="nav-categ">
            <div class="pack-categ">
                    <div class="container-bottom active page" data-switch="europe" data-id_continent="5" data-index="0" data-name="Europe" hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:5}" hx-swap="beforeend">
                        <span>Europe</span>
                        <img class="flag-small" src='assets/icons/europe.svg'>
                    </div>

                    <div class="container-bottom page" data-switch="africa" data-id_continent="1" data-index="1" data-name="Afrique" hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:1}" hx-swap="beforeend">
                        <span>Afrique</span>
                        <img class="flag-small" src='assets/icons/afrique.svg'>
                    </div>

                    <div class="container-bottom page" data-switch="america" data-id_continent="2" data-index="2" data-name="Amérique" hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:2}" hx-swap="beforeend">
                        <span>Amérique</span>
                        <img class="flag-small" src='assets/icons/amerique.svg'>
                    </div>

                    <div class="container-bottom page" data-switch="asia" data-id_continent="4" data-index="3" data-name="Asie" hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:4}" hx-swap="beforeend">
                        <span>Asie</span>
                        <img class="flag-small" src='assets/icons/asie.svg'>
                    </div>

                    <div class="container-bottom page" data-switch="oceania" data-id_continent="6" data-index="4" data-name="Océanie" hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:6}" hx-swap="beforeend">
                        <span>Océanie</span>
                        <img class="flag-small" src='assets/icons/oceanie.svg'>
                    </div>

                    <div id="trans-page" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-page" class="nav-text">Statistiques</div>
            </div>

            <div class="nav-categ">

                <div class="pack-categ">
                    <div class="container-bottom active switch" data-switch="home" data-index="0" data-name="Présentation">
                        <img class="flag-small" src='assets/icons/info.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="key" data-index="1" data-name="Indicateurs clés">
                        <img class="flag-small" src='assets/icons/bar.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="courbe" data-index="2" data-name="Courbes d'évolution">
                        <img class="flag-small" src='assets/icons/scatter-white.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="barl" data-index="3" data-name="PIB et tourisme">
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="more" data-index="4" data-name="Informations complémentaires">
                        <img class="flag-small" src='assets/icons/plus.svg'>
                    </div>

                    <div id="trans" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-switch" class="nav-text">Présentation</div>
            </div>
        </div>
            

            <script id="scripting" hx-swap-oob="outerHTML">
                createMapCatalogue("continent")
                halfpie("graph_pie")
                barreContinent("barreContinent")
                line("line")
                scatterplotContinent("scatter") 
            </script>
            <script id="orders" hx-swap-oob="outerHTML"></script>

            <div id="htmxing" hx-swap-oob="outerHTML">
                <div hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:5}" hx-trigger="load delay:.1s"></div>
            </div>

            <script id="behave" hx-swap-oob="outerHTML">
                $(".icon").on("click", function () {
                    $(".icon-active").removeClass("icon-active")
                    $(this).addClass("icon-active")
                    $("#icon_name").text($(this).data("name"));
                })

                $(".icon").on("click", function () {
                    $(".icon-active").removeClass("icon-active")
                    $(this).addClass("icon-active")
                    $("#icon_name2").text($(this).data("name"));
                })
                $(".switch").on("click", function () {
                    $(".switch").removeClass("active")
                    $(this).addClass("active")
                    $(".display").css("display","none")

                    $("#"+$(this).data("switch")).css("display","grid")
                    nb = $(this).data("index")*53
                    $("#trans").css("transform","translateX("+nb+"px)")
                    $("#name-switch").html($(this).data("name"))
                })

                $(".page").removeClass("active")
                $("#s-stats").addClass("active")
                $("#name-page").text("Statistiques");

                nb = 0
                $("#trans-page").css("transform","translateX("+nb+"px)")
                
            </script>

    </div>

</body>
</html>