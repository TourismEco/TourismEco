<?php require_once "head.php"; ?>

<?php
$cur = getDB();
// unset($_SESSION["pays"]);
if (!isset($_SESSION["pays"])) {
    $_SESSION["pays"] = [];
}

if (isset($_GET["id_pays"])) {
    $_SESSION["pays"][0] = $_GET["id_pays"];
} else if (isset($_POST["id_pays"])) {
    $_SESSION["pays"][0] = $_POST["id_pays"];
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
    require_once "catalogue.php";
    exit();
}

?>

<body>

    <div class="window">

        <div id="zones">

            <div class="zone zone-presentation home-pays display" id="home">
                <div class="container-presentation expand-3" id="bandeau"></div>
                <div class="container-presentation" id="miniMap0"></div>
                <div class="container-presentation" id="score"></div>
                <div class="container-presentation" id="safe"></div>
                <div class="container-presentation expand-2" id="rankPays"></div>

                <div class="scroll">
                    <div class="scroll-buttons">
                        <div class="scroll-dot dot-active" data-index="0"></div>
                        <div class="scroll-dot" data-index="1"></div>
                        <div class="scroll-dot" data-index="2"></div>
                    </div>
                    <div class="container-scrollable" id="indices"></div>
                </div>

                <div class="scroll expand-3">
                    <div class="scroll-buttons">
                        <div class="scroll-dot dot-active" data-index="0"></div>
                        <div class="scroll-dot" data-index="1"></div>
                        <div class="scroll-dot" data-index="2"></div>
                        <div class="scroll-dot" data-index="3"></div>
                    </div>
                    <div class="container-scrollable" id="description"></div>
                </div>
            </div>

            <div class="zone zone-spider display" id="key" style="display: none;">
                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/lamp.svg'>
                    <div>
                        <h2>Indicateurs clés</h2>
                        <p>Ce <i>Spider Chart</i> représente la position du pays pour 7 variables, normalisées.</p>
                    </div>
                </div>

                <div class="graph" id="spider"></div>

                <div class="cube" id="cube-1">
                    <div class="el-cube">
                        <div id="td_idh_0"></div>
                        <div id="td_idh_grow" class="small"></div>
                    </div>
                    
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/idh.svg">
                        <div class="top">
                            <h3>IDH (Indice de Développement Humain)</h3>
                            <p>Indicateur composite mesurant le bien-être socio-économique des populations.</p>
                            <i></i>
                        </div>
                    </div>
                    

                    <div class="el-cube">
                        <div id="td_idh_rank"></div>
                        <div id="td_idh_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-2">
                    <div class="el-cube">
                        <div id="td_pibParHab_0"></div>
                        <div id="td_pibParHab_grow" class="small"></div>
                    </div>

                    <div class="tooltip">
                        <img class="icon" src="assets/icons/dollar.svg">
                        <div class="top">
                            <h3>PIB/HAB</h3>
                            <p>Moyenne de richesse produite par personne dans un pays.</p>
                            <i></i>
                        </div>
                    </div>

                    <div class="el-cube">
                        <div id="td_pibParHab_rank"></div>
                        <div id="td_pibParHab_rankEvol" class="small"></div>
                    </div>

                </div>

                <div class="cube" id="cube-3">
                    <div class="el-cube">
                        <div id="td_gpi_0"></div>
                        <div id="td_gpi_grow" class="small"></div>
                    </div>

                    <div class="tooltip">
                        <img class="icon" src="assets/icons/shield.svg">
                        <div class="top">
                            <h3>GPI</h3>
                            <p>Mesure comparative du niveau de paix dans différents pays.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <div id="td_gpi_rank"></div>
                        <div id="td_gpi_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-4">
                    <div class="el-cube">
                        <div id="td_elecRenew_0"></div>
                        <div id="td_elecRenew_grow" class="small"></div>
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
                        <div id="td_elecRenew_rank"></div>
                        <div id="td_elecRenew_rankEvol" class="small"></div>
                    </div>

                </div>

                <div class="cube" id="cube-5">
                    <div class="el-cube">
                        <div id="td_departs_0"></div>
                        <div id="td_departs_grow" class="small"></div>
                    </div>

                    <div class="tooltip">
                        <img class="icon" src="assets/icons/up.svg">
                        <div class="top">
                            <h3>Départ</h3>
                            <p>Nombre de visiteurs partant du pays donné.</p>
                            <i></i>
                        </div>
                    </div>
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

                    <div class="tooltip">
                        <img class="icon" src="assets/icons/cloud.svg">
                        <div class="top">
                            <h3>Emission de CO2</h3>
                            <p>kilo de dioxyde de carbone dans l'atmosphère par activités humaines.</p>
                            <i></i>
                        </div>
                    </div>

                    <div class="el-cube">
                        <div id="td_co2_rank"></div>
                        <div id="td_co2_rankEvol" class="small"></div>
                    </div>
                </div>

                <div class="cube" id="cube-7">
                    <div class="el-cube">
                        <div id="td_arriveesTotal_0"></div>
                        <div id="td_arriveesTotal_grow" class="small"></div>
                    </div>

                    <div class="tooltip">
                        <img class="icon" src="assets/icons/down.svg">
                        <div class="top">
                            <h3>Arrivées touristiques</h3>
                            <p>Nombre de visiteurs arrivant dans le pays donné.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <div id="td_arriveesTotal_rank"></div>
                        <div id="td_arriveesTotal_rankEvol" class="small"></div>
                    </div>
                </div>
            </div>

            <div class="zone zone-basic display" id="courbe" style="display: none;">

                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/sort.svg'>
                    <div>
                        <h2 id="paysvs"></h2>
                        <p>Ce graphique compare l'évolution entre le pays France et la moyenne mondial au fil des années sur la statistiques que vous souhaitez.</p>
                    </div>
                </div>

                <div class="graph" id="line"></div>

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

            <div class="zone zone-basic display" id="barl" style="display:none">
                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/stats.svg'>
                    <div>
                        <h2>PIB vs Tourisme</h2>
                        <p>Ce graphique compare l'évolution entre le PIB Par Habitant et les Arrivées totales dans le pays au fil des années.</p>
                    </div>
                </div>

                <div class="graph" id="barreLine"></div>
                <div class="table">
                    <div class="legende">
                        <div class="icon_name legende-element">
                            <img src="assets/icons/bar-black.svg" class="square">
                            <p class="name" id="icon_name"> PIB Par Habitant</p>
                        </div>
                      
                        <div class="legende-element">
                            <img src="assets/icons/courbe.svg" class="square">
                            <p class ="name"> Arrivée totale dans le pays</p>
                        </div>
                    </div>

                    <div class="container-info">
                        <p>Minimum tourisme atteint en</p>
                        <p id="minTourisme1" class="big">-</p>
                        <p id="minTourisme_detail1">-</p>
                    </div>

                    <div class="container-info">
                        <p>Maximum tourisme atteint en</p>
                        <p id="maxTourisme1" class="big">-</p>
                        <p id="maxTourisme_detail1">-</p>
                    </div>

                    <div class="container-info">
                        <p>Minimum PIB atteint en</p>
                        <p id="minPib1" class="big">-</p>
                        <p id="min_detail1">-</p>
                    </div>
                    <div class="container-info">
                        <p>Maximum PIB atteint en</p>
                        <p id="maxPib1" class="big">-</p>
                        <p id="max_detail1">-</p>
                    </div>
                    <div class="container-info">
                        <p>Impact du COVID sur le tourisme en 2020</p>
                        <p id="covidImpactTourisme1" class="big">-</p>
                    </div>
                    <div class="container-info">
                        <p>Impact du COVID sur le PIB en 2020</p>
                        <p id="covidImpactPib1" class="big">-</p>
                    </div>
                    
                </div>
            </div>

            <div class="zone zone-scores display" id="scores" style="display:none">

                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/plus.svg'>
                    <div>
                        <h2>Détail des scores</h2>
                        <p>Découvrez comment est décomposé les 4 scores TourismEco de ce pays.</p>
                    </div>
                </div>

                <div class="scores-column">
                    <div class="container-scores" id="sco-pibParHab"></div>
                    <div class="container-scores" id="sco-idh"></div>
                    <div class="container-scores" id="sco-gpi"></div>
                </div>

                <div class="scores-column scores-center">
                    <div class="choice-scores">
                        <div class="border-scores border-A score-active" id="scoreGlobal"></div>
                        <div class="border-scores border-E" id="scoreDecouverte"></div>
                        <div class="border-scores border-C" id="scoreEcologique"></div>
                        <div class="border-scores border-NA" id="scoreEconomique"></div>
                    </div>

                    <div id="bigScore" class="big-score score-A">
                        <div class="score-box" id="bigScore-letter">A</div>
                        <div id="bigScore-text">Score Global</div>
                    </div>
                    <div class="jauge-scores">
                        <div class="score-E"></div>
                        <div class="score-D"></div>
                        <div class="score-C"></div>
                        <div class="score-B"></div>
                        <div class="score-A"></div>
                    </div>
                    <div class="units-scores">
                        <div id="lim0">0</div>
                        <div id="lim1">0</div>
                        <div id="lim2">0</div>
                        <div id="lim3">0</div>
                        <div id="lim4">0</div>
                        <div id="lim5">1</div>
                    </div>

                    <div class="trait-scores" id="tscor"></div>
            
                    <div class="infos-scores score-active">
                        <img src="assets/icons/info.svg" class="score-B">
                        <p id="descipScore"></p>
                    </div>

                </div>
                <div class="scores-column">
                    <div class="container-scores" id="sco-elecRenew"></div>
                    <div class="container-scores" id="sco-gesHab"></div>
                    <div class="container-scores" id="sco-arriveesTotal"></div>
                </div>
            </div>
        </div>

        <div class="zone mask"></div>

        <div class="nav-bottom" id="nav-bot" hx-swap-oob="outerHTML">
            <div class="nav-categ" id="bn">
                <div class="pack-categ">
                    <div class="container-bottom active page" data-index="0" data-name="Statistiques" id="s-stats">
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div class="container-bottom page" data-index="1" data-name="Catalogue" id="s-catalogue" hx-get="catalogue.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-vals="js:{page:'pays'}" hx-swap="outerHTML swap:0.5s">
                        <span>Catalogue</span>
                        <img class="flag-small" src='assets/icons/catalogue.svg'>
                    </div>

                    <div id="trans-page" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-page" class="nav-text">Statistiques</div>
            </div>

            <div class="nav-categ" id="bu">

                <div class="pack-categ">
                    <img class="flag-small" id="flag-bot" src='assets/icons/question.svg'>
                </div>

                <div class="nav-trait"></div>

                <div class="pack-categ">
                    <div class="container-bottom active switch" data-switch="home" data-index="0" data-name="Présentation">
                        <span>Présentation</span>
                        <img class="flag-small" src='assets/icons/info.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="scores" data-index="1" data-name="Détail des scores">
                        <span>Détail des scores</span>
                        <img class="flag-small" src='assets/icons/plus.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="key" data-index="2" data-name="Indicateurs clés">
                        <span>Indicateurs clés</span>
                        <img class="flag-small" src='assets/icons/lamp.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="courbe" data-index="3" data-name="Courbes d'évolution">
                        <span>Courbes d'évolution</span>
                        <img class="flag-small" src='assets/icons/sort.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="barl" data-index="4" data-name="PIB et tourisme">
                        <span>PIB et tourisme</span>
                        <img class="flag-small" src='assets/icons/stats.svg'>
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

        <script id="orders" hx-swap-oob="outerHTML"></script>

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
            $("#nav-bot").css("transform","translateY(0)")

            var i = 0
            var sens = 1
            $("#scr").on("scroll", function() {
                el = document.getElementById("scr")
                h = el.clientHeight.toFixed(0)
                s = el.scrollTop

                console.log(h, s, s/h, s%h);
                f = false
                if (s > h*i && s < h*(i-1)) {
                    i++
                    f = true
                } else if (s < h*i && s > h*(i+1)) {
                    i--
                    f = true
                }

                if (f) {
                    if (i == 0) {
                        $(".scroll-dot").removeClass("dot-active")
                        $("#scrb0").addClass("dot-active")
                        console.log("0");
                    } else if (i == 2) {
                        $(".scroll-dot").removeClass("dot-active")
                        $("#scrb2").addClass("dot-active")
                        console.log("400");
                    } else if (i == 1) {
                        $(".scroll-dot").removeClass("dot-active")
                        $("#scrb1").addClass("dot-active")
                        console.log("200");
                    }
                }
                f = false

            })

            $(".scroll-dot").on("click", function() {
                nb = $(this).data("index")
                h = el.clientHeight.toFixed(0)
                document.getElementById('scr').scroll({top:h*nb,behavior:"smooth"})
            })

        </script>
        
        <div id="htmxing" hx-swap-oob="outerHTML">
            <div hx-get="scripts/htmx/getPays.php" hx-vals="js:{id_pays:'<?=$pays?>'}" hx-trigger="load"></div>
        </div>

    </div>

</body>
</html>