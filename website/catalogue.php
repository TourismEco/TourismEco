<?php require_once 'head.php'?>

<body>

    <div class="flex">

        <div class="zone-catalogue" id="zones" hx-swap="swap:0.5s">
            <?php 
                $cur = getDB();
                $page = $_GET["page"];
            ?>

            <div class="map-catalogue" id="map"></div>

            <script>
                function getSearchValue() {
                    var s = document.getElementById("txt")
                    return s.value
                }

                function getIdContinent() {
                    return id_continent
                }

                function getIncr() {
                    return incr
                }

                createMapCatalogue("<?=$page?>")
            </script>

            <div class="zone-cataloguePays">
                
                <input class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue(), page:"pays",id_continent: getIdContinent()}' hx-target="#search" hx-swap="outerHTML">
                <div id=search>

                </div>

                <div class="trait-vertical"></div>

                <div class='container-continents display' id="europe">
                        <?php

                            $queryPays = "SELECT * FROM pays WHERE id_continent = 5 ORDER BY score DESC LIMIT 12";
                            $resultPays = $cur->query($queryPays);

                            while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
                            }

                            echo <<<HTML
                                <div class="container-slim bg-52796F cursor" hx-get="scripts/htmx/more.php" hx-vals="js:{continent:'5',more:1,page:'$page'}" hx-swap="outerHTML">
                                    <div class="bandeau-slim">
                                        <h2 class="nom-region">Voir plus</h2>
                                    </div>
                                </div>
                            HTML;
                        ?>
                </div>

                <div class='container-continents display' style="display:none" id="asia">
                        <?php
                            $queryPays = "SELECT * FROM pays WHERE id_continent = 4 ORDER BY score DESC LIMIT 12";
                            $resultPays = $cur->query($queryPays);

                            while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
                            }

                            echo <<<HTML
                                <div class="container-slim bg-52796F cursor" hx-get="scripts/htmx/more.php" hx-vals="js:{continent:'4',more:1,page:'$page'}" hx-swap="outerHTML">
                                    <div class="bandeau-slim">
                                        <h2 class="nom-region">Voir plus</h2>
                                    </div>
                                </div>
                            HTML;
                        ?>
                </div>

                <div class='container-continents display' style="display:none" id="africa">
                        <?php
                            $queryPays = "SELECT * FROM pays WHERE id_continent = 1 ORDER BY score DESC LIMIT 12";
                            $resultPays = $cur->query($queryPays);

                            while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
                            }

                            echo <<<HTML
                                <div class="container-slim bg-52796F cursor" hx-get="scripts/htmx/more.php" hx-vals="js:{continent:'1',more:1,page:'$page'}" hx-swap="outerHTML">
                                    <div class="bandeau-slim">
                                        <h2 class="nom-region">Voir plus</h2>
                                    </div>
                                </div>
                            HTML;
                        ?>
                </div>

                <div class='container-continents display' style="display:none" id="amerique">
                        <?php
                            $queryPays = "SELECT * FROM pays WHERE id_continent = 3 OR id_continent = 2 ORDER BY score DESC LIMIT 12";
                            $resultPays = $cur->query($queryPays);

                            while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
                            }

                            echo <<<HTML
                                <div class="container-slim bg-52796F cursor" hx-get="scripts/htmx/more.php" hx-vals="js:{continent:'2',more:1,page:'$page'}" hx-swap="outerHTML">
                                    <div class="bandeau-slim">
                                        <h2 class="nom-region">Voir plus</h2>
                                    </div>
                                </div>
                            HTML;
                        ?>
                </div>

                <div class='container-continents display' style="display:none" id="oceania">
                        <?php
                            $queryPays = "SELECT * FROM pays WHERE id_continent = 6 ORDER BY score DESC LIMIT 12";
                            $resultPays = $cur->query($queryPays);

                            while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
                            }

                            echo <<<HTML
                                <div class="container-slim bg-52796F cursor" hx-get="scripts/htmx/more.php" hx-vals="js:{continent:'6',more:1,page:'$page'}" hx-swap="outerHTML">
                                    <div class="bandeau-slim">
                                        <h2 class="nom-region">Voir plus</h2>
                                    </div>
                                </div>
                            HTML;
                        ?>
                </div>
            </div>
        </div>

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

                <div id="name-page" class="nav-text">Catalogue</div>
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
                    <div class="container-bottom active switch" data-switch="europe" data-index="0" data-name="Europe">
                        <img class="flag-small" src='assets/icons/europe.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="africa" data-index="1" data-name="Afrique">
                        <img class="flag-small" src='assets/icons/afrique.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="america" data-index="2" data-name="Amérique">
                        <img class="flag-small" src='assets/icons/amerique.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="asia" data-index="3" data-name="Asie">
                        <img class="flag-small" src='assets/icons/asie.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="oceania" data-index="4" data-name="Océanie">
                        <img class="flag-small" src='assets/icons/oceanie.svg'>
                    </div>

                    <div id="trans" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-switch" class="nav-text">Europe</div>
            </div>
        </div>

        <script id="behave" hx-swap-oob="outerHTML">
            var id_continent = 5;
            var incr = <?=$incr?>;

            $(".switch").on("click", function () {
                $(".switch").removeClass("active")
                $(this).addClass("active")

                $(".display").css("display","none")

                $("#"+$(this).data("switch")).css("display","flex")

                $("#txt").val("")
                $("#search").empty()

                map.zoomToContinent($(this).data("switch"))

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
            $("#s-catalogue").addClass("active")
            $("#name-page").text("Catalogue");

            nb = 106
            $("#trans-page").css("transform","translateX("+nb+"px)")

            $("#trans-compare").css("transform","translateX("+(53*incr)+"px)")
        </script>

        <script id="scritping"></script>

    </div>

</body>