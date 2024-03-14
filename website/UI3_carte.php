<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte</title>
    <!-- <link rel="stylesheet" href="assets/css/styles2.css"> -->
    <link rel="stylesheet" href="assets/css/UI3.css">

	<script src="https://unpkg.com/jquery.min.js"></script>
    <script src="https://unpkg.com/htmx.org"></script>

    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>

	<script src="scripts/graph/amTools.js"></script>

    <script src="scripts/graph/lineCompare.js"></script>
	<script src="scripts/graph/spiderCompare.js"></script>
	<script src="scripts/graph/barCompare.js"></script>

	<script src="scripts/js/functions.js"></script>

    <script src="scripts/map/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    
    <?php
        require("functions.php");
        $cur = getDB();
    ?>

</head>

<body>
    <nav class="navbar" hx-boost="true" hx-target="#grille" hx-select="#grille" hx-swap="outerHTML show:body:top swap:0.5s">
    
        <div class="right-nav">
            <a href="monde.php">Monde</a>
            <a href="pays.php">Pays</a>
            <a href="continent.php">Continent</a>
            <a href="comparateur.php">Comparateur</a>
        </div>
    
        <div class="img-nav">
            <a href="index.php"><img src="assets/img/eco.png"></a>
        </div>
    
        <div class="left-nav">
            <a href="calculateur.php">Calculateur</a>
            <a href="inscription.php" >S'inscrire</a>
            <a href="connexion.php">Se connecter</a>
        </div>
    
    </nav>

    <div class="flex">
        <div class="grid">
            

            <div class="container-side bg-354F52 g4-1 " id="mini$incr" hx-swap-oob="outerHTML">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">Statistiques</h2>
            </div>


            <div class="container-side bg-354F52 g5-1 active" id="mini$incr" hx-swap-oob="outerHTML" onclick="window.location.href = 'UI3_carte.php';">
                <img class="flag-small" src='assets/icons/map.svg'>
                <h2 class="nom-small">Carte</h2>
            </div>

            <div class="container-side bg-354F52 g6-1 " id="mini$incr" hx-swap-oob="outerHTML" onclick="window.location.href = 'UI3_catalogue.php';">
                <img class="flag-small" src='assets/icons/catalogue.svg'>
                <h2 class="nom-small">Catalogue</h2>
            </div>

            <div class="zone-carte">
                <div class="map-carte" id="map"></div>

                <script>
                    function getSearchValue() {
                        var s = document.getElementById("txt")
                        return s.value
                    }

                </script>
                <div class="zone-cartePays">
                    <h3 class="title-section">10 meilleurs scores</h3>
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


            </div>


        <div class="container-bottom bg-354F52 g10-2 active switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="asie">
                <img class="flag-small" src='assets/icons/map.svg'>
                <h2 class="nom-small">Carte</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-3 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="afrique" data-id_continent="1">
                <img class="flag-small" src='assets/icons/podium.svg'>
                <h2 class="nom-small">Les meilleurs scores</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-4 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="amerique" data-id_continent="2">
                <img class="flag-small" src='assets/icons/heart.svg'>
                <h2 class="nom-small">Vos favoris</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-5 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="europe" data-id_continent="5">
                <img class="flag-small" src='assets/icons/trophy.svg'>
                <h2 class="nom-small">Les populaires</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-6 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="oceanie" data-id_continent="6">
                <img class="flag-small" src='assets/icons/historical.svg'>
                <h2 class="nom-small">Historique</h2>
            </div>
        </div>
    </div>

    <script id="scripting">
        createMap()
    </script>

    <script>
        var id_continent = 4;
        $(".switch").on("click", function () {
            $(".switch").removeClass("active")
            $(this).addClass("active")
            $(".display").css("display","none")
            console.log($(this).data("switch"))
            $("#"+$(this).data("switch")).css("display","grid")

            id_continent = $(this).data("id_continent")
        })
    </script>

</body>
</html>