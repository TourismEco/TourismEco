<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoTourism - Comparaison</title>
    <link rel="stylesheet" href="styles/styles-bandeau.css">

    <!-- Base -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Map -->
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
    <script src="../assets/js/map.js"></script>

    <!-- Graph -->
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="../assets/js/graph.js"></script>

    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="../assets/js/spiderCompare.js"></script>

</head>

<body>
    <?php
        require("../functions.php");

        function dataSpider($pays) {
            $conn = getDB();

            $query = "
            SELECT ecologie.annee as annee,
            economie.pibParHab as pib,
            ecologie.elecRenew * 1000 as Enr,
            ecologie.co2/10000 as co2, 
            tourisme.arriveesTotal as arrivees, 
            tourisme.departs as departs, 
            surete.gpi * 10000 as gpi, 
            economie.cpi as cpi

            FROM ecologie, economie, tourisme, surete
            WHERE ecologie.id_pays = economie.id_pays
            AND economie.id_pays = tourisme.id_pays
            AND tourisme.id_pays = surete.id_pays
            AND surete.id_pays = '$pays'

            AND ecologie.annee = economie.annee
            AND economie.annee = tourisme.annee
            AND tourisme.annee = surete.annee  
            ORDER BY `ecologie`.`annee` DESC;
            ";

            $result = $conn->query($query);

            $data = array();
            while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
                foreach (array("pib","Enr","co2","arrivees","departs","gpi","cpi") as $key => $value) {
                    if (!isset($rs[$value])){
                        $rs[$value]=0;
                    } 
                }
                
                $data[] = <<<END
                    $rs[annee]:
                        [{"var":"PIB","value":$rs[pib],"val2":100},
                        {"var":"% renew","value":$rs[Enr],"val2":100},
                        {"var":"CO2","value":$rs[co2],"val2":100},
                        {"var":"Arrivées","value":$rs[arrivees],"val2":100},
                        {"var":"Départs","value":$rs[departs],"val2":100},
                        {"var":"GPI","value":$rs[gpi],"val2":100},
                        {"var":"CPI","value":$rs[cpi],"val2":100}]

                END;
            }

            return implode(",", $data);
        }

        function getPays($arg, $default) {
            if (isset($_GET[$arg])) {
                return $_GET[$arg];
            } else {
                return $default;
            }
        }

        function createSelect($sth, $arg, $pays) {
            $sth -> execute();

            echo <<<HTML
                <select name="$arg" id="$arg" onchange="changeComp1()">  
            HTML;

            while ($rs = $sth->fetch()) {
                if ($rs["id"] == $pays) {
                    echo <<<HTML
                        <option value=$rs[id] selected>$rs[nom]</option>
                    HTML;
                } else {
                    echo <<<HTML
                        <option value=$rs[id]>$rs[nom]</option>
                    HTML;
                }
            }

            echo <<<HTML
                </select>  
            HTML;
        }

        $cur = getDB();

        $query = "SELECT * FROM pays ORDER BY nom ASC";
        $sth = $cur -> prepare($query);

        $pays1 = getPays("pays1", "FR");
        $pays2 = getPays("pays2", "JP");

        echo <<<HTML
            <form method="get">
        HTML;

        createSelect($sth, "pays1", $pays1);
        createSelect($sth, "pays2", $pays2);

        echo <<<HTML
            <input type="submit" value="maj variable">
            <div class="container-map">
                <div id="map"></div>
            </div>

            <script>
                createMap(fun=compare,args=['$pays1','$pays2'])
            </script>

            <div id="bandeau">
        HTML;

        $a= array();
        $pic = array("test1.jpg","berlin.jpeg");

        foreach (array($pays1,$pays2) as $key => $id_pays) {
            
            $query = "SELECT * FROM pays WHERE id = :id_pays";
            $sth = $cur->prepare($query);
            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
            $sth->execute();

            $ligne = $sth->fetch();
            echo <<<HTML
                <div class="bandeau-container">     
                <img class="img" src='../$pic[$key]' alt="Bandeau">
                <img class="flag" src='../assets/twemoji/$ligne[id].svg'>
                <h1 class="nom">$ligne[nom]</h1>
            HTML;

            $a[]=$ligne["nom"];
            
            $query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
            $sth = $cur->prepare($query);
            $is_capitale = 1;
            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
            $sth->bindParam(":is_capitale", $is_capitale, PDO::PARAM_INT);
            $sth->execute();

            $ligne = $sth->fetch();
            echo <<<HTML
                <p class="capital">Capitale : $ligne[nom]</p>
            HTML;

            $search = array(array("tourisme","arriveesTotal","Arrivées"), array("ecologie","co2","CO2"), array("economie","pibParHab","PIB/Hab"), array("surete","gpi","Indice de sureté"));

            foreach ($search as $key => $value) {
                $table = $value[0];
                $arg = $value[1];
                $text = $value[2];
                $query = "SELECT ".$arg." FROM ".$table." WHERE id_pays = :id_pays AND ".$arg." IS NOT NULL ORDER BY Annee DESC";
                $sth = $cur->prepare($query);
                $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                $sth->execute();

                $ligne = $sth->fetch();
                echo <<<HTML
                    <div class='infos'>
                        <p>$text</p>
                        <p class="stat">$ligne[$arg]</p>
                    </div>
                HTML;
            }
            
            echo <<<HTML
                 </div>
            HTML;
        }

        echo <<<HTML
            </div>
        HTML;

        $dataSpider1 = dataSpider($pays1);
        $dataSpider2 = dataSpider($pays2);

    ?>

    <div class=score></div>

    <div class="container-stats">
        <h2 id=t1>Courbe de comparaison</h1>
        <div class= "flex">
            <p class=p50>Actuellement le [pays 1] est au dessus du [pays 2], montrant que [pays 1] pollue plus que [pays 2]. Au cours du temps on peut voir que le tourisme ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. Curabitur et felis felis. Donec vel nulla malesuada, tempor nisi in, faucibus nulla. Cras at ipsum tempor, rutrum sapien ut, auctor sapien.
            Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. </p>
            <div id="chartdiv"></div>
            <?php
                // Votre connexion MySQLi

                $conn = getDB();

                $query = "
                SELECT eco1.annee, eco1.co2 as eco1, eco2.co2 as eco2
                FROM ecologie as eco1, ecologie as eco2
                WHERE eco1.id_pays = '$pays1'
                AND eco2.id_pays = '$pays2'
                AND eco1.annee = eco2.annee;
                ";

                
                $result = $conn->query($query);

                $data = array();
                while ($rs = $result->fetch()) {
                    $data[] = <<<END
                        {year:'{$rs['annee']}',
                            value:{$rs['eco1']},
                            value2:{$rs['eco2']},
                        }
                    END;
                }

                $data = implode(",", $data);

            ?>

            <script>
                console.log([<?=$data?>])
                createGraph([<?=$data?>],"<?=$a[0]?>","<?=$a[1]?>")
            </script>
        </div>
    </div>

    <div id="spider"></div>
    <script>
        console.log({<?=$dataSpider1?>})
        spider({<?=$dataSpider1?>}, {<?=$dataSpider2?>} ,"<?=$a[0]?>","<?=$a[1]?>")
    </script>
</body>
</html>

