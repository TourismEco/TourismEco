<?php require_once 'head.php'?>

<body>
    <?php
        $cur = getDB();

        // unset($_SESSION["pays"]);
        $pays = array();
        if (isset($_SESSION["pays"])) {
            foreach ($_SESSION["pays"] as $key => $id_pays) {
                // echo $_SESSION["incr"];
                $query = "SELECT * FROM pays WHERE id = :id_pays";
                $sth = $cur->prepare($query);
                $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                $sth->execute();

                $ligne = $sth->fetch();
                if ($ligne) {
                    $pays[] = $id_pays;
                }
            }
        } else {
            $_SESSION["pays"] = array();
            $_SESSION["incr"] = 0;
        }

        // switch (count($pays)) {
        //     case 2:
        //         echo <<<HTML
        //             <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'$pays[0]'}" hx-trigger="load delay:1s"></div>
        //             <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:1,id_pays:'$pays[1]'}" hx-trigger="load delay:1.05s"></div>
        //         HTML;
        //         break;
            
        //     case 1:
        //         echo <<<HTML
        //             <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'$pays[0]'}" hx-trigger="load delay:1s"></div>
        //             <div hx-get="catalogue.php" hx-trigger="load" hx-select="#catalogue" hx-target="#catalogue" hx-vals="js:{page:'Compare'}"></div>
        //         HTML;
        //         break;
            
        //     case 0:
        //         echo <<<HTML
        //             <div hx-get="catalogue.php" hx-trigger="load" hx-select="#catalogue" hx-target="#catalogue" hx-vals="js:{page:'Compare'}"></div>
        //         HTML;
        //         break;
        // }

        $pays = json_encode($pays);

    ?>

    <div class="flex">
        <div class="grid">
            <div class="container-side g1-1" id="mini0">
            </div>

            <div class="container-side g2-1" id="mini1">
            </div>

            <div class="container-side bg-354F52 g4-1 active">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">Statistiques</h2>
            </div>

            <div class="container-side bg-354F52 g5-1">
                <img class="flag-small" src='assets/icons/map.svg'>
                <h2 class="nom-small">Carte</h2>
            </div>

            <div class="container-side bg-354F52 g6-1">
                <img class="flag-small" src='assets/icons/catalogue.svg'>
                <h2 class="nom-small">Catalogue</h2>
            </div>

            <div class="zone-presentation" id="home">
                <div class="container-presentation expand-2" id="mini$incr" hx-swap-oob="outerHTML">
                    <div class="bandeau"> 
                        <img class="img-side img" src='assets/img/FR.jpg' alt="Bandeau">
                        <div class="flag-plus-nom">
                            <img class="flag" src='assets/twemoji/FR.svg'>
                            <h2 class="nom">France</h2>
                        </div>
                        
                    </div>
                </div>

                <div class="container-presentation expand-2" id="mini$incr" hx-swap-oob="outerHTML">
                    <div class="bandeau"> 
                        <img class="img-side img" src='assets/img/HR.jpg' alt="Bandeau">
                        <div class="flag-plus-nom">
                            <img class="flag" src='assets/twemoji/HR.svg'>
                            <h2 class="nom">Croatie</h2>
                        </div>
                    </div>
                </div>

                <div class="container-presentation" id="mini$incr" hx-swap-oob="outerHTML">
                    <div class="score-box score-A">A</div>
                </div>

                <div class="container-presentation" id="miniMap0" hx-swap-oob="outerHTML">
                </div>

                <div class="container-presentation" id="mini$incr" hx-swap-oob="outerHTML">
                    <div class="score-box score-A">A</div>
                </div>

                <div class="container-presentation" id="miniMap1" hx-swap-oob="outerHTML">
                </div>

                <div class="container-presentation" id="mini$incr" hx-swap-oob="outerHTML">
                </div>

                <div class="container-presentation expand-2" id="mini$incr" hx-swap-oob="outerHTML">
                </div>

                <div class="container-presentation" id="mini$incr" hx-swap-oob="outerHTML">
                
                </div>

            </div>
                

            <div class="zone" id="courbe" style="display:none">        
                <div class=graph id="line"></div>

                <div class="details">
                    <div class="legende">
                        <div class="square bg-52796F"></div>
                        <p>France</p>
                        <div class="square bg-83A88B"></div>
                        <p>Croatie</p>
                    </div>

                <div class=container-buttons>
                    <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVar('co2')">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVar('pib')">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVar('gpi')">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVar('arrivees')">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVar('departs')">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVar('cpi')">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVar('Enr')">
                </div>

                </div>

                <div class="table">
                    <div class="container-info stretch"></div>
                    <div class="container-info">
                        <p>Le pays est actuellement</p>
                        <p id="rank0" class="big">-</p>
                        <p>parmi tous les pays</p>
                    </div>
                    <div class="container-info">
                        <p>Le pays est actuellement</p>
                        <p id="rank1" class="big">-</p>
                        <p>parmi tous les pays</p>
                    </div>
                    <div class="container-info">
                        <p>Entre / et / , évolution de</p>
                        <p id="evol0" class="big">-</p>
                    </div>
                    <div class="container-info">
                        <p>Entre / et / , évolution de</p>
                        <p id="evol1" class="big">-</p>
                    </div>
                    <div class="container-info">
                        <p>Impact du COVID :</p>
                        <p id="covid0" class="big">-</p>
                    </div>
                    <div class="container-info">
                        <p>Impact du COVID :</p>
                        <p id="covid1" class="big">-</p>
                    </div>
                </div>

            </div>

            <div class="zone" id="key" style="display:none">

                <div class=graph id="spider"></div>
                <div class="legende">
                    <div class="square bg-52796F"></div>
                    <p>France</p>
                    <div class="square bg-83A88B"></div>
                    <p>Croatie</p>
                </div>

                <table class="text">
                    <tr>
                        <td id="cell_6_1">Indicateur</td>
                        <td id="nom_0"></td>
                        <td id="nom_1"></td>
                    </tr>
                    <tr>
                        <td id="td_pib">PIB/Hab</td>
                        <td id="td_pib_0"></td>
                        <td id="td_pib_1"></td>
                    </tr>
                    <tr>
                        <td id="td_enr">% énergies renouvellables</td>
                        <td id="td_Enr_0"></td>
                        <td id="td_Enr_1"></td>
                    </tr>
                    <tr>
                        <td id="td_co2">Émissions de CO2</td>
                        <td id="td_co2_0"></td>
                        <td id="td_co2_1"></td>
                    </tr>
                    <tr>
                        <td id="td_arrivees">Arrivées toursitiques</td>
                        <td id="td_arrivees_0"></td>
                        <td id="td_arrivees_1"></td>
                    </tr>
                    <tr>
                        <td id="td_departs">Départs toursitiques</td>
                        <td id="td_departs_0"></td>
                        <td id="td_departs_1"></td>
                    </tr>
                    <tr>
                        <td id="td_gpi">Indice de paix</td>
                        <td id="td_gpi_0"></td>
                        <td id="td_gpi_1"></td>
                    </tr>
                    <tr>
                        <td id="td_cpi">CPI</td>
                        <td id="td_cpi_0"></td>
                        <td id="td_cpi_1"></td>
                    </tr>
                </table>

            </div>

            <div class="zone"  id="grow" style="display:none">

                <div class=graph id="bar"></div>
                <div class="legende">
                    <div class="square bg-52796F"></div>
                    <p>France</p>
                    <div class="square bg-83A88B"></div>
                    <p>Croatie</p>
                </div>

            </div>

            <div class="zone" style="display:none" id="more">
            </div>

            <div class="container-bottom bg-354F52 g10-2" id="mini$incr" hx-swap-oob="outerHTML">
                <img class="flag-small" src='assets/icons/info.svg'>
                <h2 class="nom-small">Présentation</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-3" id="mini$incr" hx-swap-oob="outerHTML">
                <img class="flag-small" src='assets/icons/lamp.svg'>
                <h2 class="nom-small">Indicateurs clés</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-4 active" id="mini$incr" hx-swap-oob="outerHTML">
                <img class="flag-small" src='assets/icons/sort.svg'>
                <h2 class="nom-small">Courbe de comparaison</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-5" id="mini$incr" hx-swap-oob="outerHTML">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">Croissances</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-6" id="mini$incr" hx-swap-oob="outerHTML">
                <img class="flag-small" src='assets/icons/plus.svg'>
                <h2 class="nom-small">Informations complémentaires</h2>
            </div>

        </div>
    </div>

    <script id=scripting>
        
        spiderCompare("spider")
        lineCompare("line")
        barCompare("bar")

        createMiniMap(0)
        createMiniMap(1)

    </script>

    <script>
        $(".icon").on("click", function () {
            console.log("qsdsqd");
            $(".icon-active").removeClass("icon-active")
            $(this).addClass("icon-active")
        })
    </script>

    <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'FR'}" hx-trigger="load delay:2s"></div>
    <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:1,id_pays:'HR'}" hx-trigger="load delay:2.5s"></div>
    
    
</body>
</html>