<?php require_once 'head.php'?>

<body>
    <div class="flex">

        <div id="zones">
            <div class="zone zone-presentation display" id="home">
                <div class="container-presentation expand-3" id="bandeau0" hx-swap-oob="outerHTML">
                </div>
                <div class="map-catalogue-continent" id="map"></div>
                <div class="graph" id="graph_pie"></div>
                <div class="container-presentation expand-5">
                    <div class='container-carteContinent display' id="explore">
                        <div class="container-explore-continent" id="pays">
                            <p class="info-explore">Selectionnez un pays sur la carte ou depuis la barre de recherche pour consulter ses informations</p>
                        </div>
                    </div>
                </div>
                <div class='container-cartePays classement-continent'  id="podium">
                    <div class="container-classement" id="rank">
                        
                    </div>
                </div>
                <script>
                    createMapCatalogueC("continent")
                    halfpie("graph_pie")
                </script> 
                

            </div>

            <div class="zone zone-basic display" id="key" style="display: none;">
                <div class="title-zone">
                    <img class="flag-small" src='assets/icons/bar.svg'>
                    <div>
                        <h2 id="paysvs"></h2>
                        <p>Ce graphique compare l'évolution du maximum et minimum du continent et sa moyenne au fil des années sur la statistiques que vous souhaitez.</p>
                    </div>
                </div>
                <div class="graph" id="barreContinent"></div>
                

                <script>
                    barreContinent("barreContinent")
                </script>

                <div class=container-buttons>
                    <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVarContinent('co2')" data-name="Émissions de CO2">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVarContinent('pibParHab')" data-name="PIB/Habitant">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVarContinent('gpi')" data-name="Global Peace Index">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVarContinent('arriveesTotal')" data-name="Arrivées touristiques">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVarContinent('departs')" data-name="Départs">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVarContinent('cpi')" data-name="CPI">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVarContinent('elecRenew')" data-name="% d'énergies renouvellables">
                </div>

            </div>
        </div>

        <div class="main" id="main">

            <?php
                $cur = getDB();

                if (!isset($_SESSION["pays"])) {
                    $_SESSION["pays"] = array();
                }

                if (isset($_GET["id_pays"])) {
                    $_SESSION["pays"][0] = $_GET["id_pays"];
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
                } else {
                    $_SESSION["continent"] = array();
                }

                if ($continent == "") {
                    echo <<<HTML
                        <div hx-get="catalogue.php" hx-trigger="load" hx-select="#catalogue" hx-target="#catalogue" hx-vals="js:{page:'Continent'}"></div>
                    HTML;
                } else {
                    echo <<<HTML
                        <div hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:'$continent'}" hx-trigger="load delay:1s"></div>
                    HTML;
                }
            ?>

            <div id="catalogue"></div>

            <div class="container-bandeaux">
                <div id="bandeau0"></div>
            </div>

                    <div id="trans-page" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-page" class="nav-text">Statistiques</div>
            </div>

            <div class="container-simple bg-354F52">
                <h2 class="title-section">Evolution du PIB de la France dans le temps</h2>
                <div class="section">
                    <div class="text">
                        <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
                            Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.                
                        </p>
                        
                    </div>

                    <div class="container-bottom switch" data-switch="courbe" data-index="2" data-name="Courbes d'évolution">
                        <img class="flag-small" src='assets/icons/scatter-white.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="barl" data-index="3" data-name="PIB et tourisme">
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="more" data-index="4" data-name="Informations complémentaires">
                        <img class="flag-small" src='assets/icons/plus.svg'>
                    </div>

                    <div id="trans" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-switch" class="nav-text">Présentation</div>
            </div>

            <div class="container-simple bg-52796F">
                <h2 class="title-section">Comparateur rapide</h2>
                <p>
                    Voici quelques pays pertinents que vous pouvez comparer avec la France
                </p> 


            </div>

            <script id=scripting>
                createMapContinent()
                barreContinent("barContinent")
                line('lineContinent')
            </script>

        </div>
    </div>

</body>
</html>