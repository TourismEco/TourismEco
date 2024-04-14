<?php require_once 'head.php'?>

<body>
    <?php
        $cur = getDB();
        $dataMap = json_encode(dataExplorer($cur),JSON_NUMERIC_CHECK);

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
     <div class="flex">
        <div class="zone-explore" id="zones">
            <div class="title-zone">
                <img class="flag-small" src='assets/icons/map.svg'>
                <div>
                    <h2>Explorateur</h2>
                    <p>Un aperçu statistique mondial, pour vous faire découvrir de nouvelles destinations.</p>
                </div>
            </div>

            <div class="map-explore">
                <div id="map"></div>
                <div class="container-buttons">
                    <div class="var-swap">
                        <span>Score</span>
                        <img class="icon icon-active" src="assets/icons/score.svg" onclick="changeVarExplorer('score')" data-name="Score">
                    </div>

                    <div class="var-swap">
                        <span>Émissions de CO2</span>
                        <img class="icon" src="assets/icons/cloud.svg" onclick="changeVarExplorer('co2')" data-name="Émissions de CO2">
                    </div>
                    <div class="var-swap">
                        <span>PIB/Habitant</span>
                        <img class="icon" src="assets/icons/dollar.svg" onclick="changeVarExplorer('pibParHab')" data-name="PIB/Habitant">
                    </div>
                    <div class="var-swap">
                        <span>Global Peace Index</span>
                        <img class="icon" src="assets/icons/shield.svg" onclick="changeVarExplorer('gpi')" data-name="Global Peace Index">
                    </div>
                    <div class="var-swap">
                        <span>Arrivées touristiques</span>
                        <img class="icon" src="assets/icons/down.svg" onclick="changeVarExplorer('arriveesTotal')" data-name="Arrivées touristiques">
                    </div>
                    <div class="var-swap">
                        <span>Départs</span>
                        <img class="icon" src="assets/icons/up.svg" onclick="changeVarExplorer('departs')" data-name="Départs">
                    </div>
                    <div class="var-swap">
                        <span>Indice de développement humain</span>
                        <img class="icon" src="assets/icons/idh.svg" onclick="changeVarExplorer('idh')" data-name="Indice de développement humain">
                    </div>
                    <div class="var-swap">
                        <span>% d'énergies renouvellables</span>
                        <img class="icon" src="assets/icons/elec.svg" onclick="changeVarExplorer('elecRenew')" data-name="% d'énergies renouvellables">
                    </div>
                </div>
            </div>

            <div class="zone-cartePays">
                <div class='container-cartePays display' id="explore">
                    <input type="text" class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue("txt"), page:"explorer", id_continent:0}' hx-target="#search" hx-swap="outerHTML">
                    <div id=search></div>

                    <div class="trait-vertical"></div>

                    <div class="container-explore" id="pays">
                        <p class="info-explore">Selectionnez un pays sur la carte ou depuis la barre de recherche pour consulter ses informations</p>
                    </div>
                </div>
        

                <div class='container-cartePays display' style="display:none" id="fav">
                    <h3 class="title-section">Vos favoris</h3>
                    <div class="container-carte">
                    <?php
                        
                        if (isset($_SESSION['user'])) {
                            $userId = $_SESSION['user']["username"];
                            $queryPays = "SELECT pays.nom AS PaysNom, pays.id, client.nom, pays.score FROM favoris
                                        JOIN pays ON pays.id = favoris.id_pays
                                        JOIN client ON client.nom = favoris.id_client
                                        WHERE favoris.id_client= :userId LIMIT 8";

                            $stmt = $cur->prepare($queryPays);
                            $stmt->execute(['userId' => $userId]);

                            while ($rsPays = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $letter = getLetter($rsPays["score"]);
                                echo addSlimCountry($rsPays["id"],$rsPays["PaysNom"],$letter,"explorerFav");
                            }
                        } else {
                            echo "<p>Connectez-vous pour accéder à cette fonctionnalité.</p>";
                        }
                    ?>
                    </div>

                    <h3 class="title-section" style="border-top: 1px solid #000; padding-top:10px;">Historique</h3>
                    <div class="container-carte">
                    <?php
                        if (isset($_SESSION['user'])) {
                            if (isset($_SESSION["historique"]) && is_array($_SESSION["historique"])) {
                                foreach ($_SESSION["historique"] as $id_pays) {
                                    $queryPays = "SELECT * FROM pays WHERE id = :id_pays";
                                    $stmt = $cur->prepare($queryPays);
                                    $stmt->execute(['id_pays' => $id_pays]);

                                    if ($rsPays = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $letter = getLetter($rsPays["score"]);
                                        echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,"explorerFav");
                                    }
                                }
                            }
                        } else {
                            echo "<p>Connectez-vous pour accéder à cette fonctionnalité.</p>";
                        }
                    ?>
                    </div>
                </div>

                <div class='container-cartePays display' style="display:none" id="podium">
                        
                    <div class="title-zone">
                        <img class="flag-small" src='assets/icons/podium.svg'>
                        <div>
                            <h2>Classements</h2>
                            <p>Choisissez une statistique pour afficher son top 10 des pays.</p>
                        </div>
                    </div>

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
                    <div class="container-bottom active switch" data-switch="explore" data-index="0" data-name="Explorer">
                        <span>Explorer</span>
                        <img class="flag-small" src='assets/icons/map.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="fav" data-index="1" data-name="Pour vous">
                        <span>Pour vous</span>
                        <img class="flag-small" src='assets/icons/heart.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="podium" data-index="2" data-name="Classements">
                        <span>Classements</span>
                        <img class="flag-small" src='assets/icons/rank.svg'>
                    </div>

                    <div id="trans" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-switch" class="nav-text">Explorer</div>
            </div>
        </div>

        <script id="scripting" hx-swap-oob="outerHTML">
            createMapExplorer(<?=$dataMap?>)
        </script>

        <script id="behave" hx-swap-oob="outerHTML">
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
        </script>

        <script id="orders" hx-swap-oob="outerHTML"></script>
        <div id="htmxing" hx-swap-oob="outerHTML"></div>

    </div>

</body>
</html>