<?php require_once 'head.php'?>

<body>
    <div class="flex">
        <div class="grid" id="grid">
            <div class="container-side g1-1" id="mini0"></div>
            <div class="container-side g2-1" id="mini1"></div>

            <div class="container-side bg-354F52 g4-1 active">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">Statistiques</h2>
            </div>

            <div class="container-side bg-354F52 g5-1">
                <img class="flag-small" src='assets/icons/map.svg'>
                <h2 class="nom-small">Exploration</h2>
            </div>

            <div class="container-side bg-354F52 g6-1" hx-get="UI3_catalogue.php" hx-select="#grid" hx-target="#grid" hx-trigger="click" hx-vals="js:{page:'Pays'}" hx-swap="outerHTML swap:0.5s">
                <img class="flag-small" src='assets/icons/catalogue.svg'>
                <h2 class="nom-small">Catalogue</h2>
            </div>

            <div class="zone-presentation display" id="home">
                <div class="container-presentation expand-2" id="bandeau0"></div>
                <div class="container-presentation expand-2" id="bandeau1"></div>

                <div class="container-presentation" id="score0"></div>
                <div class="container-presentation" id="miniMap0"></div>

                <div class="container-presentation" id="score1"></div>
                <div class="container-presentation" id="miniMap1"></div>

                <div class="container-presentation"></div>
                <div class="container-presentation expand-2"></div>
                <div class="container-presentation"></div>
            </div>
                

            <div class="zone display" id="courbe" style="display:none">        
                <div class=graph id="line"></div>

                <div class=container-buttons>
                    <img class="icon icon-active" src="assets/icons/cloud.svg" onclick="changeVar('co2')" data-name="Émissions de CO2">
                    <img class="icon" src="assets/icons/dollar.svg" onclick="changeVar('pib')" data-name="PIB/Habitant">
                    <img class="icon" src="assets/icons/shield.svg" onclick="changeVar('gpi')" data-name="Global Peace Index">
                    <img class="icon" src="assets/icons/down.svg" onclick="changeVar('arrivees')" data-name="Arrivées touristiques">
                    <img class="icon" src="assets/icons/up.svg" onclick="changeVar('departs')" data-name="Départs">
                    <img class="icon" src="assets/icons/transfer.svg" onclick="changeVar('cpi')" data-name="CPI">
                    <img class="icon" src="assets/icons/leaf.svg" onclick="changeVar('Enr')" data-name="% d'énergies renouvellables">
                </div>

                <div class="table">
                    <div class="legende">
                        <div class="legende-element">
                            <div class="square bg-52796F"></div>
                            <p class="name" id="nom0"></p>
                        </div>
                        <div class="icon_name legende-element">
                            <img src="assets/icons/courbe.svg" class="square">
                            <p class="name" id="icon_name">Émissions de CO2</p>
                        </div>
                        <div class="legende-element">
                            <p class="name" id="nom1"></p>
                            <div class="square bg-83A88B"></div>
                        </div>
                    </div>

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
                    
                    <div class="container-info stretch">
                        <p>,ndicnzepidfncisjndcvisndpicnpiAKBNDOUnaepifdhnpzenf</p>
                    </div>
                </div>

            </div>

            <div class="zone-spider display" style="display:none" id="key">
                    
                <div class="graph" id="spider"></div>
                <div></div>
                <div class="cube" id="cube-1">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_cpi_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/transfer.svg">
                        <div class="top">
                            <h3>CPI</h3>
                            <p>Ce pays a obtenu un score de sûreté et de sécurité très élevé pour l'année 2023.<br>Faites attention si vous vous y rendez.</p>
                            <p>Obtenez plus d'informations auprès de <a class="lien" href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/">France Diplomacie</a> pour voyager en sécurité.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_cpi_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-2">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_pib_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/dollar.svg">
                        <div class="top">
                            <h3>PIB/Habitant</h3>
                            <p>Ce pays a obtenu un score de sûreté et de sécurité très élevé pour l'année 2023.<br>Faites attention si vous vous y rendez.</p>
                            <p>Obtenez plus d'informations auprès de <a class="lien" href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/">France Diplomacie</a> pour voyager en sécurité.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_pib_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-3">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_gpi_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/shield.svg">
                        <div class="top">
                            <h3>Global Peace Index</h3>
                            <p>Ce pays a obtenu un score de sûreté et de sécurité très élevé pour l'année 2023.<br>Faites attention si vous vous y rendez.</p>
                            <p>Obtenez plus d'informations auprès de <a class="lien" href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/">France Diplomacie</a> pour voyager en sécurité.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_gpi_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-4">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_Enr_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/leaf.svg">
                        <div class="top">
                            <h3>% d'énergies renouvellables</h3>
                            <p>Ce pays a obtenu un score de sûreté et de sécurité très élevé pour l'année 2023.<br>Faites attention si vous vous y rendez.</p>
                            <p>Obtenez plus d'informations auprès de <a class="lien" href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/">France Diplomacie</a> pour voyager en sécurité.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_Enr_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-5">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_departs_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/up.svg">
                        <div class="top">
                            <h3>Départs touristiques</h3>
                            <p>Ce pays a obtenu un score de sûreté et de sécurité très élevé pour l'année 2023.<br>Faites attention si vous vous y rendez.</p>
                            <p>Obtenez plus d'informations auprès de <a class="lien" href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/">France Diplomacie</a> pour voyager en sécurité.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_departs_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-6">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_co2_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/cloud.svg">
                        <div class="top">
                            <h3>Emissions de CO2</h3>
                            <p>Ce pays a obtenu un score de sûreté et de sécurité très élevé pour l'année 2023.<br>Faites attention si vous vous y rendez.</p>
                            <p>Obtenez plus d'informations auprès de <a class="lien" href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/">France Diplomacie</a> pour voyager en sécurité.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_co2_1"></div>
                    </div>
                </div>
                <div class="cube" id="cube-7">
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag0">
                        <div id="td_arrivees_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/down.svg">
                        <div class="top">
                            <h3>Arrivées tourstiques</h3>
                            <p>Ce pays a obtenu un score de sûreté et de sécurité très élevé pour l'année 2023.<br>Faites attention si vous vous y rendez.</p>
                            <p>Obtenez plus d'informations auprès de <a class="lien" href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/">France Diplomacie</a> pour voyager en sécurité.</p>
                            <i></i>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" id="flag1">
                        <div id="td_arrivees_1"></div>
                    </div>
                </div>

                <div class="legende-key">
                    <div class="square bg-52796F"></div>
                    <p id="nom0"></p>
                    <div class="square bg-83A88B"></div>
                    <p id="nom1"></p>
                </div>

            </div>

            <div class="zone display"  id="grow" style="display:none">

                <div class=graph id="bar"></div>
                <div class="legende">
                    <div class="square bg-52796F"></div>
                    <p >France</p>
                    <div class="square bg-83A88B"></div>
                    <p >Croatie</p>
                </div>

            </div>

            <div class="zone display" style="display:none" id="more">
            </div>

            <div class="container-bottom bg-354F52 g10-2 switch active" data-switch="home">
                <img class="flag-small" src='assets/icons/info.svg'>
                <h2 class="nom-small">Présentation</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-3 switch" data-switch="key">
                <img class="flag-small" src='assets/icons/lamp.svg'>
                <h2 class="nom-small">Indicateurs clés</h2>
            </div>

            <div class="container-bottom bg-354F52 g10-4 switch" data-switch="courbe">
                <img class="flag-small" src='assets/icons/sort.svg'>
                <h2 class="nom-small">Courbe de comparaison</h2>
            </div>

            <div class="container-bottom bg-354F52 switch g10-5" data-switch="grow">
                <img class="flag-small" src='assets/icons/stats.svg'>
                <h2 class="nom-small">Croissances</h2>
            </div>

            <div class="container-bottom bg-354F52 switch g10-6" data-switch="more">
                <img class="flag-small" src='assets/icons/plus.svg'>
                <h2 class="nom-small">Informations complémentaires</h2>
            </div>

            <script>
                $(".icon").on("click", function () {
                    $(".icon-active").removeClass("icon-active")
                    $(this).addClass("icon-active")
                    $("#icon_name").text($(this).data("name"));
                })

                $(".switch").on("click", function () {
                    $(".switch").removeClass("active")
                    $(this).addClass("active")
                    $(".display").css("display","none")
                    console.log($(this).data("switch"))
                    $("#"+$(this).data("switch")).css("display","grid")
                })
            </script>

            <script id=scripting>
                    
                spider("spider",2)
                lineCompare("line")
                barCompare("bar")

                createMiniMap(0,"compare")
                createMiniMap(1,"compare")

            </script>

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

                switch (count($pays)) {
                    case 2:
                        echo <<<HTML
                            <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'$pays[0]'}" hx-trigger="load delay:.1s"></div>
                            <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:1,id_pays:'$pays[1]'}" hx-trigger="load delay:.2s"></div>
                        HTML;
                        break;
                    
                    case 1:
                        echo <<<HTML
                            <div hx-get="scripts/htmx/getCompare.php" hx-vals="js:{incr:0,id_pays:'$pays[0]'}" hx-trigger="load delay:.1s"></div>
                        HTML;
                        break;
                    
                    case 0:
                        echo <<<HTML
                            <div hx-get="catalogue.php" hx-trigger="load" hx-select="#grid" hx-target="#grid" hx-vals="js:{page:'Compare'}" hx-swap="outerHTML swap:0.5s"></div>
                        HTML;
                        break;
                }
            ?>

        </div>
    </div>
    
</body>
</html>