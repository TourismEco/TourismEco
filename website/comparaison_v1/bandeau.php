<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Page Web</title>
    <link rel="stylesheet" href="styles/styles-bandeau.css">
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
        
    <script src="../assets/js/map.js"></script>
</head>
<style>
  #map {
        width: 100%;
        height: 400px;
        background-color: #354F52;
    }
</style>



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
    HTML;
?>

<body>
    <div id="map"></div>

    <script>
        console.log("kek")
        createMap(fun=compare,args=["FR","CA"])
    </script>

    <header>
        <div id="bandeau">
        <div id="background">
                <div id="bandeau-container">              
                    <img class="img" src="../paris3.jpg" alt="Bandeau">

                    <?php
                        // Connexion à la base de données en utilisant la fonction getDB() du fichier functions.php
                    require_once "../functions.php";

                        $conn = getDB();

                        $query = "SELECT * FROM pays WHERE id = :id_pays";
                        $id_pays = "FR";
                        $sth = $conn->prepare($query);
                        $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                        $sth->execute();

                        $ligne = $sth->fetch();
                        echo <<<HTML
                            <div class= one>
                            <h1>$ligne[nom]</h1>
                            </div>
                            <div class= logo>
                                <img src='../assets/twemoji/$ligne[id].svg'>
                            </div>
        HTML;
                        
                        $query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
                        $sth = $conn->prepare($query);
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
                        
                        $query = "SELECT arriveesTotal FROM tourisme WHERE id_pays = :id_pays and annee = :annee";
                        $sth = $conn->prepare($query);
                        $annee = 2021;
                        $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                        $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                        $sth->execute();

                        $ligne = $sth->fetch();
                        echo <<<HTML
                            <p class='two'>Arrrivees : $ligne[arriveesTotal]</p>
        HTML;
                                        
                            $query = "SELECT * FROM ecologie WHERE id_pays = :id_pays and annee = :annee";
                            $sth = $conn->prepare($query);
                            $annee = 2020;
                            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                            $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                            $sth->execute();

                            while ($ligne = $sth->fetch()) {
                                echo <<<HTML
                                <p class='three'>CO2 : $ligne[co2]</p>
        HTML;
                            }
                        
                            $query = "SELECT pibParHab FROM economie WHERE id_pays = :id_pays and annee = :annee";
                            $sth = $conn->prepare($query);
                            $annee = 2021;
                            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                            $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                            $sth->execute();
                            while ($ligne = $sth->fetch()) {
                                echo <<<HTML
                                <p class='four'>PIB : $ligne[pibParHab]</p>
        HTML;
                            }

                            $query = "SELECT gpi FROM surete WHERE id_pays = :id_pays and annee = :annee";
                            $sth = $conn->prepare($query);
                            $annee = 2023;
                            $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                            $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                            $sth->execute();

                            while ($ligne = $sth->fetch()) {
                                echo <<<HTML
                                <p class='five'>GPI: $ligne[gpi]</p>
        HTML;
                            }
                        ?>
                </div>
            </div>
            
            <div id="bandeau-container-2">
                <img class="img-2" src="../normal_La_Tour_de_Tokyo.jpg" alt="Bandeau">

                <?php
                    // Connexion à la base de données en utilisant la fonction getDB() du fichier functions.php
                require_once "../functions.php";

                    $conn = getDB();

                    $query = "SELECT * FROM pays WHERE id = :id_pays";
                    $id_pays = "JP";
                    $sth = $conn->prepare($query);
                    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                    $sth->execute();

                    $ligne = $sth->fetch();
                    echo <<<HTML
                        <div class= one>
                        <h1>$ligne[nom]</h1>
                        </div>
                        <div class= logo>
                            <img src='../assets/twemoji/$ligne[id].svg'>
                        </div>
    HTML;
                    
                    $query = "SELECT * FROM villes WHERE id_pays = :id_pays and capitale = :is_capitale";
                    $sth = $conn->prepare($query);
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
                    
                    $query = "SELECT arriveesTotal FROM tourisme WHERE id_pays = :id_pays and annee = :annee";
                    $sth = $conn->prepare($query);
                    $annee = 2021;
                    $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                    $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                    $sth->execute();

                    $ligne = $sth->fetch();
                    echo <<<HTML
                        <p class='two'>Arrrivees : $ligne[arriveesTotal]</p>
    HTML;
                                    
                        $query = "SELECT * FROM ecologie WHERE id_pays = :id_pays and annee = :annee";
                        $sth = $conn->prepare($query);
                        $annee = 2020;
                        $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                        $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                        $sth->execute();

                        while ($ligne = $sth->fetch()) {
                            echo <<<HTML
                            <p class='three'>CO2 : $ligne[co2]</p>
    HTML;
                        }
                    
                        $query = "SELECT pibParHab FROM economie WHERE id_pays = :id_pays and annee = :annee";
                        $sth = $conn->prepare($query);
                        $annee = 2021;
                        $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                        $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                        $sth->execute();
                        while ($ligne = $sth->fetch()) {
                            echo <<<HTML
                            <p class='four'>PIB : $ligne[pibParHab]</p>
    HTML;
                        }

                        $query = "SELECT gpi FROM surete WHERE id_pays = :id_pays and annee = :annee";
                        $sth = $conn->prepare($query);
                        $annee = 2023;
                        $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
                        $sth->bindParam(":annee", $annee, PDO::PARAM_INT);
                        $sth->execute();

                        while ($ligne = $sth->fetch()) {
                            echo <<<HTML
                            <p class='five'>GPI: $ligne[gpi]</p>
    HTML;
                        }
                ?>
            </div>
        </div>
        <div class=score></div>
    </header>
    <main>
        <h2 id=t1>Courbe de comparaison</h1>
        <div class= "flex">
            <p class=p50>Actuellement le [pays 1] est au dessus du [pays 2], montrant que [pays 1] pollue plus que [pays 2]. Au cours du temps on peut voir que le tourisme ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. Curabitur et felis felis. Donec vel nulla malesuada, tempor nisi in, faucibus nulla. Cras at ipsum tempor, rutrum sapien ut, auctor sapien.
            Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. Proin mattis elementum euismod. </p>
            <div id="chartdiv"></div>
            <?php
                // Votre connexion MySQLi
                $servername = "127.0.0.1";
                $username = "root";
                $password = "";
                $database = "ecotourisme";
                $port = 3306;

                $conn = new mysqli($servername, $username, $password, $database, $port);

                if ($conn->connect_error) {
                    die("Erreur de connexion à la base de données : " . $conn->connect_error);
                }

                $query = "
                SELECT eco1.annee, eco1.co2 as eco1, eco2.co2 as eco2
                FROM ecologie as eco1, ecologie as eco2
                WHERE eco1.id_pays = 'FR'
                AND eco2.id_pays = 'JP'
                AND eco1.annee = eco2.annee;
                ";

                $result = $conn->query($query);

                $report_data = array();
                while ($rs = $result->fetch_assoc()) {
                    $report_data[] = array(
                        "year" => $rs['annee'],
                        "value" => $rs['eco1'],
                        "value2" => $rs['eco2'], // Modifiez ceci pour utiliser la valeur correcte
                    );
                }

                // Fermez la connexion à la base de données
                $conn->close();
            ?>

            <!-- Styles -->
            <style>
                #chartdiv {
                    width: 50%;
                    height: 500px;
                    }

            </style>
            <!-- Resources -->
            <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

            <!-- Chart code -->
            <script>
                am5.ready(function() {

                // Create root element
                var root = am5.Root.new("chartdiv");

                // Set themes
                root.setThemes([
                am5themes_Animated.new(root)
                ]);

                // Create chart
                var chart = root.container.children.push(am5xy.XYChart.new(root, {
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true
                }));

                chart.get("colors").set("step", 3);

                // Add cursor
                var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "none"
                }));
                cursor.lineY.set("visible", false);

                // Create axes
                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                maxDeviation: 0.2,
                categoryField: "year",
                renderer: am5xy.AxisRendererX.new(root, {}),
                tooltip: am5.Tooltip.new(root, {})
                }));

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {
                    pan: "zoom"
                }),
                min: 0, // Définissez la valeur minimale de l'axe Y
                max: 1000000, // Définissez lsa valeur maximale de l'axe Y
                }));

                // Add series 1
                var series = chart.series.push(am5xy.LineSeries.new(root, {
                name: "Series 1",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                categoryXField: "year",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{valueY}"
                })
                }));

                // Add series 2
                var series2 = chart.series.push(am5xy.LineSeries.new(root, {
                name: "Series 2",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value2",
                categoryXField: "year",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{valueY}"
                })
                }));


                series.bullets.push(function() {
                var graphics = am5.Circle.new(root, {
                    radius: 4,
                    interactive: true,
                    cursorOverStyle: "ns-resize",
                    stroke: series.get("stroke"),
                    fill: am5.color(0xffffff)
                });

                return am5.Bullet.new(root, {
                    sprite: graphics
                });
                });

                // Add scrollbar
                // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
                chart.set("scrollbarX", am5.Scrollbar.new(root, {
                orientation: "horizontal"
                }));


                var data = <?php echo json_encode($report_data); ?>;


                xAxis.data.setAll(data);
                series.data.setAll(data);
                series2.data.setAll(data);

                // Make stuff animate on load
                series.appear(1000);
                series2.appear(1000);
                chart.appear(1000, 100);

                }); // end am5.ready()
            </script>

            <!-- HTML -->

            <p></p>
            <p></p>
        </div>
    </main>
</body>
</html>

