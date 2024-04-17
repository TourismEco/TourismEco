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
                            <p>Le clustering regroupe les pays en trois groupes distincts à l’aide de ces six variables. Les centroides, points centraux, sont déplacés pour minimiser les distances avec les données jusqu'à former des groupes bien définis.</p>
                        </div>
                    </div>
                </div>

                <div class="map-cluster" id="map"></div>
                

                <div class="zone-choix">
                    <div class="container-scores border-NA">
                        <div class="title-scores">
                            <img src="assets/icons/up.svg" class="score-NA">
                            <p>Arrivées Total </p>
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
                            <p>Global Peace Index</p>
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
                            <p>Indice de Développement Humain</p>
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
                            <p>Emissions de GES/hab</p>
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
                            <p>Emission de CO2</p>
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
                    <img class="scroll-img" data-index="0" src="assets/icons/arimaHome.svg">
                    <img class="scroll-img" data-index="1" src="assets/icons/arimaTemp.svg">
                    <img class="scroll-img" data-index="2" src="assets/icons/arimaFav.svg">
                    <img class="scroll-img" data-index="3" src="assets/icons/arimaLook.svg">
                    <img class="scroll-img" data-index="4" src="assets/icons/arimaWrite.svg">
          </div>
                
                <div class="container-scrollable container-scrollable-x" id="scrArima">
                    <div class="allow-scroll allow-scroll-full zone-arima">
                    
                        <div class="container-presentation expandrow-2"> 
                        <div class="title-zone">
                        <img class="flag-small" src="assets/icons/arimaHome.svg">
                        <div>
                        <h2>ARIMA</h2>
                            <p> (AutoRegressive Integrated Moving Average) 
                                est un modèle statistique puissant utilisé pour analyser et prévoir les séries temporelles.</p>
                       </div>
                    </div>
                    </div>

                        <div class="container-presentation expandrow-2">
                        <div>
                        <p> ARIMA tire son efficacité de la combinaison de trois concepts principaux : l'autorégression (AR), l'intégration (I) et la moyenne mobile (MA).  Comprendre ces composantes est essentiel pour maîtriser ARIMA et
exploiter ses capacités dans l'analyse des séries temporelles.

(AR) qui capture la dépendance des observations précédentes, 

(I) qui rend la série stationnaire en différenciant les données, 

(MA) qui modélise les erreurs résiduelles. 


En définissant les paramètres notées respectivement 'p', 'd' et 'q', le modèle ARIMA peut s'adapter aux structures temporelles spécifiques des données, capturant à la fois les tendances à long terme et les variations aléatoires.

Cette flexibilité permet à ARIMA de modéliser une grande variété de séries
temporelles avec précision. En comprenant ces concepts fondamentaux, nous sommes prêts à explorer plus en profondeur le fonctionnement et les applications pratiques du modèle ARIMA dans l'analyse et la prévision des séries temporelles. </p>
                        </div></div>
                    </div>
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation expand-2">
                            <div class="title-zone">
                                <img class="flag-small" src="assets/icons/arimaTemp.svg">
                                <div>
                            <h2>SÉRIE TEMPORELLE</h2>
                        </div></div></div>
                        <div class="container-presentation expand-2"> 
                        <p> Les séries temporelles représentent un aspect interessant de l'analyse des données, offrant un aperçu des phénomènes qui évoluent avec le temps. Avant de plonger dans les détails du modèle ARIMA, il est important de comprendre les fondamentaux des séries temporelles.
Elles sont décomposées en trois composantes principales :
- Tendance (Tt) : Représente la variation à long terme de la série, pouvant être croissante, décroissante ou stable.
- Saisonnalité (St) : Indique les variations périodiques qui se répètent à des intervalles réguliers, comme les saisons ou
 les cycles économiques.
- Résidu ou erreur (εt) : Comprend les fluctuations aléatoires non expliquées par la tendance ou la saisonnalité.

