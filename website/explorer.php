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
                    <img class="icon icon-active" src="assets/icons/score.svg" onclick="changeVarExplorer('score')" data-name="Score">
                    <img class="icon" src="assets/icons/cloud.svg" onclick="changeVarExplorer('co2')" data-name="Émissions de CO2">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVarExplorer('pibParHab')" data-name="PIB/Habitant">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVarExplorer('gpi')" data-name="Global Peace Index">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVarExplorer('arriveesTotal')" data-name="Arrivées touristiques">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVarExplorer('departs')" data-name="Départs">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVarExplorer('cpi')" data-name="CPI">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVarExplorer('elecRenew')" data-name="% d'énergies renouvellables">
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
                    <input type="text" class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue(), page:"explorer", id_continent:0}' hx-target="#search" hx-swap="outerHTML">
                    <div id=search></div>

                    <div class="trait-vertical"></div>

                    <div class="container-explore" id="pays">
                        <p class="info-explore">Selectionnez un pays sur la carte ou depuis la barre de recherche pour consulter ses informations</p>
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
                    <div class="container-classement" id="rank">
                    </div>

                    <div class="trait-vertical"></div>

                    <div id="rank_pays">
                    <div class ="classement other">
                        <div class="otherclassement">?</div>
                        <div class="classement-pays black">Sélectionnez un pays</div>
                        <div class="value">-/-</div>
                    </div>
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

                    <div class="container-bottom page" id="s-catalogue" data-name="Catalogue" hx-get="catalogue.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-vals="js:{page:'pays'}" hx-swap="outerHTML swap:0.5s">
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
                        <img class="flag-small" src='assets/icons/rank.svg'>
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
            var typeC = "score";
            var id_pays = null;

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

</body>
</html>