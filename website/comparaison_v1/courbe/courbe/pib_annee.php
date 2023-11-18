<style>
  #chartdiv {
    /*width: 100%;
    height: 100%;
    background-color: #f2f2f2;*/
  }
  </style>
<?php
// Votre connexion MySQLi
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "Ecotourisme";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$query = "
SELECT annee,pib/1000000 as pib FROM pib Where id_pays ='FR';";

$result = $conn->query($query);

$report_data = array();
while ($rs = $result->fetch_assoc()) {
    $report_data[] = array(
        "year" => $rs['annee'], // Renommez "country" en "year"
        "value" => $rs['pib']
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
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {
// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  wheelX: "panX",
  wheelY: "zoomX",
  pinchZoomX: true
}));


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
  behavior: "none"
}));
cursor.lineY.set("visible", false);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0.2,
  categoryField: "year", // Utilisez "year" comme le champ de catégorie
  renderer: am5xy.AxisRendererX.new(root, {}),
  tooltip: am5.Tooltip.new(root, {})
}));

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: am5xy.AxisRendererY.new(root, {
    pan: "zoom"
  }),
  min: 0, // Définissez la valeur minimale de l'axe Y
  max: 3850000, // Définissez la valeur maximale de l'axe Y
}));

// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.LineSeries.new(root, {
  name: "Series",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  categoryXField: "year", // Utilisez "year" comme le champ de catégorie
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


// Définir les données
var data = <?php echo json_encode($report_data); ?>;
xAxis.data.setAll(data);
series.data.setAll(data);

// Animer les éléments lors du chargement
series.appear(1000);
chart.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>
