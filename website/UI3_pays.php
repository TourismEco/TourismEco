<?php require_once 'head.php'?>

<body>
    <div class="flex" id="main">

        <div id="zones">
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
                        <div hx-get="UI3_catalogue.php" hx-trigger="load" hx-select="#zones" hx-target="#zones" hx-vals="js:{page:'pays'}" hx-swap="outerHTML swap:0.5s"></div>
                    HTML;
                } else {
                    echo <<<HTML
                        <div hx-get="scripts/htmx/getPays.php" hx-vals="js:{id_pays:'$pays'}" hx-trigger="load delay:.1s"></div>
                    HTML;
                }
            ?>
        </div>

        <div class="zone mask"></div>

        <div class="nav-bottom" id="nav-bot" hx-swap-oob="outerHTML">
            <div class="nav-categ" id="bn">
                <div class="pack-categ">
                    <div class="container-bottom active page" data-index="0" data-name="Statistiques" id="s-stats">
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div class="container-bottom page" data-index="1" data-name="Explorer" id="s-explore">
                        <img class="flag-small" src='assets/icons/map.svg'>
                    </div>

                    <div class="container-bottom page" data-index="2" data-name="Catalogue" id="s-catalogue" hx-get="UI3_catalogue.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-vals="js:{page:'pays'}" hx-swap="outerHTML swap:0.5s">
                        <img class="flag-small" src='assets/icons/catalogue.svg'>
                    </div>

                    <div id="trans-page" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-page" class="nav-text">Statistiques</div>
            </div>

            <div class="nav-categ" id="bu">

                <div class="pack-categ">
                    <?php
                        if (isset($_SESSION["pays"][0])) {
                            $id_pays = $_SESSION["pays"][0];
                            echo <<<HTML
                                <img class="flag-small" id="flag-bot" src='assets/twemoji/$id_pays.svg'>
                            HTML;
                        } else {
                            echo <<<HTML
                                <img class="flag-small" id="flag-bot" src='assets/icons/question.svg'>
                            HTML;
                        }
                    ?>
                </div>

                <div class="nav-trait"></div>

                <div class="pack-categ">
                    <div class="container-bottom active switch" data-switch="home" data-index="0" data-name="Présentation">
                        <img class="flag-small" src='assets/icons/info.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="key" data-index="1" data-name="Indicateurs clés">
                        <img class="flag-small" src='assets/icons/lamp.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="courbe" data-index="2" data-name="Courbes d'évolution">
                        <img class="flag-small" src='assets/icons/sort.svg'>
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
                createMiniMap(0,"pays")
                spider("spider",1)
                createLine("line")
                barreLine("barreLine")
            </script>

            <script id="behave" hx-swap-oob="outerHTML">
                $(".icon").on("click", function () {
                    $(".icon-active").removeClass("icon-active")
                    $(this).addClass("icon-active")
                    $("#icon_name").text($(this).data("name"));
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