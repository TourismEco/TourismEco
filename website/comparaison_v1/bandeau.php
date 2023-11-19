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
    
</head>

<body>
    <?php
        require("../functions.php");
        $cur = getDB();

        $query = "SELECT * FROM pays ORDER BY nom ASC";
        $sth = $cur -> prepare($query);
        $sth -> execute();

        if (isset($_GET["pays1"])) {
            $pays1 = $_GET["pays1"];
        } else {
            $pays1 = "FR";
        }

        if (isset($_GET["pays2"])) {
            $pays2 = $_GET["pays2"];
        } else {
            $pays2 = "JP";
        }

        echo <<<HTML
            <select name="pays1" id="pays1" onchange="changeComp1()">  
        HTML;

        while ($rs = $sth->fetch()) {
            if ($rs["id"] == $pays1) {
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

        $sth -> execute();

        echo <<<HTML
            <select name="pays2" id="pays2" onchange="changeComp2()">  
        HTML;

            while ($rs = $sth->fetch()) {
                if ($rs["id"] == $pays2) {
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
            <div class="container-map">
                <div id="map"></div>
            </div>

            <script>
                createMap(fun=compare,args=["FR","JP"])
            </script>

            <div id="bandeau">
        HTML;

        foreach (array($pays1,$pays2) as $key => $id_pays) {
            echo <<<HTML
                <div class="bandeau-container">              
                <img class="img" src="../paris4.jpg" alt="Bandeau">
            HTML;
            
            $query = "SELECT * FROM pays WHERE id = :id_pays";
            $sth = $cur->prepare($query);
            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
            $sth->execute();

            $ligne = $sth->fetch();
            echo <<<HTML
                <div class= nom>
                <h1>$ligne[nom]</h1>
                </div>
                <div class= logo>
                    <img src='../assets/twemoji/$ligne[id].svg'>
                </div>
            HTML;
            
            $query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
            $sth = $cur->prepare($query);
            $is_capitale = 1;
            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
            $sth->bindParam(":is_capitale", $is_capitale, PDO::PARAM_INT);
            $sth->execute();

            $ligne = $sth->fetch();
            echo <<<HTML
                <div class= 'capital'>
                    <p >Capital : $ligne[nom]</p>
                </div>
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
                    <p class='infos'>$text : $ligne[$arg]</p>
                HTML;
            }
            
            echo <<<HTML
                 </div>
            HTML;
        }

        echo <<<HTML
            </div>
        HTML;
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
                WHERE eco1.id_pays = 'FR'
                AND eco2.id_pays = 'JP'
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
                createGraph([<?=$data?>])
            </script>
        </div>
    </div>
</body>
</html>

