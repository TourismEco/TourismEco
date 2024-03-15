<?php require_once 'head.php'?>

<body>
    <div class="flex">
        <div class="grid" id="grid">
            <div class="container-side bg-354F52 g1-1" id="mini0"></div>

            <div class="container-side bg-354F52 g4-1 active">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">Statistiques</h2>
            </div>

            <div class="container-side bg-354F52 g5-1">
                <img class="flag-small" src='assets/icons/map.svg'>
                <h2 class="nom-small">Exploration</h2>
            </div>

            <div class="container-side bg-354F52 g6-1" hx-get="UI3_catalogue.php" hx-select="#grid" hx-target="#grid" hx-trigger="click" hx-vals="js:{page:'Pays'}" hx-swap="outerHTML swap:0.5s">
                <img class="flag-small" src='assets/icons/catalogue.svg'>
                <h2 class="nom-small">Catalogue</h2>
            </div>

            <div class="zone-presentation display" id="home">
                <div class="container-presentation expand-3" id="bandeau0"></div>
                <div class="container-presentation" id="miniMap0"></div>
                <div class="container-presentation" id="score0"></div>

                <div class="container-presentation">
                    <div class="cost"><p class="big">60€</p><p>par jour</p></div>
                    <div class="trait"></div>
                    <div class="cost"><p class="big">420€</p><p>pour 7 jours</p></div>
                </div>

                <div class="container-presentation expand-2">
                </div>

                <div class="container-presentation">
                    <p class="paragraphe">La Tour Eiffel et ses Illuminations :<br>
                    La Tour Eiffel à Paris est l'un des monuments les plus emblématiques au monde. Une anecdote célèbre est que la Tour Eiffel est illuminée chaque nuit par des milliers de lumières scintillantes pendant les premières heures après le coucher du soleil. Cette pratique a débuté en 1985 pour célébrer le 100e anniversaire de la tour et est devenue une tradition appréciée.</p>
                </div>

                <div class="container-presentation expand-3" id="description0"></div>

            </div>

            <div class="zone-spider display" id="key" style="display: none;">
                <div class="graph" id="spider"></div>

                <div class="cube" id="cube-1">
                    <div class="el-cube">
                        <div id="td_cpi_0"></div>
                        <div id="td_cpi_grow" class="small"></div>
                    </div>
                    
                    <img class="icon" src="assets/icons/transfer.svg">
                       
                    <div class="el-cube">
                        <div id="td_cpi_rank"></div>
                        <div id="td_cpi_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-2">
                    <div class="el-cube">
                        <div id="td_pib_0"></div>
                        <div id="td_pib_grow" class="small"></div>
                    </div>
                    
                    <img class="icon" src="assets/icons/dollar.svg">
                       
                    <div class="el-cube">
                        <div id="td_pib_rank"></div>
                        <div id="td_pib_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-3">
                    <div class="el-cube">
                        <div id="td_gpi_0"></div>
                        <div id="td_gpi_grow" class="small"></div>
                    </div>
                    
                    <img class="icon" src="assets/icons/shield.svg">
                        
                    <div class="el-cube">
                        <div id="td_gpi_rank"></div>
                        <div id="td_gpi_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-4">
                    <div class="el-cube">
                        <div id="td_Enr_0"></div>
                        <div id="td_Enr_grow" class="small"></div>
                    </div>
                    
                    <img class="icon" src="assets/icons/leaf.svg">
                        
                    <div class="el-cube">
                        <div id="td_Enr_rank"></div>
                        <div id="td_Enr_rankEvol" class="small"></div>
                    </div>
                    
                </div>

                <div class="cube" id="cube-5">
                    <div class="el-cube">
                        <div id="td_departs_0"></div>
                        <div id="td_departs_grow" class="small"></div>
                    </div>
                    
                    <img class="icon" src="assets/icons/up.svg">
                        
                    <div class="el-cube">
                        <div id="td_departs_rank"></div>
                        <div id="td_departs_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-6">
                    <div class="el-cube">
                        <div id="td_co2_0"></div>
                        <div id="td_co2_grow" class="small"></div>
                    </div>
                    
                    <img class="icon" src="assets/icons/cloud.svg">
                        
                    <div class="el-cube">
                        <div id="td_co2_rank"></div>
                        <div id="td_co2_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-7">
                    <div class="el-cube">
                        <div id="td_arrivees_0"></div>
                        <div id="td_arrivees_grow" class="small"></div>
                    </div>
                    
                    <img class="icon" src="assets/icons/down.svg">
                        
                    <div class="el-cube">
                        <div id="td_arrivees_rank"></div>
                        <div id="td_arrivees_rankEvol" class="small"></div>
                    </div>
                </div>
            </div>

            <div class="zone display" id="courbe" style="display: none;">
                <div class="graph" id="line"></div>

                <div class=container-buttons>
                <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVar('co2')" data-name="Émissions de CO2">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVar('pib')" data-name="PIB/Habitant">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVar('gpi')" data-name="Global Peace Index">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVar('arrivees')" data-name="Arrivées touristiques">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVar('departs')" data-name="Départs">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVar('cpi')" data-name="CPI">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVar('Enr')" data-name="% d'énergies renouvellables">
                </div>

                <div class="table">
                    <div class="legende">
                        <div class="icon_name legende-element">
                            <img src="assets/icons/courbe.svg" class="square">
                            <p class="name" id="icon_name">Émissions de CO2</p>
                        </div>
                        <div class="legende-element">
                            <div class="square bg-52796F"></div>
                            <p class="name" id="nom0"></p>
                        </div>

                        <div class="legende-element">
                            <div class="square bg-83A88B"></div>
                            <p class ="name">Moyenne Mondiale</p>
                        </div>
                    </div>

                    <div class="container-info">
                        <p>Dernière valeur</p>
                        <p id="comp0" class="big">-</p>
                        <p id="comp_detail">-</p>
                    </div>

                    <div class="container-info">
                        <p>Le pays est actuellement</p>
                        <p id="rank0" class="big">-</p>
                        <p>parmi tous les pays</p>
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
                    <div class="container-info">
                        <p>Evolution de</p>
                        <p id="evol0" class="big">-</p>
                        <p id="evol_detail">-</p>
                    </div>
                    <div class="container-info">
                        <p>Impact du COVID en 2020</p>
                        <p id="covid0" class="big">-</p>
                    </div>
                    
                </div>
            </div>

            <div class="zone display" id="barl" style="display:none">
                <div class="graph" id="barreLine"></div>
            </div>

            <div class="container-bottom bg-354F52 switch g10-2 active" data-switch="home">
                <img class="flag-small" src='assets/icons/info.svg'>
                <h2 class="nom-small">Présentation</h2>
            </div>

            <div class="container-bottom bg-354F52 switch g10-3" data-switch="key">
                <img class="flag-small" src='assets/icons/lamp.svg'>
                <h2 class="nom-small">Indicateurs clés</h2>
            </div>

            <div class="container-bottom bg-354F52 switch g10-4" data-switch="courbe">
                <img class="flag-small" src='assets/icons/sort.svg'>
                <h2 class="nom-small">Courbe d'évolution</h2>
            </div>

            <div class="container-bottom bg-354F52 switch g10-5" data-switch="barl">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">PIB et Tourisme</h2>
            </div>

            <div class="container-bottom bg-354F52 switch g10-6" data-switch="more">
                <img class="flag-small" src='assets/icons/plus.svg'>
                <h2 class="nom-small">Informations complémentaires</h2>
            </div>

            <script id="scripting">
                createMiniMap(0,"pays")
                spider("spider",1)
                createLine("line")
                barreLine("barreLine")
            </script>

            <script>
                $(".icon").on("click", function () {
                    $(".icon-active").removeClass("icon-active")
                    $(this).addClass("icon-active")
                    $("#icon_name").text($(this).data("name"));
                })

                $(".switch").on("click", function () {
                    $(".switch").removeClass("active")
                    $(this).addClass("active")
                    $(".display").css("display","none")
                    console.log($(this).data("switch"))
                    $("#"+$(this).data("switch")).css("display","grid")
                })
            </script>

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

                if ($pays == "") {
                    echo <<<HTML
                        <div hx-get="catalogue.php" hx-trigger="load" hx-select="#grid" hx-target="#grid" hx-vals="js:{page:'Pays'}" hx-swap="outerHTML swap:0.5s"></div>
                    HTML;
                } else {
                    echo <<<HTML
                        <div hx-get="scripts/htmx/getPays.php" hx-vals="js:{id_pays:'$pays'}" hx-trigger="load delay:.1s"></div>
                    HTML;
                }
            ?>
        </div>

    </div>

</body>
</html>