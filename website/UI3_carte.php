<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte</title>
    
    <?php
        require("functions.php");
        require('head.php');
        $cur = getDB();
    ?>

</head>

<body>
    <?php
        $page ="pays";
        $cur = getDB();

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
        <div class="grid" id="grid">
            

            <div class="container-side bg-354F52 g4-1 " id="mini$incr" hx-swap-oob="outerHTML">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">Statistiques</h2>
            </div>


            <div class="container-side bg-354F52 g5-1 active" id="mini$incr" hx-swap-oob="outerHTML" onclick="window.location.href = 'UI3_carte.php';">
                <img class="flag-small" src='assets/icons/map.svg'>
                <h2 class="nom-small">Exploration</h2>
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
                    <div class='container-cartePays display' style="display:none" id="explore">
                        <input type="text" class="search-bar" placeholder="Cherchez un pays" id="txt" hx-get="scripts/htmx/search.php" hx-trigger="keyup[this.value.trim().length > 0] changed delay:0.5s" hx-vals='js:{search: getSearchValue(), page:"pays"}' hx-target="#search" hx-swap="outerHTML">
                        <div id=search></div>
                        <div class="container-explore">
                            <div class="explore-bandeau" id="bandeau"></div>
                            <div class="explore-prix" id="prix"></div>
                            <div class="explore-score" id="score" ></div>
                            <div class="explore-rang" id="rang"></div>
                            <div class="explore-describ" id="describ"></div>
                            <div class="explore-more" id="more"></div>
                        </div>
                    </div>
            

                    <div class='.container-cartePays display' style="display:none" id="fav">
                        <h3 class="title-section">Vos favoris</h3>
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

                    <div class='container-cartePays display'  id="podium">
                        <h3 class="title-section">Classement</h3>
                        <div class="container-classement">
                            <div class ="classement first">
                                <div class="premier">1</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement first">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first first">
                            </div>
                            <div class ="classement second">
                                <div class="deuxieme">2</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement second">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first second">
                            </div>
                            <div class ="classement third">
                                <div class="troisieme">3</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement third">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first third">
                            </div>
                            <div class ="classement fourth">
                                <div class="quatrieme">4</div>
                                <div class="classement-pays"> France</div>
                                <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement fourth">
                                <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first fourth">
                            </div>
                            <div class ="otherDiv">
                                <div class="classement">
                                    <div class="otherclassement">5</div>
                                    <div class="classement-pays"> France</div>
                                    <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                    <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                                </div>
                                <div class="classement">
                                    <div class="otherclassement">6</div>
                                    <div class="classement-pays"> France</div>
                                    <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                    <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                                </div>
                            </div>
                            <div class ="otherDiv">
                                <div class="classement">
                                    <div class="otherclassement">7</div>
                                    <div class="classement-pays"> France</div>
                                    <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                    <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                                </div>
                                <div class="classement">
                                    <div class="otherclassement">8</div>
                                    <div class="classement-pays"> France</div>
                                    <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                    <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                                </div>
                            </div>
                            <div class ="otherDiv">
                                <div class="classement">
                                    <div class="otherclassement">9</div>
                                    <div class="classement-pays"> France</div>
                                    <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                    <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                                </div>
                                <div class="classement">
                                    <div class="otherclassement">10</div>
                                    <div class="classement-pays"> France</div>
                                    <img src="assets/twemoji/FR.svg" alt="France" class="flagClassement other">
                                    <img src="assets/img/FR.jpg" alt="France" class="imgClassement img-first other">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='container-cartePays display' style="display:none" id="historical">
                        <h3 class="title-section">Historique</h3>
                        <div class="container-carte">
                        </div>
                    </div>

                    <div class='container-cartePays display' style="display:none" id="alea">
                        <h3 class="title-section">Découvrir</h3>
                        <div class="container-carte">
                        </div>
                    </div>
                    
                    



            </div>
        </div>



        <div class="container-bottom bg-354F52 g10-2 active switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="explore">
                <img class="flag-small" src='assets/icons/map.svg'>
                <h2 class="nom-small">Exploration</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-3 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="fav">
                <img class="flag-small" src='assets/icons/heart.svg'>
                <h2 class="nom-small">Vos favoris</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-4 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="podium" >
                <img class="flag-small" src='assets/icons/podium.svg'>
                <h2 class="nom-small">Classement</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-5 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="historical" >
                <img class="flag-small" src='assets/icons/historical.svg'>
                <h2 class="nom-small">Historique</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-6 switch" id="mini$incr" hx-swap-oob="outerHTML" data-switch="alea">
                <img class="flag-small" src='assets/icons/shuffle.svg'>
                <h2 class="nom-small">Découverte aléatoire</h2>
            </div>
        </div>
    </div>

    <script id="scripting">
        createMapExplorer()
    </script>

    <script>
        $(".switch").on("click", function () {
            $(".switch").removeClass("active")
            $(this).addClass("active")
            $(".display").css("display","none")
            console.log($(this).data("switch"))
            $("#"+$(this).data("switch")).css("display","grid")
        })
    </script>

<!-- <div hx-get="scripts/htmx/getExplore.php" hx-vals="js:{id_pays: 'FR'}" hx-trigger="load delay:.1s"></div> -->
<div hx-get="scripts/htmx/getClassement.php" hx-vals="js:{var:'arriveesTotal'}" hx-trigger="load delay:.1s"></div>


</body>
</html>