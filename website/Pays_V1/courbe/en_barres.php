<?php

// Votre connexion MySQLi
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$database = "ecotourisme";
$port = 8889;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$query = "
SELECT pays.nom, ROUND(SUM(co2)) as total
FROM ecologie, pays
WHERE pays.id = ecologie.id_pays
GROUP BY id_pays  
ORDER BY `total` DESC
LIMIT 10;
";

$result = $conn->query($query);

$report_data = array();
while ($rs = $result->fetch_assoc()) {
    $report_data[] = '
    {country:' . '"' . $rs['nom'] . '"' . ',
    ' . 'value:' . $rs['total'] . '}';
}

// Concaténer les données
$report_data = implode(",", $report_data);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ECOTOURSIME</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <!-- amcharts devbanban.com -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <!-- Définir la taille du graphique -->
    <style>
        #chartdiv {
            width: 100%;
            height: 500px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10"> <br>
           
            <div id="chartdiv"></div>
            <script>
                // DEBUT SCRIPT 
                var root = am5.Root.new("chartdiv");

                // Définir les thèmes
                root.setThemes([
                    am5themes_Animated.new(root)
                ]);

                // Créer le graphique
                var chart = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: true,
                    panY: true,
                    wheelX: "panX",
                    wheelY: "zoomX"
                }));

                // Ajouter le curseur
                var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
                cursor.lineY.set("visible", false);

                // Créer les axes
                var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
                xRenderer.labels.template.setAll({
                    rotation: -90,
                    centerY: am5.p50,
                    centerX: am5.p100,
                    paddingRight: 15
                });

                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                    maxDeviation: 0.3,
                    categoryField: "country",
                    renderer: xRenderer,
                    tooltip: am5.Tooltip.new(root, {})
                }));

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    maxDeviation: 0.3,
                    renderer: am5xy.AxisRendererY.new(root, {})
                }));

                // Créer la série
                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: "Series 1",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "value",
                    sequencedInterpolation: true,
                    categoryXField: "country",
                    tooltip: am5.Tooltip.new(root, {
                        labelText:"{valueY}"
                    })
                }));

                series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5 });
                series.columns.template.adapters.add("fill", (fill, target) => {
                    return chart.get("colors").getIndex(series.columns.indexOf(target));
                });

                series.columns.template.adapters.add("stroke", (stroke, target) => {
                    return chart.get("colors").getIndex(series.columns.indexOf(target));
                });

                // Définir les données
                var data = [<?= $report_data;?>];

                xAxis.data.setAll(data);
                series.data.setAll(data);

                // Animer les éléments lors du chargement
                series.appear(1000);
                chart.appear(1000, 100);
            </script>
        </div>
    </div>
</div>

</body>
</html>