Cette décomposition permet une compréhension approfondie de la structure des données temporelles, facilitant ainsi la modélisation et la prédiction. </p>
                       
                        </div>
                    </div>

                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation">
                        <div class="title-zone">
                                <img class="flag-small" src="assets/icons/arimaFav.svg">
                            <div>
                            <h2>DÉTERTMINER LES PARAMÈTRES ARIMA</h2>
                            <p> La configuration d'un modèle ARIMA implique de choisir les valeurs appropriées pour le paramètres p, d et q, qui déterminent l'ordre de l'autorégression, le degré de différenciation et l'ordre de la moyenne mobile, respectivement. </p>
                            </div>
                        </div>
                        </div>
                        <div class="container-presentation expandrow-2">
                            <p> Pour simplifier et accélérer le processus de configuration des modèles ARIMA, l'automatisation à
l'aide de la recherche en grille est souvent utilisée. Cette approche implique de spécifier une grille
de valeurs pour les paramètres p, d et q, puis d'évaluer la performance de chaque combinaison
de paramètres à l'aide d'une métrique de performance définie.

La recherche en grille des hyperparamètres ARIMA est une approche systématique pour trouver
les valeurs optimales des paramètres p, d et q. En résumé cette procédure implique de spécifier
une grille de valeurs pour chaque paramètre, d'évaluer les performances de chaque combinaison
de paramètres et de sélectionner celle qui donne les meilleures performances prévisionnelles. </p>
                        </div>
                        <div class="container-presentation">
                            <p> En raison de la complexité de l'estimation des paramètres ARIMA, des méthodes d'essais et d'erreurs itératifs sont souvent utilisées pour trouver les valeurs optimales. Cela implique d'ajuster différents modèles ARIMA avec des combinaisons de paramètres et de sélectionner celui qui
minimise les erreurs de performance, telles que l'erreur quadratique moyenne (RMSE) ou l'erreur
absolue moyenne (MAE). </p>
                        </div>
                    </div>
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation">
                        <div class="title-zone">
                                <img class="flag-small" src="assets/icons/arimaLook.svg">
                            <div><h2> PRÉDICTIONS SUR L’ÉCHANTILLION DE TEST</h2>
                            <p> Une fois que le modèle ARIMA a été configuré et ajusté aux données d'entraînement, il est prêt à être utilisé pour faire des prédictions sur l'échantillon de test. Cette étape est cruciale pour évaluer la performance du modèle et sa capacité à généraliser les tendances observées dans les données d'entraînement. </p>
                        </div></div>
                        </div>
                        <div class="container-presentation"></div>
                        <div class="container-presentation expand-2">
                            <div><h2> INTERPRETATION DES RÉSULTATS : </h2>
                            <p> L'évaluation des performances du modèle ARIMA sur l'échantillon de test fournit des informations précieuses sur sa capacité à capturer les tendances et les variations des données. Les métriques de performance telles que l'erreur quadratique moyenne (RMSE) et l'erreur absolue moyenne (MAE) permettent d'évaluer la précision des prédictions et d'identifier les domaines où le modèle peut être amélioré. </p>
                            </div></div>
                    </div>
                    <div class="allow-scroll allow-scroll-full zone-arima">
                        <div class="container-presentation">
                        <div class="title-zone">
                                <img class="scroll-img" src="assets/icons/arimaWrite.svg">
                           <div> <h2> PRÉDICTIONS HORS ÉCHANTILLION </h2>
                            <p> Une fois que le modèle ARIMA a été validé sur l'échantillon de test, il peut être utilisé pour faire des prédictions sur des données hors échantillon. Ces prédictions fournissent des informations sur les tendances futures et les variations potentielles des données, aidant ainsi à prendre des décisions éclairées et à anticiper les changements à venir. </p>
                        </div></div>
                        </div>
                        <div class="container-presentation"></div>
                        <div class="container-presentation expand-2">
                        <div>
                            <h2> APPLICATIONS PRATIQUES : </h2>
                            <p> Les modèles ARIMA sont largement utilisés dans divers domaines, y compris la finance, l'économie, la météorologie et la santé, pour analyser et prévoir les séries temporelles. Leur capacité à capturer les tendances et les variations des données en fait un outil puissant pour la modélisation et la prédiction des phénomènes qui évoluent avec le temps. </p>
                        </div></div>
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