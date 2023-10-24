<?php
// Votre connexion MySQLi
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$database = "Ecotourisme";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$query = "
SELECT annee, depenses, recettes FROM `argent`where id_pays='DE';
";

$result = $conn->query($query);

$report_data = array();
while ($rs = $result->fetch_assoc()) {
    $report_data[] = array(
        "year" => $rs['annee'],
        "value" => $rs['depenses'],
        "value2" => $rs['recettes'], // Modifiez ceci pour utiliser la valeur correcte
    );
}

// Fermez la connexion à la base de données
$conn->close();
?>

<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}

body{
  background-color: #354F52;
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
  max: 200000, // Définissez la valeur maximale de l'axe Y
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
  }),
  stroke: am5.color("#AA0000") 
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
  }),
  stroke: am5.color("#BB5C00") 
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
<div id="chartdiv"></div>
