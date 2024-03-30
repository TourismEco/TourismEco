<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte</title>
    
    <?php
        require("functions.php");
        require('head.php');
        $cur = getDB();
        $dataMap = json_encode(dataExplorer($cur),JSON_NUMERIC_CHECK);
    ?>

</head>

<body>
    <?php
        $page ="pays";
        $cur = getDB();

        $pays = "";
        if (isset($_SESSION["pays"]) && count($_SESSION["pays"]) != 0) {
            $query = "SELECT * FROM pays WHERE id = :id_pays";
            $sth = $cur->prepare($query);
            $sth->bindParam(":id_pays", $_SESSION["pays"][0], PDO::PARAM_STR);
            $sth->execute();

            $ligne = $sth->fetch();
            if ($ligne) {
                $pays = $_SESSION["pays"][0];
            }
        } else {
            $_SESSION["pays"] = array();
        }
        
    ?>
     <div class="flex" id="main">
        <div class="zone-carte" id="zones">
            <div class="map-carte">
                <div id="map"></div>
                <div class="container-buttons">
                    <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVarMap('co2')" data-name="Émissions de CO2">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVarMap('pibParHab')" data-name="PIB/Habitant">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVarMap('gpi')" data-name="Global Peace Index">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVarMap('arriveesTotal')" data-name="Arrivées touristiques">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVarMap('departs')" data-name="Départs">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVarMap('cpi')" data-name="CPI">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVarMap('elecRenew')" data-name="% d'énergies renouvellables">
                </div>
            </div>

            <script>
                function getSearchValue() {
                    var s = document.getElementById("txt")
                    return s.value
                }

            </script>

            <div class="zone-cartePays">
                <div class='container-cartePays display' id="explore">
                    <input type="text" class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue(), page:"pays"}' hx-target="#search" hx-swap="outerHTML">
                    <div id=search></div>
                    <div class="container-explore">
                        <div class="explore-bandeau" id="bandeau"></div>
                        <div class="explore-prix" id="prix"></div>
                        <div class="explore-score" id="score" ></div>
                        <div class="explore-rang" id="rang"></div>
                        <div class="explore-describ" id="describ"></div>
                        <div class="explore-more" id="more"></div>
                    </div>
                </div>
        

                <div class='.container-cartePays display' style="display:none" id="fav">
                    <h3 class="title-section">Vos favoris</h3>
                    <div class="container-carte">
                                                <?php
                            $queryPays = "SELECT * FROM pays ORDER BY score DESC LIMIT 10";
                            $resultPays = $cur->query($queryPays);

                            while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
                            }
                        ?>
                    </div>
                </div>

                <div class='container-cartePays display' style="display:none" id="podium">
                    <h3 class="title-section">Classement</h3>
                    <div class="container-classement">
                        <div class ="classement first">
                            <div class="premier">1</div>
                            <div class="classement-pays"> France</div>
                            <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement first">
                            <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first first">
                        </div>
                        <div class ="classement second">
                            <div class="deuxieme">2</div>
                            <div class="classement-pays"> France</div>
                            <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement second">
                            <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first second">
                        </div>
                        <div class ="classement third">
                            <div class="troisieme">3</div>
                            <div class="classement-pays"> France</div>
                            <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement third">
                            <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first third">
                        </div>
                        <div class ="classement fourth">
                            <div class="quatrieme">4</div>
                            <div class="classement-pays"> France</div>
                            <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement fourth">
                            <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first fourth">
                        </div>
                        <div class ="otherDiv">
                            <div class="classement">
                                <div class="otherclassement">5</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                            </div>
                            <div class="classement">
                                <div class="otherclassement">6</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                            </div>
                        </div>
                        <div class ="otherDiv">
                            <div class="classement">
                                <div class="otherclassement">7</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                            </div>
                            <div class="classement">
                                <div class="otherclassement">8</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                            </div>
                        </div>
                        <div class ="otherDiv">
                            <div class="classement">
                                <div class="otherclassement">9</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                            </div>
                            <div class="classement">
                                <div class="otherclassement">10</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                            </div>
                        </div>
                    </div>
                </div>

                <div class='container-cartePays display' style="display:none" id="historical">
                    <h3 class="title-section">Historique</h3>
                    <div class="container-carte">
                    </div>
                </div>

                <div class='container-cartePays display' style="display:none" id="alea">
                    <h3 class="title-section">Découvrir</h3>
                    <div class="container-carte">
                    </div>
                </div>
                
            </div>
        </div>

        <div class="zone mask"></div>

        <div class="nav-bottom" id="nav-bot" hx-swap-oob="outerHTML">
            <div class="nav-categ">
                <div class="pack-categ">
                    <?php
                        if ($page == "pays") {
                            echo <<<HTML
                                <div class="container-bottom page active" id="s-stats" hx-get="pays.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-swap="outerHTML swap:0.5s" data-name="Statistiques">
                                    <img class="flag-small" src='assets/icons/stats.svg'>
                                </div>
                            HTML;
                        } else {
                            echo <<<HTML
                                <div class="container-bottom page active" id="s-stats" hx-get="comparateur.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-swap="outerHTML swap:0.5s" data-name="Comparateur">
                                    <img class="flag-small" src='assets/icons/stats.svg'>
                                </div>
                            HTML;
                        }
                    ?>
                    
                    <div class="container-bottom page" id="s-explorer" data-name="Explorateur">
                        <img class="flag-small" src='assets/icons/map.svg'>
                    </div>

                    <div class="container-bottom page" id="s-catalogue" data-name="Catalogue">
                        <img class="flag-small" src='assets/icons/catalogue.svg'>
                    </div>

                    <div id="trans-page" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-page" class="nav-text">Explorateur</div>
            </div>

            <div class="nav-categ">
                <div class="pack-categ">
                    <?php
                        if ($page == "pays") {
                            $incr = 0;
                            
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
                        } else {
                            $incr = count($_SESSION["pays"])%2;
                            $active0 = "";
                            $active1 = "";
                            if ($incr == 0) {
                                $active0 = "active";
                            } else {
                                $active1 = "active";
                            }

                            if (isset($_SESSION["pays"][0])) {
                                $url = "twemoji/".$_SESSION["pays"][0];
                            } else {
                                $url = "icons/question";
                            }
                            echo <<<HTML
                                <div class="container-bottom switch-compare $active0" id="fb0" data-incr="0" style="filter: none;">
                                    <img class="flag-small" id="flag-bot0" src='assets/$url.svg'>
                                </div>
                            HTML;

                            if (isset($_SESSION["pays"][1])) {
                                $url = "twemoji/".$_SESSION["pays"][1];
                            } else {
                                $url = "icons/question";
                            }
                            echo <<<HTML
                                <div class="container-bottom switch-compare $active1" id="fb1" data-incr="1" style="filter: none;">
                                    <img class="flag-small" id="flag-bot1" src='assets/$url.svg'>
                                </div>
                            HTML;

                            echo <<<HTML
                                <div id="trans-compare" class="active-bg bg-compare"><img id="compare-switch" src="assets/icons/switch.svg"></div>
                            HTML;
                        }
                    ?>

                </div>
                
                <div class="nav-trait"></div>

                <div class="pack-categ">
                    <div class="container-bottom active switch" data-switch="explore" data-index="0" data-name="Explorer">
                        <img class="flag-small" src='assets/icons/map.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="fav" data-index="1" data-name="Pour vous">
                        <img class="flag-small" src='assets/icons/heart.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="podium" data-index="2" data-name="Classements">
                        <img class="flag-small" src='assets/icons/podium.svg'>
                    </div>

                    <div id="trans" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-switch" class="nav-text">Explorer</div>
            </div>
        </div>

        <script id="scripting" hx-swap-oob="outerHTML">
            createMapExplorer()
            map.addHeat(<?=$dataMap?>)
        </script>

        <script id="behave" hx-swap-oob="outerHTML">
            var incr = <?=$incr?>;

            $(".icon").on("click", function () {
                $(".icon-active").removeClass("icon-active")
                $(this).addClass("icon-active")
                $("#icon_name").text($(this).data("name"));
            })

            $(".switch").on("click", function () {
                $(".switch").removeClass("active")
                $(this).addClass("active")

                $(".display").css("display","none")
                $("#"+$(this).data("switch")).css("display","flex")

                $("#txt").val("")
                $("#search").empty()

                nb = $(this).data("index")*53
                $("#trans").css("transform","translateX("+nb+"px)")
                $("#name-switch").html($(this).data("name"))

                id_continent = $(this).data("id_continent")
            })

            $(".switch-compare").on("click", function () {
                $(".switch-compare").removeClass("active")
                $(this).addClass("active")
                nb = $(this).data("incr")*53
                $("#trans-compare").css("transform","translateX("+nb+"px)")
                incr = $(this).data("incr")
            })

            $(".page").removeClass("active")
            $("#s-explorer").addClass("active")
            $("#name-page").text("Explorer");

            nb = 53
            $("#trans-page").css("transform","translateX("+nb+"px)")
            $("#trans-compare").css("transform","translateX("+(53*incr)+"px)")
            $("#nav-bot").css("transform","translateY(0)")

            console.log(<?=$dataMap?>)
        </script>

        <script id="orders" hx-swap-oob="outerHTML"></script>

    </div>

<div hx-get="scripts/htmx/getExplore.php" hx-vals="js:{id_pays: 'FR'}" hx-trigger="load delay:.1s"></div>
<!-- <div hx-get="scripts/htmx/getClassement.php" hx-vals="js:{var:'arriveesTotal'}" hx-trigger="load delay:.1s"></div> -->


</body>
</html>