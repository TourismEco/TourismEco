<?php require_once 'head.php'?>

<script src="scripts/graph/line.js"></script>
<script src="scripts/graph/barreContinent.js"></script>


<body>
<div hx-get="scripts/htmx/getContinent.php" hx-vals="js:{id_continent:'AF'}" hx-trigger="load delay:1s"></div>
    <div class="container-map">
        <div id="map"></div>
    </div>

    <div class="grille" id="grille" hx-swap="outerHTML swap:2s">

        <div class="sidebar">
            <div id="mini0"></div>

            <div class="container-side bg-52796F" hx-get="catalogue.php" hx-select="#catalogue" hx-target="#catalogue" hx-trigger="click" hx-swap="show:top" hx-vals="js:{page:'Pays'}">
                <div class="bandeau-small">
                    <img id=plus class="flag-small" src='assets/img/plus.svg'>
                    <h2 class="nom-small">Choisir un continent</h2>
                </div>

            </div>
        </div>

        <div class="main" id="main">

            <?php
                $cur = getDB();

                $continent = "";
                if (isset($_SESSION["continent"]) && count($_SESSION["continent"]) != 0) {
                    $query = "SELECT * FROM continents WHERE code = :id_continent";
                    $sth = $cur->prepare($query);
                    $sth->bindParam(":id_continent", $_SESSION["continent"][0], PDO::PARAM_STR);
                    $sth->execute();

                    $ligne = $sth->fetch();
                    if ($ligne) {
                        $continent = $_SESSION["continent"][0];
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

            <div class="container-simple bg-52796F">
                <h2 class="title-section">Scores EcoTourism</h2>
                <div class="score"> 
                    <div id="score0"></div>
                    <div class="trait"></div>
                    <div id="score1"></div>
                </div>
            </div>

            <div class="container-simple bg-354F52">
                <h2 class="title-section">Evolution du PIB de la France dans le temps</h2>
                <div class="section">
                    <div class="text">
                        <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
                            Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.                
                        </p>
                        
                    </div>
                    

                </div>
            </div>

            <div class="container-simple bg-354F52">
                <h2 class="title-section">Comparaison de chaque indicateur</h2>
                <div class=container-buttons>
                    <button onclick="changeVar('co2')" class=button-compare>Émissions de CO2</button>
                    <button onclick="changeVar('pib')" class=button-compare>PIB par habitant</button>
                    <button onclick="changeVar('gpi')" class=button-compare>Indice de paix</button>
                    <button onclick="changeVar('arrivees')" class=button-compare>Arrivées</button>
                    <button onclick="changeVar('departs')" class=button-compare>Départs</button>
                    <button onclick="changeVar('cpi')" class=button-compare>Consumer Price Index</button>
                    <button onclick="changeVar('Enr')" class=button-compare>% énergies renouvellables</button>
                </div>
                <div class="graph" id="barContinent"></div>
                <div class="graph" id="lineContinent"></div>

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

    
            
    <?php require_once 'footer.html'?>
</body>
