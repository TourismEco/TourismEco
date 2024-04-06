<?php require_once 'head.php'?>

<body>

    <div class="flex">

        <div class="zone-catalogue" id="zones" hx-swap="swap:0.5s">
            <?php 
                require_once "functions.php";
                if (isset($_GET["page"])) {
                    $page = $_GET["page"];
                } else {
                    $page = substr(explode(".",$_SERVER["REQUEST_URI"])[0],1);
                }

                $page = "pays";
    
                // if ($page != "pays" && $page != "comparateur") {
                //     header("HTTP/1.1 401");
                //     exit;
                // }

                $cur = getDB();
            ?>

            <div class="map-catalogue" id="map"></div>

            <script>
                createMapCatalogue("<?=$page?>")
            </script>

            <div class="zone-cataloguePays">
                
                <input class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("txt"), page:"pays",id_continent: getIdContinent()}' hx-target="#search" hx-swap="outerHTML">
                <div id=search>

                </div>

                <div class="trait-vertical"></div>

                <div class='container-continents' id="cata" hx-swap="swap:0.5s">
                        
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
                    
                    <div class="container-bottom page" id="s-explorer" data-name="Explorateur" hx-get="explorer.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-vals="js:{page:'pays'}" hx-swap="outerHTML swap:0.5s">
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
                    <div class="container-bottom active switch" data-switch="europe" data-id_continent="5" data-index="0" data-name="Europe" hx-get="scripts/htmx/getCatalogue.php" hx-vals="js:{id_continent:5,page:'<?=$page?>'}" hx-target="#cata" hx-select="#cata" hx-swap="outerHTML swap:0.5s">
                        <img class="flag-small" src='assets/icons/europe.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="africa" data-id_continent="1" data-index="1" data-name="Afrique" hx-get="scripts/htmx/getCatalogue.php" hx-vals="js:{id_continent:1,page:'<?=$page?>'}" hx-target="#cata" hx-select="#cata" hx-swap="outerHTML swap:0.5s">
                        <img class="flag-small" src='assets/icons/afrique.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="america" data-id_continent="2" data-index="2" data-name="Amérique" hx-get="scripts/htmx/getCatalogue.php" hx-vals="js:{id_continent:2,page:'<?=$page?>'}" hx-target="#cata" hx-select="#cata" hx-swap="outerHTML swap:0.5s">
                        <img class="flag-small" src='assets/icons/amerique.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="asia" data-id_continent="4" data-index="3" data-name="Asie" hx-get="scripts/htmx/getCatalogue.php" hx-vals="js:{id_continent:4,page:'<?=$page?>'}" hx-target="#cata" hx-select="#cata" hx-swap="outerHTML swap:0.5s">
                        <img class="flag-small" src='assets/icons/asie.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="oceania" data-id_continent="6" data-index="4" data-name="Océanie" hx-get="scripts/htmx/getCatalogue.php" hx-vals="js:{id_continent:6,page:'<?=$page?>'}" hx-target="#cata" hx-select="#cata" hx-swap="outerHTML swap:0.5s">
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
            $("#nav-bot").css("transform","translateY(0)")
        </script>

        <script id="scripting" hx-swap-oob="outerHTML"></script>
        <script id="orders" hx-swap-oob="outerHTML"></script>
        <div id="htmxing" hx-swap-oob="outerHTML">
            <div hx-get="scripts/htmx/getCatalogue.php" hx-vals="js:{id_continent:5,page:'<?=$page?>'}" hx-trigger="load" hx-target="#cata" hx-select="#cata" hx-swap="outerHTML swap:0.5s"></div>
        </div>

    </div>

</body>