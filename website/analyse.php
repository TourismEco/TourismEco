<?php require_once 'head.php'?>

<body>
    
    <div class="flex">

        <div id="zones">

            <div class="zone zone-double display" id="home"  style="display:none">
                <div class="container-presentation">
                    <div class="title-zone">
                        <img class="flag-small" src='assets/icons/analyseHome.svg'>
                        <div>
                            <h2>Analyse</h2>
                            <p>Découvrez comment est décomposé les 4 scores TourismEco de ce pays.</p>
                        </div>
                    </div>
                </div>
                <div class="zone-choix">
                    <div class="container-presentation">
                        <div class="title-zone">
                            <img class="flag-small" src='assets/icons/analyseArima.svg'>
                            <div>
                                <h2>ARIMA</h2>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation">
                        <div class="title-zone">
                            <img class="flag-small" src='assets/icons/analyseScore.svg'>
                            <div>
                                <h2>Score</h2>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation">
                        <div class="title-zone">
                            <img class="flag-small" src='assets/icons/analyseCluster.svg'>
                            <div>
                                <h2>Clustering</h2>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation">
                        <div class="title-zone">
                            <img class="flag-small" src='assets/icons/analyseClusterPlus.svg'>
                            <div>
                                <h2>Clustering+</h2>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation">
                        <div class="title-zone">
                            <img class="flag-small" src='assets/icons/stats.svg'>
                            <div>
                                <h2>Régression Linéaire</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="zone zone-triple display" id="score" style="display: none;">
                <div class="container-presentation expandrow-2"></div>
                <div class="container-presentation expandrow-2 expand-2"></div>
            </div>

            <div class="zone zone-cluster display" id="cluster" style="display: none;">

                <div class="container-presentation">
                    <div class="title-zone">
                        <img class="flag-small" src='assets/icons/analyseCluster.svg'>
                        <div>
                            <h2>Clustering</h2>
                        </div>
                    </div>
                </div>

                <div class="map-cluster" id="map"></div>
                

                <div class="zone-choix">
                    <div class="container-scores border-NA">
                        <div class="title-scores">
                            <img src="assets/icons/dollar.svg" class="score-NA">
                            <p>PIB/hab</p>
                        </div>

                        <div class="stats-scores">
                            <div class="stats-scores-minmax">
                                <p>Min<br>$min</p>
                                <div class="trait-small"></div>
                                <p>Max<br>$max</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-scores border-NA">
                        <div class="title-scores">
                            <img src="assets/icons/dollar.svg" class="score-NA">
                            <p>PIB/hab</p>
                        </div>

                        <div class="stats-scores">
                            <div class="stats-scores-minmax">
                                <p>Min<br>$min</p>
                                <div class="trait-small"></div>
                                <p>Max<br>$max</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-scores border-NA">
                        <div class="title-scores">
                            <img src="assets/icons/dollar.svg" class="score-NA">
                            <p>PIB/hab</p>
                        </div>

                        <div class="stats-scores">
                            <div class="stats-scores-minmax">
                                <p>Min<br>$min</p>
                                <div class="trait-small"></div>
                                <p>Max<br>$max</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-scores border-NA">
                        <div class="title-scores">
                            <img src="assets/icons/dollar.svg" class="score-NA">
                            <p>PIB/hab</p>
                        </div>

                        <div class="stats-scores">
                            <div class="stats-scores-minmax">
                                <p>Min<br>$min</p>
                                <div class="trait-small"></div>
                                <p>Max<br>$max</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-scores border-NA">
                        <div class="title-scores">
                            <img src="assets/icons/dollar.svg" class="score-NA">
                            <p>PIB/hab</p>
                        </div>

                        <div class="stats-scores">
                            <div class="stats-scores-minmax">
                                <p>Min<br>$min</p>
                                <div class="trait-small"></div>
                                <p>Max<br>$max</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-scores border-NA">
                        <div class="title-scores">
                            <img src="assets/icons/dollar.svg" class="score-NA">
                            <p>PIB/hab</p>
                        </div>

                        <div class="stats-scores">
                            <div class="stats-scores-minmax">
                                <p>Min<br>$min</p>
                                <div class="trait-small"></div>
                                <p>Max<br>$max</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="zone zone-basic display" id="clusterPlus" style="display:none">
                
            </div>

            <div class="zone display" id="arima">
                <div class="scroll-buttons scroll-down scroll-arima">
                    <img class="scroll-img" data-index="0" src="assets/icons/arimaHome">
                    <img class="scroll-img" data-index="1" src="assets/icons/arimaTemp">
                    <img class="scroll-img" data-index="2" src="assets/icons/arimaFav">
                    <img class="scroll-img" data-index="3" src="assets/icons/arimaLook">
                    <img class="scroll-img" data-index="4" src="assets/icons/arimaWrite">
                </div>
                <div class="container-scrollable container-scrollable-x" id="scrArima">
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation expandrow-2"></div>
                        <div class="container-presentation expandrow-2"></div>
                    </div>
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation expand-2"></div>
                        <div class="container-presentation expand-2"></div>
                    </div>
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation"></div>
                        <div class="container-presentation expandrow-2"></div>
                        <div class="container-presentation"></div>
                    </div>
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation"></div>
                        <div class="container-presentation"></div>
                        <div class="container-presentation expand-2"></div>
                    </div>
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation"></div>
                        <div class="container-presentation"></div>
                        <div class="container-presentation expand-2"></div>
                    </div>
                </div>
                
            </div>

            <div class="zone zone-triple display" id="regr" style="display:none">

                <div class="container-presentation expand-2"></div>
                <div class="scroll">

                    <div class="scroll-buttons scroll-down">
                            <div class="scroll-dot dot-active" id="scrb0" data-index="0"></div>
                            <div class="scroll-dot" id="scrb1" data-index="1"></div>
                            <div class="scroll-dot" id="scrb2" data-index="2"></div>
                        </div>

                    <div class="container-scrollable" id="scr">
                        <div class="allow-scroll"> <img src="assets/qddd.png"></div>
                        <div class="allow-scroll"><img src="assets/qddd.png"></div>
                        <div class="allow-scroll"><img  src="assets/qddd.png"></div>
                    </div>

                </div>
                <div class="container-presentation expand-3"></div>
                
            </div>
        </div>

        <div class="zone mask"></div>

        <div class="nav-bottom" id="nav-bot" hx-swap-oob="outerHTML">

            <div class="nav-categ" id="bu">

                <div class="pack-categ">
                    <div class="container-bottom active switch" data-switch="home" data-index="0" data-name="Accueil analyse">
                        <span>Accueil analyse</span>
                        <img class="flag-small" src='assets/icons/info.svg'>
                    </div>
                    <div class="nav-trait"></div>
                    <div class="container-bottom switch" data-switch="score" data-index="1" data-name="Score">
                        <span>Score</span>
                        <img class="flag-small" src='assets/icons/analyseScore.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="cluster" data-index="2" data-name="Clustering">
                        <span>Clustering</span>
                        <img class="flag-small" src='assets/icons/analyseCluster.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="clusterPlus" data-index="3" data-name="Clustering+">
                        <span>Clustering+</span>
                        <img class="flag-small" src='assets/icons/analyseClusterPlus.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="arima" data-index="4" data-name="ARIMA">
                        <span>ARIMA</span>
                        <img class="flag-small" src='assets/icons/analyseArima.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="regr" data-index="5" data-name="Régression linéaire">
                        <span>Régression linéaire</span>
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div id="trans" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-switch" class="nav-text">Accueil analyse</div>
            </div>
        </div>
            

        <script id="scripting" hx-swap-oob="outerHTML">
            createMapCatalogue("pays")
        </script>

        <script id="orders" hx-swap-oob="outerHTML"></script>

        <script id="behave" hx-swap-oob="outerHTML">

            $(".switch").on("click", function () {
                $(".switch").removeClass("active")
                $(this).addClass("active")
                $(".display").css("display","none")

                $("#"+$(this).data("switch")).css("display","grid")
                if ($(this).data("index") != 0) {
                    nb = $(this).data("index")*53+9.5
                } else {
                    nb = 0
                }
                
                $("#trans").css("transform","translateX("+nb+"px)")
                $("#name-switch").html($(this).data("name"))
            })

            $(".page").removeClass("active")
            $("#s-stats").addClass("active")

            nb = 0
            $("#trans-page").css("transform","translateX("+nb+"px)")
            $("#nav-bot").css("transform","translateY(0)")

            $(".scroll-dot").on("click", function() {
                $(".scroll-dot").removeClass("dot-active")
                $(this).addClass("dot-active")
                el = document.getElementById("scr")
                nb = $(this).data("index")
                h = el.clientHeight.toFixed(0)
                document.getElementById('scr').scroll({top:h*nb,behavior:"smooth"})
            })

            $(".scroll-img").on("click", function() {
                // $(".scroll-img").removeClass("dot-active")
                // $(this).addClass("dot-active")
                el = document.getElementById("scrArima")
                nb = $(this).data("index")
                h = el.clientWidth.toFixed(0)
                console.log(h);
                document.getElementById('scrArima').scroll({left:h*nb,behavior:"smooth"})
            })
            
        </script>

        <div id="htmxing" hx-swap-oob="outerHTML"></div>

    </div>

</body>
</html>