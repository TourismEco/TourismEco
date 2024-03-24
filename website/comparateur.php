<?php require_once 'head.php'?>

<body>
    <div class="flex" id="main">

        <div id="zones">

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
                            <div hx-get="catalogue.php" hx-trigger="load" hx-select="#zones" hx-target="#zones" hx-vals="js:{page:'compare'}" hx-swap="outerHTML swap:0.5s"></div>
                        HTML;
                        break;
                    
                    case 0:
                        echo <<<HTML
                            <div hx-get="catalogue.php" hx-trigger="load" hx-select="#zones" hx-target="#zones" hx-vals="js:{page:'compare'}" hx-swap="outerHTML swap:0.5s"></div>
                        HTML;
                        break;
                }
            ?>

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

            <div class="zone display" style="display:none" id="grow">

                <div class=graph id="bar"></div>
                <!-- <div class="legende">
                    <div class="square bg-52796F"></div>
                    <p >France</p>
                    <div class="square bg-83A88B"></div>
                    <p >Croatie</p>
                </div> -->
                <div class="cube" id="cube-8">
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/FR.svg">
                        <div id="bar_pib_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/dollar.svg">
                        <div class="top">
                            <h3>PIB</h3>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/HR.svg">
                        <div id="bar_pib_1"></div>
                    </div>
                </div>

                <div class="cube" id="cube-9">
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/FR.svg">
                        <div id="bar_co2_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/cloud.svg">
                        <div class="top">
                            <h3>CO2</h3>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/HR.svg">
                        <div id="bar_co2_1"></div>
                    </div>
                </div>

                <div class="cube" id="cube-10">
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/FR.svg">
                        <div id="bar_arrivees_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/down.svg">
                        <div class="top">
                            <h3>PIB</h3>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/HR.svg">
                        <div id="bar_arrivees_1"></div>
                    </div>
                </div>

                <div class="cube" id="cube-11">
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/FR.svg">
                        <div id="bar_gpi_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/shield.svg">
                        <div class="top">
                            <h3>GPI</h3>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/HR.svg">
                        <div id="bar_gpi_1"></div>
                    </div>
                </div>

                <div class="cube" id="cube-12">
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/FR.svg">
                        <div id="bar_cpi_0"></div>
                    </div>
                    <div class="tooltip">
                        <img class="icon" src="assets/icons/transfer.svg">
                        <div class="top">
                            <h3>CPI</h3>
                        </div>
                    </div>
                    <div class="el-cube">
                        <img class="flag-tiny" src="assets/twemoji/HR.svg">
                        <div id="bar_cpi_1"></div>
                    </div>
                </div>
            </div>

            <div class="zone display" style="display:none" id="more">
            </div>
        </div>

        <div class="zone mask"></div>

        <div class="nav-bottom" id="nav-bot" hx-swap-oob="outerHTML">
            <div class="nav-categ">
                <div class="pack-categ">
                    <div class="container-bottom active page" data-index="0" data-name="Statistiques" id="s-stats">
                        <img class="flag-small" src='assets/icons/stats.svg'>
                    </div>

                    <div class="container-bottom page" data-index="1" data-name="Explorer" id="s-explore">
                        <img class="flag-small" src='assets/icons/map.svg'>
                    </div>

                    <div class="container-bottom page" data-index="2" data-name="Catalogue" id="s-catalogue" hx-get="catalogue.php" hx-select="#zones" hx-target="#zones" hx-trigger="click" hx-vals="js:{page:'Compare'}" hx-swap="outerHTML swap:0.5s">
                        <img class="flag-small" src='assets/icons/catalogue.svg'>
                    </div>

                    <div id="trans-page" class="active-bg"></div>
                </div>

                <div class="nav-trait"></div>

                <div id="name-page" class="nav-text">Statistiques</div>
            </div>

            <div class="nav-categ">
                <div class="pack-categ">
                    <?php
                        if (isset($_SESSION["pays"][0])) {
                            $id_pays = $_SESSION["pays"][0];
                            echo <<<HTML
                                <img class="flag-small" id="flag-bot0" src='assets/twemoji/$id_pays.svg'>
                            HTML;
                        } else {
                            echo <<<HTML
                                <img class="flag-small" id="flag-bot0" src='assets/icons/question.svg'>
                            HTML;
                        }

                        if (isset($_SESSION["pays"][1])) {
                            $id_pays = $_SESSION["pays"][1];
                            echo <<<HTML
                                <img class="flag-small" id="flag-bot1" src='assets/twemoji/$id_pays.svg'>
                            HTML;
                        } else {
                            echo <<<HTML
                                <img class="flag-small" id="flag-bot1" src='assets/icons/question.svg'>
                            HTML;
                        }
                    ?>
                </div>

                <div class="nav-trait"></div>

                <div class="pack-categ">
                    <div class="container-bottom active switch" data-switch="home" data-index="0" data-name="Présentation">
                        <img class="flag-small" src='assets/icons/info.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="key" data-index="1" data-name="Indicateurs clés">
                        <img class="flag-small" src='assets/icons/lamp.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="courbe" data-index="2" data-name="Courbes d'évolution">
                        <img class="flag-small" src='assets/icons/sort.svg'>
                    </div>

                    <div class="container-bottom switch" data-switch="grow" data-index="3" data-name="Croissances">
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
        </div>

        <script id="scripting" hx-swap-oob="outerHTML">
            spider("spider",2)
            lineCompare("line")
            barCompare("bar")

            createMiniMap(0,"compare")
            createMiniMap(1,"compare")
        </script>

        <script id="behave" hx-swap-oob="outerHTML">
            $(".icon").on("click", function () {
                $(".icon-active").removeClass("icon-active")
                $(this).addClass("icon-active")
                $("#icon_name").text($(this).data("name"));
            })

            $(".switch").on("click", function () {
                $(".switch").removeClass("active")
                $(this).addClass("active")
                $(".display").css("display","none")

                $("#"+$(this).data("switch")).css("display","grid")
                nb = $(this).data("index")*53
                $("#trans").css("transform","translateX("+nb+"px)")
                $("#name-switch").html($(this).data("name"))
            })

            $(".page").removeClass("active")
            $("#s-stats").addClass("active")
            $("#name-page").text("Comparateur");

            nb = 0
            $("#trans-page").css("transform","translateX("+nb+"px)")
            
        </script>

    </div>
    
</body>
</html>