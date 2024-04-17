<?php require_once 'head.php'?>

<body>
    
    <div class="flex">

        <div id="zones">

            <div class="zone zone-double display" id="home"  style="display:none">
            < class="container-presentation">
                <div class="sectionAnalyse">
                    <img class="flag-small" src='assets/icons/analyseHome.svg'>
                    <div>
                        <h2>Analyse</h2>
                        <p> Cette page va vous permettre de mieux comprendre le processus de création du score, vous dévoilant les étapes et les méthodes utilisées pour évaluer et attribuer une valeur à différents pays. </p>
                        <p> De plus, cette section met en avant une analyse approfondie de notre site. Vous aurez l'opportunité d'explorer les modèles de prédictions élaborés à partir de nos données. </p>
                        <p> En parcourant cette partie dédiée à l'analyse, vous pourrez également vous familiariser avec les différentes techniques de regroupement de nos données, notamment le clustering. </p>
                    </div>
                </div>
                <div class="section">
                    <div class="container-presentation" id="one">
                        <div class="sectionAnalyse">
                            <img class="flag-small" src='assets/icons/analyseArima.svg'>
                            <div>
                                <h2>ARIMA</h2>
                                <p> ARIMA (Autoregressive Integrated Moving Average) est un modèle statistique utilisé pour analyser et prévoir des séries temporelles.</p>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation" id="two">
                        <div class="sectionAnalyse">
                            <img class="flag-small" src='assets/icons/analyseScore.svg'>
                            <div>
                                <h2>Score</h2>
                                <p> Découvrez comment le score est créé, selon divers critères. Permettant d’avoir un score personnel, adapté à vos préférences et besoins. </p>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation" id="one">
                        <div class="sectionAnalyse">
                            <img class="flag-small" src='assets/icons/analyseCluster.svg'>
                            <div>
                                <h2>Clustering</h2>
                                <p> Le clustering identifie des groupes similaires parmi les données. Il regroupe des éléments partageant des caractéristiques communes.</p>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation" id="two">
                        <div class="sectionAnalyse">
                            <img class="flag-small" src='assets/icons/analyseClusterPlus.svg'>
                            <div>
                                <h2>Clustering+</h2>
                                <p> Le Clustering + va au-delà du clustering traditionnel en intégrant d'autres techniques pour une analyse plus approfondie des données.</p>
                            </div>
                        </div>
                    </div>
                    <div class="container-presentation" id="three">
                        <div class="sectionAnalyse">
                            <img class="flag-small" src='assets/icons/stats.svg'>
                            <div>
                                <h2>Régression Linéaire</h2>
                                <p> La régression linéaire est une méthode d'analyse prédictive qui modélise la relation entre une variable dépendante et une autre variable indépendante.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="zone zone-triple display" id="score" style="display: none;">
            <div class="container-presentation expandrow-2">
                    <h2> Score </h2>
                    <p> Le projet vise à attribuer un score à chaque pays pour fournir une identité supplémentaire. Ce score, basé sur des critères éthiques, permet aux utilisateurs de découvrir facilement des informations sur leur future destination en fonction de leurs préférences. Quatre scores sont mis en place : "Tourismeco" pour une vue d'ensemble par défaut, et trois autres ("Tourisme moderne", "Tourisme Éco-responsable", et "Tourisme d'Exploration et de Découverte") pour des préférences spécifiques.</p>
                    <p>Les critères de chaque score incluent des indicateurs tels que les arrivées d'avions, l'Indice de Développement Humain (IDH), les émissions de gaz à effet de serre (GES), etc. Chaque score est normalisé entre 0 et 1, puis noté de A à E, de parfait à mauvais.</p>
                </div>
                <div class="container-presentation expandrow-2 expand-2">
                    <p> La mise en place des scores s'est faite en utilisant Python. Les données ont été traitées et des poids ont été attribués à chaque critère. Une moyenne pondérée a été utilisée pour obtenir le score final. Les intervalles de score ont été définis en fonction de la variance et appliqués aux données. Enfin, une vérification de cohérence a été réalisée à l'aide d'une visualisation par une carte du monde. </p>
                    <p> De plus nous avons normaliser les données en utilisant la méthode multicritère, cela nous a permis de comparer différents critères. Elle consiste à attribuer des poids différents à chaque critère, certains augmentant le score tandis que d'autres le diminuent. Les valeurs résultantes sont comprises entre 0 et 1. Par exemple, les variables telles que les arrivées d'avions, le PIB par habitant, les énergies renouvelables et l'IDH augmentent le score (cf. calcul 2), tandis que le GPI et les émissions de GES par habitant le diminuent (cf. calcul 1). </p>
                    <div class="math">
                        <div class="frac">
                            <div class="num">Valeur du critère de l’élément</div>
                            <div class="fracbar">/</div>
                            <div class="den">Plus grande valeur sur ce critère de tous les éléments comparés </div>
                        </div>
                    </div>
                    <div class="math">
                        <div class="frac">
                            <div class="num">Plus petite valeur sur ce critère de tous les éléments comparés</div>
                            <div class="fracbar">/</div>
                            <div class="den"> Valeur du critère de l’élément</div>
                        </div>
                    </div>
                </div>
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

                <div class="container-presentation expand-2">
                    <h2> Régression Linéaire </h2>
                    <p> La régression linéaire constitue une approche utile pour prédire ou estimer des valeurs en se basant sur une relation linéaire entre une variable dépendante et une ou plusieurs variables explicatives.
                        Notre approche consiste à trouver des relations entre différentes variables de notre base de données, pour qu’elles soient de prédictions intéressantes à mettre en place. Pour cela nous avons utiliser principalement la variable PIB comme variable explicative et on a exploré d’autres variables supplémentaires comme variable explicative.
                        Pour mettre en place cette régression linéaire, nous avons utilisé le langage de programmation python. On a utilisé des fonctions et des méthodes similaires pour chaque régression, en prenant à chaque fois des données sur une intervalle de 2 ans. On a veillé à prendre des données les plus récentes, en général jusqu'à 2022 et parfois jusqu'à 2020 seulement. </p>
                </div>
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
                <div class="container-presentation expand-3">
                <p>L'analyse des relations entre le PIB par habitant et diverses variables explicatives a révélé des résultats significatifs, montrant le PIB comme un prédicteur efficace du Revenu National Brut par habitant et de l'Indice de Développement Humain. Bien que moins précis 
                pour prédire les émissions de Gaz à Effet de Serre (GES). L'exploration de variables supplémentaires comme l'IDH, l'espérance de vie, le GPI et l'énergie renouvelable a mis en évidence des liens intéressants, notamment la forte corrélation entre l'IDH et l'espérance de vie et la corrélation négative entre l'énergie renouvelable et les émissions de GES par habitant.</p>
                </div>
                
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