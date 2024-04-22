<?php require_once "head.php"; ?>

<?php
$cur = getDB();

// unset($_SESSION["pays"]);

$pays = [];
if (isset($_SESSION["pays"])) {
    foreach ($_SESSION["pays"] as $key => $id_pays) {
        // echo $_SESSION["incr"];
        $query = "SELECT * FROM pays WHERE id = :id_pays";
        $sth = $cur->prepare($query);
        $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
        $sth->execute();

        $ligne = $sth->fetch();
        if ($ligne) {
            $pays[] = $id_pays;
        }
    }
} else {
    $_SESSION["pays"] = [];
    $_SESSION["incr"] = 0;
}

if (count($pays) <= 1) {
    require_once "catalogue.php";
    exit();
}
?>

<body>
    <div class="window">

        <div id="zones">
            <div class="zone zone-presentation home-compare display" id="home">
                <div class="container-presentation expand-2" id="bandeau0"></div>
                <div class="container-presentation expand-2" id="bandeau1"></div>
                <div class="container-presentation score-home" id="score0"></div>
                <div class="container-presentation" id="miniMap0"></div>
                <div class="container-presentation score-home" id="score1"></div>
                <div class="container-presentation" id="miniMap1"></div>
                <div class="container-presentation expand-2" id="bestRank0"></div>
                <div class="container-presentation expand-2" id="bestRank1"></div>
            </div>

            <div class="zone zone-basic display" id="courbe" style="display:none">
                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/sort.svg'>
                    <div>
                        <h2>.. VS ..</h2>
                        <p>Ce graphique compare l'évolution entre .. et .. au fil des années sur la statistiques que vous souhaitez.</p>
                    </div>
                </div>

                <div class=graph id="line"></div>

                <div class=container-buttons>
                    <div class="var-swap">
                        <span>Émissions de CO2</span>
                        <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVar('co2')" data-name="Émissions de CO2">
                    </div>
                    <div class="var-swap">
                        <span>PIB/Habitant</span>
                        <img class="icon" src="assets/icons/dollar.svg" onclick="changeVar('pibParHab')" data-name="PIB/Habitant">
                    </div>
                    <div class="var-swap">
                        <span>Global Peace Index</span>
                        <img class="icon" src="assets/icons/shield.svg" onclick="changeVar('gpi')" data-name="Global Peace Index">
                    </div>
                    <div class="var-swap">
                        <span>Arrivées touristiques</span>
                        <img class="icon" src="assets/icons/down.svg" onclick="changeVar('arriveesTotal')" data-name="Arrivées touristiques">
                    </div>
                    <div class="var-swap">
                        <span>Départs</span>
                        <img class="icon" src="assets/icons/up.svg" onclick="changeVar('departs')" data-name="Départs">
                    </div>
                    <div class="var-swap">
                        <span>Indice de développement humain</span>
                        <img class="icon" src="assets/icons/idh.svg" onclick="changeVar('idh')" data-name="Indice de développement humain">
                    </div>
                    <div class="var-swap">
                        <span>% d'énergies renouvellables</span>
                        <img class="icon" src="assets/icons/elec.svg" onclick="changeVar('elecRenew')" data-name="% d'énergies renouvellables">
                    </div>
                </div>

                <div class="table">
                    <div class="legende">
                        <div class="legende-element">
                            <div class="square bg-52796F"></div>
                            <p class="name" id="nom0"></p>
                        </div>
                        <div class="icon_name legende-element">
                            <img src="assets/icons/courbe.svg" class="square">
                            <p class="name" id="icon_name">Émissions de CO2</p>
                        </div>
                        <div class="legende-element">
                            <p class="name" id="nom1"></p>
                            <div class="square bg-83A88B"></div>
                        </div>
                    </div>

                    <div class="container-info">
                        <p>Le pays est actuellement</p>
                        <p id="rank0" class="big">-</p>
                        <p>parmi tous les pays</p>
                    </div>
                    <div class="container-info">
                        <p>Le pays est actuellement</p>
                        <p id="rank1" class="big">-</p>
                        <p>parmi tous les pays</p>
                    </div>
                    <div class="container-info">
                        <p>Entre / et / , évolution de</p>
                        <p id="evol0" class="big">-</p>
                    </div>
                    <div class="container-info">
                        <p>Entre / et / , évolution de</p>
                        <p id="evol1" class="big">-</p>
                    </div>
                    <div class="container-info">
                        <p>Impact du COVID :</p>
                        <p id="covid0" class="big">-</p>
                    </div>
                    <div class="container-info">
                        <p>Impact du COVID :</p>
                        <p id="covid1" class="big">-</p>
                    </div>

                    <div class="container-info expand-2">
                        <p>Dernière valeur</p>
                        <p id="comp0" class="big">-</p>
                        <p id="comp_detail">-</p>
                    </div>
                </div>

            </div>

            <div class="zone zone-spider display" style="display:none" id="key">

                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/lamp.svg'>
                    <div>
                        <h2>Indicateurs clés</h2>
                        <p>Ce <i>Spider Chart</i> compare la position des deux pays pour 7 variables, normalisées.</p>
                    </div>
                </div>

                <div class="graph" id="spider"></div>
                <div></div>
                <div class="cube" id="cube-1">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_idh_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/idh.svg">
                        <div class="top">
                            <h3>IDH</h3>
                            <p>Indicateur composite mesurant le bien-être socio-économique des populations.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_idh_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-2">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_pibParHab_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/dollar.svg">
                        <div class="top">
                            <h3>PIB/Habitant</h3>
                            <p>Moyenne de richesse produite par personne dans un pays.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_pibParHab_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-3">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_gpi_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/shield.svg">
                        <div class="top">
                            <h3>Global Peace Index</h3>
                            <p>Mesure comparative du niveau de paix dans différents pays.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_gpi_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-4">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_elecRenew_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/elec.svg">
                        <div class="top">
                            <h3>Pourcentage d'energie renouvelable</h3>
                            <p>Part de l'énergie produite à partir de sources renouvelables dans l'ensemble.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_elecRenew_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-5">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_departs_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/up.svg">
                        <div class="top">
                            <h3>Départs touristiques</h3>
                            <p>Nombre de visiteurs partant du pays donné.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_departs_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-6">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_co2_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/cloud.svg">
                        <div class="top">
                            <h3>Emissions de CO2</h3>
                            <p>kilo de dioxyde de carbone dans l'atmosphère par activités humaines.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_co2_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-7">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_arriveesTotal_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/down.svg">
                        <div class="top">
                            <h3>Arrivées tourstiques</h3>
                            <p>Nombre de visiteurs arrivant dans le pays donné.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_arriveesTotal_1"></div>
                    </div>
                </div>

                <div class="legende-key">
                    <div class="legende-element">
                        <div class="square bg-52796F"></div>
                        <p id="nom0"></p> 
                    </div>
                    <div class="legende-element">
                        <div class="square bg-83A88B"></div>
                        <p id="nom1"></p>
                    </div>
                </div>

            </div>

            <div class="zone zone-bar display" style="display:none" id="grow">

                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/stats.svg'>
                    <div>
                        <h2>Croissances</h2>
                        <p>Ce graphique vous montre pour chaque année comment 5 variables croit ou décroit pour nos deux pays.</p>
                    </div>
                </div>

                <div class="graph" id="bar"></div>

                <div class="cubes-bar">
                    <div class="cube">
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag0">
                            <div id="bar_pibParHab_0"></div>
                        </div>
                        <div class="tooltip">
                            <img class="icon" src="assets/icons/dollar.svg">
                            <div class="top">
                                <h3>PIB</h3>
                            </div>
                        </div>
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag1">
                            <div id="bar_pibParHab_1"></div>
                        </div>
                    </div>

                    <div class="cube">
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag0">
                            <div id="bar_co2_0"></div>
                        </div>
                        <div class="tooltip">
                            <img class="icon" src="assets/icons/cloud.svg">
                            <div class="top">
                                <h3>CO2</h3>
                            </div>
                        </div>
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag1">
                            <div id="bar_co2_1"></div>
                        </div>
                    </div>

                    <div class="cube">
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag0">
                            <div id="bar_arriveesTotal_0"></div>
                        </div>
                        <div class="tooltip">
                            <img class="icon" src="assets/icons/down.svg">
                            <div class="top">
                                <h3>PIB</h3>
                            </div>
                        </div>
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag1">
                            <div id="bar_arriveesTotal_1"></div>
                        </div>
                    </div>

                    <div class="cube">
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag0">
                            <div id="bar_gpi_0"></div>
                        </div>
                        <div class="tooltip">
                            <img class="icon" src="assets/icons/shield.svg">
                            <div class="top">
                                <h3>GPI</h3>
                            </div>
                        </div>
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag1">
                            <div id="bar_gpi_1"></div>
                        </div>
                    </div>

                    <div class="cube">
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag0">
                            <div id="bar_idh_0"></div>
                        </div>
                        <div class="tooltip">
                            <img class="icon" src="assets/icons/idh.svg">
                            <div class="top">
                                <h3>IDH</h3>
                            </div>
                        </div>
                        <div class="el-cube">
                            <img class="flag-tiny" id="flag1">
                            <div id="bar_idh_1"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="zone mask"></div>

        <div class="nav-bottom" id="nav-bot" hx-swap-oob="outerHTML">
            <div class="nav-categ">
                <div class="pack-categ">
                    <div class="container-bottom active page" data-index="0" data-name="Statistiques" id="s-stats">
                        <span>Statistiques</span>
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div class="container-bottom page" data-index="1" data-name="Catalogue" id="s-catalogue" hx-get="catalogue.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-vals="js:{page:'comparateur'}" hx-swap="outerHTML swap:0.5s">
                        <span>Catalogue</span>
                        <img class="flag-small" src='assets/icons/catalogue.svg'>
                    </div>

                    <div id="trans-page" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-page" class="nav-text">Statistiques</div>
            </div>

            <div class="nav-categ">
                <div class="pack-categ">
                    <img class="flag-small" id="flag-bot0" src='assets/icons/question.svg'>
                    <img class="flag-small" id="flag-bot1" src='assets/icons/question.svg'>
                </div>

                <div class="nav-trait"></div>

                <div class="pack-categ">
                    <div class="container-bottom active switch" data-switch="home" data-index="0" data-name="Présentation">
                        <span>Présentation</span>
                        <img class="flag-small" src='assets/icons/info.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="key" data-index="1" data-name="Indicateurs clés">
                        <span>Indicateurs clés</span>
                        <img class="flag-small" src='assets/icons/lamp.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="courbe" data-index="2" data-name="Courbes d'évolution">
                        <span>Courbes d'évolution</span>
                        <img class="flag-small" src='assets/icons/sort.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="grow" data-index="3" data-name="Croissances">
                        <span>Croissances</span>
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div id="trans" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-switch" class="nav-text">Présentation</div>
            </div>
        </div>

        <script id="scripting" hx-swap-oob="outerHTML">
            spider("spider",2)
            lineCompare("line")
            barCompare("bar")

            createMiniMap(0,"comparateur")
            createMiniMap(1,"comparateur")
        </script>

        <script id="orders" hx-swap-oob="outerHTML">
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
                nb = $(this).data("index")*getMulti()
                $("#trans").css("transform","translateX("+nb+"px)")
                $("#name-switch").html($(this).data("name"))
            })

            $(".page").removeClass("active")
            $("#s-stats").addClass("active")
            $("#name-page").text("Comparateur");

            $("#trans-page").css("transform","translateX(0)")
            $("#nav-bot").css("transform","translateY(0)")

        </script>

        <div id="htmxing" hx-swap-oob="outerHTML">
            <?php echo <<<HTML
                <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'$pays[0]',load:true}" hx-trigger="load"></div>
                <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:1,id_pays:'$pays[1]',load:true}" hx-trigger="load"></div>
            HTML; ?>
        </div>

    </div>

</body>
</html>
