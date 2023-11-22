<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoTourism - Comparaison</title>
    <link rel="stylesheet" href="styles-bandeau.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Base -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Map -->
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
    

    <!-- Graph -->
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="../assets/js/graph.js"></script>

    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="../assets/js/spiderCompare.js"></script>

    <script src="../assets/js/Clustered_Column_Chart.js"></script>


    <script src="../assets/js/map.js"></script>

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
                <select name="$arg" id="$arg">  
                <optgroup label="Océanie">
            HTML;

            $tmp = 6;
            $continents = array(1=>"Afrique",2=>"Amérique du Nord",3=>"Amérique du Sud",4=>"Asie",5=>"Europe",6=>"Océanie");

            while ($rs = $sth->fetch()) {
                if ($tmp != $rs["id_continent"]) {
                    $tmp = $rs["id_continent"];
                    echo <<<HTML
                        </optgroup>
                        <optgroup label="$continents[$tmp]">
                    HTML;
                }
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
                </optgroup>
                </select>  
            HTML;
        }

        $cur = getDB();

        $query = "SELECT * FROM pays ORDER BY id_continent DESC, nom ASC";
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
            </form>
            <div class="container-map">
                <div id="map"></div>
            </div>

            <script>
                createMap(fun=compare,args=['$pays1','$pays2'])
            </script>

            <div id="bandeau">
        HTML;

        $a= array();

        foreach (array($pays1,$pays2) as $key => $id_pays) {
            
            $query = "SELECT * FROM pays WHERE id = :id_pays";
            $sth = $cur->prepare($query);
            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
            $sth->execute();

            $ligne = $sth->fetch();
            echo <<<HTML
                <div class="bandeau-container" id="bandeau$key">     
                <img class="img" src='../assets/img/$ligne[id].jpg' alt="Bandeau">
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
                        <p id=$arg class="stat">$ligne[$arg]</p>
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

        $query = "
        SELECT eco1.annee, eco1.co2 as eco1, eco2.co2 as eco2
        FROM ecologie as eco1, ecologie as eco2
        WHERE eco1.id_pays = '$pays1'
        AND eco2.id_pays = '$pays2'
        AND eco1.annee = eco2.annee;
        ";

        $result = $cur->query($query);
        $dataLine = array();

        while ($rs = $result->fetch()) {
            $dataLine[] = <<<END
                {year:'{$rs['annee']}',
                    value:{$rs['eco1']},
                    value2:{$rs['eco2']},
                }
            END;
        }

        $dataLine = implode(",", $dataLine);

        $query2 = "
        SELECT eco1.annee, eco1.pibParHab as eco1, eco2.pibParHab as eco2
        FROM economie as eco1, economie as eco2
        WHERE eco1.id_pays = '$pays1'
        AND eco2.id_pays = '$pays2'
        AND eco1.annee = eco2.annee;
        ";

        $result2 = $cur->query($query2);

        $dataBar = array();
        while ($rs = $result2->fetch()) {
            foreach (array('eco1','eco2') as $key => $value) {
                if (!isset($rs[$value])){
                    $rs[$value]=0;
                } 
            } 
            $dataBar[] = <<<END
                {year:'{$rs['annee']}',
                    value:{$rs['eco1']},
                    value2:{$rs['eco2']},
                }
            END;
        }

        $dataBar = implode(",", $dataBar);

    ?>

    <div class=score></div>

    <div class="container-spider">
        <h2 id=t1>Spider plot</h1>
        <div class= "flex">
            <div id="spider"></div>
            <script>
                console.log({<?=$dataSpider1?>})
                spider({<?=$dataSpider1?>}, {<?=$dataSpider2?>} ,"<?=$a[0]?>","<?=$a[1]?>")
            </script>
            <p class=p50>Actuellement le [pays 1] est au dessus du [pays 2], montrant que [pays 1] pollue plus que [pays 2]. Au cours du temps on peut voir que le tourisme ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. Curabitur et felis felis. Donec vel nulla malesuada, tempor nisi in, faucibus nulla. Cras at ipsum tempor, rutrum sapien ut, auctor sapien.
            Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. </p>
        </div>
    </div>

    <div class="container-stats">
        <h2 id=t1>Courbe de comparaison</h2>
        <div class= "flex">
            <p class=p50>Actuellement le [pays 1] est au dessus du [pays 2], montrant que [pays 1] pollue plus que [pays 2]. Au cours du temps on peut voir que le tourisme ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. Curabitur et felis felis. Donec vel nulla malesuada, tempor nisi in, faucibus nulla. Cras at ipsum tempor, rutrum sapien ut, auctor sapien.
            Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. </p>
            <div id="chartdiv"></div>
            <script>
                console.log([<?=$dataLine?>])
                createGraph([<?=$dataLine?>],"<?=$a[0]?>","<?=$a[1]?>")
            </script>
        </div>
    </div>

    <div class="container-stats" style="background-color:#183A37">
        <h2 id=t1>Barres</h2>
        <div class= "flex">
            <div id="bar"></div>
            <p class=p50>Actuellement le [pays 1] est au dessus du [pays 2], montrant que [pays 1] pollue plus que [pays 2]. Au cours du temps on peut voir que le tourisme ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. Curabitur et felis felis. Donec vel nulla malesuada, tempor nisi in, faucibus nulla. Cras at ipsum tempor, rutrum sapien ut, auctor sapien.
            Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. </p>
            
            <script>
                graphBar([<?=$dataBar?>],"<?=$a[0]?>","<?=$a[1]?>")
            </script>
        </div>
    </div>
    
    
</body>
</html>

