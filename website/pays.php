<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>EcoTourism - Pays</title>
    <link rel="stylesheet" href="assets/css/styles.css" type="text/css" media="screen" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>

    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>

    <script src="scripts/graph/amTools.js"></script>
    <script src="scripts/map/map.js"></script>
    <script src="scripts/graph/barre.js"></script>
    <script src="scripts/graph/double_courbe.js"></script>

</head>
<body>

    <?php require_once 'navbar.php'?>

    <?php
        require("functions.php");
        $cur = getDB();

        $pays = "";
        if (isset($_SESSION["pays"])) {
            $query = "SELECT * FROM pays WHERE id = :id_pays";
            $sth = $cur->prepare($query);
            $sth->bindParam(":id_pays", $_SESSION["pays"], PDO::PARAM_STR);
            $sth->execute();

            $ligne = $sth->fetch();
            if ($ligne) {
                $pays = $_SESSION["pays"];
            }
        } else {
            $_SESSION["pays"] = "";
        }

        if ($pays == "") {
            echo <<<HTML
                <div hx-get="catalogue.php" hx-trigger="load" hx-select="#catalogue" hx-target="#catalogue" hx-vals="js:{page:'Pays'}"></div>
            HTML;
        } else {
            echo <<<HTML
                <div hx-get="scripts/htmx/getPays.php" hx-vals="js:{id_pays:'$pays'}" hx-trigger="load"></div>
            HTML;
        }
    ?>

    <div class="container-map">
        <div id="map"></div>
    </div>

    <div class="grille">

        <div class="sidebar">
            <div id="mini"></div>

            <div class="container-side bg-52796F" hx-get="catalogue.php" hx-select="#catalogue" hx-target="#catalogue" hx-trigger="click" hx-swap="show:top" hx-vals="js:{page:'Pays'}">
                <div class="bandeau-small"> 
                    <img id=plus class="flag-small" src='assets/img/plus.svg'>
                    <h2 class="nom-small">Choisir des pays</h2>
                </div>

            </div>
        </div>

        <div class="main" id="main">

            <div id="catalogue"></div>

            <div class="container-bandeaux">
                <div id="bandeau"></div>
            </div>

            <div class="container-simple bg-52796F">
                <h2 class="title-section">Indicateurs clés</h2>
                <div class="container-even" id="indicateurs">
                    <div class="container-indic">
                        <h3>CO2</h3>
                        <p>2525</p>
                    </div>
                    <div class="container-indic">
                        <h3>CO2</h3>
                        <p>2525</p>
                    </div>
                    <div class="container-indic">
                        <h3>CO2</h3>
                        <p>2525</p>
                    </div>
                    <div class="container-indic">
                        <h3>CO2</h3>
                        <p>2525</p>
                    </div>
                </div>
                
            </div>

            <div class="container-simple bg-52796F">
                <h2 class="title-section">Score EcoTourism</h2>
                <div class=score> 
                    <div class="score-box">A</div>      
                                  
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
                    
                    <div class="graph"></div>

                </div>
            </div>

            <div class="container-simple bg-354F52">
                <h2 class="title-section">Evolution du Co2 en France dans le temps</h2>
                <div class="section">
                    <div class="graph" id="chartdiv2"></div>

                    <div class="text">
                        <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
                            Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.                
                        </p>
                    </div>
                </div>
            </div>

            <div class="container-simple bg-354F52">
                <h2 class="title-section">Evolution du PIB par rapport au tourisme</h2>
                <div class="section">
                    <div class="text">
                        <p>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
                            Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.                
                        </p>
                    </div>
                    
                    <div class="graph"></div>
                </div>
            </div>

            <div class="container-simple bg-52796F">
                <h2 class="title-section">Comparateur rapide</h2>
                <p>
                    Voici quelques pays pertinents que vous pouvez comparer avec la France
                </p>

                <div class="graph" id="chartdiv4"></div>

            </div>

        </div>
    </div>

    <?php
        require_once "functions.php";
        $conn = getDB();
        $query = "
            SELECT pays.nom, ROUND(SUM(co2)) as total
            FROM ecologie, pays
            WHERE pays.id = ecologie.id_pays
            GROUP BY id_pays
            ORDER BY `total` DESC
            LIMIT 10;
        ";
        $result = $conn->query($query);

        $data_bar = array();
        while ($rs = $result->fetch()) {
            $data_bar[] = '
            {country:' . '"' . $rs['nom'] . '"' . ',
            ' . 'value:' . $rs['total'] . '}';
        }

        // Concaténer les données
        $data_bar = implode(",", $data_bar);

        $query = "
        SELECT eco.annee, eco.co2 as eco
        FROM ecologie as eco
        WHERE eco.id_pays = 'FR'
        ";

        $result = $conn->query($query);

        $data_courbe = array();
        while ($rs = $result->fetch()) {
            $data_courbe[] = array(
                "year" => $rs['annee'],
                "value" => $rs['eco'],
            );
        }

        // Fermez la connexion à la base de données
        $conn= null;
        $data_courbe = json_encode($data_courbe);
    ?>


        <script id=scripting>
            createMap()
            double_courbe(<?=$data_courbe?>)
            barre(<?= $data_bar;?>)
        </script>
            
    <?php require_once 'footer.html'?>
    </body>
</html>